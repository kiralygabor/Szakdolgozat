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
            ->subject('Quote Request Cancelled by ' . $this->employer->first_name)
            ->greeting('Hello ' . $notifiable->first_name . ',')
            ->line($this->employer->first_name . ' has cancelled their quote request for the task: "' . $this->task->title . '".')
            ->line('No further action is required for this request.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'quote_request_cancelled',
            'title' => 'Quote Request Cancelled',
            'message' => $this->employer->first_name . ' has cancelled their quote request for: "' . $this->task->title . '".',
            'link' => route('index'),
            'task_id' => $this->task->id,
            'requester_id' => $this->employer->id,
        ];
    }
}
