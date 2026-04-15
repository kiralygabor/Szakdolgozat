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

    public function acceptDirect(User $user, Advertisement $task): bool
    {
        return (int) $task->employee_id === (int) $user->id && $task->is_direct;
    }

    public function review(User $user, Advertisement $task): bool
    {
        return $task->isCompleted() && $user->id === $task->employee_id;
    }
}
