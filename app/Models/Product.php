<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';
    protected $fillable = [
        'created_by',
        'updated_by',
        'name',
        'unit_type',
        'print_name',
        'price',
        'sale_price',
        'min_sale_price',
        'wholesaler_price',
        'retailer_price',
        'image',
        'group_id',
        'product_category_id',
        'is_height',
        'is_width',
        'is_length',
        'is_sub_product',
        'is_active',
        'extra_option_hint'
    ];
}
