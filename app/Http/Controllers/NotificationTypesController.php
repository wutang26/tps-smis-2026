<?php

namespace App\Http\Controllers;

use App\Models\NotificationType;
use Illuminate\Http\Request;
use App\Services\AuditLoggerService;

class NotificationTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notification_types = NotificationType::get();
        return view('settings.notifications.types.index', compact('notification_types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('settings.notifications.types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:notification_types,name',
            'description' => 'required',
        ]);
        // If validation passes, you can proceed with storing the data
        NotificationType::create($request->all());

        return redirect()->route('notifications.types.index')
            ->with('success', 'Notification type added successful');
    }

    /**
     * Display the specified resource.
     */
    public function show(NotificationType $notificationType)
    {
        return view('settings.notifications.types.show', compact('notificationType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NotificationType $notificationType)
    {
        return view('settings.notifications.types.edit', compact('notificationType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NotificationType $notificationType,AuditLoggerService $auditLogger)
    {
        $notificationTypeSnapshot = clone $notificationType;

        request()->validate([
            'name' => 'required|unique:notification_types,name,' . $notificationType->id,
            'description' => 'required',
        ]);


        $notificationType->update($request->all());
        $auditLogger->logAction([
            'action' => 'update_notification_type',
            'target_type' => 'NotificationType',
            'target_id' => $notificationType->id,
            'metadata' => [
                'title' => $notificationTypeSnapshot->name ?? null,
            ],
            'old_values' => [
                'notification_type' => $notificationTypeSnapshot,
            ],
            'new_values' => [
                'notification_type' => $notificationType,
            ],
            'request' => $request,
        ]);
        return redirect()->route('notifications.types.index')
            ->with('success', 'Notification type updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NotificationType $notificationType, Request $request, AuditLoggerService $auditLogger)
    {
        $notificationTypeSnapshot = clone $notificationType;
        $notificationType->delete();
        $auditLogger->logAction([
            'action' => 'delete_notification_type',
            'target_type' => 'NotificationType',
            'target_id' => $notificationType->id,
            'metadata' => [
                'title' => $notificationTypeSnapshot->name ?? null,
            ],
            'old_values' => [
                'notification_type' => $notificationTypeSnapshot,
            ],
            'new_values' => null,
            'request' => $request,
        ]);
        return redirect()->route('notifications.types.index')
            ->with('success', 'Notification types deleted successfully');
    }
}
