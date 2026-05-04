<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ITEquipment extends Model
{
    protected $table = 'it_equipment';

    protected $fillable = [
        'user_id', 'equipment_name', 'serial_number',
        'category', 'issued_date', 'due_date',
        'return_date', 'status', 'notes',
    ];

    protected $casts = [
        'issued_date' => 'date',
        'due_date'    => 'date',
        'return_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}