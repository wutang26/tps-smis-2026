<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeMapping extends Model
{
    protected $fillable = ['grading_system_id', 'grade', 'grade_point', 'min_score', 'max_score', 'remarks','class_award'];

    public function gradingSystem()
    {
        return $this->belongsTo(GradingSystem::class);
    }
}
