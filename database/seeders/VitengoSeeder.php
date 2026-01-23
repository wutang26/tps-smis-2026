<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vitengo;
class VitengoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Vitengo::create([
            "name" => "ICT",
            "description"=> "Information Communication and Technology."
        ]);

        Vitengo::create([
            "name" => "Ujenzi",
            "description"=> "Taking care of kujenga."
        ]);

        Vitengo::create([
            "name" => "Hospital",
            "description"=> "Diagnosis and treatment."
        ]);

        Vitengo::create([
            "name" => "Farm",
            "description"=> "Taking care of the farm."
        ]);

        Vitengo::create([
            "name" => "Usafi",
            "description"=> "Cleaning of the campus."
        ]);

        Vitengo::create([
            "name" => "Mess",
            "description"=> "Preparation and Cooking."
        ]);

        Vitengo::create([
            "name" => "Ushoni",
            "description"=> "..."
        ]);

        Vitengo::create([
            "name" => "Masijala",
            "description"=> "..."
        ]);

        Vitengo::create([
            "name" => "Others",
            "description"=> "..."
        ]);
    }
}
