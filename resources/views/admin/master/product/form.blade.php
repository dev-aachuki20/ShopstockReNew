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
            {!! Form::select('group_id', $groups, old('group_id'), ['class' => 'form-control select2', 'id'=>'groupList']) !!}
        </div>
        <div class="error_group_id text-danger error"></div>
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <label>@lang('admin_master.product.product_type') <span class="text-danger">*</span></label>
        <div class="input-group">
            {!! Form::select('product_category_id', $product_categories, old('product_category_id'), ['class' => 'form-control select2']) !!}
        </div>
        <div class="error_product_category_id text-danger error"></div>
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <label>@lang('admin_master.product.unit_type') <span class="text-danger">*</span></label>
        <div class="input-group">
            {!! Form::select('unit_type',['' => trans('admin_master.g_please_select')]+ config('constant.unitTypes'), old('unit_type'), ['class' => 'form-control select2']) !!}
        </div>
        <div class="error_unit_type text-danger error"></div>
    </div>
</div>
<div class="col-md-3">
    <div>
        <label>@lang('admin_master.product.extra_option') <span class="text-danger">*</span></label>
    </div>
        <label class="form-check-label" for="is_height">
        <input class="form-check-input extra_option" name="is_height" type="checkbox" id="is_height" value="1">
        @lang('admin_master.g_height')
    </label>
    
    <label class="form-check-label pl-5" for="is_width">
        <input class="form-check-input extra_option" name="is_width" type="checkbox" id="is_width" value="1">
        @lang('admin_master.g_width')
    </label>
    
    <label class="form-check-label pl-5" for="is_length">
        <input class="form-check-input extra_option" name="is_length" type="checkbox" id="is_length" value="1">
        @lang('admin_master.g_length')
    </label>
    
    <label class="form-check-label pl-5" for="is_sub_product">
        <input class="form-check-input is_sub_product" name="is_sub_product" type="checkbox" id="is_sub_product" value="1">
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
        <label>@lang('admin_master.g_image') <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="file" id="image" name="image" class="form-control">
        </div>
    </div>
</div>
<div class="col-md-12">  
  <input type="submit" class="btn btn-primary save_btn" value="@lang('admin_master.g_submit')">
</div>


