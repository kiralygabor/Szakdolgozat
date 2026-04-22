<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    protected $fillable = ['user_one_id', 'user_two_id'];

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function userOne(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }

    public function userTwo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }

    public function getOtherUser(int $authUserId): User
    {
        if ($this->user_one_id === $authUserId) {
            return $this->userTwo;
        }

        return $this->userOne;
    }

    public static function findOrCreateBetween(int $userOneId, int $userTwoId): self
    {
        return self::firstOrCreate([
            'user_one_id' => min($userOneId, $userTwoId),
            'user_two_id' => max($userOneId, $userTwoId),
        ]);
    }

    public function includesUser(int $userId): bool
    {
        return $this->user_one_id === $userId || $this->user_two_id === $userId;
    }
}
