<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Semester extends Model
{
    protected $fillable = ['semester_name']; 
    
    public function courseWorks() 
    { 
        return $this->hasMany(CourseWork::class); 
    } 
    public function semesterExams() 
    { 
        return $this->hasMany(SemesterExam::class); 
    } 
    public function courseworkResults() 
    { 
        return $this->hasMany(CourseworkResult::class); 
    } 
    public function semesterExamResults() 
    { 
        return $this->hasMany(SemesterExamResult::class);
    } 
    public function finalResults() 
    { 
        return $this->hasMany(FinalResult::class); 
    }
    public function courses_old()
    {
        return $this->belongsToMany(Course::class, 'programme_course_semesters')
                    ->withPivot('programme_id', 'course_type', 'credit_weight');
    }

    public function courses()
{
    $currentSession = session('selected_session',4);

    return $this->belongsToMany(Course::class, 'programme_course_semesters')
                ->withPivot('programme_id', 'course_type', 'credit_weight', 'session_programme_id')
                ->wherePivot('session_programme_id', $currentSession);
}

    // public function programmeCourseSemesters()
    // {
    //     return $this->hasMany(ProgrammeCourseSemester::class);
    // }

//     public function assignedCourses()
// {

//         if (Auth::user()->hasRole('Super Administrator')) {
//             return Course::all();
//         }
//         // Non-admin: only see assigned courses via course_instructors
//         return Course::join('programme_course_semesters', 'courses.id', '=', 'programme_course_semesters.course_id')
//             ->join('course_instructors', 'programme_course_semesters.id', '=', 'course_instructors.programme_course_semester_id')
//             ->where('course_instructors.user_id', $this->id)
//             ->select('courses.*')
//             ->distinct()
//             ->get();
//     }


}
