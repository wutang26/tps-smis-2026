<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentType extends Model
{
    protected $fillable = ['type_name'];

    public function courseWorks()
    {
        return $this->hasMany(CourseWork::class, 'assessment_type_id');
    }
}
