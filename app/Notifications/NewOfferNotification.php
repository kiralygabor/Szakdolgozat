<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewOfferNotification extends Notification
{
    use Queueable;

    public $offer;
    public $task;
    public $user;

    /**
     * Create a new notification instance.
     */
    public function __construct($offer, $task, $user)
    {
        $this->offer = $offer;
        $this->task = $task;
        $this->user = $user;
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
            'title' => $this->user->first_name . ' has sent an offer',
            'message' => $this->user->first_name . ' made a £' . $this->offer->price . ' offer for "' . $this->task->title . '"',
            'link' => route('my-tasks', ['task_id' => $this->task->id]),
            'offer_id' => $this->offer->id,
            'task_id' => $this->task->id,
        ];
    }
}
