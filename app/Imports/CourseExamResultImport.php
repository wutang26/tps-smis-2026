<?php
namespace App\Imports;

use App\Models\Course;
use App\Models\SemesterExam;
use App\Models\SemesterExamResult;
use App\Models\Student;
use Auth;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class CourseExamResultImport implements ToCollection
{
    /**
     * @param Collection $collection
     */

    private $num = 0; // Row counter
    private $courseId;
    private $semesterId;
    public function __construct($courseId, $semesterId)
    {
        $this->courseId   = $courseId;
        $this->semesterId = $semesterId;
    }

    public function collection(Collection $rows)
    {
        $this->num = 0;
        foreach ($rows as $row) {
            $this->num++;

            // Skip the first 4 rows (e.g., headers or meta-info)
            if ($this->num <= 5) {
                continue;
            }

            try {
                // Skip empty rows
                // if (empty($row[1]) || empty($row[2])) {
                //     continue;
                // }

                //$names =  explode(" ", $row[1]);
                //$student = Student::where('first_name', $names[0])->where('middle_name', $names[1])->where('last_name', $names[2])->where('session_programme_id', 4)->first();
                $student = Student::where('force_number', trim($row[1]))->first();
                if (! $student) {
                    Log::error("Error creating exam of ". $row[1]);
                }
                // Retrieve the student by force number
                // $student = Student::where('force_number', $row[1])->first();
                // if (! $student) {
                //     throw new Exception('Student with force number ' . $row[1] . ' not found.');
                // }
                // Validate the coursework
                $course = Course::findOrFail($this->courseId);

                $semesterExam = SemesterExam::where('course_id', $this->courseId)->where('semester_id', $this->semesterId)->first();


                if (!$semesterExam) {
                    throw new Exception('Semester Exam is not Configured.');
                }
                //throw new Exception($semesterExam);
                // Check for duplicate coursework results
                if ($this->checkStudentDuplication($student->id, $this->semesterId)) {
                    //throw new Exception('Duplicate result for student ' . $row[1] . ' in course exam ' . $this->courseId . '.');
                }
                // Save coursework result
                SemesterExamResult::updateOrCreate(
                    [
                        'student_id'       => $student->id,
                        'semester_exam_id' => $semesterExam->id,
                    ], [
                        'student_id'       => $student->id,
                        'semester_exam_id' => $semesterExam->id,
                        'score'            => $row[3],
                        'created_by'       => Auth::user()->id,
                    ]);

                \Log::info('Upload action executed');
            } catch (Exception $e) {
                // Log the error for debugging purposes
                \Log::error($e->getMessage());
                //throw new Exception($e->getMessage());
                //continue; // Skip this row and move to the next
            }
        }
    }
    private function checkStudentDuplication($studentId, $semesterId)
    {
        return SemesterExamResult::where('student_id', $studentId)
            ->where('semester_exam_id', $semesterId)
            ->exists();
    }
}
