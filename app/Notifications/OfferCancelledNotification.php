<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OfferCancelledNotification extends Notification
{
    use Queueable;

    public $task;
    public $user;
    public $amount;

    /**
     * Create a new notification instance.
     */
    public function __construct($task, $user, $amount)
    {
        $this->task = $task;
        $this->user = $user;
        $this->amount = $amount;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->user->first_name . ' has cancelled their offer',
            'message' => $this->user->first_name . ' cancelled their £' . $this->amount . ' offer for "' . $this->task->title . '"',
            'link' => route('my-tasks', ['task_id' => $this->task->id]),
            'task_id' => $this->task->id,
            'type' => 'warning'
        ];
    }
}
