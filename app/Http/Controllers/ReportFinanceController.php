<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\PaymentTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ReportFinanceController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('report_finance_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $currentYear = Carbon::now()->year;
        $previousYear = $currentYear - 1;
        $yearList = [$currentYear,$previousYear];
        $timeFrame = $request->timeFrame ?? $currentYear;
        $months = $this->getMonthList();
        return view('admin.reports.finance.index',compact('yearList','months','timeFrame'));
    }


    public function fetchSaleReportData(Request $request)
    {
        $timeFrame = $request->timeFrame;
        $saleData = $this->getSaleDataForTimeFrame($timeFrame);
        return response()->json(['saleData' => $saleData,'timeFrame'=>$timeFrame]);
    }

    public function getSaleDataForTimeFrame($timeFrame)
    {
        $labels = [];
        $values = [];
        // dd($timeFrame);
        if (strpos($timeFrame, '-') == false) {
            for ($m = 1; $m <= 12; $m++) {
                $startDate = Carbon::createFromDate($timeFrame, $m, 1)->startOfMonth();
                $endDate = Carbon::createFromDate($timeFrame, $m, 1)->endOfMonth();
                $saleAmount = $this->getSaleAmountForTimeFrame($startDate, $endDate);
                $labels[] = $startDate->format('M'); // Month abbreviation (e.g., Jan, Feb)
                $values[] = $saleAmount;
            }
        } // calculate for motnh with year which gives data of days of months of year
        else {

            $startDate = Carbon::parse($timeFrame)->startOfMonth();
            $endDate = Carbon::parse($timeFrame)->endOfMonth();

            for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                $saleAmount = $this->getSaleAmountForTimeFrame($date, $date);
                $labels[] = $date->format('d'); // Day of the month (1 to 31)
                $values[] = $saleAmount;
            }
        }

        $alldata = ['labels' => $labels, 'values' => $values , 'total_sales' => array_sum($values)];
        // dd($alldata);
        return $alldata;
    }

    public function getSaleAmountForTimeFrame($startDate, $endDate)
    {
        $saleAmount = PaymentTransaction::where('payment_way', 'order_create')
                        ->whereBetween('entry_date', [$startDate, $endDate])
                        ->sum('amount');
        $returnSaleAmount = PaymentTransaction::where('payment_way', 'order_return')
        ->whereBetween('entry_date', [$startDate, $endDate])
        ->sum('amount');
        $finalSaleAmount = $saleAmount - $returnSaleAmount;
        return $finalSaleAmount;
    }

    public function fetchProductReportData(Request $request)
    {
        // dd($request->timeFrame);
        $timeFrame = $request->timeFrame;
        $productData = $this->getProductDataForTimeFrame($timeFrame);
        // dd($productData['totalProductAmount']);
        return response()->json(['productData' => $productData['productSaleData'],'timeFrame'=>$timeFrame,'totalSaleAmount'=>$productData['totalProductAmount']]);

        // $productData = $this->getProductDataForTimeFrame($timeFrame);
        // $allamounts = $this->calculateAmounts($timeFrame);
        // return response()->json(['productData' => $productData,'allamounts' => $allamounts,'timeFrame'=>$timeFrame]);

    }

    public function getProductDataForTimeFrame($timeFrame)
    {
        $amounts = [];
        $labels = [];
        $values = [];
        // dd($timeFrame);
        if (strpos($timeFrame, '-') !== false) {
            // If the time frame contains a hyphen (year-month), calculate for the specified month
            $startDate = Carbon::parse($timeFrame)->startOfMonth();
            $endDate = Carbon::parse($timeFrame)->endOfMonth();
        } else {
            // If the time frame only contains a year, calculate for the whole year
            $startDate = Carbon::createFromDate($timeFrame, 1, 1)->startOfYear();
            $endDate = Carbon::createFromDate($timeFrame, 12, 31)->endOfYear();
        }
        // dd($startDate , $endDate);
        $productSaleData = Order::select('order_products.product_id','products.name AS product_name')
        ->selectRaw('SUM(order_products.total_price) as total_sale_amount')
        ->join('order_products', 'orders.id', '=', 'order_products.order_id')
        ->join('products', 'order_products.product_id', '=', 'products.id')
        ->whereBetween('orders.invoice_date', [$startDate, $endDate])
        ->whereNull('orders.deleted_at') // Exclude soft deleted orders
        ->whereNull('order_products.deleted_at') // Exclude soft deleted order products
        ->groupBy('order_products.product_id')
        ->get()->toArray();

        foreach ($productSaleData as $product) {
            $amounts[] = $product['total_sale_amount'];
        }

        $alldata = ['productSaleData' => $productSaleData, 'totalProductAmount' => array_sum($amounts)];
        return $alldata;
    }


    public function calculateAmounts($timeFrame)
    {
        $totalSale = 0;
        $totalProfit = 0;
        $toalProfitPercent = 0;
        $amounts = [
            'total_sale' => $totalSale,
            'total_sale' => $totalProfit,
            'total_sale' => $toalProfitPercent,
        ];

        return $amounts;
    }

    public function getMonthList()
    {
        $months = [];
        $currentDate = Carbon::now();
        for ($month = 1; $month <= 12; $month++) {
            $currentDate->month($month);
            $months[$currentDate->format('m')] = $currentDate->format('M');
        }

        return $months;
    }

}
