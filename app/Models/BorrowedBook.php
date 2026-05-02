<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowedBook extends Model
{
    protected $fillable = [
        'user_id', 'book_title', 'author',
        'isbn', 'borrow_date', 'due_date',
        'return_date', 'status',
    ];

    protected $casts = [
        'borrow_date'  => 'date',
        'due_date'     => 'date',
        'return_date'  => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isOverdue()
    {
        return $this->status === 'borrowed' && $this->due_date->isPast();
    }
}