<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;

class ResumeController extends Controller
{
    public function showSearchForm()
    {
        return view('resume.search');
    }

    public function displayResume(Request $request)
    {
        $request->validate([
            'force_number' => 'required|string'
        ]);

        $staff = Staff::where('forceNumber', $request->force_number)->first();

        if (!$staff) {
            return back()->with('error', 'Staff not found.');
        }

        return view('resume.display', compact('staff'));

//         return view('resume.display', [
//     'staff' => $staff,
//     'educationLevel' => $staff->education,
//     'work_experience' => $staff->workExperience,
//     'trainings' => $staff->trainings,
// ]);
    }
}
