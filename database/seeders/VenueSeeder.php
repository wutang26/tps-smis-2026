<?php
use Illuminate\Database\Seeder;
use App\Models\Venue;

class VenueSeeder extends Seeder
{
    public function run()
    {
        Venue::insert([
            ['name' => 'Lecture Room 103', 'location' => 'Academic Block'],
            ['name' => 'Lecture Room 102', 'location' => 'Academic Block'],
            ['name' => 'Assembly hall', 'location' => 'Uwanja wa damu'],
        ]);
    }
}
