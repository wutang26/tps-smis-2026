<?php
namespace App\Services;

use App\Models\CourseworkResult;
use App\Models\GradeMapping;
use App\Models\SemesterExamResult;

class FinalResultService
{
    public function calculateFinalResult($studentId, $semesterExamId, $semesterId, $courseId)
    {
        // Check if there are any coursework results or exam results
        $hasCourseworkResults = CourseworkResult::where('student_id', $studentId)
        //->where('semester_id', $semesterId)
            ->whereHas('coursework', function ($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })
            ->exists();
        $hasExamResults = SemesterExamResult::where('student_id', $studentId)
            ->where('semester_exam_id', $semesterExamId)
            ->whereHas('semesterExam', function ($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })
            ->exists();         
        // If there are no coursework results or exam results, mark as Incomplete
        if (! $hasCourseworkResults || ! $hasExamResults) {
            return [
                'total_score' => null,
                'grade'       => 'I',
            ];
        }
        // Calculate total coursework score
        $courseworkResults = CourseworkResult::where('student_id', $studentId)
        //->where('semester_id', $semesterId)
            ->whereHas('coursework', function ($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })
            ->get();
        $totalCourseworkScore = $courseworkResults->sum('score');
        // Calculate total exam score
        $examResults = SemesterExamResult::where('student_id', $studentId)
            ->where('semester_exam_id', $semesterExamId)
        //->where('course_id', $courseId)
        //  ->whereHas('exam', function ($query) use ($courseId) {
        //      $query->where('course_id', $courseId);
        //  })
            ->get();
        $totalExamScore = $examResults->sum('score');

        // Calculate total score
        $totalScore = (float)$totalCourseworkScore + (float)$totalExamScore;
                                                                    // Determine grade
        $gradeMapping = GradeMapping::where('grading_system_id', 1) // Assuming 1 as the grading system ID for this example
            ->where('min_score', '<=', (float)$totalScore)
            ->where('max_score', '>=', (float)$totalScore)
            ->first();
        
        $grade = $gradeMapping ? $gradeMapping->grade : 'I';

        return [
            'total_score' => $totalScore,
            'grade'       => $grade,
        ];
    }
}
