<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseworkResult extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'student_id', 'coursework_id', 'score', 'semester_id','created_by','updated_by'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function coursework()
    {
        return $this->belongsTo(CourseWork::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }


    public function programmeCourseSemester()
    {
        return $this->belongsTo(ProgrammeCourseSemester::class, 'course_id', 'course_id');
    }
}
