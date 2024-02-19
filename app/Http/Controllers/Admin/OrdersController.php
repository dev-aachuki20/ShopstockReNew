<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       // dd('yes');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        dd('yes');
        $orderType = 'create';
        $customers = Customer::select('id', 'name', 'is_type','credit_limit')->orderBy('name','asc')->get();
        $products  = Product::select('id', 'name', 'price', 'product_category_id','group_id')->orderBy('name','asc')->get();
         return view('admin.orders.create', compact('customers','products','orderType'));
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
