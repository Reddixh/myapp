<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'reg_number',
        'email',
        'password',
        'programme',
        'year',
        'college',
        'role',
        'department_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}