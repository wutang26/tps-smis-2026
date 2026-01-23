<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $casts = [
        'due_date'   => 'datetime',
        'assigned_at'=> 'datetime',
    ];

    protected $fillable = [
        'title',
        'description',
        'priority',
        'due_date',
    ];
    public function staff()
    {
        return $this->belongsToMany(Staff::class)
            ->withPivot('assigned_at', 'start_time', 'end_time', 'is_active', 'region_id', 'district_id', 'assigned_by')
            ->withTimestamps();
    }

    public function getStatusAttribute()
    {

        if ($this->due_date && $this->due_date->isPast()) {
            return 'overdue';
        }
        return 'pending';
    }
}
