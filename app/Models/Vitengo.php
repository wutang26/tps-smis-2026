<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vitengo extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
