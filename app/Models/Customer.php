<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Customer extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'customers';

    protected $fillable = [
        'name',
        'phone_number',
        'alternate_phone_number',
        'area_id',
        'is_type',
        'credit_limit',
        'created_by',
        'updated_by'
    ];

    public function area(){
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function transaction(){
        return $this->hasMany(PaymentTransaction::class, 'customer_id')->orderBy('entry_date','asc');
    }
}
