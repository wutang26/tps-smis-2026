<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeatException extends Model
{
    protected $fillable = [
        "name",
        "description",
    ];
}
