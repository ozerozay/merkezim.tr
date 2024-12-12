<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ApproveRequested extends Notification
{
    use Queueable;

    protected $approve;

    /**
     * Create a new notification instance.
     */
    public function __construct($approve)
    {
        $this->approve = $approve;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'approve_id' => $this->approve->id,
            'message' => $this->approve->message,
            'type' => $this->approve->type->name,
        ];
    }
}
