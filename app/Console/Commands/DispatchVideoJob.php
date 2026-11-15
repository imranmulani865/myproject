<?php

namespace App\Console\Commands;

use App\Jobs\ProcessVideoJob;
use App\Models\VideoProject;
use Illuminate\Console\Command;

class DispatchVideoJob extends Command
{
    protected $signature = 'video:process {id : The ID of the video project}';
    protected $description = 'Dispatches the ProcessVideoJob for a given VideoProject ID';

    public function handle(): int
    {
        $id = (int) $this->argument('id');
        $project = VideoProject::find($id);

        if (!$project) {
            $this->error("Project ID {$id} not found.");
            return Command::FAILURE;
        }

        $this->info("Dispatching ProcessVideoJob for Project ID {$id} ({$project->title})...");
        ProcessVideoJob::dispatch($id);
        $this->info("Dispatched to queue successfully!");

        return Command::SUCCESS;
    }
}
