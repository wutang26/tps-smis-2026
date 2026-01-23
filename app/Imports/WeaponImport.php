<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

use Maatwebsite\Excel\Concerns\ToModel;
use Exception;

class WeaponImport implements ToCollection, ToModel
{
        private $num = 0;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        //
    }

        public function model(array $row)
    {
        $this->num++;
    
    }
}
