<?php

namespace App\Http\Controllers;

use App\Enums\OfferStatus;
use App\Models\Advertisement;
use App\Models\Conversation;
use App\Models\Offer;
use App\Services\OfferService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OfferController extends Controller
{
    public function __construct(
        protected OfferService $offerService
    ) {}

    public function store(\App\Http\Requests\Offer\StoreOfferRequest $request, Advertisement $task): RedirectResponse
    {
        $user = Auth::user();

        if ($task->isOwner($user->id)) {
            return redirect()->route('tasks.show', $task)
                ->with('error', __('You cannot make an offer on your own task.'));
        }

        if ($task->offers()->where('user_id', $user->id)->exists()) {
            return back()->with('error', __('You have already made an offer on this task.'));
        }

        if (!$task->isOpen()) {
            return redirect()->route('tasks.show', $task)
                ->with('error', __('This task is no longer accepting offers.'));
        }

        $data = $request->validated();

        $this->offerService->placeOffer($task, $data, $user);

        if ($task->is_direct) {
            return redirect()->route('my-tasks', ['view' => 'direct', 'task_id' => $task->id])
                ->with('success', __('Your quote has been sent successfully!'));
        }

        return redirect()->route('tasks')
            ->with('success', __('Your offer has been sent to the task owner.'));
    }

    public function accept(Offer $offer): RedirectResponse
    {
        $this->authorize('accept', $offer);
        $task = $offer->task;

        DB::transaction(function () use ($offer, $task) {
            $this->offerService->acceptOffer($offer);
            $task->offers()->where('id', '!=', $offer->id)->update(['status' => OfferStatus::Declined]);
            Conversation::findOrCreateBetween($task->employer_id, $offer->user_id);
        });

        $redirectParams = ['status' => 'pending', 'task_id' => $task->id];
        if ($task->is_direct) {
            $redirectParams['view'] = 'direct';
        }

        return redirect()->route('my-tasks', $redirectParams)
            ->with('success', __('Offer accepted! You can now message the Tasker.'));
    }

    public function acceptDirect(Advertisement $task): RedirectResponse
    {
        $this->authorize('acceptDirect', $task);

        if (!$task->isOpen()) {
            return back()->with('error', __('This task is no longer open.'));
        }

        $user = Auth::user();

        $this->offerService->acceptDirectOffer($task, $user);
        Conversation::findOrCreateBetween($task->employer_id, $user->id);

        return redirect()->route('my-tasks', ['view' => 'direct', 'status' => 'pending', 'task_id' => $task->id])
            ->with('success', __('You accepted the task! You can now message the employer.'));
    }

    public function destroy(Advertisement $task): RedirectResponse
    {
        $this->offerService->cancelOffer($task, Auth::user());

        return back()->with('success', __('Your offer has been cancelled.'));
    }
}
