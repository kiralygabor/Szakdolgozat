<?php

namespace App\Policies;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ConversationPolicy
{
    public function view(User $user, Conversation $conversation): bool
    {
        return $conversation->includesUser($user->id);
    }

    public function message(User $user, Conversation $conversation): bool
    {
        return $conversation->includesUser($user->id);
    }
}
