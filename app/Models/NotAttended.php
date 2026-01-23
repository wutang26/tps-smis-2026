<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotAttended extends Model
{
    protected $fillable = [
        'student_id',
        'reason',
    ];
}
