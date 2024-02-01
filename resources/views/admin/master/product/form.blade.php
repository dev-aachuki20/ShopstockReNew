<div class="col-md-6">
    <div class="form-group">
        <label>@lang('admin_master.product.product_name') <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="text" class="form-control" name="name" value="{{ isset($product) ? $product->name : '' }}" id="name" autocomplete="true" placeholder="@lang('admin_master.product.product_name_enter')">
        </div>
        <div class="error_name text-danger error"></div>
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label>@lang('admin_master.product.print_name') <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="text" class="form-control " name="print_name" value="{{ isset($product) ? $product->print_name : '' }}" id="print_name" autocomplete="false"  placeholder="@lang('admin_master.product.print_name_enter')">
        </div>
        <div class="error_print_name text-danger error"></div>
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <label>@lang('admin_master.product.group_type') <span class="text-danger">*</span></label>
        <div class="input-group">
            {!! Form::select('group_id', $groups, $product->group_id??'', ['class' => 'form-control select2', 'id'=>'groupList']) !!}
        </div>
        <div class="error_group_id text-danger error"></div>
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <label>@lang('admin_master.product.product_type') <span class="text-danger">*</span></label>
        <div class="input-group">
            {!! Form::select('product_category_id', $product_categories, $product->product_category_id??'', ['class' => 'form-control select2', 'id'=>'product_category_id']) !!}
        </div>
        <div class="error_product_category_id text-danger error"></div>
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <label>@lang('admin_master.product.unit_type') <span class="text-danger">*</span></label>
        <div class="input-group">
            {!! Form::select('unit_type',['' => trans('admin_master.g_please_select')]+ config('constant.unitTypes'), $product->unit_type??'', ['class' => 'form-control select2', 'id'=>'unit_type']) !!}
        </div>
        <div class="error_unit_type text-danger error"></div>
    </div>
</div>
<div class="col-md-3">
    <div>
        <label>@lang('admin_master.product.extra_option') <span class="text-danger">*</span></label>
    </div>
        <label class="form-check-label" for="is_height">
        <input class="form-check-input extra_option" name="is_height" type="checkbox" id="is_height" value="1" {{(($product->is_height??'') == 1)?'checked':''}}>
        @lang('admin_master.g_height')
    </label>
    
    <label class="form-check-label pl-5" for="is_width">
        <input class="form-check-input extra_option" name="is_width" type="checkbox" id="is_width" value="1" {{(($product->is_width??'') == 1)?'checked':''}}>
        @lang('admin_master.g_width')
    </label>
    
    <label class="form-check-label pl-5" for="is_length">
        <input class="form-check-input extra_option" name="is_length" type="checkbox" id="is_length" value="1" {{(($product->is_length??'') == 1)?'checked':''}}>
        @lang('admin_master.g_length')
    </label>
    
    <label class="form-check-label pl-5" for="is_sub_product">
        <input class="form-check-input is_sub_product" name="is_sub_product" type="checkbox" id="is_sub_product" value="1" {{(($product->is_sub_product ??'') == 1)?'checked':''}}>
        @lang('admin_master.product.is_sub_product')
    </label>

    <div class="extra_option_hint mb-5" style="display: none;">
        <input type="text" class="form-control " name="extra_option_hint" value="{{ isset($product) ? $product->extra_option_hint : '' }}" id="extra_option_hint" autocomplete="false" placeholder="@lang('admin_master.product.enter_hint')">
        <span class="error_extra_option_hint text-danger error"></span>
    </div>
</div>



<div class="col-md-3">
    <div class="form-group">
        <label>@lang('admin_master.product.purchase_price') <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="text" class="form-control only_integer" name="price" value="{{ isset($product) ? $product->price : '' }}" id="price" autocomplete="false" placeholder="@lang('admin_master.product.purchase_price_enter')">
        </div>
        <div class="error_price text-danger error"></div>
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <label>@lang('admin_master.product.min_sale_price') <span class="text-danger">*</span></label>
        <div class="input-group">
         <input type="text" class="form-control only_integer" name="min_sale_price" value="{{ isset($product) ? $product->min_sale_price :'' }}" id="min_sale_price" autocomplete="false" placeholder="@lang('admin_master.product.min_sale_price_enter')">
        </div>
        <div class="error_min_sale_price text-danger error"></div>
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <label>@lang('admin_master.product.wholesaler_price') <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="text" class="form-control only_integer" name="wholesaler_price" value="{{ isset($product) ? $product->wholesaler_price : '' }}" id="wholesaler_price" autocomplete="false" placeholder="@lang('admin_master.product.wholesaler_price_enter')">
        </div>
        <div class="error_wholesaler_price text-danger error"></div>
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <label>@lang('admin_master.product.retailer_price') <span class="text-danger">*</span></label>
        <div class="input-group">
         <input type="text" class="form-control only_integer" name="retailer_price" value="{{ isset($product) ? $product->retailer_price : '' }}" id="retailer_price" autocomplete="false" placeholder="@lang('admin_master.product.retailer_price_enter')">
        </div>
        <div class="error_retailer_price text-danger error"></div>
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <label>@lang('admin_master.g_image')</label>
        <div class="input-group">
            <input type="file" id="image" name="image" accept="image/*" onchange="previewFile()" class="form-control">
        </div>
        <div class="error_image text-danger error"></div>
        <div>
            @if(isset($product->image))
                <img alt="image" src="{{isset($product->image)? asset('storage/'.$product->image):""}}" alt="profile" class="widthHeigh mt-2 profile-image" id="profile-image1" >
           @else
                <img alt="" src="{{asset('admintheme/assets/img/default-img.jpg')}}" alt="profile" class="widthHeigh   mt-2 profile-image" id="profile-image1" >
           @endif
        </div>
    </div>
</div>
<div class="col-md-12">  
    <div class="success_error_message"></div>
  <input type="submit" class="btn btn-primary save_btn" value="@lang('admin_master.g_submit')">
</div>


<!-- Add Edit Modal -->
<div class="modal fade" id="add_newModal" tabindex="-1" role="dialog" aria-labelledby="add_newModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Add <span class="add_new_drop"></span></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="naem">Name:</label>
            <input type="text" class="form-control" id="add_new_name" placeholder="Enter name">
            <span class="error_new_name text-danger error"></span>
          </div>
        </div>
        <div class="modal-footer">
          <div class="success_error_message_add)new"></div>
          <button type="button" class="btn btn-primary save_add_new">Save</button>
        </div>
      </div>
    </div>
  </div>
<!-- Add Edit Modal -->


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
        $('.success_error_message').html('');
        var action = $(this).attr('action');
        var method = $(this).attr('method');
        var formData = new FormData($("#productForm")[0]);
        $('.save_btn').prop('disabled', true);
        formData.append('_method', method);

        $.ajax({
        type: "POST",
        url: action,
        data: formData,
        contentType: false,
        processData: false,
        success: function(data) {            
            if ($.isEmptyObject(data.error)) {
                $('.success_error_message').html(`<span class="text-success">${data.success}</span>`);
                setTimeout(() => {
                    window.location.replace("{{route('admin.master.products.index')}}");
                }, 1500);                  
            } else {
                $('.save_btn').prop('disabled', false);
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
  
function previewFile() {
    var preview = document.querySelector("img.profile-image");
    var file = document.querySelector("input[type=file]").files[0];
    var reader = new FileReader();
    reader.addEventListener(
      "load",
      function () {
        preview.src = reader.result;
      },
      false,
    );
    if (file) {
      reader.readAsDataURL(file);
    }
  }
// add new dropdown
  $("#groupList").select2({
    }).on('select2:open', function () {
        let a = $(this).data('select2');
        if (!$('.select2-group_add').length) {
            a.$results.parents('.select2-results').append('<div class="select2-group_add select_2_add_btn"><button class="btns addNewgroupBtn get-customer"><i class="fa fa-plus-circle"></i> Add New</button></div>');
        }
    });
  $("#product_category_id").select2({
    }).on('select2:open', function () {
        let a = $(this).data('select2');
        if (!$('.select2-product_category_add').length) {
            a.$results.parents('.select2-results').append('<div class="select2-product_category_add select_2_add_btn"><button class="btns addNewCategoryBtn get-customer"><i class="fa fa-plus-circle"></i> Add New</button></div>');
        }
    });
  $("#unit_type").select2({
    }).on('select2:open', function () {
        let a = $(this).data('select2');
        if (!$('.select2-unit_type_add').length) {
            a.$results.parents('.select2-results').append('<div class="select2-unit_type_add select_2_add_btn"><button class="btns addNewCustomerBtn get-customer"><i class="fa fa-plus-circle"></i> Add New</button></div>');
        }
    });
$(document).ready(function(){
    $(document).on('click',".addNewgroupBtn",function(){
        $('.add_new_drop').html('Group');
        $("#add_newModal").modal('show');
        $("#groupList").select2('close');
        $(".save_add_new").removeClass('add_category_btn');
        $(".save_add_new").addClass('add_group_btn');
    }); 
    $(document).on('click','.add_group_btn',function(e){
        e.preventDefault();
        var name = $("#add_new_name").val();
        $('.save_add_new').prop('disabled', true);
        $.ajax({
            type: "POST",
            url: "{{ route('admin.master.groups.store') }}",
            data: {name: name, id: ""},
            success: function(data) {
            $('.save_add_new').prop('disabled', false);
            if ($.isEmptyObject(data.error)) {
                $("#add_newModal").modal('hide');
                var alertType = "{{ trans('quickadmin.alert-type.success') }}";
                var message = data.success;
                var title = "Group";
                showToaster(title,alertType,message);              
            } else {
                printErrorMsgAdd(data.error);
            }
            }
        });          
    })

    $(document).on('click',".addNewCategoryBtn",function(){
        $('.add_new_drop').html('Category');
        $("#add_newModal").modal('show');
        $("#product_category_id").select2('close');
        $(".save_add_new").removeClass('add_group_btn');
        $(".save_add_new").addClass('add_category_btn');
    });

    $(document).on('click','.add_category_btn',function(e){
        e.preventDefault();
        var name = $("#add_new_name").val();
        $('.save_add_new').prop('disabled', true);
        $.ajax({
            type: "POST",
            url: "{{ route('admin.master.categories.store') }}",
            data: { name: name,id: ""},
            success: function(data) {
                $('.save_add_new').prop('disabled', false);
                if ($.isEmptyObject(data.error)) {
                    $("#add_newModal").modal('hide');
                    var alertType = "{{ trans('quickadmin.alert-type.success') }}";
                    var message = data.success;
                    var title = "Category";
                    showToaster(title,alertType,message);                   
                } else {
                    printErrorMsgAdd(data.error);
                }
            }
        });
        });

    function printErrorMsgAdd(msg) {
            $.each(msg, function(key, value) {
              $(`.error_new_${key}`).html(value);
            });
          }
});

</script>
@endsection