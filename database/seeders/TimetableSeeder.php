<?php

namespace Database\Seeders;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Activity;
use App\Models\Venue;
use App\Models\Instructor;

class TimetableSeeder extends Seeder
{
    public function run()
    {
        // Create sample activities
        $activities = ['Criminal Law', 'Police duties', 'Drill', 'Criminal Procedure', 'Communication skills'];
        foreach ($activities as $activity) {
            Activity::firstOrCreate(['name' => $activity]);
        }

        // Create sample venues
        $venues = ['Assembly hall', 'Uwanja wa Damu', 'Mess B- COY', 'ABC 102', 'ABC 104'];
        foreach ($venues as $venue) {
            Venue::firstOrCreate(['name' => $venue]);
        }

        // Create sample instructors
        $instructors = ['INSP. MAPUNDA', 'BALTAZARY', 'Dr. SAYUNI', 'SGT.MHINA', 'Prof. ADAM'];
        foreach ($instructors as $instructor) {
            Instructor::firstOrCreate(['name' => $instructor]);
        }
    }
}
