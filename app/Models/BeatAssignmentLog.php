<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeatAssignmentLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'guard_area_id',   // fixed
        'patrol_area_id',
        'reason',
        'beat_round',
        'last_assigned_at',
        'date',
    ];

    // Relations
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function guardArea()
    {
        return $this->belongsTo(GuardArea::class, 'guard_area_id');
    }

    public function patrolArea()
    {
        return $this->belongsTo(PatrolArea::class, 'patrol_area_id');
    }

    // Unified area accessor
    public function getAreaAttribute()
    {
        return $this->guardArea ?? $this->patrolArea;
    }
}
