<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeatType extends Model
{
    protected $fillable =[
        "name",
        "description"
    ];

    public function area(){
        return $this->hasMany(Area::class);
    }

    public function beats(){
        return $this->hasMany(Beat::class,'beatType_id', 'id');
    }
}
