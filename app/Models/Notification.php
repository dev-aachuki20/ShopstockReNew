<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "notifications";

    protected $primarykey = "id";

    protected $fillable = [
        'id',
        'notifiable',
        'subject',
        'message',
        'notification_type',
        'created_by',
        'read_at',
    ];

    protected $dates = [
        'updated_at',
        'created_at',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function(Notification $model) {
            $model->created_by = auth()->user()->id;
        });
    }

    // public function notifyUser()
    // {
    //     return $this->belongsTo(User::class,'notifiable_id','id');
    // }

    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }

    public function notifiable()
    {
        return $this->morphTo();
    }

}
