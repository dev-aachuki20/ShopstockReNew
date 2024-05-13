<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\PaymentTransactionDataTable;
use App\Models\Customer;
use App\Http\Requests\PaymentTransactions\StoreUpdatePaymentTransactionsRequest;
use App\Models\Notification;
use App\Models\PaymentTransaction;
use App\Models\PaymentTransactionHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

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
        abort_if(Gate::denies('transaction_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $customers = Customer::select('id', 'name', 'credit_limit', 'is_type')->orderBy('id', 'desc')->get();
        $paymentTypes = array('' => trans('quickadmin.qa_please_select_customer'), 'credit' => 'Credit', 'debit' => 'Debit');
        $paymentWays = array('by_cash' => 'By Cash', 'by_check' => 'By Check', 'by_account' => 'By Account');

        $currentDate = Carbon::now()->format('Y-m-d');
        $invoice_number = getNewInvoiceNumber('','new_cash_receipt',$currentDate);
        return view('admin.payment_transactions.create', compact('customers', 'paymentTypes', 'paymentWays','invoice_number'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdatePaymentTransactionsRequest $request)
    {
        abort_if(Gate::denies('transaction_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $inputs = $request->all();
        $inputs['remark'] = is_null($inputs['remark']) ? 'Cash reciept' : $inputs['remark'];
        $inputs['voucher_number'] = $request->voucher_number ? $request->voucher_number : getNewInvoiceNumber('','new_cash_receipt',$request->entry_date);

        $payment = PaymentTransaction::create($inputs);
        addToLog($request,'Cash receipt','Create', $payment);
        $this->recordCashReceiptHistory($payment, $request->all());

        // $message = trans('quickadmin.notify_message.cash_receipt_create',['party_name' => $payment->customer->name,'invoice_number'=>$payment->voucher_number,'created_by'=>auth()->user()->name]);
        // $notify_data = [
        //     'subject'           => trans('quickadmin.notify_subject.cash_receipt_create'),
        //     'message'           => $message,
        //     'notification_type' => trans('quickadmin.notification_type.cash_receipt_create')
        // ];
        // storeNotification($notify_data);

        return redirect()->route('admin.transactions.create')->with('success', 'Successfully added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request,string $id)
    {
        abort_if(Gate::denies('transaction_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            $id = decrypt($id);
            $transaction = PaymentTransaction::withTrashed()->find($id);
            $html = View::make('admin.payment_transactions.show', compact('transaction'))->render();
            return response()->json(['success' => true, 'html' => $html]);
        }
    }

    public function showHistory($type=null,string $id)
    {
        abort_if(Gate::denies('transaction_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $id = decrypt($id);
        $alltransaction = PaymentTransactionHistory::where('payment_transaction_id',$id)->get();
        $cash_receipt = PaymentTransaction::with('customer')->select('customer_id','voucher_number','entry_date')->where('id',$id)->first();
        PaymentTransaction::where('id', $id)->update(['is_modified' => 0]);

        $html = View::make('admin.payment_transactions.show_history', compact('alltransaction','cash_receipt'))->render();
        return response()->json(['success' => true, 'html' => $html]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        abort_if(Gate::denies('transaction_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $id = decrypt($id);
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
        abort_if(Gate::denies('transaction_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $transaction = PaymentTransaction::findOrFail($id);
        $oldvalue = $transaction->getOriginal();
        $request['is_modified'] = 1;
        $transaction->update($request->all());
        $newValue = $transaction->refresh();
        addToLog($request,'Cash receipt','Edit', $newValue ,$oldvalue);
        // Record history
        $this->recordCashReceiptHistory($transaction, $request->all());

        $message = trans('quickadmin.notify_message.cash_receipt_edit',['party_name' => $transaction->customer->name,'invoice_number'=>$transaction->voucher_number,'created_by'=>auth()->user()->name]);
        $notify_data = [
            'subject'           => trans('quickadmin.notify_subject.cash_receipt_edit'),
            'message'           => $message,
            'notification_type' => trans('quickadmin.notification_type.cash_receipt_edit')
        ];
        storeNotification($notify_data);

        return response()->json(['success' => 'Update successfully.']);
    }

    protected function recordCashReceiptHistory($transaction, $cash_receipt)
    {
        $cash_receipt['payment_transaction_id'] = $transaction->id;
        $cash_receipt['voucher_number'] = $transaction->voucher_number;
        PaymentTransactionHistory::create($cash_receipt);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // abort_if(Gate::denies('transaction_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // dd($id);
        $id = decrypt($id);
        $transaction  = PaymentTransaction::findOrFail($id);
        if(!is_null($transaction->order))
        {
            $message = trans('quickadmin.notify_message.order_' . ($transaction->order->order_type == 'create' ? 'delete' : 'return_delete'), [
                'party_name' => $transaction->order->customer->name,
                'invoice_number' =>$transaction->order->invoice_number,
                'created_by' => auth()->user()->name
            ]);
            $notify_data = [
                'subject' => trans('quickadmin.notify_subject.order_' . ($transaction->order->order_type == 'create' ? 'delete' : 'return_delete')),
                'message'           => $message,
                'notification_type' => trans('quickadmin.notification_type.order_' . ($transaction->order->order_type == 'create' ? 'delete' : 'return_delete')),
            ];
            storeNotification($notify_data);
            $transaction->order->orderProduct()->delete();
            $transaction->order->delete();
        }else{
            $message = trans('quickadmin.notify_message.cash_receipt_delete',['party_name' => $transaction->customer->name,'invoice_number'=>$transaction->voucher_number,'created_by'=>auth()->user()->name]);
            $notify_data = [
                'subject'           => trans('quickadmin.notify_subject.cash_receipt_delete'),
                'message'           => $message,
                'notification_type' => trans('quickadmin.notification_type.cash_receipt_delete')
            ];
            storeNotification($notify_data);
        }

        $transaction->delete();
        return response()->json([
            'message' => 'Successfully deleted!',
            'alert-type'=> trans('quickadmin.alert-type.success')
        ]);
    }

    public function typeFilter(PaymentTransactionDataTable $dataTable, $type)
    {
        if($type == "current_estimate"){
            return redirect()->route('admin.transactions.type',['sales']);
        }
        $startDate = $_REQUEST['start_date']??'';
        $endDate = $_REQUEST['end_date']??'';
        return $dataTable->with(['type' => $type,'startDate'=> $startDate,'endDate'=>$endDate])->render('admin.payment_transactions.index',compact('type'));
    }
}
