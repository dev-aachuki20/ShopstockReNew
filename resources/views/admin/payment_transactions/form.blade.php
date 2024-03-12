<div class="panel panel-default col-md-12">
    <div class="panel-body col-md-12">
        <div class="row">
            <div class="col-md-6 form-group formValidate">
                {!! Form::label('customer_id', trans('quickadmin.transaction.fields.customer').'*', ['class' => 'control-label']) !!}
                <select class="form-control select2" name="customer_id" id="customer_id" required>
                    <option value="">{{ trans('quickadmin.qa_please_select_customer') }}</option>
                    @if($customers->count() > 0)
                    @foreach($customers as $id=>$value)
                    <option value="{{ $value->id }}" data-credit="{{ getTotalCredit($value->id) }}" data-debit="{{ getTotalDebit($value->id) }}" data-limit="{{$value->credit_limit}}" {{ old('customer_id') == $value->id ? 'selected' : ''}}>{{ $value->name }}</option>
                    @endforeach
                    @endif
                </select>
                @if($errors->has('customer_id'))
                <p class="help-block red text-danger">
                    {{ $errors->first('customer_id') }}
                </p>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 form-group">
                {!! Form::label('payment_mode', trans('quickadmin.transaction.fields.payment_mode').'*', ['class' => 'control-label']) !!}
                {!! Form::hidden('payment_type','debit') !!}
                {!! Form::select('payment_way', $paymentWays, old('payment_way'), ['class' => 'payment_mode form-control select2', 'required' => '']) !!}
                @if($errors->has('payment_way'))
                <p class="help-block red text-danger">
                    {{ $errors->first('payment_way') }}
                </p>
                @endif
            </div>
        </div>


        <div class="row extra_detail_row" style="display: {{ (old('payment_way') != '' && old('payment_way') !='by_case') ?'block':'none' }}">
            <div class="col-md-6 form-group">
                {!! Form::label('extra_details', trans('quickadmin.transaction.fields.check_account').'*', ['class' => 'extra_detail_label control-label']) !!}
                {!! Form::text('extra_details', old('extra_details'), ['id' => 'extra_details','class' => 'form-control', 'placeholder' => '']) !!}
                @if($errors->has('extra_details'))
                <p class="help-block red text-danger">
                    The Check/Account number field is required, if payment mode is in By Check/By Account.
                </p>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 form-group">
                {!! Form::label('remark', trans('quickadmin.transaction.fields.remark'), ['class' => 'control-label']) !!}
                {!! Form::text('remark', old('remark'), ['class' => 'form-control', 'placeholder' => '']) !!}
                @if($errors->has('remark'))
                <p class="help-block red text-danger">
                    {{ $errors->first('remark') }}
                </p>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 form-group">
                {!! Form::label('amount', trans('quickadmin.transaction.fields.amount').'*', ['class' => 'control-label']) !!}
                <input type="number" value="{{ old('amount') }}" class="form-control" onkeydown="javascript: return ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Period','NumpadDecimal'].includes(event.code) ? true : !isNaN(Number(event.key)) && event.code!=='Space'" min="0" step=".01" autocomplete="off" name="amount" id="amount">

                @if($errors->has('amount'))
                <p class="help-block red text-danger">
                    {{ $errors->first('amount') }}
                </p>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 form-group date_main_show">
                {!! Form::label('entry_date', trans('quickadmin.transaction.fields.entry-date').'*', ['class' => 'control-label']) !!}
                <input type="date" class="form-control dateshow" name="entry_date" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" id="date" autocomplete="true" placeholder="@lang('admin_master.product.product_name_enter')">
                @if($errors->has('entry_date'))
                <p class="help-block red text-danger">
                    {{ $errors->first('entry_date') }}
                </p>
                @endif
            </div>
        </div>
        {!! Form::submit(trans('quickadmin.qa_save'), ['class' => 'btn btn-lg btn-success cashReceiptSubmitBtn']) !!}
    </div>
</div>
@section('customJS')
<script src="{{ asset('admintheme/assets/js/jquery.validate.min.js') }}"></script>
<script type="text/javascript">
    var remainingBalance = 0;
    $('#cash-reciept-form').validate({
        focusInvalid: false,
        rules: {
            'customer_id': {
                required: true,
            },
            'amount': {
                required: true,
            },
            'payment_way': {
                required: true,
            }
        },
        messages: {
            customer_id: {
                required: "This field is required",
            },
        },
        errorElement: 'p',
        errorClass: 'is-invalid',
        errorPlacement: function(error, element) {
            element.siblings('.validation-error').remove();

            error.addClass('validation-error is-invalid invalid-feedback');
            if ($(element).hasClass('select2')) {
                error.insertAfter(element.siblings('.select2'));
            } else {
                error.insertAfter(element);
            }
        },

        // Errors //
        // errorPlacement: function errorPlacement(error, element) {
        //     var $parent = $(element).parents('.formValidate');
        //     console.log(error, element, $parent);
        //     if ($parent.find('.help-block').length) {
        //         return;
        //     }
        //     $parent.append(
        //         error.addClass('jquery-validation-error small form-text invalid-feedback')
        //     );
        // },
        // highlight: function(element) {
        //     var $el = $(element);
        //     var $parent = $el.parents('.formValidate');
        //     $el.addClass('is-invalid');
        // },
        // unhighlight: function(element) {
        //     $(element).parents('.formValidate').find('.is-invalid').removeClass('is-invalid');
        // }
    });

    $(document).on('keyup', function(e) {
        if (e.key === 'Enter') {
            $('#cash-reciept-form').submit();
        }
    });

    // $('#cash-reciept-form').submit(function (event) {
    //     // Disable the submit button
    //     if ($('#cash-reciept-form')[0].checkValidity()) {
    //         $('.cashReceiptSubmitBtn').prop('disabled', true);
    //     } else {
    //         event.preventDefault();
    //     }
    // });


    $("select.payment_mode").change(function(e) {
        var payment_mode = $(".payment_mode option:selected").val();
        if (payment_mode != '' && payment_mode != 'by_cash') {
           // $("#extra_details").prop('required', true);
            $('.extra_detail_row').show('slow');
        } else {
            $("#extra_details").val('');
            //$("#extra_details").prop('required', false);
            $('.extra_detail_row').hide('slow');
        }
    });

    $('#customer_id').change(function() {
        let $this = $(this);
        let customerId = $this.val();
        $('#remain_balance').parent().parent().remove();
        $('#customer_id-error').remove();
        if (customerId != '') {
            let findOption = $this.find('option').filter('[value=' + customerId + ']');
            let debit = parseFloat(findOption.attr('data-debit'));
            let credit = parseFloat(findOption.attr('data-credit'));
            remainingBalance = debit - credit;
            remainingBalance = Math.abs(remainingBalance);

            var addRemainingElement = '<div class="row">' +
                '<div class="col-md-6 mb-5 form-group">' +
                '<label for="remain_balance" class="control-label">{{ trans("quickadmin.transaction.fields.remain_balance")."*"}}</label>' +
                '<input type="number" value="' + remainingBalance + '" class="form-control" autocomplete="off" id="remain_balance" disabled>' +
                '</div>' +
                '</div>';

            $(addRemainingElement).insertAfter($this.parent().parent());
        }
    });

    $(document).on('input', '#amount', function() {
        var $this = $(this);
        var amount = parseFloat($this.val());
        if (remainingBalance != undefined && !isNaN(amount)) {
            remainingBalance = parseFloat(remainingBalance);
            $('#amount_error').remove();
            if (remainingBalance >= amount) {
                var remain = remainingBalance - amount;
                $('#remain_balance').val(remain.toFixed(0));
            } else {
                $('#remain_balance').val(remainingBalance);
                if (!isNaN(amount)) {
                    var alertMess = '<p id="amount_error" class="help-block red text-danger">Amount should be less than remain balance</p>';
                    $(alertMess).insertAfter($this);
                }
            }
        }
    });
</script>
@endsection