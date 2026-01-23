<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuardArea extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'company_id', 'campus_id', 'added_by', 'beat_exception_ids', 'beat_time_exception_ids', 'number_of_guards'
    ];

    // protected $casts = [
    //     'beat_exception_ids' => 'array',
    //     'beat_time_exception_ids' => 'array',
    // ];

    // ✅ Relationship with Beats
    public function beats()
    {
        return $this->hasMany(Beat::class, 'guardArea_id');
    }
    public function beatExceptions(){
        return $this->hasMany(BeatException::class);
    }
    public function beatTimeExceptions(){
        return $this->hasMany(BeatTimeException::class);
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by','id');
    }
    
}
