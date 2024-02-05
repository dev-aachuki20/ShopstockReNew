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
        'parent_id',
        'created_by',
        'updated_by'        
    ];

    public function products(){
        return $this->hasMany(Product::class, 'group_id');
    }
    public function subproducts(){
        return $this->hasMany(Product::class, 'sub_group_id');
    }

    public function parent()
    {
        return $this->belongsTo(Group::class, 'parent_id');
    }
    public function children()
    {
        return $this->hasMany(Group::class, 'parent_id');
    }
}
