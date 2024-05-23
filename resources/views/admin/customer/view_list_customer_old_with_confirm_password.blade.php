
@extends('layouts.app')
@section('title')@lang('quickadmin.customer-management.fields.alter_list') @endsection
@section('customCss')
<meta name="csrf-token" content="{{ csrf_token() }}" >
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
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
            <div class="row mx-0">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row align-items-end mb-4">
                                        <div class="col-xl-4 col-md-3 mb-md-0 mb-3">
                                            <form action="" id="yearFilterForm">
                                                <div class="form-group m-0 selectyearlabel">
                                                    <label for="year" class="mb-0">Select Year1</label>
                                                    <div class="selectyear d-flex align-items-center">
                                                        <select class="form-control" name="year" id="year" data-customerid={{$customer->id}}>
                                                            @foreach ($yearlist as $data)
                                                            <option value="{{ $data }}" {{ $year == $data ? 'selected' : '' }}>{{ $data }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-xl-8 col-md-9">
                                            <form id="estimate-delete-form" method="post" action=" {{route('admin.customers.deleteEstimates')}} ">
                                                <div class="row deletedate_box cart_filter_box pb-0">
                                                    @can('estimate_delete')
                                                    <div class="col-xl-3 col-md-4 col-sm-5 pl-md-0 pr-sm-0 mb-sm-0 mb-2">
                                                        <div class="mx-0 datapikergroup custom-select2">
                                                            <div class="form-control-inner">
                                                                <label for="estimatedelrange">Delete To Date </label>
                                                                <input type="text" name="endDate" id="estimatedelrange" class="form-control" placeholder="DD/MM/YYYY" value="" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endcan
                                                    <div class="col-xl5 col-sm-6">
                                                        <div class="form-group mb-0 d-flex">
                                                            <button type="submit" class="btn btn-primary mb-0 mr-1 col" id="apply-filter">@lang('quickadmin.qa_submit')</button>
                                                            {{-- <button type="reset" class="btn btn-primary mb-0 mr-1 col" id="reset-filter">@lang('quickadmin.qa_reset')</button> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
                                        <th colspan="2">{{ number_format(abs($openingBalance), 2, '.', ',')}}{{ $openingBalance<=1 ? ' Cr' : ' Dr'}} </th>
                                    </tr>
                                    @php
                                        $totalSales = 0;
                                        $totalCashReceipt = 0;
                                        $totalSalesReturn = 0;
                                        $lastClosingBalance = 0 ;
                                    @endphp

                                    @forelse($monthlyData as $index => $data)

                                        @php
                                            if($index === 0){
                                                $monthlyClosingBalance = $lastClosingBalance + $data['sales'] + $openingBalance - ($data['cashreceipt'] + $data['sales_return']);
                                            }
                                            else{
                                                $monthlyClosingBalance = $lastClosingBalance + $data['sales'] - ($data['cashreceipt'] + $data['sales_return']);
                                            }

                                            $monthlyClosingBalanceFormatted = $monthlyClosingBalance + $openingBalance;
                                        @endphp

                                        <tr>
                                            <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $data['month'])->format('F Y') }}</td>
                                            <td>{{ number_format($data['sales'], 2, '.', ',') }}</td>
                                            <td>{{ number_format($data['cashreceipt']+$data['sales_return'], 2, '.', ',') }}</td>
                                            <td>{{ number_format(abs($monthlyClosingBalanceFormatted), 2, '.', ',') }}{{ $monthlyClosingBalanceFormatted<=1 ? ' Cr' : ' Dr'}}</td>
                                            <td><a class="customer-month-detail" href="{{ route('admin.customers.view_customer_detail', ['customer' => $customer->id, 'month' => $data['month']]) }}"><x-svg-icon icon="view" /></a></td>
                                        </tr>
                                        @php
                                            $totalSales += $data['sales'];
                                            $totalCashReceipt += $data['cashreceipt'];
                                            $totalSalesReturn+= $data['sales_return'];
                                            $lastClosingBalance = $monthlyClosingBalance;

                                        @endphp

                                    @empty
                                        <tr>
                                            <td colspan="5">No records found</td>
                                        </tr>
                                    @endforelse


                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Grand Total</th>
                                        <th>{{ number_format($totalSales, 2, '.', ',') }}</th>
                                        <th>{{ number_format($totalCashReceipt+$totalSalesReturn, 2, '.', ',') }}</th>
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
    </div>
  </section>

  <div class="popup_render_div"></div>
@endsection

@section('customJS')
<script src="{{ asset('admintheme/assets/bundles/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('admintheme/assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admintheme/assets/js/page/datatables.js') }}"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<script type="text/javascript">

    $(function() {
        $('input[name="endDate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoApply:true,
            minYear: 2000,
            maxYear: parseInt(moment().format('YYYY'),10),
            locale: {
            format: 'DD/MM/YYYY' // Set the date format to 'DD/MM/YYYY'
        }
        }, function(start, end, label) {
            var chosenDate = start.format('YYYY-MM-DD');
        });
    });
</script>

<script>

$(document).ready(function () {

    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('change','#yearFilterForm #year', function(e){
        e.preventDefault();
        var year = $(this).val();
        var customerId = $(this).attr('data-customerid');
        var hrefUrl = "{{ route('admin.customers.view_customer') }}"+ '?id=' + customerId + '&year=' + year;
        window.location.href = hrefUrl;
    });

    $(document).on('submit','#estimate-delete-form', function(e) {

        e.preventDefault();
        // Get the date range picker instance
        var picker = $('#estimatedelrange').data('daterangepicker');
        // Retrieve the selected start and end dates
        if (picker && picker.startDate && picker.endDate) {
            var to_date = picker.startDate.format('YYYY-MM-DD');

            var formAction = $(this).attr('action');
            if(to_date == undefined || to_date == 'Invalid date'){
                to_date = '';
            }

            var formData = {
                customer_id      : {{$customer->id}},
                to_date          : to_date,
            };
            // delete
            swal({
                title: "Are you sure?",
                text: "are you sure want to delete these Estimates?",
                icon: 'warning',
                buttons: {
                confirm: 'Yes, delete',
                cancel: 'No, cancel',
                },
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                    url: formAction,
                    type: 'POST',
                    data: formData,
                    success: function (response) {
                            var alertType = "{{ trans('quickadmin.alert-type.success') }}";
                            var message = "{{ trans('messages.crud.delete_record') }}";
                            var title = "Customer's Estimates";
                            showToaster(title,alertType,message);
                            location.reload();
                    },
                    error: function (xhr) {
                        swal("Error", 'Something Went Wrong !', 'error');
                    }
                    });
                }
            });

        } else {
            // Handle the case where picker or its properties are undefined
            swal("Error", 'Plese Select Estimate Dates !', 'error');
        }

    });

    $(document).on('click','#reset-filter', function(e) {
        e.preventDefault();
        //Reset the Daterangepicker
        if ($('#estimatedelrange').data('daterangepicker')) {
            var today = moment();
            $('#estimatedelrange').data('daterangepicker').setStartDate(today);
        }
    });

});

</script>
@endsection
