<?php

namespace App\Console\Commands;

use App\Models\Tugas;
use Illuminate\Console\Command;
use Carbon\Carbon;

class PruneTrashedTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tugas:prune-trash {days=30 : The number of days to keep trashed tasks}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permanently delete soft-deleted tasks that are older than a specific number of days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->argument('days');
        $date = Carbon::now()->subDays($days);

        $this->info("Searching for tasks trashed before {$date->toDateTimeString()} ({$days} days ago)...");

        $query = Tugas::onlyTrashed()->where('deleted_at', '<', $date);
        $count = $query->count();

        if ($count === 0) {
            $this->info('No old trashed tasks found to prune.');
            return 0;
        }

        $this->warn("Found {$count} tasks to permanently delete.");

        if ($this->confirm('Do you want to proceed and permanently delete these tasks?', true)) {
            $query->forceDelete();
            $this->success("Successfully pruned {$count} trashed tasks.");
        } else {
            $this->info('Pruning cancelled.');
        }

        return 0;
    }
}
