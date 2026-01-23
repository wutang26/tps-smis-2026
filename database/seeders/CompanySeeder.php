<?php

namespace Database\Seeders;
use App\Models\cOMPANY;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::create([
            'campus_id' => 1,
            'name'=>'HQ'
        ]);
        Company::create([
            'campus_id' => 1,
            'name'=>'A'
        ]);
        Company::create([
            'campus_id' => 1,
            'name'=>'B'
        ]);
        Company::create([
            'campus_id' => 1,
            'name'=>'C'
        ]);
    }
}
