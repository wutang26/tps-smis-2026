<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NextOfKin extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    //protected $table = 'next_of_kin';
    protected $casts = [
    'next_of_kin' => 'array',
];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nextofkinFullname',
        'nextofkinRelationship',
        'nextofkinPhoneNumber',
        'nextofkinEmail',
        'nextofkinPhysicalAddress',
        'staff_id',
        'student_id',
    ];

    /**
     * Get the staff member associated with the next of kin.
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Get the student associated with the next of kin.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}