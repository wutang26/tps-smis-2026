<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeatReserve extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'student_id',
        'beat_date',
        'beat_round'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function replacement_student()
    {
        return $this->belongsTo(Student::class, 'replacement_student_id', 'id');
    }
}
