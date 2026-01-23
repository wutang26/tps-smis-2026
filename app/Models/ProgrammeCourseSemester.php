<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgrammeCourseSemester extends Model
{
    protected $table = "programme_course_semesters";
    protected $fillable = ['programme_id', 'course_id','semester_id', 'course_type', 'credit_weight', 'session_programme_id', 'created_by'];

    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function instructors()
    {
        return $this->belongsToMany(User::class, 'course_instructors', 'programme_course_semester_id', 'user_id')
                    ->withPivot('academic_year');
    }

}
