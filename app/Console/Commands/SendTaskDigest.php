<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Advertisement;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\TaskDigestMail;

class SendTaskDigest extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'digest:send-tasks';

    /**
     * The console command description.
     */
    protected $description = 'Send daily digest emails to users about new tasks in their tracked categories';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Get all users who opted in to task digest emails and have tracked categories
        $users = User::where('email_task_digest', true)
            ->where('verified', true)
            ->whereHas('trackedCategories')
            ->get();

        $sentCount = 0;

        foreach ($users as $user) {
            $trackedCategories = $user->trackedCategories;
            $categoryIds = $trackedCategories->pluck('id')->toArray();

            // Find new tasks posted in the last 24 hours in tracked categories
            $newTasks = Advertisement::whereHas('job', function($q) use ($categoryIds) {
                    $q->whereIn('categories_id', $categoryIds);
                })
                ->where('status', 'open')
                ->where('created_at', '>=', now()->subDay())
                ->where('employer_id', '!=', $user->id) // Don't notify about own tasks
                ->with(['category', 'employer'])
                ->orderBy('created_at', 'desc')
                ->get();

            if ($newTasks->isEmpty()) {
                continue;
            }

            // Group tasks by category for a nice digest
            $tasksByCategory = $newTasks->groupBy(function ($task) {
                return $task->category ? $task->category->name : 'Uncategorised';
            });

            try {
                Mail::to($user->email)->locale($user->preferredLocale())->send(new TaskDigestMail($user, $tasksByCategory));
                $sentCount++;

                // Update last_digest_sent_at for each tracked category
                foreach ($trackedCategories as $category) {
                    $user->trackedCategories()->updateExistingPivot($category->id, [
                        'last_digest_sent_at' => now(),
                    ]);
                }
            } catch (\Exception $e) {
                $this->error("Failed to send digest to {$user->email}: {$e->getMessage()}");
            }
        }

        $this->info("Sent task digest emails to {$sentCount} user(s).");

        return Command::SUCCESS;
    }
}
