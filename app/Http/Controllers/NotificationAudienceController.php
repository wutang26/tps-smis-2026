<?php

namespace App\Http\Controllers;

use App\Models\NotificationAudience;
use Illuminate\Http\Request;

class NotificationAudienceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notification_audiences = NotificationAudience::get();
        return view('settings.notifications.audiences.index', compact('notification_audiences'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('settings.notifications.audiences.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:notification_audiences,name',
            'description' => 'required',
        ]);

        // If validation passes, you can proceed with storing the data
        NotificationAudience::create($request->all());

        return redirect()->route('notifications.audiences.index')
            ->with('success', 'Notification audience added successful');
    }

    /**
     * Display the specified resource.
     */
    public function show(NotificationAudience $notificationAudience)
    {
        return view('settings.notifications.audiences.show', compact('notificationAudience'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NotificationAudience $notificationAudience)
    {
        return view('settings.notifications.audiences.edit', compact('notificationAudience'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NotificationAudience $notificationAudience)
    {
        request()->validate([
            'name' => 'required|unique:notification_audiences,name,' . $notificationAudience->id,
            'description' => 'required',
        ]);

        $notificationAudience->update($request->all());

        return redirect()->route('notifications.audiences.index')
            ->with('success', 'Notification audience updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NotificationAudience $notificationAudience)
    {
        $notificationAudience->delete();
        return redirect()->route('notifications.audiences.index')
            ->with('success', 'Notification audience deleted successfully');
    }
}
