<?php

namespace App\Jobs;

use App\Models\VideoProject;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class ProcessVideoJob implements ShouldQueue
{
    use Queueable;

    public int $timeout = 1800; // 30 minutes max
    public int $tries = 1;

    public function __construct(public int $projectId)
    {
    }

    public function handle(): void
    {
        $project = VideoProject::find($this->projectId);
        if (!$project) {
            Log::error("ProcessVideoJob: Project ID {$this->projectId} not found.");
            return;
        }

        $project->update([
            'status' => 'processing',
            'progress_step' => 'Starting background video processor...',
            'progress_percent' => 5,
        ]);

        $pythonEnv = env('PYTHON_PATH', '.venv' . DIRECTORY_SEPARATOR . 'Scripts' . DIRECTORY_SEPARATOR . 'python.exe');
        if ($pythonEnv === 'python' || $pythonEnv === 'python3') {
            $pythonBin = realpath(base_path('.venv' . DIRECTORY_SEPARATOR . 'Scripts' . DIRECTORY_SEPARATOR . 'python.exe')) ?: $pythonEnv;
        } else {
            $pythonBin = realpath($pythonEnv) ?: realpath(base_path($pythonEnv)) ?: str_replace('/', DIRECTORY_SEPARATOR, base_path($pythonEnv));
        }
        $scriptPath = realpath(base_path('python/process_video.py')) ?: str_replace('/', DIRECTORY_SEPARATOR, base_path('python/process_video.py'));
        $dbPath = realpath(database_path('database.sqlite')) ?: str_replace('/', DIRECTORY_SEPARATOR, database_path('database.sqlite'));
        $ollamaHost = env('OLLAMA_HOST', 'http://127.0.0.1:11434');
        $ollamaModel = env('OLLAMA_MODEL', 'gemma:2b');

        $command = [
            $pythonBin,
            $scriptPath,
            '--project-id', (string) $this->projectId,
            '--db-path', $dbPath,
            '--ollama-host', $ollamaHost,
            '--ollama-model', $ollamaModel,
        ];

        Log::info("ProcessVideoJob: Running command " . implode(' ', $command));

        $process = new Process($command);
        $process->setTimeout($this->timeout);
        $process->setWorkingDirectory(base_path());

        try {
            $process->run(function ($type, $buffer) {
                Log::info("Python Worker: " . trim($buffer));
            });

            if (!$process->isSuccessful()) {
                $err = $process->getErrorOutput() ?: $process->getOutput();
                Log::error("Python Worker failed: " . $err);
                
                $project->refresh();
                if ($project->status !== 'failed') {
                    $project->update([
                        'status' => 'failed',
                        'progress_step' => 'Video processing encountered an error.',
                        'error_message' => substr($err, 0, 1000),
                    ]);
                }
            } else {
                Log::info("ProcessVideoJob: Finished successfully for project {$this->projectId}");
            }
        } catch (\Throwable $e) {
            Log::error("ProcessVideoJob Exception: " . $e->getMessage());
            $project->refresh();
            $project->update([
                'status' => 'failed',
                'progress_step' => 'Error: ' . $e->getMessage(),
                'error_message' => $e->getMessage(),
            ]);
        }
    }
}
