@extends('admin.exports.pdf.layout.pdf')
@section('title', 'Estimate #'.$order->invoice_number)
@section('styles')

    <style>
       .table {
             width: 100%;
             /* width: 70%;  */
             border-collapse: collapse;
             border-spacing: 0;
             margin-bottom: 20px;
             padding: 10px;
             color: #000 !important;
         }

         .table th {
             padding: 10px;
             margin-bottom: 10px;
             border-bottom: 1px solid #dee2e6;
             white-space: nowrap;
             color:#000 !important ;
             /* font-size: 15px; */
             font-size: 12px;
         }


         .table tfoot tr td {
             margin-top: 40px;
             padding: 3px 10px;
             white-space: nowrap;
             color:#000 !important;
         }

         .table td {
             /* padding: 10px;  */
             padding: 1px 1px 2px 14px;
             color:#000 !important ;
             font-size: 14px;
         }

         .table tbody tr:nth-child(2n+2) td {
             background: #F5F5F5;
         }

         .text-center {
             text-align: center;
         }

         .text-left {
             text-align: left;
         }

         .text-right {
             text-align: right;
         }

         .header {
             width: 100%;
         }

         .header tr td address {
             /* padding-top: 50px;  */
             color: #000 !important;
         }


         .invoice-info th {
             /* width: 150px; */
             text-align: right;
         }

         .invoice-info td {
             /* width: 200px; */
             text-align: right;

         }

         .invoice-info th,
         .invoice-info td {
             padding-bottom: 0px;

         }

        .cancel-watermark {
            position: relative;
        }
       .cancel-watermark:after{
            content: "";
            background: url("{{ asset('images/cancel-watermark.png') }}") no-repeat center center;
            background-size: 100%;
            /* background-color: red; */
            opacity: 0.1;
            position: absolute;
            top: 40%;
            left: 50%;
            width: 100%;
            max-width:400px;
            height: 300px;
            margin:0 auto;
            transform: translate(-50%, -50%);
            background-repeat: no-repeat;
        }

        .split-watermark {
            position: relative;
        }
        .split-watermark:after{
            content: "";
            background: url("{{ asset('images/split-watermark.png') }}") no-repeat center center;
            background-size: 100%;
            /* background-color: red; */
            opacity: 0.1;
            position: absolute;
            top: 40%;
            left: 50%;
            width: 100%;
            max-width:400px;
            height: 300px;
            margin:0 auto;
            transform: translate(-50%, -50%);
            background-repeat: no-repeat;
        }

        .table_head td{
            font-size:13px;
        }
        .table_head address{
            font-size:13px;
        }
        .table_head address p{
            margin-bottom:0 !important;
            padding-bottom:0;
            line-height: inherit;
        }
        .table_head address p strong{
            margin-bottom:0 !important;
            padding-bottom:0;
        }
        #ItemTable{
            padding-top:0;
        }

        #ItemTable,#ItemTable tbody  {
            border:1px solid #000 !important;
        }

        #ItemTable tbody td{
            font-size:11px;
            padding-bottom:4px;
            border:1px solid #000;
            /* border-top:none; */
            border-right: 1px solid #000;
        }
        #ItemTable thead th{
            padding-right:0;
            padding-left:13px;
            border:.5px solid #000 !important;
        }
        #ItemTable thead th:first-child{
            text-align:center !important;
        }
        .header{
            padding-bottom:0;
            margin-top: -80px;
        }
        .text-align-center{
            text-align:center !important;
        }
        .title_hd{
            margin-bottom:0 !important;
            padding-bottom:0;
            font-size:16px;
        }
        @page{
        margin-top: 100px; /* create space for header */
        margin-bottom: 70px; /* create space for footer */
        }

        header{
        position: fixed;
        left: 0px;
        right: 0px;
        height: 200px;
        margin-top: -60px;
        margin-bottom:100px !important;
        padding-bottom: 20px !important;
        z-index: 1000;
        }

        footer{
        position: fixed;
        bottom:0px;
        left: 0px;
        right: 0px;
        height: 50px;
        margin-bottom: -10px;
      }

      footer .pagenum:before {
      content: counter(page);
      }
      main{
        margin-top: 10px;
      }
    </style>
@stop
@section('content')
    @php
    $isSplit = $order->orderPayTransaction->isNotEmpty() ? $order->orderPayTransaction->first()->is_split : null;
    $paytdeleted_at = $order->orderPayTransaction->isNotEmpty() ? $order->orderPayTransaction->first()->deleted_at : null;
    @endphp
    <header>
        <table class="header {{ !is_null(@$order->deleted_at) ? 'cancel-watermark' : '' }} {{ !is_null($isSplit)&& is_null($order->deleted_at) && !is_null($paytdeleted_at) ? 'split-watermark' : '' }}">
            <tr>
                <!-- <td style="padding: 40px 0 30px;vertical-align: top;"> -->
                <td style="vertical-align: top;">

                    <table class="table_head" style="width: 100%;">
                        <tr>
                            <td class="text-center">
                                    <h2 class="title_hd">Estimate</h2>
                            </td>
                        </tr>

                       <table style="width: 100%; padding-bottom:10px">
                            <tr>
                                    <td colspan="2">
                                          <table style="width: 100%;">
                                                <tr>
                                                    <td>
                                                        <div style="margin: 0; padding-top:10px" class="font-bold"><strong>Bill To:</strong></div>
                                                    </td>

                                                    <td style="width: 150px; float:right;backgound:red; text-align:right" >
                                                        <div style="margin: 0; padding-top:10px" class="font-bold"><strong>Estimate #:</strong> {{ $order->invoice_number }}</div>
                                                    </td>

                                                </tr>

                                                <tr>
                                                    <td class="font-italic">
                                                            <address>
                                                                {{ $order->customer->name ?? '' }}
                                                                {{-- {{ $order->customer->phone_number ?? '' }} <br> --}}
                                                              <br>  {{ $order->customer->area->address ?? '' }}
                                                            </address>
                                                    </td>

                                                    <td style="vertical-align: top;">
                                                        @php

                                                        $orderType = $order->order_type;
                                                        if($orderType == 'create'){
                                                            $orderType = 'Estimate' ;
                                                        }else if($orderType == 'return'){
                                                            $orderType = 'Estimate Return';
                                                        }
                                                        @endphp
                                                        <address style="text-align: right;vertical-align: top;">
                                                        <strong>Type:</strong>  {{ $orderType }}<br>
                                                        <strong>Date:</strong>{{ date('d-m-Y', strtotime($order->invoice_date)) }}<br>
                                                        </address>
                                                    </td>
                                                </tr>
                                          </table>
                                    </td>
                            </tr>
                       </table>

                    </table>
                </td>


            </tr>
        </table>
    </header>
    <footer>
        <table style="padding-left:8px;">
            @if(!empty($order->remark))
            <tr>
                <td style="margin: 0px; font-size:12px;">
                    <p class="text-justify">
                        <strong>Remark</strong> : {{ $order->remark ?? ''}}
                    </p>
                </td>
            </tr>
            @endif

            @if(!empty($order->sold_by))
            <tr>
                <td style="margin: 0px; font-size:12px;">
                <p>
                    <strong>Sold By</strong> : {{ $order->sold_by ?? ''}}
                </p>
                </td>
            </tr>
            @endif

            <tr>
                <td style="margin: 0px; font-size:12px;" class="font-bold">
                <p>

                </p>
                </td>
            </tr>
        </table>
        <hr>
        <div class="pagenum-container"><small>Page <span class="pagenum"></span></small></div>
    </footer>
    <main>
        <table id="ItemTable" class="table ">
            <thead>
                <tr>
                    <th class="text-center" style="padding-left:5px; padding-right:5px">@lang('quickadmin.order.fields.sno')</th>
                    <th class="text-left">@lang('quickadmin.order.fields.product_name')</th>
                    <th class="text-left">@lang('quickadmin.order.fields.quantity')</th>
                    <th  class="text-center">@lang('quickadmin.order.fields.price')</th>
                    <th class="text-center" style="padding-left:1px">@lang('quickadmin.order.fields.sub_total')</th>
                </tr>

            </thead>

            <tbody>
                @php
                    $sno = 0;
                @endphp
                @foreach($order->orderProduct()->withTrashed()->whereNull('deleted_by')->get() as $item)
                <tr>
                    <td class="text-align-center" style="padding-left:1px; padding-left:5px;">{{ ++$sno }}</td>
                    <td class="HI">
                        {{ ucfirst($item->product->name) }}
                        @if(!is_null($item->is_sub_product))
                            ({{ $item->is_sub_product ?? '' }})
                        @endif

                        @if(in_array($item->product->calculation_type, config('constant.product_category_id')) && isset($item->other_details))
                            <p style="margin-top:0px; margin-bottom:0px;">{!! glassProductMeasurement($item->other_details,'one_line') !!}</p>
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
                                $quantityString .= removeTrailingZeros($item->quantity).' '.strtoupper($item->product->unit_type).' ';
                            }
                        @endphp

                        {{ $quantityString }}
                    </td>
                    <td class="text-center">{{ removeTrailingZeros($item->price) }}</td>
                    <td class="text-center">{{ number_format(round($item->total_price),0) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot style="border-top: 1px solid #000000;">
                @if($order->is_add_shipping)
                    <tr style="border: 1px solid #000000;">
                        <td colspan="3" style="text-align:right; "></td>
                        <td  style="text-align:right;font-size:12px; border: 1px solid #000000;"><b>Shipping Amount</b></td>
                        <td class="text-right" style="padding-right:5px; font-style:normal; font-weight: bold; border: 1px solid #000000;"><span style="font-family: DejaVu Sans, sans-serif;">&#x20B9;</span> {{ number_format($order->shipping_amount,0) ?? 0}}</td>
                    </tr>
                @endif
                <tr style="border: 1px solid #000000;">
                    <td colspan="4"  style="text-align:right;font-size:12px; border: 1px solid #000000;"><b>Grand Total</b></td>
                    <td class="text-align-center" style="padding-right:5px; font-style:normal; font-weight: bold; border: 1px solid #000000;"><span style="font-family: DejaVu Sans, sans-serif;">&#x20B9;</span> {{ number_format(round($order->total_amount),0) ?? 0}}</td>
                </tr>
            </tfoot>
        </table>
        <h6 style="margin-left:6px;">THANK YOU</h6>
    </main>




@stop


