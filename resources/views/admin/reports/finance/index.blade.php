
@extends('layouts.app')
@section('title')@lang('quickadmin.reports.finance_report') @endsection
@section('customCss')
<meta name="csrf-token" content="{{ csrf_token() }}" >
<link rel="stylesheet" href="{{ asset('admintheme/assets/css/printView-datatable.css')}}">
<style>
#chartdiv1 , #chartdiv2 {
  width: 100%;
  height: 380px;
}
</style>
@endsection

@section('main-content')
    <section class="section roles chart_admin_page" style="z-index: unset">
        <div class="section-body">
            <div class="row row-gap-30">
                <div class="col-auto leftside">
                    <div class="row row-gap-30">
                        <div class="col-12">
                            <div class="yearcard">
                                <div class="title_header">
                                    <h5 class="title">@lang('quickadmin.reports.year')</h5>
                                    {{-- <ul class="icons">
                                        <li><a href="javascript:;"><x-svg-icon icon="filter" /></a></li>
                                        <li><a href="javascript:;"><x-svg-icon icon="filter-outer" /></a></li>
                                    </ul> --}}
                                </div>
                                <div class="contant_body">
                                    <ul>
                                        @foreach ($yearList as $year)
                                        <li><button class="addyearbtn report-year {{ $year == $timeFrame ? 'active' : '' }}" data-year="{{ $year }}">{{ $year }}</button></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="yearcard">
                                <div class="title_header">
                                    <h5 class="title">@lang('quickadmin.reports.month')</h5>
                                    {{-- <ul class="icons">
                                        <li><a href="javascript:;"><x-svg-icon icon="filter" /></a></li>
                                        <li><a href="javascript:;"><x-svg-icon icon="filter-outer" /></a></li>
                                    </ul> --}}
                                </div>
                                <div class="contant_body">
                                    <ul>
                                        @foreach ($months as $monthNumber => $monthName)
                                        <li><button class="addyearbtn report-month" data-month="{{ $monthNumber }}">{{ $monthName }}</button></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="row row-gap-30">
                        <div class="col-12">
                            <div class="row row-gap-30">
                                <div class="col-xl-4 col-md-6">
                                    <div class="sellcard yearcard">
                                        <div class="contant">
                                            <h5 class="title">@lang('quickadmin.reports.total_sale')</h5>
                                            <h3 class="amount" id="totalSales">0</h3>
                                        </div>
                                        <div class="sellicon"><x-svg-icon icon="money" /></div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6 px-xl-0">
                                    <div class="sellcard yearcard">
                                        <div class="contant">
                                            <h5 class="title">@lang('quickadmin.reports.total_profit')</h5>
                                            <h3 class="amount" id="totalProfit">0</h3>
                                        </div>
                                        <div class="sellicon"><x-svg-icon icon="total-profit" /></div>
                                    </div>
                                </div>
                                <div class="col-xl-4">
                                    <div class="sellcard yearcard">
                                        <div class="contant">
                                            <h5 class="title">@lang('quickadmin.reports.profit_percent')  </h5>
                                            <h3 class="amount" id="totalProfitPercent">0%</h3>
                                        </div>
                                        <div class="sellicon"><x-svg-icon icon="saving" /></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row row-gap-30">
                                <div class="col-xl-7">
                                    <div class="chartcard ">
                                        <div class="chart_header">
                                            <h5 class="title "><span><x-svg-icon icon="week" /></span> <strong class="timeFrame"> </strong></h5>
                                        </div>
                                        <div class="chartbody bg-light">
                                            {{-- chart --}}
                                            <div id="chartdiv1"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-5 pl-xl-0">
                                    <div class="chartcard">
                                        <div class="chart_header">
                                            <h5 class="title"><span><x-svg-icon icon="product-icon" /></span> @lang('quickadmin.reports.product')</h5>
                                        </div>
                                        <div class="chartbody bg-light">
                                            <div class="chart-box" id="progress-bars-container">
                                                <ul>
                                                    {{-- <li>
                                                        <span class="pro-name">Cooler Wheel Set</span>
                                                        <div class="chart-content">
                                                            <div class="progress w-100">
                                                                <div class="progress-bar" role="progressbar" style="width: 75%;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                                                <div class="progress-value">25%</div>
                                                            </div>
                                                        </div>
                                                    </li> --}}
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customJS')
<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
<script>
am4core.ready(function() {
    // Themes begin
    am4core.useTheme(am4themes_animated);

    // Create a Sale chart instance
    var chart1 = am4core.create("chartdiv1", am4charts.XYChart);
    // chart1.scrollbarX = new am4core.Scrollbar();
    var categoryAxis1 = chart1.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis1.dataFields.category = "months";
    categoryAxis1.renderer.grid.template.location = 0;
    categoryAxis1.renderer.minGridDistance = 30;
    categoryAxis1.renderer.labels.template.horizontalCenter = "right";
    categoryAxis1.renderer.labels.template.verticalCenter = "middle";
    categoryAxis1.renderer.labels.template.rotation = 270;
    categoryAxis1.tooltip.disabled = true;
    categoryAxis1.renderer.minHeight = 110;
    var valueAxis1 = chart1.yAxes.push(new am4charts.ValueAxis());
    valueAxis1.renderer.minWidth = 50;
    chart1.logo.disabled = true;
    chart1.paddingTop = am4core.percent(10);
    chart1.paddingBottom = am4core.percent(0);
    chart1.marginBottom = am4core.percent(0);

    // Define the updateSaleChart function
    window.updateSaleChart = function(timeFrame) {
        var data = { timeFrame: timeFrame };
        showLoader();
        $.ajax({
            type: 'GET',
            url: "{{ route('admin.reports.finance.fetchReportData') }}",
            data: data,
            success: function(response) {
                // hideLoader();
                var newSaleData = response.saleData;
                var chartData = newSaleData.labels.map(function(label, index) {
                    return { months: label, amounts: newSaleData.values[index] };
                });
                chart1.data = chartData;

                $('.timeFrame').html("@lang('quickadmin.reports.sale_duration', ['saleduration' => '" + response.timeFrame + "'])");
                // Create series (only once)
                if (chart1.series.length === 0) {
                    var series1 = chart1.series.push(new am4charts.ColumnSeries());
                    series1.sequencedInterpolation = true;
                    series1.dataFields.valueY = "amounts";
                    series1.dataFields.categoryX = "months";
                    series1.tooltipText = "Sale amount: [{categoryX}: bold]{valueY}[/]";
                    series1.columns.template.strokeWidth = 0;
                    series1.tooltip.pointerOrientation = "vertical";
                    series1.columns.template.fill = am4core.color("#6794dc");
                    series1.columns.template.column.cornerRadiusTopLeft = 10;
                    series1.columns.template.column.cornerRadiusTopRight = 10;
                    series1.columns.template.column.fillOpacity = 0.8;
                    var hoverState = series1.columns.template.column.states.create("hover");
                    hoverState.properties.cornerRadiusTopLeft = 10;
                    hoverState.properties.cornerRadiusTopRight = 10;
                    hoverState.properties.fillOpacity = 1;
                    chart1.cursor = new am4charts.XYCursor();
                }
            },
            error: function(error) {
                hideLoader();
                console.error(error);
            }
        });
    }

    // Define the updateProductChart function
    window.updateProductChart = function(timeFrame) {
        var data = { timeFrame: timeFrame };
        // showLoader();
        $.ajax({
            type: 'GET',
            url: "{{ route('admin.reports.finance.fetchProductReportData') }}",
            data: data,
            success: function(response) {

                var newProductData = response.productData;
                var allamounts = response.allamounts;
                var totalSaleAmount = parseFloat(allamounts.total_sale.toFixed(2));
                $('#progress-bars-container ul').empty();
                newProductData.forEach(function(product) {
                    var productSaleAmount = parseFloat(product.total_sale_amount);
                    var percentage = (productSaleAmount / totalSaleAmount) * 100;
                    //    var percentage = (productSaleAmount / totalSaleAmount) * 100 * 100;
                    var progressBarHtml = '<li>' +
                                                '<span class="pro-name">' + product.product_name + '</span>' +
                                                '<div class="chart-content">' +
                                                    '<div class="progress w-100">' +
                                                        '<div class="progress-bar" role="progressbar" style="width: ' + percentage  + '%;" aria-valuenow="' + percentage + '" aria-valuemin="0" aria-valuemax="100"></div>' +
                                                        '<div class="progress-value">₹ ' + productSaleAmount + ' ('+ percentage.toFixed(3) + '%) ' + '</div>' +
                                                    '</div>' +
                                                '</div>' +
                                            '</li>';
                    $('#progress-bars-container ul').append(progressBarHtml);
                });

                $('#totalSales').html(allamounts.total_sale.toFixed(2) + ' ₹');
                $('#totalProfit').html(allamounts.total_profit.toFixed(2) + ' ₹');
                $('#totalProfitPercent').html(allamounts.total_profit_percent.toFixed(2) + ' %');
                hideLoader();

            },
            error: function(error) {
                hideLoader();
                console.error(error);
            }
        });
    }
});

</script>

<script type="text/javascript">

$(document).ready(function(){
    var timeFrame = '{{ $timeFrame }}';
    updateSaleChart(timeFrame);
    updateProductChart(timeFrame);

    $(document).on('click','.report-year',function(e){
        e.preventDefault();
        removeactive();
        $(this).addClass('active');
        var year = $(this).attr('data-year');
        timeFrame = year;
        updateSaleChart(timeFrame);
        updateProductChart(timeFrame);
    });

    $(document).on('click','.report-month',function(e){
        e.preventDefault();
        // removeactive();
        $('.report-month').removeClass('active');
        $(this).addClass('active');
        var month = $(this).attr('data-month');
        var year = $('.report-year.active').attr('data-year');
        timeFrame = year+'-'+month;
        updateSaleChart(timeFrame);
        updateProductChart(timeFrame);
    });



});




function removeactive()
{
    $('button').removeClass('active');
}

</script>
@endsection
