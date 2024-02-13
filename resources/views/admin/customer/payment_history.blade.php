
@php
  $debitTotal = 0;
  $creditTotal = 0;
  $openingBalance = $openingBalance ?? 0;
  $balance = 0;
@endphp
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>@lang('quickadmin.order.fields.invoice_date')</th>
            <th>@lang('quickadmin.transaction.fields.particulars')</th>
            <th>@lang('quickadmin.transaction.fields.voucher_number')</th>
            <th>@lang('quickadmin.transaction.fields.debit_amount')</th>
            <th>@lang('quickadmin.transaction.fields.credit_amount')</th>
            <th>Balance</th>
            <th>@lang('quickadmin.qa_action')</th>
        </tr>
    </thead>
    
    <tbody>
        
        @php               
           $alltransactions=$customer->transaction;       
        @endphp
        
        @if ($alltransactions->count() > 0)
            @foreach ($alltransactions as $key => $transaction)                
                @if($transaction->remark == 'Opening balance')
                    @continue
                @endif                     
                 
                <tr data-entry-id="{{ $transaction->id }}">
                    <td field-key='entry_date'>{{ date('d-m-Y',strtotime($transaction->entry_date)) }}</td>
                    <td field-key='remark'>{{ $transaction->remark or  $transaction->extra_details}}</td>
                    <td field-key='voucher_number'>{{ $transaction->voucher_number ?? '' }}</td>
                    @if($transaction->payment_type == 'credit')
                        <td field-key='amount'><i class="fa fa-inr" aria-hidden="true"></i> {{ number_format($transaction->amount,0) }} </td>
                        @php
                          $debitTotal += (float)$transaction->amount;
                          $balance += round((float)$transaction->amount);
                        @endphp
                    @else
                        <td></td>
                    @endif

                    @if($transaction->payment_type == 'debit')
                        <td field-key='amount'><i class="fa fa-inr" aria-hidden="true"></i>{{ number_format($transaction->amount,0) }}</td>
                        @php
                         $creditTotal += (float)$transaction->amount;
                          $balance -= round((float)$transaction->amount);
                        @endphp
                    @else
                        <td></td>
                    @endif
                    
                    <td>
                        <i class="fa fa-inr" aria-hidden="true"></i> {{ number_format(abs($balance),0) }} {{ ($balance >= 0 ) ? 'Dr' : 'Cr' }}
                    </td>
                    
                    <td>
                        @if(in_array($transaction->payment_way,array('order_create','order_return')))
                         
                            @can('payment_transaction_delete')
                                <button class="btn btn-xs btn-danger" title="This is order transaction" disabled="true">@lang('quickadmin.qa_delete')</button>
                            @endcan
                            @can('payment_transaction_edit1')
                                <button class="btn btn-xs btn-info" title="This is order transaction" disabled="true">@lang('quickadmin.qa_show')</button>
                            @endcan
                            @can('payment_transaction_view1')
                                <a href="{{ route('admin.transactions.show',[$transaction->id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_show')</a>
                            @endcan

                            @can('payment_transaction_view')
                                @if(!is_null($transaction->order_id))
                                    <button data-id="{{$transaction->order_id}}" data-order="{{ encrypt($transaction->order_id) }}" id="prevOrderLink-{{$transaction->order_id}}" class="prevOrderLink btn btn-xs btn-primary">@lang('quickadmin.qa_view')</button>
                                @endif
                            @endcan

                        @else
                            @can('payment_transaction_delete')
                                {!! Form::open(array(
                                    'style' => 'display: inline-block;',
                                    'method' => 'DELETE',
                                    'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                    'route' => ['admin.transactions.destroy', $transaction->id])) !!}
                                    {!! Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                                {!! Form::close() !!}

                            @endcan
                            @can('payment_transaction_view')
                                <a href="{{ route('admin.transactions.show',[$transaction->id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_show')</a>
                            @endcan
                        @endif
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="7">@lang('quickadmin.qa_no_entries_in_table')</td>
            </tr>
        @endif
    </tbody>
    @if ($customer->transaction->count() > 0)
        <tfoot>
            <tr>
                <th colspan="3" class="text-right"> Opening Balance</th>
                <td colspan="3"><i class="fa fa-inr" aria-hidden="true"></i> {{ isset($openingBalance) ? number_format(abs($openingBalance),2) : '0.00' }}</td>
            </tr>
            <tr>
                <th colspan="3" class="text-right"> Current Balance</th>
                <td><i class="fa fa-inr" aria-hidden="true"></i> {{ number_format($debitTotal,2) }}</td>
                <td><i class="fa fa-inr" aria-hidden="true"></i> {{ number_format($creditTotal,2) }}</td>
                <td></td>
            </tr>
            <tr>
                <th colspan="3" class="text-right"> Closing Balance</th>
                @php
                    $flagSide = false;
                    $debitTotal +=$openingBalance; 
                    if($debitTotal >= $creditTotal){
                        $flagSide = true;
                    }
                    
                    $closingBalance = $debitTotal - $creditTotal;
                    //$closingBalance = $closingBalance + $openingBalance;
                @endphp
                
                @if($flagSide)
                    <td><i class="fa fa-inr" aria-hidden="true"></i> {{ number_format(abs($closingBalance),2) }}</td>
                    <td colspan="2"></td>
                @else
                    <td></td>
                    <td><i class="fa fa-inr" aria-hidden="true"></i> {{ number_format(abs($closingBalance),2) }}</td>
                @endif
            </tr>
        </tfoot>
    @endif
</table>