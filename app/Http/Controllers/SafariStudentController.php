<?php

namespace App\Http\Controllers;

use App\Models\SafariStudent;
use App\Models\Student;
use App\Models\SafariType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SafariStudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Student $student)
    {
        $validator = Validator::make($request->all(), [
            'safari_type_id' => 'required|exists:safari_types,id',
            'description' => 'required|string|max:255',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', implode(' ',$validator->errors()->all()));
        }
        SafariStudent::create([
            'student_id' => $student->id,
            'safari_type_id' => $request->safari_type_id,
            'description' => $request->description,
            'previous_beat_status' => $student->beat_status,
            'current_beat_status' => 4, 
            'created_by' =>$request->user()->id
        ]);

        $student->beat_status = 4;
        $student->save();

        $safari_types = SafariType::all();
        return redirect()->route('students.show', compact('student', 'safari_types'))->with('success', 'Student safari recorded succssfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SafariStudent $safariStudent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SafariStudent $safariStudent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateSafari(Request $request,SafariStudent $safariStudent)
    {
        session()->forget('success');
        $safariStudent->status = 'returned';
        $safariStudent->updated_by = $request->user()->id;
        $student = $safariStudent->student;  
        $student->beat_status = $safariStudent->previous_beat_status;
        $safariStudent->save();
        $student->save();
        return redirect()->back()->with('success','Student returned successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SafariStudent $safariStudent)
    {
        //
    }
}
