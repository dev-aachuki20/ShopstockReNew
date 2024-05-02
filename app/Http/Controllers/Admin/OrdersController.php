<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\DataTables\DraftInvoiceDataTable;
use App\Models\Order;
use App\Http\Requests\Order\StoreOrdersRequest;
use App\Http\Requests\Order\UpdateProductRequest;
use App\Http\Requests\Order\UpdateOrdersRequest;
use App\Models\PaymentTransaction;
use App\Models\Product;
use App\Models\OrderProduct;
use App\Models\CustomerGroup;
use App\Models\OrderEditHistory;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use PDF;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(Gate::denies('estimate_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $orderType = 'create';
        $customers = Customer::select('id', 'name', 'is_type', 'credit_limit')->orderBy('name', 'asc')->pluck('name', 'id')->prepend(trans('admin_master.g_please_select'), '');
        // $products  = Product::select('id', 'name', 'price', 'group_id')->orderBy('name', 'asc')->pluck('name', 'id')->prepend(trans('admin_master.g_please_select'), '');

        $products = Product::select('id', 'name', 'price', 'group_id', 'calculation_type', 'is_sub_product')
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.orders.create', compact('customers', 'products', 'orderType'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrdersRequest $request)
    {
        abort_if(Gate::denies('estimate_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // dd($request->all());
        try{
            DB::beginTransaction();
            $isDraft = $request->submit == 'draft' ? true : false;

            $checkOrder = Order::where(['customer_id' => $request->customer_id, 'created_at' =>  now()->toDateString(), 'invoice_date' => $request->invoice_date, 'is_draft' => $isDraft, 'order_type' => $request->order_type])->first();

            if (!$checkOrder) {
                $invoiceNumber = $request->order_type == 'return' ? getNewInvoiceNumber('', 'return',$request->invoice_date) : getNewInvoiceNumber('', 'new',$request->invoice_date);
                $inputs = array(
                    'customer_id'    => $request->customer_id,
                    'order_type'     => $request->order_type,
                    'invoice_number' => $invoiceNumber,
                    'area_id'        => $request->area_id,
                    'invoice_date'   => $request->invoice_date,
                    'shipping_amount' => (float)str_replace(',', '', $request->shipping_amount) ?? null,
                    'total_amount'   => $isDraft ? 0.00 : round((float)str_replace(',', '', $request->total_amount)),
                    'remark'         => $request->remark,
                    'sold_by'        => $request->sold_by,
                    'created_by'     => Auth::user()->id,
                    'is_draft'       => $isDraft ? 1 : 0,
                    'is_add_shipping' => $request->is_add_shipping == 'on' ? 1 : 0
                );
                $order = Order::create($inputs);
                addToLog($request, 'Order', 'Create', $order);

            } else {
                $shippingAmount = (float)str_replace(',', '', $request->shipping_amount) ?? 0.00;
                $totalAmount = round((float)str_replace(',', '', $request->total_amount));
                $checkOrder->update([
                    'shipping_amount' => $checkOrder->shipping_amount ? ((float)$checkOrder->shipping_amount + $shippingAmount) : null,
                    'total_amount'   => $isDraft ? 0.00 : ((float)$checkOrder->total_amount + $totalAmount),
                    'remark'         => $request->remark,
                    'sold_by'        => $request->sold_by,
                    'is_add_shipping' => $request->is_add_shipping == 'on' ? 1 : 0
                ]);
                $order = $checkOrder;
            }

            $orderProducts = $request->get('products');
            $allOrderProducts = array();
            foreach ($orderProducts as $oProduct) {
                $createOrderProduct = [
                    'product_id' => $oProduct['product_id'],
                    'quantity'   => $oProduct['quantity'],
                    'price'      => (float)str_replace(',', '', $oProduct['price']) ?? null,
                    'height'     => $oProduct['height'] ?? null,
                    'width'      => $oProduct['width'] ?? null,
                    'length'     => $oProduct['length'] ?? null,
                    'is_draft'   => $oProduct['is_draft'] ?? 0,
                    'description'  => (isset($oProduct['description']) && !empty($oProduct['description'])) ?  $oProduct['description'] : null,
                    'other_details'  => isset($oProduct['other_details']) ? json_encode(json_decode($oProduct['other_details'], true)) : null,
                    'is_sub_product'  => (isset($oProduct['is_sub_product_value']) && !empty($oProduct['is_sub_product_value'])) ?  $oProduct['is_sub_product_value'] : null,
                    'total_price'   => round((float)str_replace(',', '', $oProduct['total_price'])) ?? 0.00,
                ];
                $allOrderProducts[] = $createOrderProduct;
            }
            if (count($allOrderProducts) > 0) {
                $createdProducts = $order->orderProduct()->createMany($allOrderProducts);
                $createdProductIds = $createdProducts->pluck('id')->toArray();
            }

            if (!$isDraft) {
                $checkPaymentTransaction = PaymentTransaction::where('order_id', $order->id)->first();
                if (!$checkPaymentTransaction) {
                    $transaction = [
                        'order_id'      => $order->id,
                        'customer_id'   => $order->customer_id,
                        'payment_type'  => ($order->order_type == 'return') ? 'credit' : 'debit',
                        'payment_way'   => 'order_' . $order->order_type,
                        'voucher_number' => $invoiceNumber ?? $order->invoice_number,
                        'amount'        => round((float)str_replace(',', '', $order->total_amount)),
                        'created_by'    => Auth::user()->id,
                        'entry_date'    => date('Y-m-d', strtotime($request->invoice_date)),
                        'remark'        => $order->order_type == 'return' ? 'Sales return' : 'Sales',
                    ];
                    PaymentTransaction::create($transaction);
                    addToLog($request, 'Estimate', 'Create', $transaction);
                } else {
                    // 'amount'        => round((float)str_replace(',', '', $order->total_amount)) + (float)$checkPaymentTransaction->amount
                    $checkPaymentTransaction->update([
                        'amount'        => round((float)str_replace(',', '', $order->total_amount))
                    ]);
                }
            }

            if (!$isDraft && count($allOrderProducts) > 0) {
                $requestData =  $request->all();
                $requestData['products'] = orderProduct::where('order_id',$order->id)->get()->toArray();
                $this->recordOrderHistory($order, $requestData,null,$createdProductIds);  //Check History
            }
            $invoiceNumberIs = $invoiceNumber ?? $order->invoice_number;

            $checkOrder ? $order->update(['is_modified' => 1]) : '';

            DB::commit();

            if ($order->order_type == 'return') {
                return response()->json([
                    'success' => true,
                    'message'     => 'Successfully created!',
                    'invoiceNumber'     => 'Invoice No. '.$invoiceNumberIs,
                    // 'printPdfUrl' => route('admin.orders.printPdf', encrypt($order->id)),
                    'redirectUrl' => route('admin.orders.return'),
                ], 200);
            }

            return response()->json([
                'success' => true,
                'message'     => 'Successfully created!',
                'invoiceNumber'     => 'Invoice No. '.$invoiceNumberIs,
                // 'printPdfUrl' => route('admin.orders.printPdf',encrypt($order->id)),
                'redirectUrl' => route('admin.orders.create'),
            ], 200);

        }catch(\Exception $e){
            //dd($e->getMessage(),$e->getCode(),$e->getLine() );
            DB::rollBack();
            \Log::error("Error in OrdersController::store (".$e->getCode(). ")" . $e->getMessage() . " at line " . $e->getLine());

            return response()->json(['success' => false , 'message'=>'error'],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        abort_if(Gate::denies('estimate_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            $id = decrypt($id);
            $order = Order::withTrashed()->find($id);
            $type = $request->type ?? 'sales';
            $html = View::make('admin.orders.prev_order_modal', compact('order', 'type'))->render();
            return response()->json(['success' => true, 'html' => $html]);
        }
    }

    public function showHistory($type=null,string $id)
    {
        abort_if(Gate::denies('estimate_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $id = decrypt($id);
        $order= Order::findorfail($id);
        $order->update(['is_modified' => 0]);
        $allOrderHistory = OrderEditHistory::where('order_id', $id)->get();
        $html = View::make('admin.orders.prev_order_history_modal', compact('allOrderHistory','order','type'))->render();
        return response()->json(['success' => true, 'html' => $html]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($type, string $id)
    {
        abort_if(Gate::denies('estimate_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $orderType = 'edit';
        $id = decrypt($id);
        $order = Order::findOrFail($id);
        $customers = Customer::select('id', 'name', 'is_type', 'credit_limit')->orderBy('name', 'asc')->pluck('name', 'id')->prepend(trans('admin_master.g_please_select'), '');
        $products = Product::select('id', 'name', 'price', 'group_id', 'calculation_type', 'is_sub_product')->orderBy('name', 'asc')->get();
        return view('admin.orders.edit', compact('customers', 'order', 'orderType', 'products'));
    }

    public function EditProduct(UpdateProductRequest $request)
    {
        if ($request->ajax()) {
            $dataRowIndex = $request->dataRowIndex;
            $product = OrderProduct::findOrFail($request->opid);
            $order      = Order::select(
                'orders.id',
                'order_products.product_id',
                'order_products.id as order_product_id',
                'order_products.price',
                'order_products.is_sub_product'
            )
                ->join('order_products', 'orders.id', 'order_products.order_id')
                ->where('orders.customer_id', $request->customer_id)
                ->where('order_products.product_id', $request->product_id)
                ->orderBy('order_products.id', 'desc')
                ->first();
            $customer = Customer::findOrFail($request->customer_id);
            // $unit = config('constant.unitTypes')[strtolower($product->product->unit_type)];
            $unit = $product->product_unit ? $product->product_unit->name : '';
            $purchase_price = $product->product->purchase_price_encode;
            $last_order_price = $order->price ?? '';

            // dd($product->product->product_category_id);
            $rowData['order']            = !is_null($order) ? encrypt($order->id) : '';
            $rowData['customer_type']    = $customer->is_type ?? '';
            $rowData['product_name']     = $product->product->name;

            $rowData['product_description']   = $product->description ?? '';
            $rowData['other_details']    = $product->other_details ?? '';
            $rowData['totalQty']         = $product->quantity ?? 0;

            $rowData['purchase_price']   = $purchase_price ?? 0.00;
            $rowData['min_sale_price']   = $product->product->min_sale_price ?? 0.00;
            // $rowData['sale_price']       = $product->product->sale_price ?? 0.00;
            $rowData['retailer_price']   = ($customer->is_type == 'retailer') ? $product->product->retailer_price : 0.00;
            $rowData['wholesaler_price'] = ($customer->is_type == 'wholesaler') ? $product->product->wholesaler_price : 0.00;
            $WSP = 0.00;


            $customerGroup = CustomerGroup::where('customer_id',$request->customer_id)->get()->pluck('group_id')->toArray();
            if (isset($product)) {
                if ($customer->is_type == 'wholesaler' && $customer->group && in_array($product->group_id, $customerGroup)) {
                    $WSP = $product->product->wholesaler_price ?? 0.00;
                    $priceName = 'WSP';
                } else if ($customer->is_type == 'retailer' || $customer->is_type == 'wholesaler') {
                    $WSP = $product->product->retailer_price ?? 0.00;
                    $priceName = 'RSP';
                } else {
                    $WSP = $product->price;
                }
            }

            $rowData['WSP'] = $WSP;
            $rowData['priceName'] = $priceName ?? '';

            $rowData['last_order_price'] = $last_order_price ?? 0.00;
            $rowData['unit'] = $unit;

            $rowData['price'] = $product->price;
            $rowData['sub_total'] = $product->total_price ?? 0.00;
            $rowData['extra_option_hint'] = $product->product->extra_option_hint ?? '';
            $editRow = true;
            $orders[] = $order;
            $productDetail =  view('admin.orders.product_detail', compact('product', 'orders', 'customer', 'last_order_price', 'unit', 'editRow', 'dataRowIndex'))->render();
            return response()->json(array('status' => true, 'data' => $productDetail, 'rowData' => $rowData), 200);
        }
    }


    protected function recordOrderHistory($order, $updatedData,$action_type= null, $produt_add_or_update=null)
    {
        if(!$action_type){
            foreach ($updatedData['products'] as $product) {
                $historyData = [
                    'order_id' => $order->id,
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity']?? null,
                    'price' => (float)str_replace(',', '', $product['price']) ?? null,
                    'height' => $product['height'] ?? null,
                    'width' => $product['width'] ?? null,
                    'description'  => (isset($product['description']) && !empty($product['description'])) ?  $product['description'] : null,
                    'other_details'  => isset($product['other_details']) ? json_encode(json_decode($product['other_details'], true)) : null,
                    'is_sub_product'  => (isset($product['is_sub_product_value']) && !empty($product['is_sub_product_value'])) ?  $product['is_sub_product_value'] : null,
                    'total_price' => round((float)str_replace(',', '', $product['total_price'])) ?? 0.00,
                    'order_data' => json_encode([
                        'customer_id' => $updatedData['customer_id'],
                        'area_id' => $updatedData['area_id'],
                        'invoice_date' => $updatedData['invoice_date'],
                        'total_amount'   => round($order->total_amount),
                    ]),
                ];

                if (isset($product['opid'])) {
                    $historyData['update_status'] = 'update';
                    $historyData['order_product_id'] = $product['opid'];
                }elseif($produt_add_or_update != null){
                       $order_product_id = $product['id'];
                        if(in_array($order_product_id, $produt_add_or_update)){
                            $historyData['update_status'] = 'add';
                        }else{
                            $historyData['update_status'] = 'update';
                            $historyData['order_product_id'] = $product['id'];
                        }
                }else {
                    $historyData['update_status'] = 'add';
                }
                OrderEditHistory::create($historyData);
            }
        }else
        {
            foreach ($updatedData as $orderProduct) {
                $historyData = [
                    'order_id' => $order->id,
                    'product_id' => $orderProduct->product_id,
                    'quantity' => $orderProduct->quantity,
                    'price' => (float)str_replace(',', '', $orderProduct['price']) ?? null,
                    'height' => $orderProduct['height'] ?? null,
                    'width' => $orderProduct['width'] ?? null,
                    'description'  => (isset($orderProduct['description']) && !empty($orderProduct['description'])) ?  $orderProduct['description'] : null,
                    'other_details'  => isset($orderProduct['other_details']) ? json_encode(json_decode($orderProduct['other_details'], true)) : null,
                    'is_sub_product'  => (isset($orderProduct['is_sub_product_value']) && !empty($orderProduct['is_sub_product_value'])) ?  $orderProduct['is_sub_product_value'] : null,
                    'total_price' => round((float)str_replace(',', '', $orderProduct['total_price'])) ?? 0.00,
                    'order_data' => json_encode([
                        'customer_id' => $order->customer_id,
                        'area_id' => $order->area_id,
                        'invoice_date' => $order->invoice_date,
                        'total_amount'   => round($order->total_amount),
                    ]),
                    'update_status' => 'delete',
                    'order_product_id' => $orderProduct->id,
                ];
                OrderEditHistory::create($historyData);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrdersRequest $request, $id)
    {
        abort_if(Gate::denies('estimate_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        try{

            DB::beginTransaction();
            // dd($request->all());
            $type = $request->type; //sales
            $isDraft = $request->submit == 'draft' ? true : false;
            // $checkDraftStatus = $isDraft == true ? 1 : 0;
            $order = $checkProductOrder = Order::findOrFail($id);
            $beforIs_draft = $order['is_draft'];
            $checkDraftStatus = $isDraft == $beforIs_draft ? 1 : 0;
            if (!$isDraft) {
                $this->recordOrderHistory($order, $request->all());  //Check History
            }

            $inputs = array(
                'invoice_date'   => $request->invoice_date,
                'shipping_amount' => isset($request->shipping_amount) ? (float)str_replace(',', '', $request->shipping_amount) : null,
                'total_amount'   => $isDraft ? 0.00 : round($request->total_amount),
                'remark'         => $request->remark,
                'sold_by'        => $request->sold_by,
                'created_by'     => Auth::user()->id,
                'is_draft'       => $isDraft ? 1 : 0,
                'is_add_shipping' => (isset($request->is_add_shipping) && $request->is_add_shipping == 'on') ? 1 : 0
            );

            if ($order->customer_id == $request->customer_id && $order->invoice_date == $request->invoice_date)
            {
                $inputs['updated_by'] = Auth::user()->id;
                $order->update($inputs);
                addToLog($request, 'Order', 'Edit', $order, $checkProductOrder);

            } else {
                $checkOrder = Order::where(['customer_id' => $request->customer_id, 'invoice_date' => $request->invoice_date, 'is_draft' => $checkDraftStatus])->first();
                if ($checkOrder) {
                    $shippingAmount = (float)str_replace(',', '', $request->shipping_amount) ?? 0.00;
                    $totalAmount = round((float)str_replace(',', '', $request->total_amount));
                    $inputs['shipping_amount'] = $checkOrder->shipping_amount ? ((float)$checkOrder->shipping_amount + $shippingAmount) : null;
                    $inputs['total_amount']   = $isDraft ? 0.00 : ((float)$checkOrder->total_amount + $totalAmount);
                    $inputs['invoice_number'] = getNewInvoiceNumber('', 'new',$request->invoice_date);
                    $inputs['updated_by'] = Auth::user()->id;
                    $order->delete();
                    $order = $checkOrder->update($inputs);
                    addToLog($request, 'Order', 'Edit', $order, $checkProductOrder);
                } else {
                    $inputs['customer_id'] = $request->customer_id;
                    $inputs['area_id'] = $request->area_id;
                    $inputs['invoice_number'] = getNewInvoiceNumber('', 'new',$request->invoice_date);
                    $order = Order::create($inputs);
                    addToLog($request, 'Order', 'Create', $order);
                }
            }

            $orderProducts = $request->products;
            $allProducts = array();
            $existedOrderProductId = array();
            $couter = 0;
            if (count($orderProducts) > 0) {
                foreach ($orderProducts as $key => $oProduct) {
                    // dd($orderProducts);
                    $updateOrCreateOrderProduct = [
                        'product_id' => $oProduct['product_id'],
                        'quantity'   => $oProduct['quantity'],
                        'price'      => (float)str_replace(',', '', $oProduct['price']) ?? null,
                        'height'     => $oProduct['height'] ?? null,
                        'width'      => $oProduct['width'] ?? null,
                        'length'     => $oProduct['length'] ?? null,
                        'is_draft'   => $oProduct['is_draft'] ?? 0,
                        'description'  => (isset($oProduct['description']) && !empty($oProduct['description'])) ?  $oProduct['description'] : null,
                        'other_details'  => isset($oProduct['other_details']) ? json_encode(json_decode($oProduct['other_details'], true)) : null,
                        'is_sub_product'  => (isset($oProduct['is_sub_product_value']) && !empty($oProduct['is_sub_product_value'])) ?  $oProduct['is_sub_product_value'] : null,
                        'total_price'   => round((float)str_replace(',', '', $oProduct['total_price'])) ?? 0.00,
                    ];
                    //dd($updateOrCreateOrderProduct);
                    if (isset($oProduct['opid']) && !empty($oProduct['opid'])) {
                        if ($id != $order->id) {
                            $updateOrCreateOrderProduct['order_id'] = $order->id;
                        }
                        $existedOrderProductId[] = $oProduct['opid'];
                        OrderProduct::where('id', $oProduct['opid'])->update($updateOrCreateOrderProduct);
                    } else {
                        $allProducts[] = $updateOrCreateOrderProduct;
                    }
                }
            }

            $delproids = explode(",", $request->deleted_opids);

            if (count($delproids) > 0) {
                $orderProductsToDelete = OrderProduct::whereIn('id', $delproids)->get();
                $this->recordOrderHistory($order, $orderProductsToDelete, 'delete'); // Check History
                foreach ($orderProductsToDelete as $orderProduct) {
                    $orderProduct->delete();
                }
            }


            if (count($allProducts) > 0) {
                foreach ($allProducts as $oproduct) {
                    $order->orderProduct()->create($oproduct);
                }
            }
            $transaction = [
                'customer_id'    => $order->customer_id,
                'voucher_number' => $order->invoice_number,
                'amount'         => round((float)str_replace(',', '', $order->total_amount)),
                'extra_details'  => 'order update with total_amount ' . $order->total_amount . ' updated_by=' . Auth::user()->id,
                'entry_date'    => $request->invoice_date,
                'remark'        => $order->order_type == 'return' ? 'Sales return' : 'Sales',
            ];

            if (!$isDraft) {
                if ($id != $order->id) {
                    $lastPaymentTransaction = PaymentTransaction::where(['order_id' => $id, 'entry_date' => $checkProductOrder->invoice_date])->first();
                    if ($lastPaymentTransaction) {
                        $lastPaymentTransaction->delete();
                    }
                    $checkNewOrderPaymentTransaction = $oldPaymentTransaction = PaymentTransaction::where(['order_id' => $order->id, 'entry_date' => $order->invoice_date])->first();

                    if ($checkNewOrderPaymentTransaction) {
                        $checkNewOrderPaymentTransaction->update([
                            'amount'        => round((float)str_replace(',', '', $lastPaymentTransaction->amount ?? 0.00)) + (float)$checkNewOrderPaymentTransaction->amount ?? 0.00
                        ]);
                        addToLog($request, 'Order', 'Edit', $checkNewOrderPaymentTransaction, $oldPaymentTransaction);
                    } else {

                        $transaction['order_id'] = $order->id;
                        PaymentTransaction::create($transaction);
                        addToLog($request, 'Estimate', 'Create', $transaction);
                    }
                } else {
                    $transaction = [
                        'order_id'      => $order->id,
                        'customer_id'   => $order->customer_id,
                        'payment_type'  => ($order->order_type == 'return')?'credit':'debit',
                        'payment_way'   => 'order_'.$order->order_type,
                        'voucher_number' => $order->invoice_number,
                        'amount'         => round((float)str_replace(',', '', $order->total_amount)),
                        'created_by'    => Auth::user()->id,
                        'entry_date'    => $request->invoice_date, //date('Y-m-d',strtotime($request->get('invoice_date'))),
                        'remark'        => $order->order_type == 'return' ? 'Sales return' : 'Sales',
                    ];
                    //dd($transaction);
                    // PaymentTransaction::updateOrInsert(['voucher_number' => $order->invoice_number], $transaction);
                    PaymentTransaction::updateOrCreate(
                        ['voucher_number' => $order->invoice_number],$transaction // Data to update or insert
                    );
                }
            }

            $order->update(['is_modified' => 1]);
            $invoiceNumberIs = $invoiceNumber ?? $order->invoice_number;
            DB::commit();

            if ($type == 'draft') {
                return response()->json([
                    'success' => true,
                    'message'     => 'Successfully Updated!',
                    'invoiceNumber'     => 'Invoice No. '.$invoiceNumberIs,
                    'redirectUrl' => route('admin.orders.draftInvoice'),
                ]);
            }


            return response()->json([
                'success' => true,
                'message'     => 'Successfully Updated!',
                'invoiceNumber'     => 'Invoice No. '.$invoiceNumberIs,
                'redirectUrl' => route('admin.transactions.type', $type),
            ]);


        }catch(\Exception $e){
            DB::rollBack();
            dd($e->getMessage());
            \Log::error("Error in OrdersController::update (".$e->getCode(). ")" . $e->getMessage() . " at line " . $e->getLine());
            return response()->json(['success' => false , 'message'=>'error'],500);
        }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        abort_if(Gate::denies('estimate_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $id = decrypt($id);
        $order = Order::findOrFail($id);
        $order->orderProduct()->delete();
        $order->delete();
        // PaymentTransaction::where('order_id',$id)->delete();
        return response()->json(['message' => 'Successfully deleted!', 'alert-type' => 'success']);
    }


    public function get_customer_detail(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->customer_id;
            $customerDetail = Customer::where('id', $id)->first();
            return response()->json([
                'success' => true,
                'data' => [
                    'data' => $customerDetail,
                    'place_name' => $customerDetail->area,
                ]
            ]);
        }
    }

    public function get_product_detail(Request $request)
    {
        if ($request->ajax()) {
            $rules = array(
                'customer_id' => 'required|exists:customers,id',
                'product_id'  => 'required|exists:products,id'
            );

            $validator = Validator::make($request->all(), $rules);
            // Validate the input and return correct response
            if ($validator->fails()) {
                return response()->json(array(
                    'success' => false,
                    'errors' => $validator->getMessageBag()->toArray()

                ), 400);
            }

            $product = Product::findOrFail($request->product_id);
            $customer = Customer::findOrFail($request->customer_id);
            // $unit = config('constant.unitTypes')[strtolower($product->unit_type)];
            $unit = $product->product_unit ? $product->product_unit->name : '';
            $purchase_price = $product->purchase_price_encode;
            $last_order_price = 0.00;
            $orders = [];
            if ($product->is_sub_product == 1) {
                $orders = Order::select(
                    'orders.id',
                    'order_products.product_id',
                    'order_products.id as order_product_id',
                    'order_products.price',
                    'order_products.is_sub_product'
                )
                    ->leftJoin('order_products', 'orders.id', '=', 'order_products.order_id')
                    ->where('orders.customer_id', $request->customer_id)
                    ->where('order_products.product_id', $request->product_id)
                    ->whereNotNull('order_products.is_sub_product')
                    ->whereIn('order_products.id', function ($subquery) {
                        $subquery->select(\DB::raw('MAX(order_products.id)'))
                            ->from('order_products')
                            ->groupBy('order_products.is_sub_product');
                    })
                    ->whereNull('orders.deleted_at')
                    ->orderByDesc('order_products.id')->get();

                $rowData['order'] = $orders;
            } else {
                $order = Order::select('order_products.price', 'orders.id')
                    ->join('order_products', 'orders.id', 'order_products.order_id')
                    ->where('orders.customer_id', $request->customer_id)
                    ->where('order_products.product_id', $request->product_id)
                    ->orderBy('order_products.id', 'desc')
                    ->first();
                $last_order_price = $order->price ?? 0.00;
                $rowData['order'] = !is_null($order) ? encrypt($order->id) : '';
            }

            $rowData['customer_type']    = $customer->is_type ?? '';
            $rowData['product_name']     = $product->name;
            $rowData['purchase_price']   = $purchase_price ?? 0.00;
            $rowData['min_sale_price']   = $product->min_sale_price ?? 0.00;
            // $rowData['sale_price']       = $product->sale_price ?? 0.00;
            $rowData['retailer_price']   = $product->retailer_price ?? 0.00; //($customer->is_type == 'retailer') ? $product->retailer_price : 0.00;
            $rowData['wholesaler_price'] = $product->wholesaler_price ?? 0.00; //($customer->is_type == 'wholesaler') ? $product->wholesaler_price : 0.00;
            $rowData['last_order_price'] = $last_order_price ?? 0.00;

            $price = 00.00;
            /* if (isset($last_order_price) && $last_order_price != 0) {
                $price = $last_order_price;
            } else */




            $customerGroup = CustomerGroup::where('customer_id',$request->customer_id)->get()->pluck('group_id')->toArray();
            if (isset($product)) {
                if ($customer->is_type == 'wholesaler' && $customer->group && in_array($product->group_id, $customerGroup)) {
                    $price = $product->wholesaler_price ?? 0.00;
                    $priceName = 'WSP';
                } else if ($customer->is_type == 'retailer' || $customer->is_type == 'wholesaler') {
                    $price = $product->retailer_price ?? 0.00;
                    $priceName = 'RSP';
                } else {
                    $price = $product->price;
                }
            }

            $rowData['price'] = $price;
            $rowData['priceName'] = $priceName ?? '';
            $rowData['unit'] = $unit;
            $rowData['sub_total'] = $product->price ?? 0.00;
            $rowData['extra_option_hint'] = $product->extra_option_hint ?? '';
            if (empty($last_order_price)) {
                if ($customer->is_type == 'retailer') {
                    $last_order_price = $rowData['retailer_price'];
                } elseif ($customer->is_type == 'wholesaler') {
                    $last_order_price = $rowData['wholesaler_price'];
                }
            }

            $productDetail =  view('admin.orders.product_detail', compact('product', 'orders', 'customer', 'last_order_price' /* ,'unit' */))->render();
            return response()->json(array('status' => true, 'data' => $productDetail, 'rowData' => $rowData, 'is_sub_product' => $product->is_sub_product), 200);
        }
        return response()->json(array('status' => false, 'data' => ''), 200);
    }

    public function pre_price_subgroup_select(Request $request)
    {
        if ($request->ajax()) {
            $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'product_id'  => 'required|exists:products,id',
                'is_sub_product'  => 'required|exists:order_products,is_sub_product'
            ]);

            $product = Product::findOrFail($request->product_id);
            $customer = Customer::findOrFail($request->customer_id);

            if ($product->is_sub_product == 1) {
                $order = Order::select(
                    'orders.id',
                    'order_products.product_id',
                    'order_products.id as order_product_id',
                    'order_products.price',
                    'order_products.is_sub_product'
                )
                ->leftJoin('order_products', 'orders.id', '=', 'order_products.order_id')
                ->where('orders.customer_id', $request->customer_id)
                ->where('order_products.product_id', $request->product_id)
                ->where('order_products.is_sub_product',$request->is_sub_product)
                ->whereNull('orders.deleted_at')
                ->orderByDesc('order_products.id')->first();

                $rowData['order'] = !is_null($order) ? encrypt($order->id) : '';
            }

            $rowData['last_order_price'] = $order->price ?? 0.00;

            return response()->json(array('status' => true, 'rowData' => $rowData), 200);
        }
        return response()->json(array('status' => false, 'data' => ''), 200);
    }

    public function add_product_row(Request $request)
    {
        // dd($request->all());
        if ($request->ajax()) {
            $rules = array(
                'customer_id' => 'required|exists:customers,id',
                'product_id'  => 'required|exists:products,id'
            );
            $validator = Validator::make($request->all(), $rules);

            // Validate the input and return correct response
            if ($validator->fails()) {
                return response()->json(array(
                    'success' => false,
                    'errors' => $validator->getMessageBag()->toArray()

                ), 400);
            }



            $product = Product::findOrFail($request->product_id);
            $customer = Customer::findOrFail($request->customer_id);
            // $unit = config('constant.unitTypes')[strtolower($product->unit_type)];
            $unit = $product->product_unit ? $product->product_unit->name : '';
            $purchase_price = $product->purchase_price_encode;

            $last_order_price = '';
            $orders = [];
            if ($product->is_sub_product == 1) {
                $orders = Order::select(
                    'orders.id',
                    'order_products.product_id',
                    'order_products.id as order_product_id',
                    'order_products.price',
                    'order_products.is_sub_product'
                )
                    ->leftJoin('order_products', 'orders.id', '=', 'order_products.order_id')
                    ->where('orders.customer_id', $request->customer_id)
                    ->where('order_products.product_id', $request->product_id)
                    ->whereNotNull('order_products.is_sub_product')
                    ->whereIn('order_products.id', function ($subquery) {
                        $subquery->select(\DB::raw('MAX(order_products.id)'))
                            ->from('order_products')
                            ->groupBy('order_products.is_sub_product');
                    })
                    ->whereNull('orders.deleted_at')
                    ->orderByDesc('order_products.id')->get();

                // dd($orders);

                $rowData['order'] = $orders;
            } else {
                $order = Order::select('order_products.price', 'orders.id')
                    ->join('order_products', 'orders.id', 'order_products.order_id')
                    ->where('orders.customer_id', $request->customer_id)
                    ->where('order_products.product_id', $request->product_id)
                    ->orderBy('order_products.id', 'desc')
                    ->first();
                $last_order_price = $order->price ?? '';
                $rowData['order'] = !is_null($order) ? encrypt($order->id) : '';
            }

            $rowData['customer_type']    = $customer->is_type ?? '';
            $rowData['product_name']     = $product->name;
            $rowData['purchase_price']   = $purchase_price ?? 0.00;
            $rowData['min_sale_price']   = $product->min_sale_price ?? 0.00;
            // $rowData['sale_price']       = $product->sale_price ?? 0.00;
            $rowData['retailer_price']   = $product->retailer_price ?? 0.00; //($customer->is_type == 'retailer') ? $product->retailer_price : 0.00;
            $rowData['wholesaler_price'] = $product->wholesaler_price ?? 0.00; //($customer->is_type == 'wholesaler') ? $product->wholesaler_price : 0.00;
            $rowData['last_order_price'] = $last_order_price ?? 0.00;

            $price = 00.00;
            if (isset($last_order_price) && $last_order_price != 0) {
                $price = $last_order_price;
            } else if (isset($product)) {
                if ($customer->is_type == 'retailer') {
                    $product->retailer_price ?? 0.00;
                } else if ($customer->is_type == 'wholesaler') {
                    $product->wholesaler_price ?? 0.00;
                } else {
                    $price = $product->price;
                }
            }
            $rowData['price'] = $price;
            $rowData['sub_total'] = $product->price ?? 0.00;
            $rowData['extra_option_hint'] = $product->extra_option_hint ?? '';
            // dd($rowData);
            if (empty($last_order_price)) {
                if ($customer->is_type == 'retailer') {
                    $last_order_price = $rowData['retailer_price'];
                } elseif ($customer->is_type == 'wholesaler') {
                    $last_order_price = $rowData['wholesaler_price'];
                }
            }

            $productDetail =  view('admin.orders.product_detail', compact('product', 'orders', 'customer', 'last_order_price', 'unit'))->render();
            return response()->json(array('status' => true, 'data' => $productDetail, 'rowData' => $rowData, 'is_sub_product' => $product->is_sub_product), 200);
        }
        return response()->json(array('status' => false, 'data' => ''), 200);
    }

    public function checkInvoiceNumber(Request $request)
    {
        $orderId = '';
        $exists = true;

        if (isset($request->orderId) && !empty($request->orderId)) {
            $orderId = $request->orderId;
        }

        if ($request->routeName == 'new_edit') {
            $exists  = getNewInvoiceNumber($orderId, $request->routeName, $request->invoice_number);
        } else if ($request->routeName == 'new_cash_receipt') {
            $invoiceNumber = getNewInvoiceNumber($orderId, $request->routeName, $request->voucher_number);

            $exists = PaymentTransaction::where('voucher_number', $request->voucher_number)->exists();
        } else {
            $invoiceNumber = getNewInvoiceNumber($orderId, $request->routeName, $request->invoice_number);

            $exists = Order::where('invoice_number', $request->invoice_number)->exists();
        }

        return response()->json(!$exists);
    }


    public function addGlassProductView(Request $request)
    {
        if ($request->ajax()) {
            if (isset($request->otherDetails) && !is_null($request->otherDetails)) {
                $otherDetails = json_decode($request->otherDetails, true);
                $product = Product::where('id', $request->product_id)->pluck('calculation_type');
                $html =  view('admin.orders.partials.editGlassProductDetails', compact('otherDetails', 'product'))->render();
            } else {
                $customer = Customer::findOrFail($request->customer_id);
                $product = Product::findOrFail($request->product_id);
                $html =  view('admin.orders.partials.addGlassProductDetails', compact('product', 'customer'))->render();
            }

            return response()->json(array('status' => true, 'html' => $html), 200);
        }
    }

    public function returnCreate()
    {
        $orderType = 'return';
        $customers = Customer::select('id', 'name', 'is_type', 'credit_limit')->orderBy('name', 'asc')->pluck('name', 'id')->prepend(trans('admin_master.g_please_select'), '');
        $products = Product::select('id', 'name', 'price', 'group_id', 'calculation_type', 'is_sub_product')
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.orders.create', compact('customers', 'products', 'orderType'));
    }

    public function draftInvoice(DraftInvoiceDataTable $dataTable)
    {
        abort_if(Gate::denies('estimate_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $orderType = 'draft';
        return $dataTable->render('admin.orders.draft', compact('orderType'));
    }

    public function printPdf($id){
       // ini_set('max_execution_time', 300);
        try{
            if(!is_numeric($id)){
                $id = decrypt($id);
            }

            $order = Order::with(['orderPayTransaction' => function ($query) {
                $query->withTrashed();
            }])->withTrashed()->findOrFail($id);

            $pdfData['title'] = $title = time().'_estimate';
            $pdfData['order'] = $order;
            $pdfFileName = 'order_invoice_'.$id.'.pdf';
            $pdf = PDF::loadView('admin.exports.pdf.order-pdf', compact("pdfData","order","title"));
            $pdf->setPaper('A5', 'portrait');
            $pdf->setOption('charset', 'UTF-8');
            return $pdf->stream($pdfFileName, ['Attachment' => false]);
            //return view('admin.exports.pdf.order-pdf',compact("pdfData","order","title"));

        }catch(\Exception $e){
            return abort(404);
        }
    }

    public function allSelectedOrderPrint(Request $request)
    {
        ini_set('max_execution_time', 300);
        try{
            $order_ids  = explode(',',$request->order_ids);
            $orders = Order::with(['orderPayTransaction' => function ($query) {
                $query->withTrashed();
            }])->withTrashed()->whereIn('id', $order_ids)->get();

            $pdfData['title'] = $title = time().'_estimate';
            $pdfData['orders'] = $orders;
            $pdfFileName = 'order_invoice_all.pdf';
            $pdf = PDF::loadView('admin.exports.pdf.all-print', compact("pdfData","title"));
            $pdf->setPaper('A5', 'portrait');
            $pdf->setOption('charset', 'UTF-8');
            return $pdf->stream($pdfFileName, ['Attachment' => false]);
            //return view('admin.exports.pdf.all-print',compact("pdfData","title"));

        }catch(\Exception $e){
            //dd($e->getMessage());
            return abort(404);
        }
    }

}
