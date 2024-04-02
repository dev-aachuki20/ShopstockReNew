{{--
<div class="row">
    <div class="col-md-12">
        <div class="row" style="padding-top:20px;">
            <div class="col-sm-6">
                <address>
                    <strong>Billed To:</strong><br>
                    {{ $cash_receipt->customer->name ?? '' }}<br>
                    {{ $cash_receipt->customer->area->address ?? '' }}
                </address>
            </div>
            <div class="col-sm-6 text-sm-right">
                <address>
                    <strong> @lang('quickadmin.transaction-management.fields.sales') #:</strong> {{ $cash_receipt->voucher_number ?? '' }} <br>
                    <strong> @lang('quickadmin.order.fields.invoice_date'):</strong> {{ date('d-m-Y',strtotime($cash_receipt->entry_date)) }} <br>
                </address>
            </div>
        </div>
    </div>
</div> --}}

<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered table-striped">
            <tr>
                <th>@lang('quickadmin.order.fields.estimate_date')</th>
                <th>@lang('quickadmin.order.fields.updated_by')</th>
                 {{--<th>@lang('quickadmin.transaction.fields.voucher_number')</th> --}}
                <th>@lang('quickadmin.transaction.fields.particulars')</th>
                <th>@lang('quickadmin.transaction.fields.payment_type')</th>
                <th>@lang('quickadmin.transaction.fields.payment_way')</th>
                <th>@lang('quickadmin.order.fields.updated_at')</th>
                <th>@lang('quickadmin.transaction.fields.amount')</th>
            </tr>
            @foreach ($alltransaction as $transaction)
            <tr>
                <td field-key='entry_date'>{{ date('d-m-Y',strtotime($transaction->entry_date)) }}</td>
                <td>{{ $transaction->updatedBy->name ?? '' }}</td>
                {{-- <td field-key='voucher_number'>{{ $transaction->voucher_number ?? '' }}</td> --}}
                <td field-key='remark'>{{ $transaction->remark ?? '' }}</td>
                <td field-key='payment_type'>{{ ucfirst($transaction->payment_type) ?? ''  }}</td>
                <td field-key='payment_way'>{{ config('constant.paymentModifyWays')[$transaction->payment_way] ?? '' }}</td>
                <td field-key='created_at'>{{ $transaction->created_at->format('d-m-Y')}}
                <br>{{ $transaction->created_at->format('h:i:s A')}}
                </td>
                <td field-key='amount'>{{ number_format($transaction->amount,2) }}</td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
