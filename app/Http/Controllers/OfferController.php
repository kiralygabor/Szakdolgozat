<?php

namespace App\Http\Controllers;

use App\Enums\OfferStatus;
use App\Enums\TaskStatus;
use App\Models\Advertisement;
use App\Models\Offer;
use App\Notifications\NewOfferNotification;
use App\Notifications\OfferAcceptedNotification;
use App\Notifications\OfferCancelledNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfferController extends Controller
{
    public function store(Request $request, Advertisement $task): RedirectResponse
    {
        $user = Auth::user();

        if ($task->isOwner($user->id)) {
            return redirect()->route('tasks.show', $task)
                ->with('error', __('You cannot make an offer on your own task.'));
        }

        if (!$task->isOpen()) {
            return redirect()->route('tasks.show', $task)
                ->with('error', __('This task is no longer accepting offers.'));
        }

        $data = $request->validate([
            'offer_price' => ['required', 'integer', 'min:1'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $offer = Offer::create([
            'advertisement_id' => $task->id,
            'user_id' => $user->id,
            'price' => $data['offer_price'],
            'message' => $data['message'],
            'status' => OfferStatus::Pending,
        ]);

        $task->employer->notify(new NewOfferNotification($offer, $task, $user));

        if ($task->is_direct) {
            return redirect()->route('my-tasks', ['view' => 'direct', 'task_id' => $task->id])
                ->with('success', __('Your quote has been sent successfully!'));
        }

        return redirect()->route('tasks')
            ->with('success', __('Your offer has been sent to the task owner.'));
    }

    public function accept(Offer $offer): RedirectResponse
    {
        $task = $offer->task;

        if (!$task->isOwner(Auth::id())) {
            abort(403);
        }

        $offer->update(['status' => OfferStatus::Accepted]);

        $task->update([
            'status' => TaskStatus::Assigned,
            'employee_id' => $offer->user_id,
        ]);

        $offer->user->notify(new OfferAcceptedNotification($task, Auth::user()));

        $redirectParams = ['status' => 'pending', 'task_id' => $task->id];
        if ($task->is_direct) {
            $redirectParams['view'] = 'direct';
        }

        return redirect()->route('my-tasks', $redirectParams)
            ->with('success', __('Offer accepted! You can now message the Tasker.'));
    }

    public function acceptDirect(Advertisement $task): RedirectResponse
    {
        $user = Auth::user();

        $isRequestedTasker = (int) $task->employee_id === (int) $user->id;
        if (!$isRequestedTasker) {
            abort(403, 'You are not the requested tasker for this task.');
        }

        if (!$task->isOpen()) {
            return back()->with('error', __('This task is no longer open.'));
        }

        $task->update(['status' => TaskStatus::Assigned]);

        $task->employer->notify(new OfferAcceptedNotification($task, $user));

        return redirect()->route('my-tasks', ['view' => 'direct', 'status' => 'pending', 'task_id' => $task->id])
            ->with('success', __('You accepted the task! You can now message the employer.'));
    }

    public function destroy(Advertisement $task): RedirectResponse
    {
        $offer = Offer::where('advertisement_id', $task->id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$offer) {
            return back()->with('error', __('Offer not found.'));
        }

        $offerPrice = $offer->price;
        $employer = $task->employer;

        $offer->delete();

        $employer->notify(new OfferCancelledNotification($task, Auth::user(), $offerPrice));

        return back()->with('success', __('Your offer has been cancelled.'));
    }
}
