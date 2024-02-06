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
        'ip_address',
        'created_by',
        'updated_by'
    ];
}
