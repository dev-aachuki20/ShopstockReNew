@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')
@section('title')@lang('admin_master.product.seo_title_product_master') @endsection
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
                  <h4>@lang('admin_master.product.seo_title_product_master')</h4>
                  @can('group_create')
                  <a href="{{route('admin.master.products.create')}}" class="btn btn-outline-primary" ><i class="fas fa-plus"></i> @lang('admin_master.product.add')</a>
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
@endsection

@section('customJS')
{!! $dataTable->scripts() !!}
<script src="{{ asset('admintheme/assets/bundles/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('admintheme/assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admintheme/assets/js/page/datatables.js') }}"></script>

<script type="text/javascript">
$(document).ready(function(){
    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click','.delete_product',function(){
        if (confirm('are you sure want to delete?')) {
            var delete_id = $(this).data('id');
            var delete_url = "{{ route('admin.master.products.destroy',['product'=> ':productId']) }}";
                delete_url = delete_url.replace(':productId', delete_id);
            $.ajax({
            type: "DELETE",
            url: delete_url,              
            success: function(data) {
                if ($.isEmptyObject(data.error)) {
                location.reload();
                } 
            }
            });
        }
    });
});
</script>
@endsection
