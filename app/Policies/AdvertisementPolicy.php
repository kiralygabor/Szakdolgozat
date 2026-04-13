<?php

namespace App\Policies;

use App\Models\Advertisement;
use App\Models\User;

class AdvertisementPolicy
{
    public function update(User $user, Advertisement $task): bool
    {
        return $task->isOwner($user->id);
    }

    public function delete(User $user, Advertisement $task): bool
    {
        return $task->isOwner($user->id);
    }

    public function complete(User $user, Advertisement $task): bool
    {
        return $task->isOwner($user->id);
    }
}
