<?php
namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\FinalResult;
use App\Models\Programme;
use App\Models\Semester;
use App\Models\SemesterExam;
use App\Models\SessionProgramme;
use App\Models\Student;
use App\Services\FinalResultService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\Snappy\Facades\SnappyPdf;
use App\Models\CourseworkResult;
use App\Models\SemesterExamResult;
use App\Models\ProgrammeCourseSemester;
use Illuminate\Support\Facades\Auth;

class FinalResultController extends Controller
{
    protected $finalResultService;

    public function __construct(FinalResultService $finalResultService)
    {
        $this->middleware('permission:generate-results')->only(['createGenerate', 'generate','returnResults']);
        $this->middleware('permission:student-coursework-list')->only(['getStudentResults']);
        $this->finalResultService = $finalResultService;
    }

    public function studentList()
    {
        // Check if a session ID has been submitted
        if (request()->has('session_id')) {
            // Store the selected session ID in the session
            session(['selected_session' => request()->session_id]);
        }
        
        $selectedSessionId = session('selected_session');
        if (! $selectedSessionId) {
            $selectedSessionId = 1;
        }

        $students   = Student::where('session_programme_id', $selectedSessionId)->where('enrollment_status', 1)->orderBy('company_id')->orderBy('platoon')->paginate(20);
        $companiesy = Company::all();

        $companies = Company::whereHas('students', function ($query) use ($selectedSessionId) {
            $query->where('session_programme_id', $selectedSessionId);
        })
            ->with(['students' => function ($query) use ($selectedSessionId) {
                $query->where('session_programme_id', $selectedSessionId)->where('enrollment_status', 1)->orderBy('platoon');
            }])
            ->get();

        // dd($companies);

        return view('final_results.student_certificate', compact('students', 'companies', 'selectedSessionId'));

    }
    public function index(Request $request)
    {
        $programme = Programme::findOrFail(1);
        $userId    = $request->user()->id;
        $user      = $request->user();
        if (
            $user->hasRole('Super Administrator') ||
            $user->hasRole('Academic Coordinator') ||
            $user->hasRole('Chief Instructor') ||
            $user->hasRole('Head of Department')) {
            $semesters = Semester::with('courses')->get();

        } else if ($request->user()->hasRole('Instructor')) {
            $semesters = Semester::with(['courses' => function ($query) use ($userId) {
                $query->whereHas('courseInstructors', function ($subQuery) use ($userId) {
                    $subQuery->where('user_id', $userId);
                });
            }])->get();
        } else {
            $semesters = [];
        }
        $selectedSemesterId = $request->get('semester_id');
        $selectedSemester   = $selectedSemesterId ? Semester::with('courses')->find($selectedSemesterId) : null;

        return view('final_results.index', compact('programme', 'semesters', 'selectedSemester'));
    }

    public function create()
    {
        $students  = Student::all();
        $semesters = Semester::all();
        $courses   = Course::all();
        return view('final_results.create', compact('students', 'semesters', 'courses'));
    }

    public function createGenerate()
    {
        
        $sessionProgrammeId = session('selected_session', 1);
        $enrollments  = Enrollment::where('session_programme_id', $sessionProgrammeId)->get();
        $session_programme = SessionProgramme::find($sessionProgrammeId);
        $finalResults = FinalResult::with(['student', 'semester', 'course'])->get();
        $programme =  $session_programme->programme;
        // $sessionProgramme = SessionProgramme::findOrFail(session('selected_session', 4));
        $courses  = $programme->courses()
        //->wherePivot('semester_id', 2)
                ->wherePivot('session_programme_id', $sessionProgrammeId)
                ->orderBy('course_type', 'ASC')
                ->get();
            // return $courses;   
        return view('final_results.generate', compact('finalResults', 'courses','sessionProgrammeId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id'  => 'required|exists:students,id',
            'semester_id' => 'required|exists:semesters,id',
            'course_id'   => 'required|exists:courses,id',
        ]);

        // You may need to fetch the appropriate semester exam ID for the given course and semester
        $semesterExam = \App\Models\SemesterExam::where('course_id', $request->course_id)
            ->where('semester_id', $request->semester_id)
            ->first();

        $semesterExamId = $semesterExam ? $semesterExam->id : null;

        $resultData = $this->finalResultService->calculateFinalResult(
            $request->student_id,
            $semesterExamId,
            $request->semester_id,
            $request->course_id
        );

        $resultData['student_id']  = $request->student_id;
        $resultData['semester_id'] = $request->semester_id;
        $resultData['course_id']   = $request->course_id;

        FinalResult::create($resultData);

        return redirect()->route('final_results.index')
            ->with('success', 'Final result created successfully.');
    }
    public function showExamResults()
    {
        // Get the authenticated student
        $user = Auth::user();
        $student = $user->student;

        // Retrieve results with relationships eager-loaded
        $results = FinalResult::with([
            'semester',
            'course',
            //'semesterExam.course.programmes'
        ])
        ->where('student_id', $student->id)
        ->get();
        // Group results by semester
        $groupedBySemester = $results->groupBy(function ($result) {
            return $result->semester->id;
        });

        return view('students.exam.results', compact('groupedBySemester','student'));
    }
    
    public function show(FinalResult $finalResult)
    {
        return view('final_results.show', compact('finalResult'));                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        
    }

    public function edit(FinalResult $finalResult)
    {
        $students  = Student::all();
        $semesters = Semester::all();
        $courses   = Course::all();
        return view('final_results.edit', compact('finalResult', 'students', 'semesters', 'courses'));
    }

    public function update(Request $request, FinalResult $finalResult)
    {
        $request->validate([
            'student_id'  => 'required|exists:students,id',
            'semester_id' => 'required|exists:semesters,id',
            'course_id'   => 'required|exists:courses,id',
        ]);

        $resultData = $this->finalResultService->calculateFinalResult(
            $request->student_id,
            $request->semester_id,
            $request->course_id
        );

        $resultData['student_id']  = $request->student_id;
        $resultData['semester_id'] = $request->semester_id;
        $resultData['course_id']   = $request->course_id;

        $finalResult->update($resultData);

        return redirect()->route('final_results.index')
            ->with('success', 'Final result updated successfully.');
    }

    public function destroy(FinalResult $finalResult)
    {
        $finalResult->delete();

        return redirect()->route('final_results.index')
            ->with('success', 'Final result deleted successfully.');
    }

    public function generate(Request $request, $sessionProgrammeId)
    {
        $semesterExam = SemesterExam::where('course_id', $request->course_id)->where('session_programme_id', $sessionProgrammeId)->first();
        $sessionProgramme = SessionProgramme::find($sessionProgrammeId);
        if (!$semesterExam) {
            return redirect()->back()->with('info', 'Course Exam is not configured.');
        }
        $semesterExamId = $semesterExam->id;
        $courseId       = $request->course_id;
        $students       = $sessionProgramme->students; //->where('force_number', 'J.9425')->values();
        $i = 0;
        foreach ($students as $student) {
            $resultData = $this->finalResultService->calculateFinalResult(
                $student->id,
                $semesterExamId,
                $semesterExam->semester_id,
                $courseId
            );
            if($resultData ['grade'] == 'I'){
                $i++;
            }
            $resultData = array_merge($resultData, [
                'student_id'  => $student->id,
                'semester_id' => $semesterExam->semester_id,                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
                'course_id'   => $request->course_id,
            ]);

            $finalResult = FinalResult::updateOrCreate(
                [
                'student_id' => $student->id,
                'semester_id' => $semesterExam->semester_id,
                'course_id' => $request->course_id,
                ],
                $resultData
            );
        }

        // foreach ($enrollments as $enrollment) {
        //     $resultData = $this->finalResultService->calculateFinalResult(
        //         $enrollment->student_id,
        //         $enrollment->semester_id,
        //         $enrollment->course_id
        //     );

        //     $resultData['student_id']  = $enrollment->student_id;
        //     $resultData['semester_id'] = $enrollment->semester_id;
        //     $resultData['course_id']   = $enrollment->course_id;

            // $finalResult = FinalResult::updateOrCreate(
            //     [
            //         'student_id'  => $enrollment->student_id,
            //         'semester_id' => $semesterExam->semester_id,
            //         'course_id'   => $request->course_id,
            //     ],
            //     $resultData
            // );
        
\Log::info("Total incomplete results generated: $i");
        return redirect()->route('final_results.index')
            ->with('success', 'Final results generated successfully.');
    }


    public function generateTranscriptxx(Request $request)
    {
        $selectedStudentIds = $request->input('selected_students');
        if (empty($selectedStudentIds)) {
            return redirect()->back()->with('error', 'No students selected.');
        }

        $students = Student::whereIn('id', $selectedStudentIds)
            ->with(['finalResults', 'admittedStudent'])
            ->get();

        // Attach QR code to each student



        // Pass only students to the view
        $pdf = Pdf::loadView('final_results.pdf', compact('students'))
            ->setPaper('a4', 'landscape')
            ->setOption('margin-bottom', '20mm'); 


        return $pdf->stream('final_results.pdf');
    }


    public function generateTranscript(Request $request)
    {
        $selectedStudentIds = $request->input('selected_students');
        if (empty($selectedStudentIds)) {
            return redirect()->back()->with('error', 'No students selected.');
        }

        $students = Student::whereIn('id', $selectedStudentIds)
            ->with(['finalResults', 'admittedStudent'])
            ->get();

        foreach ($students as $student) {
            $admission = $student->admittedStudent;

        

            $qrPayload = json_encode([
                'full_name' => $student->first_name . ' ' . $student->last_name ?? '',
                // 'dob' => $student->dob ?? '',
                'nin' => $student->nin ?? '',
                'programme_abbreviation' => $student->programme->abbreviation ?? '',
                'registration_number' => $admission->registration_number ?? '',
                'completion_date' => $admission->completion_date ?? '',
            ]);

            \Log::info('QR Payload:', ['payload' => $qrPayload]);


            $student->qrCodeBase64 = 'data:image/svg+xml;base64,' . base64_encode(
                QrCode::format('svg')
                    ->size(120) // ✅ Small but crisp
                    ->errorCorrection('M') // Medium balance
                    ->generate($qrPayload)
            );

        }

        $pdf = Pdf::loadView('final_results.pdf', compact('students'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('final_results.pdf');
    }






    public function generateCertificate_oldNew(Request $request)
    {
        // dd($request->input());
        $selectedStudentIds = $request->input('selected_students');
        if (empty($selectedStudentIds)) {
            return redirect()->back()->with('error', 'No students selected.');
        }

        $students = Student::whereIn('id', $selectedStudentIds)->get();

        // Load the view and set paper with custom margins
        $pdf = PDF::loadView('final_results.certificate', compact('students'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => true,
                'defaultPaperMargins'  => ['top' => '15mm', 'right' => '15mm', 'bottom' => '15mm', 'left' => '15mm'],
            ]);

        // Render the HTML as PDF
        $pdf->render();
        // Return the PDF content as a response to be rendered in a new browser window
        return $pdf->stream('final_results.certificate');

    }

    public function generateCertificate(Request $request)
    {
        $selectedSessionId = session('selected_session');
        if (! $selectedSessionId) {
            $selectedSessionId = 1;
        }
        //  dd($request->input());
        // Retrieve selected student IDs from the request
        $selectedStudentIds = $request->input('selected_students');

        // Validate if any students are selected
        if (empty($selectedStudentIds)) {
            return redirect()->back()->with('error', 'No students selected.');
        }

        // Fetch the student data in batches to improve performance
        $students = Student::whereIn('id', $selectedStudentIds)->get();

        // Check if students are retrieved successfully
        if ($students->isEmpty()) {
            return redirect()->back()->with('error', 'No valid student data found.');
        }

        $session_programme = SessionProgramme::find($selectedSessionId);
        $programme_courses = ProgrammeCourseSemester::where('session_programme_id',$selectedSessionId)->get();
        // Load the certificate view and configure the PDF
        try {
            $pdf = PDF::loadView('final_results.certificate', compact('students','session_programme','programme_courses'))
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled'      => true,
                    'dpi'                  => 120, // Improves rendering efficiency
                                                   // 'defaultFont' => 'Arial',
                    'defaultFont'          => 'Edwardian Script ITC',
                ]);

            // Return the PDF stream
            return $pdf->stream('final_results.certificate');

        } catch (\Exception $e) {
            // Handle errors and provide feedback
            return redirect()->back()->with('error', 'An error occurred while generating the PDF. Please try again.');
        }
    }

    public function generateCertificatex()
    {
        $data = [
            'title'      => 'Certificate of Achievement',
            'recipient'  => 'G.3332 CPL Erick Eusebo Msilu',
            'course'     => 'Sergeant Course No. 1/2024/2025',
            'school'     => 'Tanzania Police School-Moshi',
            'dates'      => '10 December 2024 to 07 March 2025',
            'subjects'   => [
                'Police Duties and Administration',
                'Human Rights and Policing',
                'Police Leadership',
                'Communication Skills and Customer Care',
                'Traffic Control and Management',
                'Criminal Investigation, Intelligence and Forensic Science',
                'Criminal Procedure',
                'Law of Evidence',
                'Criminal Law',
                'Gender Issues and Child Protection',
                'Public Health and Environmental Protection',
                'Community Policing, Radicalization, Violent Extremism and Terrorism',
                'Drill and Parade',
                'Military and Safety Training',
            ],
            'signatures' => [
                'Omary S. Kisalo - ACP, Chief Instructor',
                'Ramadhani A. Mungi - SACP, Commandant',
            ],
        ];

        $pdf = PDF::loadView('certificate', $data);

        return $pdf->download('Certificate.pdf');
    }

    public function search(Request $request, $companyId)
{
    $search = null;
    // Store selected session
    if ($request->has('session_id')) {
        session(['selected_session' => $request->session_id]);
    }

    $selectedSessionId = session('selected_session', 1);

    // Students query (for pagination)
    $students = Student::where('session_programme_id', $selectedSessionId)
        ->where('enrollment_status', 1)
        ->orderBy('company_id')
        ->orderBy('platoon');

    if ($request->filled('search')) {
        $search = $request->search;
        $students->where(function ($query) use ($search) {
            $query->where('first_name', 'like', "%{$search}%")
                  ->orWhere('middle_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('force_number', 'like', "%{$search}%");
        });
    }

    if ($request->filled('platoon')) {
        $students->where('platoon', $request->platoon);
    }

    $students = $students->paginate(20);

    // Get all companies in the current session
    $companies = Company::whereHas('students', function ($query) use ($selectedSessionId) {
            $query->where('session_programme_id', $selectedSessionId);
        })
        ->with(['students' => function ($query) use ($selectedSessionId, $request, $companyId) {
            $query->where('session_programme_id', $selectedSessionId)
                  ->where('enrollment_status', 1)
                  ->where('company_id', $companyId);

            if ($request->filled('platoon')) {
                $query->where('platoon', $request->platoon);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('middle_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('force_number', 'like', "%{$search}%");
                });
            }
        }])
        ->get();
    
    return view('final_results.student_certificate', compact('students', 'companies', 'selectedSessionId', 'search'));
}



    public function getResults($semesterId, $courseId, Request $request)
{
    // Handle session selection
    if ($request->has('session_id')) {
        session(['selected_session' => $request->session_id]);
    }

    $perPage = 10;
    $selectedSessionId = session('selected_session', 4);

    $search = $request->query('search', ''); // Get search term from query

    $finalResults = FinalResult::with('student')
        ->where('course_id', $courseId)
        ->where('semester_id', $semesterId)
        ->whereHas('student', function ($query) use ($selectedSessionId, $search) {
            $query->where('session_programme_id', $selectedSessionId)
                  ->where('status', 'approved');

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('middle_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('force_number', 'like', "%{$search}%");
                });
            }
        })
        ->paginate($perPage);

    return response()->json([
        'course'  => Course::find($courseId),
        'results' => $finalResults,
    ]);
}


    public function getStudentResults(Request $request, $studentId)
    {
        // Check if a session ID has been submitted
        if (request()->has('session_id')) {
            // Store the selected session ID in the session
            session(['selected_session' => request()->session_id]);
        }
        
        $perPage = 10;
        // Fetch final results with related student info
        $selectedSessionId = session('selected_session');
        if (! $selectedSessionId) {
            $selectedSessionId = 4;
        }
        $student = Student::find($studentId);

        $results = FinalResult::with([
            'semester',
            'course',
            //'semesterExam.course.programmes'
        ])
        ->where('student_id', $student->id)
        ->get();
        // Group results by semester
        $groupedBySemester = $results->groupBy(function ($result) {
            return $result->semester->id;
        });

        return view('students.exam.results', compact('groupedBySemester','student'));
    }

    public function returnResults($semesterId, $courseId)
    {
        // Check if a session ID has been submitted
        if (request()->has('session_id')) {
            // Store the selected session ID in the session
            session(['selected_session' => request()->session_id]);
        }
        
        $selectedSessionId = session('selected_session');
        if (! $selectedSessionId) {
            $selectedSessionId = 4;
        }

        $deletedCount = FinalResult::where('course_id', $courseId)
            ->where('semester_id', $semesterId)
            ->whereHas('course', function ($query) use ($selectedSessionId) {
                $query->whereHas('semesters', function ($subQuery) use ($selectedSessionId) {
                    $subQuery->where('programme_course_semesters.session_programme_id', $selectedSessionId);
                });
            })
            ->delete();

        return response()->json([
            'status'  => 'ok',
            'message' => "$deletedCount final result(s) deleted successfully.",
        ]);
    }

     
    public function generateAll()
{
    $sessionProgrammeId = session('selected_session', 1);
    $sessionProgramme = SessionProgramme::findOrFail($sessionProgrammeId);
    // Get all enrollments (i.e., courses assigned to this sessionProgramme)
    $enrollments = Enrollment::where('session_programme_id', $sessionProgrammeId)->get();

    if (!$sessionProgramme->programmeCourseSemesters) {
        return redirect()->back()->with('info', 'No courses are assigned to this session.');
    }

    foreach ($sessionProgramme->programmeCourseSemesters as $courseSemester) {
        $courseId = $courseSemester->course_id;
        $semesterId = $courseSemester->semester_id;

        // Ensure the exam exists for this course & semester
        $semesterExam = SemesterExam::where('course_id', $courseId)
            ->where('semester_id', $semesterId)
            ->first();

        if (!$semesterExam) {
            \Log::warning("Skipped course during final result generation: {$courseSemester->course->courseName} (Course ID: {$courseId}, Semester ID: {$semesterId})");
            // Skip this course if exam not configured
            continue;
        }

        $semesterExamId = $semesterExam->id;
        
        foreach ($sessionProgramme->students as $student) {
            
            $resultData = $this->finalResultService->calculateFinalResult(
                $student->id,
                $semesterExamId,
                $semesterId,
                $courseId
            );


            $resultData = array_merge($resultData, [
                'student_id'  => $student->id,
                'semester_id' => $semesterId,
                'course_id'   => $courseId,
            ]);

            FinalResult::updateOrCreate(
                [
                    'student_id'  => $student->id,
                    'semester_id' => $semesterId,
                    'course_id'   => $courseId,
                ],
                $resultData
            );
        }
    }
    
    return redirect()->route('final_results.index')
        ->with('success', 'Final results for all students and courses generated successfully.');
}

}
