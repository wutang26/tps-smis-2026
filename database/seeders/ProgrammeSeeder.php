<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Programme;

class ProgrammeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = Programme::create([
            'programmeName' => 'Basic Technician Certificate in Policing and Security Management',
            'abbreviation' => 'PST',
            'duration' => '1',
            'department_id' => '1',
            'studyLevel_id' => '1'
        ]);
    }
}
