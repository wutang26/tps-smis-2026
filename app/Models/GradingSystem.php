<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradingSystem extends Model
{ 
    protected $fillable = ['system_name', 'description'];

    public function gradeMappings()
    {
        return $this->hasMany(GradeMapping::class);
    }
}
