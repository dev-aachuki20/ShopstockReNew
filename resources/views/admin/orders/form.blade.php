<div class="col-md-3">
    <div class="form-group">
        <label>@lang('admin_master.new_estimate.customer_name') <span class="text-danger">*</span></label>
        <div class="input-group">
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
        </div>
        <div class="error_customer_place text-danger error"></div>
    </div>
</div>

<div class="col-md-2">
    <div class="form-group">
        <label>@lang('admin_master.new_estimate.date') <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="date" class="form-control" name="date" value="{{ date('Y-m-d') }}" id="date" autocomplete="true" placeholder="@lang('admin_master.product.product_name_enter')">
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

<div class="col-md-3">
    <div class="form-group">
        <label>@lang('admin_master.product.product_name') <span class="text-danger">*</span></label>
        <div class="input-group">
            {!! Form::select('product_id', $products , $product->calculation_type??'', ['class' => 'form-control select2 product', 'id'=>'productList']) !!}
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
            <label>WSP:<span class="whole-sale-price"> 333</span></label>
            <label><a href="#pre" id="prevOrderLink" data-order="">Pre. Price:<span class="min-pre-price"> 555</span></a></label>
        </p>
    </div>
</div>

<div class="col-md-1">
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
</div>


{{-- table html --}}
    <div class="col-md-12 form-group order_products_table-design product-detail">
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
                    <tr id="tmptr">
                        <td colspan="6"></td>
                    </tr>
                </tbody>

                <tfoot class="order_create mt-5">
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
                            <input class="form-control" placeholder="Enter order remark" name="remark" type="text">
                        </td>
                    </tr>
                    <tr class="sold_by">
                        <th colspan="4" class="total text-right">Sold By</th>
                        <td colspan="2">
                            <input class="form-control" placeholder="Sold By" name="sold_by" type="text">
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="text-left">
            <input type="checkbox" name="is_add_shipping" class="is_add_shipping" id="isAddShipping">
            <label for="isAddShipping">Add Shipping</label>
        </div>
        <div class="text-right order_create">
            <input type="hidden" name="submit" value="">
            <button class="btn btn-info btn-lg w-150" type="submit" name="submit" value="draft" disabled="">Save as Draft</button>
            <button class="btn btn-success btn-lg" type="submit" name="submit" value="save">Save Estimate</button>
        </div>
    </div>
{{-- end table html --}}






















{{-- <div class="col-md-12">
    @if(isset($product))
        <input type="submit" class="btn btn-primary save_btn" value="@lang('admin_master.g_update')">
    @else
        <input type="submit" class="btn btn-primary save_btn" value="@lang('admin_master.g_submit')">
    @endif
</div> --}}


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
          <div class="success_error_message_add_new"></div>
          <button type="button" class="btn btn-primary save_add_new">Save</button>
        </div>
      </div>
    </div>
  </div>
<!-- Add Edit Modal -->


@section('customJS')

<script type="text/javascript">

    var productDetail = {
        // 'customer':'',
        'product':'',
    };

    // get customer data
    $(document).on('change','#customerList', function() {
        var customer_id = $(this).val();
            $.ajax({
            type: "GET",
            url: "{{ route('admin.customer_detail')}}",
            data:{
                customer_id:customer_id
            },
            success: function(data) {
            var customer_id =  $('#customerList').val();
            if(customer_id !== ""){
                    $('.error_customer_id').text('');
                    $('#customer_number').val(data.data.data.phone_number);
                    $('#customer_place').val(data.data.place_name.address);
                }else{
                    $('.error_customer_id').text('Please select customer.');
                }
            }
            });
    });

    $(document).on('change','#productList', function() {
        var product_id = $(this).val();
        var customer_id =  $('#customerList').val();
        $.ajaxSetup({
					headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
				});
        $.ajax({
        type: "POST",
        url: "{{ route('admin.product_detail')}}",
        data:{
            product_id:product_id,
            customer_id:customer_id,
        },
        success: function(data) {
            var customer_id =  $('#customerList').val();
            if(!customer_id){
                $('.error_customer_id').text('Please select customer.');
                $('#productList').val('').trigger('change');
           }else{
                $('.error_customer_id').text('');
                $('.error_product_id').text('');
                $('#product_unit').val(data.data.unit_type);
                $('#product_price').val(data.data.price);
                // $('#get_estimate_price').append(`MSP:${data.data.min_sale_price} WSP:${data.data.wholesaler_price} RSP:${data.data.retailer_price}`);
                if(data.data.customerType == 'retailer'){
                    minPrePrice = data.data.retailer_price;
                }else if(data.data.customerType == 'wholesaler'){
                    minPrePrice = data.data.wholesaler_price;
                }else{
                    minPrePrice = data.data.price;
                }

                $('.min-pre-price').html('');
				$('.min-pre-price').parent('#prevOrderLink').css('display','none');

                $('.purchase-price').text(data.data.purchase_price);
				$('.min-sale-price').text(data.data.min_sale_price);
				$('.whole-sale-price').text(data.data.wholesaler_price);
				$('.retail-sale-price').text(data.data.retailer_price);
                $('.product_information-block').show();
           }
        }
        });


                // $.ajax({
				// 	type:'POST',
				// 	url:"{{ route('admin.add_product_row') }}",
		 		// 	data:{
		 		// 		product_id:product_id,
		 		// 		customer_id:customer_id,
		 		// 	},
				// 	success:function(response){
                //         console.log('response', response);
				// 		productCost['response'] = response;

				// 		if (response.status) {
				// 			$('.product-detail').html(response.data);

				// 			var products_table = $('#products_table');

				// 			var minPrePrice = 0 ;
				// 			if(response.rowData.last_order_price != 0){
				// 				minPrePrice = response.rowData.last_order_price;
				// 				$('.min-pre-price').parent('#prevOrderLink').css('display','block');
				// 				$('.min-pre-price').parent('#prevOrderLink').attr('data-order',response.rowData.order);
				// 				$('.min-pre-price').text(minPrePrice);
				// 			}else{
				// 				if(response.rowData.customer_type == 'retailer'){
				// 					minPrePrice = response.rowData.retailer_price;
				// 				}else if(response.rowData.customer_type == 'wholesaler'){
				// 					minPrePrice = response.rowData.wholesaler_price;
                //                 // alert(minPrePrice);

				// 				}else{
				// 					minPrePrice = response.rowData.price;
				// 				}
				// 				$('.min-pre-price').html('');
				// 				$('.min-pre-price').parent('#prevOrderLink').css('display','none');

				// 			}

				// 		    products_table.find('.price').text(response.rowData.price);
				// 			products_table.find('.sub_total').text(response.rowData.sub_total);
				// 			// console.log(response.rowData);
				// 			// $('.purchase-price').text(response.rowData.purchase_price);
				// 			// $('.min-sale-price').text(response.rowData.min_sale_price);
				// 			// $('.whole-sale-price').text(response.rowData.wholesaler_price);
				// 			// $('.retail-sale-price').text(response.rowData.retailer_price);

				// 			$('.addRow').data('product_name',response.rowData.product_name);

				// 			// $('.product_information-block').show();
				// 			calculateProducts();
				// 		}
				// 	},
                //     error: function (reject) {
                //         if( reject.status === 400 ) {
                //             var rejectResponse = $.parseJSON(reject.responseText);
                //             var error = rejectResponse.errors;
                //             $.each(error, function (key, val) {
				// 				// console.log("#" + key + "_error = "+val[0])
                //                 $("#" + key + "_error").text(val[0]);
                //             });
                //         }
                //     }
		 		// });
    });


    // show product
    // var productCost = {};
        // $(".product").change(function(e){
		// 	e.preventDefault();
		// 	$('#product_id_error').hide();
		// 	$('#products_error').hide();
		// 	$('select.customers').siblings('#customer_error').remove();
		// 	$('select.products').siblings('#products_error').remove();
		// 	$('select.customers').siblings('.jquery-validation-error').remove();
		// 	$('select.products').siblings('.jquery-validation-error').remove();

		// 	customer_id = $(".customers option:selected").val();
		// 	// if( customer_id == '' ){
		// 	// 	// $('#customer_error').show();
		// 	// 	$('<p id="customer_error" style="color:red; font-size:12px;">Please select customer.</p>').insertAfter($('select.customers').siblings('.select2-container'));
		// 	// 	$(this).select2('destroy').find('option:selected').prop('selected', false).end().select2()
		// 	// }
        //     // if( customer_id === '' ){
        //     //     $('.error_customer_id').text('Please select customer.');
        //     //     $('.product').val('').trigger('change');
        //     // }


		// 	var product_id = $(this).select2('val');
		// 	var productCategory = $(this).find('option:selected').attr('data-type');
		// 	var is_sub_product = $(this).find('option:selected').attr('data-isSubProduct');
		// 	// console.log('pid:',product_id);
		// 	if (product_id !='' && customer_id !='') {
		// 		productDetail['product'] = product_id;

		// 		// $('.estimateInvoice').show();
		// 		productCost['product_id'] = product_id;
		// 		productCost['customer_id'] = customer_id;
		// 		productCost['is_sub_product'] = is_sub_product;

		// 		$.ajaxSetup({
		// 			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
		// 		});
		// 		$.ajax({
		// 			type:'POST',
		// 			url:"{{ route('admin.add_product_row') }}",
		// 			data:{
		// 				product_id:product_id,
		// 				customer_id:customer_id,
		// 			},
		// 			success:function(response){
		// 				productCost['response'] = response;

		// 				if (response.status) {
		// 					$('.product-detail').html(response.data);

		// 					var products_table = $('#products_table');

		// 					var minPrePrice = 0;
		// 					if(response.rowData.last_order_price != 0){
		// 						minPrePrice = response.rowData.last_order_price;
		// 						$('.min-pre-price').parent('#prevOrderLink').css('display','block');
		// 						$('.min-pre-price').parent('#prevOrderLink').attr('data-order',response.rowData.order);
		// 						$('.min-pre-price').text(minPrePrice);
		// 					}else{

		// 						if(response.rowData.customer_type == 'retailer'){
		// 							minPrePrice = response.rowData.retailer_price;
		// 						}else if(response.rowData.customer_type == 'wholesaler'){
		// 							minPrePrice = response.rowData.wholesaler_price;
		// 						}else{
		// 							minPrePrice = response.rowData.price;
		// 						}

		// 						$('.min-pre-price').html('');
		// 						$('.min-pre-price').parent('#prevOrderLink').css('display','none');

		// 					}

		// 				    products_table.find('.price').text(response.rowData.price);
		// 					products_table.find('.sub_total').text(response.rowData.sub_total);

		// 					$('.purchase-price').text(response.rowData.purchase_price);
		// 					$('.min-sale-price').text(response.rowData.min_sale_price);

		// 					// $('.min-pre-price').parent('#prevOrderLink').attr('data-order',response.rowData.order);
		// 					// $('.min-pre-price').text(minPrePrice);
		// 					$('.addRow').data('product_name',response.rowData.product_name);

		// 					$('.product_information-block').show();
		// 					calculateProducts();
		// 				}
		// 			},
        //             error: function (reject) {
        //                 if( reject.status === 400 ) {
        //                     var rejectResponse = $.parseJSON(reject.responseText);
        //                     var error = rejectResponse.errors;
        //                     $.each(error, function (key, val) {
		// 						// console.log("#" + key + "_error = "+val[0])
        //                         $("#" + key + "_error").text(val[0]);
        //                     });
        //                 }
        //             }
		// 		});
		// 	}else{
		// 		productDetail['product'] = '';

		// 		$('#glassProductBtn').remove();
		// 		$('.product_information-block').hide();
		// 		$('.quantity').removeAttr('disabled');
		// 		$('.sub_total').text('0');
		// 		$('.price').val('0');
		// 		$('.unit').text('');
		// 		$('#product_id_error').show();
		// 	}
		// 	console.log("productCost",productCost);

		// });




















    // get product data
    // var productCost = {};
    // $(document).on('change','#productList', function() {
    //     var product_id = $(this).val();
    //     var customer_id =  $('#customerList').val();

    //     $.ajax({
    //     type: "GET",
    //     url: "{{ route('admin.product_detail')}}",
    //     data:{
    //         product_id:product_id,
    //         customer_id:customer_id,
    //     },
    //     success: function(data) {
    //         var customer_id =  $('#customerList').val();
    //         if(!customer_id){
    //             $('.error_customer_id').text('Please select customer.');
    //             $('#productList').val('').trigger('change');
    //        }else{
    //             $('.error_customer_id').text('');
    //             $('.error_product_id').text('');
    //             $('#product_unit').val(data.data.unit_type);
    //             $('#product_price').val(data.data.price);
    //             // $('#get_estimate_price').append(`MSP:${data.data.min_sale_price} WSP:${data.data.wholesaler_price} RSP:${data.data.retailer_price}`);
    //             if(data.data.customerType == 'retailer'){
    //                 minPrePrice = data.data.retailer_price;
    //             }else if(data.data.customerType == 'wholesaler'){
    //                 minPrePrice = data.data.wholesaler_price;
    //             }else{
    //                 minPrePrice = data.data.price;
    //             }

    //             $('.min-pre-price').html('');
	// 			$('.min-pre-price').parent('#prevOrderLink').css('display','none');

    //             $('.purchase-price').text(data.data.purchase_price);
	// 			$('.min-sale-price').text(data.data.min_sale_price);
	// 			$('.whole-sale-price').text(data.data.wholesaler_price);
	// 			$('.retail-sale-price').text(data.data.retailer_price);
    //             $('.product_information-block').show();
    //        }
    //     }
    //     });


    //             // $.ajax({
	// 			// 	type:'POST',
	// 			// 	url:"{{ route('admin.add_product_row') }}",
	// 	 		// 	data:{
	// 	 		// 		product_id:product_id,
	// 	 		// 		customer_id:customer_id,
	// 	 		// 	},
	// 			// 	success:function(response){
    //             //         console.log('response', response);
	// 			// 		productCost['response'] = response;

	// 			// 		if (response.status) {
	// 			// 			$('.product-detail').html(response.data);

	// 			// 			var products_table = $('#products_table');

	// 			// 			var minPrePrice = 0 ;
	// 			// 			if(response.rowData.last_order_price != 0){
	// 			// 				minPrePrice = response.rowData.last_order_price;
	// 			// 				$('.min-pre-price').parent('#prevOrderLink').css('display','block');
	// 			// 				$('.min-pre-price').parent('#prevOrderLink').attr('data-order',response.rowData.order);
	// 			// 				$('.min-pre-price').text(minPrePrice);
	// 			// 			}else{
	// 			// 				if(response.rowData.customer_type == 'retailer'){
	// 			// 					minPrePrice = response.rowData.retailer_price;
	// 			// 				}else if(response.rowData.customer_type == 'wholesaler'){
	// 			// 					minPrePrice = response.rowData.wholesaler_price;
    //             //                 // alert(minPrePrice);

	// 			// 				}else{
	// 			// 					minPrePrice = response.rowData.price;
	// 			// 				}
	// 			// 				$('.min-pre-price').html('');
	// 			// 				$('.min-pre-price').parent('#prevOrderLink').css('display','none');

	// 			// 			}

	// 			// 		    products_table.find('.price').text(response.rowData.price);
	// 			// 			products_table.find('.sub_total').text(response.rowData.sub_total);
	// 			// 			// console.log(response.rowData);
	// 			// 			// $('.purchase-price').text(response.rowData.purchase_price);
	// 			// 			// $('.min-sale-price').text(response.rowData.min_sale_price);
	// 			// 			// $('.whole-sale-price').text(response.rowData.wholesaler_price);
	// 			// 			// $('.retail-sale-price').text(response.rowData.retailer_price);

	// 			// 			$('.addRow').data('product_name',response.rowData.product_name);

	// 			// 			// $('.product_information-block').show();
	// 			// 			calculateProducts();
	// 			// 		}
	// 			// 	},
    //             //     error: function (reject) {
    //             //         if( reject.status === 400 ) {
    //             //             var rejectResponse = $.parseJSON(reject.responseText);
    //             //             var error = rejectResponse.errors;
    //             //             $.each(error, function (key, val) {
	// 			// 				// console.log("#" + key + "_error = "+val[0])
    //             //                 $("#" + key + "_error").text(val[0]);
    //             //             });
    //             //         }
    //             //     }
	// 	 		// });
    // });














    // on increase button for add orders in table (show error and save data).
    $(document).on('click','.addRow', function(){
        var customer_id       = $('.customers').val();
        var product_id        = $('.product').val();
        var product_quantity  = $('#product_quantity').val();

        if(customer_id === '' && product_id === ''){
            $('.error_customer_id').text('Please select customer.');
            $('.error_product_id').text('Please select product.');
        }else if(customer_id === ''){
            $('.error_customer_id').text('Please select customer.');
        }else if(product_id === ''){
            $('.error_product_id').text('Please select product.');
        }else if(product_quantity == 0){
            $('.error_product_quantity').text('Please enter quantity.');
        }else{
            $('.error_customer_id').text('');
            $('.error_product_id').text('');
        }


        // add order
        // var product_price = $('.product_price').val();
        // var product_amount = $('.product_amount').val();

                // $.ajax({
				// 	type:'POST',
				// 	url:"{{ route('admin.add_product_row') }}",
		 		// 	data:{
		 		// 		product_id:product_id,
		 		// 		customer_id:customer_id,
		 		// 	},
				// 	success:function(response){
                //         console.log('response', response);
				// 		productCost['response'] = response;

				// 		if (response.status) {
				// 			$('.product-detail').html(response.data);

				// 			var products_table = $('#products_table');

				// 			var minPrePrice = 0 ;
				// 			if(response.rowData.last_order_price != 0){
				// 				minPrePrice = response.rowData.last_order_price;
				// 				$('.min-pre-price').parent('#prevOrderLink').css('display','block');
				// 				$('.min-pre-price').parent('#prevOrderLink').attr('data-order',response.rowData.order);
				// 				$('.min-pre-price').text(minPrePrice);
				// 			}else{
				// 				if(response.rowData.customer_type == 'retailer'){
				// 					minPrePrice = response.rowData.retailer_price;
				// 				}else if(response.rowData.customer_type == 'wholesaler'){
				// 					minPrePrice = response.rowData.wholesaler_price;
                //                 // alert(minPrePrice);

				// 				}else{
				// 					minPrePrice = response.rowData.price;
				// 				}
				// 				$('.min-pre-price').html('');
				// 				$('.min-pre-price').parent('#prevOrderLink').css('display','none');

				// 			}

				// 		    products_table.find('.price').text(response.rowData.price);
				// 			products_table.find('.sub_total').text(response.rowData.sub_total);
				// 			// console.log(response.rowData);
				// 			// $('.purchase-price').text(response.rowData.purchase_price);
				// 			// $('.min-sale-price').text(response.rowData.min_sale_price);
				// 			// $('.whole-sale-price').text(response.rowData.wholesaler_price);
				// 			// $('.retail-sale-price').text(response.rowData.retailer_price);

				// 			$('.addRow').data('product_name',response.rowData.product_name);

				// 			// $('.product_information-block').show();
				// 			calculateProducts();
				// 		}
				// 	},
                //     error: function (reject) {
                //         if( reject.status === 400 ) {
                //             var rejectResponse = $.parseJSON(reject.responseText);
                //             var error = rejectResponse.errors;
                //             $.each(error, function (key, val) {
				// 				// console.log("#" + key + "_error = "+val[0])
                //                 $("#" + key + "_error").text(val[0]);
                //             });
                //         }
                //     }
		 		// });
        // end add order


        var row_count = 1;
        var products_table = $('#products_table');
        console.log(products_table.find('.price').val());
        return false;
        var isDraftSymbol_N_Price = products_table.find('.price').val().toLowerCase() == 'n' ? 1 : 0;

        var unit 	 	= products_table.find('.unit').text();
		var quantity 	= products_table.find('.quantity').val();
		var height  	= products_table.find('.pro_height').val();
		var width   	= products_table.find('.pro_width').val();
		var length  	= products_table.find('.pro_length').val();
        var price 		= products_table.find('.price').val().toLowerCase() == 'n' ? 0 : parseFloat(products_table.find('.price').val()).toFixed(2);
        var total_price = products_table.find('.price').val().toLowerCase() == 'n' ? 0 : parseFloat(products_table.find('.sub_total').text()).toFixed(2);
		var extra_option_hint = products_table.find('.extra_option_hint').val();


        icon = products_table.find('#addNewSubProduct i');
		var is_sub_product_value = '';
		if(icon.hasClass("fa-plus")){
			is_sub_product_value = products_table.find('.is_sub_product_select option:selected').val();
		}else{
			is_sub_product_value = products_table.find('.is_sub_product_text').val();
		}

        var MinSalePrice = parseFloat($('.min-sale-price').text());
		// if(is_sub_product == 1 && is_sub_product_value == ''){
		// 	$('#is_sub_product').removeClass('d-none');
		// 	$('#is_sub_product').text('Please enter sub product.');
		// 	return false;
		// }else{
		// 	$('#is_sub_product').addClass('d-none');
		// 	$('#is_sub_product').text('');
		// }


        if((!(MinSalePrice <= price)) && products_table.find('.price').val().toLowerCase() != 'n' && price != 0){
			$('#price_error').removeClass('d-none');
			$('#price_error').text('Price should be greater or equal to min sale price.');
		}else{
			$('#price_error').addClass('d-none');
			$('#price_error').text('');

			if(product_id != ''){
				if(quantity != 0){
					$('#quantity_error').hide();
					if(products_table.find('.price').val() != 0){
						$('#price_error').addClass('d-none');
						$('#price_error').text('');

						$('.product_information-block').hide();
						$('#tmptr').remove();
						$(".products").select2().val('').trigger('change');


						var markup = "<tr><td>"+row_count+"</td>";

						var matrialElement = '';

						if(productCategory == '2' || productCategory == '3'){
							console.log('proCat:',productCategory);
							var glassProductSizeList = '';
							for (const [key, product] of Object.entries(glassProduct.products)) {
								var extra_option_hint = product.extra_option_hint;
								var productMeasurement = '';

								if(productCategory == '2'){
									if(product.height != 0 && product.width != 0){
										productMeasurement += product.height+" "+extra_option_hint+" × "+product.width+" "+extra_option_hint+" - "+product.qty+" pc";
									}else if(product.width != 0 && product.length != 0){
										productMeasurement += product.width+" "+extra_option_hint+" × "+product.length+" "+extra_option_hint+" - "+product.qty+" pc";
									}else if(product.height != 0 && product.length != 0){
										productMeasurement += product.height+" "+extra_option_hint+" × "+product.length+" "+extra_option_hint+" - "+product.qty+" pc";
									}else if(product.height != 0 && product.width != 0 && product.length != 0){
										productMeasurement += product.height+" "+extra_option_hint+" × "+product.width+" "+extra_option_hint+" ×"+product.length+" "+extra_option_hint+" - "+product.qty+" pc";
									}
								}else if(productCategory == '3' && product.height != 0){
									productMeasurement += product.height+" "+extra_option_hint+" - "+product.qty+" pc";
								}
								glassProductSizeList += "<p style='margin-bottom: 0px;'>"+productMeasurement+"</p>";
							}

							markup += "<td class='desc'>"+
							"<input type='hidden' name='products["+row_count+"][is_draft]' value='"+isDraftSymbol_N_Price+"' required/>"+
							"<input type='hidden' name='products["+row_count+"][product_id]' value='"+product_id+"' required/>"+
							"<input type='hidden' name='products["+row_count+"][description]' value='"+productObject.description+"' required/>"+
							"<input type='hidden' name='products["+row_count+"][other_details]' value='"+JSON.stringify(glassProduct.products)+"' required/>"+product_name+glassProductSizeList;


							if(productObject.description != ''){
								markup += "<p style='margin-top:0px; margin-bottom:0px;'>("+productObject.description+")</p>";
							}

							markup += "</td>";

						}else{
							markup += "<td class='desc'>";
							markup += "<input type='hidden' name='products["+row_count+"][is_draft]' value='"+isDraftSymbol_N_Price+"' required/>";
							markup += "<input type='hidden' name='products["+row_count+"][product_id]' value='"+product_id+"' required/>";
							markup += "<input type='hidden' name='products["+row_count+"][description]' value='"+productObject.description+"' required/>";
							markup += product_name;

							if(is_sub_product != undefined && is_sub_product == 1 && is_sub_product_value != ''){
								markup += "("+is_sub_product_value+")";
								markup += "<input type='hidden' name='products["+row_count+"][is_sub_product]' value='"+is_sub_product+"' required/>";
								markup += "<input type='hidden' name='products["+row_count+"][is_sub_product_value]' value='"+is_sub_product_value+"' required/>";
							}

							if(productObject.description != ''){
								markup += "<p style='margin-top:0px; margin-bottom:0px;'>("+productObject.description+")</p>";
							}

							//8 ft x 4 ft - 1 pc
							markup +="</td>";
							if(height != undefined && height != 0){
								matrialElement += "<input type='hidden' name='products["+row_count+"][height]' value='"+parseFloat(height).toFixed(2)+"' required/>  "+parseFloat(height).toFixed(2).replace(/\.0+$/,'')+ extra_option_hint;
							}

							if(height != undefined && height != 0 && width != undefined && width != 0){
								matrialElement += " x ";
							}else if(height != undefined && height != 0 && length != undefined && length != 0){
								matrialElement += " x ";
							}

							if(width != undefined && width != 0){
								matrialElement += "<input type='hidden' name='products["+row_count+"][width]' value='"+parseFloat(width).toFixed(2)+"' required/>  "+parseFloat(width).toFixed(2).replace(/\.0+$/,'')+ extra_option_hint;
							}

							if(width != undefined && width != 0 && length != undefined && length != 0){
								matrialElement += " x ";
							}else if(height != undefined && height != 0 && length != undefined && length != 0){
								matrialElement += " x ";
							}


							if(length != undefined && length != 0){
								matrialElement += "<input type='hidden' name='products["+row_count+"][length]' value='"+parseFloat(length).toFixed(2)+"' required/>  "+parseFloat(length).toFixed(2).replace(/\.0+$/,'')+ extra_option_hint;
							}

							if(matrialElement != ''){
								matrialElement += " - ";
							}
						}


						markup += "<td class='qty'><input type='hidden' name='products["+row_count+"][quantity]' value='"+parseFloat(quantity).toFixed(2)+"' required/>"+matrialElement+parseFloat(quantity).toFixed(2).replace(/\.0+$/,'')+" "+unit+"</td>";

						markup += "<td><input type='hidden' name='products["+row_count+"][price]' value='"+price+"' required/>"+price.toString().replace(/\.0+$/,'')+"</td><td class='total_price'><input type='hidden' name='products["+row_count+"][total_price]' value='"+total_price+"' required/>"+total_price.toString().replace(/\.0+$/,'')+"</td>"+
						"<td><button type='button' id='delete_row_["+row_count+"]' class='deleteRow pull-right btn btn-danger'><i class='fa fa-trash' aria-hidden='true'></i></button></td>"+
						"</tr>";
						tableBody = $("#order_products_table tbody");
						tableBody.append(markup);
						calculateGrandTotal();

						products_table.find('.unit').text('');
						$(".is_sub_product_select").select2().val('').trigger('change');
						if($("#addNewSubProduct").find("i").hasClass("fa-remove")){
							$("#addNewSubProduct").find("i").toggleClass("fa-plus fa-remove");
						}
						// $('.is_sub_product_text').hide();
						$('.sub_product').remove();

						$(".price").val('0');
						$('.sub_total').text('0');
						$('.update_price').val('0');

						$(".price").parent().parent().parent().removeClass('col-lg-1');
						$(".price").parent().parent().parent().addClass('col-lg-3');

						products_table.find('.pro_height').parent().remove();
						products_table.find('.pro_width').parent().remove();
						products_table.find('.pro_length').parent().remove();

						//Empty product object
						glassProduct.products = {};
						glassProduct.totalQty = 0;
						productObject.description = '';
					}else{
						$('#price_error').removeClass('d-none');
						$('#price_error').text('Price should not be zero.');
					}
				}else{
					$('<p id="quantity_error" style="color:red; font-size:12px;">Please enter quantity.</p>').insertAfter($('.quantity'));
				}
			}
		}

    });



    // calulate total amount based on quantity and price.
    $('#product_quantity').keyup(function() {
        var product_quantity 	= $('#product_quantity').val();
        var product_price       = $("#product_price").val();
        var total = product_quantity * product_price;
        $("#product_amount").val(total);
    });

</script>



@endsection
