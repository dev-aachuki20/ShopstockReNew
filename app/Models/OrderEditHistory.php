<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderEditHistory extends Model
{
    use HasFactory, SoftDeletes;
    public $table = 'order_history';
    protected $fillable = [
        'order_id',
        'order_product_id',
        'product_id',
        'quantity',
        'price',
        'height',
        'width',
        'total_price',
        'is_draft',
        'description',
        'other_details',
        'is_sub_product',
        'created_by',
        'updated_by',
        'deleted_by',
        'order_update_time',
        'update_status',
        'order_data'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function(OrderEditHistory $model) {
            $model->created_by = auth()->user()->id;
            $model->order_update_time = now();
        });

        static::deleting(function (OrderEditHistory $model) {
            $model->deleted_by = auth()->user()->id;
            $model->save();
        });

        static::updating(function(OrderEditHistory $model) {
            $model->updated_by = auth()->user()->id;
        });
    }
}
