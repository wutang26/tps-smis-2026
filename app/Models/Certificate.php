<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
        'certificate_name',
        'description',
        'background_image',
        'student_photo',
        'status',
        'created_by',
        'updated_by'
    ];
}
