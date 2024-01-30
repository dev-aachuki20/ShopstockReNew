@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')
@section('title')@lang('admin_master.product.seo_title') @endsection
@section('customCss')
<meta name="csrf-token" content="{{ csrf_token() }}" >
@endsection
@section('main-content')
<section class="section">
    <div class="section-body">
      <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
          <div class="card">
            <div class="card-body">
            <form action=""  id="productForm" name="productForm" enctype="multipart/form-data">
              <div class="row">
                @include('admin.master.product.form')
              </div>
            </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@section('customJS')
<script type="text/javascript">         
  $(document).ready(function(){
      $('input.extra_option').change(function(){
            if($('.extra_option').filter(':checked').length >= 1){
                $('div.extra_option_hint').show();
                $('.extra_option_hint input').attr("required", true);
            }else{
                $('.extra_option_hint input').attr("required", false);
                $('div.extra_option_hint').hide();
            }
        }).change();

        $(document).on('input','.only_integer', function(evt) {
            var inputValue = $(this).val();
                $(this).val(inputValue.replace(/[^0-9.]/g, ''));
        });

      
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $(document).on('submit', "#productForm", function(e) {
          e.preventDefault();
          $('.error').html('');
          var name = $("#name").val();
          // var formData = $("#productForm").serialize();
          var formData = new FormData($("#productForm")[0]);
          $('.save_btn').prop('disabled', true);
          $.ajax({
            type: "POST",
            url: "{{ route('admin.master.products.store') }}",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
              $('.save_btn').prop('disabled', false);
              if ($.isEmptyObject(data.error)) {
                $('.success_error_message').html(`<span class="text-success">${data.success}</span>`);
                // setTimeout(() => {
                //   location.reload();
                // }, 1500);                  
              } else {
                printErrorMsg(data.error);
              }
            },
            error: function(data){
              $('.save_btn').prop('disabled', false);
            }
          });
        });
 })

function printErrorMsg(msg) {
  $.each(msg, function(key, value) {
    $(`.error_${key}`).html(value);
  });
}
        
</script>
@endsection
