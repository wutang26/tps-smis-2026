<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
        protected $fillable = [
        'session_programme_id',
        'status',
        'published_by'
        ];


    public function publisher()
    {
        return $this->belongsTo(User::class,'published_by','id');
    }

    public function session()
    {
        return $this->belongsTo(SessionProgramme::class, 'session_programme_id', 'id');
    }

    public function student_posts()
    {
        return $this->hasMany(StudentPost::class);
    }
}
