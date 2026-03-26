<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OfferAcceptedNotification extends Notification
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
        if ($notifiable->email_notifications) {
            $channels[] = 'mail';
        }
        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = route('tasks.show', ['task' => $this->task->id]);

        return (new MailMessage)
            ->subject(__('notifications.offer_accepted.subject'))
            ->greeting(__('notifications.offer_accepted.greeting', ['name' => $notifiable->first_name]))
            ->line(__('notifications.offer_accepted.line1', ['user' => $this->employer->first_name, 'task' => $this->task->title]))
            ->line(__('notifications.offer_accepted.line2'))
            ->action(__('notifications.offer_accepted.action'), $url)
            ->line(__('notifications.offer_accepted.line3'));
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => __('notifications.offer_accepted.database_title', ['user' => $this->employer->first_name]),
            'message' => __('notifications.offer_accepted.database_message', ['user' => $this->employer->first_name, 'task' => $this->task->title]),
            'link' => route('tasks.show', ['task' => $this->task->id]),
            'task_id' => $this->task->id,
            'type' => 'success'
        ];
    }
}
