<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification
{
    use Queueable;

    public $message;
    public $sender;

    /**
     * Create a new notification instance.
     */
    public function __construct($message, $sender)
    {
        $this->message = $message;
        $this->sender = $sender;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        $displayMessage = $this->message->body;
        
        if ($this->message->attachment) {
            if ($this->message->attachment_type === 'image') {
                $displayMessage = __('notifications.new_message.sent_image', ['body' => $this->message->body]);
            } else {
                $displayMessage = __('notifications.new_message.sent_attachment', ['body' => $this->message->body]);
            }
        }

        return [
            'title' => __('notifications.new_message.database_title', ['user' => $this->sender->first_name]),
            'message' => $displayMessage,
            'link' => route('messages', ['user_id' => $this->sender->id]),
            'sender_id' => $this->sender->id,
            'conversation_id' => $this->message->conversation_id,
        ];
    }
}
