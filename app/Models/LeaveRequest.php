<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'staff_id',
        'company_id',
        'platoon',
        'phone_number',
        'previous_beat_status',
        'current_beat_status',
        'location',
        'reason',
        'status',
        'start_date',
        'end_date',
        'attachments',
    ];
    

    public function student() {
        return $this->belongsTo(Student::class, 'student_id');
    }


//     public function student()
// {
//     return $this->belongsTo(Student::class);
// }

 public function staff(){
    return $this->belongsTo(Staff::class, 'staff_id');
 }
 
public function company()
{
    return $this->belongsTo(Company::class, 'company_id');
}


    /**
     * Scope for filtering by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for filtering by company and platoon.
     */
    public function scopeByCompanyAndPlatoon($query, $company_id, $platoon)
    {
        return $query->where('company_id', $company_id)->where('platoon', $platoon);
    }
}
