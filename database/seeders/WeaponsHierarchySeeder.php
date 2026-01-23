<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\WeaponType;
use App\Models\WeaponModel;

class WeaponsHierarchySeeder extends Seeder
{
    public function run()
    {
       

        // 1️⃣ Categories
        $categories = [
            'Firearm',
            'Explosive',
            'Ammunition',
        ];

        $categoryIds = [];
        foreach ($categories as $cat) {
            $categoryIds[$cat] = Category::create(['name' => $cat])->id;
        }

        // 2️⃣ Weapon Types linked to categories
        $types = [
            ['name' => 'Rifle',     'category' => 'Firearm'],
            ['name' => 'Pistol',    'category' => 'Firearm'],
            ['name' => 'SMG',       'category' => 'Firearm'],
            ['name' => 'LMG',       'category' => 'Firearm'],
            ['name' => 'Grenade',   'category' => 'Explosive'],
            ['name' => 'C4',        'category' => 'Explosive'],
            ['name' => '9mm Ammo',  'category' => 'Ammunition'],
            ['name' => '7.62mm Ammo','category' => 'Ammunition'],
        ];

        $typeIds = [];
        foreach ($types as $type) {
            $typeIds[$type['name']] = WeaponType::create([
                'name'        => $type['name'],
                'category_id' => $categoryIds[$type['category']],
            ])->id;
        }

        // 3️⃣ Weapon Models linked to Weapon Types
        $models = [
            // Rifles
            ['name' => 'AK-47',   'type' => 'Rifle'],
            ['name' => 'M16',     'type' => 'Rifle'],
            // Pistols
            ['name' => 'Glock 17','type' => 'Pistol'],
            ['name' => 'Beretta M9','type' => 'Pistol'],
            // SMGs
            ['name' => 'MP5',     'type' => 'SMG'],
            // LMGs
            ['name' => 'PKM',     'type' => 'LMG'],
            // Explosives
            ['name' => 'Frag Grenade','type' => 'Grenade'],
            ['name' => 'C4 Charge',  'type' => 'C4'],
            // Ammunition
            ['name' => '9mm FMJ', 'type' => '9mm Ammo'],
            ['name' => '7.62mm NATO', 'type' => '7.62mm Ammo'],
        ];

        foreach ($models as $model) {
            WeaponModel::create([
                'name'           => $model['name'],
                'weapon_type_id' => $typeIds[$model['type']],
                'category_id'    => WeaponType::find($typeIds[$model['type']])->category_id,
            ]);
        }
    }
}
