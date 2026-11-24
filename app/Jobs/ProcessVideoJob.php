<?php

namespace App\Jobs;

use App\Models\GeneratedShort;
use App\Models\VideoProject;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class ProcessVideoJob implements ShouldQueue
{
    use Queueable;

    public int $timeout = 1800; // 30 minutes max
    public int $tries = 3;

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
        $usesSqlite = config('database.default') === 'sqlite';
        $ollamaHost = env('OLLAMA_HOST', 'http://127.0.0.1:11434');
        $ollamaModel = env('OLLAMA_MODEL', 'gemma:2b');

        $command = [
            $pythonBin,
            $scriptPath,
            '--project-id', (string) $this->projectId,
        ];
        if ($usesSqlite) {
            $command[] = '--db-path';
            $command[] = realpath(database_path('database.sqlite')) ?: str_replace('/', DIRECTORY_SEPARATOR, database_path('database.sqlite'));
        } else {
            $command[] = '--project-stdin';
        }

        array_push($command, '--ollama-host', $ollamaHost, '--ollama-model', $ollamaModel);

        Log::info("ProcessVideoJob: Running command " . implode(' ', $command));

        $process = new Process($command);
        $process->setTimeout($this->timeout);
        $process->setWorkingDirectory(base_path());
        if (! $usesSqlite) {
            $process->setInput(json_encode([
                'id' => $project->id,
                'user_id' => $project->user_id,
                'title' => $project->title,
                'source_type' => $project->source_type,
                'source_url' => $project->source_url,
                'video_file_path' => $project->video_file_path,
                'clip_duration_pref' => $project->clip_duration_pref ?: 30,
                'clips_count' => $project->clips_count ?? 3,
                'mode' => $project->mode ?: 'dialogue',
            ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE));
        }

        try {
            $outputBuffers = [Process::OUT => '', Process::ERR => ''];
            $process->run(function (string $type, string $buffer) use (&$outputBuffers, $project): void {
                $outputBuffers[$type] .= $buffer;
                while (($lineEnd = strpos($outputBuffers[$type], "\n")) !== false) {
                    $line = substr($outputBuffers[$type], 0, $lineEnd);
                    $outputBuffers[$type] = substr($outputBuffers[$type], $lineEnd + 1);
                    $this->handleWorkerOutputLine(rtrim($line, "\r"), $type, $project);
                }
            });
            foreach ($outputBuffers as $type => $buffer) {
                if ($buffer !== '') {
                    $this->handleWorkerOutputLine($buffer, $type, $project);
                }
            }

            if (! $usesSqlite && $process->isSuccessful()) {
                $project->refresh();
                if ($project->status !== 'completed') {
                    throw new \RuntimeException('Python worker finished without reporting completion.');
                }
            }

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

                // Automatic token refund on failure
                if ($project->tokens_used > 0 && $project->user) {
                    $project->user->addTokens(
                        $project->tokens_used,
                        "Automatic refund for failed project #{$project->id}",
                        null,
                        'refund'
                    );
                    $project->update(['tokens_used' => 0]);
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

            // Automatic token refund on failure
            if ($project->tokens_used > 0 && $project->user) {
                $project->user->addTokens(
                    $project->tokens_used,
                    "Automatic refund for failed project #{$project->id}",
                    null,
                    'refund'
                );
                $project->update(['tokens_used' => 0]);
            }
        }
    }

    private function handleWorkerOutputLine(string $line, string $outputType, VideoProject $project): void
    {
        if ($outputType !== Process::OUT || ! str_starts_with($line, 'VIDEO_EVENT:')) {
            if ($line !== '') {
                Log::info('Python Worker: '.$line);
            }

            return;
        }

        $event = json_decode(substr($line, strlen('VIDEO_EVENT:')), true, 512, JSON_THROW_ON_ERROR);
        if (! is_array($event)) {
            throw new \UnexpectedValueException('Python worker sent an invalid event.');
        }

        $this->handleWorkerEvent($event, $project);
    }

    /**
     * @param  array<string, mixed>  $event
     */
    private function handleWorkerEvent(array $event, VideoProject $project): void
    {
        switch ($event['type'] ?? null) {
            case 'progress':
                $attributes = [
                    'progress_percent' => (int) $event['percent'],
                    'progress_step' => (string) $event['step'],
                ];
                foreach (['status', 'error_message', 'raw_analysis'] as $attribute) {
                    if (! empty($event[$attribute])) {
                        $attributes[$attribute] = $event[$attribute];
                    }
                }

                $project->update($attributes);
                break;

            case 'title':
                $project->update(['title' => (string) $event['title']]);
                break;

            case 'duration':
                $project->update(['duration_seconds' => (int) $event['duration_seconds']]);
                break;

            case 'shorts':
                $this->saveWorkerShorts($event, $project);
                break;

            default:
                throw new \UnexpectedValueException('Python worker sent an unknown event.');
        }
    }

    /**
     * @param  array<string, mixed>  $event
     */
    private function saveWorkerShorts(array $event, VideoProject $project): void
    {
        $shorts = $event['shorts'] ?? null;
        if (! is_array($shorts) || $shorts === []) {
            throw new \UnexpectedValueException('Python worker returned no shorts.');
        }

        DB::transaction(function () use ($shorts, $event, $project): void {
            foreach ($shorts as $short) {
                if (! is_array($short)) {
                    throw new \UnexpectedValueException('Python worker sent an invalid short.');
                }

                $generatedShort = new GeneratedShort;
                $generatedShort->user_id = $project->user_id;
                $generatedShort->title = (string) $short['title'];
                $generatedShort->description = (string) $short['description'];
                $generatedShort->tags = (string) $short['tags'];
                $generatedShort->start_seconds = (float) $short['start_seconds'];
                $generatedShort->end_seconds = (float) $short['end_seconds'];
                $generatedShort->duration_seconds = (int) $short['duration_seconds'];
                $generatedShort->hook_reason = (string) $short['hook_reason'];
                $generatedShort->video_path = (string) $short['video_path'];
                $generatedShort->thumbnail_path = (string) $short['thumbnail_path'];
                $generatedShort->file_size_bytes = (int) $short['file_size_bytes'];
                $project->shorts()->save($generatedShort);
            }

            $project->notifications()->create([
                'user_id' => $project->user_id,
                'title' => '🎉 Shorts Ready to View & Download!',
                'message' => count($shorts).' viral Shorts extracted from \''.(string) $event['project_title'].'\' (Mukhda & Hook Climax with Subscribe CTA).',
                'type' => 'success',
                'is_read' => false,
            ]);
        });
    }

    /**
     * Handle a job failure from queue timeout or max attempts.
     */
    public function failed(?\Throwable $exception = null): void
    {
        $project = VideoProject::find($this->projectId);
        if (!$project) {
            return;
        }

        $project->refresh();
        if ($project->status !== 'completed') {
            $errorMsg = $exception ? $exception->getMessage() : 'Queue worker timeout or max attempts exceeded.';
            $project->update([
                'status' => 'failed',
                'progress_step' => 'Processing failed: ' . substr($errorMsg, 0, 150),
                'error_message' => $errorMsg,
            ]);

            // Automatic token refund on failure
            if ($project->tokens_used > 0 && $project->user) {
                $tokensToRefund = $project->tokens_used;
                $project->user->addTokens(
                    $tokensToRefund,
                    "Automatic refund for failed project #{$project->id}",
                    null,
                    'refund'
                );
                $project->update(['tokens_used' => 0]);
                Log::info("ProcessVideoJob: Refunded {$tokensToRefund} tokens to user #{$project->user_id} for project #{$project->id}");
            }
        }
    }
}
