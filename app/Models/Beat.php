<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GuardArea;
use App\Models\PatrolArea;
use App\Models\Student;

class Beat extends Model
{
    use HasFactory;

    protected $fillable = [
        'beatType_id', 'guardArea_id', 'patrolArea_id', 'student_ids', 'date', 'start_at', 'end_at', 'status'
    ];

    protected $casts = [
        'student_ids' => 'array', // Ensure JSON field is cast properly
    ];

    // ✅ Relationship with GuardArea
    public function guardArea()
    {
        return $this->belongsTo(GuardArea::class, 'guardArea_id');
    }

    // ✅ Relationship with PatrolArea
    public function patrolArea()
    {
        return $this->belongsTo(PatrolArea::class, 'patrolArea_id');
    }

    // ✅ Relationship with BeatType
    public function beatType()
    {
        return $this->belongsTo(BeatType::class, 'beatType_id');
    }
    
    // ✅ Relationship with Student
    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_beat', 'beat_id', 'student_id')
                    ->withTimestamps();
    }
}
