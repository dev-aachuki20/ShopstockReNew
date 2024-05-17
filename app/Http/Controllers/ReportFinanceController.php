<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\PaymentTransaction;
use App\Models\Product;
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

        if (strpos($timeFrame, '-') == false) {
            for ($m = 1; $m <= 12; $m++) {
                $startDate = Carbon::createFromDate($timeFrame, $m, 1)->startOfMonth();
                $endDate = Carbon::createFromDate($timeFrame, $m, 1)->endOfMonth();
                $saleAmount = $this->getSaleAmountForTimeFrame($startDate, $endDate);
                $labels[] = $startDate->format('M');
                $values[] = $saleAmount ?? 0;
            }
        } // calculate for motnh with year which gives data of days of months of year
        else {

            $startDate = Carbon::parse($timeFrame)->startOfMonth();
            $endDate = Carbon::parse($timeFrame)->endOfMonth();

            for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                $saleAmount = $this->getSaleAmountForTimeFrame($date, $date);
                $labels[] = $date->format('d'); // Day of the month (1 to 31)
                $values[] = $saleAmount ?? 0;
            }
        }

        $alldata = ['labels' => $labels, 'values' => $values , 'total_sales' => array_sum($values)];

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
        $timeFrame = $request->timeFrame;
        $productData = $this->getProductDataForTimeFrame($timeFrame);
        return response()->json(['productData' => $productData['productSaleData'],'timeFrame'=>$timeFrame ,'allamounts' => $productData['allamounts']]);
    }

    public function getProductDataForTimeFrame($timeFrame)
    {
        $amounts = [];
        if (strpos($timeFrame, '-') !== false) {
            // If the time frame contains a hyphen (year-month), calculate for the specified month
            $startDate = Carbon::parse($timeFrame)->startOfMonth();
            $endDate = Carbon::parse($timeFrame)->endOfMonth();
        } else {
            // If the time frame only contains a year, calculate for the whole year
            $startDate = Carbon::createFromDate($timeFrame, 1, 1)->startOfYear();
            $endDate = Carbon::createFromDate($timeFrame, 12, 31)->endOfYear();
        }

        $productSaleData = Order::select('order_products.product_id','products.name AS product_name')
        ->selectRaw('SUM(order_products.total_price) as total_sale_amount')
        ->join('order_products', 'orders.id', '=', 'order_products.order_id')
        ->join('products', 'order_products.product_id', '=', 'products.id')
        ->whereBetween('orders.invoice_date', [$startDate, $endDate])
        ->whereNull('orders.deleted_at')
        ->whereNull('order_products.deleted_at')
        ->groupBy('order_products.product_id')
        ->get()->toArray();

        foreach ($productSaleData as $product) {
            $amounts[] = $product['total_sale_amount'] ?? 0;
        }

        $allamounts = $this->calculateAmounts($startDate, $endDate);
        $alldata = ['productSaleData' => $productSaleData,'allamounts' => $allamounts];

        return $alldata;
    }


    public function calculateAmounts($startDate, $endDate)
    {
        $totalSaleMinPrice = 0;
        $totalSaleSoldPrice = 0;
        $totalSaleReturnMinPrice = 0;
        $totalSaleSoldReturnPrice = 0;
        $paymentTransactions = PaymentTransaction::select(
            DB::raw('SUM(order_products.quantity * order_products.price) AS total_sale_price'),
            DB::raw('SUM(order_products.quantity * products.min_sale_price) AS total_min_price'),
            'payment_transactions.payment_way'
        )
        ->join('order_products', 'payment_transactions.order_id', '=', 'order_products.order_id')
        ->join('products', 'order_products.product_id', '=', 'products.id')
        ->whereNull('payment_transactions.deleted_at')
        ->whereNull('order_products.deleted_at')
        ->whereBetween('payment_transactions.entry_date', [$startDate, $endDate])
        ->groupBy('payment_transactions.payment_way')
        ->get();

        foreach($paymentTransactions as $transaction){
            if($transaction->payment_way == 'order_create'){
                $totalSaleMinPrice = $transaction->total_min_price ?? 0;
                $totalSaleSoldPrice = $transaction->total_sale_price ?? 0;
            }elseif($transaction->payment_way == 'order_return'){
                $totalSaleReturnMinPrice = $transaction->total_min_price ?? 0;
                $totalSaleSoldReturnPrice = $transaction->total_sale_price ?? 0;
            }
        }

        $totalFinalSaleMinPrice = $totalSaleMinPrice - $totalSaleReturnMinPrice;
        $totalFinalSalePrice = $totalSaleSoldPrice - $totalSaleSoldReturnPrice;
        $profitAmount= $totalFinalSalePrice -  $totalFinalSaleMinPrice;
        $profitInPercent =  $totalFinalSalePrice ? ($profitAmount/$totalFinalSalePrice) * 100 : 0;

        $amounts = [
            'total_sale' => $totalFinalSalePrice,
            'total_profit' => $profitAmount,
            'total_profit_percent' => $profitInPercent,
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
