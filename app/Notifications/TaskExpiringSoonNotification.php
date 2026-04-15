<?php

namespace App\Notifications;

use App\Models\Advertisement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskExpiringSoonNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Advertisement $task
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Your task is expiring soon!'))
            ->line(__('Your task ":title" is set to expire in less than 24 hours.', ['title' => $this->task->title]))
            ->line(__('If you still need help, you can extend the expiration date or re-post it to keep it visible.'))
            ->action(__('View Task'), route('tasks.show', $this->task->id))
            ->line(__('Thank you for using MiniJobz!'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'title' => $this->task->title,
            'message' => __('Your task is expiring soon!'),
            'type' => 'expiry_warning',
        ];
    }
}
