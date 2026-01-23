<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Region;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
            $regions = [
                ['name' => 'Arusha', 'code' => 'AR'],
                ['name' => 'Dar es Salaam', 'code' => 'DS'],
                ['name' => 'Dodoma', 'code' => 'DO'],
                ['name' => 'Geita', 'code' => 'GE'],
                ['name' => 'Iringa', 'code' => 'IR'],
                ['name' => 'Kagera', 'code' => 'KA'],
                ['name' => 'Katavi', 'code' => 'KV'],
                ['name' => 'Kigoma', 'code' => 'KI'],
                ['name' => 'Kilimanjaro', 'code' => 'KL'],
                ['name' => 'Lindi', 'code' => 'LI'],
                ['name' => 'Manyara', 'code' => 'MY'],
                ['name' => 'Mara', 'code' => 'MR'],
                ['name' => 'Mbeya', 'code' => 'MB'],
                ['name' => 'Morogoro', 'code' => 'MO'],
                ['name' => 'Mtwara', 'code' => 'MT'],
                ['name' => 'Mwanza', 'code' => 'MW'],
                ['name' => 'Njombe', 'code' => 'NJ'],
                ['name' => 'Pwani', 'code' => 'PW'],
                ['name' => 'Rukwa', 'code' => 'RK'],
                ['name' => 'Ruvuma', 'code' => 'RV'],
                ['name' => 'Shinyanga', 'code' => 'SH'],
                ['name' => 'Simiyu', 'code' => 'SM'],
                ['name' => 'Singida', 'code' => 'SG'],
                ['name' => 'Songwe', 'code' => 'SO'],
                ['name' => 'Tabora', 'code' => 'TB'],
                ['name' => 'Tanga', 'code' => 'TN'],
                ['name' => 'Mjini Magharibi', 'code' => 'MM'],
                ['name' => 'Kaskazini Unguja', 'code' => 'UN'],
                ['name' => 'Kusini Unguja', 'code' => 'US'],
                ['name' => 'Kaskazini Pemba', 'code' => 'PN'],
                ['name' => 'Kusini Pemba', 'code' => 'PS'],
            ];
    
            foreach ($regions as $region) {
                Region::create([
                    'name' => $region['name'],
                    'code' => $region['code'],
                ]);
            }
    }

}
