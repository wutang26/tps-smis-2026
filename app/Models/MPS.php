<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MPS extends Model
{
    protected $fillable = [
        'added_by',
        'student_id',
        'arrested_at',
        'description',
        'previous_beat_status'
    ];
    protected $casts = [
        "arrested_at" => "datetime",
    ];
    public function student(){
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }
    public function staff(){
        return $this->belongsTo(User::class, 'added_by', 'id');
    }
}
