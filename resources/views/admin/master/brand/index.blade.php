@inject('request', 'Illuminate\Http\Request')
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
                  <h4>@lang('quickadmin.brand_master.title')</h4>
                  {{-- @can('role_create') --}}
                  <a href="javascript:void(0)" class="btn btn-outline-primary add_brand" ><i class="fas fa-plus"></i> Add Brand</a>
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
  <div class="modal fade" id="brandModal" tabindex="-1" role="dialog" aria-labelledby="brandModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle"><span class="Add_edit_brand">Add</span> Brand</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="brand_form">
        <div class="modal-body">
          <div class="form-group">
            <label for="naem">Name:</label>
            <input type="hidden" class="brand_edit_id">
            <input type="text" class="form-control brand_edit_name" id="name" placeholder="Enter name" name="name">
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
          $(document).on('click','.add_brand',function(){
            $('.error').html('');
            $("#brandModal").modal('show');
            $(".brand_edit_id").val('');
            $(".brand_edit_name").val('');
            $(".save_btn").html('Save');
            $(".Add_edit_brand").html('Add');
          })
          $(document).on('click','.edit_brand',function(){
            $('.error').html('');
            $("#brandModal").modal('show');
            $(".brand_edit_id").val($(this).data('id'));
            $(".brand_edit_name").val($(this).data('name'));
            $(".save_btn").html('Update');
            $(".Add_edit_brand").html('Edit');
          })
     
    
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
          $(document).on('submit', "#brand_form", function(e) {
            e.preventDefault();
            var name = $("#name").val();
            var _id = $(".brand_edit_id").val();
            $('.save_btn').prop('disabled', true);
            var postType = "POST";
            var post_url = "{{ route('brands.store') }}"
            if(_id){
               var post_url = "/brands/" + _id;
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
                  $('.success_error_message').html(`<span class="text-success">${data.success}</span>`);
                  setTimeout(() => {
                    location.reload();
                  }, 1500);                  
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
          $(document).on('click','.delete_brand',function(){
            if (confirm('are you sure want to delete?')) {
                var delete_id = $(this).data('id');
                $.ajax({
                type: "DELETE",
                url: "/brands/" + delete_id,              
                success: function(data) {
                  if ($.isEmptyObject(data.error)) {
                    alert(data.success);
                    location.reload();
                  } 
                }
              });
            }
          });
    // delete
})
</script>

@endsection
