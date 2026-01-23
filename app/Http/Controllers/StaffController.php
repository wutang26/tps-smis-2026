<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\EducationLevel;
use App\Models\School;
use App\Models\WorkExperience;
use App\Models\Staff;
use App\Models\Company;
use App\Models\User;
use App\Models\Department;
use App\Models\NextOfKin;
use App\Imports\BulkImportStaff;
use App\Imports\UpdateStaffDetails;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Services\AuditLoggerService;
use App\Models\StaffStatus;
class StaffController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:staff-list|staff-create|staff-edit|staff-delete', ['only' => ['index']]);
         $this->middleware('permission:staff-create', ['only' => ['create','store','import']]);
         $this->middleware('permission:staff-edit', ['only' => ['edit','update','updateProfile']]);
         $this->middleware('permission:staff-delete', ['only' => ['destroy']]);
         $this->middleware('permission:staff-view|profile-list', ['only' => ['profile','view']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $companies = Company::all();
        $staffs = Staff::orderBy('id','DESC')->paginate(10);
        return view('staffs.index',compact('staffs', 'companies'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::get();
        $companies = Company::all();
        $roles = Role::pluck('name','name')->all();
        return view('staffs.create', compact('departments', 'roles','companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate request
        $this->validate($request, [
            'forceNumber' => 'required|unique:staff',
            'rank' => 'required',
            'company_id' => 'required',
            'firstName' => 'required',
            'lastName' => 'required',
            'gender' => 'required',
            'email' => 'required|email|unique:users,email',
            'roles' => 'required',
            'created_by' => 'required|exists:users,id',
        ]);

        // Process input
        $input = $request->all();
        $password = Hash::make(strtoupper($input['lastName']));
        $fullName = $request->firstName . ' ' . $request->middleName . ' ' . $request->lastName;

        // Create User
        $user = User::create([
            'name' => $fullName,
            'email' => $request->email,
            'password' => $password,
        ]);
        $user->assignRole($request->input('roles'));

        // Set user_id for staff
        $input['user_id'] = $user->id;

        // Create Staff
        $staff = Staff::create($input);

        // Check if Next of Kin full name is provided
        if (!empty($request->input('nextofkinFullname'))) {
            // Validate NextOfKin fields
            $this->validate($request, [
                'nextofkinFullname' => 'required',
                'nextofkinRelationship' => 'required',
                'nextofkinPhysicalAddress' => 'required',
            ]);

            // Create NextOfKin
            NextOfKin::Create([
                'nextofkinFullname' => $input['nextofkinFullname'],
                'nextofkinRelationship' => $input['nextofkinRelationship'],
                'nextofkinPhoneNumber' => $input['nextofkinPhoneNumber'],
                'nextofkinPhysicalAddress' => $input['nextofkinPhysicalAddress'],
                'staff_id' => $staff->id,
            ]);
        }

        return redirect()->route('staffs.index')->with('success', 'Staff created successfully.');
    }

    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'import_file' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!in_array($value->getClientOriginalExtension(), ['csv', 'xls', 'xlsx'])) {
                        $fail('Incorrect :attribute type choose.');
                    }
                }
            ],
        ]);
        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first());
        }
        Excel::import(new BulkImportStaff, filePath: $request->file('import_file'));
        return back()->with('success', 'Staff Uploaded  successfully.');
    }

    /**
     * Displaying user profile..
     */
    public function profile($id):View
    {
        $user = User::find($id);
        return view('staffs.profile',compact('user'));
    }
    
    /**
     * Display the specified resource.
     */
    public function show(Staff $staff)
    {
        return view('staffs.show', compact('staff'));
    }

    /**
     * Display the CV for a specific staff member.
     */
    // public function resume1()
    // {
    //     $id=1;
    //         $staff = Staff::with('department')->find(1);
        
    //         if (!$staff) {
    //             return abort(404, 'Staff member not found.');
    //         }
        
    //         return view('staffs.resume', compact('staff'));        
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Staff $staff)
    {        
        // Ensure the roles relationship is loaded
        $staff->load('roles');
        $companies = Company::all();
        $user = User::find($staff->user_id);

        $departments = Department::get();
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
        $staffNextofkin = NextOfKin::where('staff_id', $staff->id)->first();

        return view('staffs.edit', compact('staff', 'departments','roles', 'userRole', 'staffNextofkin','companies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Staff $staff,AuditLoggerService $auditLogger)
{
    $this->validate($request, [
        'forceNumber' => 'required|unique:staff,forceNumber,' . $staff->id,
        'rank' => 'required',
        'firstName' => 'required',
        'lastName' => 'required',
        'gender' => 'required',
        'company_id' => 'required',
        // 'DoB' => 'required|date', 
        // 'maritalStatus' => 'required', 
        // 'phoneNumber' => 'required',
        'email' => 'required|unique:staff,email,' . $staff->id,
        'department_id' => 'required|integer',
        // 'roles' => 'required',
        // 'educationLevel' => 'required',
        // 'contractType' => 'required',
        // 'joiningDate' => 'required|date',
        // 'location' => 'required',
        'updated_by' => 'required|exists:users,id'
    ]);

    // Retrieve user ID associated with the staff
    $userId = $staff->user_id;  // Better way to access the user_id directly
    $fullName = $request->firstName . ' ' . $request->middleName . ' ' . $request->lastName;
    
    $input = $request->all();
    // Update the associated user details
    $user = User::find($userId);  // Simplified user retrieval

    if ($user) {
        $userSnapshot = $user;
        $user->update([
            'name' => $fullName,
            //'email' => $input['email']
        ]);
    }

    // Update user roles
    DB::table('model_has_roles')->where('model_id', $userId)->delete();
    $user->assignRole($request->input('roles'));
    $staffSnapshot = clone $staff;
    // Update staff details
    $staff->update($input);

    // Check if Next of Kin full name is provided
    if ($request->has('nextofkinFullname') && !empty($request->input('nextofkinFullname'))) {
        // Validate NextOfKin fields
        $this->validate($request, [
            'nextofkinFullname' => 'required',
            'nextofkinRelationship' => 'required',
            'nextofkinPhoneNumber' => 'required',
            'nextofkinPhysicalAddress' => 'required',
        ]);

        // Update or Create NextOfKin
        NextOfKin::updateOrCreate(
            ['staff_id' => $staff->id], // This is the condition to find the existing record
            [
                'nextofkinFullname' => $input['nextofkinFullname'],
                'nextofkinRelationship' => $input['nextofkinRelationship'],
                'nextofkinPhoneNumber' => $input['nextofkinPhoneNumber'],
                'nextofkinPhysicalAddress' => $input['nextofkinPhysicalAddress'],
                'staff_id' => $staff->id,
            ]
        );
    }

            //capture staff snapshot before deleted
        
        // Then, delete the staff member
        
        $auditLogger->logAction([
        'action' => 'update_staff',
        'target_type' => 'Staff',
        'target_id' => $staffSnapshot->id,
        'metadata' => [
            'title' => $staffSnapshot->forceNumber. ' '.$staffSnapshot->firstName . ' '.$staffSnapshot->lastName,
            'department' => $staffSnapshot->department ?? null,
        ],
        'old_values' => [
            'staff' => $staffSnapshot,
            'user' => $userSnapshot,
        ],
        'new_values' => [
            'staff' => $staff,
            'user' => $userSnapshot,
        ],
        'request' => $request,
    ]);

    // Redirect back with a success message
    return redirect()->route('staffs.index')->with('success', 'Staff updated successfully.');
}

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'staffs_file' => 'required|file|mimes:xlsx,csv',
        ]);

        $import = new UpdateStaffDetails();

        try {
            Excel::import($import, $request->file('staffs_file'));

            return redirect()->back()->with([
                'success'      => 'Staff details updated successfully!',
                'warnings'     => $import->warnings,
                'importErrors' => $import->errors,
                'created'      => $import->created,
                'updated'      => $import->updated,
                'skipped'      => $import->skipped,
                'failed'       => $import->failed,
            ]);
        } catch (\Exception $e) {
            Log::error('Bulk import failed: ' . $e->getMessage());

            return redirect()->back()->with([
                'importErrors' => ['Import failed: ' . $e->getMessage()],
            ]);
        }
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Staff $staff, Request $request, AuditLoggerService $auditLogger)
    {
        // First, delete the corresponding user
        $user = $staff->user; // Assuming there's a relationship defined between Staff and User
        $userSnapshot = $user;
        if ($user) {
            //capture user snapshot before deleted
            
            $user->delete();
        }

        //capture staff snapshot before deleted
        $staffSnapshot = clone $staff;
        // Then, delete the staff member
        $staff->delete();
        
        $auditLogger->logAction([
        'action' => 'delete_staff',
        'target_type' => 'Staff',
        'target_id' => $staffSnapshot->id,
        'metadata' => [
            'title' => $staffSnapshot->forceNumber. ' '.$staffSnapshot->firstName . ' '.$staffSnapshot->lastName,
            'department' => $staffSnapshot->department ?? null,
        ],
        'old_values' => [
            'user' => $userSnapshot,
            'staff' => $staffSnapshot,
        ],
        'new_values' => null,
        'request' => $request,
    ]);
        return redirect()->route('staffs.index')->with('success', 'Staff and corresponding user deleted successfully.');
    }
    public function downloadSample () {
        $path = storage_path('app/public/sample/staff sample.xlsx');
        if (file_exists($path)) {
            return response()->download($path);
        }
        abort(404);
    }
public function search(Request $request)
{
    $staffs = Staff::with(['company', 'department']);

    if ($request->filled('company_id')) {
        $staffs->where('company_id', $request->company_id);
    }

    if ($request->filled('name')) {
        $staffs->where(function ($query) use ($request) {
            $query->where('firstName', 'like', '%' . $request->name . '%')
                  ->orWhere('middleName', 'like', '%' . $request->name . '%')
                  ->orWhere('forceNumber', 'like', '%' . $request->name . '%')
                  ->orWhere('lastName', 'like', '%' . $request->name . '%');
        });
    }

    $companies = Company::all();
    $staffs = $staffs->orderBy('id', 'desc')->paginate(10)->appends($request->all());

    return view('staffs.index', compact('staffs', 'companies'))
        ->with('i', ($request->input('page', 1) - 1) * 10);
}


    public function generateResume($staffId)
    {
        $staff = Staff::findOrFail($staffId);
        return view('staffs.resume', compact('staff'));
    }
    
    public function generateResumexx($id)
    {
        // Fetch the staff record and its associated user
        $staff = Staff::with('user')->findOrFail($id);

        // Fetch related data using the user relation
        $user = $staff->user;

        $workExperiences = $user->workExperiences; // Use user_id to fetch work experiences
        $computerLiteracies = $user->computerLiteracies; // Fetch computer literacy details
        $languageProficiencies = $user->languageProficiencies; // Fetch language proficiencies
        $trainingsAndWorkshops = $user->trainingsAndWorkshops; // Fetch trainings and workshops
        $referees = $user->referees; // Fetch referees

        // Pass all data to the view
        return view('staffs.resume', compact(
            'staff', 
            'workExperiences', 
            'computerLiteracies', 
            'languageProficiencies', 
            'trainingsAndWorkshops', 
            'referees'
        ));
    }

    public function create_cv($staff_id){
        $staff = Staff::find($staff_id);
        $education_levels = EducationLevel::all();
        return view('staffs.create_cv', compact('staff', 'education_levels'));
    }

    public function generateResumeePdf($staff_id){
        $staff = Staff::find($staff_id);
        $education_levels = EducationLevel::all();
        $pdf = PDF::loadView('staffs.download_resumeePdf',compact('staff', 'education_levels'));
        $pdf->set_option('isHtml5ParserEnabled', true);
        $pdf->set_option('isPhpEnabled', true);

        return $pdf->stream("resumee.pdf");
    }
    public function update_cv(Request $request, $staff_id){
        $staff = Staff::find($staff_id);
        $education_levels = EducationLevel::all();
        // $request->validate([
        //     'father_names' => 'null|string',
        //     'father_ward_of_birth' => 'required_if:father_names,!null|string',
        //     'father_district_of_birth' => 'required_if:father_names,!null|string',
        //     'father_region_of_birth' => 'required_if:father_names,!null|string',
        // ]);
        $staff-> fatherParticulars = [
             $request-> father_names,
             $request-> father_village_of_birth,
            $request-> father_ward_of_birth,
             $request-> father_district_of_birth,
             $request-> father_region_of_birth,
        ];

        $staff-> motherParticulars = [
              $request-> mother_names,
             $request-> mother_village_of_birth,
             $request-> mother_ward_of_birth,
             $request-> mother_district_of_birth,
             $request-> mother_region_of_birth,
        ];
        $staff-> parentsAddress = [
             $request-> parentsVillage,
             $request-> parentsWard,
              $request-> parentsDistrict,
            $request-> parentsRegion,
        ];
        $staff->save();
        return redirect()->back()->with('success', 'CV updated successfully.');
        //return view('staffs.create_cv', compact('staff', 'education_levels'));
    }

    public function update_school_cv(Request $request, $staff_id){
        //return $request->all();
        $staff = Staff::find($staff_id);
        if($request->primary_school_name){
            $message = 'Primary';
$school = School::updateOrCreate(
    // 👇 These are the "uniqueness" conditions
    [
        'staff_id' => $staff_id,
        'education_level_id' => 1,
    ],
    // 👇 These are the values that will be inserted/updated
    [
        'name' => $request->primary_school_name,
        'admission_year' => $request->primary_school_YoA,
        'graduation_year' => $request->primary_school_YoG,
        'ward' => $request->primary_school_ward,
        'village' => $request->primary_school_village,
        'district' => $request->primary_school_district,
        'region' => $request->primary_school_region,
    ]
);
          
        }

        if($request->secondary_school_name){
            $message = $request->secondary_school_type == 2? 'O-Level': 'A-Level';
            $school = School::updateOrCreate(
                [
                'staff_id' => $staff_id,
                'education_level_id' => 2,                  
                ],
                [
                'staff_id' => $staff_id,
                'name' =>$request->secondary_school_name,
                'education_level_id' =>$request->secondary_school_type,
                'admission_year' =>$request->secondary_school_YoA,
                'graduation_year' =>$request->secondary_school_YoG,
                'award' =>$request->secondary_school_ward,
                'village' =>$request->secondary_school_village,
                'district' =>$request->secondary_school_district,
                'region' =>$request->secondary_school_region
            ] );            
        }

        if($request->advanced_secondary_school_name){
            $message = $request->advanced_secondary_school_type == 2? 'O-Level': 'A-Level';
            $school = School::updateOrCreate(
                [
                'staff_id' => $staff_id,
                'education_level_id' => 3,                  
                ],
                [
                'staff_id' => $staff_id,
                'name' =>$request->advanced_secondary_school_name,
                'education_level_id' =>$request->advanced_secondary_school_type,
                'admission_year' =>$request->advanced_secondary_school_YoA,
                'graduation_year' =>$request->advanced_secondary_school_YoG,
                'award' =>$request->advanced_secondary_school_ward,
                'village' =>$request->advanced_secondary_school_village,
                'district' =>$request->advanced_secondary_school_district,
                'region' =>$request->advanced_secondary_school_region
            ] );            
        }

        if ($request->has('colleges_name')) {
    $submittedNames = array_filter($request->colleges_name); // remove empty

    // 1. Update or create submitted records
    foreach ($submittedNames as $index => $name) {
        School::updateOrCreate(
            [
                'staff_id'           => $staff_id,
                'education_level_id' => 4,
                'name'               => $name,
            ],
            [
                'admission_year'  => $request->colleges_YoA[$index] ?? null,
                'graduation_year' => $request->colleges_YoG[$index] ?? null,
                'duration'        => $request->duration[$index] ?? null,
                'country'         => $request->colleges_name_region[$index] ?? null,
                'award'           => $request->colleges_award[$index] ?? null,
                'region'          => $request->colleges_name_region[$index] ?? null,
            ]
        );
    }

    // 2. Delete old records that were not resubmitted
    School::where('staff_id', $staff_id)
        ->where('education_level_id', 4)
        ->whereNotIn('name', $submittedNames)
        ->delete();

    $message = 'Colleges';
}


        if($request->venue){
            $message = 'Other';
            $school = School::create([
                'staff_id' => $staff_id,
                'name' =>$request->college,
                'education_level_id' =>5,
                'duration' =>$request->duration,
                'country' =>$request->colleges_name_region,
                'award' =>$request->award,
                'venue' =>$request->venue,
            ] );            
        }
        return redirect()->route('staff.cv',['staffId'=>$staff->id])->with('success', $message.' updated successfully.');
    }
    public function update_work_experience(Request $request, $staff_id){

        $validator = Validator::make($request->all(), [
            'start_date'    => ['required', 'integer', 'min:1960'],
            'end_date'      => ['nullable', 'integer', 'min:1960', 'gte:start_date'],
            'institution'   => ['required', 'string', 'max:255'],
            'job_title'     => ['required', 'string', 'max:255'],
            'address'       => ['required', 'string', 'max:255'],
            'duties'        => ['required', 'array', 'min:1'],
            'duties.*'      => ['required', 'string', 'min:3'],
        ]);
        
        
        if ($validator->fails()) {
            // Redirect back with validation errors
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $staff = Staff::find($staff_id);
        WorkExperience::create([
            'user_id' =>$staff->id,
            'institution'=> $request->institution,
            'address'=> $request->address,
            'job_title'=> $request->job_title,
            //'position' => $request->position, 
            'start_date' =>  $request->start_date,
            'end_date' => $request->end_date,
            'duties' => json_encode($request->duties),
        ]);


        return redirect()->route('staff.cv',['staffId'=>$staff->id])->with('success', 'Cv updated successfully.');
    }

    public function deleteWorkExprience($experienceId){
        $workExperience = WorkExperience::find($experienceId);

        $workExperience->delete();

        return redirect()->back()->with('success', 'Work experience deleted successfully.');
    }

    public function deleteSchool($schoolId){
        $school = School::find($schoolId);

        $school->delete();

        return redirect()->back()->with('success', 'School or Professional deleted successfully.');
    }
   public function change_status(Request $request)
{
    $status = $request->status;
    $staff = Staff::find($request->staff_id);

    if (!$staff) {
        return redirect()->back()->with('info', 'Staff is not found.');
    }

    // If staff's current status is not active, update the previous StaffStatus record's end_date only
    if ($staff->status !== 'active') {
        $previousRecord = StaffStatus::where('staff_id', $staff->id)
            ->whereNull('end_date')
            ->latest()
            ->first();

        if ($previousRecord) {
            $previousRecord->end_date = now();
            $previousRecord->save();
        }

        return redirect()->route('staffs.summary.index')
            ->with('success', 'Previous status record updated successfully.');
    }

    // If staff's current status is active, update status and create a new StaffStatus record
    $previousStatus = $staff->status;
    $staff->status = $status;
    $staff->save();

    StaffStatus::create([
        'description' =>$request->description,
        'staff_id' => $staff->id,
        'previous_status' => $previousStatus,
        'current_status' => $status,
        'start_date' => $request->start_date ?? now(),
        'end_date' => null,
        'user_id' => $request->user()->id,
    ]);

    return redirect()->route('staffs.summary.index')
        ->with('success', 'Status changed and new record created successfully.');
}
}