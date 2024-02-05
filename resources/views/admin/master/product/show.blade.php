
<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered table-striped">
            <tr>
                <th>@lang('admin_master.product.product_name')</th>
                <td field-key='user name'>{{ $product->name ?? '' }}</td>
            </tr>

            <tr>
                <th>@lang('admin_master.product.group_type_name')</th>
                <td field-key='user name'>{{ $product->group->name ?? '' }}</td>
            </tr>
            <tr>
                <th>@lang('admin_master.product.sub_group_type_name')</th>
                <td field-key='user name'>{{ $product->sub_group->name ?? '' }}</td>
            </tr>
            <tr>
                <th>@lang('admin_master.product.product_type')</th>
                @php 
                    $calculation = config('constant.calculationType');
                @endphp 

                <td field-key='user name'>{{ $calculation[$product->calculation_type] ?? '' }}</td>
            </tr>
            <tr>
                <th>@lang('admin_master.product.unit_type')</th>
                <td field-key='user name'>{{ $product->product_unit->name ?? '' }}</td>
            </tr>



            <tr>
                <th>@lang('admin_master.product.purchase_price')</th>
                <td field-key='user name'><i class="fa fa-inr"></i> {{ $product->price ?? '' }}</td>
            </tr>
            <tr>
                <th>@lang('admin_master.product.min_sale_price')</th>
                <td field-key='user name'><i class="fa fa-inr"></i> {{ $product->min_sale_price ?? '' }}</td>
            </tr>
            <tr>
                <th>@lang('admin_master.product.wholesaler_price')</th>
                <td field-key='user name'><i class="fa fa-inr"></i> {{ $product->wholesaler_price ?? '' }}</td>
            </tr>
            <tr>
                <th>@lang('admin_master.product.retailer_price')</th>
                <td field-key='user name'><i class="fa fa-inr"></i> {{ $product->retailer_price ?? '' }}</td>
            </tr>



            <tr>
                <th>@lang('admin_master.g_height')</th>
                <td field-key='user name'>{{ $product->is_height > 0 ?? '' }}</td>
            </tr>
            <tr>
                <th>@lang('admin_master.g_width')</th>
                <td field-key='user name'>{{ $product->is_width > 0 ?? '' }}</td>
            </tr>
            <tr>
                <th>@lang('admin_master.g_length')</th>
                <td field-key='user name'>{{ $product->is_length > 0 ?? '' }}</td>
            </tr>
            <tr>
                <th>@lang('admin_master.product.is_sub_product')</th>
                <td field-key='user name'>{{ $product->is_sub_product > 0 ?? '' }}</td>
            </tr>
            <tr>
                <th>@lang('admin_master.product.enter_hint')</th>
                <td field-key='user name'>{{ $product->extra_option_hint ?? '' }}</td>
            </tr>
            <tr>
                <th>@lang('admin_master.g_image')</th>
                <td field-key='user name'>
                    @if(isset($product->image))
                    <img alt="image" src="{{isset($product->image)? asset('storage/'.$product->image):""}}" alt="profile" class="widthHeigh mt-2 profile-image" id="profile-image1" >
                    @else
                            <img alt="" src="{{asset('admintheme/assets/img/default-img.jpg')}}" alt="profile" class="widthHeigh   mt-2 profile-image" id="profile-image1" >
                    @endif
                </td>
            </tr>



        </table>
    </div>
</div>