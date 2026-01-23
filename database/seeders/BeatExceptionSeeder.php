<?php

namespace Database\Seeders;
use App\Models\BeatException;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BeatExceptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BeatException::create([
            "name"=> "Female only",
            "description"=>"Female only",
        ]);
        BeatException::create([
            "name"=> "Male only",
            "description"=>"Male only",
        ]);
        BeatException::create([
            "name"=> "Both but not in Pair",
            "description"=>"Both genders but not in Pairs",
        ]);
    }
}
