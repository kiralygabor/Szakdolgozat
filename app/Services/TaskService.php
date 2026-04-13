<?php

namespace App\Services;

use App\Enums\TaskStatus;
use App\Models\Advertisement;
use App\Models\User;
use App\Notifications\DirectQuoteReceivedNotification;

class TaskService
{
    public function createTask(array $data, int $userId): Advertisement
    {
        $photos = $this->uploadPhotos($data['photos'] ?? []);
        unset($data['photos']);

        $task = Advertisement::create(array_merge($data, [
            'employer_id' => $userId,
            'photos' => $photos,
            'status' => TaskStatus::Open,
            'is_direct' => !empty($data['employee_id']),
            'expiration_date' => now()->addDays(30),
        ]));

        $this->notifyDirectQuoteRecipient($task);

        return $task;
    }

    private function uploadPhotos(array $files): array
    {
        return array_map(
            fn($file) => $file->store('task-photos', 'public'),
            $files
        );
    }

    private function notifyDirectQuoteRecipient(Advertisement $task): void
    {
        if (!$task->is_direct || !$task->hasEmployee()) {
            return;
        }

        $employee = User::find($task->employee_id);
        $employee?->notify(new DirectQuoteReceivedNotification($task, $task->employer));
    }
}
