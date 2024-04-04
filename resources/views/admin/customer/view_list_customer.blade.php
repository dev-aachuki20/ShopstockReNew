
@extends('layouts.app')
@section('title')@lang('quickadmin.customer-management.fields.alter_list') @endsection
@section('customCss')
<meta name="csrf-token" content="{{ csrf_token() }}" >
<link rel="stylesheet" href="{{ asset('admintheme/assets/css/printView-datatable.css')}}">
@endsection
@section('main-content')

<section class="section roles customer_view_deail" style="z-index: unset">
    <div class="section-body">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>{{ucwords($customer->name)}}</h3>
                    <p class="clientInformation">
                      <small title="Customer category">
                        <i class="fa fa-user-md" aria-hidden="true"></i>
                        {{$customer->is_type ?? ''}}
                      </small>
                        <small title="Customer phone number"><i class="fa fa-phone" aria-hidden="true"></i> {{$customer->phone_number}}</small>
                        <small title="Customer address"><i class="fa fa-map-marker" aria-hidden="true"></i> {{ $customer->area->address ?? '' }}</small>
                    </p>
                </div>
                <div class="col-md-12">
                    <!-- Start payement history  -->
                    <div class="panel panel-default mt-0">
                        <div class="panel-body table-responsive payment-history-content party-list-managementTable">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Particulars</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                        <th>Closing Balance</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th style="border-right: none;">Opening Balance</th>
                                        <th style="border-right: none;border-left: none;"></th>
                                        <th style="border-left: none;"></th>
                                        <th colspan="2">{{  number_format($openingBalance, 2, '.', ',')}}{{ $openingBalance<=1 ? ' Dr' : ' Cr'}} </th>
                                    </tr>
                                    @php
                                        $totalSales = 0;
                                        $totalCashReceipt = 0;
                                        $totalSalesReturn = 0;
                                        $currentBalance = 0 ;
                                    @endphp
                                    @foreach($monthlyData as $index => $data)

                                    @php
                                        if($index === 0){
                                            $monthlyClosingBalance = $currentBalance + $data['sales'] + $openingBalance - ($data['cashreceipt'] + $data['sales_return']);
                                        }
                                        else{
                                            $monthlyClosingBalance = $currentBalance + $data['sales'] - ($data['cashreceipt'] + $data['sales_return']);
                                        }
                                        $monthlyClosingBalanceFormatted = number_format($monthlyClosingBalance + $openingBalance, 2, '.', ',');
                                    @endphp

                                    <tr>
                                        <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $data['month'])->format('F Y') }}</td>
                                        <td>{{ number_format($data['cashreceipt']+$data['sales_return'], 2, '.', ',') }}</td>
                                        <td>{{ number_format($data['sales'], 2, '.', ',') }}</td>
                                        <td>{{ $monthlyClosingBalanceFormatted }}</td>
                                        <td><button class="customer-month-detail" data-href="{{ route('admin.customers.view_customer_detail', ['customer' => $customer->id, 'month' => $data['month']]) }}"><x-svg-icon icon="view" /></button></td>
                                    </tr>
                                    @php
                                        $totalSales += $data['sales'];
                                        $totalCashReceipt += $data['cashreceipt'];
                                        $totalSalesReturn+= $data['sales_return'];
                                        $currentBalance += $monthlyClosingBalance;
                                    @endphp
                                    @endforeach

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Grand Total</th>
                                        <th>{{ number_format($totalCashReceipt+$totalSalesReturn, 2, '.', ',') }}</th>
                                        <th>{{ number_format($totalSales, 2, '.', ',') }}</th>
                                        <th colspan="2">{{ number_format($totalSales - ($totalCashReceipt+$totalSalesReturn) + $openingBalance , 2, '.', ',') }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
              </div>
            </div>
           </div>
        </div>
  </section>

  <div class="popup_render_div"></div>
@endsection

@section('customJS')
<script src="{{ asset('admintheme/assets/bundles/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('admintheme/assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admintheme/assets/js/page/datatables.js') }}"></script>

<script>

$(document).ready(function () {

$(document).on("click", ".customer-month-detail", function () {
        var hrefUrl = $(this).attr('data-href');
        $('.modal-backdrop').remove();
        $.ajax({
            type: 'get',
            url: hrefUrl,
            success: function (response) {
                //$('#preloader').css('display', 'none');
                if(response.success) {
                    $('.popup_render_div').html(response.htmlView);
                    $('#customerMonthDetail').modal('show');
                    $('#customerMonthDetail').css('z-index', '99999');
                }
            }
        });
    });
});

</script>
@endsection
