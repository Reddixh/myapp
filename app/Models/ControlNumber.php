<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ControlNumber extends Model
{
    protected $fillable = [
        'user_id', 'penalty_id',
        'control_number', 'status', 'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'date',
    ];

    public function penalty()
    {
        return $this->belongsTo(Penalty::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}