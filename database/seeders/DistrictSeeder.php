<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Region;
use App\Models\District;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run()
    {
            $data = [
                    'Arusha' => ['Arusha City', 'Arumeru', 'Karatu', 'Monduli', 'Ngorongoro'],
                    'Dar es Salaam' => ['Ilala', 'Kinondoni', 'Temeke', 'Ubungo', 'Kigamboni'],
                    'Dodoma' => ['Dodoma City', 'Bahi', 'Chamwino', 'Chemba', 'Kondoa', 'Mpwapwa'],
                    'Geita' => ['Geita', 'Bukombe', 'Chato', 'Mbogwe', 'Nyang’hwale'],
                    'Iringa' => ['Iringa', 'Kilolo', 'Mafinga'],
                    'Kagera' => ['Bukoba', 'Biharamulo', 'Karagwe', 'Kyerwa', 'Missenyi', 'Ngara'],
                    'Katavi' => ['Mpanda', 'Mlele', 'Tanganyika'],
                    'Kigoma' => ['Kigoma', 'Buhigwe', 'Kakonko', 'Kasulu', 'Kibondo', 'Uvinza'],
                    'Kilimanjaro' => ['Moshi', 'Hai', 'Rombo', 'Same', 'Mwanga', 'Siha'],
                    'Lindi' => ['Lindi', 'Kilwa', 'Liwale', 'Nachingwea', 'Ruangwa'],
                    'Manyara' => ['Babati', 'Hanang', 'Kiteto', 'Mbulu', 'Simanjiro'],
                    'Mara' => ['Musoma', 'Bunda', 'Butiama', 'Rorya', 'Serengeti', 'Tarime'],
                    'Mbeya' => ['Mbeya City', 'Mbeya Rural', 'Chunya', 'Kyela', 'Mbarali', 'Rungwe'],
                    'Morogoro' => ['Morogoro', 'Gairo', 'Kilosa', 'Kilombero', 'Malinyi', 'Mvomero', 'Ulanga'],
                    'Mtwara' => ['Mtwara', 'Masasi', 'Nanyumbu', 'Newala', 'Tandahimba'],
                    'Mwanza' => ['Ilemela', 'Nyamagana', 'Sengerema', 'Magu', 'Misungwi', 'Ukerewe'],
                    'Njombe' => ['Njombe', 'Makambako', 'Ludewa', 'Makete', 'Wanging’ombe'],
                    'Pwani' => ['Kibaha', 'Bagamoyo', 'Kisarawe', 'Mafia', 'Mkuranga', 'Rufiji'],
                    'Rukwa' => ['Sumbawanga', 'Kalambo', 'Nkasi'],
                    'Ruvuma' => ['Songea', 'Mbinga', 'Namtumbo', 'Nyasa', 'Tunduru'],
                    'Shinyanga' => ['Shinyanga', 'Kahama', 'Msalala'],
                    'Simiyu' => ['Bariadi', 'Busega', 'Itilima', 'Maswa', 'Meatu'],
                    'Singida' => ['Singida', 'Ikungi', 'Iramba', 'Manyoni', 'Mkalama'],
                    'Songwe' => ['Songwe', 'Ileje', 'Mbozi', 'Momba', 'Tunduma'],
                    'Tabora' => ['Tabora', 'Igunga', 'Kaliua', 'Nzega', 'Sikonge', 'Urambo'],
                    'Tanga' => ['Tanga', 'Handeni', 'Kilindi', 'Korogwe', 'Lushoto', 'Muheza', 'Pangani'],
                    'Kaskazini Unguja' => ['Kaskazini A', 'Kaskazini B'],
                    'Kusini Unguja' => ['Kusini A', 'Kusini B'],
                    'Mjini Magharibi' => ['Mjini', 'Magharibi'],
                    'Kaskazini Pemba' => ['Wete', 'Micheweni'],
                    'Kusini Pemba' => ['Chake Chake', 'Mkoani'],
                ];


        foreach ($data as $regionName => $districts) {
            $region = Region::where('name', $regionName)->first();

            if ($region) {
                foreach ($districts as $districtName) {
                    District::create([
                        'region_id' => $region->id,
                        'name' => $districtName,
                    ]);
                }
            }
        }
    }
}
