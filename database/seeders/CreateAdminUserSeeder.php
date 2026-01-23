<?php
  
namespace Database\Seeders;
  
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
  
class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        // Create admin user
        $user = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Beni Bakari',
                'password' => bcrypt('12345678')
            ]
        );

        // Create admin role
        $role = Role::firstOrCreate(['name' => 'Admin']);

        //  Give all existing permissions to this role
        $permissions = Permission::all(); // fetch all permissions
        $role->syncPermissions($permissions);

        // Assign role to user
        $user->assignRole($role);

        $this->command->info("Admin user created with all permissions.");
    }
}
