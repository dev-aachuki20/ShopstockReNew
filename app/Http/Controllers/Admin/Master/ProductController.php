<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\DataTables\ProductDataTable;
use App\Models\ProductUnit;
use App\Models\Group;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductExport;
use Illuminate\Support\Facades\View;
use Auth, DataTables;
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

    public function recycleIndex(ProductDataTable $dataTable)
    {
        abort_if(Gate::denies('product_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $recycle = 'isRecycle';
        return $dataTable->withParam1($recycle)->render('admin.master.product.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(Gate::denies('product_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $product_unit = ProductUnit::all()->pluck('name', 'id')->prepend(trans('admin_master.g_please_select'), '');
		$groups = Group::where('parent_id',0)->get()->pluck('name', 'id')->prepend(trans('admin_master.g_please_select'), '');
        return view('admin.master.product.create',compact('groups','product_unit'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_if(Gate::denies('product_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
       $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:250',
            'group_id' => 'required|numeric',
            'sub_group_id' => 'required|numeric',
            'calculation_type' => 'required|numeric',
            'unit_type' => 'required|string|max:50',
            'price' => 'required|numeric',
            'min_sale_price' => 'required|numeric',
            'wholesaler_price' => 'required|numeric',
            'retailer_price' => 'required|numeric',
            'extra_option_hint' => 'required|string|max:50',
            'image' => 'image|mimes:jpeg,png,jpg,PNG,JPG|max:2048'
        ]); 
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->toArray()
            ]);
        }

        $data = [
            'name' => $request->name,
            'group_id' => $request->group_id,
            'sub_group_id' => $request->sub_group_id,
            'calculation_type' => $request->calculation_type,
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
        $product =  Product::create($data);
        addToLog($request,'Product','Create', $product);
        return response()->json(['success' => 'Product Created successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        if($request->ajax()){
            $id = decrypt($id);
            $product = Product::where('id',$id)->first();
            $html = View::make('admin.master.product.show',compact('product'))->render();
            return response()->json(['success' => true, 'html' => $html]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {   
        abort_if(Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $product = Product::findOrFail($id);
        $product_unit = ProductUnit::all()->pluck('name', 'id')->prepend(trans('admin_master.g_please_select'), '');
		$groups = Group::where('parent_id',0)->get()->pluck('name', 'id')->prepend(trans('admin_master.g_please_select'), '');
        return view('admin.master.product.edit', compact('product_unit','groups','product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        abort_if(Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:250',
            'group_id' => 'required|numeric',
            'sub_group_id' => 'required|numeric',            
            'calculation_type' => 'required|numeric',
            'unit_type' => 'required|string|max:50',
            'price' => 'required|numeric',
            'min_sale_price' => 'required|numeric',
            'wholesaler_price' => 'required|numeric',
            'retailer_price' => 'required|numeric',
            'extra_option_hint' => 'required|string|max:50',
            'image' => 'image|mimes:jpeg,png,jpg,PNG,JPG|max:2048'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->toArray()
            ]);
        }
        $product = Product::findOrFail($id);
        $oldvalue = $product->getOriginal();   
        $product->name = $request->name; 
        $product->group_id = $request->group_id; 
        $product->sub_group_id = $request->sub_group_id; 
        $product->calculation_type = $request->calculation_type; 
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
        $newValue = $product->refresh();
        addToLog($request,'Product','Edit', $newValue ,$oldvalue);  
        return response()->json(['success' => 'Product Update successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        abort_if(Gate::denies('product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $record = Product::find(decrypt($id));
        $oldvalue = $record->getOriginal(); 
        $record->updated_by = Auth::id();
        $record->save();
        $newValue = $record->refresh();
        addToLog($request,'Product','Delete', $newValue ,$oldvalue); 
        $record->delete();
        return response()->json(['success' => 'Product Delete successfully.']);
    }

    public function viewUpdateProductPrice(Request $request)
    {
        abort_if(Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $product_groups = Group::Where('parent_id','0')->get()->pluck('name', 'id')->prepend(trans('admin_master.g_please_select'), '');
        return view('admin.master.product.update_product_prices',compact('product_groups'));
    }


    public function productPriceList(Request $request){
        abort_if(Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if($request->ajax()){
            $searchValue = $request->search['value'];
            $paginationValue = $request->length;
              $products = Product::select(['products.*']);
              $products->leftJoin('groups', 'groups.id', '=', 'products.group_id');
              $products->whereNull('groups.deleted_at')->orderBy('name','asc');
            

            if(isset($request->product_type) && $request->product_type != ''){
                $products = $products->where('group_id',$request->product_type);
            }            
            if(!empty($searchValue)){
                $products = $products->where(function($query) use($searchValue){
                    $query->where('products.name','like','%'.$searchValue.'%')
                    ->orWhere('products.price','like',$searchValue.'%')
                    ->orWhere('products.min_sale_price','like',$searchValue.'%')
                    ->orWhere('products.wholesaler_price','like',$searchValue.'%')
                    ->orWhere('products.retailer_price','like',$searchValue.'%');
                });
            }            
            $products =  $products->get();

            return DataTables::of($products)
                ->editColumn('price', function ($row) {
                    return '<i class="fa fa-inr"></i> <span data-field="price" data-product="'.$row->id.'">'.$row->price ?? 0.00.'</span>';
                })
                ->editColumn('min_sale_price', function ($row) {
                    return '<i class="fa fa-inr"></i> <span data-field="min_sale_price" data-product="'.$row->id.'">'.$row->min_sale_price ?? 0.00.'</span>';
                })
                ->editColumn('wholesaler_price', function ($row) {
                    return '<i class="fa fa-inr"></i> <span data-field="wholesaler_price" data-product="'.$row->id.'">'.$row->wholesaler_price ?? 0.00.'</span>';
                })
                ->editColumn('retailer_price', function ($row) {
                    return '<i class="fa fa-inr"></i> <span data-field="retailer_price" data-product="'.$row->id.'">'.$row->retailer_price ?? 0.00.'</span>';
                })
                ->rawColumns(['price','min_sale_price','wholesaler_price','retailer_price'])->toJson();
        }
    }


    public function updateProductPrice(Request $request){
        abort_if(Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if($request->ajax()){
            $rowId = $request->input('rowId');
            $fieldName = $request->input('fieldName');
            $newValue = $request->input('newValue');
            try {            
                $product = Product::findOrFail($rowId);            
                $product->$fieldName = $newValue;           
                $product->save();
                return response()->json(['success' => true, 'message' => 'Product price updated successfully']);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Error updating product price']);
            }
        }
    }

    public function export($product_id = null){
        abort_if(Gate::denies('product_export'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return Excel::download(new ProductExport($product_id), 'product-list.xlsx');
    }

    public function undoGroup(Request $request){
        abort_if(Gate::denies('product_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');       
        $id =  decrypt($request->recycle_id);      
        
        $deletedData =  Product::withTrashed()->find($id);
        $oldvalue = $deletedData->getOriginal(); 
        $deletedData->deleted_at = null; 
        $deletedData->updated_by =  Auth::id(); 
        $deletedData->save();
        $newValue = $deletedData->refresh();
        addToLog($request,'Product','Undo', $newValue ,$oldvalue);       
        return response()->json(['success' => 'Undo successfully.']);
    }

}
