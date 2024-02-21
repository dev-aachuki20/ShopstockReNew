<div class="product-table-design" id="products_table">
    @if(isset($product->is_sub_product) && $product->is_sub_product == 1)
        <div class="col-xs-2 col-lg-2 col-md-2 col-sm-12 sub_product">
            <div class="form-group">
                <label for="quantity">@lang('quickadmin.order.fields.is_sub_product')</label>
                @if($orders->count() > 0)
                    <a href="javascript: void(0);" id="addNewSubProduct" class="add-inline-btn" style="float: right;"><i class="fa fa-plus {{$orders->count() > 0 ? 'selectBox':'textBox'}}"></i></a>
                    {{-- <select name="is_sub_product" class="form-control select2 sub_product is_sub_product_select mb-5" required> --}}
                    <select name="is_sub_product" class="form-control select2 sub_product is_sub_product_select mb-5">
                        <option value="" data-order_id="" data-price="0">{{ trans('quickadmin.qa_please_select') }}</option>
                        @foreach($orders as $value)
                            <option  value="{{ $value->is_sub_product }}" data-order_id="{{ $value->id }}" data-price="{{ $value->price }}">{{ $value->is_sub_product }}</option>
                        @endforeach
                    </select>
                @endif

                {{-- <input type="text" name="is_sub_product" class="form-control sub_product is_sub_product_text" style="display:{{ ($orders->count() > 0)? 'none':''}}" required/> --}}
                <input type="text" name="is_sub_product" class="form-control sub_product is_sub_product_text" style="display:{{ ($orders->count() > 0)? 'none':''}}" />

            </div>
            <span id="is_sub_product" class="invalid-feedback text-danger d-none" role="alert" style="font-size:12px;"></span>
        </div>
    @endif

    {{-- <div class="col-xs-2 col-lg-2 col-md-2  col-sm-12"> --}}
    <div class="col-xs-2 col-lg-2 col-md-2  col-sm-12">
        <div class="quantity-content">
            <div class="form-group">
                <label for="unit">@lang('quickadmin.product.fields.unit_type')</label>
                <p class="unit form-control">{{ $unit ?? ''}}</p>
            </div>
            {{-- <div class="form-group">
                <label for="quantity">@lang('quickadmin.order.fields.quantity')</label>
                <input type="number" value="{{$product->quantity ?? 0 }}" class="form-control update_price quantity" min="0" max="999999" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Period','NumpadDecimal'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length <= 6"  required {{isset($product) && in_array($product->product_category_id,config('constant.product_category_id_set')) ? 'disabled':''}} />
            </div> --}}
        </div>
    </div>

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
        @endphp


    <div class="col-xs-2 {{ (isset($product->is_sub_product) && $product->is_sub_product == 1) ? 'col-lg-1' : 'col-lg-3' }} col-md-12 col-sm-12">
        <div class="quantity-content">

            @if(isset($editRow) && $editRow)
                <input type="hidden" name="extra_option_hint" class="extra_option_hint" value="{{$product->product->extra_option_hint ?? '' }}">

                @if(isset($product->product) && !in_array($product->product->product_category_id,config('constant.product_category_id')))
                        @if($product->product->is_height)
                        <div class="form-group">
                            <label for="height">@lang('quickadmin.order.fields.height')</label>
                            <input type="number" class="form-control input-size update_price pro_height" value="{{$product->height}}" min="0" max="999999" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Period','NumpadDecimal'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length <= 6"  required/>
                            <span class="inche-span"><label>{{$product->product->extra_option_hint ?? '' }}</label></span>
                        </div>
                        @endif

                        @if($product->product->is_width)
                        <div class="form-group">
                            <label for="width">@lang('quickadmin.order.fields.width')</label>
                            <input type="number" value="{{$product->width}}" class="form-control input-size update_price pro_width"  min="0" max="999999" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Period','NumpadDecimal'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length <= 6"  required/>
                            <span class="inche-span"><label>{{$product->product->extra_option_hint ?? '' }}</label></span>
                        </div>
                        @endif

                        @if($product->product->is_length)
                        <div class="form-group">
                            <label for="length">@lang('quickadmin.order.fields.length')</label>
                            <input type="number" value="{{$product->length}}" class="form-control input-size update_price pro_length" max="999999" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Period','NumpadDecimal'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length <= 6"   min="0" required/>
                            <span class="inche-span"><label>{{$product->product->extra_option_hint ?? '' }}</label></span>
                        </div>
                        @endif

                @endif

            @else
                <input type="hidden" name="extra_option_hint" class="extra_option_hint" value="{{$product->extra_option_hint ?? '' }}">

                @if(isset($product) && !in_array($product->product_category_id,config('constant.product_category_id')))
                    @if(isset($product) && $product->is_height)
                    <div class="form-group">
                        <label for="height">@lang('quickadmin.order.fields.height')</label>
                        <input type="number" class="form-control input-size update_price pro_height" value="0" min="0" max="999999" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Period','NumpadDecimal'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length <= 6"  required/>
                        <span class="inche-span"><label>{{$product['extra_option_hint'] ?? '' }}</label></span>
                    </div>
                    @endif

                    @if(isset($product) && $product->is_width)
                    <div class="form-group">
                        <label for="width">@lang('quickadmin.order.fields.width')</label>
                        <input type="number" value="0" class="form-control input-size update_price pro_width"  min="0" max="999999" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Period','NumpadDecimal'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length <= 6"  required/>
                        <span class="inche-span"><label>{{$product['extra_option_hint'] ?? '' }}</label></span>
                    </div>
                    @endif

                    @if(isset($product) && $product->is_length)
                    <div class="form-group">
                        <label for="length">@lang('quickadmin.order.fields.length')</label>
                        <input type="number" value="0" class="form-control input-size update_price pro_length" max="999999" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Period','NumpadDecimal'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length <= 6"   min="0" required/>
                        <span class="inche-span"><label>{{$product['extra_option_hint'] ?? '' }}</label></span>
                    </div>
                    @endif

                @endif

            @endif

            <div class="form-group">
                <label for="price">@lang('quickadmin.order.fields.price')</label>
                <!-- <span class="form-control price" id="price">{{ $price }}</span> -->
                <input type="text" data-price="{{ $price }}" value="{{ $price }}" class="form-control price"  onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Period','NumpadDecimal','KeyN'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space'"  min="0" step=".01" autocomplete="off" id="price">

                <span id="price_error" class="invalid-feedback text-danger  d-none" role="alert" style="font-size:12px;"></span>
            </div>

            <!-- End Price -->
        </div>
    </div>

    <!-- Start Sub Total -->
    <div class="col-xs-1 {{ (isset($product->is_sub_product) && $product->is_sub_product == 1) ? 'col-lg-2' : 'col-lg-1' }} col-md-12 col-sm-12">
        <div class="form-group">
            <label for="customer">@lang('quickadmin.order.fields.sub_total')</label>
            <span class="form-control sub_total"> {{ $product->sale_price ?? 0.00}}</span>
        </div>
    </div>
    <!-- End Sub Total -->

    <div class="col-xs-2 {{ (isset($product->is_sub_product) && $product->is_sub_product == 1) ? 'col-lg-2' : 'col-lg-3' }} col-md-2 col-sm-2 form-group add-product mt-10">
        <button title="Add Product" type="button" id='add_row' data-product-exists="{{$orderProductId}}" data-edit-row-num="{{ isset($dataRowIndex) ? $dataRowIndex : '' }}" class="addRow pull-right btn btn-success"><i class="fa fa-plus"></i></button>

        <button title="Add Description" type="button" id='addDesBtn' data-product-exists="{{$orderProductId}}" data-edit-row-num="{{ isset($dataRowIndex) ? $dataRowIndex : '' }}" class="addDes pull-right btn btn-primary"><i class="fa fa-commenting"></i></button>

        {{-- @if(isset($product) && (in_array($product->product_category_id,config('constant.product_category_id')) || in_array($product->product->product_category_id,config('constant.product_category_id'))))           --}}
        @if(isset($product) && in_array($product->product_category_id,config('constant.product_category_id')))
            <button title="Fill Product Details" type="button" id='glassProductBtn' data-product-exists="{{$orderProductId}}" data-edit-row-num="{{ isset($dataRowIndex) ? $dataRowIndex : '' }}" class="pull-right btn btn-primary"><i class="fa fa-plus"></i> Details</button>
        @endif
    </div>

</div>
