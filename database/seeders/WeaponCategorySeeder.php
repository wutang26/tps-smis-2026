<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WeaponCategory;
class WeaponCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Firearm', 'description' => 'Guns and related weapons'],//1
            ['name' => 'Explosive', 'description' => 'Grenades, mines, bombs'],//2
            ['name' => 'Ammunition', 'description' => 'Bullets, shells, magazines'],//3
        ];

        foreach ($categories as $category) {
            WeaponCategory::firstOrCreate(['name' => $category['name']], $category);
        }
    }
}
