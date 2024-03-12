<?php
namespace App\Http\Requests\PaymentTransactions;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdatePaymentTransactionsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer_id'   => 'required|exists:customers,id',
            'payment_way'   => 'required|in:by_cash,by_check,by_account',
           // 'extra_details' => 'required_unless:payment_way,by_cash',
            'amount'        => 'required|regex:/^\d+(\.\d{1,4})?$/',
            'entry_date'    => 'required',
        ];
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return [
            //'extra_details.required_unless'  => 'The check/account number field is required, if payment mode is in by check or by account.',
        ];
    }

}
