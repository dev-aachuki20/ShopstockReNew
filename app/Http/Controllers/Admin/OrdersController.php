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
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use PDF;
// use Dompdf\Dompdf;
// use Dompdf\Options;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Cache;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // dd('yes');
    }

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
        $isDraft = $request->submit == 'draft' ? true : false;
        $checkOrder = Order::where(['customer_id' => $request->customer_id, 'invoice_date' => $request->invoice_date, 'is_draft' => $isDraft, 'order_type' => $request->order_type])->first();
        if (!$checkOrder) {
            $invoiceNumber = $request->order_type == 'return' ? getNewInvoiceNumber('', 'return') : getNewInvoiceNumber('', 'new');
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

        // dd($inputs,$order);

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
            $order->orderProduct()->createMany($allOrderProducts);
        }

        if (!$isDraft) {
            $checkPaymentTransaction = PaymentTransaction::where('order_id', $order->id)->first();
            if (!$checkPaymentTransaction) {
                $transaction = [
                    'order_id'      => $order->id,
                    'customer_id'   => $order->customer_id,
                    'payment_type'  => ($order->order_type == 'return') ? 'debit' : 'credit',
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
                $checkPaymentTransaction->update([
                    'amount'        => round((float)str_replace(',', '', $order->total_amount)) + (float)$checkPaymentTransaction->amount
                ]);
            }
        }
        if ($order->order_type == 'return') {
            return response()->json([
                'success' => true,
                'message'     => 'Successfully created!',
                // 'printPdfUrl' => route('admin.orders.printPdf', encrypt($order->id)),
                'redirectUrl' => route('admin.orders.return'),
            ], 200);
        }

        return response()->json([
            'success' => true,
            'message'     => 'Successfully created!',
            // 'printPdfUrl' => route('admin.orders.printPdf',encrypt($order->id)),
            'redirectUrl' => route('admin.orders.create'),
        ], 200);
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
            $type = $request->type;
            $html = View::make('admin.orders.prev_order_modal', compact('order', 'type'))->render();
            return response()->json(['success' => true, 'html' => $html]);
        }
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
            if (isset($product)) {
                if ($customer->is_type == 'wholesaler' && $customer->group && $customer->group->group_id == $product->product->group_id) {
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

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrdersRequest $request, $id)
    {
        abort_if(Gate::denies('estimate_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $type = $request->type; //sales
        $isDraft = $request->submit == 'draft' ? true : false;
        $checkDraftStatus = $isDraft == true ? 0 : 1;

        $order = $checkProductOrder = Order::findOrFail($id);
        $beforIs_draft = $order['is_draft'];
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
        if ($order->customer_id == $request->customer_id && $order->invoice_date == $request->invoice_date && $order->is_draft = $checkDraftStatus) {
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
                $inputs['updated_by'] = Auth::user()->id;
                $order->delete();
                $checkOrder->update($inputs);
                $order = $checkOrder;
                addToLog($request, 'Order', 'Edit', $order, $checkProductOrder);
            } else {
                $inputs['customer_id'] = $request->customer_id;
                $inputs['area_id'] = $request->area_id;
                $inputs['invoice_number'] = getNewInvoiceNumber('', 'new');
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
                PaymentTransaction::updateOrInsert(['voucher_number' => $order->invoice_number], $transaction);
            }
        }

        if ($type == 'draft') {
            return response()->json([
                'success' => true,
                'message'     => 'Successfully Updated!',
                'redirectUrl' => route('admin.orders.draftInvoice'),
            ]);
        }

        return response()->json([
            'success' => true,
            'message'     => 'Successfully Updated!',
            'redirectUrl' => route('admin.transactions.type', $type),
        ]);
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
            if (isset($product)) {
                if ($customer->is_type == 'wholesaler' && $customer->group && $customer->group->group_id == $product->group_id) {
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
            // if (Cache::has('order_invoice_'.$id)){
            //     $order = Order::with(['orderPayTransaction' => function ($query) {                    
            //         $query->withTrashed();
            //     }])->withTrashed()->findOrFail($id);
            //     if(!is_null($order->deleted_at)){
            //         //dd($order);
            //         if (Cache::has('order_invoice_cancel_'.$id)){
            //             return Cache::get('order_invoice_cancel_'.$id);
            //         }else{
            //             $pdfData['title'] = time().'_estimate';
            //             $pdfData['order'] = $order;
                        
            //             $pdf = PDF::loadView('admin.exports.pdf.order-pdf',$pdfData)->setPaper('a5');    
            //             $stream = $pdf->stream();

            //             Cache::Forever('order_invoice_cancel_'.$id, $stream);
            //             return $stream;
            //         }
                  
            //     }else{
            //         return Cache::get('order_invoice_'.$id); 
            //     }
                
            // }else{         
                $order = Order::with(['orderPayTransaction' => function ($query) {                    
                    $query->withTrashed();
                }])->withTrashed()->findOrFail($id);                    
                                   
                $pdfData['title'] = $title = time().'_estimate';
                $pdfData['order'] = $order;                

                //return view('admin.exports.pdf.order-pdf',compact("pdfData","order","title"));

                $pdfHtml = view('admin.exports.pdf.order-pdf', compact("pdfData","order","title"))->render();
                $mpdf = new Mpdf();
                $mpdf->WriteHTML($pdfHtml);
                $mpdf->Output('order_invoice_'.$id.'.pdf', 'I');
              
            // }
        }catch(\Exception $e){
            return abort(404);
        }
    }
}
