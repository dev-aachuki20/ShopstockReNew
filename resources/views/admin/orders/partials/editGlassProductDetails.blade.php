@if(isset($otherDetails) && count($otherDetails) > 0)

@foreach($otherDetails as $orderProduct)
<div class="row">

    <div class="col-xs-8 col-lg-5 col-md-12 col-sm-12">
        <div class="quantity-content">
            @if(isset($orderProduct) && ($orderProduct['height'] || $orderProduct['width'] || $orderProduct['length']))
            <input type="hidden" name="product_category_id" class="product_category_id" value="{{$product[0] ?? '' }}" />
            <input type="hidden" name="extra_option_hint" class="extra_option_hint_value" value="{{$orderProduct['extra_option_hint'] ?? '' }}" />
            @endif

            @if(isset($orderProduct['height']))
            <div class="form-group">
                <label>@lang('admin_master.g_height')</label>
                <input type="number" class="form-control input-size glass-input glass-height" value="{{ $orderProduct['height'] }}" min="0" max="999999" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Period','NumpadDecimal'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length <= 6" required />
                <span class="inche-span"><label>{{$orderProduct['extra_option_hint'] ?? '' }}</label></span>
            </div>
            @endif

            @if(isset($orderProduct['width']))
            <div class="form-group">
                <label>@lang('admin_master.g_width')</label>
                <input type="number" value="{{ $orderProduct['width'] }}" class="form-control input-size glass-input glass-width" min="0" max="999999" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Period','NumpadDecimal'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length <= 6" required />
                <span class="inche-span"><label>{{$orderProduct['extra_option_hint'] ?? '' }}</label></span>
            </div>
            @endif

            @if(isset($orderProduct['length']))
            <div class="form-group">
                <label>@lang('admin_master.g_height')</label>
                <input type="number" value="{{ $orderProduct['length'] }}" class="form-control input-size glass-input glass-length" max="999999" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Period','NumpadDecimal'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length <= 6" min="0" required />
                <span class="inche-span"><label>{{$orderProduct['extra_option_hint'] ?? '' }}</label></span>
            </div>
            @endif

        </div>
    </div>

    <div class="col-xs-3 col-lg-4 col-md-3  col-sm-12">
        <div class="form-group">
            <label>@lang('admin_master.g_number_of_item')</label>
            <input type="number" value="{{ $orderProduct['qty'] }}" class="form-control glass-input glass-no-item" min="0" max="999999" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Period','NumpadDecimal'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space' && this.value.length <= 6" required />
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

@endforeach
@endif