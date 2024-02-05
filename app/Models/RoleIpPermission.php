<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleIpPermission extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'role_ip_permissions';
    protected $fillable = [
        'role_id',
        'role_ip_id',
    ];
}
