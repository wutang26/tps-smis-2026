<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeaponType extends Model
{
    use HasFactory;

    protected $fillable = ['name','description', 'weapon_category_id'];

    

    public function models()
    {
        return $this->hasMany(WeaponModel::class);
    }
    public function category()
{
    return $this->belongsTo(WeaponCategory::class, 'weapon_category_id');
}

}
