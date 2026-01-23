<?php

namespace Database\Seeders;
use App\Models\BeatTimeException;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BeatTimeExceptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BeatTimeException::create([
            "name"=> "Morning",
            "time_range"=>"06:00-12:00",
        ]);
        BeatTimeException::create([
            "name"=> "Afternoon",
            "time_range"=>"12:00-18:00",
        ]);
        BeatTimeException::create([
            "name"=> "Night",
            "time_range"=>"18:00-00:00",
        ]);
        BeatTimeException::create([
            "name"=> "Mid-Night",
            "time_range"=>"00:00-06:00",
        ]);

    }
}
