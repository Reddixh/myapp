<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'order', 'is_active'];

    public function clearances()
    {
        return $this->hasMany(Clearance::class);
    }

    public function penalties()
    {
        return $this->hasMany(Penalty::class);
    }
}