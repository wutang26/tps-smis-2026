<?php

namespace App\Http\Controllers;

use App\Imports\StudentPostImport;
use App\Models\Company;
use App\Models\Post;
use App\Models\Student;
use App\Models\StudentPost;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class StudentPostController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:post-view|post-create|post-edit|post-delete', ['only' => ['index', 'store', 'import']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index_old(Request $request)
    {
        $selectedSessionId = session('selected_session', 1);
        $session_post = Post::where('session_programme_id', $selectedSessionId)->first();
        if (! $session_post) {
            return redirect()->back()->with('info', 'No post uploaded for the current session.');
        }
        $companies = Company::whereHas('students', function ($query) use ($selectedSessionId) {
            $query->where('session_programme_id', $selectedSessionId); // Filter students by session
        })->get();
        $posts = StudentPost::paginate(20);

        return view('students.posts.index', compact('posts', 'companies'));
    }

    public function index(Request $request)
    {
        $selectedSessionId = session('selected_session', 1);

        // Get the first post for this session
        $session_post = Post::where('session_programme_id', $selectedSessionId)->first();

        // If there's no post at all
        if (! $session_post) {
            return redirect()->back()->with('info', 'No post uploaded for the current session.');
        }
        // If the post exists but is not published
        if ($session_post->status !== 'published' || ! Auth::user()->can('post-view')) {

            // Check if user has permission to view unpublished posts
            // if () {
            return redirect()->back()->with('info', 'No post uploaded for the current session.');
            // }

        }

        // If passed all checks, get posts (you can restrict to published or not)
        $posts = StudentPost::with(['post', 'student.company'])
            ->whereHas('post', function ($query) use ($selectedSessionId) {
                $query->where('session_programme_id', $selectedSessionId);
            })->paginate(20);
        $companies = Company::whereHas('students', function ($query) use ($selectedSessionId) {
            $query->where('session_programme_id', $selectedSessionId);
        })->get();

        return view('students.posts.index', compact('posts', 'companies', 'selectedSessionId'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('students.posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Display the specified resource.
     */
    public function show(StudentPost $studentPost)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudentPost $studentPost)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StudentPost $studentPost)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentPost $studentPost)
    {
        //
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
            Excel::import(new StudentPostImport, filePath: $request->file('import_file'));
        } catch (Exception $e) {
            // If an error occurs during import, catch the exception and return the error message
            return redirect()->back()->with('error', 'Import failed: '.$e->getMessage());
        }

        return redirect()->route('students-post.index')->with('success', 'Students post Uploaded/Updated  successfully.');
    }

    public function search(Request $request)
    {
        // Check if a session ID has been submitted
        if ($request->has('session_id')) {
            session(['selected_session' => $request->session_id]);
        }

        $selectedSessionId = session('selected_session', 1);

        // Build student query
        $students = Student::where('session_programme_id', $selectedSessionId)
            ->when($request->company_id, fn ($q) => $q->where('company_id', $request->company_id))
            ->when($request->platoon, fn ($q) => $q->where('platoon', $request->platoon))
            ->when($request->name, function ($q, $name) {
                $q->where(function ($sub) use ($name) {
                    $sub->where('first_name', 'like', "%{$name}%")
                        ->orWhere('last_name', 'like', "%{$name}%")
                        ->orWhere('middle_name', 'like', "%{$name}%")
                        ->orWhere('force_number', 'like', "%{$name}%");
                });
            });

        // Get matching student IDs
        $studentIds = $students->pluck('id');

        // Fetch posts for these students (use pagination)
        $posts = StudentPost::whereIn('student_id', $studentIds)
            ->paginate(90)
            ->withQueryString();

        // Get companies for dropdown
        $companies = Company::whereHas('students', function ($q) use ($selectedSessionId) {
            $q->where('session_programme_id', $selectedSessionId);
        })->get();

        return view('students.posts.index', compact('posts', 'companies'))
            ->with('i', ($request->input('page', 1) - 1) * 90);

    }

    public function downloadSample()
    {
        $path = storage_path('app/public/sample/students_post.csv');
        if (file_exists($path)) {
            return response()->download($path);
        }
        abort(404);
    }

    
    public function edit_post()
    {
        return view('students.posts.edit');
    }


}
