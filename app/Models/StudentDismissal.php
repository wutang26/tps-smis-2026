<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentDismissal extends Model
{
    protected $fillable = ['student_id', 'reason_id', 'custom_reason', 'dismissed_at'];
}
