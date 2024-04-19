<?php

namespace App\Http\Controllers;

use App\DataTables\ReportCustomerDataTable;
use App\Models\Area;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;

class ReportsCustomerController extends Controller
{

    public function index(ReportCustomerDataTable $dataTable)
    {
        abort_if(Gate::denies('report_customer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $areas = Area::pluck('address','id');
        return $dataTable->render('admin.reports.customers.index',compact('areas'));
    }

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

        $allcustomers = $query->select('customers.*')
        ->whereExists(function ($query) {
            $query->select('id')
                ->from('payment_transactions')
                ->whereColumn('payment_transactions.customer_id', 'customers.id')
                ->where('payment_transactions.remark', '<>', 'Opening balance');
        })->with('area')->get();

        return view('admin.reports.customers.print-customer-list',compact('allcustomers','areaNames'))->render();
    }
}
