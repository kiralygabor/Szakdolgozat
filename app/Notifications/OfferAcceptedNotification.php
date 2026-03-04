<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
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
            ->subject('Your Offer Was Accepted!')
            ->greeting('Great news, ' . $notifiable->first_name . '!')
            ->line($this->employer->first_name . ' has **accepted** your offer for the task **"' . $this->task->title . '"**.')
            ->line('You can now start working on this task and communicate with the employer through the messaging system.')
            ->action('View Task', $url)
            ->line('Good luck with the task!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->employer->first_name . ' has accepted your offer',
            'message' => $this->employer->first_name . ' accepted your offer for "' . $this->task->title . '"',
            'link' => route('tasks.show', ['task' => $this->task->id]),
            'task_id' => $this->task->id,
            'type' => 'success'
        ];
    }
}
