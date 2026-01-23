<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AssignSirMajorSeeder extends Seeder
{
    public function run()
    {
        // Ensure the 'Sir Major' role exists
        $role = Role::firstOrCreate(['name' => 'Sir Major']);

        // Sir Major details
        $sirMajors = [
            [
                'name' => 'Mhina James Mhina',
                'email' => 'lazarouskasika@gmail.com',
                'password' => bcrypt('mhina'),
                'company' => 'HQ',
            ],
            [
                'name' => 'Juma Haji Duni',
                'email' => 'haji@gmail.com',
                'password' => bcrypt('haji'),
                'company' => 'A',
            ],
            [
                'name' => 'beni bakari beni',
                'email' => 'bakari@gmail.com',
                'password' => bcrypt('beni'),
                'company' => 'B',
            ],
            [
                'name' => 'Janeth ayubu james',
                'email' => 'ayubu@gmail.com',
                'password' => bcrypt('ayubu'),
                'company' => 'C',
            ]
        ];

        foreach ($sirMajors as $data) {
            // Check if the user exists by email
            $user = User::updateOrCreate(
                ['email' => $data['email']], // Search condition
                [
                    'name' => $data['name'],
                    'password' => $data['password'], // Already encrypted
                    'company' => $data['company'],
                ]
            );

            // Assign the role if not already assigned
            if (!$user->hasRole('Sir Major')) {
                $user->assignRole('Sir Major');
            }
        }
    }
}
