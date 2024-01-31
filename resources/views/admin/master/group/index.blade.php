@extends('layouts.app')
@section('title')@lang('quickadmin.roles.title') @endsection
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
                  <h4>@lang('quickadmin.group_master.title')</h4>
                  @can('group_create')
                  <a href="javascript:void(0)" class="btn btn-outline-primary add_group" ><i class="fas fa-plus"></i> @lang('quickadmin.group_master.add')</a>
                  @endcan
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
  <div class="modal fade" id="groupModal" tabindex="-1" role="dialog" aria-labelledby="groupModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle"><span class="Add_edit_group">Add</span> Group</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="group_form">
        <div class="modal-body">
          <div class="form-group">
            <label for="naem">Name:</label>
            <input type="hidden" class="group_edit_id">
            <input type="text" class="form-control group_edit_name" id="name" placeholder="Enter name" name="name">
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
        var DataaTable = $('#group-table').DataTable();
          $(document).on('click','.add_group',function(){
            $('.error').html('');
            $("#groupModal").modal('show');
            $(".group_edit_id").val('');
            $(".group_edit_name").val('');
            $(".save_btn").html('Save');
            $(".Add_edit_group").html('Add');
          })
          $(document).on('click','.edit_group',function(){
            $('.error').html('');
            $("#groupModal").modal('show');
            $(".group_edit_id").val($(this).data('id'));
            $(".group_edit_name").val($(this).data('name'));
            $(".save_btn").html('Update');
            $(".Add_edit_group").html('Edit');
          })
     
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
          $(document).on('submit', "#group_form", function(e) {
            e.preventDefault();
            var name = $("#name").val();
            var _id = $(".group_edit_id").val();
            $('.save_btn').prop('disabled', true);
            var postType = "POST";
            var post_url = "{{ route('admin.master.groups.store') }}";
            if(_id){
              //  var post_url = "/admin/master/groups/" + _id;
               var post_url = "{{ route('admin.master.groups.update',['group'=> ':groupId']) }}";
                post_url = post_url.replace(':groupId', _id);
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
                   $("#groupModal").modal('hide');
                    DataaTable.ajax.reload();
                    var alertType = "{{ trans('quickadmin.alert-type.success') }}";
                    var message = data.success;
                    var title = "Group";
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
          $(document).on('click','.delete_group',function(){
            var delete_id = $(this).data('id');
            var delete_url = "{{ route('admin.master.groups.destroy',['group'=> ':groupId']) }}";
            delete_url = delete_url.replace(':groupId', delete_id);
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
    // delete
})
</script>

@endsection
