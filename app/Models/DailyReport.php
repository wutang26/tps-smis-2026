<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_date',
        'reported_by',
        'company',
        'repeated_cases',
        'overloaded_cases',
        'last_assigned_date',
        'sick_student_names',
        'sick_student_platoon',
        'sick_student_company',
        'vitengo_cases',
        'emergency_cases',
        'challenges',
        'suggestions',
    ];

    protected $casts = [
        'repeated_cases'        => 'array',
        'overloaded_cases'      => 'array',
        'last_assigned_date'    => 'array',
        'sick_student_names'    => 'array',
        'sick_student_platoon'  => 'array',
        'company'                => 'array',
        'vitengo_cases'         => 'array',
        'emergency_cases'       => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
}