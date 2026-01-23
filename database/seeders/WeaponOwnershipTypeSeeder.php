<?php

namespace Database\Seeders;
use App\Models\WeaponOwnershipType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WeaponOwnershipTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'TPS-Moshi', 
                'description' => 'Owned by an TPS-Moshi'],
            [
                'name' => 'Civilian',
                'description' => 'Weapons owned by non-military, non-police individuals.',
            ],
            [
                'name' => 'Private',
                'description' => 'Weapons privately owned, not belonging to the government or public institutions.',
            ],
        ];

        foreach ($types as $type) {
            WeaponOwnershipType::updateOrCreate(
                ['name' => $type['name']],
                $type
            );
        }
    }
}
