<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowedWeapon extends Model
{
    protected $fillable = ['weapon_borrower_id','weapon_id'];

        public function weapon()
    {
        return $this->belongsTo(Weapon::class);
    }

        public function weapon_borrower()
    {
        return $this->belongsTo(WeaponBorrower::class);
    }
}
