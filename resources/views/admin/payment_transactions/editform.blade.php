<div class="panel panel-default col-md-12">
    <div class="panel-body col-md-12">

        <div class="row">
            <div class="col-md-6 form-group">
                {!! Form::label('customer_id', trans('quickadmin.transaction.fields.customer').'*', ['class' => 'control-label']) !!}
                {!! Form::select('customer_id', $customers, $transaction->customer_id, ['class' => 'form-control select2', 'required' => '']) !!}
                <div class="error_customer_id text-danger error"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 form-group">
                {!! Form::label('payment_mode', trans('quickadmin.transaction.fields.payment_mode').'*', ['class' => 'control-label']) !!}
                {!! Form::hidden('payment_type','debit') !!}
                {!! Form::select('payment_way', $paymentWays, $transaction->payment_way, ['class' => 'payment_mode form-control select2', 'required' => '']) !!}
                <div class="error_payment_way text-danger error"></div>
            </div>
        </div>
        
        
        <div class="row extra_detail_row" style="display: {{ ($transaction->payment_way != '' && $transaction->payment_way !='by_case') ?'block':'none' }}">
            <div class="col-md-6 form-group">
                {!! Form::label('extra_details', trans('quickadmin.transaction.fields.check_account').'*', ['class' => 'extra_detail_label control-label']) !!}
                {!! Form::text('extra_details', $transaction->extra_details, ['id' => 'extra_details','class' => 'form-control', 'placeholder' => '']) !!}
                <div class="error_extra_details text-danger error"></div>               
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 form-group">
                {!! Form::label('remark', trans('quickadmin.transaction.fields.remark'), ['class' => 'control-label']) !!}
                {!! Form::text('remark', $transaction->remark, ['class' => 'form-control', 'placeholder' => '']) !!}
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 form-group">
                {!! Form::label('amount', trans('quickadmin.transaction.fields.amount').'*', ['class' => 'control-label']) !!}
                <input type="number" value="{{ $transaction->amount}}" class="form-control"  onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Period','NumpadDecimal'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space'"  min="0" step=".01" autocomplete="off" name="amount" id="amount">
                 
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 form-group">
                {!! Form::label('entry_date', trans('quickadmin.transaction.fields.entry-date').'*', ['class' => 'control-label']) !!}               
                <input type="date" class="form-control" name="entry_date" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" id="date" autocomplete="true" placeholder="@lang('admin_master.product.product_name_enter')">
                <div class="error_entry_date text-danger error"></div> 
            </div>
        </div>
        {!! Form::submit(trans('quickadmin.qa_save'), ['class' => 'btn btn-lg btn-success cashReceiptSubmitBtn']) !!}
    </div>
</div>

@section('customJS')
<script type="text/javascript">
  $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('submit', "#transactionForm", function(e) {
            e.preventDefault();
            $('.error').html('');
            var action = $(this).attr('action');
            var method = $(this).attr('method');
            var formData = new FormData($("#transactionForm")[0]);
            $('.save_btn').prop('disabled', true);
            formData.append('_method', method);

            $.ajax({
                type: "POST",
                url: action,
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    if ($.isEmptyObject(data.error)) {
                        var alertType = "{{ trans('quickadmin.alert-type.success') }}";
                        var message = data.success;
                        var title = "Group";
                        showToaster(title, alertType, message);
                        setTimeout(() => {
                            window.location.replace("{{route('admin.master.products.index')}}");
                        }, 1500);
                    } else {
                        $('.save_btn').prop('disabled', false);
                        printErrorMsg(data.error);
                    }
                },
                error: function(data) {
                    $('.save_btn').prop('disabled', false);
                }
            });
        });
    });

    function printErrorMsg(msg) {
        $.each(msg, function(key, value) {
            $(`.error_${key}`).html(value);
        });
    }

</script>