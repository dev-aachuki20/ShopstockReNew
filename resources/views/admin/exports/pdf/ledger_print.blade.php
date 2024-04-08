@extends('admin.exports.pdf.layout.pdf')
@section('title', 'Ledger Of '.$customer->name)
@section('styles')
    <style>

              body{
            /* text-align:center; */
            padding-top: 80px;
            word-break: break-word;
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
            font-size:9px;
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
            font-size:12px;
         }
         .my-3{
            margin:2px 0 !important;
         }
         /* header{
            position: fixed;
            left: 0px;
            right: 0px;
            height: 300px;
            margin-top: 0;
            margin-bottom:100px !important;
            padding-bottom: 20px !important;
            z-index: 1000;
        } */

    </style>
@stop
@section('content')
    @php
        $debitTotal = 0;
        $creditTotal = 0;
        $balance = 0;
    @endphp

    <header>
        <table class="table main" style="margin-bottom: 20px;">
            <thead>
                <tr>
                    <td class="text-center" style="font-size: 18px; font-weight:bold;">{{ ucwords($customer->name) }}</td>
                </tr>
                <tr>
                    <td class="sm-font my-3 text-center">Address : {{ $customer->area->address or ''  }}</td>
                </tr>
                <tr>
                    <td class="sm-font text-center">Item Wise Ledger</td>
                </tr>
                <tr>
                    @if(!is_null($from_date) && !is_null($to_date))
                        <td class="sm-font text-center">{{ \Carbon\Carbon::parse($from_date)->format('d F Y') }} to {{ \Carbon\Carbon::parse($to_date)->format('d F Y') }}</td>
                    @endif
                </tr>
            </thead>
        </table>
    </header>

    <main>

        <table class="table table-wrapper heading_wrap">
            <thead>
                <tr>
                    <th colspan="4">Particulars</th>
                    <th style="width:15%">Debit</th>
                    <th style="width:15%">Credit</th>
                    <th style="width:15%">Balance</th>
                </tr>
            </thead>
            <tbody>

            @if($customer->transaction->count() > 0 )
                @php
                    $isOpeningBalance = true;
                    $balance += (float)$openingBalance;
                @endphp

                <tr>
                    <td colspan="4" style="text-align: center; font-size:14px;">
                        <strong> Opening Balance </strong>
                    </td>
                    <td>
                        <span style="font-family: DejaVu Sans, sans-serif;">&#x20B9;</span> {{ number_format(abs($openingBalance),0) }}
                    </td>
                    <td>
                        <strong> </strong>
                    </td>
                    <td>
                        <span style="font-family: DejaVu Sans, sans-serif;">&#x20B9;</span> {{ number_format(abs($balance),0) }}
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
                            <td colspan="4">

                                <table class="table" style="border:0; margin-bottom:0;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                            <td style="border: 0; padding-bottom: 0; font-size: 10px;">
                                                @if(!is_null($transaction->order))
                                                    <strong>Type:</strong> {{-- Only show if transaction order is not null --}}
                                                @endif
                                                @if($isOpeningBalance == true && $transaction->remark == 'Opening balance' && in_array($transaction->payment_way,['by_cash','by_split']))
                                                    {{-- Do nothing --}}
                                                @else
                                                    <span style="padding-right: 30px;">{{ $type }}</span>
                                                    <strong>Vch.No:</strong> {{ $voucherNumber }}
                                                    <span style="padding-right: 8px;"><strong>Date:</strong> {{ $invoiceDate }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                @if(!is_null($transaction->order))
                                    @if($transaction->order->orderProduct()->count() > 0)
                                    <table class="table data_type" style="margin-top: 10px; width:95%;">
                                        <thead>
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
                                                    <td style="font-size:12px;">
                                                        {{ ++$key }}
                                                    </td>
                                                    <td class="HI" style="font-size:12px;">
                                                        {{ ucfirst($item->product->name) }}
                                                        @if(!is_null($item->is_sub_product))
                                                            ({{ $item->is_sub_product ?? '' }})
                                                        @endif

                                                        @if(in_array($item->product->product_category_id, config('constant.product_category_id')) && isset($item->other_details))
                                                        <p style="margin-top:0px; margin-bottom:0px;"> {{ glassProductMeasurement($item->other_details,'one_line') }}</p>
                                                        @endif

                                                        @if(!is_null($item->description))
                                                        <p style="margin-top:0px; margin-bottom:0px;">({{ $item->description }})</p>
                                                        @endif
                                                    </td>
                                                    <td style="font-size:12px;">
                                                        @php
                                                        $quantityString = '';
                                                        if(!in_array($item->product->product_category_id,config('constant.product_category_id'))){
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
                                                    <td style="font-size:12px;">
                                                        {{ removeTrailingZeros($item->price) }}
                                                    </td>
                                                    <td style="font-size:12px;">
                                                        {{ number_format(round($item->total_price),0) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @endif
                                @endif
                            </td>
                            <td style="vertical-align: middle;">
                                @if($transaction->payment_type == 'debit')
                                    @php
                                        $amount = (float)$transaction->amount;
                                        $debitTotal += $amount;
                                        $balance += round($amount);

                                    @endphp
                                    <span style="font-family: DejaVu Sans, sans-serif;">&#x20B9;</span> {{ number_format(round($amount),0) }}
                                @endif

                            </td>
                            <td style="vertical-align: middle;">
                                @if($transaction->payment_type == 'credit')
                                <span style="font-family: DejaVu Sans, sans-serif;">&#x20B9;</span> {{ number_format(round($transaction->amount),0) }}
                                    @php
                                        $creditTotal += (float)$transaction->amount;
                                        $balance -= round((float)$transaction->amount);
                                    @endphp
                                @endif
                            </td>
                            <td style="vertical-align: middle;">
                                <span style="font-family: DejaVu Sans, sans-serif;">&#x20B9;</span> {{ number_format(abs($balance),0) }}
                                {{ ($balance >= 0 ) ? 'Dr' : 'Cr' }}
                            </td>
                        </tr>

                    @endif
                @endforeach
            @endif

            </tbody>
            <tfoot>
                <tr class="text-right">
                    <td colspan="4" style="text-align: right; padding-right: 9px; padding-top:15px; padding-bottom:15px;">
                        <strong>Total</strong>
                    </td>
                    <td>
                        @if(isset($debitTotal) && $debitTotal > 0)
                            <strong> <span style="font-family: DejaVu Sans, sans-serif;">&#x20B9;</span> {{ number_format($debitTotal,0) }}</strong>
                        @endif
                    </td>
                    <td>
                        @if(isset($creditTotal) && $creditTotal > 0)
                            <strong> <span style="font-family: DejaVu Sans, sans-serif;">&#x20B9;</span> {{ number_format($creditTotal,0) }}</strong>
                        @endif
                    </td>
                    <td></td>
                </tr>
                <tr class="text-right">
                    <td colspan="4" style="text-align: right; padding-right: 9px; padding-top:5px; padding-bottom:5px;">
                        <strong> Closing Balance </strong>
                    </td>
                    <td>
                        @php
                            $closingBalance = ((float)$debitTotal+(float)$openingBalance) - (float)$creditTotal;
                        @endphp
                        <strong> <span style="font-family: DejaVu Sans, sans-serif;">&#x20B9;</span> {{ number_format(abs($closingBalance),0) }} </strong>
                    </td>
                    <td>
                        <strong> </strong>
                    </td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </main>

    <footer>
        <table style="padding-left:10px; margin-top:30px;">
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
