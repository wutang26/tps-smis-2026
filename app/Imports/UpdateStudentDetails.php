<?php

namespace App\Imports;

use App\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;

class UpdateStudentDetails implements ToCollection
{
    public $errors = [];
    public $warnings = [];

    public function collection(Collection $rows)
    {
        $num = 0;

        foreach ($rows as $row) {
            $num++;
            if ($num <= 5) continue; // Skip headers

            // --- Validation ---
            // $validator = Validator::make([
            //     'first_name'   => $row[2] ?? null,
            //     'middle_name'  => $row[3] ?? null,
            //     'last_name'    => $row[4] ?? null,
            //     'company_id'   => $this->getCompanyId($row[6]) ?? null,
            //     'platoon'      => $row[7] ?? null,
            //     'force_number' => $row[0] ?? null,
            // ], [
            //     'first_name'   => 'required|string|max:255',
            //     'middle_name'  => 'required|string|max:255',
            //     'last_name'    => 'required|string|max:255',
            //     'company_id'   => 'required',
            //     'platoon'      => 'required',
            //     'force_number' => 'nullable|string|max:20',
            // ]);

            // if ($validator->fails()) {
            //     $this->errors[] = "Row $num: " . implode(', ', $validator->errors()->all());
            //     Log::error("Row $num validation failed: " . json_encode($validator->errors()->all()));
            //     continue;
            // }

            // --- Student Lookup ---
            //Log::info($row[21]);
            $student = !empty($row[0])
                ? Student::where('force_number', trim($row[0]))->first()
                : Student::where([
                    ['first_name', '=', $row[2]],
                    ['middle_name', '=', $row[3]],
                    ['last_name', '=', $row[4]],
                    ['company_id', '=', $this->getCompanyId($row[6])],
                    ['platoon', '=', $row[7]],
                ])->first();

            if (!$student) {
                $this->warnings[] = "Row $num: Student not found.";
                Log::warning("Row $num student not found: " . json_encode($row));
                continue;
            }

            // --- Session Check ---
            try {
                $selectedSessionId = $this->getSessionId();
            } catch (\Exception $e) {
                $this->errors[] = "Row $num: " . $e->getMessage();
                continue;
            }

            if ($student->session_programme_id != $selectedSessionId) {
                $this->warnings[] = "Row $num: Session mismatch.";
                Log::warning("Row $num session mismatch: " . json_encode($row));
                continue;
            }

            // --- NIN Conflict Check ---
            if (!empty($row[10])) {
                $ninConflict = Student::where('nin', $row[10])
                    ->where('id', '!=', $student->id)
                    ->exists();

                if ($ninConflict) {
                    $this->errors[] = "Row $num: NIN already exists for another student.";
                    Log::error("Row $num duplicate NIN: " . json_encode($row));
                    continue;
                }
                $student->nin = $row[10];
            }

            // --- Update Fields Conditionally ---
            $student->fill([
                'force_number'   => $row[0] ?? $student->force_number,
                'phone'          => $row[8] ?? $student->phone,
                'dob'            => $row[9] ?? $student->dob,
                'blood_group'    => $row[11] ?? $student->blood_group,
                'home_region'    => $row[12] ?? $student->home_region,
                'entry_region'   => $row[13] ?? $student->entry_region,
                'education_level'=> $row[14] ?? $student->education_level,
                'profession'     => $row[15] ?? $student->profession,
                'weight'         => $row[16] ?? $student->weight,
                'height'         => $row[17] ?? $student->height,
                'account_number' => $row[18] ?? $student->account_number,
                'bank_name'      => $row[19] ?? $student->bank_name,
                'registration_number' =>$row[21] ?? $student->registration_number
            ]);
            $student->save(); // More semantic than update()
        }
    }

    private function getCompanyId($companyName)
    {
        $mapping = [
            'HQ' => 1,
            'A'  => 2,
            'B'  => 3,
            'C'  => 4,
            'D'  => 5,
        ];
        return $mapping[strtoupper(trim($companyName))] ?? null;
    }

    private function getSessionId()
    {
        $selectedSessionId = session('selected_session');
        if (!$selectedSessionId) {
            throw new \Exception('Please select a session.');
        }
        return $selectedSessionId;
    }
}
