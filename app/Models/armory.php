<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Armory extends Model
{
    protected $fillable = ['staff_id'];

    public function handovers()
    {
        return $this->hasMany(WeaponHandover::class, 'staff_id');
    }

    public function primaryShifts()
    {
        return $this->hasMany(Shift::class, 'primary_staff_id');
    }

    public function secondaryShifts()
    {
        return $this->hasMany(Shift::class, 'secondary_staff_id');
    }
}
