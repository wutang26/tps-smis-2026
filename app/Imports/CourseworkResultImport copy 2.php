<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Student;
use App\Models\CourseworkResult;
use App\Models\CourseWork;
use Auth;
use Exception;

class CourseworkResultImport implements ToCollection
{
    private $num = 0; // Row counter
    private $courseworkId;
    private $courseId;
    private $semesterId;

    // Constructor to initialize semesterId, courseId, and courseworkId
    public function __construct($semesterId, $courseId, $courseworkId)
    {
        $this->semesterId = $semesterId;
        $this->courseId = $courseId;
        $this->courseworkId = $courseworkId;
    }

    // Main method to process the Excel rows
    public function collection(Collection $rows)
    {
    // dd('Processing rows...', $rows);

        $this->num = 0;

        foreach ($rows as $row) {
            $this->num++;

            // Skip the first 4 rows (e.g., headers or meta-info)
            if ($this->num <= 4) {
                continue;
            }

            try {
                // Skip empty rows
                if (empty($row[1]) || empty($row[3])) {
                    continue;
                }

                // Retrieve the student by force number
                $student = Student::where('force_number', $row[1])->first();
                if (!$student) {
                    throw new Exception('Student with force number ' . $row[1] . ' not found.');
                }

                // Validate the coursework
                $coursework = CourseWork::findOrFail($this->courseworkId);
                
                if ($row[3] > $coursework->max_score) {
                    throw new Exception('Score (' . $row[3] . ') exceeds maximum score (' . $coursework->max_score . ').');
                }

                // Check for duplicate coursework results
                if ($this->checkStudentDuplication($student->id)) {
                    throw new Exception('Duplicate result for student ' . $row[1] . ' in coursework ' . $this->courseworkId . '.');
                }

                // Save coursework result
                CourseworkResult::create([
                    'student_id' => $student->id,
                    'course_id' => $this->courseId,
                    'coursework_id' => $this->courseworkId,
                    'semester_id' => $this->semesterId,
                    'score' => $row[3],
                    'created_by' => Auth::user()->id,
                ]);
                
                \Log::info('Upload action executed');
            } catch (Exception $e) {
                // Log the error for debugging purposes
                \Log::error($e->getMessage());
                continue; // Skip this row and move to the next
            }
        }
    }

    // Utility method to check for duplicate entries
    private function checkStudentDuplication($studentId)
    {
        return CourseworkResult::where('student_id', $studentId)
            ->where('course_id', $this->courseId)
            ->where('coursework_id', $this->courseworkId)
            ->where('semester_id', $this->semesterId)
            ->exists();
    }
}