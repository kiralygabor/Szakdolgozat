<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Offer;
use App\Notifications\OfferCancelledNotification;
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

        // Profile completion check
        $missingSteps = array_filter([
            empty($user->avatar) ? 'Upload a profile picture' : null,
            empty($user->birthdate) ? 'Add your date of birth' : null,
            empty($user->phone_number) ? 'Verify your mobile' : null,
            empty($user->city_id) ? 'Add your location' : null,
        ]);

        if (count($missingSteps) > 0) {
            return redirect()->route('profile')->with('info', 'Please complete your profile before making an offer.');
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
        $task->employer->notify(new \App\Notifications\NewOfferNotification($offer, $task, $user));

        // If it's a direct task, redirect back to "My Tasks" (Applied tab) instead of browse tasks list
        if ($task->is_direct) {
            return redirect()->route('my-tasks', ['view' => 'applied', 'task_id' => $task->id])
                ->with('success', 'Your quote has been sent successfully!');
        }

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

        // Update the task status to 'assigned' and assign employee
        $task->update([
            'status' => 'assigned',
            'employee_id' => $offer->user_id,
        ]);

        // Notify the Tasker
        $offer->user->notify(new \App\Notifications\OfferAcceptedNotification($task, $user));

        // Redirect to the correct view based on whether it's a direct task
        $redirectParams = ['status' => 'pending', 'task_id' => $task->id];
        if ($task->is_direct) {
            $redirectParams['view'] = 'direct';
        }

        return redirect()->route('my-tasks', $redirectParams)
            ->with('success', 'Offer accepted! You can now message the Tasker.');
    }

    /**
     * Accept a direct quote request at the employer's budget — skip the offer step entirely.
     */
    public function acceptDirect(Advertisement $task): RedirectResponse
    {
        $user = Auth::user();
        if (!$user) {
            abort(403);
        }

        // Only the requested employee can accept
        if ((int) $task->employee_id !== (int) $user->id) {
            abort(403, 'You are not the requested tasker for this task.');
        }

        if ($task->status !== 'open') {
            return redirect()->back()->with('error', 'This task is no longer open.');
        }

        // Directly assign the task
        $task->update([
            'status' => 'assigned',
        ]);

        // Notify the employer that their direct request was accepted
        $task->employer->notify(new \App\Notifications\OfferAcceptedNotification($task, $user));

        return redirect()->route('my-tasks', ['view' => 'applied', 'status' => 'pending', 'task_id' => $task->id])
            ->with('success', 'You accepted the task! You can now message the employer.');
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
            $amount = $offer->price;
            $employer = $task->employer;
            $offer->delete();
            
            // Notify the employer
            $employer->notify(new OfferCancelledNotification($task, $user, $amount));

            return redirect()->back()->with('success', 'Your offer has been cancelled.');
        }

        return redirect()->back()->with('error', 'Offer not found.');
    }
}
