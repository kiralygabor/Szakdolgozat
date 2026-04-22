<?php

namespace App\Console\Commands;

use App\Enums\TaskStatus;
use App\Models\Advertisement;
use Illuminate\Console\Command;

class CleanupExpiredTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cleanup-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = Advertisement::where('status', TaskStatus::Open)
            ->where('expiration_date', '<', now())
            ->update(['status' => TaskStatus::Expired]);

        $this->info("Cleaned up {$count} expired tasks.");
    }
}
