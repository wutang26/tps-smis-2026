<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeatRound extends Model
{
    protected $fillable = [
        'company_id', 'current_round'
    ];

}
