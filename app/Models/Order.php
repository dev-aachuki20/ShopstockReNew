<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    // public static function boot(){
    //     parent::boot();
    //     self::creating(function ($model) {
    // 		$model->invoice_number = getNewInvoiceNumber($model->id);
    //     });
    // }

    protected $fillable = [
        'customer_id',
        'shipping_amount',
        'total_amount',
        'order_type',
        'remark',
        'is_draft',
        'created_by',
        'updated_by',
        'invoice_number',
        'area_id',
        'invoice_date',
        'due_date',
        'is_add_shipping',
        'sold_by'
    ];

    protected $hidden = [];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function orderProduct()
    {
        return $this->hasMany(OrderProduct::class, 'order_id');
    }

    public function orderPayTransaction()
    {
        return $this->hasMany(PaymentTransaction::class, 'order_id');
    }
}
