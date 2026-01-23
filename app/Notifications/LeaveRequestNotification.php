<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\LeaveRequest;

// class LeaveRequestSubmitted extends Notification
class LeaveRequestNotification extends Notification
{
    use Queueable;

    protected $leaveRequest;

    public function __construct(LeaveRequest $leaveRequest)
    {
        $this->leaveRequest = $leaveRequest;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'leave_request_id' => $this->leaveRequest->id,
            'student_name' => $this->leaveRequest->student->user->name,
            'start_date' => $this->leaveRequest->start_date,
            'end_date' => $this->leaveRequest->end_date,
            'reason' => $this->leaveRequest->reason,
        ];
    }
}
