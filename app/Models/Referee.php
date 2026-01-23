<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'referee_fullname',
        'title',
        'organization',
        'email_address',
        'phone_number',
        'address',
    ];

    /**
     * Relationship with User model.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
