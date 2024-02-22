<?php
namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrdersRequest extends FormRequest
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
            // 'shipping_amount'=> 'regex:/^\d+(\.\d{1,2})?$/',
            'total_amount'  => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'products'      => 'required|array',
        ];
    }
}
