<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\PaymentTransactionDataTable;
use App\Models\Customer;
use App\Http\Requests\PaymentTransactions\StoreUpdatePaymentTransactionsRequest;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\View;

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
        $customers = Customer::select('id', 'name', 'credit_limit', 'is_type')->orderBy('id', 'desc')->get();
        $paymentTypes = array('' => trans('quickadmin.qa_please_select_customer'), 'credit' => 'Credit', 'debit' => 'Debit');
        $paymentWays = array('by_cash' => 'By Cash', 'by_check' => 'By Check', 'by_account' => 'By Account');
        return view('admin.payment_transactions.create', compact('customers', 'paymentTypes', 'paymentWays'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdatePaymentTransactionsRequest $request)
    {
        $inputs = $request->all();
        $inputs['remark'] = is_null($inputs['remark']) ? 'Cash reciept' : $inputs['remark'];
        $inputs['voucher_number'] = getNewInvoiceNumber('','new_cash_receipt');
        // $voucherAlredyExit = PaymentTransaction::where('voucher_number',$request->voucher_number)->withTrashed()->first();
        //  if($voucherAlredyExit){
        //     $errors = new MessageBag(['voucher_number' => ['This estimate number is already exists.']]);
        //     return redirect()->back()->withErrors($errors)->withInput();
        // }
        
        $payment = PaymentTransaction::create($inputs);
        return redirect()->route('admin.transactions.create')->with('success', 'Successfully added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request,string $id)
    {
        if ($request->ajax()) { 
            $id = decrypt($id);            
            $transaction = PaymentTransaction::withTrashed()->find($id);
            $html = View::make('admin.payment_transactions.show', compact('transaction'))->render();
            return response()->json(['success' => true, 'html' => $html]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $transaction = PaymentTransaction::findOrFail($id);
        $customers = Customer::orderBy('id','desc')->get()->pluck('name', 'id')->prepend(trans('quickadmin.qa_please_select_customer'), '');
        $paymentTypes = array(''=>trans('quickadmin.qa_please_select_customer'),'credit' => 'Credit','debit'=>'Debit');
        $paymentWays = array('by_cash' => 'By Cash','by_check' => 'By Check','by_account' => 'By Account');
        return view('admin.payment_transactions.edit', compact('customers', 'paymentTypes', 'paymentWays','transaction'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdatePaymentTransactionsRequest $request, $id)
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

    public function typeFilter(PaymentTransactionDataTable $dataTable, $type)
    {
        return $dataTable->with(['type' => $type])->render('admin.payment_transactions.index',compact('type'));
    }
}
