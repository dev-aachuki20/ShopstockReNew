<style>
    .invoice-title h2,
    .invoice-title h3 {
        display: inline-block;
    }

    .table>tbody>tr>.no-line {
        border-top: none;
    }

    .table>thead>tr>.no-line {
        border-bottom: none;
    }

    .table>tbody>tr>.thick-line {
        /* border-top: 2px solid; */
        border-top: 2px solid #f5f5f5;
    }

    .pre-order-content .panel-body.cancel-watermark {
        position: relative;
    }

    .pre-order-content .panel-body.cancel-watermark:after {
        content: "";
        background: url("{{ asset('images/cancel-watermark.png') }}") no-repeat center center;
        background-size: 70%;
        opacity: 0.1;
        position: absolute;
        top: 50%;
        left: 50%;
        height: 500px;
        width: 500px;
        transform: translate(-50%, -50%);
        background-repeat: no-repeat;
    }

    .pre-order-content .panel-body.split-watermark {
        position: relative;
    }

    .pre-order-content .panel-body.split-watermark:after {
        content: "";
        background: url("{{ asset('images/split-watermark.png') }}") no-repeat center center;
        background-size: 70%;
        opacity: 0.1;
        position: absolute;
        top: 50%;
        left: 50%;
        height: 500px;
        width: 500px;
        transform: translate(-50%, -50%);
        background-repeat: no-repeat;
    }
    .panel.panel-default{
        display: inline-block;
        width: 100%;
        padding-bottom: 50px;
    }
    .panel-body .table tbody tr:not(:last-child) td{
        border-bottom: 1px solid #dee2e6 !important;
    }
    .panel-body .table tr th:last-child,
    .panel-body .table tr td:last-child{
        text-align: right !important;
    }

    @media(max-width:400px) {
        .pre-order-content .panel-body.cancel-watermark:after {
            background-size: 50%;
        }

        .pre-order-content .panel-body.split-watermark:after {
            background-size: 50%;
        }
    }
</style>
<a href="{{ route('admin.orders.printPdf',encrypt($order->id))}}" id="download-btn" class="btn btn-primary" target="_blank" style="float:right;padding: 6px 30px;">
<i class="fa fa-print"></i> Print
</a>
@php
$isSplit = $order->orderPayTransaction->isNotEmpty() ? $order->orderPayTransaction->first()->is_split : null;
$paytdeleted_at = $order->orderPayTransaction->isNotEmpty() ? $order->orderPayTransaction->first()->deleted_at : null;
@endphp

<div class="panel panel-default">
    <div class="panel-body {{ !is_null($order->deleted_at) ? 'cancel-watermark' : '' }} {{ !is_null($isSplit)&& is_null($order->deleted_at) && !is_null($paytdeleted_at) ? 'split-watermark' : '' }}">
        <div class="row">
            <div class="col-md-12">
                <div class="invoice-title">
                    <h3>@lang('quickadmin.transaction-management.fields.'.$type)</h3>
                </div>
                <div class="row" style="padding-top:20px;">
                    <div class="col-sm-6">
                        <address>
                            <strong>Billed To:</strong><br>
                            {{ $order->customer->name ?? '' }}<br>
                            {{-- {{ $order->customer->phone_number ?? '' }} --}}
                            {{ $order->customer->area->address ?? '' }}
                        </address>
                    </div>
                    <div class="col-sm-6 text-sm-right">
                        <address>
                            <strong> @lang('quickadmin.transaction-management.fields.sales') #:</strong> {{ $order->invoice_number }} <br>
                            @php
                            $orderType = $order->order_type;
                            if($orderType == 'create'){
                            $orderType = 'Estimate';
                            }else if($orderType == 'return'){
                            $orderType = 'Estimate Return';
                            }
                            @endphp
                            <strong> @lang('quickadmin.type'):</strong> {{ $orderType }} <br>
                            <strong> @lang('quickadmin.date'):</strong> {{ date('d-m-Y', strtotime($order->invoice_date)) }} <br>
                        </address>
                    </div>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div style="margin-top: 20px;">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-condensed">
                                <thead>
                                    <tr>
                                        <th>@lang('quickadmin.order.fields.sno')</th>
                                        <th>@lang('quickadmin.order.fields.product_name')</th>
                                        <th>@lang('quickadmin.order.fields.quantity')</th>
                                        <th>@lang('quickadmin.order.fields.price')</th>
                                        <th>@lang('quickadmin.order.fields.sub_total')</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @php
                                    $sno = 0;
                                    @endphp
                                    @foreach($order->orderProduct()->withTrashed()->whereNull('deleted_at')->get() as $item)
                                    <tr>
                                        <td class="text-left">{{ ++$sno }}</td>
                                        <td>
                                            {{ ucfirst($item->product->name) }}
                                            @if(!is_null($item->is_sub_product))
                                            ({{ $item->is_sub_product ?? '' }})
                                            @endif

                                            @if(in_array($item->product->calculation_type, config('constant.product_category_id')) && !is_null($item->other_details))
                                            {!! glassProductMeasurement($item->other_details,'new_line') !!}
                                            @endif

                                            @if(!is_null($item->description))
                                            <p style="margin-top:0px; margin-bottom:0px;">({{ $item->description }})</p>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                            $quantityString = '';
                                            if(!in_array($item->product->calculation_type,config('constant.product_category_id'))){
                                            if(!is_null($item->height)){
                                            $quantityString .= removeTrailingZeros($item->height) .$item->product->extra_option_hint;
                                            }

                                            if(!is_null($item->height) && !is_null($item->width)){
                                            $quantityString .= ' x ';
                                            }else if(!is_null($item->height) && !is_null($item->length)){
                                            $quantityString .= ' x ';
                                            }

                                            if(!is_null($item->width)){
                                            $quantityString .= removeTrailingZeros($item->width) .$item->product->extra_option_hint;
                                            }

                                            if(!is_null($item->length) && !is_null($item->width)){
                                            $quantityString .= ' x ';
                                            }else if(!is_null($item->height) && !is_null($item->length)){
                                            $quantityString .= ' x ';
                                            }

                                            if(!is_null($item->length)){
                                            $quantityString .= removeTrailingZeros($item->length) .$item->product->extra_option_hint;
                                            }

                                            if($quantityString !=''){
                                            $quantityString .= ' - ';
                                            }
                                            }

                                            if(!is_null($item->quantity)){
                                            $quantityString .= removeTrailingZeros($item->quantity).' '.strtoupper($item->product->product_unit->name).' ';
                                            }
                                            @endphp

                                            {{ $quantityString }}
                                        </td>
                                        <td><i class="fa fa-inr" aria-hidden="true"></i> {{ removeTrailingZeros($item->price) }}</td>
                                        <td><i class="fa fa-inr" aria-hidden="true"></i> {{ number_format(round($item->total_price),0) }}</td>
                                    </tr>
                                    @endforeach

                                    @if($order->is_add_shipping)
                                    <tr>
                                        <td class="thick-line text-right" colspan="4"><strong>@lang('quickadmin.order.fields.shipping_amount')</strong></td>
                                        <td class="thick-line text-left" colspan="1"> <span><i class="fa fa-inr" aria-hidden="true"></i> {{ number_format($order->shipping_amount,0) ?? 0}}</span></td>
                                    </tr>
                                    @endif

                                    <tr>
                                        <!-- <td class="thick-line"></td>
                                            <td class="thick-line"></td>
                                            <td class="thick-line"></td> -->
                                        <td class="thick-line text-right" colspan="4"><strong>@lang('quickadmin.order.fields.grand_total')</strong></td>
                                        <td class="thick-line text-left" colspan="1">
                                            <!-- <span class="amount_data"> -->
                                            <span><i class="fa fa-inr" aria-hidden="true"></i> {{ number_format(round($order->total_amount),0) ?? 0}}</span>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div class="row">
                <div class="col-md-6">
                    <address style="padding-top:0px;font-size: 18px;">
                    <strong>Shipped To:</strong>
                    <p>
                        Jane Smith<br>
                        1234 Main<br>
                        Apt. 4B<br>
                        Springfield, ST 54321
                    </p>
                    </address>
                </div>
                <div class="col-md-6">
                    <address style="padding-top:0px;font-size: 18px;text-align:right;">
                        <strong >Company Name</strong>
                        <p>
                            Test Address <br>
                            GST NO: 542542542542<br>
                            Ph: 25365845845<br>
                        </p>
                    </address>
                </div>
            </div> -->
        <div class="row">
            <div class="col-md-12">
                <strong>THANK YOU</strong>
            </div>
        </div>

        @if(!empty($order->remark))
        <div class="row">
            <div class="col-md-12">
                <p class="text-justify">
                    <strong>@lang('quickadmin.transaction.fields.remark')<strong> : {{ $order->remark ?? ''}}
                </p>
            </div>
        </div>
        @endif

        @if(!empty($order->sold_by))
        <div class="row">
            <div class="col-md-12">
                <strong>@lang('quickadmin.order.fields.sold_by')<strong> : {{ $order->sold_by ?? ''}}
            </div>
        </div>
        @endif

    </div>
</div>

<div class="row">
    <div class="col-md-12"><strong> Created By : {{$order->createdBy->name ?? ""}}</strong></div>
</div>

<div class="row">
    <div class="col-md-12"><strong> Created Time : {{date('d-m-Y H:i',strtotime($order->created_at))}}</strong></div>
</div>