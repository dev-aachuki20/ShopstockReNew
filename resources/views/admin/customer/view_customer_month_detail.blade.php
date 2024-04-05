
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
                  <h4>@lang('quickadmin.qa_payment_history')</h4>
                  <div class="dbl-btns d-flex">
                    <button class="printbtn btn btn-primary"><i class="fa fa-print"></i> Print Product Ledger</button>
                    <button class="printbtn btn btn-primary"><i class="fa fa-print"></i> Print Statement</button>
                  </div>
                </div>
                <div class="col-md-12">
                    <h3>{{ $customer->name ? ucwords($customer->name) : "" }} ({{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }})</h3>
                    <p class="clientInformation">
                      <small title="Customer category">
                        <i class="fa fa-user-md" aria-hidden="true"></i>
                        {{$customer->is_type ?? ''}}
                      </small>
                        <small title="Customer phone number"><i class="fa fa-phone" aria-hidden="true"></i> {{$customer->phone_number}}</small>
                        {{-- <small title="Customer total blance"><i class="fa fa-money" aria-hidden="true"></i> <i class="fa fa-inr" aria-hidden="true"></i> {{$customer->credit_limit}}</small> --}}
                        <small title="Customer address"><i class="fa fa-map-marker" aria-hidden="true"></i> {{ $customer->area->address ?? '' }}</small>
                  </p>
                </div>
               <div class="col-md-12">
                  <!-- Start payement history  -->
                    <div class="panel panel-default mt-0">
                      <div class="panel-body table-responsive payment-history-customer">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Estimate Date</th>
                                    <th>Particulars</th>
                                    <th>Estimate Number</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $totalSales = 0;
                                $totalCashReceipt = 0;
                                $totalSalesReturn = 0;
                                $lastBalance = 0;
                                @endphp
                                @foreach ($alldata as $data)
                                @php
                                    $debitAmount = $data->type=='sales' ? $data->amount : 0;
                                    $creditAmount = $data->type=='cashreceipt' || $data->type=='sales_return' ? $data->amount : 0;

                                    $Balance = $lastBalance + $debitAmount - $creditAmount;
                                @endphp
                                <tr>
                                    <td>{{ $data->entry_date ? \Carbon\Carbon::parse($data->entry_date)->format('d-m-Y') : '' }}</td>
                                    <td><button class="payment-detail-btn">{{ $data->type=='sales' ? "Sales" : ($data->type=='sales_return' ? "Estimate Return" : "Cash Receipt") }}</button></td>
                                    <td>{{ $data->voucher_number ?? ""}}</td>
                                    <td>{!! $data->type == 'sales' ? '<i class="fa fa-inr"></i> ' . $data->amount : '' !!}</td>
                                    <td>{!! $data->type=='cashreceipt' || $data->type=='sales_return' ? '<i class="fa fa-inr"></i> ' . $data->amount : "" !!}</td>
                                    <td>{!! $Balance ? '<i class="fa fa-inr"></i> ' . number_format(abs($Balance), 2, '.', ',') : 0 !!}{{ $Balance<=1 ? ' Cr' : ' Dr'}}</td>
                                </tr>
                                @php
                                    if ($data->type == 'sales') {
                                        $totalSales += $data->amount;
                                    } elseif ($data->type == 'cashreceipt') {
                                        $totalCashReceipt += $data->amount;
                                    } elseif ($data->type == 'sales_return') {
                                        $totalSalesReturn += $data->amount;
                                    }

                                    $lastBalance = $Balance;
                                @endphp
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-right">Opening Balance</th>
                                    <td colspan="3"><i class="fa fa-inr"></i> {{number_format($openingBalance, 2, '.', ',')}}</td>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-right">Current Balance</th>
                                    <td><i class="fa fa-inr"></i> {{ number_format($totalSales, 2, '.', ',') }}</td>
                                    <td colspan="2"><i class="fa fa-inr"></i> {{ number_format($totalCashReceipt+$totalSalesReturn, 2, '.', ',') }}</td>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-right">Closing Balance</th>
                                    <td colspan="3"><i class="fa fa-inr"></i> {{ number_format($totalSales + $openingBalance - ($totalCashReceipt+$totalSalesReturn), 2, '.', ',') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                      </div>
                  </div>
                <!-- End Payment History -->
               </div>

              </div>
              <a href="{{route('admin.customers.view_customer',['id'=> $customer->id])}}" class="btn btn-default Blist-btn">Back to list</a>
            </div>
           </div>
        </div>
  </section>



@endsection

@section('customJS')
<script src="{{ asset('admintheme/assets/bundles/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('admintheme/assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admintheme/assets/js/page/datatables.js') }}"></script>

<script>


</script>
@endsection
