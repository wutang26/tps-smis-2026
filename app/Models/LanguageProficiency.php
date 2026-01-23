<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanguageProficiency extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'language',
        'speak',
        'write',
        'read',
    ];

    /**
     * Relationship to User model.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

