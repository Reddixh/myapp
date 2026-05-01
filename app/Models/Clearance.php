<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Clearance extends Model
{
    protected $fillable = [
        'user_id', 'department_id', 'status',
        'remarks', 'requested_at', 'cleared_at'
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'cleared_at'   => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}