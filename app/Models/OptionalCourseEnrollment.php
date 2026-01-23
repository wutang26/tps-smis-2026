<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OptionalCourseEnrollment extends Model
{
    protected $fillable = ['student_id', 'course_id', 'semester_id', 'enrollment_date'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
}
