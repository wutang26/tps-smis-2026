<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\NotificationAudience;

class NotificationAudienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        NotificationAudience::create([
            'name' => 'All',
            'description'=> "Notification for all users.",
            'created_by' => 1
        ]);
    }
}
