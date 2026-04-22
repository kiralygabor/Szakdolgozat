<?php

namespace App\Services;

use App\Enums\OfferStatus;
use App\Enums\TaskStatus;
use App\Models\Advertisement;
use App\Models\Offer;
use App\Models\User;
use App\Notifications\NewOfferNotification;
use App\Notifications\OfferAcceptedNotification;
use App\Notifications\OfferCancelledNotification;
use Illuminate\Support\Facades\Auth;

class OfferService
{
    public function placeOffer(Advertisement $task, array $data, User $user): Offer
    {
        $offer = Offer::create([
            'advertisement_id' => $task->id,
            'user_id' => $user->id,
            'price' => $data['offer_price'],
            'message' => $data['message'],
            'status' => OfferStatus::Pending,
        ]);

        $task->employer->notify(new NewOfferNotification($offer, $task, $user));

        return $offer;
    }

    public function acceptOffer(Offer $offer): void
    {
        $task = $offer->task;

        $offer->update(['status' => OfferStatus::Accepted]);

        $task->update([
            'status' => TaskStatus::Assigned,
            'employee_id' => $offer->user_id,
        ]);

        $offer->user->notify(new OfferAcceptedNotification($task, Auth::user()));
    }

    public function acceptDirectOffer(Advertisement $task, User $user): void
    {
        $task->update(['status' => TaskStatus::Assigned]);

        $task->employer->notify(new OfferAcceptedNotification($task, $user));
    }

    public function cancelOffer(Advertisement $task, User $user): void
    {
        $offer = Offer::where('advertisement_id', $task->id)
            ->where('user_id', $user->id)
            ->first();

        if ($offer) {
            $offerPrice = $offer->price;
            $employer = $task->employer;

            $offer->delete();

            $employer->notify(new OfferCancelledNotification($task, $user, $offerPrice));
        }
    }
}
