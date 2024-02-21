<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderProduct extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'order_id',
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
        'deleted_by'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            if (Auth::check()) {
                // Get the currently authenticated user's ID
                $userId = Auth::id();

                // Update the deleted_by column
                $model->update(['deleted_by' => $userId]);
            }
        });
    }
}
