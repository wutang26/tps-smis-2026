<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\Students;
use App\Models\Staff;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\LeaveApprovedNotification;
use App\Notifications\LeaveRejectedNotification;
use App\Notifications\LeaveForwardedNotification;

class LeaveController extends Controller
{
    // public function index()
    // {
    //     $leaves = Leave::where('student_id', Auth::id())->get();
    //     return view('leaves.index', compact('leaves'));
    // }

    public function index()
    {
        $leaves = Leave::where('student_id', Auth::id())->get();
        return view('leaves.index', compact('leaves')); // Ensure this file exists
    }
    



    public function create()
    { 
        return view('leaves.create');
    }
    public function store(Request $request)
    { 
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to request leave.');
        }
    
        $user = Students::where('id', Auth::id())->first();
        
         if (!$user) {
            return back()->with('error', 'User not found in the students table.');
}

    
        $sirMajor = Staff::where('designation', 'sir major')
            ->where('company_id', $user->company_id)
            ->first();
    
        if (!$sirMajor) {
            return back()->with('error', 'Sir Major not found for your company.');
        }
    
        $leave = Leave::create([
            'student_id' => $user->id,
            'sir_major_id' => $sirMajor->id,
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);
    
        Notification::send($sirMajor, new LeaveForwardedNotification($leave));
    
        return redirect()->route('leave_requests.index')->with('success', 'Leave request submitted successfully.');
    }
    
    public function forwardToInspector($id)
    {
        $leave = Leave::findOrFail($id);
        $leave->update(['status' => 'forwarded_to_inspector']);

        $inspector = Staff::where('designation', 'inspector')->first();
        Notification::send($inspector, new LeaveNotification("New leave request from Sir Major for review."));

        return back()->with('success', 'Leave request forwarded to Inspector on Duty.');
    }

    public function forwardToChief($id)
    {
        $leave = Leave::findOrFail($id);
        $leave->update(['status' => 'forwarded_to_chief']);

        $chiefInstructor = Staff::where('designation', 'chief_instructor')->first();
        Notification::send($chiefInstructor, new LeaveNotification("New leave request from Inspector for approval."));

        return back()->with('success', 'Leave request forwarded to Chief Instructor.');
    }

    public function approveLeave($id)
    {
        $leave = Leave::findOrFail($id);
        $leave->update(['status' => 'approved']);

        Notification::send([$leave->student, $leave->sirMajor, $leave->inspector], new LeaveNotification("Your leave request has been approved."));

        return back()->with('success', 'Leave request approved successfully.');
    }

    public function rejectLeave(Request $request, $id)
    {
        $leave = Leave::findOrFail($id);
        $leave->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        Notification::send([$leave->student, $leave->sirMajor, $leave->inspector], new LeaveNotification("Your leave request has been rejected. Reason: " . $request->rejection_reason));

        return back()->with('success', 'Leave request rejected successfully.');
    }

    public function show($id)
{
    $leave = Leave::findOrFail($id);
    return view('leaves.show', compact('leave'));
}

}
