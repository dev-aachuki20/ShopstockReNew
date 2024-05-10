<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\CustomerDataTable;
use App\DataTables\CustomerListDataTable;
use App\Models\Area;
use App\Models\Customer;
use App\Models\Group;
use App\Models\CustomerGroup;
use App\Models\Order;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use Auth;
use PDF;
use Illuminate\Support\Facades\DB;
// use Mpdf\Mpdf;

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

    public function customerList(Request $request, CustomerListDataTable $dataTable)
    {
        abort_if(Gate::denies('customer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $listtype = $request->listtype ?? 'ledger';
        $areas = Area::pluck('address','id');
        return $dataTable->with(['listtype'=>$listtype])->render('admin.customer.list',compact('listtype','areas'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(Gate::denies('customer_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $areas = Area::orderBy('address','ASC')->get()->pluck('address', 'id')->prepend(trans('admin_master.g_please_select'), '');
        $types = ['' => trans('quickadmin.qa_please_select')]+config('constant.customer_types');
        $groups = Group::where('parent_id','0')->get();
        return view('admin.customer.create',compact('areas','types','groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      //  dd($request->all());
        abort_if(Gate::denies('customer_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $validator = Validator::make($request->all(), [
            'name' => ['required','string','max:250'],
            'phone_number' => ['required','numeric','digits_between:7,10'],
            'alternate_phone_number' => ['nullable','numeric','digits_between:7,10'],
            'area_id' => ['required','numeric'],
            'is_type' => ['required','string','max:50'],
        ],[
            'area_id.required' => 'The area address field is required.',
            'is_type.required' => 'The type field is required.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->toArray()
            ]);
        }
        $phoneNumber = $request->phone_number;
        $altPhoneNumber = $request->alternate_phone_number;


        $checkPhoneNumber =  Customer::where('name',$request->name)->where(function ($query) use ($phoneNumber,$altPhoneNumber) {
            $query->where('phone_number', '=', $phoneNumber)
                  ->orWhere('alternate_phone_number', '=', $phoneNumber);
            if($altPhoneNumber !=''){
                $query->orWhere('phone_number', '=', $altPhoneNumber)
                       ->orWhere('alternate_phone_number', '=', $altPhoneNumber);
                }
        })->first();


        if($checkPhoneNumber){
            return response()->json([
                'error' => array("name" => "Name AND Phone number or Alternate Phone Number Alredy Exit")
            ]);
        }
        $data = [
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'alternate_phone_number' => $request->alternate_phone_number,
            'area_id' => $request->area_id,
            'is_type' => $request->is_type,
            'credit_limit' => $request->credit_limit ?? 0,
            'created_by'=> Auth::id()
        ];
        $customer =  Customer::create($data);
        addToLog($request,'Customer','Create', $customer);

        // group add
        if($request->is_type == "wholesaler"){
            if($request->has('groups')){
                foreach($request->groups as $row){
                    $groupData = [
                        'group_id' =>  $row,
                        'customer_id' => $customer->id,
                    ];
                    CustomerGroup::create($groupData);
                }
            }
        }
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
            $customerGroup = CustomerGroup::where('customer_id',$id)->get();
            $html = View::make('admin.customer.show',compact('customer','customerGroup'))->render();
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
        $groups = Group::where('parent_id','0')->get();
        $customerGroup = CustomerGroup::where('customer_id',$id)->get()->pluck('group_id');
        return view('admin.customer.edit',compact('areas','types','customer','groups','customerGroup'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        abort_if(Gate::denies('customer_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $isrequired =  !(auth()->user()->hasRole(config('app.roleid.admin'))) ? 'required' : 'nullable';

        $validator = Validator::make($request->all(), [
            'name' => ['required','string','max:250'],
            'phone_number' => ['required','numeric','digits_between:7,10'],
            'alternate_phone_number' => ['nullable','numeric','digits_between:7,10'],
            'area_id' => [$isrequired,'numeric'],
            'is_type' => [$isrequired,'string','max:50']
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->toArray()
            ]);
        }

        // for validate
        $phoneNumber = $request->phone_number;
        $altPhoneNumber = $request->alternate_phone_number;
        $checkPhoneNumber =  Customer::where('name',$request->name)->where('id','<>',$id)->where(function ($query) use ($phoneNumber,$altPhoneNumber) {
            $query->where('phone_number', '=', $phoneNumber)
                  ->orWhere('alternate_phone_number', '=', $phoneNumber);
            if($altPhoneNumber !=''){
                $query->orWhere('phone_number', '=', $altPhoneNumber)
                       ->orWhere('alternate_phone_number', '=', $altPhoneNumber);
                }
        })->first();
        if($checkPhoneNumber){
            return response()->json([
                'error' => array("name" => "Name AND Phone number or Alternate Phone Number Alredy Exit")
            ]);
        }
        // for validate

        $customer = Customer::findOrFail($id);
        $oldvalue = $customer->getOriginal();
        $customer->name = $request->name ?? $customer->name;
        $customer->phone_number = $request->phone_number ?? $customer->phone_number;
        $customer->alternate_phone_number = $request->alternate_phone_number  ?? $customer->alternate_phone_number;
        $customer->area_id = $request->area_id  ?? $customer->area_id;
        $customer->is_type = $request->is_type  ?? $customer->is_type;
        $customer->credit_limit = $request->credit_limit ?? ($customer->credit_limit ?? 0);
        $customer->updated_by = Auth::id();
        $customer->save();
        $newValue = $customer->refresh();

         // group add
         CustomerGroup::where('customer_id',$customer->id)->delete();
         if($request->is_type == "wholesaler"){
            if($request->has('groups')){
                foreach($request->groups as $row){
                    $groupData = [
                        'group_id' =>  $row,
                        'customer_id' => $customer->id,
                    ];
                    CustomerGroup::create($groupData);
                }
            }
        }
        // group add

        addToLog($request,'Customer','Edit', $newValue ,$oldvalue);
        return response()->json(['success' => 'Update successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        // dd('remove from client');
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

    // print customers

    public function allCustomerPrintView(Request $request)
    {
        abort_if(Gate::denies('customer_print'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = Customer::query();
        $areaNames = [];

        if(request()->has('area_id') && !empty(request()->area_id))
        {
            $area_ids = explode(',', request()->area_id);
            $area_ids = array_map('intval', $area_ids);
            $query->whereIn('area_id', $area_ids);
            $areaNames = Area::whereIn('id', $area_ids)->pluck('address')->toArray();
        }

        if(request()->has('customer_id') && !empty(request()->customer_id))
        {
            $customer_ids = explode(',', request()->customer_id);
            $customer_ids = array_map('intval', $customer_ids);
            $query->whereIn('id', $customer_ids);
        }

        $listtype = isset(request()->listtype) && request()->listtype ? $request->listtype  : 'ledger';
        switch ($listtype) {
            case 'ledger':
                $query = $query->newQuery()
                ->select('customers.*')
                ->whereExists(function ($query) {
                    $query->selectRaw("SUM(CASE WHEN payment_type='debit' THEN amount ELSE 0 END) AS total_debit_amount")
                        ->selectRaw("SUM(CASE WHEN payment_type='credit' THEN amount ELSE 0 END) AS total_credit_amount")
                        ->selectRaw("SUM(CASE WHEN payment_type = 'debit' THEN amount ELSE 0 END) - SUM(CASE WHEN payment_type = 'credit' THEN amount ELSE 0 END) AS total_balance")
                        ->from('payment_transactions')
                        ->whereColumn('payment_transactions.customer_id', 'customers.id')
                        // ->where('payment_transactions.remark', '<>', 'Opening balance')
                        ->whereNull('payment_transactions.deleted_at')
                        ->groupBy('payment_transactions.customer_id')
                        ->havingRaw('total_balance != 0');
                });

                break;

            case 'all':
                $query = $query->newQuery()->select(['customers.*'])/* ->orderBy('Name','ASC') */;
                break;
            default:
            return abort(404);
            break;
        }
        $allcustomers = $query->with('area')->get();
        return view('admin.customer.print-customer-list',compact('allcustomers','areaNames'))->render();
    }

    public function historyFilter(Request $request)
    {
        $customerId = $request->customer;
        $openingBalance =0 ;
        if(is_null($request->from_date) && is_null($request->to_date)){
             $customer = Customer::findOrFail($customerId);
        }else{
             $startDate = Carbon::parse($request->from_date)->format('Y-m-d');
             $endDate  = Carbon::parse($request->to_date)->format('Y-m-d');

             $previousDebitBalance = PaymentTransaction::whereDate('entry_date','<',date('Y-m-d', strtotime($startDate)))->where('customer_id',$customerId)->where('payment_type','debit')->sum('amount');
             $previousCreditBalance = PaymentTransaction::whereDate('entry_date','<',date('Y-m-d', strtotime($startDate)))->where('customer_id',$customerId)->where('payment_type','credit')->sum('amount');
             $openingBalance = ((float)$previousCreditBalance - (float)$previousDebitBalance);

             $customer = Customer::with(['transaction'=>function($query) use($startDate,$endDate){
                     $query->whereDate('entry_date','>=',date('Y-m-d', strtotime($startDate)))->whereDate('entry_date','<=',date('Y-m-d', strtotime($endDate)));
             }])->where('id',$customerId)->first();
        }
        $view = view('admin.customer.payment_history', compact('customer','openingBalance'))->render();
        return response()->json(array('success' => true,'viewRender' =>$view), 200);
    }

    public function getCustomerNameList(Request $request)
    {
        $customerlist =   Customer::withTrashed() ->where('name', 'like', "%{$request->name}%")->get();
        $html = "";
        if(count($customerlist) > 0){
            $html .= "<ul>";
            foreach($customerlist as $row){
                $html .= "<li>".$row->name."</li>";
            }
            $html .= "</ul>";
        }
        return response()->json(array('success' => true,'viewData' =>$html), 200);
    }


    public function viewCostomer(Request $request )
    {
        abort_if(Gate::denies('customer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $customer = Customer::findOrFail($request->id);
        $customerCreatedAtYear = Carbon::createFromFormat('Y-m-d H:i:s', $customer->created_at)->year;
        $currentYear = Carbon::now()->year;
        $yearlist = range(2021, $currentYear);
        $currentMonth = Carbon::now()->month;
        $year = $request->year ?? $currentYear;
        $customerCreatedAtMonth = Carbon::createFromFormat('Y-m-d H:i:s', $customer->created_at)->month;
        if ($year == $customerCreatedAtYear)
        {
            $startDate = Carbon::create($year,$customerCreatedAtMonth, 1)->startOfMonth();
        }
        elseif($year < $customerCreatedAtYear){
            $startDate = Carbon::create($customerCreatedAtYear,1, 1)->startOfMonth();
        }
        else{
            $startDate = Carbon::create($year,1, 1)->startOfMonth();
        }

        $endDate = ($year == $currentYear) ? Carbon::create($year, $currentMonth, 1)->endOfMonth() : Carbon::create($year, 12, 1)->endOfMonth();
        $openingBalance = GetYearOpeningBalance($customer->id,$year);
        $estimateData = PaymentTransaction::selectRaw("SUM(amount) as total_amount, DATE_FORMAT(entry_date, '%Y-%m') as month, 'sales' as type")
        ->where('customer_id', $request->id)->where('payment_way', 'order_create')->whereBetween('entry_date', [$startDate, $endDate])->groupBy(DB::raw('YEAR(entry_date)'), DB::raw('MONTH(entry_date)'), 'type')->get();

        $cashReceiptData = PaymentTransaction::selectRaw("SUM(amount) as total_amount, DATE_FORMAT(entry_date, '%Y-%m') as month, 'cashreceipt' as type")->where('customer_id', $request->id)->whereIn('payment_way', ['by_cash', 'by_check', 'by_account'])->whereNotNull('voucher_number')
        ->where('remark', '!=', 'Opening balance')->whereBetween('entry_date', [$startDate, $endDate])->groupBy(DB::raw('YEAR(entry_date)'), DB::raw('MONTH(entry_date)'), 'type')->get();

        $estimateReturnData = PaymentTransaction::selectRaw("SUM(amount) as total_amount, DATE_FORMAT(entry_date, '%Y-%m') as month, 'sales_return' as type")->where('customer_id', $request->id)->where('payment_way', 'order_return')->whereBetween('entry_date', [$startDate, $endDate])->groupBy(DB::raw('YEAR(entry_date)'), DB::raw('MONTH(entry_date)'), 'type')->get();

        $monthlyData = [];
        $currentMonth = $startDate->copy();
        while ($currentMonth <= $endDate) {
            $monthKey = $currentMonth->format('Y-m');
            $monthlyData[$monthKey]['month'] = $monthKey;
            $monthlyData[$monthKey]['sales'] = 0;
            $monthlyData[$monthKey]['cashreceipt'] = 0;
            $monthlyData[$monthKey]['sales_return'] = 0;
            foreach($estimateData as $esrow){
                if ($esrow['month'] === $monthKey) {
                    $monthlyData[$monthKey]['sales'] = $esrow['total_amount'];
                    break;
                }
            }
            foreach($cashReceiptData as $cashrow){
                if ($cashrow['month'] === $monthKey) {
                    $monthlyData[$monthKey]['cashreceipt'] = $cashrow['total_amount'];
                    break;
                }
            }
            foreach($estimateReturnData as $esreturnrow){
                if ($esreturnrow['month'] === $monthKey) {
                    $monthlyData[$monthKey]['sales_return'] = $esreturnrow['total_amount'];
                    break;
                }
            }
            $currentMonth->addMonth();
        }

        //dd($monthlyData);
        // Sort the monthly data by month
        ksort($monthlyData);
        return view('admin.customer.view_list_customer',compact('customer','openingBalance','monthlyData','yearlist','year'));
    }

    public function viewCustomerDetail(Customer $customer,string $month)
    {
        $year = substr($month, 0, 4);
        $openingBalance = GetMonthWiseOpeningBalance($customer->id,$month);
        $estimateData = PaymentTransaction::selectRaw("*,'sales' as type")->where('customer_id', $customer->id)->where('payment_way', 'order_create')->whereRaw("DATE_FORMAT(entry_date, '%Y-%m') = ?", [$month]);

        $cashReceiptData = PaymentTransaction::selectRaw("*, 'cashreceipt' as type")->where('customer_id', $customer->id)->whereIn('payment_way', ['by_cash', 'by_check', 'by_account'])->whereNotNull('voucher_number')->where('remark', '!=', 'Opening balance')->whereRaw("DATE_FORMAT(entry_date, '%Y-%m') = ?", [$month]);

        $estimateReturnData = PaymentTransaction::selectRaw("*,'sales_return' as type")->where('customer_id', $customer->id)->where('payment_way', 'order_return')->whereRaw("DATE_FORMAT(entry_date, '%Y-%m') = ?", [$month]);

        $alldata = collect($estimateData->get())->merge($cashReceiptData->get())->merge($estimateReturnData->get());
        $alldata= $alldata->sortBy('entry_date');
        return view('admin.customer.view_customer_month_detail',compact('customer','alldata','month','openingBalance'));
    }

    public function printPaymentHistory($type,$customerId,$yearmonth)
    {
        ini_set('max_execution_time', 300);
        $from = null;
        $to = null;

        $year = substr($yearmonth, 0, 4);
        $month = substr($yearmonth, 5, 2);
        $startDate = Carbon::create($year,$month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();
        try{
            if(!is_numeric($customerId)){
                $customerId = decrypt($customerId);
            }

            $openingBalance = 0;
            if($customerId){

                $openingBalance = GetMonthWiseOpeningBalance($customerId,$yearmonth);

                $customer = Customer::with(['transaction'=>function($query) use($startDate,$endDate){
                        $query->with(['order'])->whereDate('entry_date','>=',date('Y-m-d', strtotime($startDate)))->whereDate('entry_date','<=',date('Y-m-d', strtotime($endDate)));
                }])->where('id',$customerId)->first();

                $pdfData['customer']  = $customer;
                $pdfData['from_date'] = $startDate;
                $pdfData['to_date']   = $endDate;
                $pdfData['openingBalance']   = $openingBalance;
                if($type == 'print-product-ledger')
                {
                    $pdfFileName = 'Print_Ledeger_'.$yearmonth.'.pdf';
                    $pdf = PDF::loadView('admin.exports.pdf.ledger_print',$pdfData);
                    $pdf->setPaper('A5', 'portrait');
                    $pdf->setOption('charset', 'UTF-8');
                    return $pdf->stream($pdfFileName, ['Attachment' => false]);

                }else if($type == 'print-statement')
                {

                    $pdfFileName = 'Print_Statement_'.$yearmonth.'.pdf';
                    $pdf = PDF::loadView('admin.exports.pdf.statement_print',$pdfData);
                    $pdf->setPaper('A5', 'portrait');
                    $pdf->setOption('charset', 'UTF-8');
                    return $pdf->stream($pdfFileName, ['Attachment' => false]);

                    //return view('admin.exports.pdf.statement_pdf_header', $pdfData);
                }
            }

        }catch(\Exception $e){
           // dd($e->getMessage());
            return abort(404);
        }
    }

    public function deleteCustomerDateEstimates(Request $request)
    {
        $customer = Customer::findOrFail($request->customer_id);
        $to_date = $request->to_date;

        try{
            DB::beginTransaction();
            Order::where('customer_id', $customer->id)
            ->whereDate('invoice_date', '<=', $to_date)
            ->get()
            ->each(function($order) {
            $order->delete();
            });

            PaymentTransaction::where('customer_id', $customer->id)
            ->whereDate('entry_date', '<=', $to_date)
            ->get()
            ->each(function($transaction) {
                $transaction->delete();
            });
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => trans('messages.crud.delete_record'),
            ], 200);

        }catch(\Exception $e){
            DB::rollBack();
           // dd($e->getMessage());
            return response()->json([
                'success' => false,
                'message' =>"Something Went Wrong !",
            ], 505);
        }
    }

}
