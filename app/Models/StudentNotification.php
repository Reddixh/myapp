<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentNotification extends Model
{
    protected $fillable = [
        'user_id', 'title', 'message',
        'type', 'from', 'is_read',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}