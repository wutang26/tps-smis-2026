<?php
namespace App\Http\Controllers;

use App\Imports\BulkImportStudents;
use App\Imports\UpdateStudentDetails;
use App\Models\Company;
use App\Models\Platoon;
use App\Models\Programme;
use App\Models\SafariType;
use App\Models\Student;
use App\Models\User;
use App\Models\Vitengo;
use App\Models\TerminationReason;
use App\Models\StudentDismissal;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Hash;
// use Illuminate\Support\Facades\Hash;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\AuditLoggerService;

// Namespace for the Log facade

//use Barryvdh\DomPDF\Facade as PDF;

// use Barryvdh\DomPDF\Facade\Pdf;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:student-list|student-create|student-edit|student-delete', ['only' => ['index', 'view', 'search']]);
        //$this->middleware('permission:student-create', ['only' => ['create', 'store', 'createStepOne', 'postStepOne', 'createStepTwo', 'postStepTwo', 'createStepThree', 'postStepThree', 'import']]);
        $this->middleware('permission:student-edit', ['only' => ['edit', 'postStepOne', 'createStepTwo', 'postStepTwo', 'createStepThree', 'postStepThree','update']]);
        $this->middleware('permission:student-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
      
        // Check if a session ID has been submitted
        if (request()->has('session_id')) {
            // Store the selected session ID in the session
            session(['selected_session' => request()->session_id]);
        }

        $selectedSessionId = session('selected_session',1);
        $user = Auth::user();
        $companies = [];

    if ($user->hasRole(['Teacher', 'Instructor', 'OC Coy']) || $user->hasRole('Sir Major')) {
            $companies = collect([$user->staff->company]);
            if (count($companies) != 0) {
                if ($companies[0] == null) {               
                    //return view('attendences/index', compact('attendenceType', 'date'));
                }
            }
        } elseif ($user->hasRole(['Admin', 'Academic Coordinator', 'Super Administrator', 'Chief Instructor', 'Staff Officer'])) {
            $companies = Company::whereHas('students', function ($query) use ($selectedSessionId) {
                $query->where('session_programme_id', $selectedSessionId);
            })->get();

        }
        
        // Global approved count based on selected session only
        $approvedCount = Student::where('session_programme_id', $selectedSessionId)
            ->whereIn('company_id', $companies->pluck('id'))
            ->where('status', 'approved')
            ->count();
    
        $terminationReasons = TerminationReason::all()
                                ->filter(fn($r) => $r->category && is_string($r->category))
                                ->groupBy('category');

        

        $students  = Student::where('session_programme_id', $selectedSessionId)->whereIn('company_id', $companies->pluck('id'))->where('enrollment_status', 1)->latest()->orderBy('company_id')->orderBy('platoon')->paginate(20);
        // $students  = Student::where('session_programme_id', $selectedSessionId)->whereIn('company_id', $companies->pluck('id'))->where('enrollment_status', 1)->orderBy('created_at')->orderBy('platoon')->paginate(20);

        return view('students.index', compact('students', 'companies', 'approvedCount', 'terminationReasons'))
            ->with('i', ($request->input('page', 1) - 1) * 20);
    }


    public function search(Request $request)
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

        $students = Student::where('session_programme_id', $selectedSessionId);

        if ($request->company_id) {
            $students = $students->where('company_id', $request->company_id);
            if ($request->platoon) {
                $students = $students->where('platoon', $request->platoon);
            }
        }

        if ($request->name) {
            $students = $students->where(function ($query) use ($request) {
                $query->where('first_name', 'like', '%' . $request->name . '%')
                    ->orWhere('last_name', 'like', '%' . $request->name . '%')
                    ->orWhere('force_number', 'like', '%' . $request->name . '%')
                    ->orWhere('middle_name', 'like', '%' . $request->name . '%');
            });
        }

        // Clone the query before pagination to get approved count
        $approvedCount = (clone $students)->where('enrollment_status', 1)->where('status', 'approved')->count();
        
        $terminationReasons = TerminationReason::all()
                                ->filter(fn($r) => $r->category && is_string($r->category))
                                ->groupBy('category');

        $companies = Company::whereHas('students', function ($query) use ($selectedSessionId) {
            $query->where('session_programme_id', $selectedSessionId);
        })->get();

        $students = $students->where('enrollment_status', 1)->orderBy('first_name', 'asc')->latest()->paginate(90);

        return view('students.index', compact('students', 'companies', 'approvedCount', 'terminationReasons'))
            ->with('i', ($request->input('page', 1) - 1) * 90);

    }

    public function dismiss(Request $request, $id, AuditLoggerService $auditLogger)
    {
       $request->validate([
            'reason_id'     => 'required|exists:termination_reasons,id',
            'dismissed_at'  => 'required|date|before_or_equal:today',
        ], [
            'dismissed_at.before_or_equal' => 'Dismissal date cannot be in the future.',
        ]);

        $student = Student::findOrFail($id);
        StudentDismissal::create([
            'student_id'   => $student->id,
            'reason_id'    => $request->reason_id,
            'custom_reason'    => $request->custom_reason ?? 'null',
            'dismissed_at' => $request->dismissed_at,
        ]);

        // Update student status means dismissed en 1 means active/available
        $student->update([
            'enrollment_status' => 0, 
        ]);

        $auditLogger->logAction([
            'action' => 'dismiss_student',
            'target_type' => 'Student',
            'target_id' => $student->id,
            'metadata' => [
                'title' => $student->force_number. ' '.$student->first_name. ' '.$student->last_name,
                'programme' => $student->programme ?? null,
            ],
            'old_values' => [
                'enrollment_status' => 1,
            ],
            'new_values' => [
                'enrollment_status' => 0,
                'dismissal_data' => [
                    'reason'    =>  TerminationReason::where('id', $request->reason_id)->pluck('reason')->first(),
                    'custom_reason'    => $request->custom_reason ?? 'null',
                    'dismissed_at' => $request->dismissed_at,
                ],
            ],
            'request' => $request,
        ]);
        return redirect()->back()->with('success', 'Student has been dismissed successfully.');
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('students.wizards.stepOne');
    }

    public function createPage()
    {
        $programmes = Programme::get();
        return view('students.self.register', compact('programmes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'rank'        => 'required',
            //'education_level' => 'required',
            'first_name'  => 'required|max:30|alpha',
            'last_name'   => 'required|max:30|alpha',
            'middle_name' => 'required|max:30|alpha',
            'phone'       => 'nullable|numeric|unique:students',
            'weight'      => 'nullable|numeric',
            'height'      => 'nullable|numeric',
            'home_region' => 'required|string|min:4',
            'nin'         => 'required|numeric|digits:20|',
            'dob'         => 'required|string',
            'gender'      => 'required|max:1|alpha|regex:/^[M,F]/',
            'company'     => 'required|max:2|alpha',
            'platoon'     => 'required|max:1',
            'blood_group' => 'required|max:2',
        ]);
        if ($validator->errors()->any()) {
            return redirect()->back()->withErrors($validator->errors()); //->with('success',$validator->errors());
        }

        $student = Student::create([
            //questions(user created first or)
            'education_level' => $request->education_level,
            'rank'            => $request->rank,
            'force_number'    => $request->force_number,
            'first_name'      => $request->first_name,
            'middle_name'     => $request->middle_name,
            'last_name'       => $request->last_name,
            'nin'             => $request->nin,
            'home_region'     => $request->home_region,
            'phone'           => $request->phone,
            'height'          => $request->height,
            'weight'          => $request->weight,
            'gender'          => $request->gender,
            'dob'             => $request->dob,
            'company'         => $request->company,
            'platoon'         => $request->platoon,
            'blood_group'     => $request->blood_group,
        ]);
        return redirect()->route('students.index')->with('success', "Student created successfully.");
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'force_number' => 'required|regex:/^[A-Z]{1,2}\.\d+$/|unique:students,force_number',
            'first_name'   => 'required|max:50|regex:/^[A-Z][a-zA-Z\s\-\'\.\,]*$/',
            'middle_name'  => 'required|max:50|regex:/^[A-Z][a-zA-Z\s\-\'\.\,]*$/',
            'last_name'    => 'required|max:50|regex:/^[A-Z][a-zA-Z\s\-\'\.\,]*$/',
            'nin'          => 'required|numeric|unique:students,nin',
            'dob'          => 'required|date',
            'programme_id' => 'required|string|max:255',
            'email'        => 'required|string|email|max:100|unique:users',
            'gender'       => 'required',
            'password'     => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $input    = $request->all();
        $password = Hash::make($input['password']);
        $fullName = $request->first_name . ' ' . $request->middle_name . ' ' . $request->last_name;

        //Create User First
        $user = User::create([
            'name'     => $fullName,
            'email'    => $request->email,
            'password' => $password,
        ]);

        // Assign the 'student' role to the user
        $user->assignRole('student');

        $input['user_id'] = $user->id;

        // dd($input);
        //Now Create Student
        $student = new Student([
            'force_number'         => $request->input('force_number'),
            'first_name'           => $request->input('first_name'),
            'middle_name'          => $request->input('middle_name'),
            'last_name'            => $request->input('last_name'),
            'nin'                  => $request->input('nin'),
            'rank'                 => $request->input('rank'),
            'dob'                  => $request->input('dob'),
            'programme_id'         => $request->input('programme_id'),
            'session_programme_id' => $request->input('session_programme_id'),
            'email'                => $request->input('email'),
            'gender'               => $request->input('gender'),
            'user_id'              => $user->id,
            'password'             => Hash::make($request->input('password')),
        ]);

        $student->save();

        // return redirect()->back()->with('success', 'Your successfully created an account!');

        return redirect()->route('login')->with('success', "Your successfully created an account.");

    }

        public function myCourses()
    {
        $userId = auth()->id();
        // Get the student model for the logged-in user
        $student = Student::where('user_id', $userId)->first();

        if (!$student) {
            // Handle no student found, maybe redirect or show error
            abort(404, 'Student not found');
        }

        // Load courses with pivot data
        $courses = $student->courses()
            ->withPivot(['semester_id', 'course_type', 'credit_weight'])
            ->get();

        return view('students.mycourse', compact('courses'));
    }


    //Haitumiki for now
    public function dashboard()
    {
        $student         = auth()->user()->id;
        $pending_message = session('message');
        // $pending_message = "Your account is pending for approval.";

    
        return view('dashboard.student_dashboard', compact('pending_message'));
    }

    /**
     * Displaying user profile..
     */
    public function profile($id): View
    {
        $user = User::find($id);
        return view('students.profile', compact('user'));
    }

    public function approveStudent(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $student->approve();
            return redirect()->route('students.search', [
                'company_id' => $student->company_id,
                'platoon'    => $student->platoon,
            ])->with('success', 'Student approved successfully.');
        //return redirect()->route('students.index')->with('success', 'Student approved successfully.');
    }

    /**
     * Display the specified resource.
     */

    public function show($id)
    {
        $student      = Student::findOrFail($id);
        $safari_types = SafariType::all();
        $vitengo = Vitengo::all(); // or ->pluck('name', 'id') if you prefer

        $userEmail = $student->user ? $student->user->email : 'N/A';
        $lockups = DB::table('m_p_s')
            ->where('student_id', $student->id)
            ->orderByDesc('arrested_at')
            ->get();

        $dismissal = DB::table('student_dismissals')
                        ->join('termination_reasons', 'termination_reasons.id', '=', 'student_dismissals.reason_id')
                        ->select('student_dismissals.*', 'termination_reasons.reason as reason_label', 'termination_reasons.category')
                        ->where('student_dismissals.student_id', $student->id)
                        ->latest('dismissed_at')
                        ->first();
        return view('students.show', compact('student', 'safari_types', 'vitengo', 'lockups', 'dismissal', 'userEmail'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        $student = Student::find($id);
        $request->session()->put('student', $student);
        $page_name = "Edit Student Details";
        //return redirect("/students/create/");
        return view('students/wizards/stepOne', compact('student'));
        // return view('students.edit',compact('student', 'page_name'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
     

        $student = Student::findOrFail($id);

        try {
            $validated = $request->validate([
                'full_name' => [
                                    'nullable',
                                    'string',
                                    'max:250',
                                    function ($attribute, $value, $fail) {
                                        $parts = preg_split('/\s+/', trim($value));
                                        if (count($parts) != 3) {
                                            $fail('The full name must include first, middle, and last name (exactly 3 names).');
                                        }
                                    },
                                ],
                'force_number'      => 'nullable|string|max:10|unique:students,force_number,' . $student->id,
                'email'             => 'nullable|email|max:150|unique:students,email,' . $student->id,
                'phone'             => 'nullable|string|max:20',
                'dob'               => 'nullable|date',
                'blood_group'       => 'nullable|string|max:3',
                'education_level'   => 'nullable|string|max:100',
                'nin'               => 'nullable|string|max:23|unique:students,nin,' . $student->id,
                'home_region'       => 'nullable|string|max:100',
                'entry_region'      => 'nullable|string|max:100',
                'height'            => 'nullable|numeric|min:4.0|max:10',
                'weight'            => 'nullable|numeric|min:20|max:120',
                'account_number'    => 'nullable|string|max:30',
                'bank_name'         => 'nullable|string|max:100',
                'profession'        => 'nullable|string|max:100',
                'vitengo_id'        => 'nullable',
                'next_of_kin'       => 'nullable|array',
                'next_of_kin.*.name'         => 'nullable|string|max:100',
                'next_of_kin.*.relationship' => 'nullable|string|max:50',
                'next_of_kin.*.phone'        => 'nullable|string|max:20',
                'next_of_kin.*.address'      => 'nullable|string|max:150',
            ]);


        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->with('error', 'Form submission failed. Please check highlighted fields and try again.');
        }


        // Assign force_number only if student doesn't already have one
        if (empty($student->force_number) && !empty($validated['force_number'])) {
            $student->force_number = $validated['force_number'];
            \Log::info("Force number assigned to student ID {$student->id}: {$validated['force_number']}");
        }

        // Split full_name into parts if provided
        if (!empty($validated['full_name'])) {
            $parts = preg_split('/\s+/', trim($validated['full_name']));
            $student->first_name = $parts[0] ?? null;
            $student->middle_name = count($parts) > 2 ? $parts[1] : null;
            $student->last_name = count($parts) > 2 ? $parts[2] : ($parts[1] ?? null);
            \Log::info("Name split for student {$student->id}", [
                'first'  => $student->first_name,
                'middle' => $student->middle_name,
                'last'   => $student->last_name
            ]);
        }

        // Update all other fields safely
        $fields = [
            'email', 'phone', 'dob', 'blood_group', 'education_level',
            'home_region', 'entry_region', 'height', 'weight',
            'account_number', 'bank_name', 'profession', 'vitengo_id', 'next_of_kin'
        ];

        $student->update($request->only($fields));
   
        return redirect()->back()->with('success', 'Student information updated successfully!');
    }

    public function updatePasswords(Request $request)
    {
        if ($request->has('session_id')) {
            session(['selected_session' => $request->session_id]);
        }

        $selectedSessionId = session('selected_session', 1);

        if (! $selectedSessionId) {
            return redirect()->back()->withErrors('Please select a session before resetting students password.');
        }

        $students = Student::with('user')
            ->where('session_programme_id', $selectedSessionId)
            ->get();

        if ($students->isEmpty()) {
            return back()->withErrors('No students found for this programme.');
        }

        $count = 0;

        foreach ($students as $student) {
            if ($student->user) {
                $student->user->password = Hash::make(strtoupper($student->last_name));
                $student->user->must_change_password = true; // force change on next login
                $student->user->save();

                $count++;

                // Log each reset to default logs
                Log::info("Password reset for user_id={$student->user->id}, student_id={$student->id}, programme={$selectedSessionId}");
            }
        }

        // Log summary
        Log::info("Bulk password reset completed: {$count} students in programme {$selectedSessionId}");

        return back()->with('status', "Passwords resetted successfully for {$count} students in selected session programme {$selectedSessionId}.");
    }




    /**
     * Update the specified student in storage.
     */
    public function completeProfile($id)
    {
        $student = Student::where('id', $id)->first();
       
        return view('students.complete_profile', compact('student'));
    }

    public function profileComplete(Request $request, $id)
    {
        // dd($request->file('photo'));`````````````````````````````````
        $student = Student::findOrFail($id);
        $request->validate([
            //'education_level' => 'required',
            'home_region'                => 'required|string|min:4',
            'phone'                      => 'nullable|numeric|unique:students,phone,' . $student->id,
            'photo'                      => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'next_of_kin'                => 'nullable|array',
            'next_of_kin.*.name'         => 'nullable|string',
            'next_of_kin.*.phone'        => 'nullable|string',
            'next_of_kin.*.relationship' => 'nullable|string',
            'next_of_kin.*.address'      => 'nullable|string',
            'company'                    => 'required',
            'platoon'                    => 'required',
        ]);

        // if ($request->hasFile('photo')) {
        //     $photoFile = $request->file('photo');

        //     // Check if the file was uploaded correctly
        //     if ($photoFile->isValid()) {
        //         // Store the file and get the path
        //         $photoPath = $photoFile->store('photos', 'public');

        //         // Check the stored path
        //         if ($photoPath) {
        //             $student->photo = $photoPath;
        //             \Log::info('Photo stored at: ' . $photoPath);
        //         } else {
        //             \Log::error('Failed to store photo.');
        //             return back()->with('error', 'Failed to store photo.');
        //         }
        //     } else {
        //         \Log::error('Uploaded file is not valid.');
        //         return back()->with('error', 'Uploaded file is not valid.');
        //     }
        // }

        // Handle the file upload and resize using GD library
        if ($request->hasFile('photo')) {
            $photoFile = $request->file('photo');
            Log::info('Photo file received.', ['file' => $photoFile->getClientOriginalName()]);

            $photoPath = 'photos/' . uniqid() . '.' . $photoFile->getClientOriginalExtension();
            Log::info('Generated photo path.', ['path' => $photoPath]);

            // Get the original dimensions and create the GD resource
            list($width, $height) = getimagesize($photoFile->getPathname());
            Log::info('Original dimensions.', ['width' => $width, 'height' => $height]);

            $src = null;
            switch ($photoFile->getClientOriginalExtension()) {
                case 'jpeg':
                case 'jpg':
                    $src = imagecreatefromjpeg($photoFile->getPathname());
                    break;
                case 'png':
                    $src = imagecreatefrompng($photoFile->getPathname());
                    break;
                case 'gif':
                    $src = imagecreatefromgif($photoFile->getPathname());
                    break;
                case 'svg':
                    // SVG handling would require additional libraries
                    break;
            }

            if ($src) {
                // Create a new true color image with the desired dimensions
                $dst = imagecreatetruecolor(150, 170);
                Log::info('Created new true color image.');

                // Resize and copy the original image to the new image
                imagecopyresampled($dst, $src, 0, 0, 0, 0, 150, 170, $width, $height);
                Log::info('Resized and copied the original image to the new image.');

                // Save the new image
                $saveSuccess = false;
                switch (strtolower($photoFile->getClientOriginalExtension())) {
                    case 'jpeg':
                    case 'jpg':
                        $saveSuccess = imagejpeg($dst, storage_path('app/public/' . $photoPath));
                        break;
                    case 'png':
                        $saveSuccess = imagepng($dst, storage_path('app/public/' . $photoPath));
                        break;
                    case 'gif':
                        $saveSuccess = imagegif($dst, storage_path('app/public/' . $photoPath));
                        break;
                }

                if ($saveSuccess) {
                    Log::info('Saved the new image.', ['path' => $photoPath]);
                    $student->photo = $photoPath;
                } else {
                    Log::error('Failed to save the new image.');
                    return back()->with('error', 'Failed to save the photo.');
                }

                // Free up memory
                imagedestroy($src);
                imagedestroy($dst);
                Log::info('Freed up memory.');
            } else {
                Log::error('Unsupported image format.');
                return back()->with('error', 'Unsupported image format.');
            }
        }

        // dd($student->photo = $photoPath);
        // Update other student attributes...
        // $student->update($request->all());
        // Update or Create NextOfKin
        Student::updateOrCreate(
            ['id' => $student->id], // This is the condition to find the existing record
            [
                'education_level' => $request->education_level,
                'home_region'     => $request->home_region,
                'phone'           => $request->phone,
                'photo'           => $student->photo = $photoPath,
                'company'         => $request->company,

                
                'platoon'         => $request->platoon,
            ]
        );

        Log::info('Student saved successfully.', ['student' => $student]);

        // Update other student attributes...
        // $student->update($request->except('photo'));

        // Handle next-of-kin data
        $student->next_of_kin = $request->next_of_kin;
        $student->save();

        return redirect()->route('profile', $student->user_id)->with('success', 'Student updated successfully.');
        // return back()->with('success', 'Students updated  successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $student = Student::find($id);

        // Check if the student has a related user
        if ($student->user) {
            $student->user->delete();
        }

        // Delete the student
        $student->delete();

        return redirect('students')->with('success', "Student and related user deleted successfully.");
    }

    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'import_file' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (! in_array($value->getClientOriginalExtension(), ['csv', 'xls', 'xlsx'])) {
                        $fail('Incorrect :attribute type choose.');
                    }
                },
            ],
        ]);
        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first());
        }
        try {
            Excel::import(new BulkImportStudents, filePath: $request->file('import_file'));
        } catch (Exception $e) {
            // If an error occurs during import, catch the exception and return the error message
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
        return redirect()->route('students.index')->with('success', 'Students Uploaded  successfully.');
    }

    public function updateStudents(Request $request)
    {
        // Check if a session ID has been submitted
        if (request()->has('session_id')) {
            // Store the selected session ID in the session
            session(['selected_session' => request()->session_id]);
        }
        
        // Check if a session is selected
        $selectedSessionId = session('selected_session');
        if (! $selectedSessionId) {
            return redirect()->back()->withErrors('Please select a session before updating students.');
        }

        $import = new UpdateStudentDetails();

        try {
            Excel::import($import, $request->file('students_file'));

            // Return with success, warnings, and errors
            return redirect()->back()->with([
                'success'  => 'Student details updated successfully!',
                'warnings' => $import->warnings,
                'errors'   => $import->errors,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }

    // public function createStepOne()
    // {
    //     return view('students.wizards.stepOne');
    // }
    public function postStepOne(Request $request, $type)
    {
        $student_validate_rule = "";
        if ($type == "edit") {
            $student               = Student::findOrFail($request->id);
            $student_validate_rule = '|unique:students,force_number,' . $student->id . ',id';
        } else {
            $student_validate_rule = '|unique:students,force_number';
        }

        $validatedData = $request->validate([
            'force_number' => 'nullable|regex:/^[A-Z]{1,2}\.\d+$/' . $student_validate_rule,
            'rank'         => 'nullable',
            //'education_level' => 'required',
            'first_name'   => 'required|max:30',
            'last_name'    => 'required|max:30',
            'middle_name'  => 'required|max:30',
            'home_region'  => 'nullable|string|min:4',
            // 'rank' => 'required',
            // 'education_level' => 'required',
        ]);
        $companies = Company::all();
        if (empty($request->session()->get('student'))) {
            $student = new Student();
            $student->fill($validatedData);
            $request->session()->put('student', $student);
        } else {

            $student = $request->session()->get('student');

            $student['force_number'] = $validatedData['force_number'];
            //$student['rank'] = $validatedData['rank'];
            //$student['education_level'] = $validatedData['education_level'];
            $student['rank']            = $validatedData['rank'];
            $student['education_level'] = $request->education_level;
            $student['first_name']      = $validatedData['first_name'];
            $student['middle_name']     = $validatedData['middle_name'];
            $student['last_name']       = $validatedData['last_name'];
            $student['home_region']     = $validatedData['home_region'];

            $request->session()->put('student', $student);
        }
        if ($type == "edit") {
            return view('students.wizards.stepTwo', compact('student', 'companies'));

        }
        return view('students.wizards.stepTwo', compact('companies', 'type'));

    }

    public function createStepTwo(Request $request)
    {
        $student = $request->session()->get('student');
        return view('students.wizards.stepTwo', compact('student'));
    }

    public function postStepTwo(Request $request, $type)
    {

        $companies = Company::all();

        $student = $request->session()->get('student');
        // return $student;
        $validator = Validator::make($request->all(), [
            'phone'      => 'nullable|numeric|unique:students,phone,' . $student->id . ',id',
            'nin'        => 'nullable|digits:20|numeric|unique:students,nin,' . $student->id . ',id',
            'dob'        => 'nullable|string',
            'gender'     => 'required|max:1|alpha|regex:/^[M,F]/',
            'company_id' => 'required|numeric',
            'platoon'    => 'required|max:2',
            'weight'     => 'nullable|numeric',
            'height'     => 'nullable|numeric',
        ]);
        if ($validator->errors()->any()) {
            $companies = Company::all();
            return view('students.wizards.stepTwo', compact('companies', 'type'))->withErrors($validator->errors());
        }
        $student['phone']      = $request->phone;
        $student['nin']        = $request->nin;
        $student['dob']        = $request->dob;
        $student['gender']     = $request->gender;
        $student['company_id'] = $request->company_id;
        $student['platoon']    = $request->platoon;
        $student['weight']     = $request->weight;
        $student['height']     = $request->height;
        $request->session()->put('student', $student);
        if ($type == "create") {
            return redirect('students/create/step-three/create')->with('success', 'Student created successfully.');
        }
        return view('students.wizards.stepThree', compact('student', 'companies'));

    }

    public function createStepThree(Request $request)
    {
        $student = $request->session()->get('student');
        return view('students.wizards.stepThree', compact('student'));
    }

    public function postStepThree(Request $request, $type)
    {
        $validatedData = $request->validate([

        ]);
        $validator = Validator::make($request->all(), [
            'next_kin_phone'        => 'nullable|numeric|',
            'next_kin_names'        => 'nullable|max:30',
            'next_kin_address'      => 'nullable|string|min:4',
            'next_kin_relationship' => 'nullable|string|min:4',
        ]);
        if ($validator->errors()->any()) {
            $companies = Company::all();
            return view('students.wizards.stepThree', compact('companies', 'type'))->withErrors($validator->errors());
        }
        $student                          = $request->session()->get('student');
        $student['next_kin_phone']        = $request->next_kin_phone;
        $student['next_kin_names']        = $request->next_kin_names;
        $student['next_kin_address']      = $request->next_kin_address;
        $student['next_kin_relationship'] = $request->next_kin_relationship;
        if ($type == 'create') {
            $selectedSessionId = session('selected_session');
            if (! $selectedSessionId) {
                $selectedSessionId = 1;
            }

            $student['session_programme_id'] = $selectedSessionId;
        }
        //$student->fill($validatedData);
        $student->save();
        //$request->session()->put('student', $student);
        $student = $request->session()->get('student');
        $request->session()->forget('student');
        //return $student;
        if ($type == "edit") {
            return redirect()->route('students.search', [
                'company_id' => $student->company_id,
                'platoon'    => $student->platoon,
            ])->with('success', 'Student updated successfully.');

        } else {
            $message = "Student created successfully.";
        }
        return redirect()->route('students.index')->with('success', $message);

    }

    public function activate_beat_status($studentId)
    {
        $student = Student::find($studentId);

        $student->beat_status = 1;
        $student->save();
        return redirect()->back()->with('success', 'Beat activated successfully.');
    }

    public function deactivate_beat_status($studentId)
    {
        $student = Student::find($studentId);

        $student->beat_status = 0;
        $student->save();
        return redirect()->back()->with('success', 'Beat deactivated successfully.');
    }

    public function downloadSample()
    {
        $path = storage_path('app/public/sample/basic recruit course students.csv');
        if (file_exists($path)) {
            return response()->download($path);
        }
        abort(404);
    }

    public function updateFastStatus(Request $request, $studentId, $fastStatus)
    {
        $student = Student::findOrFail($studentId);
        if (! $student) {
            return redirect()->back()->with('success', 'Student with the ' . $studentId . ' Id is not found.');
        }

        if ($fastStatus == 1) {
            $student->fast_status = 1;
        } else if ($fastStatus == 0) {
            $student->fast_status = 0;
        } else {
            return redirect()->route('students.index')->with('success', 'Please specify fasting status.');
        }

        $student->save();

        return redirect()->route('students.index')->with('success', 'Fasting status updated successfully.');
    }

    public function toSafari($studentId)
    {
        $student = Student::findOrFail($studentId);
        if (! $student) {
            return redirect()->back()->with('error', 'Student with the ' . $studentId . ' Id is not found.');
        }

        $student->beat_status = 4;
        $student->save();
        return redirect()->route('students.index')->with('success', 'Beat status to Safari updated successfully.');
    }

    public function BackFromsafari($studentId)
    {
        $student = Student::findOrFail($studentId);
        if (! $student) {
            return redirect()->back()->with('error', 'Student is not found.');
        }

        $student->beat_status = 1;
        $student->save();
        return redirect()->route('students.index')->with('success', 'Beat status back from Safari updated successfully.');
    }

  // Reflector Day Shift function
public function reflectorDayShift($studentId)
{
    $student = Student::findOrFail($studentId);

    $student->beat_status = 5; // Reflector - Day
    $student->save();

    return redirect()->route('students.index')
        ->with('success', 'Reflector Day shift assigned successfully.');
}


   // Arrange Based on Day and Night Shift
public function assignReflectorDayOnly($studentId)
{
    $student = Student::findOrFail($studentId);

    $hour = now()->hour;

    if ($hour >= 6 && $hour < 18) {
        $student->beat_status = 5; // Day
    } else {
        return redirect()->back()
            ->with('error', 'Day shift can only be assigned between 6 AM and 6 PM.');
    }

    $student->save();

    return redirect()->back()
        ->with('success', 'Reflector Day shift assigned successfully.');
}



    public function generatePdf($platoon, $company_id)
    {
        // Check if a session ID has been submitted
        if (request()->has('session_id')) {
            // Store the selected session ID in the session
            session(['selected_session' => request()->session_id]);
        }
        
        $platoon           = Platoon::where('name', $platoon)->where('company_id', $company_id)->first();
        
        $selectedSessionId = session('selected_session');
        if (! $selectedSessionId) {
            $selectedSessionId = 1;
        }

        $students = $platoon->students()->where('session_programme_id', $selectedSessionId)->where('company_id', $company_id)->get();

        $pdf = PDF::loadView('students.pdfSheet', compact('students', 'platoon'));
        $pdf->set_option('margin_top', 10);
        $pdf->set_option('isHtml5ParserEnabled', true);
        $pdf->set_option('isPhpEnabled', true);
        return $pdf->stream($platoon->name . ".pdf");
    }
}
