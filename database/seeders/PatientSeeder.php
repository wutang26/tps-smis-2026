<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;

class PatientSeeder extends Seeder
{
    public function run()
    {
        // Create multiple patient records manually
        Patient::create([
            'first_name' => 'mwambingu',
            'last_name' => '123456789',
            'company' => 'A',
            'platoon' => '10',
        ]);

        Patient::create([
            'first_name' => 'Jane ',
            'last_name' => '987654321',
            'company' => 'B',
            'platoon' => '12',
        ]);

        Patient::create([
            'first_name' => 'BENI BAKARI',
            'last_name' => '555444333',
            'company' => 'HQ',
            'platoon' => '15',
        ]);

        Patient::create([
            'first_name' => 'Mary Johnson',
            'last_name' => '111223344',
            'company' => 'c',
            'platoon' => '4',
        ]);
    }
}
