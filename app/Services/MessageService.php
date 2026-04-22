<?php

namespace App\Services;

use App\Enums\AttachmentType;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Notifications\NewMessageNotification;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MessageService
{
    /**
     * Send a message within a conversation.
     */
    public function sendMessage(Conversation $conversation, User $sender, string $body, ?UploadedFile $attachment = null): Message
    {
        $attachmentPath = null;
        $attachmentType = null;

        if ($attachment) {
            try {
                $attachmentPath = \Illuminate\Support\Facades\Storage::disk('public')->putFile('message-attachments', $attachment);
                $attachmentType = $this->determineAttachmentType($attachment);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Storage failed: ' . $e->getMessage());
                $attachmentPath = null;
            }
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $sender->id,
            'body' => $body,
            'attachment' => $attachmentPath,
            'attachment_type' => $attachmentType,
        ]);

        // Notify the other user
        $recipient = $conversation->getOtherUser($sender->id);
        $recipient->notify(new NewMessageNotification($message, $conversation, $sender));

        return $message;
    }

    /**
     * Determine the type of the attachment based on extension/mime.
     */
    private function determineAttachmentType(UploadedFile $file): AttachmentType
    {
        $mime = $file->getMimeType();

        if (str_starts_with($mime, 'image/')) {
            return AttachmentType::Image;
        }

        if (str_starts_with($mime, 'video/')) {
            return AttachmentType::Video;
        }

        if (str_starts_with($mime, 'audio/')) {
            return AttachmentType::Audio;
        }

        return AttachmentType::File;
    }
}
