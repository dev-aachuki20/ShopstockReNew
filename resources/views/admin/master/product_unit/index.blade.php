
@extends('layouts.app')
@section('title')@lang('quickadmin.product_unint_master.title') @endsection
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
                  <h4>@lang('quickadmin.product_unint_master.title')</h4>
                  {{-- @can('unit_create') --}}
                  <a href="javascript:void(0)" class="btn btn-outline-primary add_unit" ><i class="fas fa-plus"></i> Add Unit</a>
                  {{-- @endcan --}}
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

<!-- Add Edit Modal -->
  <div class="modal fade" id="unitModal" tabindex="-1" role="dialog" aria-labelledby="unitModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle"><span class="Add_edit_unit">Add</span> unit</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="unit_form">
        <div class="modal-body">
          <div class="form-group">
            <label for="naem">Name:</label>
            <input type="hidden" class="unit_edit_id">
            <input type="text" class="form-control unit_edit_name" id="name" placeholder="Enter name" name="name">
            <span class="error_name text-danger error"></span>
          </div>
        </div>
        <div class="modal-footer">
          <div class="success_error_message"></div>
          <button type="submit" class="btn btn-primary save_btn">Save</button>
        </div>
        </form>
      </div>
    </div>
  </div>
<!-- Add Edit Modal -->

@endsection

@section('customJS')
{!! $dataTable->scripts() !!}
<script src="{{ asset('admintheme/assets/bundles/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('admintheme/assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admintheme/assets/js/page/datatables.js') }}"></script>
<script type="text/javascript">


    // add or edit
      $(document).ready(function(){
        var DataaTable = $('#unit-table').DataTable();
          $(document).on('click','.add_unit',function(){
            $('.error').html('');
            $("#unitModal").modal('show');
            $(".unit_edit_id").val('');
            $(".unit_edit_name").val('');
            $(".save_btn").html('Save');
            $(".Add_edit_unit").html('Add');
          })
          $(document).on('click','.edit_unit',function(){
            $('.error').html('');
            $("#unitModal").modal('show');
            $(".unit_edit_id").val($(this).data('id'));
            $(".unit_edit_name").val($(this).data('name'));
            $(".save_btn").html('Update');
            $(".Add_edit_unit").html('Edit');
          })
     
    
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
          $(document).on('submit', "#unit_form", function(e) {
            e.preventDefault();
            var name = $("#name").val();
            var _id = $(".unit_edit_id").val();
            $('.save_btn').prop('disabled', true);
            var postType = "POST";
            var post_url = "{{ route('admin.master.product-unit.store') }}"
            if(_id){
              var post_url = "{{ route('admin.master.product-unit.update',['product_unit'=> ':unitId']) }}";
              post_url = post_url.replace(':unitId', _id);
               var postType = "PUT";
            }
            $.ajax({
              type: postType,
              url: post_url,
              data: {
                name: name,
                id: _id,
              },
              success: function(data) {
                $('.save_btn').prop('disabled', false);
                if ($.isEmptyObject(data.error)) {
                    $("#unitModal").modal('hide');
                    DataaTable.ajax.reload();
                    var alertType = "{{ trans('quickadmin.alert-type.success') }}";
                    var message = data.success;
                    var title = "Unit";
                    showToaster(title,alertType,message);                   
                } else {
                  printErrorMsg(data.error);
                }
              }
            });
          });
          function printErrorMsg(msg) {
            $.each(msg, function(key, value) {
              $(`.error_${key}`).html(value);
            });
          }
    // add or edit
    // delete
          $(document).on('click','.delete_unit',function(){
            var delete_id = $(this).data('id');
            var delete_url = "{{ route('admin.master.product-unit.destroy',['product_unit'=> ':unitId']) }}";
            delete_url = delete_url.replace(':unitId', delete_id);
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
                    var title = "Unit";
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
    // delete
})


</script>

@endsection
