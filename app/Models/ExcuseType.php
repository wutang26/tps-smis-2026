<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExcuseType extends Model
{
    protected $fillable = ['excuseName', 'abbreviation', 'description','created_by','updated_by']; 
}

