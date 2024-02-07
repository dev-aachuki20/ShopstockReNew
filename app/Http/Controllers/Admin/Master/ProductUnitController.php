<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\ProductUnitDataTable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\ProductUnit;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Auth;

class ProductUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ProductUnitDataTable $dataTable)
    {
        abort_if(Gate::denies('unit_access'), Response::HTTP_FORBIDDEN, '403 Forbidden'); 
        return $dataTable->render('admin.master.product_unit.index');
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
        abort_if(Gate::denies('unit_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                Rule::unique('product_units', 'name')->whereNull('deleted_at'),
            ]]); 
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->toArray()
            ]);
        }
        $unit_data = ProductUnit::create(['name' => $request->name,'created_by'=> Auth::id()]); 
        addToLog($request,'ProductUnit','Create', $unit_data);
        return response()->json(['success' => 'Unit created successfully.', 'unitData' => $unit_data]);
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
        abort_if(Gate::denies('unit_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $id =  decrypt($id);
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                Rule::unique('product_units', 'name')->ignore($id)->whereNull('deleted_at'),
            ]]);  
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->toArray()
            ]);
        }
        $unitData =  ProductUnit::find($id);
        $oldvalue = $unitData->getOriginal();         
        $unitData->name = $request->name;
        $unitData->updated_by = Auth::id();
        $unitData->save();       
        $newValue = $unitData->refresh();
        addToLog($request,'ProductUnit','Edit', $newValue ,$oldvalue);        
        return response()->json(['success' => 'Unit Update successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        abort_if(Gate::denies('unit_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $record = ProductUnit::find(decrypt($id));
        $oldvalue = $record->getOriginal(); 
        $record->updated_by = Auth::id();
        $record->save();
        $newValue = $record->refresh();
        addToLog($request,'ProductUnit','Delete', $newValue ,$oldvalue);
        $record->delete();
        return response()->json(['success' => 'Unit Deleted successfully.']);
    }
}
