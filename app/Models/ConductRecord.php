<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConductRecord extends Model
{
    protected $fillable = [
        'user_id', 'title', 'description',
        'type', 'severity', 'status',
        'incident_date', 'resolved_date',
    ];

    protected $casts = [
        'incident_date'  => 'date',
        'resolved_date'  => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}