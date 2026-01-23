<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MPSVisitor extends Model
{
    protected $fillable=[
        'student_id',
        'visited_at',
        'names',
        'phone',
        'relationship',
        'welcomed_by'
    ];

    protected $casts = [
        "visited_at" => "datetime",
    ];
    public function student(){
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }
    public function staff(){
        return $this->belongsTo(User::class, 'welcomed_by', 'id');
    }
}
