<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeatTimeException extends Model
{
    protected $fillable = [
        "name",
        "time_range",
    ];
}
