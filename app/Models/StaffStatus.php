<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'staff_id',
        'previous_status',
        'current_status',
        'start_date',
        'end_date',
        'user_id'
    ];

    /**
     * The staff member this status belongs to
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    /**
     * The user who updated the status
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
