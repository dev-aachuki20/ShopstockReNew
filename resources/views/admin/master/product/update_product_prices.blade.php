@extends('layouts.app')
@section('title')@lang('admin_master.product.seo_title_product_price_master') @endsection
@section('customCss')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('admintheme/assets/css/printView-datatable.css')}}">
<style>
  div.table-responsive>div.dataTables_wrapper>div.row {
    width: 100%;
  }
</style>
@endsection
@section('main-content')

<section class="section roles" style="z-index: unset">
  <div class="section-body">
    <div class="row">
      <div class="col-md-12 form-group">
        {!! Form::label('name', trans('admin_master.product.group_type').'*', ['class' => 'control-label']) !!}
        {!! Form::select('product_group', $product_groups, old('product_group'), ['class' => 'form-control select2', 'id'=>'product_group', 'required' => '']) !!}
        @if($errors->has('name'))
            <p class="help-block red">
                {{ $errors->first('name') }}
            </p>
        @endif
      </div>

      <div class="col-12">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h4>@lang('admin_master.product.seo_title_product_price_master')</h4>
          </div>
          <div class="card-body">
            <div class="table-responsive fixed_Search">
              <table class="table table-bordered table-striped" id="productDatatable">
                <thead>
                  <tr>
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
@endsection

@section('customJS')
<script src="{{ asset('admintheme/assets/bundles/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('admintheme/assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}">
</script>
<script src="{{ asset('admintheme/assets/js/page/datatables.js') }}"></script>

<script type="text/javascript">
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
                    data.product_type = $('#product_group').val();
                }
            },
            columns: [
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
            $('#productDatatable').DataTable().draw();
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
    });
</script>
@endsection