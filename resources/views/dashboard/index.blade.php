@extends('layouts.app')

@section('content')
<div class="space-y-8" x-data="dashboardPoller()">
    <!-- Top Welcome Banner -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-6 rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950/40 to-slate-900 border border-slate-800 shadow-xl">
        <div>
            <h1 class="text-2xl font-bold text-white flex items-center gap-2">
                <span>Welcome back, {{ auth()->user()->name }}!</span>
                <span class="text-xl">👋</span>
            </h1>
            <p class="text-sm text-slate-400 mt-1">
                Your AI Video Studio. Extract 30s/60s viral shorts from long videos with Ollama Gemma.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('wallet') }}" class="inline-flex items-center gap-2 px-4 py-3 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-300 font-bold text-sm hover:bg-amber-500/20 transition">
                <i class="fa-solid fa-coins text-amber-400"></i>
                <span>{{ number_format(auth()->user()->tokens) }} Tokens</span>
            </a>
            <a href="{{ route('projects.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-500 via-purple-600 to-pink-500 hover:from-indigo-600 hover:to-pink-600 text-white font-semibold px-5 py-3 rounded-2xl shadow-lg shadow-indigo-500/25 transition hover:scale-105">
                <i class="fa-solid fa-wand-magic-sparkles"></i>
                <span>Create New Shorts</span>
            </a>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-slate-900/60 border border-slate-800 p-5 rounded-2xl">
            <div class="flex items-center justify-between text-slate-400 text-xs font-semibold uppercase tracking-wider mb-2">
                <span>Total Projects</span>
                <i class="fa-solid fa-film text-indigo-400"></i>
            </div>
            <div class="text-2xl font-bold text-white">{{ $stats['total_projects'] }}</div>
        </div>

        <div class="bg-slate-900/60 border border-slate-800 p-5 rounded-2xl">
            <div class="flex items-center justify-between text-slate-400 text-xs font-semibold uppercase tracking-wider mb-2">
                <span>Generated Shorts</span>
                <i class="fa-solid fa-play text-pink-400"></i>
            </div>
            <div class="text-2xl font-bold text-white">{{ $stats['total_shorts'] }}</div>
        </div>

        <div class="bg-slate-900/60 border border-slate-800 p-5 rounded-2xl">
            <div class="flex items-center justify-between text-slate-400 text-xs font-semibold uppercase tracking-wider mb-2">
                <span>In Queue / Processing</span>
                <i class="fa-solid fa-spinner {{ $stats['processing'] > 0 ? 'fa-spin text-amber-400' : 'text-slate-500' }}"></i>
            </div>
            <div class="text-2xl font-bold {{ $stats['processing'] > 0 ? 'text-amber-400' : 'text-white' }}">
                {{ $stats['processing'] }}
            </div>
        </div>

        <div class="bg-slate-900/60 border border-slate-800 p-5 rounded-2xl">
            <div class="flex items-center justify-between text-slate-400 text-xs font-semibold uppercase tracking-wider mb-2">
                <span>Completed</span>
                <i class="fa-solid fa-circle-check text-emerald-400"></i>
            </div>
            <div class="text-2xl font-bold text-emerald-400">{{ $stats['completed'] }}</div>
        </div>
    </div>

    <!-- Video Projects History Section -->
    <div class="bg-slate-900/80 border border-slate-800 rounded-3xl shadow-xl overflow-hidden">
        <div class="p-6 border-b border-slate-800 flex items-center justify-between flex-wrap gap-4">
            <div>
                <h2 class="text-lg font-bold text-white">Your Video Projects History</h2>
                <p class="text-xs text-slate-400 mt-0.5">Videos submitted for AI hook extraction and shorts rendering</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-slate-800 text-slate-300 border border-slate-700">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Auto-refreshing status
                </span>
            </div>
        </div>

        @if($projects->isEmpty())
            <div class="p-12 text-center">
                <div class="w-16 h-16 rounded-3xl bg-slate-800/80 text-slate-500 flex items-center justify-center mx-auto mb-4 text-2xl">
                    <i class="fa-solid fa-video-slash"></i>
                </div>
                <h3 class="text-base font-semibold text-white">No video projects yet</h3>
                <p class="text-sm text-slate-400 mt-1 max-w-sm mx-auto">Paste a YouTube link or upload your first video to start generating AI shorts.</p>
                <a href="{{ route('projects.create') }}" class="mt-5 inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition">
                    <i class="fa-solid fa-plus"></i>
                    <span>Create Your First Short</span>
                </a>
            </div>
        @else
            <div class="divide-y divide-slate-800/80">
                @foreach($projects as $project)
                    <div class="p-5 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:bg-slate-800/30 transition">
                        <div class="flex items-start gap-4">
                            <!-- Source Icon -->
                            <div class="w-12 h-12 rounded-2xl flex-shrink-0 flex items-center justify-center text-lg {{ $project->source_type === 'youtube' ? 'bg-red-500/10 text-red-500 border border-red-500/20' : 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' }}">
                                @if($project->source_type === 'youtube')
                                    <i class="fa-brands fa-youtube"></i>
                                @else
                                    <i class="fa-solid fa-file-video"></i>
                                @endif
                            </div>

                            <!-- Details -->
                            <div>
                                <a href="{{ route('projects.show', $project->id) }}" class="font-semibold text-white hover:text-indigo-400 transition text-base line-clamp-1">
                                    {{ $project->title ?: 'Video Project #' . $project->id }}
                                </a>
                                <div class="flex flex-wrap items-center gap-3 text-xs text-slate-400 mt-1.5">
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
                                    <span class="uppercase font-medium text-[10px] px-2 py-0.5 rounded {{ $project->source_type === 'youtube' ? 'bg-red-950 text-red-300' : 'bg-indigo-950 text-indigo-300' }}">
                                        {{ $project->source_type }}
                                    </span>
                                </div>

                                <!-- Progress Bar if processing -->
                                @if(in_array($project->status, ['pending', 'processing']))
                                    <div class="mt-3 w-full max-w-md">
                                        <div class="flex items-center justify-between text-xs mb-1">
                                            <span class="text-indigo-300 flex items-center gap-1.5">
                                                <i class="fa-solid fa-circle-notch fa-spin text-xs"></i>
                                                {{ $project->progress_step }}
                                            </span>
                                            <span class="font-mono text-slate-400">{{ $project->progress_percent }}%</span>
                                        </div>
                                        <div class="w-full bg-slate-800 rounded-full h-1.5 overflow-hidden">
                                            <div class="bg-gradient-to-r from-indigo-500 to-pink-500 h-1.5 rounded-full transition-all duration-500" style="width: {{ $project->progress_percent }}%"></div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Right Status and Actions -->
                        <div class="flex items-center gap-3 self-end md:self-center">
                            @if($project->status === 'completed')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-950/80 text-emerald-400 border border-emerald-800">
                                    <i class="fa-solid fa-circle-check"></i>
                                    {{ $project->shorts_count }} Shorts Ready
                                </span>
                            @elseif($project->status === 'failed')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-950/80 text-rose-400 border border-rose-800" title="{{ $project->error_message }}">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                    Failed
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-950/80 text-amber-400 border border-amber-800">
                                    <i class="fa-solid fa-spinner fa-spin"></i>
                                    Processing
                                </span>
                            @endif

                            <a href="{{ route('projects.show', $project->id) }}" class="inline-flex items-center gap-1.5 bg-slate-800 hover:bg-slate-700 text-white text-xs font-semibold px-3 py-2 rounded-xl transition">
                                <span>View</span>
                                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </a>

                            <form action="{{ route('projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('Delete this project and its generated shorts?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 rounded-xl transition" title="Delete Project">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($projects->hasPages())
                <div class="p-4 border-t border-slate-800">
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
