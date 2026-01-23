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
use Picqer\Barcode\BarcodeGeneratorPNG;

class FinalResultController extends Controller
{
    protected $finalResultService;

    public function __construct(FinalResultService $finalResultService)
    {
        $this->middleware('permission:generate-results')->only(['createGenerate', 'generate','returnResults']);
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
            $selectedSessionId = 4;
        }

        $students   = Student::where('session_programme_id', $selectedSessionId)->orderBy('company_id')->orderBy('platoon')->paginate(20);
        $companiesy = Company::all();

        $companies = Company::whereHas('students', function ($query) use ($selectedSessionId) {
            $query->where('session_programme_id', $selectedSessionId);
        })
            ->with(['students' => function ($query) use ($selectedSessionId) {
                $query->where('session_programme_id', $selectedSessionId)->orderBy('platoon');
            }])
            ->get();

        // dd($companies);

        return view('final_results.student_certificate', compact('students', 'companies'));

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
        $enrollments  = Enrollment::all();
        $finalResults = FinalResult::with(['student', 'semester', 'course'])->get();
        return view('final_results.generate', compact('finalResults', 'enrollments'));
    }

    public function store(Request $request)
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

        FinalResult::create($resultData);

        return redirect()->route('final_results.index')
            ->with('success', 'Final result created successfully.');
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
        $enrollment   = Enrollment::where('session_programme_id', $sessionProgrammeId)->where('course_id', $request->course_id)->first();
        $semesterExam = SemesterExam::where('course_id', $request->course_id)->where('semester_id', $enrollment->semester_id)->get();
        if ($semesterExam->isEmpty()) {
            return redirect()->back()->with('info', 'Course Exam is not configured.');
        }
        $semesterExamId = $semesterExam->first()->id;
        $courseId       = $request->course_id;
        $students       = $enrollment->sessionProgramme->students; //->where('force_number', 'J.9425')->values();

        foreach ($students as $student) {
            $resultData = $this->finalResultService->calculateFinalResult(
                $student->id,
                $semesterExamId,
                $enrollment->semester_id,
                $courseId
            );

            $resultData = array_merge($resultData, [
                'student_id'  => $student->id,
                'semester_id' => $enrollment->semester_id,
                'course_id'   => $enrollment->course_id,
            ]);

            $finalResult = FinalResult::updateOrCreate(
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

            $finalResult = FinalResult::updateOrCreate(
                [
                    'student_id'  => $enrollment->student_id,
                    'semester_id' => $enrollment->semester_id,
                    'course_id'   => $enrollment->course_id,
                ],
                $resultData
            );
        

        return redirect()->route('final_results.index')
            ->with('success', 'Final results generated successfully.');
    }

    public function generateTranscript(Request $request)
    {
        // dd($request->input());
        $selectedStudentIds = $request->input('selected_students');
        if (empty($selectedStudentIds)) {
            return redirect()->back()->with('error', 'No students selected.');
        }

        $students = Student::whereIn('id', $selectedStudentIds)->with('finalResults')->with('admittedStudent')->get();




$student = Student::with('admittedStudent')->findOrFail($id);

$barcodeData = implode('|', [
    $student->first_name,
    $student->dob->format('d-m-Y'),
    $student->nin,
    $student->programme_id,
    $student->admittedStudent->registration_number ?? 'N/A',
    optional($student->admittedStudent->completion_date)->format('d-m-Y') ?? 'N/A',
]);


$generator = new BarcodeGeneratorPNG();
$barcode = $generator->getBarcode($barcodeData, $generator::TYPE_CODE_128);
$barcodeBase64 = base64_encode($barcode);


        //dd($students);
        // dd($this->finalResultService->calculateFinalResult(
        //     '513','2','4'
        // ));
        // Query data from 'final_results' table and process certificates
        // Generate and return PDF with selected students' certificates
        //return $students[0]->courses;
        // Example (using a package like Dompdf or another PDF library):
        $pdf = PDF::loadView('final_results.pdf', compact('students'))->setPaper('a4', 'landscape');


        // Set the HTML5 parser option
        // $pdf->setOptions(['isHtml5ParserEnabled' => true]);

        // Render the HTML as PDF
        $pdf->render();
        // Return the PDF content as a response to be rendered in a new browser window
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

        // Load the certificate view and configure the PDF
        try {
            $pdf = PDF::loadView('final_results.certificate', compact('students'))
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
        // Check if a session ID has been submitted
        if (request()->has('session_id')) {
            // Store the selected session ID in the session
            session(['selected_session' => request()->session_id]);
        }
        
        $selectedSessionId = session('selected_session');
        if (! $selectedSessionId) {
            $selectedSessionId = 4;
        }

        $students   = Student::where('session_programme_id', $selectedSessionId)->orderBy('company_id')->orderBy('platoon')->paginate(20);
        $companiesy = Company::all();

        $companies = Company::whereHas('students', function ($query) use ($selectedSessionId) {
            $query->where('session_programme_id', $selectedSessionId);
        })
            ->with(['students' => function ($query) use ($selectedSessionId, $request, $companyId) {
                $query->where('session_programme_id', $selectedSessionId)
                    ->where('company_id', $companyId)
                    ->where('platoon', $request->platoon);
            }])
            ->get();
        return view('final_results.student_certificate', compact('students', 'companies'));

    }

    public function getResults($semesterId, $courseId, Request $request)
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
        $finalResults = FinalResult::with('student')
            ->where('course_id', $courseId)
            ->where('semester_id', $semesterId)
            ->whereHas('student', function ($query) use ($selectedSessionId) {
                $query->where('session_programme_id', $selectedSessionId);
            })
            ->paginate($perPage);

        // Get course information (optional, not used in rendering results table)
        $course = Course::find($courseId);

        return response()->json([
            'course'  => $course ?? [],
            'results' => $finalResults, // Laravel automatically includes pagination structure
        ]);
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
        
        $sessionId =  session('selected_session',1);

        $session = SessionProgramme::with(['programmeCourseSemesters.course', 'programmeCourseSemesters.programme'])
        ->findOrFail($sessionId);
    $courses = $session->programmeCourseSemesters->map(function ($pcs) {
        return [
            'course_id'   => $pcs->course->id,
            'courseCode'  => $pcs->course->courseCode,
            'courseName'  => $pcs->course->courseName,
            'programmeId' => $pcs->programme->id,
            'programme'   => $pcs->programme->programmeName,
            'semesterId'  => $pcs->semester_id,
            'courseType'  => $pcs->course_type,
            'creditWeight'=> $pcs->credit_weight,
        ];
    });
        return $courses;
        $enrollments = Enrollment::all();

        foreach ($enrollments as $enrollment) {
            $resultData = $this->finalResultService->calculateFinalResult(
                $enrollment->student_id,
                $enrollment->semester_id,
                $enrollment->course_id
            );

            $resultData['student_id'] = $enrollment->student_id;
            $resultData['semester_id'] = $enrollment->semester_id;
            $resultData['course_id'] = $enrollment->course_id;

            $finalResult = FinalResult::updateOrCreate(
                [
                    'student_id' => $enrollment->student_id,
                    'semester_id' => $enrollment->semester_id,
                    'course_id' => $enrollment->course_id,
                ],
                $resultData
            );
        }

        return redirect()->route('final_results.index')
                         ->with('success', 'Final results generated successfully.');
    }
}
