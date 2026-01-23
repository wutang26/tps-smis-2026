<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkExperience extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'institution',
        'address',
        'job_title',
        //'position', 
        'duties',
        'supervisor_name',
        'supervisor_phone_number',
        'supervisor_address',
        'start_date',
        'end_date',
    ];

    /**
     * Relationship with User model.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
