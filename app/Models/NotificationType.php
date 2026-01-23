<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationType extends Model
{
    protected $table = 'notification_types';
    protected $fillable = [
        'name','description','created_by',
    ];
}
