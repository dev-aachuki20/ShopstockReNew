
@extends('layouts.app')
@section('title')@lang('quickadmin.customer-management.fields.list') @endsection
@section('customCss')
<meta name="csrf-token" content="{{ csrf_token() }}" >
<link rel="stylesheet" href="{{ asset('admintheme/assets/css/printView-datatable.css')}}">
<style>
    .dropdown-toggle::after {
    display: none;
    }

    .custom-select2 select{
        width: 200px;
        z-index: 1;
        position: relative;
    }
    .custom-select2 .form-control-inner{
        position: relative;
    }
    .custom-select2 .form-control-inner label{
        position: absolute;
        left: 10px;
        top: -10px;
        background-color: #fff;
        padding: 0 5px;
        z-index: 1;
        font-weight: 600;
        font-size: 14px;
    }
    .select2-results{
        padding-top: 48px;
        position: relative;
    }
    .select2-link2{
        position: absolute;
        top: 6px;
        left: 5px;
        width: 100%;
    }
    .select2-container--default .select2-selection--single,
    .select2-container--default .select2-selection--single .select2-selection__arrow{
        height: 40px;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered{
        line-height: 41px;
    }
    .select2-search--dropdown .select2-search__field{
        padding: 10px;
        font-size: 15px;
    }
    .select2-search--dropdown .select2-search__field:focus{
        outline: none;
    }
    .select2-link2 .btns {
        color: #3584a5;
        background-color: transparent;
        border: none;
        font-size: 14px;
        padding: 7px 15px;
        cursor: pointer;
        border: 1px solid #3584a5;
        border-radius: 60px;
    }

</style>
@endsection
@section('main-content')

<section class="section roles" style="z-index: unset">
    <div class="section-body">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>@lang('quickadmin.customer-management.fields.list')</h4>
                    <div class="form-group mb-0 d-flex justify-content-md-end filegroip m-0">
                        @can('customer_print')
                        <div class="col-auto px-md-1 pr-1">
                            <a href="{{ route('admin.customer.allprint')}}" class="btn printbtn h-10 col circlebtn"  id="customer-print" title="@lang('quickadmin.qa_print')"> <x-svg-icon icon="print" /></a>
                        </div>
                        @endcan
                    </div>
                </div>
                    <div class="col">
                        <form id="listfilter" class="select_filter" style="display: none;">
                            <div class="row mx-0">
                                <div class="col-auto form-group ml-auto px-0">
                                   <div class=" d-flex align-items-center selectgroup">
                                        <select class="form-control" name="listtype" id="listtype">
                                            <option value="all" {{ $listtype == 'all' ? 'selected' : '' }}>All</option>
                                            <option value="ledger" {{ $listtype == 'ledger' ? 'selected' : '' }}>Ledger</option>
                                        </select>
                                   </div>
                                </div>
                            </div>
                        </form>
                    </div>
                <div class="card-body">
                    <form id="area-filter-form">
                        <div class="row align-items-center mb-4 cart_filter_box">
                            <div class="col-md-12 mb-md-0 mb-4">
                                <div class="custom-select2 fullselect2">
                                    <div class="form-control-inner customer-report-top">
                                        <label for="area_id">Select Area <button type="button" class="btn btn-primary mr-1 col select-all-area" id="select-all-area">All</button> <button type="button" class="btn btn-primary mr-1 col select-all-area" id="total-area-amount">Total Amount : <i class="fa fa-inr"></i> <span class="area-wise-total-amount">0</span></button></label>
                                        <select class="form-control filter-area-select areas" name="area_id" id="area_id" multiple>
                                            @foreach($areas as $id=>$name)
                                                <option value="{{ $id }}" data-icon="fa fa-inr" data-balance="{{ number_format(abs(getTotalBlanceAreaWise($id)),0) }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                        <button class="closebtn reset-filter">X</button>
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="col-auto">
                                <div class="form-group mb-0 d-flex">
                                    <button type="reset" class="btn btn-primary mr-1 col reset-filter" id="reset-filter">@lang('quickadmin.qa_reset')</button>
                                </div>
                            </div> --}}
                            {{-- <div class="col-auto">
                                <div class="form-group mb-0 d-flex">
                                    <button type="button" class="btn btn-primary mr-1 col select-all-area" id="select-all-area">Select All</button>
                                </div>
                            </div> --}}
                        </div>
                    </form>
                    <div class="table-responsive fixed_Search">
                        {{$dataTable->table(['class' => 'table dt-responsive dropdownBtnTable partyListTable', 'style' => 'width:100%;' , 'id'=>'customer-table'])}}
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
  </section>


@endsection

@section('customJS')
{!! $dataTable->scripts() !!}
<script src="{{ asset('admintheme/assets/bundles/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('admintheme/assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admintheme/assets/js/page/datatables.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function(){
        var globallisttype = '{{ $listtype }}';
        $("#customer-table_filter.dataTables_filter").append($("#listfilter"));
        $("#listfilter").show();
        var DataaTable = $('#customer-table').DataTable();
        var selectedFilterAreaValues = [];
        var customer_selectedIds = [];

        $('#customer-print').printPage();
        // Page show from top when page changes
        $(document).on('draw.dt','#customer-table', function (e) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: 0
            }, 'fast');
        });

        function formatText (icon) {
            if(icon.id != ''){
                return $('<span>'+icon.text+'</span><span style="float:right;"><i class="'+ $(icon.element).data('icon') + '"></i> ' + $(icon.element).data('balance') + '</span>');
            }else{
                return $('<span>'+icon.text+'</span>');
            }
        };

        $('select.areas').select2({
            // selectOnClose: true,
            width: "50%",
            placeholder:'Please Select Areas',
            templateSelection: formatText,
            templateResult: formatText,
            // multiple: true,
            minimumResultsForSearch: Infinity
        });

        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });


        // delete
        $(document).on('click','.delete_customer',function(e){
            e.preventDefault();
            var delete_id = $(this).data('id');
            var delete_url = "{{ route('admin.customers.destroy',['customer'=> ':cId']) }}";
            delete_url = delete_url.replace(':cId', delete_id);
            swal({
            title: "Are  you sure?",
            text: "are you sure want to delete?",
            icon: 'warning',
            buttons: {
            confirm: 'Yes, delete',
            cancel: 'No, cancel',
            },
            dangerMode: true,
            }).then(function(willDelete) {
            if(willDelete) {
                $.ajax({
                type: "DELETE",
                url: delete_url,
                success: function(data) {
                if ($.isEmptyObject(data.error)) {
                    DataaTable.ajax.reload();
                    var alertType = "{{ trans('quickadmin.alert-type.success') }}";
                    var message = "{{ trans('messages.crud.delete_record') }}";
                    var title = "Customer";
                    showToaster(title,alertType,message);
                }
                },
                error: function (xhr) {
                swal("Error", 'Some mistake is there.', 'error');
                }
            });
            }

            })
        });


        $(document).on('change','#listfilter #listtype', function(e){
            e.preventDefault();
            var listtype = $(this).val();
            globallisttype = listtype;
            var hrefUrl = "{{ route('admin.customer_list') }}"+ '?listtype=' + listtype;
            //window.location.href = hrefUrl;
            DataaTable.ajax.url(hrefUrl).load();
        });

        // Area Select functionalities
        $(document).on('change','#area-filter-form #area_id', function(e)
        {
            e.preventDefault();
            var selectedAreas = $(this).val();
            selectedFilterAreaValues = selectedAreas ? selectedAreas : [];
            // Clear all checkboxes
            $('.dt_checkbox').prop('checked', false);
            $('#dt_cb_all').prop('checked', false);
            var totalAmount = 0;

            $('#area_id option:selected').each(function() {
                // selectedFilterAreaValues.push($(this).val());
                var value = $(this).val();
                if (selectedFilterAreaValues.indexOf(value) === -1) {
                    selectedFilterAreaValues.push(value);
                }
                var balance = $(this).attr('data-balance');
                totalAmount += parseInt(balance.replace(/,/g, ''));
            });

            $('.area-wise-total-amount').html(totalAmount);

            var area_ids = selectedFilterAreaValues.join(',');
            var hrefurl = "{{ route('admin.customer_list') }}?area_id=" + encodeURIComponent(area_ids)+ '&listtype=' + globallisttype;
            var printUrl = "{{ route('admin.customer.allprint') }}?area_id=" + encodeURIComponent(area_ids)+ '&listtype=' + globallisttype;
            //Update the DataTable URL and print link
            DataaTable.ajax.url(hrefurl).load();
            $('#customer-print').attr('href', printUrl);

        });

        // Select All Area
        $(document).on('click','#area-filter-form .select-all-area', function(e)
        {
            e.preventDefault();
            selectedFilterAreaValues = [];
            // Clear all checkboxes
            $('.dt_checkbox').prop('checked', false);
            $('#dt_cb_all').prop('checked', false);
            $('#area_id option').each(function() {
                selectedFilterAreaValues.push($(this).val());
            });

            // Select all options in the select box
            $('#area-filter-form #area_id').val(selectedFilterAreaValues).trigger('change');
        });


        $(document).on('click','.reset-filter', function(e)
        {
            e.preventDefault();
            $('#area-filter-form')[0].reset();
            var select2Element = $('#area_id');
            select2Element.val('').trigger('change.select2');
            DataaTable.ajax.url("{{ route('admin.customer_list') }}").load();
            originalPrintUrl = "{{ route('admin.customer.allprint') }}";
            $('#customer-print').attr('href', originalPrintUrl);
            // Clear all checkboxes
            $('.dt_checkbox').prop('checked', false);
            $('#dt_cb_all').prop('checked', false);
            $('.area-wise-total-amount').html('0');
        });

        $(document).on('change', '#dt_cb_all', function(e)
        {
            e.preventDefault();
            var isChecked = $(this).prop('checked');
            $('.dt_checkbox').prop('checked', isChecked).trigger('change');
        });

        $(document).on('change', '.dt_checkbox', function(e)
        {
            e.preventDefault();
            customer_selectedIds = [];
            $('.dt_checkbox:checked').each(function() {
                customer_selectedIds.push($(this).val());
            });
            var printUrl = "{{ route('admin.customer.allprint') }}?customer_id=" + encodeURIComponent(customer_selectedIds.join(','))+ '&listtype=' + globallisttype;
            $('#customer-print').attr('href', printUrl);
        });

    });
</script>
@endsection
