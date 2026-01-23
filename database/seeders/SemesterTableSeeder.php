<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class SemesterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('semesters')->insert([ 
            ['semester_name' => 'Semester One'], 
            ['semester_name' => 'Semester Two'] 
        ]);
    }
}
