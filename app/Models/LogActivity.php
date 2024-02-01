<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Request;
class LogActivity extends Model
{
    use HasFactory, SoftDeletes;

    public static function boot(){
        parent::boot();
        self::creating(function ($model) {
            $model->url = Request::fullUrl();
            $model->method = Request::method();
            $model->ip = Request::ip();
            $model->agent = Request::header('user-agent');
            $model->user_id = auth()->check() ? auth()->user()->id : 1;
        });
    }

    protected $fillable = [
        'created_by','updated_by','subject', 'url', 'method', 'ip', 'agent', 'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
