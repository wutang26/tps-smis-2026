<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WeaponType;
use App\Models\WeaponCategory;

class WeaponTypesTableSeeder extends Seeder
{
    public function run(): void
    {
        $weaponTypes = [
            ['name' => 'Rifle',           'description' => 'Standard military rifles',        'category_name' => 'Firearm'],
            ['name' => 'Pistol',          'description' => 'Handguns for close combat',       'category_name' => 'Firearm'],
            ['name' => 'Machine Gun',     'description' => 'Automatic firing weapons',        'category_name' => 'Firearm'],
            ['name' => 'Shotgun',         'description' => 'Close-range firearms',            'category_name' => 'Firearm'],
            ['name' => 'Grenade',         'description' => 'Explosive devices',               'category_name' => 'Explosive'],
            ['name' => 'Rocket Launcher', 'description' => 'Anti-armor weapons',              'category_name' => 'Explosive'],
            ['name' => 'Sniper Rifle',    'description' => 'Long-range precision rifles',     'category_name' => 'Firearm'],
            ['name' => 'Ammunition',      'description' => 'Bullets and shells',              'category_name' => 'Ammunition'],
        ];

        foreach ($weaponTypes as $type) {
            $category = WeaponCategory::where('name', $type['category_name'])->first();
            
            if (!$category) {
                throw new \Exception("WeaponCategory not found: " . $type['category_name']);
            }

            WeaponType::updateOrCreate(
                ['name' => $type['name']],
                [
                    'description' => $type['description'],
                    'weapon_category_id' => $category->id
                ]
            );
        }
    }
}
