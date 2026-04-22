<?php

namespace App\Policies;

use App\Models\Offer;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OfferPolicy
{
    public function accept(User $user, Offer $offer): bool
    {
        return $offer->task->isOwner($user->id);
    }

    public function delete(User $user, Offer $offer): bool
    {
        return $offer->user_id === $user->id;
    }
}
