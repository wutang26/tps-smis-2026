<?php

namespace App\Jobs;

use App\Models\Staff;
use App\Models\SharedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AttachUsersToNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $notificationId;
    public function __construct($notificationId)
    {
        $this->notificationId = $notificationId;
    }
public function handle()
{
    $notification = SharedNotification::find($this->notificationId);
    if (!$notification) {
        //Log::error("Notification with ID {$notification->id} not found.");
        return;
    }

if ($notification->notification_category_id == 3) {
    $notificatified_user = $notification->data['requested_by'];

    Staff::whereNotNull('user_id')
        ->where('user_id', '!=', auth()->id()) // Exclude current user
        ->where(function ($query) use ($notificatified_user) {
            $query->whereHas('user.roles', function ($q) {
                $q->whereIn('name', ['Admin', 'CRO', 'Super Administrator']);
            })
            ->orWhere('user_id', $notificatified_user);
        })
        ->select('user_id')
        ->chunk(100, function ($users) use ($notification) {
            $ids = $users->pluck('user_id')->toArray();
            if (!empty($ids)) {
                $notification->users()->syncWithoutDetaching($ids);
            }
        });
    }else{
        Staff::whereNotNull('user_id')
            ->where('user_id', '!=', auth()->id()) // Exclude current user
            ->select('user_id')
            ->chunk(500, function ($users) use ($notification) {
                $ids = $users->pluck('user_id')->toArray();
                if (!empty($ids)) {
                    $notification->users()->syncWithoutDetaching($ids);
                    //Log::info('Attached ' . count($ids) . ' users (excluding current) to notification ID ' . $notification->id);
                }
            });        
    }
}

}
