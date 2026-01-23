<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Department;

class Course extends Model
{
    protected $fillable = ['courseCode', 'courseName', 'department_id'];

    public function department() 
    { 
        return $this->belongsTo(Department::class); 
    }

    // public function students() 
    // { 
    //     return $this->belongsToMany(Student::class, 'enrollments'); 
    // }

    public function programmes()
    {
        return $this->belongsToMany(Programme::class, 'programme_course_semesters')
                    ->withPivot('semester_id', 'course_type', 'credit_weight', 'session_programme_id');
    }

    public function semesters()
    {
        return $this->belongsToMany(Semester::class, 'programme_course_semesters')
                    ->withPivot('programme_id', 'course_type', 'credit_weight', 'session_programme_id');
    }

    public function courseWorks()
    {
        return $this->hasMany(CourseWork::class);
    }

    public function courseworkResults()
    {
        return $this->hasMany(CourseworkResult::class);
    }

    public function semesterExams()
    {
        return $this->hasMany(SemesterExam::class);
    }

        public function enrolledSession()
    {
        return $this->hasMany(Enrollment::class);
    }
    public function programmeCourseSemestersInProgramme($programmeId)
    {
        return $this->programmes()
            ->where('programmes.id', $programmeId)
            ->first()
            ?->programmeCourseSemesters
            ->where('course_id', $this->id)
            ->values();
    }

    public function instructors()
{
    return $this->hasManyThrough(
        User::class,
        CourseInstructor::class,
        'programme_course_semester_id', // Foreign key on CourseInstructor
        'id',                            // Local key on User
        'id',                            // Local key on Course
        'user_id'                        // Foreign key on CourseInstructor
    )
    ->join('programme_course_semesters', 'course_instructors.programme_course_semester_id', '=', 'programme_course_semesters.id')
    ->where('programme_course_semesters.course_id', $this->id)
    ->select('users.*');
}
    public function courseInstructors()
{
    return $this->hasMany(CourseInstructor::class, 'course_id', 'id');
}

}
