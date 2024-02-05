<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoleIp extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'role_ips';
    protected $fillable = [
        'role_ips',
        'user_id',
        'is_active',
        'created_by',
        'updated_by'
    ];
}
