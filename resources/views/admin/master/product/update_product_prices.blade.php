@extends('layouts.app')
@section('title')@lang('admin_master.product.seo_title_product_price_master') @endsection
@section('customCss')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('admintheme/assets/css/printView-datatable.css')}}">
<style>
  .select2-results {
    padding-top: 0px !important;
}
</style>
@endsection
@section('main-content')

<section class="section roles update_product" style="z-index: unset">
  <div class="section-body">
    <div class="row">
      <div class="col-md-6 form-group ">
        {!! Form::label('name', trans('admin_master.product.group_type'), ['class' => 'control-label']) !!}
        {!! Form::select('product_group', $product_groups, old('product_group'), ['class' => 'form-control select2 ', 'id'=>'product_group', 'required' => '']) !!}
        @if($errors->has('name'))
            <p class="help-block red">
                {{ $errors->first('name') }}
            </p>
        @endif
      </div>
      <div class="col-md-6">
        <div class="form-group">
            <label>@lang('admin_master.product.sub_group_type_name') </label>
            <div class="sub_group_list">
                <div class="input-group">
                    {!! Form::select('sub_group_id', [], $product->sub_group_id??'', ['class' => 'form-control select2', 'id'=>'sub_group_list']) !!}
                </div>
                <div class="error_sub_group_id text-danger error"></div>
            </div>
        </div>
      </div>

      <div class="col-12">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h4>@lang('admin_master.product.seo_title_product_price_master')</h4>

            <div class="col-auto  mt-md-0 mt-3 ml-auto">
              <div class="row align-items-center">
                  <div class="col-auto px-1">
                      @can('product_edit')                              
                        <a href="javascript:void(0)" class="addnew-btn add_group edit_product_price sm_btn circlebtn btn" title="@lang('admin_master.product.edit')"><x-svg-icon icon="edit" /></a>
                        @endcan
                  </div>
              </div>
            </div>



          </div>
          <div class="card-body">
            <div class="table-responsive fixed_Search update_data_table_responsive">
              <table class="table table-bordered table-striped" id="productDatatable">
                <thead>
                  <tr>
                    <th><input type="checkbox" id="select_all">
                      <label for="select_all">Select All</label></th>
                    <th>@lang('quickadmin.product2.fields.name')</th>
                    <th>@lang('quickadmin.product2.fields.price')</th>
                    <th>@lang('quickadmin.product2.fields.min_sale_price')</th>
                    <th>@lang('quickadmin.product2.fields.wholesaler_price')</th>
                    <th>@lang('quickadmin.product2.fields.retailer_price')</th>
                  </tr>
                </thead>
                <tbody class="product-price-list">
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
{{-- for eidt price --}}
<div class="modal fade" id="product_priceModal" tabindex="-1" role="dialog" aria-labelledby="product_priceModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">@lang('admin_master.product.edit')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="edit_price_form">
      <div class="modal-body">
        <div class="form-group">
          <label for="naem">Type:</label>
          <select class="price_type form-control">
            <option value="">Please Select Price Type</option>
            <option value="increment">increment</option>
            <option value="decrement">Decrease</option>
          </select>
          <span class="error_price_type text-danger error"></span>
        </div>
        <div class="form-group">
          <label for="naem">Amount:</label>
          <input type="text" class="form-control update_amount" placeholder="Enter Amount">
          <span class="error_amount text-danger error"></span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary  update_btn">Update</button>
      </div>
      </form>
    </div>
  </div>
</div>
{{-- for eidt price --}}

@endsection

@section('customJS')
<script src="{{ asset('admintheme/assets/bundles/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('admintheme/assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}">
</script>
<script src="{{ asset('admintheme/assets/js/page/datatables.js') }}"></script>

<script type="text/javascript">
   // get sub Group
    $(document).on('change','#product_group', function() {
          var group_list_id = $(this).val();
          if(group_list_id > 0){
              $('#sub_group_list').prop('disabled', true);
              getSubGroup(group_list_id);
          }
      });
    // get sub Group
  var updateProductObject = {data:[]};
  var singleProduct = {};
  var rowNumber;
    $(document).ready(function(){
        $('#productDatatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.master.product-price-list') }}",
                data: function (data) {
                    data.group_id = $('#product_group').val();
                    data.sub_group_id = $('#sub_group_list').val();
                }
            },
            columns: [
                { data: 'select_p', name: 'select_p' },
                { data: 'name', name: 'name' },
                { data: 'price', name: 'price', className: 'update_price', },
                { data: 'min_sale_price', name: 'min_sale_price', className: 'update_price', },
                { data: 'wholesaler_price', name: 'wholesaler_price', className: 'update_price', },
                { data: 'retailer_price', name: 'retailer_price', className: 'update_price',},
            ],
            // Add an action column
            columnDefs: [
                { 
                    orderable: false, 
                    serachable: false, 
                    targets: [0],
                },
               
            ],
        });
		    $('#product_group').change(function(){
           // $('#productDatatable').DataTable().draw();
        });		   
        $(document).on('change','#productDatatable_length select',function(){
            $('#productDatatable').DataTable().draw();
        });
        $(document).on('keyup','#productDatatable_filter input[type=search]',function(){
            $('#productDatatable').DataTable().draw();
        });
        const table = document.querySelector('table');
        table.addEventListener('click', function(event) {
            // Get the `td` element that was clicked
            const clickedTD = event.target;
            // Get the parent `tr` element of the `td` element
            const row = clickedTD.closest('tr');
            // Get the row number of the `tr` element
            rowNumber = row.rowIndex;
            // Do something with the row number
        });    
        
       

        $(document).on('click', '.update_price span', function () {
            var editElement = $(this);
            var rowId = editElement.attr('data-product');
            var fieldName = editElement.attr('data-field');
            var fieldValue = editElement.html();
            if($(".disable_other_input").hasClass('edit-input')){
              return false;
            }
            var inputElement = $('<input type="number" class="edit-input disable_other_input" data-field="'+fieldName+'" step=".01" autocomplete="off">').val(fieldValue);
             inputElement.keypress(function (e) {
                if (e.which === 13) {
                    var newValue = inputElement.val();
                    if(newValue < 0){
                          var alertType = "{{ trans('quickadmin.alert-type.error') }}";
                          var messages = "The price field must be at least 0.";
                          var title = "Product";
                          showToaster(title,alertType,messages); 
                          return false; 
                    }
                    $.ajaxSetup({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
                    });
                    $.ajax({
                        method: 'POST',
                        url: "{{ route('admin.master.updateProductPrice') }}",
                        data: {
                            rowId: rowId,
                            fieldName: fieldName,
                            newValue: newValue
                        },
                        success: function (response) {  
                            editElement.html(newValue); 
                            var alertType = "{{ trans('quickadmin.alert-type.success') }}";
                            var messages = response.message;
                            var title = "Product";
                            showToaster(title,alertType,messages);    
                        },
                        error: function (xhr, status, error) {                            
                            console.error(error);
                        }
                    });                                       
                    inputElement.unbind('keypress');
                }
            });
            editElement.html(inputElement);
            inputElement.focus();
        });

        $(document).on('change','#sub_group_list',function(){
            $('#select_all').prop('checked', false);
            $('#productDatatable').DataTable().draw();
        });

        $(document).ready(function() {
            $('#select_all').click(function() {
                var isChecked = $(this).prop('checked');
                $('.selected_product').prop('checked', isChecked);
            });
        });

        $(document).on('click','.edit_product_price',function(){
          $("#edit_price_form")[0].reset();
          var checkedProduct = [];
            $('.selected_product:checked').each(function() {
              checkedProduct.push($(this).val());
            });
            if(checkedProduct.length < 1){
              var alertType = "{{ trans('quickadmin.alert-type.error') }}";
                  var message = "Please select One Product";
                  var title = "Product";
                  showToaster(title,alertType,message);   
                return false;
            }
            $("#product_priceModal").modal('show');
            $('.update_btn').prop('disabled', false);
        });
        
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $(document).on('click','.update_btn',function(e){
            e.preventDefault();
            $('.error').html('');           
            var price_type = $('.price_type').find(":selected").val();
            var amount = $('.update_amount').val();
            var checkedValues = [];
            $('.selected_product:checked').each(function() {
                checkedValues.push($(this).val());
            });
            if(checkedValues.length < 1){
              var alertType = "{{ trans('quickadmin.alert-type.error') }}";
                  var message = "Please select One Product";
                  var title = "Product";
                  showToaster(title,alertType,message);   
                return false;
            }
            swal({
            title: "Are  you sure?",
            text: "are you sure want to Update?",
            icon: 'warning',
            buttons: {
              confirm: 'Yes, delete',
              cancel: 'No, cancel',
            },
            dangerMode: true,
            }).then(function(willDelete) {
                if(willDelete) { 
                  $('.update_btn').prop('disabled', true); 
                  $.ajax({
                    method: 'POST',
                    url: "{{ route('admin.master.updateProductPriceGroup') }}",
                    data: {
                      price_type: price_type,
                      amount: amount,
                      product_ids:checkedValues
                    },
                    success: function(data) {
                      $('.update_btn').prop('disabled', false);
                      if ($.isEmptyObject(data.error)) {
                          $("#product_priceModal").modal('hide');
                          $('#select_all').prop('checked', false);
                          $('#productDatatable').DataTable().draw();
                          var alertType = "{{ trans('quickadmin.alert-type.success') }}";
                          var message = data.message;
                          var title = "Product";
                          showToaster(title,alertType,message);              
                      } else {
                        printErrorMsg(data.error);
                      }
                    }
                  });
                } 
              }) 

        });

    });

    function getSubGroup(group_list_id,selected_id=""){
    $.ajax({
            type: "GET",
            url: "{{ route('admin.master.get_group_child')}}",
            data:{parent_id:group_list_id,selected_id:selected_id},
            success: function(data) {
                $('#sub_group_list').prop('disabled', false);               
                $('.sub_group_list').html('');
                $('.sub_group_list').html(data.html);
                $('#sub_group_list').select2();
                $('#productDatatable').DataTable().draw();
                $('#select_all').prop('checked', false);
            }
        });
   }
   function printErrorMsg(msg) {
      $.each(msg, function(key, value) {
        $(`.error_${key}`).html(value);
      });
    }
</script>
@endsection