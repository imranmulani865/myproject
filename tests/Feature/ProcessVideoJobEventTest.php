<?php

namespace Tests\Feature;

use App\Jobs\ProcessVideoJob;
use App\Models\User;
use App\Models\VideoProject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ReflectionMethod;
use Symfony\Component\Process\Process;
use Tests\TestCase;

class ProcessVideoJobEventTest extends TestCase
{
    use RefreshDatabase;

    public function test_worker_events_persist_progress_and_generated_shorts(): void
    {
        $user = User::factory()->create();
        $project = VideoProject::create([
            'user_id' => $user->id,
            'title' => 'Original title',
            'source_type' => 'upload',
            'video_file_path' => 'source.mp4',
            'status' => 'processing',
        ]);
        $job = new ProcessVideoJob($project->id);
        $handleOutputLine = new ReflectionMethod($job, 'handleWorkerOutputLine');

        $events = [
            ['type' => 'title', 'title' => 'Updated title'],
            ['type' => 'duration', 'duration_seconds' => 124],
            ['type' => 'progress', 'percent' => 65, 'step' => 'Rendering shorts', 'raw_analysis' => 'A promising hook'],
            [
                'type' => 'shorts',
                'project_title' => 'Updated title',
                'shorts' => [[
                    'title' => 'First short',
                    'description' => 'A short description',
                    'tags' => '#shorts',
                    'start_seconds' => 12.5,
                    'end_seconds' => 42.5,
                    'duration_seconds' => 30,
                    'hook_reason' => 'Strong opening',
                    'video_path' => 'media/shorts/p_1/short.mp4',
                    'thumbnail_path' => 'media/shorts/p_1/thumb.jpg',
                    'file_size_bytes' => 1024,
                ]],
            ],
            ['type' => 'progress', 'percent' => 100, 'step' => 'Done', 'status' => 'completed'],
        ];

        foreach ($events as $event) {
            $handleOutputLine->invoke($job, 'VIDEO_EVENT:'.json_encode($event, JSON_THROW_ON_ERROR), Process::OUT, $project);
        }

        $this->assertDatabaseHas('video_projects', [
            'id' => $project->id,
            'title' => 'Updated title',
            'duration_seconds' => 124,
            'status' => 'completed',
            'progress_percent' => 100,
            'raw_analysis' => 'A promising hook',
        ]);
        $this->assertDatabaseHas('generated_shorts', [
            'video_project_id' => $project->id,
            'user_id' => $user->id,
            'title' => 'First short',
            'hook_reason' => 'Strong opening',
            'video_path' => 'media/shorts/p_1/short.mp4',
            'file_size_bytes' => 1024,
        ]);
        $this->assertDatabaseHas('app_notifications', [
            'video_project_id' => $project->id,
            'user_id' => $user->id,
            'type' => 'success',
        ]);
    }
}
