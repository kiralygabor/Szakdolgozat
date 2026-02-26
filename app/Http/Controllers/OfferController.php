<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Offer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfferController extends Controller
{
    public function store(Request $request, Advertisement $task): RedirectResponse
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

        // Prevent offers if task is not open
        if ($task->status !== 'open') {
            return redirect()->route('tasks.show', $task->id)
                ->with('error', 'This task is no longer accepting offers.');
        }

        $data = $request->validate([
            'offer_price' => ['required', 'integer', 'min:1'],
            'message'     => ['required', 'string', 'max:5000'],
        ]);

        $offer = Offer::create([
            'advertisement_id' => $task->id,
            'user_id'         => $user->id,
            'price'           => $data['offer_price'],
            'message'         => $data['message'],
            'status'          => 'pending',
        ]);

        // Notify the employer
        $task->employer->notify(new \App\Notifications\NewOfferNotification($offer, $task));

        return redirect()->route('tasks')
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

        // Update the task status to 'pending' and assign employee
        $task->update([
            'status' => 'pending',
            'employee_id' => $offer->user_id,
        ]);

        // Notify the Tasker
        $offer->user->notify(new \App\Notifications\OfferAcceptedNotification($task));

        return redirect()->back()->with('success', 'Offer accepted! You can now message the Tasker.');
    }

    public function destroy(Advertisement $task): RedirectResponse
    {
        $user = Auth::user();
        if (!$user) {
            abort(403);
        }

        $offer = Offer::where('advertisement_id', $task->id)
            ->where('user_id', $user->id)
            ->first();

        if ($offer) {
            $offer->delete();
            return redirect()->back()->with('success', 'Your offer has been cancelled.');
        }

        return redirect()->back()->with('error', 'Offer not found.');
    }
}
