<?php

namespace App\Http\Controllers;
use App\Events\NotificationEvent;
use App\Models\NotificationAudience;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Services\AuditLoggerService;

class AnnouncementController extends Controller
{
    private $selectedSessionId;
    public function __construct()
    {
        $this->selectedSessionId = session('selected_session');
        if (!$this->selectedSessionId)
            $this->selectedSessionId = 1;

        $this->middleware('permission:announcement-list|announcement-create|announcement-edit', ['only' => ['index', 'show', 'edit']]);
        $this->middleware('permission:announcement-create', ['only' => ['create', 'store', 'update']]);
        $this->middleware('permission:announcement-delete', ['only' => ['destroy']]);


    }
    public function index()
    {
        //$announcements = Announcement::where('expires_at', '>', Carbon::now())->orderBy('created_at', 'desc')->get();
        $announcements = Announcement::orderBy('created_at', 'desc')->get();
        // $audience = NotificationAudience::find(1);
        // broadcast(new NotificationEvent(
        //     $announcements[1]->id,   // ID from announcement
        //     $audience,                // Audience object or instance
        //     1,  // Notification type
        //     1,                        // Category (ensure 1 is a valid category ID)
        //     $announcements[1]->title, // Title of the notification
        //     $announcements[1],           // Full announcements object
        //     "body"  // Body of the notification
        // ));
        return view('announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('announcements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'message' => 'required',
            'document' => 'nullable|mimes:pdf|max:5120',//5MB
            'type' => 'required',
            'audience' => 'required',
            'expires_at' => 'nullable|date',
        ]);

        $expiresAt = $request->input('expires_at');
        if ($expiresAt) {
            $expiresAt = Carbon::createFromFormat('Y-m-d\TH:i', $expiresAt);
        }
        foreach (Auth::user()->roles as $role) { {
                $request->audience = Auth::user()->staff->company_id ?? $request->audience;
            }
        }
        $announcement = new Announcement();
        $announcement->title = $request->title;
        $announcement->message = $request->message;
        $announcement->type = $request->type;
        $announcement->posted_by = $request->user()->id;
        $announcement->expires_at = $expiresAt;

        if ($file = $request->file('document')) {
            $filePath = $file->store('uploads', 'public');
            $announcement->document_path = $filePath;
        }
        //return $request->audience;
        if ($request->audience == "all") {
            $announcement->audience = $request->audience;
        } else if ($request->audience == "staff") {
            $announcement->company_id = $request->audience;

        } else {
            //$announcement->company_id = Auth::user()->staff->company_id?? $request->audience;
        }
        $announcement->save();
        $audience = NotificationAudience::find(1);
            broadcast(new NotificationEvent(
            $announcement->id,   // ID from announcement
            $audience,                // Audience object or instance
            1,  // Notification type
            1,                        // Category (ensure 1 is a valid category ID)
            $announcement->title, // Title of the notification
            $announcement,           // Full announcements object
            "body"  // Body of the notification
        ));
        return redirect()->route('announcements.index')->with('success', 'Announcement created successfully.');
    }

    public function show(Announcement $announcement)
    {
        return view('announcements.show', compact('announcement'));
    }



    public function edit(Announcement $announcement)
    {
        return view('announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement, AuditLoggerService $auditLogger)
    {
        $request->validate([
            'title' => 'required',
            'message' => 'required',
            'type' => 'required',
            'expires_at' => 'nullable|date',
        ]);
        $announcementSnapshot = clone  $announcement;

        $announcement->update($request->all());

        $auditLogger->logAction([
            'action' => 'update_announcement',
            'target_type' => 'Announcement',
            'target_id' => $announcementSnapshot->id,
            'metadata' => [
                'title' => $announcementSnapshot->title,
            ],
            'old_values' => [
                'announcement' => $announcementSnapshot,
            ],
            'new_values' => [
                'announcement' => $announcement,
            ],
            'request' => $request,
        ]);
        return redirect()->route('announcements.index')->with('success', 'Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement, Request $request, AuditLoggerService $auditLogger)
    {
        $announcementSnapshot = clone  $announcement;
        $announcement->delete();
                $auditLogger->logAction([
            'action' => 'delete_announcement',
            'target_type' => 'Announcement',
            'target_id' => $announcementSnapshot->id,
            'metadata' => [
                'title' => $announcementSnapshot->title,
            ],
            'old_values' => [
                'announcement' => $announcementSnapshot,
            ],
            'new_values' => [
                'announcement' => $announcement,
            ],
            'request' => $request,
        ]);
        return redirect()->route('announcements.index')->with('success', 'Announcement deleted successfully.');
    }

    public function downloadFile($announcementId)
    {
        $announcement = Announcement::find($announcementId);
        $path = storage_path('app/public/' . $announcement->document_path);
        if (file_exists($path)) {
            return response()->download($path);
        }
        abort(404);
    }

}
