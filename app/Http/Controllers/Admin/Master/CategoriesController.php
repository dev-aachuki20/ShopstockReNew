<?php

namespace App\Http\Controllers\Admin\Master;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\CategoryDataTable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\ProductCategory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Auth;
class CategoriesController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(CategoryDataTable $dataTable)
    {
        abort_if(Gate::denies('category_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return $dataTable->render('admin.master.category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_if(Gate::denies('category_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:product_categories,name',
        ]);  
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->toArray()
            ]);
        }
        ProductCategory::create(['name' => $request->name,'created_by'=> Auth::id()]);  
        return response()->json(['success' => 'Category created successfully.']);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {  
        abort_if(Gate::denies('category_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $id =  decrypt($id);
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                Rule::unique('product_categories', 'name')->ignore($id)->whereNull('deleted_at'),
            ]]);  
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->toArray()
            ]);
        }
        ProductCategory::where('id',$id)->update(['name' => $request->name,'updated_by'=> Auth::id()]);  
        return response()->json(['success' => 'Category Update successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        abort_if(Gate::denies('category_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $record = ProductCategory::find(decrypt($id));
        $record->updated_by = Auth::id();
        $record->save();
        $record->delete();
        return response()->json(['success' => 'Category Delete successfully.']);
    }
}
