<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SemesterExam extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'semester_id',
        'exam_title',                // optional: exam name/type (Midterm, Final, etc.)
        'exam_date',
        'max_score',
        'session_programme_id',
        'created_by',
        'updated_by',
    ];

    // Each exam belongs to a course
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Each exam belongs to a semester
    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    // Each exam has many results
    public function results()
    {
        return $this->hasMany(SemesterExamResult::class);
    }
}
