<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Group extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'groups';
    protected $fillable = [
        'name',
        'created_by',
        'updated_by'
    ];

    public function products(){
        return $this->hasMany(Product::class, 'group_id');
    }
}
