
@extends('admin.exports.pdf.layout.pdf')
@section('title', 'Statement of '.$customer->name)
@section('styles')
    <style>
		body{
            text-align:center;
		}

        .table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
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
            border: 0.5px solid #333;
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
        }
        .w-50{
            white-space:normal;
            width:100px;
            min-width:50px;
        }
        .footer_tab ,.footer_tab tr, .footer_tab td{
            padding: 0;
            font-size:10px;
        }
        .footer_tab table{
            padding-top: 0;
            margin-left:auto;
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
        @page {
            margin: 10px 20px;
        }

         .footer_tab table tr td{
            border:1px solid #000;
         }

         .heading_wrap tr td,  .heading_wrap tr th{
            text-align: center;
            border:1px solid #000;
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
    </style>
@stop

@section('content')

    @php
    $debitTotal = 0;
    $creditTotal = 0;
    $openingBalance = $openingBalance ?? 0;
    @endphp

    <div class="main">
        <div class="text-center">
                <p class="font-18 font-800">{{ ucwords($customer->name) }}</p>
                <p class="sm-font my-3">Address : {{ $customer->area->address ?? ''  }}</p>
                <p class="sm-font">Statement</p>
                @if(!is_null($from_date) && !is_null($to_date))
                    <p class="sm-font">{{ \Carbon\Carbon::parse($from_date)->format('d F Y') }} to {{ \Carbon\Carbon::parse($to_date)->format('d F Y') }}</p>
                @endif
        </div>
    </div>

    <table class="table heading_wrap">
        <thead>
            <tr>
                <th>Date</th>
                <th class="w-100">Particulars</th>
                <th>Voucher number</th>
                <th>Debit</th>
                <th>Credit</th>
            </tr>
        </thead>
        <tbody>
                @if (count($customer->transaction) > 0)
                    @foreach ($customer->transaction as $key => $transaction)
                        @if($transaction->remark == 'Opening balance')
                            {{-- @php $openingBalance = (float)$transaction->amount; @endphp --}}
                            @continue
                        @endif

                        <tr>
                            <td>
                                {{ \Carbon\Carbon::parse($transaction->entry_date)->format('d-m-Y') }}
                            </td>

                            <td class="w-100">
                                    {{ $transaction->remark }}
                            </td>

                            <td>
                                {{ $transaction->voucher_number }}
                            </td>

                            <td>
                                @if($transaction->payment_type == 'debit')
                                <span style="font-family: DejaVu Sans, sans-serif;">&#x20B9;</span> {{ number_format($transaction->amount,0) }}
                                    @php
                                        $debitTotal += (float)$transaction->amount;
                                    @endphp
                                @endif
                            </td>
                            <td>
                                @if($transaction->payment_type == 'credit')
                                <span style="font-family: DejaVu Sans, sans-serif;">&#x20B9;</span> {{ number_format($transaction->amount,0) }}

                                    @php
                                        $creditTotal += (float)$transaction->amount;
                                    @endphp
                                @endif
                            </td>

                        </tr>
                    @endforeach
                @else
                <tr>
                    <td colspan="4">@lang('quickadmin.qa_no_entries_in_table')</td>
                </tr>
                @endif

        </tbody>
        <tfoot>
            <tr class="text-right">
                <td colspan="3" class="text-right" style="text-align: right; padding-right: 9px;">
                    <strong>Opening Balance</strong>
                </td>
                <td>
                    <strong> <span style="font-family: DejaVu Sans, sans-serif;">&#x20B9;</span> {{ isset($openingBalance) ? number_format(abs($openingBalance),0) : '0.00' }} </strong>
                </td>
                <td></td>
            </tr>

            <tr class="text-right space-wrap">
                <td colspan="3" class="text-right" style="text-align: right; padding-right: 9px;">
                    <strong>Current Balance</strong>
                </td>
                <td>
                    @if(isset($debitTotal) && $debitTotal > 0)
                        <strong><span style="font-family: DejaVu Sans, sans-serif;">&#x20B9;</span> {{ number_format($debitTotal,0) }}</strong></td>
                    @endif
                <td>
                    @if(isset($creditTotal) && $creditTotal > 0)
                        <strong><span style="font-family: DejaVu Sans, sans-serif;">&#x20B9;</span> {{ number_format($creditTotal,0) }}</strong></td>
                    @endif
            </tr>

            @php
                $closingBalance = $debitTotal - $creditTotal;
                $closingBalance = $closingBalance + $openingBalance;
            @endphp
            <tr class="text-right">
                <td colspan="3" class="text-right" style="text-align: right; padding-right: 9px;">
                    <strong>Closing Balance</strong>
                </td>
                <td>
                    <strong><span style="font-family: DejaVu Sans, sans-serif;">&#x20B9;</span> {{ number_format($closingBalance,0) }}</strong>
                </td>
                <td></td>
            </tr>
        </tfoot>
    </table>

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
