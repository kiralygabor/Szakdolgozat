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
            ->subject(__('notifications.new_offer.subject', ['task' => $this->task->title]))
            ->greeting(__('notifications.new_offer.greeting', ['name' => $notifiable->first_name]))
            ->line(__('notifications.new_offer.line1', ['user' => $this->user->first_name, 'price' => $this->offer->price, 'task' => $this->task->title]))
            ->line(__('notifications.new_offer.line2', ['user' => $this->user->first_name]))
            ->line('"' . $this->offer->message . '"')
            ->action(__('notifications.new_offer.action'), $url)
            ->line(__('notifications.new_offer.line3'));
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => __('notifications.new_offer.database_title', ['user' => $this->user->first_name]),
            'message' => __('notifications.new_offer.database_message', ['user' => $this->user->first_name, 'price' => $this->offer->price, 'task' => $this->task->title]),
            'link' => route('my-tasks', ['task_id' => $this->task->id]),
            'offer_id' => $this->offer->id,
            'task_id' => $this->task->id,
        ];
    }
}
