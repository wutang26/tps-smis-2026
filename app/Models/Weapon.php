<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Weapon extends Model
{
    use HasFactory;

    protected $fillable = [
        'serial_number',
        'company_id',
        'weaponModel_id',
        'weaponOwnershipType_id',
        'owner',
    ];

    protected $casts = [
        'owner' => 'array',
    ];

    // Relationships

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function weaponModel()
    {
        return $this->belongsTo(WeaponModel::class, 'weaponModel_id');
    }

    public function ownershipType()
    {
        return $this->belongsTo(WeaponOwnershipType::class, 'weaponOwnershipType_id');
    }

        public function handovers()
    {
        return $this->hasMany(WeaponHandover::class, );
    }
}
