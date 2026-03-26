<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class DirectQuoteCancelledNotification extends Notification
{
    use Queueable;

    public $task;
    public $employer;

    /**
     * Create a new notification instance.
     */
    public function __construct($task, $employer)
    {
        $this->task = $task;
        $this->employer = $employer;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        $channels = ['database'];
        if ($notifiable->email_direct_quotes) {
            $channels[] = 'mail';
        }
        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('notifications.direct_quote_cancelled.subject', ['user' => $this->employer->first_name]))
            ->greeting(__('notifications.direct_quote_cancelled.greeting', ['name' => $notifiable->first_name]))
            ->line(__('notifications.direct_quote_cancelled.line1', ['user' => $this->employer->first_name, 'task' => $this->task->title]))
            ->line(__('notifications.direct_quote_cancelled.line2'));
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'quote_request_cancelled',
            'title' => __('notifications.direct_quote_cancelled.database_title'),
            'message' => __('notifications.direct_quote_cancelled.database_message', ['user' => $this->employer->first_name, 'task' => $this->task->title]),
            'link' => route('index'),
            'task_id' => $this->task->id,
            'requester_id' => $this->employer->id,
        ];
    }
}
