<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\DataTables\RolePermissionDataTable;
use Illuminate\Http\Response;
use App\Models\Role;
use App\Models\RoleIp;
use App\Models\RoleIpPermission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Auth;
class RoleIpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(RolePermissionDataTable $dataTable)
    {
        abort_if(Gate::denies('ip_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return $dataTable->render('admin.master.roleip.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(Gate::denies('ip_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $allRoles = Role::all();
        return view('admin.master.roleip.create',compact('allRoles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    { 
        abort_if(Gate::denies('ip_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $validator = Validator::make($request->all(), [
            'ip_address' => [
            'required',
            'ip',
            Rule::unique('role_ips', 'ip_address')->whereNull('deleted_at')
        ]]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->toArray()
            ]);
        }
       
        $roledata =  RoleIp::create(['ip_address' => $request->ip_address,'created_by'=> Auth::id()]); 
        if($request->has('roles')){
            foreach($request->roles as $row){
                RoleIpPermission::create(['role_id' => $row,'role_ip_id'=> $roledata->id]);   
            }
        }        
        addToLog($request,'RoleIp','Create', $roledata);
        return response()->json(['success' => 'Role Ip created successfully.']);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        abort_if(Gate::denies('ip_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $allRoles = Role::all();
        $role_ip = RoleIp::findOrFail($id);     
        $RoleIpPermission = RoleIpPermission::where('role_ip_id',$id)->pluck('role_id')->toArray();
        return view('admin.master.roleip.edit',compact('allRoles','role_ip','RoleIpPermission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        abort_if(Gate::denies('ip_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $validator = Validator::make($request->all(), [
            'ip_address' => [
                'required',
                'ip',
                Rule::unique('role_ips', 'ip_address')->whereNull('deleted_at')->ignore($id)
            ]]);        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->toArray()
            ]);
        }
        $role_ip = RoleIp::findOrFail($id);
        $oldvalue = $role_ip->getOriginal();   
        $role_ip->ip_address = $request->ip_address;
        $role_ip->updated_by = Auth::id();  
         $role_ip->save();
        $newValue = $role_ip->refresh();

        RoleIpPermission::where('role_ip_id',$id)->delete();
        if($request->has('roles')){
            foreach($request->roles as $row){
                RoleIpPermission::create(['role_id' => $row,'role_ip_id'=> $id]);   
            }
        } 

        addToLog($request,'RoleIp','Edit', $newValue ,$oldvalue);      
        return response()->json(['success' => 'Role Ip  Update successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        abort_if(Gate::denies('ip_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $record = RoleIp::find(decrypt($id));
        $oldvalue = $record->getOriginal(); 
        $record->delete();
        $record->updated_by = Auth::id();
        $record->save();       
       
        $newValue = $record->refresh();
        addToLog($request,'RoleIp','Delete', $newValue ,$oldvalue); 
        return response()->json(['success' => 'Role Ip Delete successfully.']);
    }
}
