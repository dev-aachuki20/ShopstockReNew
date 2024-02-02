@extends('layouts.app')
@section('title')@lang('admin_master.product.seo_title_product_master') @endsection
@section('customCss')
<meta name="csrf-token" content="{{ csrf_token() }}" >
<link rel="stylesheet" href="{{ asset('admintheme/assets/css/printView-datatable.css')}}">
@endsection
@section('main-content')

@php $isRecycle = ""; @endphp
@if(Request::is('admin/master/product-recycle*'))
  @php  $isRecycle = "Yes"; @endphp
@endif

<section class="section roles" style="z-index: unset">
    <div class="section-body">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                  @if($isRecycle == "Yes")
                  <h4>@lang('admin_master.product.product_recycle_master')</h4>
                  @else               
                  <h4>@lang('admin_master.product.seo_title_product_master')</h4>                 
                      <div class="col-auto  mt-md-0 mt-3 ml-auto">
                    <div class="row align-items-center">
                        <div class="col-auto px-1">
                            @can('product_create')                              
                              <a href="{{route('admin.master.products.create')}}" class="addnew-btn add_group sm_btn circlebtn btn" title="@lang('messages.add')"><x-svg-icon icon="add" /></a>
                              @endcan
                        </div>
                        <div class="col-auto pl-1">
                            @can('product_export')
                            <a href="{{ route('admin.master.product.export')}}" class="excelbtn btn h-10 col circlebtn" title="@lang('messages.excel')"  id="excel-button"><x-svg-icon icon="excel" /></a>
                            @endcan
                        </div>
                        <div class="col-auto pl-1">
                            {{-- @can('product_rejoin') --}}
                            <a href="{{ route('admin.master.product.recycle')}}" class="recycleicon btn h-10 col circlebtn" title="@lang('messages.undo')"  id="excel-button"><x-svg-icon icon="rejoin-btn" /></a>
                            {{-- @endcan --}}
                        </div>
                    </div>
                  @endif
                </div>


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
  </section>
@endsection

@section('customJS')
{!! $dataTable->scripts() !!}
<script src="{{ asset('admintheme/assets/bundles/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('admintheme/assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admintheme/assets/js/page/datatables.js') }}"></script>

<script type="text/javascript">
$(document).ready(function(){
  var DataaTable = $('#product-table').DataTable();
    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click','.delete_product',function(){       
      var delete_id = $(this).data('id');
      var delete_url = "{{ route('admin.master.products.destroy',['product'=> ':productId']) }}";
          delete_url = delete_url.replace(':productId', delete_id);
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
                  var title = "Product";
                  showToaster(title,alertType,message); 
              } 
            },
                error: function (xhr) {
                  swal("{{ trans('quickadmin.order.invoice') }}", 'Some mistake is there.', 'error');
                }
            });
          }
      })
    });

    // recycle
    $(document).on('click','.recycle_group',function(){
            var recycle_id = $(this).data('id');
          swal({
            title: "Are  you sure?",
            text: "are you sure want to Undo?",
            icon: 'warning',
            buttons: {
              confirm: 'Yes, Undo',
              cancel: 'No, cancel',
            },
            dangerMode: true,
            }).then(function(willDelete) {
                if(willDelete) {  
                    $.ajax({
                    type: "POST",
                    url: "{{ route('admin.master.product.undo')}}", 
                    data:{recycle_id:recycle_id},             
                    success: function(data) {
                      if ($.isEmptyObject(data.error)) {
                        DataaTable.ajax.reload();
                        var alertType = "{{ trans('quickadmin.alert-type.success') }}";
                        var message = "{{ trans('messages.crud.delete_record') }}";
                        var title = "Group";
                        showToaster(title,alertType,message);   
                      } 
                    },
                    error: function (xhr) {
                      swal("{{ trans('quickadmin.order.invoice') }}", 'Some mistake is there.', 'error');
                    }
                  });
                } 
              })              
          });
    // recycle

});
</script>
@endsection
