@extends('layouts.app')
@section('title')@lang('admin_master.product.seo_title_product_group_master') @endsection
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
      <div class="col-md-12 form-group ">
        {!! Form::label('name', trans('admin_master.product.group_type').'*', ['class' => 'control-label']) !!}
        {!! Form::select('product_group', $product_groups, old('product_group'), ['class' => 'form-control select2 ', 'id'=>'product_group', 'required' => '']) !!}
        @if($errors->has('name'))
            <p class="help-block red">
                {{ $errors->first('name') }}
            </p>
        @endif
      </div>

      <div class="col-12">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h4>@lang('admin_master.product.seo_title_product_group_master')</h4>
          </div>
          <div class="card-body">
            <div class="table-responsive fixed_Search">
              <table class="table table-bordered table-striped" id="productDatatable">
                <thead>
                  <tr>
                    <th>@lang('quickadmin.product2.fields.name')</th>
                    <th>@lang('quickadmin.product2.fields.group')</th>
                    <th>@lang('quickadmin.product2.fields.sub_group')</th>
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
                url: "{{ route('admin.master.product-group-list') }}",
                data: function (data) {
                    data.product_type = $('#product_group').val();
                }
            },
            columns: [
                { data: 'name', name: 'name' },
                { data: 'group_id', name: 'group_id' },
                { data: 'sub_group_id', name: 'sub_group_id' },
            ],
            // Add an action column
            columnDefs: [
                { 
                    orderable: false, 
                    serachable: false, 
                    targets: [0],
                },
               
            ],
            drawCallback: function(settings) {
              $('.group_list').select2();
              $('.sub_group').select2();
          }
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
            const clickedTD = event.target;
            const row = clickedTD.closest('tr');
            rowNumber = row.rowIndex;
        });    
        
        $(document).on('change','.group_list', function() {
          var group_id = $(this).val();
          var porduct_id = $(this).data('porduct_id');
            if(group_id > 0){
              changeGroup(group_id,porduct_id,'Group');
            }else{
                var alertType = "{{ trans('quickadmin.alert-type.error') }}";
                var message = "Please select one Group";
                var title = "Group";
                showToaster(title,alertType,message); 
                $('#productDatatable').DataTable().draw();
            }
        });
        $(document).on('change','.sub_group', function() {
          var group_id = $(this).val();
          var porduct_id = $(this).data('porduct_id');
            if(group_id > 0){
              changeGroup(group_id,porduct_id,'SubGroup');
            }else{
                var alertType = "{{ trans('quickadmin.alert-type.error') }}";
                var message = "Please select one Sub Group";
                var title = "Group";
                showToaster(title,alertType,message); 
                $('#productDatatable').DataTable().draw();
            }
        });

    });
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    function changeGroup(group_id,porduct_id,type){
      $.ajax({
            type: "POST",
            url: "{{ route('admin.master.product-group-update')}}",
            data:{group_id:group_id,porduct_id:porduct_id,group_sub_group:type},
            success: function(data) {
                $('#productDatatable').DataTable().draw();
                var alertType = "{{ trans('quickadmin.alert-type.success') }}";
                var message = "Changed successfully";
                var title = "Group";
                showToaster(title,alertType,message); 
            }
        });
    }
</script>
@endsection