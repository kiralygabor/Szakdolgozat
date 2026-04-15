<?php

namespace App\Console\Commands;

use App\Enums\TaskStatus;
use App\Models\Advertisement;
use App\Notifications\TaskExpiringSoonNotification;
use Illuminate\Console\Command;

class NotifyExpiringTasks extends Command
{
    private const EXPIRY_WINDOW_START_HOURS = 24;
    private const EXPIRY_WINDOW_END_HOURS = 48;

    protected $signature = 'app:notify-expiring-tasks';

    protected $description = 'Notify employers about tasks expiring in the next 24 hours';

    public function handle(): int
    {
        $windowStart = now()->addHours(self::EXPIRY_WINDOW_START_HOURS);
        $windowEnd = now()->addHours(self::EXPIRY_WINDOW_END_HOURS);

        $tasks = Advertisement::where('status', TaskStatus::Open)
            ->whereBetween('expiration_date', [$windowStart, $windowEnd])
            ->with('employer')
            ->get();

        $notifiedCount = 0;
        foreach ($tasks as $task) {
            if ($task->employer) {
                $task->employer->notify(new TaskExpiringSoonNotification($task));
                $notifiedCount++;
            }
        }

        $this->info("Sent {$notifiedCount} expiry notifications.");

        return self::SUCCESS;
    }
}
