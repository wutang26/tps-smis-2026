<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeaponCategory extends Model
{
        protected $fillable = ['name','description'];

    /**
     * A category has many weapon types.
     */
    public function types()
    {
        return $this->hasMany(WeaponType::class, 'weapon_category_id');
    }

    /**
     * A category has many weapon models (through types).
     */
    public function models()
    {
        return $this->hasMany(WeaponModel::class, 'weapon_category_id', 'weapon_type_id');
    }
    public function groupedModelsForSelect()
{
    $grouped = [];

    foreach ($this->types as $type) {
        foreach ($type->models as $model) {
            $grouped[$type->name][] = $model;
        }
    }

    return $grouped;
}

}
