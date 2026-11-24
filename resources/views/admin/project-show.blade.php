@extends('layouts.app')

@section('title', 'Project details • Admin')

@section('content')
@php
    $formatTimestamp = static function (int|float $seconds): string {
        $wholeSeconds = max(0, (int) floor($seconds));

        return sprintf('%02d:%02d:%02d', intdiv($wholeSeconds, 3600), intdiv($wholeSeconds % 3600, 60), $wholeSeconds % 60);
    };
@endphp
<div class="mx-auto max-w-7xl space-y-7 py-4">
    @include('admin.navigation')
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <a href="{{ route('admin.projects') }}" class="text-sm font-semibold text-[#4D5CFF] hover:underline">← All projects</a>
            <h1 class="mt-3 font-['Space_Grotesk'] text-3xl font-bold tracking-tight text-[#152238]">{{ $project->title ?: 'Untitled project' }}</h1>
            <p class="mt-1 text-sm text-[#5E6C83]">Project #{{ $project->id }} · Created {{ $project->created_at?->format('M j, Y g:i A') }}</p>
        </div>
        @if ($project->user)
            <a href="{{ route('admin.users.show', $project->user) }}" class="rounded-xl border border-[#CCD6E5] bg-white px-4 py-2.5 text-sm font-bold text-[#3E4BE8] hover:bg-[#EEF0FF]">{{ $project->user->name }} ↗</a>
        @endif
    </div>

    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-2xl border border-[#DCE3EF] bg-white p-5"><p class="text-xs font-semibold text-[#687684]">Status</p><p class="mt-2 font-['Space_Grotesk'] text-xl font-bold capitalize text-[#152238]">{{ $project->status }}</p><p class="mt-1 text-xs text-[#687684]">{{ $project->progress_percent }}% complete</p></div>
        <div class="rounded-2xl border border-[#DCE3EF] bg-white p-5"><p class="text-xs font-semibold text-[#687684]">Source</p><p class="mt-2 font-['Space_Grotesk'] text-xl font-bold capitalize text-[#152238]">{{ $project->source_type }}</p><p class="mt-1 text-xs capitalize text-[#687684]">{{ $project->mode }} mode</p></div>
        <div class="rounded-2xl border border-[#DCE3EF] bg-white p-5"><p class="text-xs font-semibold text-[#687684]">Requested clips</p><p class="mt-2 font-['Space_Grotesk'] text-xl font-bold text-[#152238]">{{ $project->clips_count === 0 ? 'All possible' : $project->clips_count }}</p><p class="mt-1 text-xs text-[#687684]">{{ $project->clip_duration_pref }} second preference</p></div>
        <div class="rounded-2xl border border-[#DCE3EF] bg-white p-5"><p class="text-xs font-semibold text-[#687684]">Credits used</p><p class="mt-2 font-['Space_Grotesk'] text-xl font-bold text-[#152238]">{{ $project->tokens_used }}</p><p class="mt-1 text-xs text-[#687684]">Source duration: {{ $project->duration_seconds ? $formatTimestamp($project->duration_seconds) : 'Unknown' }}</p></div>
    </div>

    <section class="rounded-2xl border border-[#DCE3EF] bg-white p-5" aria-labelledby="project-progress-heading">
        <h2 id="project-progress-heading" class="font-['Space_Grotesk'] text-xl font-bold text-[#152238]">Processing details</h2>
        <div class="mt-4 h-2 overflow-hidden rounded-full bg-[#E9ECF8]"><div class="h-full rounded-full bg-[#4D5CFF]" style="width: {{ max(0, min(100, (int) $project->progress_percent)) }}%"></div></div>
        <p class="mt-3 text-sm text-[#526078]">{{ $project->progress_step ?: 'No progress message yet.' }}</p>
        @if ($project->error_message)
            <div class="mt-4 rounded-xl border border-[#FFD5CE] bg-[#FFF3F0] p-4 text-sm text-[#A84339]"><strong>Processing error:</strong> {{ $project->error_message }}</div>
        @endif
        <dl class="mt-5 grid gap-4 text-sm sm:grid-cols-2">
            <div><dt class="font-semibold text-[#687684]">Source URL</dt><dd class="mt-1 break-all text-[#152238]">{{ $project->source_url ?: 'None — uploaded file' }}</dd></div>
            <div><dt class="font-semibold text-[#687684]">Uploaded file path</dt><dd class="mt-1 break-all font-mono text-xs text-[#152238]">{{ $project->video_file_path ?: 'None' }}</dd></div>
            <div><dt class="font-semibold text-[#687684]">Last updated</dt><dd class="mt-1 text-[#152238]">{{ $project->updated_at?->format('M j, Y g:i A') }}</dd></div>
        </dl>
        @if ($project->raw_analysis)
            <details class="mt-5 rounded-xl border border-[#E7ECF5] bg-[#FAFBFE] p-4"><summary class="cursor-pointer text-sm font-bold text-[#3E4BE8]">View raw analysis</summary><pre class="mt-3 max-h-80 overflow-auto whitespace-pre-wrap break-words text-xs text-[#526078]">{{ $project->raw_analysis }}</pre></details>
        @endif
    </section>

    <section class="rounded-2xl border border-[#DCE3EF] bg-white p-5" aria-labelledby="project-shorts-heading">
        <h2 id="project-shorts-heading" class="font-['Space_Grotesk'] text-xl font-bold text-[#152238]">Generated shorts</h2>
        <div class="mt-4 grid gap-4 lg:grid-cols-2">
            @forelse ($shorts as $short)
                <article class="rounded-xl border border-[#E7ECF5] bg-[#FAFBFE] p-4">
                    <div class="flex flex-wrap items-start justify-between gap-3"><div><h3 class="font-bold text-[#152238]">{{ $short->title }}</h3><p class="mt-1 text-xs text-[#687684]">{{ $formatTimestamp($short->start_seconds) }}–{{ $formatTimestamp($short->end_seconds) }} · {{ $short->formatted_duration }} · {{ $short->formatted_file_size }}</p></div><a href="{{ $short->video_url }}" target="_blank" rel="noopener noreferrer" class="rounded-lg bg-[#E7EAFF] px-3 py-1.5 text-xs font-bold text-[#3E4BE8] hover:bg-[#D8DFFF]">View clip ↗</a></div>
                    @if ($short->hook_reason)<p class="mt-3 text-sm text-[#526078]"><strong>Hook:</strong> {{ $short->hook_reason }}</p>@endif
                    @if ($short->description)<p class="mt-2 whitespace-pre-line text-sm text-[#526078]">{{ $short->description }}</p>@endif
                    @if ($short->tags)<p class="mt-2 text-xs text-[#687684]">Tags: {{ $short->tags }}</p>@endif
                    <p class="mt-3 break-all font-mono text-[11px] text-[#8792A6]">{{ $short->video_path }}</p>
                </article>
            @empty
                <p class="text-sm text-[#687684]">No shorts generated for this project yet.</p>
            @endforelse
        </div>
        @if ($shorts->hasPages()) <div class="mt-4">{{ $shorts->links() }}</div> @endif
    </section>

    <section class="rounded-2xl border border-[#DCE3EF] bg-white p-5" aria-labelledby="project-notifications-heading">
        <h2 id="project-notifications-heading" class="font-['Space_Grotesk'] text-xl font-bold text-[#152238]">Related notifications</h2>
        <div class="mt-4 space-y-3">
            @forelse ($notifications as $notification)
                <div class="rounded-xl border border-[#EDF0F6] bg-[#FAFBFE] p-3"><div class="flex flex-wrap justify-between gap-2"><strong class="text-sm text-[#152238]">{{ $notification->title }}</strong><span class="text-xs text-[#687684]">{{ $notification->created_at?->format('M j, Y g:i A') }}</span></div><p class="mt-1 text-sm text-[#526078]">{{ $notification->message }}</p></div>
            @empty
                <p class="text-sm text-[#687684]">No notifications for this project.</p>
            @endforelse
        </div>
        @if ($notifications->hasPages()) <div class="mt-4">{{ $notifications->links() }}</div> @endif
    </section>
</div>
@endsection
