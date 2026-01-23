<?php

namespace App\Imports;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Exception;
use Illuminate\Support\Facades\Log;

class BulkImportStudents implements ToCollection, ToModel
{
    private $num = 0;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
    }

    

    public function model(array $row)
    {
        $this->num++;

        if ($this->num > 1) {
            if (empty($row[1])) {
                Log::warning("Skipped row due to missing first name: " . json_encode($row));
                return;
            }

            try {
                // Create student record first
                $student = new Student();
                $student->force_number = $row[0];
                $student->first_name = $row[1];
                if (!empty($row[2])) {
                    $student->middle_name = $row[2];
                }
                $student->last_name = $row[3];
                $student->session_programme_id = $this->getSessionId();

                if ($student->session_programme_id == 1) {
                    $student->gender = $row[20] == "RC" ? "M" : "F";
                } elseif ($student->session_programme_id == 5) {
                    $student->gender = $row[4];
                    $student->rank = 'Bigular';
                } else {
                    
                    $student->rank = $row[20];
                }
                $student->gender = substr($row[4],0,1);
                $student->phone = $row[5];
                $student->nin = $row[7];
                $student->blood_group = $row[8];
                $student->home_region = $row[9];
                $student->company_id = $this->getCompanyId($row[10]);
                $student->platoon = $row[11];
                $student->education_level = $row[12];
                $student->next_kin_names = $row[16];
                $student->next_kin_phone = $row[17];
                //$student->religion = $row[21];
                $student->beat_status = 0;

                // Save student record first
                $student->save();

                // Log::info("Student created: ID=" . $student->id . ", Name=" . $student->first_name . " " . $student->last_name);

                // **Only create a user account if session_programme_id is NOT 1 or 5**
                if ($student->session_programme_id != 1 && $student->session_programme_id != 5) {
                    $email = strtolower($row[1].'.'.$row[3].'@tpf.go.tz');
                    $password = strtoupper($row[3]);

                    $user = new User();
                    $user->name = $row[1] . ' ' . $row[3];
                    $user->email = $email;
                    $user->password = bcrypt($password);
                    $user->save();

                    Log::info("User created: " . $user->email);

                    // Assign 'student' role using Spatie
                    $user->assignRole('student');
                    Log::info("Assigned 'student' role to user: " . $user->email);

                    // Link the student to the newly created user
                    $student->user_id = $user->id;
                    $student->save();
                }
            } catch (\Exception $e) {
                Log::error("Error creating student/user: " . $e->getMessage());
            }
        }
    }

    /**
     * Get session ID from the active session
     */
    private function getSessionId()
    {
        $selectedSessionId = session('selected_session');
        if (!$selectedSessionId) {
            throw new Exception('Please select session.');
        }
        return $selectedSessionId;
    }

    /**
     * Map company names to IDs
     */
    private function getCompanyId($companyName)
    {
        $companyMapping = [
            'HQ' => 1,
            'A' => 2,
            'B' => 3,
            'C' => 4,
            'D' => 5,
            'E' => 6,
            'F' => 7,
        ];

        return $companyMapping[$companyName] ?? null;
    }
}
