<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $fillable = [
        'staff_id','name','education_level_id','admission_year','graduation_year','duration','country', 'award','village','region','ward','district','venue',
    ];

    public function education_level()
    {
        return $this->belongsTo(EducationLevel::class);
    }
}
