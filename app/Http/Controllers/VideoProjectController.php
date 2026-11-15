<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessVideoJob;
use App\Models\VideoProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class VideoProjectController extends Controller
{
    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'source_type' => ['required', 'in:youtube,upload'],
            'youtube_url' => ['required_if:source_type,youtube', 'nullable', 'url'],
            'video_file' => ['required_if:source_type,upload', 'nullable', 'file', 'mimes:mp4,mov,avi,mkv,webm', 'max:512000'], // up to 500MB
            'title' => ['nullable', 'string', 'max:255'],
            'clip_duration_pref' => ['required', 'in:30,60'],
            'clips_count' => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        $sourceType = $validated['source_type'];
        $videoFilePath = null;
        $title = $validated['title'];

        if ($sourceType === 'upload') {
            $file = $request->file('video_file');
            $filename = 'upload_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('media/uploads');
            
            if (!File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true, true);
            }
            
            $file->move($destinationPath, $filename);
            $videoFilePath = 'media/uploads/' . $filename;

            if (empty($title)) {
                $title = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            }
        } else {
            if (empty($title)) {
                $title = 'YouTube Video Project';
            }
        }

        $project = VideoProject::create([
            'user_id' => Auth::id(),
            'title' => $title,
            'source_type' => $sourceType,
            'source_url' => $validated['youtube_url'] ?? null,
            'video_file_path' => $videoFilePath,
            'clip_duration_pref' => (int) $validated['clip_duration_pref'],
            'clips_count' => (int) $validated['clips_count'],
            'status' => 'pending',
            'progress_step' => 'Queued in background processing queue...',
            'progress_percent' => 0,
        ]);

        // Dispatch background job to process the video
        ProcessVideoJob::dispatch($project->id);

        return redirect()->route('projects.show', $project->id)
            ->with('success', 'Video submitted successfully! Processing in background queue.');
    }

    public function show(int $id)
    {
        $project = VideoProject::where('user_id', Auth::id())
            ->with('shorts')
            ->findOrFail($id);

        return view('projects.show', compact('project'));
    }

    public function status(int $id)
    {
        $project = VideoProject::where('user_id', Auth::id())
            ->withCount('shorts')
            ->findOrFail($id);

        return response()->json([
            'id' => $project->id,
            'status' => $project->status,
            'progress_percent' => $project->progress_percent,
            'progress_step' => $project->progress_step,
            'shorts_count' => $project->shorts_count,
            'error_message' => $project->error_message,
            'is_completed' => $project->isCompleted(),
            'is_failed' => $project->isFailed(),
        ]);
    }

    public function destroy(int $id)
    {
        $project = VideoProject::where('user_id', Auth::id())->findOrFail($id);

        // Delete uploaded video if exists
        if ($project->video_file_path && File::exists(public_path($project->video_file_path))) {
            File::delete(public_path($project->video_file_path));
        }

        // Delete shorts directory if exists
        $shortsDir = public_path("media/shorts/p_{$project->id}");
        if (File::isDirectory($shortsDir)) {
            File::deleteDirectory($shortsDir);
        }

        $project->delete();

        return redirect()->route('dashboard')->with('success', 'Project deleted successfully.');
    }
}
