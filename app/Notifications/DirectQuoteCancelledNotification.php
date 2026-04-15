<?php

namespace App\Notifications;

use App\Models\Advertisement;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DirectQuoteCancelledNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Advertisement $task,
        protected User $employer
    ) {}

    public function via(object $notifiable): array
    {
        $channels = ['database'];

        if ($notifiable->email_direct_quotes) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('notifications.direct_quote_cancelled.subject', ['user' => $this->employer->first_name]))
            ->greeting(__('notifications.direct_quote_cancelled.greeting', ['name' => $notifiable->first_name]))
            ->line(__('notifications.direct_quote_cancelled.line1', [
                'user' => $this->employer->first_name,
                'task' => $this->task->title,
            ]))
            ->line(__('notifications.direct_quote_cancelled.line2'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'quote_request_cancelled',
            'title' => __('notifications.direct_quote_cancelled.database_title'),
            'message' => __('notifications.direct_quote_cancelled.database_message', [
                'user' => $this->employer->first_name,
                'task' => $this->task->title,
            ]),
            'link' => route('index'),
            'task_id' => $this->task->id,
            'requester_id' => $this->employer->id,
        ];
    }
}
