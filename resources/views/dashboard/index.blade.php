@extends('layouts.app')

@section('content')
<div class="space-y-8" x-data="dashboardPoller()">
    <!-- Top Welcome Banner -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-5 p-6 sm:p-8 rounded-3xl bg-gradient-to-r from-studio-surface via-studio-surface to-studio-lavender/60 border border-studio-line shadow-[0_18px_48px_rgba(48,40,166,0.08)]">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-studio-ink">
                Welcome back, {{ auth()->user()->name }}.
            </h1>
            <p class="text-sm text-studio-muted mt-2">
                Track your video projects and the shorts created from each one.
            </p>
        </div>
        <div>
            <a href="{{ route('projects.create') }}" class="inline-flex min-h-11 items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-semibold px-5 py-3 rounded-xl shadow-lg shadow-brand-600/20 transition-colors">
                <i class="fa-solid fa-plus" aria-hidden="true"></i>
                <span>New project</span>
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 overflow-hidden rounded-3xl border border-studio-line bg-studio-surface shadow-sm">
        <div class="border-b border-r border-studio-line p-5 md:border-b-0">
            <div class="flex items-center justify-between text-studio-muted text-sm font-medium mb-2">
                <span>Total projects</span>
                <i class="fa-solid fa-film text-brand-500" aria-hidden="true"></i>
            </div>
            <div class="text-3xl font-bold text-studio-ink">{{ $stats['total_projects'] }}</div>
        </div>

        <div class="border-b border-studio-line p-5 md:border-b-0 md:border-r">
            <div class="flex items-center justify-between text-studio-muted text-sm font-medium mb-2">
                <span>Generated shorts</span>
                <i class="fa-solid fa-play text-brand-500" aria-hidden="true"></i>
            </div>
            <div class="text-3xl font-bold text-studio-ink">{{ $stats['total_shorts'] }}</div>
        </div>

        <div class="border-r border-studio-line p-5">
            <div class="flex items-center justify-between text-studio-muted text-sm font-medium mb-2">
                <span>Processing</span>
                <i class="fa-solid fa-spinner {{ $stats['processing'] > 0 ? 'fa-spin text-amber-600' : 'text-studio-muted' }}" aria-hidden="true"></i>
            </div>
            <div class="text-3xl font-bold {{ $stats['processing'] > 0 ? 'text-amber-700' : 'text-studio-ink' }}">
                {{ $stats['processing'] }}
            </div>
        </div>

        <div class="p-5">
            <div class="flex items-center justify-between text-studio-muted text-sm font-medium mb-2">
                <span>Completed</span>
                <i class="fa-solid fa-circle-check text-emerald-600" aria-hidden="true"></i>
            </div>
            <div class="text-3xl font-bold text-emerald-700">{{ $stats['completed'] }}</div>
        </div>
    </div>

    <!-- Video Projects History Section -->
    <div class="bg-studio-surface border border-studio-line rounded-3xl shadow-[0_18px_48px_rgba(48,40,166,0.06)] overflow-hidden">
        <div class="p-6 border-b border-studio-line flex items-center justify-between flex-wrap gap-4">
            <div>
                <h2 class="text-lg font-bold text-studio-ink">Your video projects</h2>
                <p class="text-sm text-studio-muted mt-0.5">Follow each video from upload to finished shorts.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-studio-canvas text-studio-muted border border-studio-line">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Auto-refreshing status
                </span>
            </div>
        </div>

        @if($projects->isEmpty())
            <div class="p-12 text-center">
                <div class="w-16 h-16 rounded-3xl bg-studio-lavender text-brand-600 flex items-center justify-center mx-auto mb-4 text-2xl">
                    <i class="fa-solid fa-video-slash"></i>
                </div>
                <h3 class="text-base font-semibold text-studio-ink">No video projects yet</h3>
                <p class="text-sm text-studio-muted mt-1 max-w-sm mx-auto">Paste a YouTube link or upload a video to create your first shorts.</p>
                <a href="{{ route('projects.create') }}" class="mt-5 inline-flex min-h-11 items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors">
                    <i class="fa-solid fa-plus"></i>
                    <span>Create a project</span>
                </a>
            </div>
        @else
            <div class="divide-y divide-studio-line">
                @foreach($projects as $project)
                    <div class="p-5 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:bg-studio-canvas/80 transition-colors">
                        <div class="flex items-start gap-4">
                            <!-- Source Icon -->
                            <div class="w-12 h-12 rounded-2xl flex-shrink-0 flex items-center justify-center text-lg {{ $project->source_type === 'youtube' ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-studio-lavender text-brand-600 border border-brand-200' }}">
                                @if($project->source_type === 'youtube')
                                    <i class="fa-brands fa-youtube"></i>
                                @else
                                    <i class="fa-solid fa-file-video"></i>
                                @endif
                            </div>

                            <!-- Details -->
                            <div>
                                <a href="{{ route('projects.show', $project->id) }}" class="font-semibold text-studio-ink hover:text-brand-600 transition-colors text-base line-clamp-1">
                                    {{ $project->title ?: 'Video Project #' . $project->id }}
                                </a>
                                <div class="flex flex-wrap items-center gap-3 text-xs text-studio-muted mt-1.5">
                                    <span>
                                        <i class="fa-regular fa-clock mr-1"></i>
                                        {{ $project->created_at->diffForHumans() }}
                                    </span>
                                    <span>•</span>
                                    <span class="inline-flex items-center gap-1">
                                        <i class="fa-solid fa-scissors"></i>
                                        {{ $project->clip_duration_pref }}s clips ({{ $project->clips_count }} requested)
                                    </span>
                                    <span>•</span>
                                    <span class="uppercase font-medium text-[10px] px-2 py-0.5 rounded {{ $project->source_type === 'youtube' ? 'bg-red-50 text-red-700' : 'bg-studio-lavender text-brand-700' }}">
                                        {{ $project->source_type }}
                                    </span>
                                </div>

                                <!-- Progress Bar if processing -->
                                @if(in_array($project->status, ['pending', 'processing']))
                                    <div class="mt-3 w-full max-w-md">
                                        <div class="flex items-center justify-between text-xs mb-1">
                                            <span class="text-brand-600 flex items-center gap-1.5">
                                                <i class="fa-solid fa-circle-notch fa-spin text-xs"></i>
                                                {{ $project->progress_step }}
                                            </span>
                                            <span class="font-mono text-studio-muted">{{ $project->progress_percent }}%</span>
                                        </div>
                                        <div class="w-full bg-studio-line rounded-full h-1.5 overflow-hidden">
                                            <div class="bg-brand-600 h-1.5 rounded-full transition-all duration-500" style="width: {{ $project->progress_percent }}%"></div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Right Status and Actions -->
                        <div class="flex items-center gap-3 self-end md:self-center">
                            @if($project->status === 'completed')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <i class="fa-solid fa-circle-check"></i>
                                    {{ $project->shorts_count }} Shorts Ready
                                </span>
                            @elseif($project->status === 'failed')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200" title="{{ $project->error_message }}">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                    Failed
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                    <i class="fa-solid fa-spinner fa-spin"></i>
                                    Processing
                                </span>
                            @endif

                            <a href="{{ route('projects.show', $project->id) }}" class="inline-flex min-h-10 items-center gap-1.5 bg-studio-lavender hover:bg-brand-200 text-brand-700 text-xs font-semibold px-3 py-2 rounded-xl transition-colors">
                                <span>View</span>
                                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </a>

                            <form action="{{ route('projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('Delete this project and its generated shorts?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-studio-muted hover:text-rose-700 hover:bg-rose-50 rounded-xl transition-colors" title="Delete Project">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($projects->hasPages())
                <div class="p-4 border-t border-studio-line">
                    {{ $projects->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

@push('scripts')
<script>
function dashboardPoller() {
    return {
        init() {
            // Check if any processing projects exist
            const hasProcessing = {{ $stats['processing'] > 0 ? 'true' : 'false' }};
            if (hasProcessing) {
                setInterval(() => {
                    window.location.reload();
                }, 8000);
            }
        }
    }
}
</script>
@endpush
@endsection
