<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BeatType;
class BeatTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BeatType::create([
            'name' => "Guards",
            'description' =>""
        ]);

        BeatType::create([
            'name' => "Patrol",
            'description' =>""
        ]);
    }
}
