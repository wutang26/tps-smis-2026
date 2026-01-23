<?php

namespace Database\Seeders;
use App\Models\Area;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Area::create([
            "name"=> "MPS",
            "added_by"=>"1",
            "company_id"=>1,
            "campus_id"=> 1,
        ]);

        Area::create([
            "name"=> "ATM",
            "added_by"=>"1",
            "company_id"=>2,
            "campus_id"=> 1,
        ]);

        Area::create([
            "name"=> "COMMANDANT",
            "added_by"=>"1",
            "company_id"=>1,
            "campus_id"=> 1,
        ]);

        Area::create([
            "name"=> "ASSEMBLY HOLE",
            "added_by"=>"1",
            "company_id"=>1,
            "campus_id"=> 1,
        ]);

        Area::create([
            "name"=> "LOWER GATE",
            "added_by"=>"1",
            "company_id"=>1,
            "campus_id"=> 1,
        ]);

        Area::create([
            "name"=> "MAIN GATE",
            "added_by"=>"1",
            "company_id"=>1,
            "campus_id"=> 1,
        ]);

        Area::create([
            "name"=> "TANK LA MAJI",
            "added_by"=>"1",
            "company_id"=>3,
            "campus_id"=> 1,
        ]);

        Area::create([
            "name"=> "SOA HOUSE",
            "added_by"=>"1",
            "company_id"=>1,
            "campus_id"=> 1,
        ]);
    }
}
