<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceRequest extends Model
{
    protected $fillable = [ 'id','date','attendenceType_id','company_id','requested_by','reason'];
    
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

        public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
       public function type()
   {
      return $this->belongsTo(AttendenceType::class, 'attendenceType_id', 'id');
   }
}
