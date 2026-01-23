<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeatLeaderOnDuty  extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
 protected $table = 'beat_leaderon_duties';

    protected $fillable = [
        'company_id',
        'student_id',
        'beat_date'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
