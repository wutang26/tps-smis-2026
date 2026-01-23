<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationAudience extends Model
{
        protected $fillable = [
        'name','description','created_by',
    ];
}
