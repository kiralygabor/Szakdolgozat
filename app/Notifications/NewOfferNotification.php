<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOfferNotification extends Notification
{
    use Queueable;

    public $offer;
    public $task;
    public $user;

    /**
     * Create a new notification instance.
     */
    public function __construct($offer, $task, $user)
    {
        $this->offer = $offer;
        $this->task = $task;
        $this->user = $user;
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
        $url = route('my-tasks', ['task_id' => $this->task->id]);

        return (new MailMessage)
            ->subject('New Offer on "' . $this->task->title . '"')
            ->greeting('Hello ' . $notifiable->first_name . '!')
            ->line($this->user->first_name . ' has made a **£' . $this->offer->price . '** offer on your task **"' . $this->task->title . '"**.')
            ->line('**Message from ' . $this->user->first_name . ':**')
            ->line('"' . $this->offer->message . '"')
            ->action('View Offers', $url)
            ->line('Log in to review and accept or decline this offer.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->user->first_name . ' has sent an offer',
            'message' => $this->user->first_name . ' made a £' . $this->offer->price . ' offer for "' . $this->task->title . '"',
            'link' => route('my-tasks', ['task_id' => $this->task->id]),
            'offer_id' => $this->offer->id,
            'task_id' => $this->task->id,
        ];
    }
}
