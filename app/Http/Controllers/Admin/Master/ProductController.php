<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\DataTables\ProductDataTable;
use App\Models\ProductCategory;
use App\Models\Group;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ProductDataTable $dataTable)
    {
        abort_if(Gate::denies('product_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return $dataTable->render('admin.master.product.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(Gate::denies('product_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $product_categories = ProductCategory::all()->pluck('name', 'id')->prepend(trans('admin_master.g_please_select'), '');
		$groups = Group::get()->pluck('name', 'id')->prepend(trans('admin_master.g_please_select'), '');
        return view('admin.master.product.create',compact('product_categories','groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_if(Gate::denies('product_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:250',
            'print_name' => 'required|string|max:120',
            'group_id' => 'required|numeric',
            'product_category_id' => 'required|numeric',
            'unit_type' => 'required|string|max:50',
            'extra_option_hint' => 'required|string|max:50',
            'price' => 'required|numeric',
            'min_sale_price' => 'required|numeric',
            'wholesaler_price' => 'required|numeric',
            'retailer_price' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,PNG,JPG|max:2048'
        ]); 
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->toArray()
            ]);
        }

        $data = [
            'name' => $request->name,
            'print_name' => $request->print_name,
            'group_id' => $request->group_id,
            'product_category_id' => $request->product_category_id,
            'unit_type' => $request->unit_type,            
            'is_height' => $request->is_height ?? 0,
            'is_width' => $request->is_width ?? 0,
            'is_length' => $request->is_length ?? 0,
            'is_sub_product' => $request->is_sub_product ?? 0,      
            'extra_option_hint' => $request->extra_option_hint,
            'price' => $request->price,
            'min_sale_price' => $request->min_sale_price,
            'wholesaler_price' => $request->wholesaler_price,
            'retailer_price' => $request->retailer_price,
            'created_by'=> Auth::id(),
            'is_active'=> 1
        ];
        if ($request->hasFile('image')) {
            $image = $request->file('image');             
            $filename = $image->store('product','public');
            $data['image'] = $filename;
        } 
        Product::create($data);
        return response()->json(['success' => 'Product Created successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {   
        abort_if(Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $product = Product::findOrFail($id);
        $product_categories = ProductCategory::all()->pluck('name', 'id')->prepend(trans('admin_master.g_please_select'), '');
		$groups = Group::get()->pluck('name', 'id')->prepend(trans('admin_master.g_please_select'), '');
        return view('admin.master.product.edit', compact('product_categories','groups','product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        abort_if(Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:250',
            'print_name' => 'required|string|max:120',
            'group_id' => 'required|numeric',
            'product_category_id' => 'required|numeric',
            'unit_type' => 'required|string|max:50',
            'extra_option_hint' => 'required|string|max:50',
            'price' => 'required|numeric',
            'min_sale_price' => 'required|numeric',
            'wholesaler_price' => 'required|numeric',
            'retailer_price' => 'required|numeric',
            'image' => 'image|mimes:jpeg,png,jpg,PNG,JPG|max:2048'
        ]); 
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->toArray()
            ]);
        }

        $product = Product::findOrFail($id);

        $product->name = $request->name; 
        $product->print_name = $request->print_name; 
        $product->group_id = $request->group_id; 
        $product->product_category_id = $request->product_category_id; 
        $product->unit_type = $request->unit_type; 
        $product->is_height =  $request->is_height ?? 0; 
        $product->is_width = $request->is_width ?? 0; 
        $product->is_length = $request->is_length ?? 0; 
        $product->is_sub_product = $request->is_sub_product ?? 0;
        $product->extra_option_hint = $request->extra_option_hint; 
        $product->price = $request->price; 
        $product->min_sale_price = $request->min_sale_price; 
        $product->wholesaler_price = $request->wholesaler_price; 
        $product->retailer_price = $request->retailer_price; 
        $product->updated_by = Auth::id();  

        if ($request->hasFile('image')) {
            $image = $request->file('image');             
            $filename = $image->store('product','public');
            $product->image = $filename;
        } 
        $product->save();
        return response()->json(['success' => 'Product Update successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        abort_if(Gate::denies('product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $record = Product::find(decrypt($id));
        $record->updated_by = Auth::id();
        $record->save();
        $record->delete();
        return response()->json(['success' => 'Product Delete successfully.']);
    }
}
