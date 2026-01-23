<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PatientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
  // Example seed data
  
  DB::table('patients')->insert([
    [
        'student_id' => 1,
        'staff_id' => 1,
        'rest_days' => 5,
        'doctor_comment' => 'anaumwa sana.',
        'excuse_type' => 'excuse duty',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'student_id' => 2,
        'staff_id' => 2,
        'rest_days' => 3,
        'doctor_comment' => 'alazwe.',
        'excuse_type' => 'light duty',
        'created_at' => now(),
        'updated_at' => now(),
    ],
   
]);
}
}
