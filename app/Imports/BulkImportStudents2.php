<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;

class BulkImportStudents2 implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        dd($row);
        // return new Student([
        //     'first_name' => $row['first_name'],
        //     'middle_name' => $row['middle_name'],
        //     'last_name' => $row['last_name'],
        //     'gender' => $row['gender'],
        //     'phone' => $row['phone'],
        //     'nin' => $row['nin'],
        //     'dob' => $row['dob'],
        //     'home_region'=> $row['home_region'],
        //     'company' => $row['company'],
        //     'platoon' => $row['platoon'],
        //     'education_level' => $row['education_level'],
        //     'rank' => $row['rank'],
        //     'height' => $row['height'],
        //     'weight' => $row['weight'],
        // ]);
    }
}
