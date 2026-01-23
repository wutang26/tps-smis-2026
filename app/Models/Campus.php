<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campus extends Model
{
    protected $fillable = ['campusName', 'description','created_by','updated_by']; 
}
