
@extends('layouts.app')
@section('title')@lang('quickadmin.customer-management.fields.list') @endsection
@section('customCss')
<meta name="csrf-token" content="{{ csrf_token() }}" >
<link rel="stylesheet" href="{{ asset('admintheme/assets/css/printView-datatable.css')}}">
@endsection
@section('main-content')

<section class="section roles" style="z-index: unset">
    <div class="section-body">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <h4>@lang('quickadmin.customer-management.fields.list')</h4>                 
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
  var DataaTable = $('#customer-table').DataTable();      
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
// delete
      $(document).on('click','.delete_customer',function(){
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
// delete
})
</script>
@endsection
