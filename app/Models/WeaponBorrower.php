<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeaponBorrower extends Model
{
    protected $fillable = [
        'armorer_id',
        'approved_by',
        'name',
        'received_officer',
        'start_date',
        'expected_return_date',
        'returned_at',
        'status'
    ];

    protected $casts = [
    'received_officer' => 'array',
];

    public function armorer()
    {
        return $this->belongsTo(User::class, 'armorer_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }


    public function borrowed_weapons()
    {
        return $this->hasMany(BorrowedWeapon::class);
    }


}
