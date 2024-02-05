<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\DataTables\RoleDataTable;
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
    public function index(RoleDataTable $dataTable)
    {
        abort_if(Gate::denies('role_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return $dataTable->render('admin.master.roleip.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $allRoles = Role::all();
        return view('admin.master.roleip.create',compact('allRoles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:role_ips,ip_address',
        ]);  
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->toArray()
            ]);
        }
        $roledata =  RoleIp::create(['name' => $request->name,'created_by'=> Auth::id()]); 
        if($request->has('roles')){
            foreach($request->roles as $row){
                RoleIpPermission::create(['role_id' => $row,'role_ip_id'=> $roledata->id]);   
            }
        }
        
        addToLog($request,'RoleIp','Create', $roledata);
        return response()->json(['success' => 'Role created successfully.']);

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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
