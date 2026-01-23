<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Handover extends Model
{
    protected $fillable = [
    'weapon_id',
    'staff_id',
    'handover_date',
    'return_date',
    'purpose',
    'remarks',
    'status',
];


    public function weapon()
    {
        return $this->belongsTo(Weapon::class);
    }
     public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
