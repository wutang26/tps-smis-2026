<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherOnDuty extends Model
{
    protected $fillable = [
        'user_id',
        'company_id',
        'start_date',
    ];

public function user()
{
    return $this->belongsTo(User::class);
}

public function staff()
{
    return $this->hasOneThrough(
        Staff::class,   // Final target model
        User::class,    // Intermediate model
        'id',           // Foreign key on User (User.id = Staff.user_id)
        'user_id',      // Foreign key on Staff (Staff.user_id)
        'user_id',      // Local key on TeacherOnDuty
        'id'            // Local key on User
    );
}

public function company(){
    return $this->belongsTo(Company::class);
}
}
