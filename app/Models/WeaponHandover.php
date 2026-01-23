<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class WeaponHandover extends Model
{
    protected $fillable = [
        'weapon_id',
        'staff_id',        // Staff taking the weapon
        'handover_armorer_id',      // Staff who keeps the armory 
        'return_armorer_id',      // Armorer receiving back
        'expected_return_at',//Expected time to return
        'handover_at',   // 'issue' or 'return'
        'returned_at',
        'purpose',          // 'Beats', 'Escort weapons'
    ];

    // Weapon involved
    public function weapon()
    {
        return $this->belongsTo(Weapon::class);
    }

    // Staff who takes the weapon
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    // Staff who keeps the armory
    public function handover_armorer()
    {
        return $this->belongsTo(User::class, 'handover_armorer_id');
    }
        public function return_armorer()
    {
        return $this->belongsTo(User::class, 'return_armorer_id');
    }
}
