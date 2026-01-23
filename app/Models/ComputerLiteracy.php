<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComputerLiteracy extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'skill',
        'proficiency',
        'certifications',
    ];

    /**
     * Relationship with User model.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
