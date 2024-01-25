<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    public $table = 'address';
    protected $fillable = ['address','updated_at'];
    public $timestamps = true;

    public function users(){
        return $this->hasMany(User::class, 'address_id');
    }

    public function customers(){
        return $this->hasMany(Customer::class);
    }

}
