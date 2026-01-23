<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class TimeSheet extends Model
{
    protected $table = 'time_sheet';
    protected $fillable =[
        'user_id',
        'description',
        'tasks',
        'hours',
        'date'
    ];

    protected $casts = [
        "date" => "date",
        'time_in' => 'datetime:H:i', // Cast 'time_in' to time format (HH:mm)
        'time_out' => 'datetime:H:i', // 
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function approvedBy(){
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }
}
