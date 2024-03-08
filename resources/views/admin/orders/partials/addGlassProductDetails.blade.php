<div class="row">

    <div class="col-xs-8 col-lg-5 col-md-12 col-sm-12">
        <div class="quantity-content"> 
            @if(isset($product) && ($product->is_height || $product->is_width || $product->is_length))
            <input type="hidden" name="product_category_id" class="product_category_id" value="{{$product['calculation_type'] ?? '' }}" />
            <input type="hidden" name="extra_option_hint" class="extra_option_hint_value" value="{{$product['extra_option_hint'] ?? '' }}" />
            @endif

            @if(isset($product) && $product->is_height)
            <div class="form-group">
                <label>@lang('admin_master.g_height')</label>
                <input type="number" class="form-control input-size glass-input glass-height" value="0" min="0" max="999999" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Period','NumpadDecimal'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length <= 6" required />
                <span class="inche-span"><label>{{$product['extra_option_hint'] ?? '' }}</label></span>
            </div>
            @endif

            @if(isset($product) && $product->is_width)
            <div class="form-group">
                <label>@lang('admin_master.g_width')</label>
                <input type="number" value="0" class="form-control input-size glass-input glass-width" min="0" max="999999" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Period','NumpadDecimal'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length <= 6" required />
                <span class="inche-span"><label>{{$product['extra_option_hint'] ?? '' }}</label></span>
            </div>
            @endif

            @if(isset($product) && $product->is_length)
            <div class="form-group">
                <label>@lang('admin_master.g_length')</label>
                <input type="number" value="0" class="form-control input-size glass-input glass-length" max="999999" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Period','NumpadDecimal'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length <= 6" min="0" required />
                <span class="inche-span"><label>{{ $product['extra_option_hint'] ?? '' }}</label></span>
            </div>
            @endif

        </div>
    </div>

    <div class="col-xs-3 col-lg-4 col-md-3  col-sm-12">
        <div class="form-group">
            <label>@lang('admin_master.g_number_of_item')</label>
            <input type="number" value="0" class="form-control glass-input glass-no-item" min="0" max="999999" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Period','NumpadDecimal'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length <= 6" required />
        </div>
    </div>

    <div class="col-xs-1 col-lg-3 col-md-1  col-sm-1 mt-10" style="margin-top: 25px;">
        <div class="form-group">
            <button type="button" class="deleteGlassRow pull-right btn btn-danger">
                <i class="fa fa-minus"></i>
            </button>
        </div>
    </div>


</div>