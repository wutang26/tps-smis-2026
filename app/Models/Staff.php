<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Staff extends Model
{

    use HasRoles; 

    protected $fillable = [
            'forceNumber',
            'rank',
            'role',
            'nin',
            'firstName',
            'middleName',
            'lastName',
            'gender',
            'DoB',
            'maritalStatus',
            'fatherParticulars',
            'religion',
            'tribe',
            'phoneNumber',
            'email',
            'currentAddress',
            'permanentAddress',
            'department_id',
            'designation',
            'educationLevel',
            'contractType',
            'joiningDate',
            'location',
            'user_id',
            'created_by',
            'updated_by',
            'company_id'
        ];


        public function department() 
        { 
            return $this->belongsTo(Department::class); 

        }
        public function company() 
        { 
            return $this->belongsTo(Company::class); 
        }
        
        public function user()
        {
            return $this->belongsTo(User::class);
        }

        public function referees()
        {
            return $this->hasManyThrough(
                Referee::class, // Final model
                User::class, // Intermediate model
                'id', // Foreign key on users table
                'user_id', // Foreign key on referees table
                'user_id', // Local key in staff table
                'id' // Local key in users table
            );
        }

        // Function to check roles
    public function hasRole($roles)
    {
        if (is_array($roles)) {
            return in_array(strtolower($this->role), array_map('strtolower', $roles));
        }
        return strtolower($this->role) === strtolower($roles);
    }

    public function schools()
    {
        return $this->hasMany(School::class);
    }
    public function work_experiences()
    {
        return $this->hasMany(WorkExperience::class,'user_id');
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class)
            ->withPivot('assigned_at', 'start_time', 'end_time', 'is_active', 'region', 'district')
            ->withTimestamps();
    }

    public function currentLoad()
    {
        return $this->tasks()->wherePivot('is_active', true)->count();
    }

}
