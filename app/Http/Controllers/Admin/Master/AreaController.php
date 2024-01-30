<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\AreaDataTable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Area;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Auth;
class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(AreaDataTable $dataTable)
    {
        abort_if(Gate::denies('area_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return $dataTable->render('admin.master.area.index');
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
        abort_if(Gate::denies('area_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $validator = Validator::make($request->all(), [
            'address' => 'required|unique:areas,address',
        ]);  
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->toArray()
            ]);
        }
        Area::create(['address' => $request->address,'created_by'=> Auth::id()]);  
        return response()->json(['success' => 'Area created successfully.']);
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
        abort_if(Gate::denies('area_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $id =  decrypt($id); 
        $validator = Validator::make($request->all(), [
            'address' => [
                'required',
                Rule::unique('areas', 'address')->ignore($id)->whereNull('deleted_at'),
            ]]);  
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->toArray()
            ]);
        }
        Area::where('id',$id)->update(['address' => $request->address,'updated_by'=> Auth::id()]);  
        return response()->json(['success' => 'Area Update successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        abort_if(Gate::denies('area_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $record = Area::find(decrypt($id));
        $record->updated_by = Auth::id();
        $record->save();
        $record->delete();
        return response()->json(['success' => 'Area Deleted successfully.']);
    }
}
