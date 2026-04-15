<?php

namespace App\Services;

use App\Enums\TaskStatus;
use App\Models\Advertisement;
use App\Models\Review;
use App\Models\User;
use App\Notifications\DirectQuoteReceivedNotification;
use Illuminate\Support\Facades\Auth;

class TaskService
{
    use \App\Traits\InteractsWithStorage;

    private const DEFAULT_EXPIRATION_DAYS = 30;

    public function createTask(array $data, int $userId): Advertisement
    {
        $data['photos'] = $this->storeFiles($data['photos'] ?? [], 'task-photos');

        $task = Advertisement::create(array_merge($data, [
            'employer_id' => $userId,
            'status' => TaskStatus::Open,
            'is_direct' => !empty($data['employee_id']),
            'expiration_date' => now()->addDays(self::DEFAULT_EXPIRATION_DAYS),
        ]));

        $this->notifyDirectQuoteRecipient($task);

        return $task;
    }

    public function completeTask(Advertisement $task, array $reviewData): void
    {
        $task->update(['status' => TaskStatus::Completed]);

        $hasReviewData = !empty($reviewData['stars']) && $task->hasEmployee();
        if ($hasReviewData) {
            $this->createCompletionReview($task, $reviewData);
        }
    }

    private function createCompletionReview(Advertisement $task, array $data): void
    {
        Review::create([
            'reviewer_id' => Auth::id(),
            'target_user_id' => $task->employee_id,
            'stars' => $data['stars'],
            'comment' => $data['comment'] ?? '',
        ]);
    }



    private function notifyDirectQuoteRecipient(Advertisement $task): void
    {
        $isDirectWithEmployee = $task->is_direct && $task->hasEmployee();
        if (!$isDirectWithEmployee) {
            return;
        }

        $employee = User::find($task->employee_id);
        $employee?->notify(new DirectQuoteReceivedNotification($task, $task->employer));
    }
}
