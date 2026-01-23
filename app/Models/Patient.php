<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'excuse_type_id',
        'rest_days',
        'doctor_comment',
       
        'company_id',
        'platoon',
        'status', // Workflow status: pending, approved, rejected, treated
        'receptionist_comment', // Comments from the receptionist
        'updated_by', // User ID of the last person who updated the record
    ];

    /**
     * Define the relationship to the Student model.
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
    
    /**
     * Define the relationship to the User model for tracking updates.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
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

    /**
     * Set default attributes for new records.
     */
    protected static function booted()
    {
        static::creating(function ($patient) {
            $patient->status = $patient->status ?? 'pending';
        });
    }


//     public function excuse_type()
// {
//     return $this->belongsTo(ExcuseType::class, 'excuse_type_id');
// }



public function excuseType()
{
    return $this->belongsTo(ExcuseType::class, 'excuse_type_id');
}

public function getExcuseTypeNameAttribute()
{
    return $this->excuseType->name ?? null;
}


}

