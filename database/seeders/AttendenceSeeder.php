<?php

namespace Database\Seeders;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];
        $startDate = Carbon::now('Africa/Dar_es_Salaam')->subWeeks(12);

        for ($day = 0; $day < 12 * 7; $day++) { // 12 weeks * 7 days
            $currentDate = $startDate->copy()->addDays($day);

            $data[] = [
                'platoon_id' => 15,
                'attendenceType_id' => 1,
                'present' => rand(20, 30),
                'sentry' => rand(1, 5),
                'absent' => rand(1, 3),
                'adm' => rand(0, 2),
                'safari' => rand(0, 2),
                'mess' => rand(0, 2),
                'off' => rand(0, 2),
                'female' => rand(10, 15),
                'male' => rand(10, 15),
                'total' => rand(20, 30),
                'session_programme_id' => 1,
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ];
        }

        DB::table('attendences')->insert($data);
    }
}
