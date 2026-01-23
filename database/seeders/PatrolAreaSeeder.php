<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PatrolArea;
class PatrolAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PatrolArea::create([
            "company_id" => 1,
            "start_area"=> 1,
            "number_of_guards" => 3,
            "end_area"=> 2
        ]);

        PatrolArea::create([
            "company_id" => 2,
            "start_area" => 3,
            "number_of_guards" => 3,
            "end_area" => 4
        ]);
    }
}
