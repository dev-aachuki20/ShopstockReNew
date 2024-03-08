<?php

namespace App\Http\Controllers\Admin\Master;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\DataTables\GroupDataTable;
use App\DataTables\GroupSubDataTable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Group;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use Auth;
use App\Exports\GroupExport;
use App\Exports\GroupSubExport;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GroupDataTable $dataTable)
    {
        abort_if(Gate::denies('group_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');        
        return $dataTable->render('admin.master.group.index');
    }
    public function recycleIndex(GroupDataTable $dataTable)
    {
        abort_if(Gate::denies('group_undo'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $recycle = 'isRecycle';
        return $dataTable->withParam1($recycle)->render('admin.master.group.index');
    }
    public function subGroupIndex(GroupSubDataTable $dataTable)
    {
        abort_if(Gate::denies('group_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');        
        return $dataTable->render('admin.master.group.sub_group_index');
    }
    public function subGroupRecycleIndex(GroupSubDataTable $dataTable)
    {
        abort_if(Gate::denies('group_undo'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $recycle = 'isRecycle';
        return $dataTable->withParam1($recycle)->render('admin.master.group.sub_group_index');
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
        abort_if(Gate::denies('group_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
         if($request->add_type == "Add Sub"){
            $validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    Rule::unique('groups', 'name')->where('parent_id',$request->parent_id)
                ],
                'parent_id' => [
                    'required'
                ]
            ]);
        }else{
            $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                Rule::unique('groups', 'name')->where('parent_id',0)
            ]]); 
        }          
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->toArray()
            ]);
        }
        $group =  Group::create(['name' => $request->name,'parent_id' => $request->parent_id ?? 0 ,'created_by'=> Auth::id()]);  
        addToLog($request,'Group','Create', $group);
        return response()->json(['success' => 'Group created successfully.', 'group' => $group]);
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
        abort_if(Gate::denies('group_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $id =  decrypt($id);
        $checkParentGroup = Group::Where('id',$id)->first(); 
        if($checkParentGroup->parent_id > 0){
            $validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    Rule::unique('groups', 'name')->where('parent_id',$checkParentGroup->parent_id)->ignore($id)
                ]]);
        }else{
            $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                Rule::unique('groups', 'name')->where('parent_id',0)->ignore($id)
            ]]); 
        }  

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->toArray()
            ]);
        }
        $groupData =  Group::find($id);
        $oldvalue = $groupData->getOriginal();         
        $groupData->name = $request->name;
        $groupData->updated_by = Auth::id();
        $groupData->save();       
        $newValue = $groupData->refresh();
        addToLog($request,'Group','Edit', $newValue ,$oldvalue);  
        return response()->json(['success' => 'Group Update successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        abort_if(Gate::denies('group_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $record = Group::find(decrypt($id));
        $oldvalue = $record->getOriginal(); 
        $record->delete();
        $record->updated_by = Auth::id();
        $record->save();       
       
        $newValue = $record->refresh();
        addToLog($request,'Group','Delete', $newValue ,$oldvalue); 
        return response()->json(['success' => 'Group Delete successfully.']);
    }

    public function getGroupParent(Request $request){
        if($request->ajax()){
            $parent_id = $request->parent_id;
            $groups = Group::where('parent_id',0)->pluck('name', 'id')->prepend(trans('admin_master.g_please_select'), '');
            $html = View::make('admin.master.group.parent_form',compact('groups','parent_id'))->render();
            return response()->json(['success' => true, 'html' => $html]);
        }
    }
    public function getSubGroup(Request $request){
        if($request->ajax()){
            $parent_id = $request->parent_id;
            $selected_id = $request->selected_id??'';
            $groups = Group::where('parent_id',$parent_id)->pluck('name', 'id')->prepend(trans('admin_master.g_please_select'), '');
            $html = View::make('admin.master.group.child_form',compact('groups','selected_id'))->render();
            return response()->json(['success' => true, 'html' => $html]);
        }
    }

    public function export($group_id = null){
        abort_if(Gate::denies('group_export'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return Excel::download(new GroupExport($group_id), 'group-list.xlsx');
    }
    public function exportSubGroup($group_id = null){
        abort_if(Gate::denies('group_export'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return Excel::download(new GroupSubExport($group_id), 'sub-group-list.xlsx');
    }

    public function undoGroup(Request $request){
        abort_if(Gate::denies('group_undo'), Response::HTTP_FORBIDDEN, '403 Forbidden');       
        $id =  decrypt($request->recycle_id);      

       $deletedData =  Group::withTrashed()->find($id);
       $oldvalue = $deletedData->getOriginal(); 
       $deletedData->deleted_at = null; 
       $deletedData->updated_by =  Auth::id(); 
       $deletedData->save();
       $newValue = $deletedData->refresh();
       addToLog($request,'Group','Undo', $newValue ,$oldvalue); 
        return response()->json(['success' => 'Undo successfully.']);
    }
}
