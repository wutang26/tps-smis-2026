<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EducationLevel;

class EducationLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EducationLevel::create([
            'added_by' => '1',
            'name' => 'Primary',
        ]);

        EducationLevel::create([
            'added_by' => '1',
            'name' => 'O-Level',
        ]);

        EducationLevel::create([
            'added_by' => '1',
            'name' => 'A-Level',
        ]);

        EducationLevel::create([
            'added_by' => '1',
            'name' => 'College',
        ]);
    }
}
