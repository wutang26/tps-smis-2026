<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicQualification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'education_level',
        'programme_name',
        'place',
        'institution_name',
        'date_from',
        'date_to',
        'grade',
    ];

    /**
     * Define the relationship with the User model.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define the relationship with the AcademicAttachment model.
     */
    public function attachments()
    {
        return $this->hasMany(AcademicAttachment::class);
    }
}
