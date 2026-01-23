<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesTableSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Firearm', 'description' => 'Guns and related weapons'],
            ['name' => 'Explosive', 'description' => 'Grenades, mines, bombs'],
            ['name' => 'Ammunition', 'description' => 'Bullets, shells, magazines'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['name' => $category['name']], $category);
        }
    }
}
