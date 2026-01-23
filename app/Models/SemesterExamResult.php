<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SemesterExamResult extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'semester_exam_id', 'score','created_by'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function semesterExam()
    {
        return $this->belongsTo(SemesterExam::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

        public function exam()
    {
        return $this->belongsTo(Semester::class);
    }

    
}