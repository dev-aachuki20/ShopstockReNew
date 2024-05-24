@extends('admin.exports.pdf.layout.pdf')
@section('title', 'Ledger Of '.$customer->name)
@section('styles')
    <style>

		body{
            text-align:center;
            padding-top: 0px;
		}

        .table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 5px;
            padding: 2px;
            padding-left:0;
            color: #000;
            padding-right:5px;
        }

        .table th {
            white-space: nowrap;
            color:#000;
            font-size: 11px;
            font-weight: bold;
        }
        .table th p{
            margin: 0px 0px 5px;
            line-height: 12px;
        }
        .table th .font-18{
            font-size: 18px;
        }
        .font-18{
            font-size: 18px;
        }
        .table tfoot tr td {
            color:#000 !important;
        }

        .table td {
            padding: 1px 1px 2px 14px;
            color:#000;
            font-size: 12px;
            vertical-align: middle;
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
        .font-800{
            font-weight: bold;
        }
        .padding-0{
            padding: 0;
        }

        .header-wrap tr th{
            padding-bottom:0;
        }
        .heading_wrap{
            padding:0;
            border:0.5px solid #333;
            border-left:0;
            border-right:0;
        }

        .heading_wrap thead th{
            padding: 8px 2px;
            border-bottom:1px solid #333;
            text-align:left;
        }

        .heading_wrap tbody td{
            font-size:14px;
            font-weight:400;
            color:#000000;
            padding: 8px 2px;
            text-align:left;
            vertical-align: text-top;
        }
        .w-50{
            white-space:normal;
            width:100px;
            min-width:50px;
        }
        .w-100{
            white-space:normal;
            width:200px;
        }
        .footer_tab ,.footer_tab tr, .footer_tab td{
            padding: 0;
            font-size:10px;
        }
        .footer_tab table{
            padding-top: 0;
            width:200px;
            margin-left:auto;
            margin-right:95px;
        }
        .footer_tab table tr{
            padding-top:0;
        }
        .footer_tab table td{
            font-weight:bold;
            font-size:9px;
        }
        .border-top {
            border-top:0.5px solid #000;
        }
        .border-top td, .space-wrap td{
            padding-top:5px;
        }
        .space-wrap td{
            padding-bottom:5px;
        }

        td.w-100 td {
            padding-left: 0 !important;
            padding-bottom:0 !important;
            margin-bottom:0;
            line-height:12px !important;
        }
        td.w-100 table{
            margin-bottom:0;
        }

        @page {
            margin: 10px 20px 10px;
        }
        .heading_wrap tr td,  .heading_wrap tr th{
            text-align: center;
            border:1px solid #000;
         }

         .data_type{
            padding-left:5px;
         }
         .main {
            margin-bottom:1rem;
         }
         .main p{
            margin:0;
         }
         .sm-font{
            font-size:14px;
         }
         .my-3{
            margin:2px 0 !important;
         }
        header{
            position: fixed;
            left: 0px;
            right: 0px;
            height: 200px;
            margin-top: -70px;
            margin-bottom:100px !important;
            padding-bottom: 20px !important;
            z-index: 1000;
        }

    </style>
@stop
@section('content')
    @php
        $debitTotal = 0;
        $creditTotal = 0;
        $balance = 0;
    @endphp

    {{-- <header>
        <div class="main">
            <div class="text-center">
                <p class="font-18 font-800">{{ ucwords($customer->name) }}</p>
                <p class="sm-font my-3">Address : {{ $customer->area->address ?? ''  }}</p>
                <p class="sm-font">Item Wise Ledger</p>
                @if(!is_null($from_date) && !is_null($to_date))
                    <p class="sm-font">{{ \Carbon\Carbon::parse($from_date)->format('d F Y') }} to {{ \Carbon\Carbon::parse($to_date)->format('d F Y') }}</p>
                @endif
            </div>
        </div>
    </header> --}}

    <main>
    <table class="table table-wrapper heading_wrap" style="border:0px;margin-bottom:5px;">
        <thead >
            <tr style="border:0px;margin-top:15px;padding-top:15px;"><td colspan="4" style="border: 0px !important;margin-top:15px;padding-top:15px;"> &nbsp;</td></tr>
            <tr>
                <td colspan="7" style="border:0px;"><p class="font-18 font-800" style="margin: 0 0 3px;">{{ ucwords($customer->name) }}</p><p class="sm-font my-3">Address : {{ $customer->area->address ?? ''  }}</p>
                    <p class="sm-font" style="margin: 0 0 3px;">Item Wise Ledger</p>@if(!is_null($from_date) && !is_null($to_date))
                    <p class="sm-font" style="margin: 0 0 3px;">{{ \Carbon\Carbon::parse($from_date)->format('d F Y') }} to {{ \Carbon\Carbon::parse($to_date)->format('d F Y') }}</p>
                @endif</td>
            </tr>
            <tr>
                <th colspan="4" style="font-size: 12px;">Particulars</th>
                <th style="font-size: 12px;">Debit</th>
                <th style="font-size: 12px;">Credit</th>
                <th style="font-size: 12px;">Balance</th>
            </tr>
        </thead>
        <tbody>
        @if($customer->transaction->count() > 0 )
            @php
                $isOpeningBalance = true;
                $balance += (float)$openingBalance;
            @endphp

            <tr class="text-center">
                <td colspan="4" style="text-align: center; font-size:14px;">
                    <strong> Opening Balance </strong>
                </td>
                <td style="white-space: nowrap">
                    <span style="">&#x20B9;</span> {{ number_format(abs($openingBalance),0) }}
                </td>
                <td style="white-space: nowrap">
                    <strong> </strong>
                </td>
                <td style="white-space: nowrap">
                    <span style="">&#x20B9;</span> {{ number_format(abs($balance),0) }}
                    {{ ($balance >= 0 ) ? 'Dr' : 'Cr' }}
                </td>
            </tr>

                @foreach($customer->transaction as $transaction)
                @if(!is_null($transaction->voucher_number) && $transaction->remark != 'Opening balance')
                    @php
                        $type = 'Cash Reciept';
                        $voucherNumber = $transaction->voucher_number;
                        $invoiceDate = date('d-m-Y',strtotime($transaction->entry_date));
                        if($transaction->payment_way == 'order_create'){
                            $type = 'Sales';
                        }else if($transaction->payment_way == 'order_return'){
                            $type = 'Sales Return';
                        }
                    @endphp
                    <tr>
                        <td colspan="4" style="padding-top: 5px;padding-left:12px;padding-right:12px;">
                            <div class="title text-left" style="padding-left: 5px;">
                                <span style="padding-bottom:5px;display: block;">
                                    @if(!is_null($transaction->order))
                                        <strong style="font-size:12px;">Type:</strong>
                                    @endif

                                    @if($isOpeningBalance == true && $transaction->remark == 'Opening balance' && in_array($transaction->payment_way,['by_cash','by_split']))
                                        {{-- <strong style="display:block; font-size:10px; text-align: right; padding-right: 9px;">Opening Balance</strong> --}}
                                    @else
                                        <span style="padding-right: 8px;font-size:12px;">{{ $type }}</span>
                                        <strong style="font-size:12px;">Vch.No:</strong>
                                        <span style="padding-right: 8px;font-size:12px;">{{ $voucherNumber }}</span>
                                        <strong style="font-size:12px;">Date:</strong>
                                        <span style="padding-right: 8px;font-size:12px;">{{ $invoiceDate }}</span>
                                    @endif
                                </span>

                            </div>
                            @if(!is_null($transaction->order))
                                @if($transaction->order->orderProduct()->count() > 0)
                                <table class="table data_type">
                                    <thead>
                                        <tr><td style="padding: 5px;border:0px;"></td></tr>
                                        <tr>
                                            <td>SNo.</td>
                                            <td>Product Name</td>
                                            <td>Qty</td>
                                            <td>Price</td>
                                            <td>Amount</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($transaction->order->orderProduct()->get() as $key=>$item)
                                        <tr>
                                            <td>
                                                {{ ++$key }}
                                            </td>
                                            <td class="">
                                                {{ ucfirst($item->product->name) }}
                                                @if(!is_null($item->is_sub_product))
                                                    ({{ $item->is_sub_product ?? '' }})
                                                @endif

                                                @if(in_array($item->product->calculation_type, config('constant.product_category_id')) && isset($item->other_details))
                                                <p style="margin-top:0px; margin-bottom:0px;padding:2px;"> {{ glassProductMeasurement($item->other_details,'one_line') }}</p>
                                                @endif

                                                @if(!is_null($item->description))
                                                <p style="margin-top:0px; margin-bottom:0px;">({{ $item->description }})</p>
                                                @endif
                                            </td>
                                            <td style="padding-left:5px;padding-right:5px;">
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
                                                            $quantityString .= removeTrailingZeros($item->quantity).' '.strtoupper($item->product->product_unit->name??'').' ';
                                                        }
                                                    @endphp

                                                {{ $quantityString }}
                                            </td>
                                            <td style="white-space: nowrap">
                                                {{ removeTrailingZeros($item->price) }}
                                            </td>
                                            <td style="white-space: nowrap">
                                                {{ number_format(round($item->total_price),0) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                @endif
                            @endif
                        </td>
                        <td style="white-space: nowrap;">
                            @if($transaction->payment_type == 'debit')
                                @php
                                    $amount = (float)$transaction->amount;
                                    $debitTotal += $amount;
                                    $balance += round($amount);
                                @endphp
                                <span style=""> &#x20B9;</span> {{ number_format(round($amount),0) }}
                            @endif
                        </td>
                        <td style="white-space: nowrap;">
                            @if($transaction->payment_type == 'credit')
                            <span style=""> &#x20B9;</span> {{ number_format(round($transaction->amount),0) }}
                                @php
                                    $creditTotal += (float)$transaction->amount;
                                    $balance -= round((float)$transaction->amount);
                                @endphp
                            @endif
                        </td>
                        <td style="white-space: nowrap;">
                            <span style=""> &#x20B9;</span> {{ number_format(abs($balance),0) }}
                            {{ ($balance >= 0 ) ? 'Dr' : 'Cr' }}
                        </td>
                    </tr>

                @endif
                @endforeach
            @endif

            <tr class="text-right">
                <td colspan="4" style="text-align: right; padding-right: 9px;">
                    <strong>Total</strong>
                </td>
                <td>
                    @if(isset($debitTotal) && $debitTotal > 0)
                        <strong> <span style="">&#x20B9;</span> {{ number_format($debitTotal,0) }}</strong>
                    @endif
                </td>
                <td>
                    @if(isset($creditTotal) && $creditTotal > 0)
                        <strong> <span style="">&#x20B9;</span> {{ number_format($creditTotal,0) }}</strong>
                    @endif
                </td>
                <td></td>
            </tr>
            <tr class="text-right">
                <td colspan="4" style="text-align: right; padding-right: 9px;">
                    <strong> Closing Balance </strong>
                </td>
                <td>
                    @php
                        $closingBalance = ((float)$debitTotal+(float)$openingBalance) - (float)$creditTotal;
                    @endphp
                    <strong> <span style="">&#x20B9;</span> {{ number_format($closingBalance,0) }} </strong>
                </td>
                <td>
                    <strong> </strong>
                </td>
                <td></td>
            </tr>
        </tbody>
        <tfoot>
           <tr style="padding-top:0px;margin-top:0px;border:0px;"><td colspan="4" style="border: 0px !important;"></td></tr>
           <tr style="padding-top:0px;margin-top:0px;border:0px;"><td colspan="4" style="border: 0px !important;"></td></tr>
           <tr style="padding-top:0px;margin-top:0px;border:0px;"><td colspan="4" style="border: 0px !important;"></td></tr>
           <tr style="padding-top:0px;margin-top:0px;border:0px;"><td colspan="4" style="border: 0px !important;"></td></tr>
        </tfoot>
    </table>
    </main>

    <footer>
        <table style="padding-left:8px;">
            <tr>
                <td style="margin: 0px; font-size:12px;" class="font-bold">
                <p>
                    <h4 style="margin:1px 0 0">THANK YOU</h4>
                </p>
                </td>
            </tr>
        </table>

    </footer>
@stop
