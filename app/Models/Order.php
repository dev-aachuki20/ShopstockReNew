<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

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
        'sold_by',
        'is_modified',
        'deleted_by'
    ];

    protected $hidden = [];

    protected static function boot()
    {
        parent::boot();
        static::creating(function(Order $model) {
            $model->created_by = auth()->user()->id;
        });

        static::deleting(function(Order $model) {
            $model->deleted_by = auth()->user()->id;
            $model->save();
        });

        static::updating(function(Order $model) {
            $model->updated_by = auth()->user()->id;
           // $model->is_modified = 1;
        });
    }

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

    public function history()
    {
        return $this->hasMany(OrderEditHistory::class);
    }

}
