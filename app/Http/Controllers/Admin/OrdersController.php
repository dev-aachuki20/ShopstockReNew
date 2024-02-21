<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;


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
        $orderType = 'create';
        $customers = Customer::select('id', 'name', 'is_type', 'credit_limit')->orderBy('name', 'asc')->pluck('name', 'id')->prepend(trans('admin_master.g_please_select'), '');
        $products  = Product::select('id', 'name', 'price', 'group_id')->orderBy('name', 'asc')->pluck('name', 'id')->prepend(trans('admin_master.g_please_select'), '');
        return view('admin.orders.create', compact('customers', 'products', 'orderType'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        if ($request->ajax()) {
            $id = decrypt($id);
            $product = Product::where('id', $id)->first();
            $html = View::make('admin.master.product.show', compact('product'))->render();
            return response()->json(['success' => true, 'html' => $html]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
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

    // public function get_product_detail(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $id = $request->product_id;
    //         $productDetail = Product::where('id', $id)->first();
    //         $customerType = Customer::where('id', $request->customer_id)->pluck('is_type');
    //         $productDetail['customerType'] = $customerType[0];
    //         return response()->json([
    //             'success' => true,
    //             'data' => $productDetail,
    //         ]);
    //     }
    // }

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
            $unit = config('constant.unitTypes')[strtolower($product->unit_type)];
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
            if (empty($last_order_price)) {
                if ($customer->is_type == 'retailer') {
                    $last_order_price = $rowData['retailer_price'];
                } elseif ($customer->is_type == 'wholesaler') {
                    $last_order_price = $rowData['wholesaler_price'];
                }
            }

            $productDetail =  view('admin.orders.order_detail_table', compact('product', 'orders', 'customer', 'last_order_price' /* ,'unit' */))->render();
            return response()->json(array('status' => true, 'data' => $productDetail, 'rowData' => $rowData, 'is_sub_product' => $product->is_sub_product), 200);
        }
        return response()->json(array('status' => false, 'data' => ''), 200);









            // $id = $request->product_id;
            // $productDetail = Product::where('id', $id)->first();
            // $customerType = Customer::where('id', $request->customer_id)->pluck('is_type');
            // $productDetail['customerType'] = $customerType[0];
            // return response()->json([
                // 'success' => true,
                // 'data' => $productDetail,
            // ]);
        // }
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
            dd($rowData);
            if (empty($last_order_price)) {
                if ($customer->is_type == 'retailer') {
                    $last_order_price = $rowData['retailer_price'];
                } elseif ($customer->is_type == 'wholesaler') {
                    $last_order_price = $rowData['wholesaler_price'];
                }
            }

            $productDetail =  view('admin.orders.order_detail_table', compact('product', 'orders', 'customer', 'last_order_price' /* ,'unit' */))->render();
            return response()->json(array('status' => true, 'data' => $productDetail, 'rowData' => $rowData, 'is_sub_product' => $product->is_sub_product), 200);
        }
        return response()->json(array('status' => false, 'data' => ''), 200);
    }
}
