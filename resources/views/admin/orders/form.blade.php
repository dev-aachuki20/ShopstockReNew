<div class="col-md-3">
    <div class="form-group">
        <label>@lang('admin_master.new_estimate.customer_name') <span class="text-danger">*</span></label>
        <div class="input-group">
            {!! Form::hidden('order_type', $orderType) !!}
            {!! Form::hidden('type', optional(request())->route('type')) !!}
            {!! Form::hidden('deleted_opids') !!}
            {!! Form::select('customer_id', $customers, isset($customer->id) ? $customer->id : '', ['class' => 'form-control select2 customers', 'id'=>'customerList']) !!}
        </div>
        <div class="error_customer_id text-danger error"></div>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label>@lang('admin_master.new_estimate.customer_number') <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="text" class="form-control" name="customer_number" value="" id="customer_number" autocomplete="true" readonly>
        </div>
        <div class="error_customer_number text-danger error"></div>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label>@lang('admin_master.new_estimate.supply_place') <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="text" class="form-control" name="customer_place" value="" id="customer_place" autocomplete="true" readonly>
            <input type="hidden" name="area_id" class="area_id" id="area_id">
        </div>
        <div class="error_customer_place text-danger error"></div>
    </div>
</div>

<div class="col-md-2">
    <div class="form-group">
        <label>@lang('admin_master.new_estimate.date') <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="date" class="form-control" name="invoice_date" value="{{ (isset($order) && $order) ? $order->invoice_date : date('Y-m-d') }}" max="{{ date('Y-m-d') }}" id="date" autocomplete="true" placeholder="@lang('admin_master.product.product_name_enter')">
        </div>
        <div class="error_date text-danger error"></div>
    </div>
</div>

{{-- <div class="col-md-2">
    <div class="form-group">
        <label>@lang('admin_master.new_estimate.estimate_number') <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="text" class="form-control" name="estimate_number" value="{{ isset($product) ? $product->name : '' }}" id="estimate_number" autocomplete="true">
</div>
<div class="error_estimate_number text-danger error"></div>
</div>
</div> --}}

<div class="col-md-12 col-lg-12 col-xl-3">
    <div class="form-group">
        <label>@lang('admin_master.product.product_name') <span class="text-danger">*</span></label>
        <div class="input-group">
            <select class="form-control select2 products" id="product_list">
                <option value="">{{trans('admin_master.g_please_select')}}</option>
                @foreach($products as $productData)
                <option value="{{$productData->id}}" data-name="{{$productData->name ?? ''}}" data-type="{{$productData->calculation_type}}" data-isSubProduct="{{$productData->is_sub_product}}">{{$productData->name ?? ''}}</option>
                @endforeach
            </select>
            {{--{!! Form::select('product_id', $products , $product->calculation_type??'', ['class' => 'form-control select2 products', 'id'=>'productList']) !!}--}}
        </div>
        <div class="error_product_id text-danger error"></div>
        {{-- <p id="get_estimate_price">
            <p class="d-none">
                <a href="#pre" id="prevOrderLink" data-order="">Pre. Price:<span class="min-pre-price"> 555</span></a>
            </p>
        </p> --}}

        <p class="product_information-block" style="display:none">
            {{-- <label>Product Price:<span class="purchase-price"> 777</span></label> --}}
            <label>MSP:<span class="min-sale-price"> 555</span></label>
            <label><span class="price-name">WSP</span>:<span class="whole-sale-price"> 333</span></label>
            <label><a href="#pre" id="prevOrderLink" data-order="">Pre. Price:<span class="min-pre-price"> 555</span></a></label>
        </p>
    </div>
</div>

<div class="form-group product-detail col-md-12 col-lg-12 col-xl-9">
    @include('admin.orders.product_detail')
</div>

{{--<div class="col-md-1">
    <div class="form-group">
        <label>@lang('admin_master.product.unit_type') <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="text" class="form-control" name="product_unit" value="{{ isset($product) ? $product->unit_type : '' }}" id="product_unit" autocomplete="true" readonly>
</div>
<div class="error_product_unit text-danger error"></div>
</div>
</div>

<div class="col-md-1">
    <div class="form-group">
        <label>@lang('admin_master.new_estimate.quantity') <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="number" class="form-control" name="product_quantity" value="{{ isset($product) ? $product->quantity : 0 }}" min="0" max="999999" id="product_quantity" autocomplete="false">
        </div>
        <div class="error_product_quantity text-danger error"></div>
    </div>
</div>


<div class="col-md-3">
    <div class="form-group">
        <label>@lang('admin_master.new_estimate.price') <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="text" class="form-control only_integer product_price" name="product_price" value="{{ isset($product) ? $product->price : 0 }}" id="product_price" autocomplete="false">
        </div>
        <div class="error_product_price text-danger error"></div>
    </div>
</div>

<div class="col-md-1">
    <div class="form-group">
        <label>@lang('admin_master.new_estimate.amount') <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="text" class="form-control only_integer product_amount" name="product_amount" value="" id="product_amount" autocomplete="false" placeholder="0" readonly>
        </div>
        <div class="error_product_amount text-danger error"></div>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group add-product">
        <button title="Add Product" type="button" id="add_row" data-product-exists="" data-edit-row-num="" class="addRow pull-right btn btn-success"><i class="fa fa-plus"></i></button>
        <button title="Add Description" type="button" id="addDesBtn" data-product-exists="" data-edit-row-num="" class="addDes pull-right btn btn-primary"><i class="fa fa-commenting"></i></button>
    </div>
</div>--}}


{{-- table html --}}
<div class="col-md-12 form-group order_products_table-design">
    {{-- @include('admin.orders.order_detail_table') --}}


    <div class="table-responsive">
        <table class="table" id="order_products_table">
            <thead>
                <tr>
                    <th>S No.</th>
                    <th class="desc">Product Name</th>
                    <th class="qty">Quantity</th>
                    <th>Price</th>
                    <th>Amount</th>
                    <th width="100">&nbsp;</th>
                </tr>
            </thead>
            <tbody class="table-for-data">
                @if(isset($order) && $order->orderProduct->count() > 0)
                @php
                $snCount = 0;
                @endphp
                @foreach($order->orderProduct as $order_product)
                <tr id="order-product-row-{{++$snCount }}" class="{{ !is_null($order_product->deleted_at) ? 'line-through-row deleted-row' : '' }}">
                    <td id="{{ $snCount }}">
                        {{ $snCount }}
                    </td>
                    <td class="desc">
                        <input type='hidden' name='products[{{$snCount}}][is_draft]' value='{{$order_product->is_draft}}' required />
                        <input type="hidden" name="products[{{$snCount}}][opid]" value="{{$order_product->id}}" required="">
                        <input type="hidden" name="products[{{$snCount}}][product_id]" value="{{$order_product->product_id}}" required="">
                        <input type="hidden" name="products[{{$snCount}}][description]" value="{{$order_product->description}}" required="">

                        {{ $order_product->product->name }}
                        @if(in_array($order_product->product->calculation_type,config('constant.product_category_id')))
                        {!! glassProductMeasurement($order_product->other_details,'new_line') !!}
                        @endif

                        @if($order_product->is_sub_product != '')
                        ({{ removeTrailingZeros($order_product->is_sub_product) }})
                        <input type='hidden' name='products[{{$snCount}}][is_sub_product]' value='1' required />
                        <input type="hidden" name="products[{{$snCount}}][is_sub_product_value]" value="{{$order_product->is_sub_product}}" required="">
                        @endif

                        @if(!is_null($order_product->description))
                        <p>({{ $order_product->description }})</p>
                        @endif

                    </td>
                    <td class="qty">
                        @if(isset($order_product) && $order_product->product->calculation_type != 2)
                        @if(!is_null($order_product->height))
                        <input type="hidden" name="products[{{$snCount}}][height]" value="{{$order_product->height}}" required="">{{removeTrailingZeros($order_product->height)}}{{$order_product->product->extra_option_hint}}
                        @endif

                        @if(!is_null($order_product->height) && !is_null($order_product->width))
                        x
                        @elseif(!is_null($order_product->height) && !is_null($order_product->length))
                        x
                        @endif

                        @if(!is_null($order_product->width))
                        <input type="hidden" name="products[{{$snCount}}][width]" value="{{$order_product->width}}" required="">{{removeTrailingZeros($order_product->width)}} {{$order_product->product->extra_option_hint}}
                        @endif

                        @if(!is_null($order_product->length) && !is_null($order_product->width))
                        x
                        @elseif(!is_null($order_product->height) && !is_null($order_product->length))
                        x
                        @endif

                        @if(!is_null($order_product->length))
                        <input type="hidden" name="products[{{$snCount}}][length]" value="{{$order_product->length}}" required="">{{removeTrailingZeros($order_product->length)}}{{$order_product->product->extra_option_hint}}
                        @endif

                        @if(!is_null($order_product->length) || !is_null($order_product->width) || !is_null($order_product->height))
                        -
                        @endif
                        {{-- @else
                                <input type="hidden" name="products[{{$snCount}}][other_details]" value="{{$order_product->other_details}}" required=""> --}}
                        @endif

                        @if ($order_product->product->calculation_type == 2 || $order_product->product->calculation_type == 3)
                        <input type="hidden" name="products[{{$snCount}}][other_details]" value="{{$order_product->other_details}}" required="">
                        @endif

                        <input type="hidden" name="products[{{$snCount}}][quantity]" value="{{$order_product->quantity}}" required="">{{removeTrailingZeros($order_product->quantity)}} {{ $order_product->product->product_unit->name }}

                    </td>
                    <td>
                        <input type="hidden" name="products[{{$snCount}}][price]" value="{{ number_format($order_product->price,2)}}" required="" data-isdraft="{{ $order_product->is_draft }}">{{ removeTrailingZeros($order_product->price)}}
                    </td>
                    <td class="total_price">
                        <input type="hidden" name="products[{{$snCount}}][total_price]" value="{{ number_format($order_product->total_price,0)}}" required="">{{ number_format($order_product->total_price,0)}}
                    </td>
                    <td>
                        @if(is_null($order_product->deleted_at))
                        <button type="button" id="delete_row_[{{$snCount}}]" class="deleteRow pull-right btn btn-danger" data-oproid="{{ $order_product->id }}"><i class="fa fa-trash" aria-hidden="true"></i></button>

                        <button type="button" id="edit_row_[{{$snCount}}]" class="editRow pull-right btn btn-primary" data-order-product="{{$order_product->id}}" data-customer="{{$order->customer_id}}" data-product="{{$order_product->product_id}}" data-type="{{$order_product->product->calculation_type}}" data-row-index="{{$snCount}}"><i class="fa fa-edit" aria-hidden="true"></i></button>
                        @endif
                    </td>
                </tr>
                @endforeach
                @else
                <tr id="tmptr">
                    <td colspan="6"></td>
                </tr>
                @endif
            </tbody>
            <tfoot class="order_create mt-5">
                @if(isset($order) && $order->is_add_shipping == 1 && !is_null($order->shipping_amount))
                @php
                $shipingAmount = str_replace(',','',$order->shipping_amount);
                @endphp
                <tr>
                    <th colspan="4" class="text-right">Shipping Amount</th>
                    <td colspan="2">
                        <input type="number" name="shipping_amount" class="form-control shipping_amount" min="0" max="999999" onkeydown="javascript: return [" backspace","delete","arrowleft","arrowright","tab","period","numpaddecimal"].includes(event.code)="" ?="" true="" :="" !isnan(number(event.key))="" &&="" event.code!="=&quot;Space&quot;" this.value.length="" <="10&quot;" required="" value="{{ $shipingAmount }}">
                    </td>
                </tr>
                @endif
                <tr>
                    <th colspan="4" class="grand total text-right">GRAND TOTAL</th>
                    <td colspan="2" class="grand total">
                        <input type="hidden" name="total_amount" class="grandTotalHidden">
                        <span class="form-control grand_total" id="grandTotalSpan">0</span>
                    </td>
                </tr>
                <tr class="remark">
                    <th colspan="4" class="total text-right">Remark</th>
                    <td colspan="2">
                        <input class="form-control" placeholder="Enter order remark" name="remark" type="text" value="{{(isset($order) && $order) ? $order->remark : ''}}">
                    </td>
                </tr>
                <tr class="sold_by">
                    <th colspan="4" class="total text-right">Sold By</th>
                    <td colspan="2">
                        <input class="form-control" placeholder="Sold By" name="sold_by" type="text" value="{{(isset($order) && $order) ? $order->sold_by : ''}}">
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="text-left">
        <input type="checkbox" name="is_add_shipping" class="is_add_shipping" id="isAddShipping" {{ isset($order) && $order->is_add_shipping == 1 ? 'checked': '' }}>
        <label for="isAddShipping">Add Shipping</label>
    </div>
    <div class="text-right order_create order_create_btnarea">
        <input type="hidden" name="submit" value="">
        @if($orderType=='create')<button class="btn btn-info btn-lg w-150" type="submit" name="submit" value="draft" disabled="">Save as Draft</button>@endif
        @if($orderType=='edit')
        <button class="btn btn-info btn-lg w-150" type="submit" name="submit" value="draft">
            {{ trans('quickadmin.qa_update_as_draft_invoice') }}</button>
        @endif
        <button class="btn btn-success btn-lg order_form_submit" type="button" name="submit" value="save">
            @if($orderType=='create')
            {{ trans('quickadmin.qa_save_estimate') }}
            @elseif($orderType == 'return')
            {{ trans('quickadmin.qa_save_invoice_return') }}
            @else
            {{ trans('quickadmin.qa_update') }}
            @endif
        </button>
    </div>
</div>
{{-- end table html --}}




<!-- Add Edit Modal -->
{{-- <div class="modal fade" id="add_newModal" tabindex="-1" role="dialog" aria-labelledby="add_newModalTitle" aria-hidden="true">
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
                <div class="success_error_message_add_new"></div>
                <button type="button" class="btn btn-primary save_add_new">Save</button>
            </div>
        </div>
    </div>
</div> --}}
<!-- Add Edit Modal -->

<!-- All modals -->
@include('admin.orders.modal.create_all_modal')

@section('customJS')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
$('#productForm').on('keyup keypress', function(e) {
  var keyCode = e.keyCode || e.which;
  if (keyCode === 13) { 
    if(!$('.select2').hasClass('select2-container--open')){
        $("#add_row").trigger('click');
    }
    e.preventDefault();
    return false;
  }
});
    var productDetail = {
        // 'customer':'',
        'product': '',
    };

    var glassProduct = {
        'products': {},
        'totalQty': 0
    };
    var productObject = {
        'description': ''
    };
    var productDetailHtml = $('#products_table').html();

    @if(isset($order) && $order)
    var customerId = '{{$order->customer_id}}';
    $('#customerList').val(customerId);
    handleCustomerData(customerId);
    @endif

    $(document).ready(function() {

        calculateGrandTotal();

        // get customer data
        $(document).on('change', '#customerList', function() {
            var customer_id = $(this).val();
            handleCustomerData(customer_id);
        });

        // get product data
        var productCost = {};
        $("select.products").change(function(e) {
            e.preventDefault();
            $('.error_product_id').html('');
            $('.products_error').hide();
            $('select.customers').siblings('#customer_error').remove();
            $('select.products').siblings('#products_error').remove();
            $('select.customers').siblings('.jquery-validation-error').remove();
            $('select.products').siblings('.jquery-validation-error').remove();

            customer_id = $(".customers option:selected").val();
            if (customer_id == '') {
                // $('#customer_error').show();
                $('<p id="customer_error" style="color:red; font-size:12px;">Please select customer.</p>').insertAfter($('select.customers').siblings('.select2-container'));
                $(this).select2('destroy').find('option:selected').prop('selected', false).end().select2();
                return;
            }

            var product_id = $(this).select2('val');
            var productCategory = $(this).find('option:selected').attr('data-type');
            var is_sub_product = $(this).find('option:selected').attr('data-isSubProduct');

            if (product_id != '' && customer_id != '') {
                productDetail['product'] = product_id;

                // $('.estimateInvoice').show();
                productCost['product_id'] = product_id;
                productCost['customer_id'] = customer_id;
                productCost['is_sub_product'] = is_sub_product;

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.get_product_detail') }}",
                    data: {
                        product_id: product_id,
                        customer_id: customer_id,
                    },
                    success: function(response) {
                        productCost['response'] = response;

                        if (response.status) {
                            $('.product-detail').html(response.data);

                            var products_table = $('#products_table');

                            var minPrePrice = 0;
                            if (response.rowData.last_order_price != 0) {
                                minPrePrice = response.rowData.last_order_price;
                                $('.min-pre-price').parent('#prevOrderLink').css('display', 'block');
                                $('.min-pre-price').parent('#prevOrderLink').attr('data-order', response.rowData.order);
                                $('.min-pre-price').text(minPrePrice);
                            } else {

                                if (response.rowData.customer_type == 'retailer') {
                                    minPrePrice = response.rowData.retailer_price;
                                } else if (response.rowData.customer_type == 'wholesaler') {
                                    minPrePrice = response.rowData.wholesaler_price;
                                } else {
                                    minPrePrice = response.rowData.price;
                                }

                                $('.min-pre-price').html('');
                                $('.min-pre-price').parent('#prevOrderLink').css('display', 'none');

                            }
                            var priceToDisplay = response.rowData.price;
                            if (response.rowData.last_order_price && response.rowData.last_order_price != 0) {
                                priceToDisplay = response.rowData.last_order_price;
                            }
                            products_table.find('.price').val(priceToDisplay);
                            // products_table.find('.sub_total').text(response.rowData.sub_total);

                            $('.purchase-price').text(response.rowData.purchase_price);
                            $('.min-sale-price').text(response.rowData.min_sale_price);
                            $('.whole-sale-price').text(response.rowData.price);
                            $('.price-name').text(response.rowData.priceName);

                            // $('.min-pre-price').parent('#prevOrderLink').attr('data-order',response.rowData.order);
                            // $('.min-pre-price').text(minPrePrice);
                            $('.addRow').data('product_name', response.rowData.product_name);

                            $('.product_information-block').show();
                            calculateProducts();
                        }
                    },
                    error: function(reject) {
                        if (reject.status === 400) {
                            var rejectResponse = $.parseJSON(reject.responseText);
                            var error = rejectResponse.errors;
                            $.each(error, function(key, val) {
                                // console.log("#" + key + "_error = "+val[0])
                                $("#" + key + "_error").text(val[0]);
                            });
                        }
                    }
                });
            } else {
                productDetail['product'] = '';

                // $('#glassProductBtn').remove();
                $('.product_information-block').hide();
                // $('#product_quantity').removeAttr('disabled');
                // $('#product_amount').val('0');
                // $('.price').val('0');
                // $('#product_unit').val('');
                // $('.error_product_id').show();
                $('#products_table').html(productDetailHtml);
            }
            // console.log("productCost",productCost);

        });

        // Start glass product details
        $(document).on('click', '#glassProductBtn', function(e) {
            $('select.products').siblings('p#products_error').remove();
            $('select.products').siblings('.jquery-validation-error').remove();

            var products_table = $('#products_table');

            if (productDetail.product == '') {
                $('<p id="products_error" style="color:red; font-size:12px;">Please select product.</p>').insertAfter($('select.products').siblings('.select2-container'));
            }

            var customer_id = $('select.customers').select2().val();
            var product_id = $(".products option:selected").val();
            var product_name = $(".products option:selected").attr('data-name');
            if (product_id != '') {
                $('#addGlassProductDetailModal').modal('show');
                $('#addGlassProductDetailModal').find('.modal-title').text(product_name);

                if (Object.keys(glassProduct.products).length > 0) {
                    // addGlassProductViewRender(customer_id,product_id,'append');
                } else {
                    addGlassProductViewRender(customer_id, product_id, 'html');
                }

            }
        });

        //Start glass product item
        $(document).on('click', '.addGlassRow', function(e) {
            var addGlassItemBtn = $(this);

            var products_table = $('#products_table');
            var customerId = $('select.customers').select2().val();
            var productId = $(".products option:selected").val();
            var price = products_table.find('.price').val();

            addGlassProductViewRender(customerId, productId, 'append');

        });

        $(document).on('click', '.deleteGlassRow', function() {
            $(this).parent().parent().parent().remove();
            delete glassProduct.products[$(this).attr('data-row-index')];
        });

        $(document).on('click', '.glass-item-save', function(e) {
            e.preventDefault();
            var totalQty = 0;
            var products_table = $('#products_table');
            var isModalClose = true;

            $('.glass-product-item .row').each(function(rowIndex, rowElement) {

                $(rowElement).find('.deleteGlassRow').attr('data-row-index', rowIndex);

                var product_category_id = $(rowElement).find('.product_category_id').val();
                var qty = $(rowElement).find('.glass-no-item').val();
                var height = $(rowElement).find('.glass-height').val();
                var width = $(rowElement).find('.glass-width').val();
                var length = $(rowElement).find('.glass-length').val();
                var extra_option_hint_value = $(rowElement).find('.extra_option_hint_value').val();

                var isEmpty = false;
                $('#addGlassProductDetailModal input[type="number"]').each(function() {
                    $(this).siblings('.glass_error').remove();

                    var value = $.trim($(this).val());

                    if (value == 0) {
                        isEmpty = true;
                        $('<p class="glass_error" style="color:red; font-size:12px;margin-bottom:0px;">This field is required!</p>').insertAfter($(this));
                    }

                });

                var measurementValue = 1;
                if (!isEmpty) {
                    var itemObj = {};
                    if (height != undefined) {
                        itemObj['height'] = parseFloat(height).toFixed(2).replace(/\.0+$/, '');

                        if (product_category_id != undefined && product_category_id == 2) {
                            measurementValue *= getClosestValue(parseFloat(height));
                        } else if (product_category_id != undefined && (product_category_id == 3 || product_category_id == 4)) {
                            measurementValue *= parseFloat(height);
                        }
                    }
                    if (width != undefined) {
                        itemObj['width'] = parseFloat(width).toFixed(2).replace(/\.0+$/, '');

                        if (product_category_id != undefined && product_category_id == 2) {
                            measurementValue *= getClosestValue(parseFloat(width));
                        } else if (product_category_id != undefined && (product_category_id == 3 || product_category_id == 4)) {
                            measurementValue *= parseFloat(width);
                        }
                    }
                    // if(length != undefined){
                    //     itemObj['length'] = parseFloat(length).toFixed(2).replace(/\.0+$/,'');

                    //     if(product_category_id != undefined && product_category_id == 2){
                    //         measurementValue *= getClosestValue(parseFloat(length));
                    //     }else if(product_category_id != undefined && product_category_id == 3){
                    //         measurementValue *= parseFloat(length);
                    //     }
                    // }

                    itemObj['qty'] = parseFloat(qty).toFixed(2).replace(/\.0+$/, '');

                    itemObj['extra_option_hint'] = extra_option_hint_value;

                    glassProduct['products'][rowIndex] = itemObj;

                    if (product_category_id != undefined && product_category_id == 2) {
                        totalQty += calculateSqft(measurementValue) * parseFloat(qty).toFixed(2);
                    } else if (product_category_id != undefined && (product_category_id == 3 || product_category_id == 4)) {
                        totalQty += measurementValue * parseFloat(qty).toFixed(2);
                    }

                } else {
                    isModalClose = false;
                }

            });

            products_table.find('#product_quantity').val(totalQty).trigger('input');
            glassProduct['totalQty'] = totalQty;
            if (isModalClose) {
                $('#addGlassProductDetailModal').modal('hide');
            }
        });
        //End glass product item

        // End glass product details

        // calulate total amount based on quantity and price.
        // $(document).on('keyup','#product_quantity, .price',function() {
        //     var product_quantity 	= $('#product_quantity').val(); 
        //     var product_price       = $("#product_price").val();
        //     var total = product_quantity * product_price;
        //     $("#product_amount").val(total.toFixed(2));
        // });

        $(document).on('click', '.addRow', function(e) {
            $('select.customers').siblings('p').remove();
            $('select.products').siblings('p#products_error').remove();
            $('select.customers').siblings('.jquery-validation-error').remove();
            $('select.products').siblings('.jquery-validation-error').remove();
            $('#product_quantity').siblings('#quantity_error').remove();
            $('.is_sub_product').siblings('#is_sub_product').remove();
            $('.error_product_id').html('');
            var row_count = 1;
            if ($('#order_products_table tbody tr').length > 0) {
                var firstTdText = $('#order_products_table tbody tr:first td:first').text().trim();
                if (firstTdText !== '') {
                    // Your code here
                    row_count = $('#order_products_table tbody tr').length + 1;
                }
            }

            if (productDetail.customer == '') {
                $('<p id="customer_error" style="color:red; font-size:12px;">Please select customer.</p>').insertAfter($('select.customers').siblings('.select2-container'));
            }
            if (productDetail.product == '') {
                $('<p id="products_error" style="color:red; font-size:12px;">Please select product.</p>').insertAfter($('select.products').siblings('.select2-container'));
            }

            var product_id = $(".products option:selected").val();
            var product_name = $(".products option:selected").attr('data-name');
            var productCategory = $('select.products').find('option:selected').attr('data-type');
            var is_sub_product = $('select.products').find('option:selected').attr('data-isSubProduct');
            var products_table = $('#products_table');
            var isDraftSymbol_N_Price = products_table.find('.price').val().toLowerCase() == 'n' ? 1 : 0;

            var unit = products_table.find('#product_unit').val();
            var quantity = products_table.find('#product_quantity').val();
            var height = products_table.find('.pro_height').val();
            var width = products_table.find('.pro_width').val();
            var length = products_table.find('.pro_length').val();
            // var price 		= parseFloat(products_table.find('.price').val()).toFixed(2);
            var price = products_table.find('.price').val().toLowerCase() == 'n' ? 0 : parseFloat(products_table.find('.price').val()).toFixed(2);
            // var total_price   = parseFloat(products_table.find('.sub_total').text()).toFixed(0);
            var total_price = products_table.find('.price').val().toLowerCase() == 'n' ? 0 : parseFloat(products_table.find('#product_amount').val()).toFixed(2);
            var extra_option_hint = products_table.find('.extra_option_hint').val();
            console.log(extra_option_hint);

            icon = products_table.find('#addNewSubProduct i');
            var is_sub_product_value = '';
            if (icon.hasClass("fa-plus")) {
                is_sub_product_value = products_table.find('.is_sub_product_select option:selected').val();
            } else {
                is_sub_product_value = products_table.find('.is_sub_product_text').val();
            }

            var MinSalePrice = parseFloat($('.min-sale-price').text());
            var orderProductId = $(this).attr('data-product-exists');
            if (is_sub_product == 1 && is_sub_product_value == '') {
                $('#is_sub_product').removeClass('d-none');
                $('#is_sub_product').text('Please enter sub product.');
                return false;
            } else {
                $('#is_sub_product').addClass('d-none');
                $('#is_sub_product').text('');
            }

            if ((!(MinSalePrice <= price)) && products_table.find('.price').val().toLowerCase() != 'n' && price != 0) {
                $('#price_error').removeClass('d-none');
                $('#price_error').text('Price should be greater or equal to min sale price.');
            } else {
                $('#price_error').addClass('d-none');
                $('#price_error').text('');

                if (product_id != '') {
                    if (quantity != 0) {
                        $('#quantity_error').addClass('d-none');
                        $('#quantity_error').text('');
                        if (products_table.find('.price').val() != 0) {
                            $('#price_error').addClass('d-none');
                            $('#price_error').text('');

                            $('.product_information-block').hide();
                            $('#tmptr').remove();
                            $(".products").select2().val('').trigger('change');

                            var editRowNum = $(this).attr('data-edit-row-num');
                            if (editRowNum == '') {
                                var markup = "<tr><td>" + row_count + "</td>";

                                var matrialElement = '';

                                if (productCategory == '2' || productCategory == '3' || productCategory == '4') {
                                    // console.log('proCat:',productCategory);
                                    var glassProductSizeList = '';
                                    for (const [key, product] of Object.entries(glassProduct.products)) {
                                        var extra_option_hint = product.extra_option_hint;
                                        var productMeasurement = '';

                                        if (productCategory == '2') {
                                            if (product.height != 0 && product.width != 0) {
                                                productMeasurement += product.height + " " + extra_option_hint + " × " + product.width + " " + extra_option_hint + " - " + product.qty + " pc";
                                            } else if (product.width != 0 && product.length != 0) {
                                                productMeasurement += product.width + " " + extra_option_hint + " × " + product.length + " " + extra_option_hint + " - " + product.qty + " pc";
                                            } else if (product.height != 0 && product.length != 0) {
                                                productMeasurement += product.height + " " + extra_option_hint + " × " + product.length + " " + extra_option_hint + " - " + product.qty + " pc";
                                            } else if (product.height != 0 && product.width != 0 && product.length != 0) {
                                                productMeasurement += product.height + " " + extra_option_hint + " × " + product.width + " " + extra_option_hint + " ×" + product.length + " " + extra_option_hint + " - " + product.qty + " pc";
                                            }
                                        } else if (productCategory == '3' && product.height != 0) {
                                            productMeasurement += product.height + " " + extra_option_hint + " - " + product.qty + " pc";
                                        } else if (productCategory == '4' && product.height != 0 && product.width != 0) {
                                            productMeasurement += product.height + " " + extra_option_hint + " × " + product.width + " " + extra_option_hint + " - " + product.qty + " pc";
                                        }
                                        glassProductSizeList += "<p style='margin-bottom: 0px;'>" + productMeasurement + "</p>";
                                    }

                                    markup += "<td class='desc'>" +
                                        "<input type='hidden' name='products[" + row_count + "][is_draft]' value='" + isDraftSymbol_N_Price + "' required/>" +
                                        "<input type='hidden' name='products[" + row_count + "][product_id]' value='" + product_id + "' required/>" +
                                        "<input type='hidden' name='products[" + row_count + "][description]' value='" + productObject.description + "' required/>" +
                                        "<input type='hidden' name='products[" + row_count + "][other_details]' value='" + JSON.stringify(glassProduct.products) + "' required/>" + product_name + glassProductSizeList;


                                    if (productObject.description != '') {
                                        markup += "<p style='margin-top:0px; margin-bottom:0px;'>(" + productObject.description + ")</p>";
                                    }

                                    markup += "</td>";

                                } else {
                                    markup += "<td class='desc'>";
                                    markup += "<input type='hidden' name='products[" + row_count + "][is_draft]' value='" + isDraftSymbol_N_Price + "' required/>";
                                    markup += "<input type='hidden' name='products[" + row_count + "][product_id]' value='" + product_id + "' required/>";
                                    markup += "<input type='hidden' name='products[" + row_count + "][description]' value='" + productObject.description + "' required/>";
                                    markup += product_name;

                                    if (is_sub_product != undefined && is_sub_product == 1 && is_sub_product_value != '') {
                                        markup += "(" + is_sub_product_value + ")";
                                        markup += "<input type='hidden' name='products[" + row_count + "][is_sub_product]' value='" + is_sub_product + "' required/>";
                                        markup += "<input type='hidden' name='products[" + row_count + "][is_sub_product_value]' value='" + is_sub_product_value + "' required/>";
                                    }

                                    if (productObject.description != '') {
                                        markup += "<p style='margin-top:0px; margin-bottom:0px;'>(" + productObject.description + ")</p>";
                                    }

                                    //8 ft x 4 ft - 1 pc 
                                    markup += "</td>";
                                    if (height != undefined && height != 0) {
                                        matrialElement += "<input type='hidden' name='products[" + row_count + "][height]' value='" + parseFloat(height).toFixed(2) + "' required/>  " + parseFloat(height).toFixed(2).replace(/\.0+$/, '') + extra_option_hint;
                                    }

                                    if (height != undefined && height != 0 && width != undefined && width != 0) {
                                        matrialElement += " x ";
                                    } else if (height != undefined && height != 0 && length != undefined && length != 0) {
                                        matrialElement += " x ";
                                    }

                                    if (width != undefined && width != 0) {
                                        matrialElement += "<input type='hidden' name='products[" + row_count + "][width]' value='" + parseFloat(width).toFixed(2) + "' required/>  " + parseFloat(width).toFixed(2).replace(/\.0+$/, '') + extra_option_hint;
                                    }

                                    if (width != undefined && width != 0 && length != undefined && length != 0) {
                                        matrialElement += " x ";
                                    } else if (height != undefined && height != 0 && length != undefined && length != 0) {
                                        matrialElement += " x ";
                                    }


                                    if (length != undefined && length != 0) {
                                        matrialElement += "<input type='hidden' name='products[" + row_count + "][length]' value='" + parseFloat(length).toFixed(2) + "' required/>  " + parseFloat(length).toFixed(2).replace(/\.0+$/, '') + extra_option_hint;
                                    }

                                    if (matrialElement != '') {
                                        matrialElement += " - ";
                                    }
                                }


                                markup += "<td class='qty'><input type='hidden' name='products[" + row_count + "][quantity]' value='" + parseFloat(quantity).toFixed(2) + "' required/>" + matrialElement + parseFloat(quantity).toFixed(2).replace(/\.0+$/, '') + " " + unit + "</td>";

                                markup += "<td><input type='hidden' name='products[" + row_count + "][price]' value='" + price + "' required/>" + price.toString().replace(/\.0+$/, '') + "</td><td class='total_price'><input type='hidden' name='products[" + row_count + "][total_price]' value='" + total_price + "' required/>" + total_price.toString().replace(/\.0+$/, '') + "</td>" +
                                    "<td><button type='button' id='delete_row_[" + row_count + "]' class='deleteRow pull-right btn btn-danger'><i class='fa fa-trash' aria-hidden='true'></i></button></td>" +
                                    "</tr>";
                                tableBody = $("#order_products_table tbody");
                                tableBody.append(markup);
                            } else {
                                var updateProductRow = $('#order-product-row-' + editRowNum).children();
                                $.each(updateProductRow, function(index, tdElement) {
                                    if (index == 0) {
                                        tdElement.innerHTML = editRowNum;
                                    }

                                    if (index == 1) {
                                        if (productCategory == '1') {
                                            tdElement.innerHTML = "<input type='hidden' name='products[" + editRowNum + "][is_draft]' value='" + isDraftSymbol_N_Price + "' required/><input type='hidden' name='products[" + editRowNum + "][opid]' value='" + orderProductId + "' required><input type='hidden' name='products[" + editRowNum + "][product_id]' value='" + product_id + "' required/><input type='hidden' name='products[" + editRowNum + "][description]' value='" + productObject.description + "' required/>" + product_name;
                                            if (is_sub_product != undefined && is_sub_product == 1 && is_sub_product_value != '') {
                                                tdElement.innerHTML += " (" + is_sub_product_value + ")";
                                                tdElement.innerHTML += "<input type='hidden' name='products[" + editRowNum + "][is_sub_product]' value='" + is_sub_product + "' required/>";
                                                tdElement.innerHTML += "<input type='hidden' name='products[" + editRowNum + "][is_sub_product_value]' value='" + is_sub_product_value + "' required/>";
                                            }
                                            if (productObject.description != '') {
                                                tdElement.innerHTML += "<p style='margin-top:0px; margin-bottom:0px;'>(" + productObject.description + ")</p>";
                                            }
                                        } else {

                                            var glassProductSizeList = '';
                                            for (const [key, product] of Object.entries(glassProduct.products)) {

                                                var productMeasurement = '';

                                                if (productCategory == '2') {
                                                    if (product.height != 0 && product.width != 0) {
                                                        productMeasurement += product.height + " inch × " + product.width + " inch - " + product.qty + " pc";
                                                    } else if (product.width != 0 && product.length != 0) {
                                                        productMeasurement += product.width + " inch × " + product.length + " inch - " + product.qty + " pc";
                                                    } else if (product.height != 0 && product.length != 0) {
                                                        productMeasurement += product.height + " inch × " + product.length + " inch - " + product.qty + " pc";
                                                    } else if (product.height != 0 && product.width != 0 && product.length != 0) {
                                                        productMeasurement += product.height + " inch × " + product.width + " inch ×" + product.length + " inch - " + product.qty + " pc";
                                                    }
                                                } else if (productCategory == '3' && product.height != 0) {
                                                    productMeasurement += product.height + " " + product.extra_option_hint + " - " + product.qty + " pc";
                                                } else if (productCategory == '4' && product.height != 0 && product.width != 0) {
                                                    productMeasurement += product.height + " " + product.extra_option_hint + " × " + product.width + " " + product.extra_option_hint + " - " + product.qty + " pc";
                                                }

                                                glassProductSizeList += "<p style='margin-bottom: 0px;'>" + productMeasurement + "</p>";
                                            }

                                            tdElement.innerHTML = "<input type='hidden' name='products[" + editRowNum + "][is_draft]' value='" + isDraftSymbol_N_Price + "' required/><input type='hidden' name='products[" + editRowNum + "][opid]' value='" + orderProductId + "' required><input type='hidden' name='products[" + editRowNum + "][product_id]' value='" + product_id + "' required/><input type='hidden' name='products[" + editRowNum + "][description]' value='" + productObject.description + "' required/><input type='hidden' name='products[" + editRowNum + "][other_details]' value='" + JSON.stringify(glassProduct.products) + "' required/>" + product_name + glassProductSizeList;

                                            if (productObject.description != '') {
                                                tdElement.innerHTML += "<p style='margin-top:0px; margin-bottom:0px;'>(" + productObject.description + ")</p>";
                                            }
                                        }
                                    }

                                    if (index == 2) {
                                        var matrialElement = '';
                                        if (productCategory != '2') {

                                            if (height != undefined && height != 0) {
                                                matrialElement += "<input type='hidden' name='products[" + editRowNum + "][height]' value='" + parseFloat(height).toFixed(2) + "' required/>  " + parseFloat(height).toFixed(2).replace(/\.0+$/, '') + extra_option_hint_value;
                                            }

                                            if (height != undefined && height != 0 && width != undefined && width != 0) {
                                                matrialElement += " x ";
                                            } else if (height != undefined && height != 0 && length != undefined && length != 0) {
                                                matrialElement += " x ";
                                            }

                                            if (width != undefined && width != 0) {
                                                matrialElement += "<input type='hidden' name='products[" + editRowNum + "][width]' value='" + parseFloat(width).toFixed(2) + "' required/>  " + parseFloat(width).toFixed(2).replace(/\.0+$/, '') + extra_option_hint_value;
                                            }

                                            if (width != undefined && width != 0 && length != undefined && length != 0) {
                                                matrialElement += " x ";
                                            } else if (height != undefined && height != 0 && length != undefined && length != 0) {
                                                matrialElement += " x ";
                                            }

                                            if (length != undefined && length != 0) {
                                                matrialElement += "<input type='hidden' name='products[" + editRowNum + "][length]' value='" + parseFloat(length).toFixed(2) + "' required/>  " + parseFloat(length).toFixed(2).replace(/\.0+$/, '') + extra_option_hint_value;
                                            }

                                            if (matrialElement != '') {
                                                matrialElement += " - ";
                                            }
                                        }

                                        tdElement.innerHTML = matrialElement + "<input type='hidden' name='products[" + editRowNum + "][quantity]' value='" + parseFloat(quantity).toFixed(2) + "' required/>" + parseFloat(quantity).toFixed(2).replace(/\.0+$/, '') + " " + unit;

                                    }

                                    if (index == 3) {
                                        tdElement.innerHTML = "<input type='hidden' name='products[" + editRowNum + "][price]' value='" + price + "' required/>" + parseFloat(price).toFixed(2).replace(/\.0+$/, '');
                                    }

                                    if (index == 4) {
                                        tdElement.innerHTML = "<input type='hidden' name='products[" + editRowNum + "][total_price]' value='" + total_price + "' required/>" + total_price;
                                    }

                                    if (index == 5) {
                                        var customerId = $('select.customers').val();
                                        tdElement.innerHTML = '<button type="button" id="delete_row_[' + editRowNum + ']" class="deleteRow pull-right btn btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></button>' +

                                            '<button type="button" id="edit_row_[' + editRowNum + ']" class="editRow pull-right btn btn-primary" data-order-product="' + orderProductId + '" data-customer="' + customerId + '" data-product="' + product_id + '" data-row-index="' + editRowNum + '"><i class="fa fa-edit" aria-hidden="true"></i></button>';
                                    }

                                });

                            }

                            calculateGrandTotal();

                            products_table.find('#product_unit').val('');
                            // $(".is_sub_product_select").select2().val('').trigger('change');
                            // if($("#addNewSubProduct").find("i").hasClass("fa-remove")){
                            // 	$("#addNewSubProduct").find("i").toggleClass("fa-plus fa-remove");
                            // }
                            // $('.sub_product_div').remove();
                            // $('.sub_product').remove();

                            // $(".price").val('0');
                            // $("#product_quantity").val('0');
                            // $('#product_amount').val('0');
                            // $('.update_price').val('0');

                            // $(".price").parent().parent().parent().removeClass('col-lg-1');
                            // $(".price").parent().parent().parent().addClass('col-lg-3');

                            // products_table.find('.pro_height').parent().remove();
                            // products_table.find('.pro_width').parent().remove();
                            // products_table.find('.pro_length').parent().remove();

                            $('#products_table').html(productDetailHtml);

                            //Empty product object
                            glassProduct.products = {};
                            glassProduct.totalQty = 0;
                            productObject.description = '';
                        } else {
                            $('#price_error').removeClass('d-none');
                            $('#price_error').text('Price should not be zero.');
                        }
                    } else {
                        // $('<p id="quantity_error" style="color:red; font-size:12px;">Please enter quantity.</p>').insertAfter($('.quantity'));
                        $('#quantity_error').removeClass('d-none');
                        $('#quantity_error').text('Please enter quantity.');
                        return false;
                    }
                }
            }
        });

        $(document).on('change', '.is_sub_product_select', function(e) {
            e.preventDefault();
            var price = $('#products_table').find('.price').attr('data-price');
            if ($(this).val() != '') {
                var price = $(this).find('option:selected').attr('data-price');
                $('#products_table').find('.price').val(price);
            } else {
                $('#products_table').find('.price').val(price);
            }
            calculateProducts();
        });

        $(document).on('click', '#addNewSubProduct', function(e) {
            $('.is_sub_product_text').toggle('0');
            $('.is_sub_product_select').toggle('0');
            icon = $(this).find("i");
            icon.toggleClass("fa-plus fa-remove");

            if (icon.hasClass("fa-plus") && $(".is_sub_product_select").find('option:selected').val() != '') {
                var price = $(".is_sub_product_select").find('option:selected').attr('data-price');
                $('#products_table').find('.price').val(price);
            } else {
                var price = $('#products_table').find('.price').attr('data-price');
                $('#products_table').find('.price').val(price);
            }
            calculateProducts();
        });

        $(document).on('click', '#addDesBtn', function(e) {
            $('select.products').siblings('p#products_error').remove();
            $('select.products').siblings('.jquery-validation-error').remove();

            var products_table = $('#products_table');

            var product_id = $(".products option:selected").val();
            var product_name = $(".products option:selected").attr('data-name');
            if (product_id == '') {
                $('<p id="products_error" style="color:red; font-size:12px;">Please select product.</p>').insertAfter($('select.products').siblings('.select2-container'));
            } else {
                Swal.fire({
                    title: 'Add Description',
                    input: 'textarea',
                    inputLabel: 'Description',
                    inputValue: productObject.description,
                    inputAttributes: {
                        name: 'description',
                        autocomplete: 'off',
                    },
                    confirmButtonText: 'Save',
                    showCancelButton: true,
                    inputValidator: (value) => {
                        if (!(value.length <= 100)) {
                            return 'The description should be 100 maximum character!'
                        }
                        if (!value) {
                            return 'The description is required!'
                        }
                    }
                }).then(function(res) {
                    if (res.isConfirmed) {
                        // console.log(res.value);
                        productObject.description = res.value;
                    }
                });

                // console.log(productObject.description);
            }
        });

        $(document).on('input', '.swal2-textarea', function() {
            var str = $(this).val();
            if (!(str.length <= 100)) {
                $(this).addClass('swal2-inputerror');
                $(this).siblings('div.swal2-validation-message').css('display', 'flex');
                $(this).siblings('div.swal2-validation-message').html("The description should be 100 maximum character!");
            } else {
                $(this).removeClass('swal2-inputerror');
                $(this).siblings('div.swal2-validation-message').css('display', 'none');
                $(this).siblings('div.swal2-validation-message').html("");
            }
        });

        $(document).on('change', '#isAddShipping', function(e) {
            if ($(this).is(':checked')) {
                var shippingTrHtml = '<tr>' +
                    '<th colspan="4" class="text-right">Shipping Amount</th>' +
                    '<td colspan="2">' +
                    '<input type="number" name= "shipping_amount" value="{{$order->shipping_amount ?? number_format(config("constant.shipping_amount"),2) }}" class="form-control shipping_amount" min="0" max="999999" onkeydown="javascript: return ["Backspace","Delete","ArrowLeft","ArrowRight","Tab","Period","NumpadDecimal"].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=="Space" && this.value.length <= 10"  required/>' +
                    '</td>' +
                    '</tr>';
                $(shippingTrHtml).insertBefore($('#order_products_table').children('tfoot').children('tr:nth-child(1)'));

            } else {
                $('#order_products_table').children('tfoot').children('tr:nth-child(1)').remove();
            }

            calculateGrandTotal();
        });

        $(document).on('input', '.shipping_amount', function(e) {
            calculateGrandTotal();
        });

        var deleted_proids = [];

        $(document).on('click', '.deleteRow', function(e) {
            var orderTable = document.querySelectorAll('#order_products_table tbody tr');
            var oproid = $(this).data('oproid');
            if (oproid) {
                deleted_proids.push(oproid);
                $('input[name="deleted_opids"]').val(deleted_proids.join(','));
            }
            if (orderTable.length == 1) {
                $('.table-for-data').html('<tr id="tmptr"><td colspan="6"></td></tr>');
            }
            $(this).closest('tr').remove();
            calculateGrandTotal();
        });

        $(document).on('click', 'button[type=submit]', function(e) {
            $('input[name=submit]').val($(this).attr('value'));
        });


        $(document).on('click', '.order_form_submit', function(e) {
            e.preventDefault();

            // var customerCreditAmount = parseFloat($(".customers option:selected").attr('data-credit'));
            // var customerDebitAmount  = parseFloat($(".customers option:selected").attr('data-debit'));
            // var customerCreditLimit  = parseFloat($(".customers option:selected").attr('data-limit'));

            // var grandTotal = parseFloat($('#grandTotalSpan').text());
            // var remmainCreditLimit = customerCreditAmount - customerDebitAmount;
            // // var remmainCreditLimit = customerCreditLimit - customerDebitAmount;

            // if(!(customerCreditLimit > remmainCreditLimit && customerCreditLimit > grandTotal) && customerCreditLimit != 0){
            //     // Toast.fire({
            //     // 	icon: 'error',
            //     // 	title: 'Note:Credit limit has been exceeds!',
            //     // });

            //     $('#credit-limit-alert').text('Note:Credit limit has been exceeds!');

            // }else{
            //     $('#credit-limit-alert').text('');
            // }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': "{!! csrf_token() !!}"
                }
            });
            $.ajax({
                type: $("#productForm").attr('method'),
                url: $("#productForm").attr('action'),
                data: $("#productForm").serialize(),
                dataType: 'json',
                success: function(response, status, xhr) {
                    // console.log(response);
                    if (response.success) {
                        Swal.fire(
                            'Success!',
                            response.message,
                            'success'
                        ).then(function() {

                            // window.open(response.printPdfUrl, '_blank')

                            window.location.replace(response.redirectUrl);
                        });
                    }
                },
                error: function(response) {
                    // console.log(response);
                    var errorArray = response.responseJSON.errors;
                    $.each(errorArray, function(index, item) {
                        // console.log(index, item);
                        $('.error_' + index).html(item);
                        if (index == 'products') {
                            $('.error_product_id').html('Add at least 1 product');
                        }
                        // Swal.fire('Error!', item[0], 'error');
                    });
                }
            });
        });

        $(document).on('input', '#product_quantity,.update_price,.price', function(e) {

            if ($(this).hasClass('price')) {
                var priceVal = $(this).val();


                if (priceVal != 'n') {
                    var MinSalePrice = parseFloat($('.min-sale-price').text());
                    var inputPrice = !isNaN(parseFloat(priceVal)) ? parseFloat(priceVal) : 0;

                    if (!(MinSalePrice <= inputPrice) && inputPrice != 0) {
                        $('#price_error').removeClass('d-none');
                        $('#price_error').text('Price should be greater or equal to min sale price.');
                    } else {
                        $('#price_error').addClass('d-none');
                        $('#price_error').text('');
                    }

                    $(this).attr('maxlength', 20);
                } else {
                    $('#price_error').addClass('d-none');
                    $('#price_error').text('');
                    $(this).attr('maxlength', 1);
                }

            }

            calculateProducts();

        });

        $(document).on('click', '.update_price,.price,.glass-input,.shipping_amount', function(e) {
            var value = parseInt($(this).val());
            if (value == 0) {
                $(this).val('');
            }
        });

        $(document).on('focusout', '.update_price, .price ,.shipping_amount,.glass-input', function(e) {
            if ($(this).val() == '') {
                $(this).val('0');
            }
        });

        //Start to edit product row
        $(document).on('click', '.editRow', function(e) {
            e.preventDefault();

            var customer_id = $(".customers option:selected").val();
            var product_id = $(this).attr('data-product');
            var opid = $(this).attr('data-order-product');
            var dataRowIndex = $(this).attr('data-row-index');
            var productCategory = $(this).attr('data-type');

            $('#product_id_error').hide();
            $('#products_error').hide();
            $('select.customers').siblings('#customer_error').remove();
            $('select.products').siblings('#products_error').remove();
            $('select.customers').siblings('.jquery-validation-error').remove();
            $('select.products').siblings('.jquery-validation-error').remove();

            if (customer_id == '') {
                // $('#customer_error').show();
                $('<p id="customer_error" style="color:red; font-size:12px;">Please select customer.</p>').insertAfter($('select.customers').siblings('.select2-container'));
                $(this).select2('destroy').find('option:selected').prop('selected', false).end().select2()
            }

            if (product_id != '' && customer_id != '') {
                productDetail['product'] = product_id;

                // $('.estimateInvoice').show();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.orders.editProduct') }}",
                    data: {
                        product_id: product_id,
                        customer_id: customer_id,
                        opid: opid,
                        dataRowIndex: dataRowIndex,
                    },
                    success: function(response) {
                        if (response.status) {
                            // console.log(response);
                            $("select.products").select2('destroy').find('option[value=' + product_id + ']').prop('selected', 'selected').end().select2();
                            $('.product_information-block').show();
                            $('.product-detail').html(response.data);
                            var products_table = $('#products_table');
                            var minPrePrice = 0;
                            if (response.rowData.last_order_price != 0) {
                                minPrePrice = response.rowData.last_order_price;
                                $('.min-pre-price').parent('#prevOrderLink').css('display', 'block');
                                $('.min-pre-price').parent('#prevOrderLink').attr('data-order', response.rowData.order);
                                $('.min-pre-price').text(minPrePrice);
                            } else {

                                if (response.rowData.customer_type == 'retailer') {
                                    minPrePrice = response.rowData.retailer_price;
                                } else if (response.rowData.customer_type == 'wholesaler') {
                                    minPrePrice = response.rowData.wholesaler_price;
                                } else {
                                    minPrePrice = response.rowData.price;
                                }

                                $('.min-pre-price').html('');
                                $('.min-pre-price').parent('#prevOrderLink').css('display', 'none');
                            }

                            // products_table.find('.price').val(response.rowData.price);
                            // products_table.find('.sub_total').text(response.rowData.sub_total);						
                            // Start to store value in object
                            productObject.description = response.rowData.product_description;

                            if (productCategory == '2' || productCategory == '3' || productCategory == '4') {
                                glassProduct.products = response.rowData.other_details != '' && JSON.parse(response.rowData.other_details);
                                glassProduct.totalQty = response.rowData.totalQty;
                                addGlassProductViewRender(customer_id, product_id, 'html', response.rowData.other_details);
                            }

                            $('.whole-sale-price').text(response.rowData.WSP);
                            $('.price-name').text(response.rowData.priceName);

                            // End to store value in object						

                            $('.purchase-price').text(response.rowData.purchase_price);
                            $('.min-sale-price').text(response.rowData.min_sale_price);
                            $('.addRow').data('product_name', response.rowData.product_name);
                            $('.addRow').html('<i class="fa fa-pencil"></i>');
                            $("select.products").focus();
                            $('.editRow').focusout();
                            calculateProducts();
                        }
                    },
                    error: function(reject) {
                        if (reject.status === 400) {
                            var rejectResponse = $.parseJSON(reject.responseText);
                            var error = rejectResponse.errors;
                            $.each(error, function(key, val) {
                                // console.log("#" + key + "_error = "+val[0])
                                $("#" + key + "_error").text(val[0]);
                            });
                        }
                    }
                });
            } else {
                productDetail['product'] = '';

                $('.product_information-block').hide();
                $('.sub_total').text('0');
                $('.price').val('0');
                $('.unit').text('');
                $('#product_id_error').show();
            }
        });
        //End to edit product row
    });

    //To get next closest number from given array
    function getClosestValue(targetVal) {
        const range = (start, stop, step) => Array.from({
            length: (stop - start) / step
        }, (_, i) => start + i * step);
        // console.log(range(6,506,6));
        var glass_range = parseInt("{{config('constant.glass_range')}}");
        var standardArray = range(6, glass_range, 6);

        standardArray = standardArray.sort(function(a, b) {
            return a - b
        });
        if (!(standardArray) || standardArray.length == 0) {
            return null;
        }

        if (standardArray.length == 1) {
            return standardArray[0];
        }

        for (var i = 0; i < standardArray.length - 1; i++) {
            if (standardArray[i] >= targetVal) {
                var curr = standardArray[i];
                var next = standardArray[i + 1]
                return Math.abs(curr - targetVal) < Math.abs(next - targetVal) ? curr : next;
            }
        }

        return standardArray[standardArray.length - 1];
    }

    //To calculate sqft
    function calculateSqft(measurementValue) {
        const range = (start, stop, step) => Array.from({
            length: (stop - start) / step
        }, (_, i) => start + i * step);

        var glass_range = parseInt("{{config('constant.glass_range')}}");
        var glassArr = range(6, glass_range, 6);
        var sqft = parseFloat(parseFloat(measurementValue) / 144);
        return sqft;
    }

    function numberWithCommas(x) {
        x = x.toString();
        var pattern = /(-?\d+)(\d{3})/;
        while (pattern.test(x))
            x = x.replace(pattern, "$1,$2");
        return x;
    }

    async function calculateProducts() {

        var products_table = $('#products_table');

        var productCategory = $('select.products').find('option:selected').attr('data-type');

        var unit = products_table.find('#product_unit').val() != undefined ? products_table.find('#product_unit').val() : '';

        var quantity = products_table.find('#product_quantity').val() != undefined && products_table.find('#product_quantity').val() != '' ? products_table.find('#product_quantity').val() : 0;

        var height = products_table.find('.pro_height').val() != undefined && products_table.find('.pro_height').val() != '' ? products_table.find('.pro_height').val() : 0;

        var width = products_table.find('.pro_width').val() != undefined && products_table.find('.pro_width').val() != '' ? products_table.find('.pro_width').val() : 0;

        var length = products_table.find('.pro_length').val() != undefined && products_table.find('.pro_length').val() != '' ? products_table.find('.pro_length').val() : 0;

        var price = parseFloat(products_table.find('.price').val());
        var sub_total = parseFloat(products_table.find('#product_amount').val());


        switch (productCategory) {
            case '1':
                simpleCalculation(products_table, height, width, length, quantity, price, sub_total);
                break;
            case '2':
                GlassCalculation(products_table, quantity, price, sub_total);
                break;
            case '3':
                GlassCalculation(products_table, quantity, price, sub_total);
                break;
            case '4':
                GlassCalculation(products_table, quantity, price, sub_total);
                break;
            default:
                console.log('Invalid Category!');
        }

    }

    async function simpleCalculation(products_table_block, height, width, length, quantity, price, sub_total) {
        var matrialValue = 1;
        if (height != 0) {
            matrialValue *= height;
        }

        if (width != 0) {
            matrialValue *= width;
        }

        if (length != 0) {
            matrialValue *= length;
        }


        var totalMatirial = parseFloat(matrialValue) * parseFloat(quantity);
        price = isNaN(price) ? 0.00 : price;
        sub_total = totalMatirial * price;

        // console.log('price',price);
        // console.log('sub_total',sub_total);

        if (!isNaN(sub_total)) {
            // products_table_block.find('.sub_total').text(sub_total.toFixed(2));
            products_table_block.find('#product_amount').val(sub_total.toFixed(2));

            // calculateGrandTotal();
        }
    }

    async function GlassCalculation(products_table_block, quantity, price, sub_total) {

        var subTotal = parseFloat(quantity) * parseFloat(price);
        if (!isNaN(subTotal)) {
            // products_table_block.find('.sub_total').text(subTotal.toFixed(2));
            products_table_block.find('#product_amount').val(subTotal.toFixed(2));
        }
    }

    //Render glass product item 
    function addGlassProductViewRender(customer_id, product_id, renderType = 'html', otherDetails = null) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            url: "{{ route('admin.addGlassProductView') }}",
            data: {
                customer_id: customer_id,
                product_id: product_id,
                otherDetails: otherDetails,
            },
            success: function(response) {
                if (response.status) {
                    // console.log(response);
                    if (renderType == 'html') {
                        $('.glass-product-item').html(response.html);
                    } else if (renderType == 'append') {
                        $('.glass-product-item').append(response.html);
                    }
                }
            },
            error: function(reject) {
                if (reject.status === 400) {
                    var rejectResponse = $.parseJSON(reject.responseText);
                    var error = rejectResponse.errors;
                    $.each(error, function(key, val) {
                        // console.log("#" + key + "_error = "+val[0])
                        $("#" + key + "_error").text(val[0]);
                    });
                }
            }
        });
    }

    async function calculateGrandTotal() {

        var customerCreditAmount = parseFloat($(".customers option:selected").attr('data-credit'));
        var customerDebitAmount = parseFloat($(".customers option:selected").attr('data-debit'));
        var customerCreditLimit = parseFloat($(".customers option:selected").attr('data-limit'));

        var remmainCreditLimit = customerCreditAmount - customerDebitAmount;
        // var remmainCreditLimit = customerCreditLimit - customerDebitAmount;

        var orderTable = document.querySelectorAll('#order_products_table tbody tr');
        $('.addRow').attr('data-row-count', orderTable.length);
        var serialNo = 1;
        var allPrices = [];
        orderTable.forEach((trElement) => {
            // First check that the element has child nodes
            if (trElement.hasChildNodes()) {
                let tdElement = trElement.getElementsByTagName('td');
                if (tdElement.length > 1) {
                    // trElement.firstChild.textContent  = serialNo;
                    for (var i = 1; i < tdElement.length; i++) {
                        if (i != (tdElement.length - 1)) {
                            let inputElement = tdElement[i].children;
                            if (inputElement) {
                                for (var j = 0; j < inputElement.length; j++) {
                                    if (inputElement[j].tagName.toLowerCase() == 'input') {
                                        var name = inputElement[j].getAttribute('name');
                                        if (name.slice(11) == '[price]') {
                                            allPrices.push(inputElement[j].getAttribute('value'));
                                        }
                                        var setName = name.replace(/\d+/g, serialNo);
                                        inputElement[j].setAttribute('name', setName);
                                    }
                                }
                            }
                        }
                    }
                }
            }
            serialNo++;
        });

        //Start to disable save button
        if (allPrices.includes('0')) {
            $('.order_create').find('button[value=draft]').removeAttr('disabled');
            $('.order_create').find('button[value=save]').attr('disabled', true);
        } else {
            $('.order_create').find('button[value=draft]').attr('disabled', true);
            $('.order_create').find('button[value=save]').removeAttr('disabled');
        }
        //End to disable save button

        var grand_total = 0;
        var totalPriceTd = document.querySelectorAll('.total_price input');
        totalPriceTd.forEach((totalPriceElement) => {
            grand_total += parseFloat(totalPriceElement.value.replace(/\,/g, ''));
        });

        if ($('#isAddShipping').is(':checked')) {
            var shipping_amount = 0.00;
            if ($('.shipping_amount').val() != '') {
                shipping_amount = $('.shipping_amount').val();
            }
            grand_total += parseFloat(shipping_amount.replace(/\,/g, ''));
        }

        if (customerCreditLimit != 0 && !(customerCreditLimit > remmainCreditLimit && customerCreditLimit > grand_total)) {
            // Toast.fire({
            // 	icon: 'error',
            // 	title: 'Note:Credit limit has been exceeds!',
            // });
            // console.log(customerCreditLimit,remmainCreditLimit,grand_total);
            $('#credit-limit-alert').text('Note:Credit limit has been exceeds!');
        } else {
            $('#credit-limit-alert').text('');
        }

        $('#grandTotalSpan').text(numberWithCommas(grand_total.toFixed(0)));
        $('.grandTotalHidden').val(grand_total.toFixed(0));

    }

    function handleCustomerData(customer_id) {
        $('select.customers').siblings('#customer_error').remove();
        $.ajax({
            type: "GET",
            url: "{{ route('admin.customer_detail')}}",
            data: {
                customer_id: customer_id
            },
            success: function(data) {
                var customer_id = $('#customerList').val();
                if (customer_id !== "") {
                    $('.error_customer_id').text('');
                    $('#customer_number').val(data.data.data.phone_number);
                    $('#customer_place').val(data.data.place_name.address);
                    $('#area_id').val(data.data.place_name.id);
                } else {
                    $('.error_customer_id').text('Please select customer.');
                }
            }
        });
    }

    $(document).ready(function(){
        $("#product_list").select2({}).on('select2:open', function() {
            let a = $(this).data('select2');
            if (!$('.select2-product_add').length) {
                a.$results.parents('.select2-results').append('<div class="select2-product_add select_2_add_btn"><button class="btns add_new_product get-customer"><i class="fa fa-plus-circle"></i> Add New</button></div>');
            }
        });
        $(document).on('click','.add_new_product',function(){
            $("#product_list").select2('close');
            $.ajax({
            type: "GET",
            url: "{{ route('admin.get_product_add_form')}}",
            data: {},
            success: function(data) {
                $('#AddnewProductModal').modal('show');
                $(".view_model_form").html(data.html);
            }
            });
        });
    });
</script>
@endsection