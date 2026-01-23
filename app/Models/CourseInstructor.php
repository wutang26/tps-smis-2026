<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseInstructor extends Model
{
    protected $table = 'course_instructors';
    
    protected $fillable = [
        'programme_course_semester_id',
        'course_id',
        'user_id',
        'academic_year',
    ];

    public function programmeCourseSemester()
    {
        return $this->belongsTo(ProgrammeCourseSemester::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
//     public function course()
// {
//     return $this->hasOneThrough(
//         Course::class,
//         ProgrammeCourseSemester::class,
//         'id',           // Foreign key on ProgrammeCourseSemester
//         'id',           // Foreign key on Course
//         'programme_course_semester_id', // Local key on CourseInstructor
//         'course_id'     // Local key on ProgrammeCourseSemester
//     );
// }
    public function getCourseAttribute()
    {
        return $this->programmeCourseSemester?->course;
    }
}
