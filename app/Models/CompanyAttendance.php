<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyAttendance extends Model
{
    protected $fillable = [
        'company_id', 'date', 'attendenceType_id',
    ];
}
