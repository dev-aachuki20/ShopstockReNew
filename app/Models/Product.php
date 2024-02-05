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
        'name',
        'group_id',
        'sub_group_id',
        'calculation_type',
        'unit_type',
        'price',
        'min_sale_price',
        'wholesaler_price',
        'retailer_price',
        'image',       
        'is_height',
        'is_width',
        'is_length',
        'is_sub_product',
        'is_active',
        'extra_option_hint',
        'created_by',
        'updated_by'
    ];



    public function group(){
        return $this->belongsTo(Group::class, 'group_id');
    }
    public function sub_group(){
        return $this->belongsTo(Group::class, 'sub_group_id');
    }
    public function product_unit(){
        return $this->belongsTo(ProductUnit::class, 'unit_type');
    }
}
