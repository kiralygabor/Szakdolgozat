<?php

namespace App\Notifications;

use App\Models\Advertisement;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DirectQuoteReceivedNotification extends Notification
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
        $url = route('my-tasks', ['view' => 'applied', 'task_id' => $this->task->id]);
        $locale = $notifiable->locale ?? app()->getLocale();

        return (new MailMessage)
            ->subject(__('notifications.direct_quote.subject', ['user' => $this->employer->first_name], $locale))
            ->view('emails.direct-quote', [
                'notifiable' => $notifiable,
                'task' => $this->task,
                'employer' => $this->employer,
                'url' => $url,
                'locale' => $locale,
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_quote_request',
            'title' => __('notifications.direct_quote.database_title'),
            'message' => __('notifications.direct_quote.database_message', [
                'user' => $this->employer->first_name,
                'task' => $this->task->title,
            ]),
            'link' => route('my-tasks', ['view' => 'applied', 'task_id' => $this->task->id]),
            'task_id' => $this->task->id,
            'requester_id' => $this->employer->id,
        ];
    }
}
