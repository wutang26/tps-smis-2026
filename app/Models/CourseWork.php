<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseWork extends Model
{
    use HasFactory;
    protected $table = 'courseworks';

    protected $fillable = ['programme_id','course_id',  'semester_id', 'assessment_type_id', 'coursework_title',  'max_score', 'due_date', 'session_programme_id','created_by','updated_by'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
    public function courseworkResults()
    {
        return $this->hasMany(CourseworkResult::class, 'coursework_id');
    }

    public function assessmentType()
    {
        return $this->belongsTo(AssessmentType::class, 'assessment_type_id');
    }

    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }
}
