<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_qualification_id',
        'file_path',
        'attachment_type',
    ];

    /**
     * Define the relationship with the AcademicQualification model.
     */
    public function academicQualification()
    {
        return $this->belongsTo(AcademicQualification::class);
    }
}

