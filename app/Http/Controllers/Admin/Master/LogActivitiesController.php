<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LogActivity;
use Illuminate\Support\Facades\View;
use App\DataTables\LogActivityDataTable;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class LogActivitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(LogActivityDataTable $dataTable)
    {
        return $dataTable->render('admin.master.log_activity.index');
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        if($request->ajax()){
            $id = decrypt($id);
            $logActivity = LogActivity::where('id',$id)->first();
            $html = View::make('admin.master.log_activity.show',compact('logActivity'))->render();
            return response()->json(['success' => true, 'html' => $html]);

        }
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
