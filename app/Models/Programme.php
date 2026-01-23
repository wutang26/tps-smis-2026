<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Department;
use App\Models\studyLevel;

class Programme extends Model
{    
    protected $fillable = [ ' programme_id','programmeName', 'abbreviation', 'duration', 'department_id', 'studyLevel_id'];

    public function department() 
    { 
        return $this->belongsTo(Department::class); 
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'programme_course_semesters')
                    ->withPivot('semester_id', 'course_type', 'credit_weight', 'session_programme_id');
    }

    public function students() 
    { 
        return $this->hasMany(Student::class); 
    }
 
    public function studyLevel()
    {
        return $this->belongsTo(studyLevel::class, 'studyLevel_id');
    }
    
    public function admittedStudents()
    {
        return $this->hasMany(AdmittedStudent::class);
    }

    public function programmeCourseSemesters()
    {
        return $this->hasMany(ProgrammeCourseSemester::class);
    }

    
    
}
