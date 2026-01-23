<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $fillable =[
        'session_programme_id', 'course_id', 'semester_id','enrollment_date'
    ];

        public function sessionProgramme()
    {
        return $this->belongsTo(SessionProgramme::class);
    }
        public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
}
