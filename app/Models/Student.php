<?php

namespace App\Models;
use Illuminate\Support\Facades\Log;
use Auth;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $casts = [
        'next_of_kin' => 'array',
    ];

    protected $fillable = [
        'force_number', 'rank', 'first_name', 'middle_name', 'last_name',
        'user_id', 'vitengo_id', 'gender', 'blood_group', 'email', 'phone', 'nin',
        'dob', 'education_level', 'profession', 'home_region', 'entry_region', 'company_id', 'programme_id', 'session_programme_id',
        'height', 'weight', 'platoon', 'next_kin_names', 'next_kin_phone',
        'next_kin_relationship', 'next_kin_address', 'next_of_kin', 'profile_complete', 'photo',
        'status', 'enrollment_status', 'approved_at', 'rejected_at', 'reject_reason', 'approved_by',
        'rejected_by', 'transcript_printed', 'certificate_printed', 'printed_by',
        'reprint_reason', 'beat_exclusion_vitengo_id', 'beat_emergency', 'bank_name', 'account_number', 'study_level_id', 'dismissed_by','registration_number',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admittedStudent()
    {
        return $this->hasOne(AdmittedStudent::class);
    }

    public function studyLevel()
    {
        return $this->belongsTo(studyLevel::class, 'study_level_id');
    }

    public function finalResults()
    {
        return $this->hasMany(FinalResult::class);
    }

    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }

    public function sessionProgramme()
    {
        return $this->belongsTo(SessionProgramme::class);
    }
    public function vitengo()
    {
        return $this->belongsTo(Vitengo::class);
    }

    public function mps()
    {
        return $this->hasMany(MPS::class, 'student_id', 'id');
    }

    public function platoons()
    {
        return $this->hasMany(Platoon::class, 'name', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }
    // public function programme()
    // {
    //     return $this->hasOne(Programme::class, 'id', 'programme_id');
    // }

    // public function platoon()
    // {
    //     //return $this->company()->merge($this->platoons());
    // }

    public function optionalCourseEnrollments()
    {
        return $this->hasMany(OptionalCourseEnrollment::class); // Optional enrollments
    }

    public function optionalCourses()
    {
        return $this->optionalCourseEnrollments()->with('course')->get()->pluck('course');
    }

    public function courses()
    {
        $programmeCourses = $this->programme->courses(); // Fixed here
        $optionalCourses = $this->optionalCourseEnrollments();
        // return $programmeCourses->merge($optionalCourses);
        return $programmeCourses;
    }

    public function approve()
    {
        $this->status = 'approved';
        $this->approved_at = now();
        $this->approved_by = Auth::user()->id;
        $this->save();
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'approved_by')->withDefault();
    }

    public function beats()
    {
        return $this->belongsToMany(Beat::class, 'student_beat', 'student_id', 'beat_id')
            ->withTimestamps();
    }

    // public function getPhotoUrlAttribute()
    // {
    //     return Storage::url($this->photo);
    // }

    public function safari()
    {
        return $this->hasMany(SafariStudent::class);
    }

    public function pendingSafari()
    {
        return $this->safari()->where('status', 'safari');
    }

    public function sick()
    {
        return $this->hasMany(Patient::class);
    }

    public function leaves()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function getGPAAttribute()
    {
        $results = $this->finalResults;        
        $sessionProgramme = $results[0]->student->sessionProgramme; 
        $programmeCourseSemesters = $sessionProgramme->programmeCourseSemesters;// Retrieve ALL courses
        $semester_one_total_credit_weight = 0;
        $semester_one_total_grade_credit = 0;
        $semester_two_total_credit_weight = 0;
        $semester_two_total_grade_credit = 0;
        foreach ($results as $result) {
            if ($result->semester_id == 1) {
                $semester_one_total_credit_weight += $this->getGradePoint($result->grade) * $result->course->programmes->first()->pivot->credit_weight;
            } elseif ($result->semester_id == 2) {               
                $semester_two_total_credit_weight += $this->getGradePoint($result->grade) * $result->course->programmes->first()->pivot->credit_weight;
            }
            
        }
        
        // dd($semester_one_total_credit_weight);
        foreach ($programmeCourseSemesters as $programmeCourseSemester) {
            if ($programmeCourseSemester->course->semesters->first()->pivot->semester_id == 1) {
                $semester_one_total_grade_credit += $programmeCourseSemester->course->semesters->first()->pivot->credit_weight;
            }
            // Ensure no null error
            elseif ($programmeCourseSemester->course->semesters->first()->pivot->semester_id == 2) {
                
                $semester_two_total_grade_credit += $programmeCourseSemester->course->semesters->first()->pivot->credit_weight;
            }

        }
        // Calculation of gpa for each semester
        // $semesterOneGPA = $semester_one_total_grade_credit == 0 ? 0 : $semester_one_total_credit_weight / $semester_one_total_grade_credit;
        // $semesterTwoGPA = $semester_two_total_grade_credit == 0 ? 0 : $semester_two_total_credit_weight / $semester_two_total_grade_credit;

        // $average = ($semesterOneGPA + $semesterTwoGPA) / 2;
        // $overallGPA = intval($average * 10) / 10;

        // $overallGPA = number_format($overallGPA, 1);

        

        // Calculate and truncate Semester One GPA
        $semesterOneGPA = $semester_one_total_grade_credit == 0
            ? 0
            : $this->truncateToDecimal($semester_one_total_credit_weight / $semester_one_total_grade_credit, 1);

        // Calculate and truncate Semester Two GPA
        $semesterTwoGPA = $semester_two_total_grade_credit == 0
            ? 0
            : $this->truncateToDecimal($semester_two_total_credit_weight / $semester_two_total_grade_credit, 1);

        // Average the truncated semester GPAs and truncate the final result
        $averageGPA = ($semesterOneGPA + $semesterTwoGPA) / 2;
        $overallGPA = $this->truncateToDecimal($averageGPA, 1);
        
        return collect([
            'semesterOneGPA' => $semesterOneGPA,
            'semesterTwoGPA' => $semesterTwoGPA,
            'overallGPA' => $overallGPA,
            'classAwarded' => $this->getClass($overallGPA),
        ]);

        //return $total_credit > 0 ? round($total_grade_credit / $total_credit, 1) : null; // Avoid division by zero
    }
    // Truncate helper function
        function truncateToDecimal($number, $decimals = 1)
        {
            $factor = pow(10, $decimals);

            return intval($number * $factor) / $factor;
        }

    public function getClass($gpa)
    {
        if ($gpa >= 3.5 && $gpa <= 4) {
            return ' First Class';
        } elseif ($gpa >= 3.0 && $gpa <= 3.4) {
            return 'Second Class';
        } elseif ($gpa >= 2.0 && $gpa <= 2.9) {
            return 'Pass';
        } else {
            return 'Failed';
        }
    }

    public function getGradePoint($grade)
    {
        switch ($grade) {
            case 'A':return 4;
            case 'B':return 3;
            case 'C':return 2;
            case 'D':return 1;
            case 'F':return 0;
            default:return 0;
        }
    }

    /**
     * Check if the student has an active leave (end_date is null).
     *
     * @return bool
     */
    public function hasActiveLeaveRecord()
    {
        return $this->leaves()
            ->whereNull('return_date')
            ->where('status', 'approved')
            ->exists();
    }

    public function dismissal()
    {
        return $this->hasOne(StudentDismissal::class, 'student_id');
    }

    public function platoonRelation()
    {
        return $this->belongsTo(Platoon::class, 'platoon', 'name');
    }
}
