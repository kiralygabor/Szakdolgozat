<?php

namespace App\Notifications;

use App\Models\Advertisement;
use App\Models\Offer;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOfferNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Offer $offer,
        protected Advertisement $task,
        protected User $offerUser
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
        $url = route('my-tasks', ['task_id' => $this->task->id]);
        $locale = $notifiable->locale ?? app()->getLocale();

        return (new MailMessage)
            ->subject(__('notifications.new_offer.subject', ['task' => $this->task->title], $locale))
            ->view('emails.new-offer', [
                'notifiable' => $notifiable,
                'offer' => $this->offer,
                'task' => $this->task,
                'sender' => $this->offerUser,
                'url' => $url,
                'locale' => $locale,
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => __('notifications.new_offer.database_title', ['user' => $this->offerUser->first_name]),
            'message' => __('notifications.new_offer.database_message', [
                'user' => $this->offerUser->first_name,
                'price' => $this->offer->price,
                'task' => $this->task->title,
            ]),
            'link' => route('my-tasks', ['task_id' => $this->task->id]),
            'offer_id' => $this->offer->id,
            'task_id' => $this->task->id,
        ];
    }
}
