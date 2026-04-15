<?php

namespace App\Notifications;

use App\Models\Advertisement;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OfferAcceptedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Advertisement $task,
        protected User $employer
    ) {}

    public function via(object $notifiable): array
    {
        $channels = ['database'];

        if ($notifiable->email_notifications) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('tasks.show', ['task' => $this->task->id]);
        $locale = $notifiable->locale ?? app()->getLocale();

        return (new MailMessage)
            ->subject(__('notifications.offer_accepted.subject', [], $locale))
            ->view('emails.offer-accepted', [
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
            'title' => __('notifications.offer_accepted.database_title', ['user' => $this->employer->first_name]),
            'message' => __('notifications.offer_accepted.database_message', [
                'user' => $this->employer->first_name,
                'task' => $this->task->title,
            ]),
            'link' => route('tasks.show', ['task' => $this->task->id]),
            'task_id' => $this->task->id,
            'type' => 'success',
        ];
    }
}
