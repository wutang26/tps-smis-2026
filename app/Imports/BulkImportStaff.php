<?php

namespace App\Imports;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;

class UpdateStaffDetails implements ToCollection
{
    public array $errors = [];
    public array $warnings = [];

    public function collection(Collection $rows)
    {
        $num = 0;

        foreach ($rows as $row) {
            $num++;
            if ($num <= 5) continue; // Skip header rows

            // --- Validate required fields ---
            if (empty($row[0]) || empty($row[1]) || empty($row[2]) || empty($row[4])) {
                $this->warnings[] = "Row $num: Missing required fields (FORCE NUMBER, RANK, FIRST NAME, LAST NAME)";
                Log::warning("Row $num skipped: missing required fields");
                continue;
            }

            // --- Lookup staff by FORCE NUMBER ---
            $staff = Staff::where('forceNumber', trim($row[0]))->first();

            // --- If staff not found, create User + Staff ---
            if (!$staff) {
                try {
                    // Create User account
                    $user = new User();
                    $user->name = trim($row[2]) . " " . trim($row[3]) . " " . trim($row[4]); // First + Middle + Last
                    $user->email = empty($row[6])
                        ? strtolower(trim($row[2])) . "." . strtolower(trim($row[4])) . "@tpf.go.tz"
                        : trim($row[6]);
                    $user->password = Hash::make(strtoupper(trim($row[4]))); // Last name as password
                    $user->save();

                    // Assign default role (id = 6)
                    $user->assignRole([6]);

                    // Create Staff record linked to User
                    $staff = new Staff();
                    $staff->forceNumber   = trim($row[0]);
                    $staff->rank          = trim($row[1]);
                    $staff->firstName     = trim($row[2]);
                    $staff->middleName    = trim($row[3]);
                    $staff->lastName      = trim($row[4]);
                    $staff->gender        = trim($row[5]);
                    $staff->email         = $user->email;
                    $staff->user_id       = $user->id; // link staff to user
                    $staff->save();

                    Log::info("Row $num: Created new staff {$row[0]} with user account {$user->email}");
                } catch (\Exception $e) {
                    $this->errors[] = "Row $num: Failed to create staff/user {$row[0]} — " . $e->getMessage();
                    Log::error("Row $num create failed for {$row[0]}: " . $e->getMessage());
                }
                continue; // move to next row
            }

            // --- Build update data dynamically ---
            $map = [
                1  => 'rank',
                2  => 'firstName',
                3  => 'middleName',
                4  => 'lastName',
                5  => 'gender',
                6  => 'designation',
                7  => 'phoneNumber',
                8  => 'DoB',
                9  => 'maritalStatus',
                10 => 'bloodGroup',
                11 => 'religion',
                12 => 'tribe',
                13 => 'educationLevel',
                14 => 'nextofkinFullName',
                15 => 'nextofkinPhoneNumber',
                16 => 'nextofkinPysicalAddress',
                17 => 'nextofkinCity',
                18 => 'nextofkinState',
            ];

            $updateData = [];
            foreach ($map as $index => $field) {
                if (isset($row[$index]) && $row[$index] !== null && $row[$index] !== '') {
                    $updateData[$field] = trim($row[$index]);
                }
            }

            try {
                $staff->fill($updateData);
                $staff->save();
                Log::info("Row $num: Updated staff {$row[0]}");
            } catch (\Exception $e) {
                $this->errors[] = "Row $num: Failed to update staff {$row[0]} — " . $e->getMessage();
                Log::error("Row $num update failed for {$row[0]}: " . $e->getMessage());
            }
        }
    }
}