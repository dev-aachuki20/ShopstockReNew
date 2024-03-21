@extends('layouts.app')
@section('title')@lang('quickadmin.transaction-management.fields.draft_invoice') @endsection
@section('customCss')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('admintheme/assets/css/printView-datatable.css')}}">
@endsection
@section('main-content')
{{--<h3 class="page-title">{{ trans('quickadmin.order-management2.title-'.$orderType) }}</h3>
<div class="panel panel-default">
    <div class="panel-heading">
        @lang('quickadmin.qa_list')
    </div>

    <div class="panel-body table-responsive fixed_Search">
        <table class="table table-bordered table-striped {{ count($orders) > 0 ? 'datatable' : '' }} @can('order_delete') dt-select @endcan">
            <thead>
                <tr>
                    @can('order_delete')
                    <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>
                    @endcan

                    <th>@lang('quickadmin.order.fields.customer_name')</th>
                    <th>@lang('quickadmin.order.fields.total_products')</th>
                    <th>@lang('quickadmin.order.fields.total_amount')</th>
                    <th>@lang('quickadmin.transaction.fields.remark')</th>
                    <th>@lang('quickadmin.qa_created_at')</th>
                    <th>@lang('quickadmin.qa_action')</th>
                </tr>
            </thead>

            <tbody>
                @if (count($orders) > 0)
                @foreach ($orders as $order)
                <tr class="row-bg-white" data-entry-id="{{ $order->id }}">
                    @can('order_delete')
                    <td></td>
                    @endcan

                    <td field-key='name'>{{ $order->customer->name or '' }}</td>
                    <td field-key='orderProduct'>{{ $order->orderProduct->count()}}</td>
                    <td field-key='total_amount'>{{ $order->total_amount or '' }}</td>
                    <td field-key='remark'>{{ $order->remark or '' }}</td>
                    <td field-key='created_at'>
                        {{ $order->created_at ? $order->created_at->format('d-m-Y') : ''}}
                    </td>
                    <td>
                        @can('order_edit')
                        <a href="{{ route('admin.orders.edit',[$orderType,$order->id]) }}" class="btn btn-sm btn-primary">@lang('quickadmin.qa_edit')</a>
                        @endcan

                        @can('order_view')
                        <a href="{{ route('admin.orders.show',[$order->id,'type'=>$orderType]) }}" class="btn btn-sm btn-info">@lang('quickadmin.qa_show')</a>
                        @endcan
                        @can('order_delete')
                        {!! Form::open(array(
                        'style' => 'display: inline-block;',
                        'method' => 'DELETE',
                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                        'route' => ['admin.orders.destroy', $order->id])) !!}
                        {!! Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-sm btn-danger')) !!}
                        {!! Form::close() !!}
                        @endcan
                    </td>

                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="7">@lang('quickadmin.qa_no_entries_in_table')</td>
                </tr>
                @endif
            </tbody>
        </table>
        {{$dataTable->table(['class' => 'table dt-responsive dropdownBtnTable', 'style' => 'width:100%;'])}}
    </div>
</div>--}}
<section class="section roles" style="z-index: unset">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>
                            {{ trans('quickadmin.order-management2.title-'.$orderType) }}
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive fixed_Search">
                            {{$dataTable->table(['class' => 'table dt-responsive dropdownBtnTable', 'style' => 'width:100%;'])}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>


<!-- view Modal -->
<div class="modal fade" id="view_model_Modal" tabindex="-1" role="dialog" aria-labelledby="view_model_ModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">@lang('quickadmin.order.view-title-'.$orderType)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body show_html">
            </div>
        </div>
    </div>
</div>
<!-- view Modal -->
@endsection

@section('customJS')
{!! $dataTable->scripts() !!}
<script src="{{ asset('admintheme/assets/bundles/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('admintheme/assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admintheme/assets/js/page/datatables.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var DataTable = $('#draft-invoice-table').DataTable();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('click', '.view_detail', function() {
            $("#view_model_Modal").modal('show');
            $('.show_html').html('');
            var url = $(this).data('url');
            if (url) {
                $.ajax({
                    type: "GET",
                    url: url,
                    success: function(data) {
                        $('.show_html').html(data.html);
                    }
                });
            }
        });

        $(document).on('click', '.delete_transaction', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var delete_url = "{{ route('admin.orders.destroy',['order'=> ':orderId']) }}";
            delete_url = delete_url.replace(':orderId', id);
            swal({
                title: "{{ trans('messages.deletetitle') }}",
                text: "{{ trans('messages.areYouSure') }}",
                icon: 'warning',
                buttons: {
                    confirm: 'Yes, delete it',
                    cancel: 'No, cancel',
                },
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    // If the user confirms, send the DELETE request
                    $.ajax({
                        url: delete_url,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            var alertType = response['alert-type'];
                            var message = response['message'];
                            var title = "{{ trans('quickadmin.transaction.title_singular') }}";
                            showToaster(title, alertType, message);
                            DataTable.ajax.reload();
                            // location.reload();

                        },
                        error: function(xhr) {
                            // Handle error response
                            swal("{{ trans('quickadmin.transaction.title_singular') }}", 'some mistake is there.', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endsection