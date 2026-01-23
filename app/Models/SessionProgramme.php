<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SessionProgramme extends Model
{
    use HasFactory;
  
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $fillable = [
        'session_programme_name','description','year','startDate','endDate','is_current','is_active','programme_id'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function students()
    {
        return $this->hasMany(Student::class);
    }

        public function programme()
    {
        return $this->belongsTo(Programme::class,'programme_id');
    }
    public function programmeCourseSemesters()
    {
        return $this->hasMany(ProgrammeCourseSemester::class);
    }

    
}
