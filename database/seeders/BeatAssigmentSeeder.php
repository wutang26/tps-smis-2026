<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Student;
use Carbon\Carbon;          // ← THIS IS IMPORTANT
use App\Models\BeatAssignmentLog;

class BeatAssigmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run()
    {
        // Get all students
        $students = Student::all();

        if($students->isEmpty()){
            $this->command->info("No students found! Seed students first.");
            return;
        }

        // Define date range to simulate (last 7 days)
        $dates = collect(range(0,6))->map(fn($d) => Carbon::now()->subDays($d)->format('Y-m-d'));

        $reasons = ['Strict', 'Dynamic', 'Emergency'];

        $logs = [];

        foreach($dates as $date){
            foreach($students as $student){

                // Randomly decide if student has a beat today (70% chance)
                if(rand(1,100) <= 70){

                    // Randomly assign reason
                    $reason = $reasons[array_rand($reasons)];

                    $logs[] = [
                        'student_id'     => $student->id,
                        'date'           => $date,
                        'reason'         => $reason,
                        'beat_round'     => rand(1,3),
                        'guard_area_id'  => rand(1,5), // assuming you have 5 guard areas
                        'patrol_area_id' => null, // leave null or random
                        'last_assigned_at' => Carbon::now()->subDays(rand(0,10)),
                        'created_at'     => now(),
                        'updated_at'     => now(),
                    ];
                }
            }
        }

        // Insert in chunks for performance
        foreach(array_chunk($logs, 500) as $chunk){
            BeatAssignmentLog::insert($chunk);
        }

        $this->command->info("Seeded ".count($logs)." BeatAssignmentLog entries successfully!");
    }
}
