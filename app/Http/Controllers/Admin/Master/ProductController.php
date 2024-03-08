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
        abort_if(Gate::denies('product_undo'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $recycle = 'isRecycle';
        return $dataTable->withParam1($recycle)->render('admin.master.product.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        abort_if(Gate::denies('product_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $product_unit = ProductUnit::all()->pluck('name', 'id')->prepend(trans('admin_master.g_please_select'), '');
        $groups = Group::where('parent_id', 0)->get()->pluck('name', 'id')->prepend(trans('admin_master.g_please_select'), '');
        $isOrderFrom = "No";
        if($request->ajax()){
            $isOrderFrom = "Yes";
            $html = view('admin.master.product.form', compact('groups', 'product_unit','isOrderFrom'))->render();
            return response()->json(['html' => $html]);
        }else{
            return view('admin.master.product.create', compact('groups', 'product_unit','isOrderFrom'));
        }
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_if(Gate::denies('product_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $rules = [
            'name' => 'required|string|max:250',
            'group_id' => 'required|numeric',
            'sub_group_id' => 'required|numeric',
            'calculation_type' => 'required|numeric',
            'price' => 'required|numeric|min:0',
            'min_sale_price' => 'required|numeric|min:0',
            'wholesaler_price' => 'required|numeric|min:0',
            'retailer_price' => 'required|numeric|min:0',
            'image' => 'image|mimes:jpeg,png,jpg,PNG,JPG|max:2048',
        ];

        if (in_array($request->calculation_type, [2, 3])) {
            $rules['unit_type'] = 'required|string|max:50';
            $rules['extra_option_hint'] = 'required|string|max:50';
        } else if ($request->calculation_type == 4) {
            $rules['extra_option_hint'] = 'required|string|max:50';
        }
        $validator = Validator::make($request->all(), $rules);

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
            'created_by' => Auth::id(),
            'is_active' => 1
        ];
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = $image->store('product', 'public');
            $data['image'] = $filename;
        }
        $product =  Product::create($data);
        addToLog($request, 'Product', 'Create', $product);
        return response()->json(['success' => 'Product Created successfully.', 'product' => $product]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        abort_if(Gate::denies('product_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
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
        abort_if(Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $product = Product::findOrFail($id);
        $product_unit = ProductUnit::all()->pluck('name', 'id')->prepend(trans('admin_master.g_please_select'), '');
        $groups = Group::where('parent_id', 0)->get()->pluck('name', 'id')->prepend(trans('admin_master.g_please_select'), '');
        $isOrderFrom = "No";
        return view('admin.master.product.edit', compact('product_unit', 'groups', 'product','isOrderFrom'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        abort_if(Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $rules = [
            'name' => 'required|string|max:250',
            'group_id' => 'required|numeric',
            'sub_group_id' => 'required|numeric',
            'calculation_type' => 'required|numeric',
            'price' => 'required|numeric|min:0',
            'min_sale_price' => 'required|numeric|min:0',
            'wholesaler_price' => 'required|numeric|min:0',
            'retailer_price' => 'required|numeric|min:0',
            'image' => 'image|mimes:jpeg,png,jpg,PNG,JPG|max:2048',
        ];

        if (in_array($request->calculation_type, [2, 3])) {
            $rules['unit_type'] = 'required|string|max:50';
            $rules['extra_option_hint'] = 'required|string|max:50';
        } else if ($request->calculation_type == 4) {
            $rules['extra_option_hint'] = 'required|string|max:50';
        }
        $validator = Validator::make($request->all(), $rules);

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
            $filename = $image->store('product', 'public');
            $product->image = $filename;
        }
        $product->save();
        $newValue = $product->refresh();
        addToLog($request, 'Product', 'Edit', $newValue, $oldvalue);
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
        addToLog($request, 'Product', 'Delete', $newValue, $oldvalue);
        $record->delete();
        return response()->json(['success' => 'Product Delete successfully.']);
    }

    public function viewUpdateProductPrice(Request $request)
    {
        abort_if(Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $product_groups = Group::Where('parent_id', '0')->get()->pluck('name', 'id')->prepend(trans('admin_master.g_please_select'), '');
        return view('admin.master.product.update_product_prices', compact('product_groups'));
    }


    public function productPriceList(Request $request)
    {
        abort_if(Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            $searchValue = $request->search['value'];
            $paginationValue = $request->length;
            $products = Product::select(['products.*']);
            $products->leftJoin('groups', 'groups.id', '=', 'products.group_id');
            $products->whereNull('groups.deleted_at')->orderBy('name', 'asc');


            if (isset($request->group_id) && $request->group_id != '') {
                $products = $products->where('group_id', $request->group_id);
            }
            if (isset($request->sub_group_id) && $request->sub_group_id != '') {
                $products = $products->where('sub_group_id', $request->sub_group_id);
            }
            if (!empty($searchValue)) {
                $products = $products->where(function ($query) use ($searchValue) {
                    $query->where('products.name', 'like', '%' . $searchValue . '%')
                        ->orWhere('products.price', 'like', $searchValue . '%')
                        ->orWhere('products.min_sale_price', 'like', $searchValue . '%')
                        ->orWhere('products.wholesaler_price', 'like', $searchValue . '%')
                        ->orWhere('products.retailer_price', 'like', $searchValue . '%');
                });
            }
            $products =  $products->get();

            return DataTables::of($products)
                ->editColumn('select_p', function ($row) {
                    return '<input type="checkbox" class="selected_product" name="products[]" value="' . $row->id . '">';
                })
                ->editColumn('price', function ($row) {
                    return '<i class="fa fa-inr"></i> <span data-field="price" data-product="' . $row->id . '">' . $row->price ?? 0.00 . '</span>';
                })
                ->editColumn('min_sale_price', function ($row) {
                    return '<i class="fa fa-inr"></i> <span data-field="min_sale_price" data-product="' . $row->id . '">' . $row->min_sale_price ?? 0.00 . '</span>';
                })
                ->editColumn('wholesaler_price', function ($row) {
                    return '<i class="fa fa-inr"></i> <span data-field="wholesaler_price" data-product="' . $row->id . '">' . $row->wholesaler_price ?? 0.00 . '</span>';
                })
                ->editColumn('retailer_price', function ($row) {
                    return '<i class="fa fa-inr"></i> <span data-field="retailer_price" data-product="' . $row->id . '">' . $row->retailer_price ?? 0.00 . '</span>';
                })
                ->rawColumns(['select_p', 'price', 'min_sale_price', 'wholesaler_price', 'retailer_price'])->toJson();
        }
    }


    public function updateProductPrice(Request $request)
    {
        abort_if(Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $validator = Validator::make($request->all(), [
            'newValue' => 'required|numeric|min:0'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->toArray()
            ]);
        }
        if ($request->ajax()) {
            $rowId = $request->input('rowId');
            $fieldName = $request->input('fieldName');
            $newValue = $request->input('newValue');
            try {
                $product = Product::findOrFail($rowId);
                $oldvalue = $product->getOriginal();
                $product->$fieldName = $newValue;
                $product->updated_by =  Auth::id();
                $product->save();
                $newValue = $product->refresh();
                addToLog($request, 'Product', 'Update Product Price', $newValue, $oldvalue);

                return response()->json(['success' => true, 'message' => 'Product price updated successfully']);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Error updating product price']);
            }
        }
    }
    public function updateProductPriceGroup(Request $request)
    {
        abort_if(Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'price_type' => 'required|string',
                'amount' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'error' => $validator->errors()->toArray()
                ]);
            }
            $product_id = $request->product_ids;
            $price_type = $request->price_type;
            foreach ($product_id as $row) {
                $product = Product::findOrFail($row);
                $oldvalue = $product->getOriginal();
                if ($price_type == "increment") {
                    $product->$price_type('price', $request->amount);
                    $product->$price_type('min_sale_price', $request->amount);
                    $product->$price_type('wholesaler_price', $request->amount);
                    $product->$price_type('retailer_price', $request->amount);
                    $product->updated_by =  Auth::id();
                    $product->save();
                    $newValue = $product->refresh();
                    addToLog($request, 'Product', 'Update Product Price', $newValue, $oldvalue);
                }

                if ($price_type == "decrement") {
                    if (($product->price - $request->amount) < 0) {
                        $product->price = 0;
                    } else {
                        $product->$price_type('price', $request->amount);
                    }

                    if (($product->min_sale_price - $request->amount) < 0) {
                        $product->min_sale_price = 0;
                    } else {
                        $product->$price_type('min_sale_price', $request->amount);
                    }

                    if (($product->wholesaler_price - $request->amount) < 0) {
                        $product->wholesaler_price = 0;
                    } else {
                        $product->$price_type('wholesaler_price', $request->amount);
                    }

                    if (($product->retailer_price - $request->amount) < 0) {
                        $product->retailer_price = 0;
                    } else {
                        $product->$price_type('retailer_price', $request->amount);
                    }
                    $product->updated_by =  Auth::id();
                    $product->save();
                    $newValue = $product->refresh();
                    addToLog($request, 'Product', 'Update Product Price', $newValue, $oldvalue);
                }
            }
            return response()->json(['success' => true, 'message' => 'Product price updated successfully']);
        }
    }

    public function export($product_id = null)
    {
        abort_if(Gate::denies('product_export'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return Excel::download(new ProductExport($product_id), 'product-list.xlsx');
    }

    public function undoGroup(Request $request)
    {
        abort_if(Gate::denies('product_undo'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $id =  decrypt($request->recycle_id);

        $deletedData =  Product::withTrashed()->find($id);
        $oldvalue = $deletedData->getOriginal();
        $deletedData->deleted_at = null;
        $deletedData->updated_by =  Auth::id();
        $deletedData->save();
        $newValue = $deletedData->refresh();
        addToLog($request, 'Product', 'Undo', $newValue, $oldvalue);
        return response()->json(['success' => 'Undo successfully.']);
    }

    public function viewUpdateProductGroup(Request $request)
    {
        abort_if(Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $product_groups = Group::Where('parent_id', '0')->get()->pluck('name', 'id')->prepend(trans('admin_master.g_please_select'), '');
        return view('admin.master.product.update_product_group', compact('product_groups'));
    }

    public function productUpdateGroupList(Request $request)
    {
        abort_if(Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            $searchValue = $request->search['value'];
            $paginationValue = $request->length;
            $products = Product::select(['products.*']);
            $products->leftJoin('groups', 'groups.id', '=', 'products.group_id');
            $products->whereNull('groups.deleted_at')->orderBy('name', 'asc');


            if (isset($request->product_type) && $request->product_type != '') {
                $products = $products->where('group_id', $request->product_type);
            }
            if (!empty($searchValue)) {
                $products = $products->where(function ($query) use ($searchValue) {
                    $query->where('products.name', 'like', '%' . $searchValue . '%');
                });
            }
            $products =  $products->get();

            return DataTables::of($products)
                ->editColumn('group_id', function ($row) {
                    $allGroup = Group::Where('parent_id', '0')->get();
                    $html = "";
                    $html .= '<select class="group_list select2" id="group_$row->id" data-porduct_id="' . $row->id . '">';
                    $html .= '<option value="">' . trans('admin_master.g_please_select') . '</option>';
                    foreach ($allGroup  as $group) {
                        $selected = "";
                        if ($group->id == $row->group_id) {
                            $selected = "selected";
                        }
                        $html .= '<option value="' . $group->id . '" ' . $selected . '>' . $group->name . '</option>';
                    }
                    $html .= '</select>';
                    return $html;
                })
                ->editColumn('sub_group_id', function ($row) {
                    $allGroup = Group::Where('parent_id', $row->group_id)->get();
                    $html = "";
                    $html .= '<select class="sub_group select2" id="sub_group_' . $row->id . '" data-porduct_id="' . $row->id . '">';
                    $html .= '<option value="">' . trans('admin_master.g_please_select') . '</option>';
                    foreach ($allGroup  as $group) {
                        $selected = "";
                        if ($group->id == $row->sub_group_id) {
                            $selected = "selected";
                        }
                        $html .= '<option value="' . $group->id . '" ' . $selected . '>' . $group->name . '</option>';
                    }
                    $html .= '</select>';
                    return $html;
                })
                ->editColumn('unit_type', function ($row) {
                    $allUnit = ProductUnit::get();
                    $html = "";
                    $html .= '<select class="product_unit select2" id="product_unit_' . $row->id . '" data-porduct_id="' . $row->id . '">';
                    $html .= '<option value="">' . trans('admin_master.g_please_select') . '</option>';
                    foreach ($allUnit  as $unit) {
                        $selected = "";
                        if ($unit->id == $row->unit_type) {
                            $selected = "selected";
                        }
                        $html .= '<option value="' . $unit->id . '" ' . $selected . '>' . $unit->name . '</option>';
                    }
                    $html .= '</select>';
                    return $html;
                })
                ->rawColumns(['group_id', 'sub_group_id', 'unit_type'])->toJson();
        }
    }

    public function updateProductGroup(Request $request)
    {
        abort_if(Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $validator = Validator::make($request->all(), [
            'porduct_id' => 'required|numeric',
            'group_id' => 'required|numeric'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->toArray()
            ]);
        }
        $product = Product::findOrFail($request->porduct_id);
        $oldvalue = $product->getOriginal();
        if ($request->group_sub_group == "Group") {
            $product->group_id = $request->group_id;
            $product->sub_group_id = 0;
        }
        if ($request->group_sub_group == "SubGroup") {
            $product->sub_group_id = $request->group_id;
        }
        if ($request->group_sub_group == "Unit") {
            $product->unit_type = $request->group_id;
        }
        $product->updated_by = Auth::id();
        $product->save();
        $newValue = $product->refresh();
        $group_sub_group = 'Update Product ' . $request->group_sub_group;
        addToLog($request, 'Product', $group_sub_group, $newValue, $oldvalue);
        return response()->json(['success' => 'Product Update successfully.']);
    }
}
