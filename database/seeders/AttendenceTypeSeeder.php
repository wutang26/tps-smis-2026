<?php

namespace Database\Seeders;
use App\Models\AttendenceType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttendenceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AttendenceType::create([
            'name'=>'Morning',
        ]);

        AttendenceType::create([
            'name'=>'Master Parade',
        ]);

        AttendenceType::create([
            'name'=>'Night',
        ]);

        AttendenceType::create([
            'name'=>'Flag',
        ]);
    }
}
