<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            PermissionTableSeeder::class,
            //WeaponCategorySeeder::class,
            //WeaponTypesTableSeeder::class,
            //WeaponModelsTableSeeder::class,
            //WeaponOwnershipTypeSeeder::class,
            // CreateAdminUserSeeder::class,
            // BeatTypeSeeder::class,
            // AreaSeeder::class,
            // VitengoSeeder::class,
            // CompanySeeder::class,
            // PlatoonSeeder::class,
            // AttendenceTypeSeeder::class,
            // AttendenceSeeder::class,
            // GradingSystemsTableSeeder::class,
            // PatientsTableSeeder::class,
            // SafariTypeSeeder::class,
            // EducationLevelSeeder::class
            // NotificationAudienceSeeder::class,
            // NotificationTypeSeeder::class
        ]);

        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
