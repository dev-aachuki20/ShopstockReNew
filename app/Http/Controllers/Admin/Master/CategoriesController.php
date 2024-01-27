<?php

namespace App\Http\Controllers\Admin\Master;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\CategoryDataTable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\ProductCategory;
class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CategoryDataTable $dataTable)
    {
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:product_categories,name',
        ]);  
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->toArray()
            ]);
        }
        ProductCategory::create(['name' => $request->name]);  
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
        ProductCategory::where('id',$id)->update(['name' => $request->name]);  
        return response()->json(['success' => 'Category Update successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $record = ProductCategory::find(decrypt($id));
        $record->delete();
        return response()->json(['success' => 'Category Delete successfully.']);
    }
}
