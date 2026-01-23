<?php

namespace App\Imports;

use App\Models\Staff;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UpdateStaffDetails implements ToModel, WithHeadingRow
{
    public array $errors = [];
    public array $warnings = [];

    public function __construct()
    {
        HeadingRowFormatter::default(); // scoped snake_case formatting
    }

    public function model(array $row)
    {
        $required = ['force_number', 'rank', 'first_name', 'middle_name', 'last_name', 'gender'];
        $missing = [];

        foreach ($required as $key) {
            if (empty($row[$key])) {
                $missing[] = $key;
            }
        }

        if (!empty($missing)) {
            $forceNumber = $row['force_number'] ?? 'unknown';
            $message = "⚠️ Skipped row: missing required fields (" . implode(', ', $missing) . ") for {$forceNumber}";
            $this->warnings[] = $message;
            Log::warning($message);
            return null;
        }

        $map = [
            'rank'               => 'rank',
            'nin'                => 'nin',
            'first_name'         => 'firstName',
            'middle_name'        => 'middleName',
            'last_name'          => 'lastName',
            'gender'             => 'gender',
            'date_of_birth'      => 'DoB',
            'marital'            => 'maritalStatus',
            'religion'           => 'religion',
            'origin'             => 'tribe',
            'phone'              => 'phoneNumber',
            'designation'        => 'designation',
            'next_of_kin_name'   => 'nextofkinFullName',
            'next_of_kin_phone'  => 'nextofkinPhoneNumber',
            'next_of_kin_address'=> 'nextofkinPysicalAddress',
            'next_of_kin_city'   => 'nextofkinCity',
            'next_of_kin_state'  => 'nextofkinState',
        ];

        $updateData = [];
        foreach ($map as $excelKey => $dbField) {
            if (isset($row[$excelKey]) && $row[$excelKey] !== '') {
                $updateData[$dbField] = trim($row[$excelKey]);
            }
        }

        try {
            Staff::updateOrCreate(
                ['forceNumber' => trim($row['force_number'])],
                $updateData
            );
        } catch (\Exception $e) {
            $message = "❌ Failed to update/create staff {$row['force_number']} — " . $e->getMessage();
            $this->errors[] = $message;
            Log::error($message);
        }

        return null;
    }
}