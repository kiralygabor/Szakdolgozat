<?php

namespace App\Notifications;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Message $message,
        protected Conversation $conversation,
        protected User $sender
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $displayMessage = $this->buildDisplayMessage();

        return [
            'title' => __('notifications.new_message.database_title', ['user' => $this->sender->first_name]),
            'message' => $displayMessage,
            'link' => route('messages', ['user_id' => $this->sender->id]),
            'sender_id' => $this->sender->id,
            'conversation_id' => $this->message->conversation_id,
        ];
    }

    private function buildDisplayMessage(): string
    {
        if (!$this->message->hasAttachment()) {
            return $this->message->body;
        }

        $translationKey = $this->message->attachment_type === 'image'
            ? 'notifications.new_message.sent_image'
            : 'notifications.new_message.sent_attachment';

        return __($translationKey, ['body' => $this->message->body]);
    }
}
