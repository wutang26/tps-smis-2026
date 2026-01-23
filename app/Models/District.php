<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $fillable = ['region_id', 'name', 'code'];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
