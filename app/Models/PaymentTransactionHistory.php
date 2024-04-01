<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentTransactionHistory extends Model
{
    use HasFactory, SoftDeletes;
    protected $table= 'payment_transaction_histories';

    protected $fillable = ['customer_id', 'payment_transaction_id','payment_type', 'payment_way', 'voucher_number', 'order_id', 'extra_details', 'remark', 'amount',  'entry_date', 'created_by','updated_by','deleted_by','is_split'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function(PaymentTransactionHistory $model) {
            $model->created_by = auth()->user()->id;
        });

        static::deleting(function(PaymentTransactionHistory $model) {
            $model->deleted_by = auth()->user()->id;
            $model->save();
        });
    }

    public function setEntryDateAttribute($input){
        if ($input != null && $input != '') {
            $this->attributes['entry_date'] = Carbon::createFromFormat(config('app.date_format'), $input)->format('Y-m-d');
        } else {
            $this->attributes['entry_date'] = null;
        }
    }

    /**
     * Get attribute from date format
     * @param $input
     *
     * @return string
     */
    public function getEntryDateAttribute($input){
        $zeroDate = str_replace(['Y', 'm', 'd'], ['0000', '00', '00'], config('app.date_format'));

        if ($input != $zeroDate && $input != null) {
            return Carbon::createFromFormat('Y-m-d', $input)->format(config('app.date_format'));
        } else {
            return '';
        }
    }

    /**
     * Set attribute to date format
     * @param $input
     */
    public function setAmountAttribute($input){
        if ($input != '') {
            $this->attributes['amount'] = $input;
        } else {
            $this->attributes['amount'] = null;
        }
    }


    /**
     * Set attribute to date format
     * @param $input
     */
    public function setPaymentTypeAttribute($input){
        if ($input != '') {
            $this->attributes['payment_type'] = $input;
        } else {
            $this->attributes['payment_type'] = null;
        }
    }

    public function user(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function deletedByUser(){
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function order(){
        return $this->belongsTo(Order::class, 'order_id');
    }

}
