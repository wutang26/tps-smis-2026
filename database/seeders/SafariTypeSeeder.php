<?php

namespace Database\Seeders;

use App\Models\SafariType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SafariTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SafariType::create([
            'name' => "Vyeti",
            'description' => ".."
        ]);

        SafariType::create([
            'name' => "Others",
            'description' => ".."
        ]); 
    }
}
