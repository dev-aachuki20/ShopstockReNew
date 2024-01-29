<div class="col-md-6">
    <div class="form-group">
        <label>@lang('admin_master.product.product_name') <span class="text-danger">*</span></label>
        <div class="input-group">
        <input type="text" class="form-control phone-number" name="name" value="{{ isset($product) ? $product->name : old('name') }}" id="name" autocomplete="true" placeholder="@lang('admin_master.product.product_name_enter')">
        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label>@lang('admin_master.product.print_name') <span class="text-danger">*</span></label>
        <div class="input-group">
        <input type="text" class="form-control phone-number" name="name" value="{{ isset($product) ? $product->name : old('name') }}" id="name" autocomplete="true"  placeholder="@lang('admin_master.product.print_name_enter')">
        </div>
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <label>@lang('admin_master.product.group_type') <span class="text-danger">*</span></label>
        <div class="input-group">
            {!! Form::select('group_id', $groups, old('group_id'), ['class' => 'form-control select2', 'required' => '','id'=>'groupList']) !!}
          </div>
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <label>@lang('admin_master.product.product_type') <span class="text-danger">*</span></label>
        <div class="input-group">
            {!! Form::select('product_category_id', $product_categories, old('product_category_id'), ['class' => 'form-control select2', 'required' => '']) !!}
        </div>
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <label>@lang('admin_master.product.unit_type') <span class="text-danger">*</span></label>
        <div class="input-group">
            {!! Form::select('unit_type',['' => trans('admin_master.g_please_select')]+ config('constant.unitTypes'), old('measurement_type'), ['class' => 'form-control select2', 'required' => '']) !!}
        </div>
    </div>
</div>
<div class="col-md-3">
    <label>@lang('admin_master.product.extra_option') <span class="text-danger">*</span></label>
    <br>
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
        <input type="text" class="form-control phone-number" name="name" value="{{ isset($product) ? $product->name : old('name') }}" id="name" autocomplete="true" placeholder="@lang('admin_master.product.enter_hint')">
    </div>
</div>



<div class="col-md-3">
    <div class="form-group">
        <label>@lang('admin_master.product.purchase_price') <span class="text-danger">*</span></label>
        <div class="input-group">
        <input type="text" class="form-control phone-number" name="name" value="{{ isset($product) ? $product->price : old('price') }}" id="price" autocomplete="true" placeholder="@lang('admin_master.product.purchase_price_enter')">
        </div>
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <label>@lang('admin_master.product.min_sale_price') <span class="text-danger">*</span></label>
        <div class="input-group">
        <input type="text" class="form-control phone-number" name="name" value="{{ isset($product) ? $product->min_sale_price : old('min_sale_price') }}" id="min_sale_price" autocomplete="true" placeholder="@lang('admin_master.product.min_sale_price_enter')">
        </div>
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <label>@lang('admin_master.product.wholesaler_price') <span class="text-danger">*</span></label>
        <div class="input-group">
        <input type="text" class="form-control phone-number" name="name" value="{{ isset($product) ? $product->wholesaler_price : old('wholesaler_price') }}" id="wholesaler_price" autocomplete="true" placeholder="@lang('admin_master.product.wholesaler_price_enter')">
        </div>
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <label>@lang('admin_master.product.retailer_price') <span class="text-danger">*</span></label>
        <div class="input-group">
        <input type="text" class="form-control phone-number" name="name" value="{{ isset($product) ? $product->retailer_price : old('retailer_price') }}" id="retailer_price" autocomplete="true" placeholder="@lang('admin_master.product.retailer_price_enter')">
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="form-group">
        <label>@lang('admin_master.g_image') <span class="text-danger">*</span></label>
        <div class="input-group">
        <input type="text" class="form-control phone-number" name="name" value="{{ isset($product) ? $product->name : old('name') }}" id="name" autocomplete="true">
        </div>
    </div>
</div>
<div class="col-md-12">  
  <input type="submit" class="btn btn-primary save_btn" value="@lang('admin_master.g_submit')">
</div>


