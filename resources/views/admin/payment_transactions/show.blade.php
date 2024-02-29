

<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered table-striped">
            <tr>
                <th>@lang('quickadmin.order.fields.invoice_date')</th>
                <td field-key='entry_date'>{{ date('d-m-Y',strtotime($transaction->entry_date)) }}</td>
            </tr>
            <tr>
                <th>@lang('quickadmin.transaction.fields.voucher_number')</th>
                <td field-key='voucher_number'>{{ $transaction->voucher_number ?? '' }}</td>
            </tr>
            <tr>
                <th>@lang('quickadmin.transaction.fields.particulars')</th>
                <td field-key='remark'>{{ $transaction->remark ?? '' }}</td>
            </tr>
            <tr>
                <th>@lang('quickadmin.transaction.fields.payment_type')</th>
                <td field-key='payment_type'>{{ ucfirst($transaction->payment_type)  }}</td>
            </tr>
            <tr>
                <th>@lang('quickadmin.transaction.fields.payment_way')</th>
                <td field-key='payment_way'>{{ config('constant.paymentModifyWays')[$transaction->payment_way] }}</td>
            </tr>
            <tr>
                <th>@lang('quickadmin.qa_created_at')</th>
                <td field-key='created_at'>
                    <p>{{ $transaction->user->name??''}}</p>
                    <small>{{ $transaction->created_at}}</small>
                </td>
            </tr>           
            <tr>
                <th>@lang('quickadmin.transaction.fields.amount')</th>
                <td field-key='amount'><i class="fa fa-inr" aria-hidden="true"> {{ number_format($transaction->amount,2) }}</td>
            </tr>
        </table>
    </div>
</div>