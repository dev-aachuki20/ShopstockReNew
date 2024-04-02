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


<div class="panel panel-default estimates_trans_view">
    @foreach($allOrderHistory->groupBy('order_update_time') as $orderUpdateTime => $orderHistoryGroup)
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <div class="row" style="padding-top:20px;">
                    <div class="col-sm-6">
                        <address>
                            <strong>By :</strong> {{ $orderHistoryGroup->first()->updatedBy->name ?? '' }}<br>
                        </address>
                    </div>
                    <div class="col-sm-6 text-sm-right">
                        <address>
                            <strong> @lang('quickadmin.date'):</strong> {{ date('d-m-Y h:i:s A', strtotime($orderUpdateTime)) }} <br>
                        </address>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div style="margin-top: 10px;">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-condensed">
                                <thead>
                                    <tr>
                                        <th>@lang('quickadmin.order.fields.sno')</th>
                                        <th>Status</th>
                                        <th>@lang('quickadmin.order.fields.product_name')</th>
                                        <th>@lang('quickadmin.order.fields.quantity')</th>
                                        <th>@lang('quickadmin.order.fields.price')</th>
                                        <th>@lang('quickadmin.order.fields.sub_total')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $grandTotal = 0;
                                    @endphp
                                    @forelse ($orderHistoryGroup as $key => $orderHistory)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ ucfirst($orderHistory->update_status)}}</td>
                                        <td>
                                            {{ ucfirst($orderHistory->product->name) }}
                                            @if(!is_null($orderHistory->is_sub_product))
                                            ({{ $orderHistory->is_sub_product ?? '' }})
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                            $quantityString = '';
                                            if(!in_array($orderHistory->product->calculation_type,config('constant.product_category_id'))){
                                            if(!is_null($orderHistory->height)){
                                            $quantityString .= removeTrailingZeros($orderHistory->height) .$orderHistory->product->extra_option_hint;
                                            }

                                            if(!is_null($orderHistory->height) && !is_null($orderHistory->width)){
                                            $quantityString .= ' x ';
                                            }else if(!is_null($orderHistory->height) && !is_null($orderHistory->length)){
                                            $quantityString .= ' x ';
                                            }

                                            if(!is_null($orderHistory->width)){
                                            $quantityString .= removeTrailingZeros($orderHistory->width) .$orderHistory->product->extra_option_hint;
                                            }

                                            if(!is_null($orderHistory->length) && !is_null($orderHistory->width)){
                                            $quantityString .= ' x ';
                                            }else if(!is_null($orderHistory->height) && !is_null($orderHistory->length)){
                                            $quantityString .= ' x ';
                                            }

                                            if(!is_null($orderHistory->length)){
                                            $quantityString .= removeTrailingZeros($orderHistory->length) .$orderHistory->product->extra_option_hint;
                                            }

                                            if($quantityString !=''){
                                            $quantityString .= ' - ';
                                            }
                                            }

                                            if(!is_null($orderHistory->quantity)){
                                            $quantityString .= removeTrailingZeros($orderHistory->quantity).' '.strtoupper($orderHistory->product->product_unit->name).' ';
                                            }
                                            @endphp

                                            {{ $quantityString }}
                                        </td>
                                        <td><i class="fa fa-inr" aria-hidden="true"></i> {{ removeTrailingZeros($orderHistory->price) }}</td>
                                        <td><i class="fa fa-inr" aria-hidden="true"></i> {{ number_format(round($orderHistory->total_price),0) }}</td>
                                    </tr>
                                        @if($orderHistory->update_status == 'add' || $orderHistory->update_status == 'update')
                                            @php
                                                $grandTotal += $orderHistory->total_price;
                                            @endphp
                                        @endif
                                    @empty
                                    <tr>
                                        <td colspan="4">No Record Found!</td>
                                    </tr>
                                    @endforelse

                                    <tr>
                                        <td class="thick-line text-right" colspan="5"><strong>@lang('quickadmin.order.fields.grand_total')</strong></td>
                                        <td class="thick-line text-left" colspan="1">
                                            <span><i class="fa fa-inr" aria-hidden="true"></i> {{ number_format(round($grandTotal),0) ?? 0}}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endforeach
</div>


