<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendence extends Model
{
   protected $fillable = [
      'attendenceType_id',
      'platoon_id',
      'present',
      'sentry',
      'absent',
      'adm',
      'ed',
      'safari',
      'off',
      'mess',
      'female',
      'male',
      'lockUp',
      'kazini',
      'sick',
      'lockUp_students_ids',
      'total',
      'absent_student_ids',
      'adm_student_ids',
      'ed_student_ids',
      'session_programme_id',
      'created_at',
      'updated_at',
      'recorded_by',
      'date'
   ];

   public function recordedBy()
   {
      return $this->belongsTo(User::class, 'recorded_by', 'id');
   }
   public function platoon()
   {
      return $this->belongsTo(Platoon::class, 'platoon_id', 'id');
   }
   public function type()
   {
      return $this->belongsTo(AttendenceType::class, 'attendenceType_id', 'id');
   }

   public function setAbsentStudentsAttribute($students)
   {
      $this->attributes['absent_students'] = $students;
   }
   public function sessionProgramme()
{
    return $this->belongsTo(SessionProgramme::class);
}

      public function requests()
      {
         return $this->hasMany(AttendanceRequest::class, 'company_id', 'company_id');     
      }
      

}
