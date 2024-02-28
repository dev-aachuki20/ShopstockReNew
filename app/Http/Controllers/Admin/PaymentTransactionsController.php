<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\MessageBag;
class PaymentTransactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        dd('yes');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::select('id','name','credit_limit','is_type')->orderBy('id','desc')->get();
        $paymentTypes = array(''=>trans('quickadmin.qa_please_select_customer'),'credit' => 'Credit','debit'=>'Debit');
        $paymentWays = array('by_cash' => 'By Cash','by_check' => 'By Check','by_account' => 'By Account');
        return view('admin.payment_transactions.create',compact('customers','paymentTypes','paymentWays'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'voucher_number' => 'required',
            'customer_id' => 'required',
            'amount' => 'required',
            'payment_way' => 'required'
        ]);
        $inputs = $request->all();
        $inputs['remark'] = is_null($inputs['remark']) ? 'Cash reciept' : $inputs['remark'];

        $voucherAlredyExit = PaymentTransaction::where('voucher_number',$request->voucher_number)->withTrashed()->first();
         if($voucherAlredyExit){
            $errors = new MessageBag(['voucher_number' => ['This estimate number is already exists.']]);
            return redirect()->back()->withErrors($errors)->withInput();
        }
        
        $payment = PaymentTransaction::create($inputs);
        return redirect()->route('admin.transactions.create')->with('success', 'Successfully added!');
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
