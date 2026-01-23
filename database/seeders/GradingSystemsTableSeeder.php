<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class GradingSystemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('grading_systems')->insert([
            ['system_name' => 'NACTE Grades Certificate', 'description' => 'Grades assigned based on NACTE standards NTA LEVEL 4 & 5'],
            ['system_name' => 'NACTE Grades Diploma', 'description' => 'Grades assigned based on NACTE standards NTA LEVEL 6'],
            ['system_name' => 'NACTE GPA', 'description' => 'Grades assigned based on NACTE GPA scale (e.g., 4.0 scale)'],
            ['system_name' => 'TCU Grades Bachelor', 'description' => 'Grades assigned for Bachelor degrees TCU (A, B+, B, C, etc.)'],
        ]);
    }
}
