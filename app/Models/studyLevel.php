<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Programme;

class studyLevel extends Model
{
    protected $fillable = ['studyLevelName'];
    
    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }
    public function students()
    {
        return $this->hasMany(Student::class);
    }

}
