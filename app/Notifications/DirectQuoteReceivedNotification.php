<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DirectQuoteReceivedNotification extends Notification
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
     *
     * @return array<int, string>
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
        $url = route('my-tasks', ['view' => 'applied', 'task_id' => $this->task->id]);
        $locale = $notifiable->locale ?? app()->getLocale();

        return (new MailMessage)
            ->subject(__('notifications.direct_quote.subject', ['user' => $this->employer->first_name], $locale))
            ->view('emails.direct-quote', [
                'notifiable' => $notifiable,
                'task' => $this->task,
                'employer' => $this->employer,
                'url' => $url,
                'locale' => $locale
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_quote_request',
            'title' => __('notifications.direct_quote.database_title'),
            'message' => __('notifications.direct_quote.database_message', ['user' => $this->employer->first_name, 'task' => $this->task->title]),
            'link' => route('my-tasks', ['view' => 'applied', 'task_id' => $this->task->id]),
            'task_id' => $this->task->id,
            'requester_id' => $this->employer->id,
        ];
    }
}
