<div class="product-table-design" id="products_table">
    <div class="row">
        @php $isSubProductCheck = "No"; @endphp
        @if((isset($product->is_sub_product) && $product->is_sub_product == 1) || (isset($product->product->is_sub_product) && $product->product->is_sub_product == 1))
        @php $isSubProductCheck = "Yes"; @endphp
        <div class="col-lg-3 sub_product_div">
            <div class="form-group">
                <label for="sub_product">@lang('admin_master.product.sub_product')</label>
                @if((is_array($orders) && count($orders)>0) || ($orders->count() > 0))
                <a href="javascript: void(0);" id="addNewSubProduct" class="add-inline-btn" style="float: right;"><i class="fa fa-plus {{(is_array($orders) && count($orders)>0) || ($orders->count() > 0) ? 'selectBox':'textBox'}}"></i></a>
                {{-- <select name="is_sub_product" class="form-control select2 sub_product is_sub_product_select mb-5" required> --}}
                <select name="is_sub_product" class="form-control select2 sub_product is_sub_product_select">
                    <option value="" data-order_id="" data-price="0">{{ trans('quickadmin.qa_please_select') }}</option>
                    @foreach($orders as $value)
                    <option value="{{ $value->is_sub_product }}" data-order_id="{{ $value->id }}" data-price="{{ $value->price }}" {{$value->is_sub_product == $product->is_sub_product ? 'selected' : ''}}>{{ $value->is_sub_product }}</option>
                    @endforeach
                </select>
                @endif
                <input type="text" name="is_sub_product" id="sub_product" class="form-control sub_product is_sub_product_text" style="display:{{ ((is_array($orders) && count($orders)>0) || ($orders->count() > 0) > 0)? 'none':''}}" />

            </div>
            <span id="is_sub_product" class="text-danger d-none" role="alert" style="font-size:12px;"></span>
        </div>
        @endif

        <div class="col-lg-3 quantity-column">
            <div class="quantity-content">
                <div class="form-group">
                    <label>@lang('admin_master.product.unit_type') <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="product_unit" value="{{ (isset($product) && $product->product_unit) ? $product->product_unit->name : ((isset($product) && $product->product && $product->product->product_unit) ? $product->product->product_unit->name : '') }}" id="product_unit" autocomplete="true" readonly>
                    </div>
                    <div class="error_product_unit text-danger error"></div>
                </div>
                <div class="form-group">
                    <label>@lang('admin_master.new_estimate.quantity') <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" class="form-control" name="product_quantity" value="{{ isset($product) ? $product->quantity : 0 }}" min="0" max="999999" id="product_quantity" autocomplete="false">
                    </div>
                    <span id="quantity_error" class="text-danger  d-none" role="alert" style="font-size:12px;"></span>
                </div>
            </div>
        </div>

        {{-- <div class="col-lg-1 col-md-12 pr-0">
            <div class="form-group">
                <label>@lang('admin_master.product.unit_type') <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="text" class="form-control" name="product_unit" value="{{ (isset($product) && $product->product_unit) ? $product->product_unit->name : ((isset($product) && $product->product && $product->product->product_unit) ? $product->product->product_unit->name : '') }}" id="product_unit" autocomplete="true" readonly>
                </div>
                <div class="error_product_unit text-danger error"></div>
            </div>
        </div>

        <div class="col-lg-1 col-md-12 pr-0">
            <div class="form-group">
                <label>@lang('admin_master.new_estimate.quantity') <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="number" class="form-control" name="product_quantity" value="{{ isset($product) ? $product->quantity : 0 }}" min="0" max="999999" id="product_quantity" autocomplete="false">
                </div>
                <span id="quantity_error" class="text-danger  d-none" role="alert" style="font-size:12px;"></span>
            </div>
        </div> --}}

        <!-- Start Price -->
        @php
        $orderProductId = '';
        if(isset($editRow) && $editRow){
        $price = $product->price;
        $orderProductId = $product->id;
        }else{
        $price = 0.00;
        if(isset($last_order_price) && $last_order_price != 0){
        $price = $last_order_price;
        }else if(isset($product)){
        if($customer->is_type == 'retailer'){
        $price = $product->retailer_price;
        }else if($customer->is_type == 'wholesaler'){
        $price = $product->wholesaler_price;
        }else{
        $price = $product->sale_price;
        }
        }
        }

        $productAtr = 0;
        if(isset($product) && ((isset($product->calculation_type) && !in_array($product->calculation_type,config('constant.product_category_id'))) || (isset($product->product->calculation_type) && !in_array($product->product->calculation_type,config('constant.product_category_id'))))){
        if($product->is_height || ($product->product && $product->product->is_height)){
        $productAtr++;
        }
        if($product->is_width || ($product->product && $product->product->is_width)){
        $productAtr++;
        }
        if($product->is_length || ($product->product && $product->product->is_length)){
        $productAtr++;
        }
        }
        @endphp

        <div class="price-column {{ $productAtr != 0 ? 'col-lg-2' : 'col-lg-2'}} ">
            <div class="quantity-content">
                {{--@if(isset($editRow) && $editRow)
                <input type="hidden" name="extra_option_hint" class="extra_option_hint" value="{{$product->product->extra_option_hint ?? '' }}">

                @if(isset($product->product) && !in_array($product->product->calculation_type,config('constant.product_category_id')))
                @if($product->product->is_height)
                <div class="form-group">
                    <label for="height">@lang('admin_master.g_height')</label>
                    <input type="number" class="form-control input-size update_price pro_height" value="" min="0" max="999999" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Period','NumpadDecimal'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length <= 6" required />
                    <span class="inche-span"><label>{{$product->product->extra_option_hint ?? '' }}</label></span>
                </div>
                @endif

                @if($product->product->is_width)
                <div class="form-group">
                    <label for="width">@lang('admin_master.g_width')</label>
                    <input type="number" value="" class="form-control input-size update_price pro_width" min="0" max="999999" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Period','NumpadDecimal'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length <= 6" required />
                    <span class="inche-span"><label>{{$product->product->extra_option_hint ?? '' }}</label></span>
                </div>
                @endif

                @if($product->product->is_length)
                <div class="form-group">
                    <label for="length">@lang('admin_master.g_length')</label>
                    <input type="number" value="" class="form-control input-size update_price pro_length" max="999999" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Period','NumpadDecimal'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length <= 6" min="0" required />
                    <span class="inche-span"><label>{{$product->product->extra_option_hint ?? '' }}</label></span>
                </div>
                @endif

                @endif

                @else
                <input type="hidden" name="extra_option_hint" class="extra_option_hint" value="{{$product->extra_option_hint ?? '' }}">

                @if(isset($product) && !in_array($product->calculation_type,config('constant.product_category_id')))
                @if(isset($product) && $product->is_height)
                <div class="form-group">
                    <label for="height">@lang('admin_master.g_height')</label>
                    <input type="number" class="form-control input-size update_price pro_height" value="0" min="0" max="999999" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Period','NumpadDecimal'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length <= 6" required />
                    <span class="inche-span"><label>{{$product['extra_option_hint'] ?? '' }}</label></span>
                </div>
                @endif

                @if(isset($product) && $product->is_width)
                <div class="form-group">
                    <label for="width">@lang('admin_master.g_width')</label>
                    <input type="number" value="0" class="form-control input-size update_price pro_width" min="0" max="999999" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Period','NumpadDecimal'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length <= 6" required />
                    <span class="inche-span"><label>{{$product['extra_option_hint'] ?? '' }}</label></span>
                </div>
                @endif

                @if(isset($product) && $product->is_length)
                <div class="form-group">
                    <label for="length">@lang('admin_master.g_length')</label>
                    <input type="number" value="0" class="form-control input-size update_price pro_length" max="999999" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Period','NumpadDecimal'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length <= 6" min="0" required />
                    <span class="inche-span"><label>{{$product['extra_option_hint'] ?? '' }}</label></span>
                </div>
                @endif

                @endif

                @endif--}}

                <div class="form-group">
                    <label for="price">@lang('quickadmin.order.fields.price')</label>
                    <!-- <span class="form-control price" id="price">{{ $price }}</span> -->
                    <input type="text" data-price="{{ $price }}" value="{{ $price }}" class="form-control price" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Period','NumpadDecimal','KeyN'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space'" min="0" step=".01" autocomplete="off" id="product_price">

                    <span id="price_error" class="text-danger  d-none" role="alert" style="font-size:12px;"></span>
                </div>

                <!-- End Price -->
            </div>
        </div>

        <!-- Start Sub Total -->
        <div class="col-lg-2 amount-column">
            <div class="form-group">
                <label for="customer">@lang('quickadmin.order.fields.sub_total')</label>
                <div class="input-group">
                    <input type="text" class="form-control only_integer product_amount" name="product_amount" value="" id="product_amount" autocomplete="false" placeholder="0" readonly>
                </div>
                <div class="error_product_amount text-danger error"></div>
                <!-- <span class="form-control sub_total"> {{ $product->sale_price ?? 0.00}}</span> -->
                <!-- <span class="form-control sub_total"> 0.00</span> -->
            </div>
        </div>
        <!-- End Sub Total -->

        {{-- <div class="{{ $productAtr != 0 ? 'col-lg-2' : 'col-lg-5'}} form-group add-product mt-10"> --}}
        <div class="{{ ($isSubProductCheck == "Yes")? 'col-lg-2' : 'col-lg-5'}} form-group add-product mt-10">
            {{-- @if(isset($product) && (in_array($product->product_category_id,config('constant.product_category_id')) || in_array($product->product->product_category_id,config('constant.product_category_id')))) 
            @if(isset($product) && in_array($product->calculation_type,config('constant.product_category_id')))--}}

            @if(isset($product) && ((isset($product->calculation_type) && in_array($product->calculation_type,config('constant.product_category_id'))) || (isset($product->product->calculation_type) && in_array($product->product->calculation_type,config('constant.product_category_id')))))
            <button title="Fill Product Details" type="button" id='glassProductBtn'  data-product-exists="{{$orderProductId}}" data-calculation_type="{{$product->calculation_type??''}}" data-edit-row-num="{{ isset($dataRowIndex) ? $dataRowIndex : '' }}" class="glassProductBtn pull-right btn btn-primary"><i class="fa fa-plus"></i> Details</button>
            @endif
            <button title="Add Description" type="button" id='addDesBtn' data-product-exists="{{$orderProductId}}" data-edit-row-num="{{ isset($dataRowIndex) ? $dataRowIndex : '' }}" class="addDes pull-right btn btn-primary"><i class="fa fa-commenting"></i></button>
            <button title="Add Product" type="button" id='add_row' data-product-exists="{{$orderProductId}}" data-edit-row-num="{{ isset($dataRowIndex) ? $dataRowIndex : '' }}" class="addRow pull-right btn btn-success"><i class="fa fa-plus"></i></button>
            
        </div>
    </div>
</div>