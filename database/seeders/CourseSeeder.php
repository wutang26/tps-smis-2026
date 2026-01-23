<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [ 
            ['courseCode' => 'PSMU 04101', 'courseName' => 'Police Administration'], 
            ['courseCode' => 'PSMU 04102', 'courseName' => 'Criminal Law'], 
            ['courseCode' => 'PSMU 04103', 'courseName' => 'Criminal Procedure'], 
            ['courseCode' => 'PSMU 04104', 'courseName' => 'Law of Evidence'], 
            ['courseCode' => 'PSMU 04105', 'courseName' => 'Criminal Investigation'], 
            ['courseCode' => 'PSMU 04106', 'courseName' => 'Traffic Management'], 
            ['courseCode' => 'PSMU 04107', 'courseName' => 'Drills and Parade Training'], 
            ['courseCode' => 'PSMU 04108', 'courseName' => 'Disaster Management'],             ]; 
        foreach ($courses as $course) 
        { 
            Course::create($course); 
        }
    }
}