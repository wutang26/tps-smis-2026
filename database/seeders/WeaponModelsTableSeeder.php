<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WeaponModel;
use App\Models\WeaponType;
use App\Models\WeaponCategory;

class WeaponModelsTableSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure categories exist
        $firearm   = WeaponCategory::firstOrCreate(['name' => 'Firearm'], ['description' => 'Guns and related weapons']);
        $explosive = WeaponCategory::firstOrCreate(['name' => 'Explosive'], ['description' => 'Grenades, mines, bombs']);
        $ammo      = WeaponCategory::firstOrCreate(['name' => 'Ammunition'], ['description' => 'Bullets, shells, magazines']);

        // Ensure weapon types exist
        $rifle   = WeaponType::firstOrCreate(['name' => 'Rifle'], ['description' => 'Long guns like AK-47, M16']);
        $pistol  = WeaponType::firstOrCreate(['name' => 'Pistol'], ['description' => 'Handguns like Glock']);
        $smg     = WeaponType::firstOrCreate(['name' => 'SMG'], ['description' => 'Submachine guns']);
        $lmg     = WeaponType::firstOrCreate(['name' => 'LMG'], ['description' => 'Light machine guns']);
        $shotgun = WeaponType::firstOrCreate(['name' => 'Shotgun'], ['description' => 'Close range weapons']);

        // Define default models
$models = [
    ['name' => 'AK-47',        'weapon_type_id' => $rifle->id,   'weapon_category_id' => $firearm->id, 'description' => 'A reliable Soviet-era assault rifle known for its durability.'],
    ['name' => 'M16',          'weapon_type_id' => $rifle->id,   'weapon_category_id' => $firearm->id, 'description' => 'A standard-issue American military rifle, accurate and lightweight.'],
    ['name' => 'Glock 17',     'weapon_type_id' => $pistol->id,  'weapon_category_id' => $firearm->id, 'description' => 'A widely-used semi-automatic pistol with high capacity and reliability.'],
    ['name' => 'Beretta M9',   'weapon_type_id' => $pistol->id,  'weapon_category_id' => $firearm->id, 'description' => 'Standard sidearm of the U.S. military for decades, chambered in 9mm.'],
    ['name' => 'MP5',          'weapon_type_id' => $smg->id,     'weapon_category_id' => $firearm->id, 'description' => 'A compact 9mm submachine gun, used by special forces and police.'],
    ['name' => 'Uzi',          'weapon_type_id' => $smg->id,     'weapon_category_id' => $firearm->id, 'description' => 'An Israeli submachine gun known for its compact design and ease of use.'],
    ['name' => 'M249 SAW',     'weapon_type_id' => $lmg->id,     'weapon_category_id' => $firearm->id, 'description' => 'A light machine gun providing sustained automatic fire for infantry units.'],
    ['name' => 'PKM',          'weapon_type_id' => $lmg->id,     'weapon_category_id' => $firearm->id, 'description' => 'A Russian general-purpose machine gun chambered in 7.62mm.'],
    ['name' => 'Remington 870','weapon_type_id' => $shotgun->id, 'weapon_category_id' => $firearm->id, 'description' => 'A pump-action shotgun widely used by law enforcement and hunters.'],
    ['name' => 'Mossberg 500', 'weapon_type_id' => $shotgun->id, 'weapon_category_id' => $firearm->id, 'description' => 'A versatile pump-action shotgun known for reliability and customization.'],

    // Explosives
    ['name' => 'Hand Grenade', 'weapon_type_id' => null, 'weapon_category_id' => $explosive->id, 'description' => 'A throwable explosive device designed for short-range combat.'],
    ['name' => 'Claymore Mine','weapon_type_id' => null, 'weapon_category_id' => $explosive->id, 'description' => 'A directional anti-personnel mine that projects shrapnel forward.'],
    ['name' => 'C4 Explosive', 'weapon_type_id' => null, 'weapon_category_id' => $explosive->id, 'description' => 'A plastic explosive used for demolition, known for its stability and power.'],

    // Ammunition
    ['name' => '7.62mm Rounds','weapon_type_id' => null, 'weapon_category_id' => $ammo->id, 'description' => 'Ammunition commonly used in battle rifles and light machine guns.'],
    ['name' => '9mm Rounds',   'weapon_type_id' => null, 'weapon_category_id' => $ammo->id, 'description' => 'Standard pistol and submachine gun ammunition used worldwide.'],
    ['name' => '5.56mm Rounds','weapon_type_id' => null, 'weapon_category_id' => $ammo->id, 'description' => 'Lightweight rifle ammunition used in assault rifles like the M16.'],
];


        foreach ($models as $model) {
WeaponModel::updateOrCreate(
    ['name' => $model['name']],
    [
        'description'       => $model['description'] ?? '',
        'weapon_type_id'    => $model['weapon_type_id'],
    ]
);

        }
    }
}
