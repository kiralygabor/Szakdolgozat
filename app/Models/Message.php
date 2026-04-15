<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = [
        'conversation_id',
        'sender_id',
        'body',
        'is_read',
        'attachment',
        'attachment_type',
        'is_deleted',
    ];

    protected $casts = [
        'body' => 'encrypted',
        'attachment_type' => \App\Enums\AttachmentType::class,
        'is_read' => 'boolean',
        'is_deleted' => 'boolean',
    ];

    protected $appends = ['attachment_url'];

    public function getAttachmentUrlAttribute(): ?string
    {
        if (!$this->attachment) return null;
        return asset('storage/' . $this->attachment);
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function hasAttachment(): bool
    {
        return $this->attachment !== null;
    }

    public function isOwnedBy(int $userId): bool
    {
        return $this->sender_id === $userId;
    }
}
