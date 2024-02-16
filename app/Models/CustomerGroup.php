<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerGroup extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'customer_id',
        'group_id'
    ];

    public function group(){
        return $this->belongsTo(Group::class, 'group_id');
    }

}
