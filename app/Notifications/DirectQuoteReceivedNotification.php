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
        return (new MailMessage)
            ->subject('New Quote Request from ' . $this->employer->first_name)
            ->greeting('Hello ' . $notifiable->first_name . ',')
            ->line($this->employer->first_name . ' has requested a quote from you for their task: "' . $this->task->title . '".')
            ->line('Budget: $' . $this->task->price)
            ->action('View Task & Send Offer', route('my-tasks', ['view' => 'applied', 'task_id' => $this->task->id]))
            ->line('Send them an offer to get started!');
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
            'title' => 'New Quote Request',
            'message' => $this->employer->first_name . ' has requested a quote from you for: "' . $this->task->title . '".',
            'link' => route('my-tasks', ['view' => 'applied', 'task_id' => $this->task->id]),
            'task_id' => $this->task->id,
            'requester_id' => $this->employer->id,
        ];
    }
}
