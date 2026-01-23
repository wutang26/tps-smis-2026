<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AuditLoggerService;
use App\Models\SessionProgramme;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SessionProgrammeController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:programme-session-list|programme-session-create|programme-session-edit|programme-session-delete', ['only' => ['index','view']]);
         $this->middleware('permission:programme-session-create', ['only' => ['create','store']]);
         $this->middleware('permission:programme-session-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:programme-session-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource
     */
    // public function index(Request $request): View
    // {
    //     $session_programmes = SessionProgramme::orderBy('id','DESC')->paginate(5);
    //     return view('session_programmes.index',compact('session_programmes'))
    //         ->with('i', ($request->input('page', 1) - 1) * 5);
    // }
            public function index(Request $request): View
                {
                    

                    // Get all session programmes
                    $sessionProgrammes = SessionProgramme::orderBy('id', 'DESC')->get();

                    // Set default session to 8 if not already set
                    if (!session()->has('selected_session')) {
                        session(['selected_session' => 8]);
                    }

                    // Update session if user selects a session
                    if ($request->filled('session_id')) {
                        session(['selected_session' => $request->session_id]);
                    }

                    $selectedSessionId = session('selected_session');

                    // Your paginated programmes (you can filter by selected session if needed)
                    $session_programmes = SessionProgramme::orderBy('id', 'DESC')->paginate(5);

                    return view(
                        'session_programmes.index',
                        compact('session_programmes', 'sessionProgrammes', 'selectedSessionId')
                    )->with('i', ($request->input('page', 1) - 1) * 5);
                }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('session_programmes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'session_programme_name' => 'required|unique:session_programmes,session_programme_name',
            'year' => 'required',
        ]);
        //return $request->all();
        SessionProgramme::create($request->all());
    
        return redirect()->route('session_programmes.index')
                        ->with('success','Session programme created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(SessionProgramme $session_programme): View
    {
        
        return view('session_programmes.show',compact('session_programme'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SessionProgramme $session_programme): View
    {
        return view('session_programmes.edit',compact('session_programme'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  SessionProgramme $sessionProgramme,AuditLoggerService $auditLogger)
    {
        request()->validate([
            'session_programme_name' => 'required|unique:session_programmes,session_programme_name,'. $sessionProgramme->id,
            'year' => 'required',
       ]);
       $session_programmeSnapShot = clone $sessionProgramme;
       $sessionProgramme->update($request->all());
         $auditLogger->logAction([
            'action' => 'update_session_programme',
            'target_type' => 'Session',
            'target_id' => $session_programmeSnapShot->id,
            'metadata' => [
                'title' => $session_programmeSnapShot->session_programme_name ?? null,
            ],
            'old_values' => [
                'session_programme' => $session_programmeSnapShot,
            ],
            'new_values' => null,
            'request' => $request,
        ]);
       return redirect()->route('session_programmes.index')
                       ->with('success','Session programme updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SessionProgramme $session_programme, Request $request, AuditLoggerService $auditLogger): RedirectResponse
    {
        $session_programmeSnapShot = clone $session_programme;
        $session_programme->delete();
        $auditLogger->logAction([
            'action' => 'delete_session_programme',
            'target_type' => 'Session',
            'target_id' => $session_programmeSnapShot->id,
            'metadata' => [
                'title' => $session_programmeSnapShot->session_programme_name ?? null,
            ],
            'old_values' => [
                'session_programme' => $session_programmeSnapShot,
            ],
            'new_values' => null,
            'request' => $request,
        ]);
        return redirect()->route('session_programmes.index')
                        ->with('success','Session programme deleted successfully');
    }
}
