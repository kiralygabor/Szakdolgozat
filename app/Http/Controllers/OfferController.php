<?php

namespace App\Http\Controllers;

use App\Models\Advertisment;
use App\Models\Offer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfferController extends Controller
{
    public function store(Request $request, Advertisment $task): RedirectResponse
    {
        $user = Auth::user();
        if (!$user) {
            abort(403);
        }

        // Prevent employer from making an offer on own task
        if ((int) $task->employer_id === (int) $user->id) {
            return redirect()->route('tasks.show', $task->id)
                ->with('error', 'You cannot make an offer on your own task.');
        }

        $data = $request->validate([
            'offer_price' => ['required', 'integer', 'min:1'],
            'message'     => ['required', 'string', 'max:5000'],
        ]);

        $offer = Offer::create([
            'advertisment_id' => $task->id,
            'user_id'         => $user->id,
            'price'           => $data['offer_price'],
            'message'         => $data['message'],
            'status'          => 'pending',
        ]);

        // Notify the employer
        $task->employer->notify(new \App\Notifications\NewOfferNotification($offer, $task));

        return redirect()->route('tasks.show', $task->id)
            ->with('success', 'Your offer has been sent to the task owner.');
    }

    public function accept(Offer $offer): RedirectResponse
    {
        $user = Auth::user();
        $task = $offer->task; // Assumes 'task' relationship exists on Offer model

        // Verify that the current user is the employer of the task
        if ((int) $task->employer_id !== (int) $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // Update offer status
        $offer->update(['status' => 'accepted']);

        // Ideally, we should also update the task status to 'assigned' or similar
        // $task->update(['status' => 'assigned']);

        // Notify the Tasker
        $offer->user->notify(new \App\Notifications\OfferAcceptedNotification($task));

        return redirect()->back()->with('success', 'Offer accepted! You can now message the Tasker.');
    }
}
