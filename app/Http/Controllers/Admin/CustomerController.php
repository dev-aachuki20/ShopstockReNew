<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\CustomerDataTable;
use App\DataTables\CustomerListDataTable;
use App\Models\Area;
use App\Models\Customer;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use Auth;
class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CustomerDataTable $dataTable)
    {
        abort_if(Gate::denies('customer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return $dataTable->render('admin.customer.index');
    }
    public function customerList(CustomerListDataTable $dataTable)
    {
        abort_if(Gate::denies('customer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return $dataTable->render('admin.customer.list');
    }
    public function viewCostomer(Request $request)
    {
        abort_if(Gate::denies('customer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $customer = Customer::findOrFail($request->id);
        $openingBalance = PaymentTransaction::where('customer_id',$request->id)->whereIn('payment_way',['by_cash','by_split'])->where('remark','Opening balance')->orderBy('id','ASC')->sum('amount');
        return view('admin.customer.view_list_customer',compact('customer','openingBalance'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(Gate::denies('customer_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $areas = Area::orderBy('address','ASC')->get()->pluck('address', 'id')->prepend(trans('admin_master.g_please_select'), '');
        $types = ['' => trans('quickadmin.qa_please_select')]+config('constant.customer_types');
        return view('admin.customer.create',compact('areas','types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_if(Gate::denies('customer_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $validator = Validator::make($request->all(), [
            'name' => ['required','string','max:250'], 
            'phone_number' => ['required','numeric','digits_between:7,15'], 
            'email' => [
                'required','max:50','email',
                Rule::unique('customers', 'email')->whereNull('deleted_at'),
            ],
            'area_id' => ['required','numeric'], 
            'is_type' => ['required','string','max:50'], 
            'opening_blance' => ['required','numeric'], 
            'credit_limit' => ['required','numeric'], 
        ]); 
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->toArray()
            ]);
        }

        $data = [
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'area_id' => $request->area_id,
            'is_type' => $request->is_type,            
            'credit_limit' => $request->credit_limit ?? 0,
            'created_by'=> Auth::id()
        ];
        $customer =  Customer::create($data);
        addToLog($request,'Customer','Create', $customer);

        $transactionDetails = array(
            'customer_id' => $customer->id,
            'payment_type' => 'credit',
            'payment_way' => 'by_cash',
            'remark' => 'Opening balance',
            'amount' => $request->opening_blance,
            'entry_date' => Carbon::now()->format(config('app.date_format')),
            'created_by' => Auth::id(),
        );
        $transaction = PaymentTransaction::create($transactionDetails);
        addToLog($request,'PaymentTransaction','Create From Customer', $transaction);
        return response()->json(['success' => 'Created successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        abort_if(Gate::denies('customer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if($request->ajax()){
            $id = decrypt($id);
            $customer = Customer::where('id',$id)->first();
            $html = View::make('admin.customer.show',compact('customer'))->render();
            return response()->json(['success' => true, 'html' => $html]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        abort_if(Gate::denies('customer_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $customer = Customer::findOrFail($id);
        $areas = Area::orderBy('address','ASC')->get()->pluck('address', 'id')->prepend(trans('admin_master.g_please_select'), '');
        $types = ['' => trans('quickadmin.qa_please_select')]+config('constant.customer_types');
        return view('admin.customer.edit',compact('areas','types','customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        abort_if(Gate::denies('customer_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $validator = Validator::make($request->all(), [
            'name' => ['required','string','max:250'], 
            'phone_number' => ['required','numeric','digits_between:7,15'], 
            'email' => [
                'required','max:50','email',
                Rule::unique('customers', 'email')->whereNull('deleted_at')->ignore($id),
            ],
            'area_id' => ['required','numeric'], 
            'is_type' => ['required','string','max:50'], 
            'credit_limit' => ['required','numeric'], 
        ]); 
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->toArray()
            ]);
        }

        $customer = Customer::findOrFail($id);
        $oldvalue = $customer->getOriginal(); 
        $customer->name = $request->name; 
        $customer->phone_number = $request->phone_number; 
        $customer->email = $request->email; 
        $customer->area_id = $request->area_id; 
        $customer->is_type = $request->is_type; 
        $customer->credit_limit = $request->credit_limit ?? 0; 
        $customer->updated_by = Auth::id(); 
        $customer->save();
        $newValue = $customer->refresh();
        addToLog($request,'Customer','Edit', $newValue ,$oldvalue); 
        return response()->json(['success' => 'Update successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        abort_if(Gate::denies('customer_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $record = Customer::find(decrypt($id));
        $oldvalue = $record->getOriginal(); 
        $record->updated_by = Auth::id();
        $record->save();
        $newValue = $record->refresh();
        addToLog($request,'Customer','Delete', $newValue ,$oldvalue); 
        $record->delete();
        return response()->json(['success' => 'Delete successfully.']);
    }


    public function historyFilter(Request $request){        
        $customerId = $request->customer;
        $openingBalance = 0;
        $openingBalance = PaymentTransaction::where('customer_id',$customerId)->whereIn('payment_way',['by_cash','by_split'])->where('remark','Opening balance')->orderBy('id','ASC')->sum('amount');
        if(is_null($request->from_date) && is_null($request->to_date)){
             $customer = Customer::findOrFail($customerId);
        }else{            
             $startDate = Carbon::parse($request->from_date)->format('Y-m-d');
             $endDate  = Carbon::parse($request->to_date)->format('Y-m-d');
 
             $previousDebitBalance = PaymentTransaction::whereDate('entry_date','<',date('Y-m-d', strtotime($startDate)))->where('customer_id',$customerId)->where('payment_type','debit')->sum('amount');
             $previousCreditBalance = PaymentTransaction::whereDate('entry_date','<',date('Y-m-d', strtotime($startDate)))->where('customer_id',$customerId)->where('payment_type','credit')->sum('amount');
             $openingBalance = $openingBalance + ((float)$previousCreditBalance - (float)$previousDebitBalance);
 
             $customer = Customer::with(['transaction'=>function($query) use($startDate,$endDate){
                     $query->whereDate('entry_date','>=',date('Y-m-d', strtotime($startDate)))->whereDate('entry_date','<=',date('Y-m-d', strtotime($endDate)));
             }])->where('id',$customerId)->first();
        }         
        $view = view('admin.customer.payment_history', compact('customer','openingBalance'))->render();       
        return response()->json(array('success' => true,'viewRender' =>$view), 200);
     }
}
