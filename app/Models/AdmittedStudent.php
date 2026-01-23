<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmittedStudent extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'student_id',
        'registration_number',
        'programme_id',
        'study_level_id',
        'admitted_date',
        'completion_date'
    ];

    // Define the relationship with the Student model
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }
    
     // Define the relationship with the StudyLevel model
     public function studyLevel()
     {
         return $this->belongsTo(StudyLevel::class);
     }
}

