<?php

namespace App\Http\Controllers;

use App\DataTables\AddressDataTable;
use App\Exports\AddressExport;
use App\Models\Address;
use App\Rules\TitleValidationRule;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(AddressDataTable $dataTable)
    {

        abort_if(Gate::denies('address_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        //$roles = Address::orderBy('id','asc')->get();
        // $addresses = Address::orderBy('address','asc')->get();
        $addresses = Address::orderByRaw('CAST(address AS SIGNED), address')->get();
        return $dataTable->render('admin.address.index',compact('addresses'));
    }

    public function printView($address_id = null)
    {
        abort_if(Gate::denies('address_print'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $query = Address::query();
        if ($address_id !== null) {
            $query->where('id', $address_id);
        }
        $addresses = $query->orderByRaw('CAST(address AS SIGNED), address')->get();
        return view('admin.address.print-address-list',compact('addresses'))->render();
    }

    public function export($address_id = null){
        abort_if(Gate::denies('address_export'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return Excel::download(new AddressExport($address_id), 'cities.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $htmlView = view('admin.address.create')->render();
        return response()->json(['success' => true, 'htmlView' => $htmlView]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validatedData =$request->validate([
            'address' => ['required','string','unique:address,address', new TitleValidationRule],
        ],[
            'address.regex'=> 'This field should not contain multiple consecutive spaces or consist of only spaces.'
        ]);

        $address=Address::create($validatedData);
        // return response()->json(['success' => true, 'message' => trans('messages.crud.add_record'),'alert-type'=> trans('quickadmin.alert-type.success')], 200);
        return response()->json([
            'success' => true,
            'message' => trans('messages.crud.add_record'),
            'alert-type' => trans('quickadmin.alert-type.success'),
            'address' => [
                'id' => $address->id,
                'address' => $address->address,
            ],
        ], 200);
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
        $address = Address::findOrFail($id);
        $htmlView = view('admin.address.edit', compact('address'))->render();
        return response()->json(['success' => true, 'htmlView' => $htmlView]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $address = Address::find($id);
        $validatedData =$request->validate([
            'address' => ['required','string','unique:address,address,'.$address->id, new TitleValidationRule],
        ],[
            'address.regex'=> 'This field should not contain multiple consecutive spaces or consist of only spaces.'
        ]);

        $address->update($validatedData);
        return response()->json([
            'success' => true,
            'message' => trans('messages.crud.update_record'),
            'alert-type'=> trans('quickadmin.alert-type.success'),
            'redirecturl'=>route('address.index'),
            'title' => trans('quickadmin.address.address')], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        abort_if(Gate::denies('address_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        //dd($id);
        $address = Address::findOrFail($id);
        $address->delete();

        return response()->json(['success' => true,
         'message' => trans('messages.crud.delete_record'),
         'alert-type'=> trans('quickadmin.alert-type.success'),
         'title' => trans('quickadmin.address.address')
        ], 200);
    }
}
