<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class AssignDoctorSeeder extends Seeder
{
    public function run()
    {
        // Ensure permissions exist
        $permissions = [
            'hospital-list',
            'hospital-update', // <-- Fix: Added missing permission
            'hospital-edit'
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Assign permissions to the Doctor role
        $doctorRole = Role::firstOrCreate(['name' => 'kisalo james kisalo']);
        $doctorRole->syncPermissions($permissions);

        // Assign role to the doctor user
        $doctorUser = User::where('email', 'kisalo@gmail.com')->first();
        if ($doctorUser) {
            $doctorUser->assignRole($doctorRole);
        }
    }
}
