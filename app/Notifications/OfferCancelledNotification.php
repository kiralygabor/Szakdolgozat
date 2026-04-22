<?php

namespace App\Notifications;

use App\Models\Advertisement;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OfferCancelledNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Advertisement $task,
        protected User $offerUser,
        protected int $amount
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => __('notifications.offer_cancelled.database_title'),
            'message' => __('notifications.offer_cancelled.database_message', [
                'user' => $this->offerUser->first_name,
                'task' => $this->task->title,
                'price' => $this->amount,
            ]),
            'link' => route('my-tasks', ['task_id' => $this->task->id]),
            'task_id' => $this->task->id,
            'type' => 'warning',
        ];
    }
}
