<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatrolArea extends Model
{
    
    // use HasFactory;
    protected $fillable =[
        'number_of_guards',
        'company_id',
        "added_by",
        "campus_id",
        'start_area',
        'end_area',
        'beat_exception_ids',
        'beat_time_exception_ids'
    ];
    // public function beats(){
    //     return $this->belongsTo(Beat::class,'patrolArea_id', 'id');
    // }

    public function beatExceptions(){
        return $this->hasMany(BeatException::class);
    }
    public function beatTimeExceptions(){
        return $this->hasMany(BeatTimeException::class);
    }

    public function beats()
    {
        return $this->hasMany(Beat::class, 'patrolArea_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }
    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by','id');
    }
}
