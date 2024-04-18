@extends('layouts.print-view.print-layout')
@section('title')@lang('quickadmin.customer-management.fields.list')@endsection

@section('custom_css')

@endsection

@section('content')
    <div class="page-header">
        <header style="padding: 1px 0; max-width: 100%; margin: 0 auto;">
            <h2 style="margin: 0;color: #2a2a33;font-size: 18px;font-weight: bold; text-align:center;"><strong>@lang('quickadmin.customer-management.fields.list')</strong></h2>
        </header>
    </div>
    <main class="main" style="max-width: 100%;margin: 0 auto;padding: 40px 0;padding-top: 20px;">
        <table cellpadding="0" cellspacing="0" width="100%" style="color: #000;font-size: 15px;">
            <tbody>
                @if(!empty($areaNames))
                <tr>
                    <td colspan="4" style="padding-bottom: 20px:text-align:center"> <strong>Area :</strong>
                        {{ implode(', ', $areaNames) }}
                    </td>
                </tr>
                @endif
                <tr>
                    <th style="padding: 8px;border: 1px solid #000;border-right: none;" align="left">@lang('quickadmin.qa_sn')</th>
                    <th style="padding: 8px;border: 1px solid #000;border-right: none;" align="center">@lang('quickadmin.customers.fields.name')</th>
                    <th style="padding: 8px;border: 1px solid #000;border-right: none;" align="center">@lang('quickadmin.customers.fields.phone_number')</th>
                    <th style="padding: 8px;border: 1px solid #000;border-right: none;" align="center">@lang('quickadmin.transaction.fields.debit_amount')</th>
                    <th style="padding: 8px;border: 1px solid #000;" align="center">@lang('quickadmin.transaction.fields.credit_amount')</th>
                </tr>

                @forelse ($allcustomers as $key => $customer)

                @php
                    $getTotalBlance = getTotalBlance($customer->id,1);
                    $debit_blance = "";
                    if($getTotalBlance < 0){
                        $debit_blance =  number_format(abs($getTotalBlance),2);
                    }

                    $credit_blance = "";
                    if($getTotalBlance > 0){
                        $credit_blance = number_format(abs($getTotalBlance),2);
                    }
                @endphp

                <tr>
                    <td style="padding: 8px;border: 1px solid #000;border-right: none;" align="left">{{ $key + 1 }}</td>
                    <td style="padding: 8px;border: 1px solid #000;border-right: none;" align="center">{{ $customer->name ?? '' }}</td>
                    <td style="padding: 8px;border: 1px solid #000;border-right: none;" align="center">{{ $customer->phone_number ?? ''}}</td>
                    <td style="padding: 8px;border: 1px solid #000;border-right: none;" align="center"><i class="fa fa-inr" aria-hidden="true"></i> {{ $debit_blance ?? '' }}</td>
                    <td style="padding: 8px;border: 1px solid #000;" align="center"><i class="fa fa-inr" aria-hidden="true"></i> {{ $credit_blance ?? ''}}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 8px;border: 1px solid #000;border-top: none;" align="center">No Record Found!</td>
                </tr>
                @endforelse

            </tbody>
        </table>
    </main>
@endsection
