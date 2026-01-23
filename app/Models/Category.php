<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * A category has many weapon types.
     */
    public function types()
    {
        return $this->hasMany(WeaponType::class, 'category_id');
    }

    /**
     * A category has many weapon models (through types).
     */
    public function models()
    {
        return $this->hasMany(WeaponModel::class, 'category_id');
    }
}
