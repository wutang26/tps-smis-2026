<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SafariStudent extends Model
{
    protected $fillable = [
        'student_id',
        'safari_type_id',
        'description',
        'previous_beat_status',
        'current_beat_status',
        'created_by'
    ];

    public function student(){
        return $this->belongsTo(Student::class);
    }
}
