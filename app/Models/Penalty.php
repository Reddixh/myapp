<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Penalty extends Model
{
    protected $fillable = [
        'user_id', 'department_id', 'name',
        'type', 'amount', 'status', 'due_date', 'paid_at'
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_at'  => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function controlNumber()
    {
        return $this->hasOne(ControlNumber::class);
    }
}