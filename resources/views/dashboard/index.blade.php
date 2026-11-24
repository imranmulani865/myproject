@extends('layouts.app')

@section('title', 'Dashboard • AI Shorts Creator')

@section('content')
<div class="space-y-7" x-data="dashboardPoller()">
    <section class="relative overflow-hidden rounded-[1.75rem] border border-[#DDE4F0] bg-white p-6 shadow-[0_16px_50px_rgba(21,34,56,.06)] sm:p-8">
        <div class="absolute -right-20 -top-24 h-72 w-72 rounded-full bg-[#DDE2FF]/60 blur-3xl" aria-hidden="true"></div>
        <div class="relative flex flex-col justify-between gap-6 md:flex-row md:items-center">
            <div class="flex items-start gap-4">
                <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-[#4D5CFF] font-['Space_Grotesk'] text-2xl font-bold text-white shadow-[0_10px_22px_rgba(77,92,255,.2)]">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                <div>
                    <p class="mb-1 text-xs font-bold text-[#4D5CFF]">YOUR CREATOR STUDIO</p>
                    <h1 class="font-['Space_Grotesk'] text-2xl font-bold tracking-[-.05em] text-[#152238] sm:text-3xl">Welcome back, {{ auth()->user()->name }}</h1>
                    <p class="mt-2 text-sm text-[#687684]">Your next short is one great moment away.</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('wallet') }}" class="inline-flex min-h-11 items-center gap-2 rounded-xl border border-[#D8DFFF] bg-[#F6F7FF] px-4 py-2 text-sm font-bold text-[#4251CE] hover:bg-[#EEF0FF]">
                    <i class="fa-solid fa-coins" aria-hidden="true"></i>{{ number_format(auth()->user()->tokens) }} credits
                </a>
                <a href="{{ route('projects.create') }}" class="inline-flex min-h-11 items-center gap-2 rounded-xl bg-[#4D5CFF] px-5 py-2 text-sm font-bold text-white shadow-[0_8px_18px_rgba(77,92,255,.18)] hover:bg-[#3949E9]">
                    <i class="fa-solid fa-plus" aria-hidden="true"></i>Create shorts
                </a>
            </div>
        </div>
    </section>

    <div class="flex items-start gap-3 rounded-2xl border border-[#D8DFFF] bg-[#F6F7FF] px-4 py-3 text-sm text-[#4251CE]">
        <i class="fa-solid fa-download mt-0.5" aria-hidden="true"></i>
        <p><strong>Keep a copy of every short.</strong> Download your finished clips to your device for easy access.</p>
    </div>

    <section class="grid grid-cols-2 gap-4 lg:grid-cols-4" aria-label="Project summary">
        <div class="rounded-2xl border border-[#DDE4F0] bg-white p-5 shadow-[0_10px_30px_rgba(21,34,56,.04)]">
            <div class="flex items-center justify-between"><span class="text-xs font-semibold text-[#687684]">Projects</span><i class="fa-solid fa-film text-[#4D5CFF]" aria-hidden="true"></i></div>
            <p class="mt-4 font-['Space_Grotesk'] text-3xl font-bold text-[#152238]">{{ $stats['total_projects'] }}</p>
            <p class="mt-1 text-xs text-[#8A96A8]">Videos added</p>
        </div>
        <div class="rounded-2xl border border-[#DDE4F0] bg-white p-5 shadow-[0_10px_30px_rgba(21,34,56,.04)]">
            <div class="flex items-center justify-between"><span class="text-xs font-semibold text-[#687684]">Shorts</span><i class="fa-solid fa-play text-[#FF735B]" aria-hidden="true"></i></div>
            <p class="mt-4 font-['Space_Grotesk'] text-3xl font-bold text-[#152238]">{{ $stats['total_shorts'] }}</p>
            <p class="mt-1 text-xs text-[#8A96A8]">Ready to share</p>
        </div>
        <div class="rounded-2xl border border-[#DDE4F0] bg-white p-5 shadow-[0_10px_30px_rgba(21,34,56,.04)]">
            <div class="flex items-center justify-between"><span class="text-xs font-semibold text-[#687684]">Processing</span><i class="fa-solid fa-circle-notch {{ $stats['processing'] > 0 ? 'fa-spin text-[#4D5CFF]' : 'text-[#A6B1C2]' }}" aria-hidden="true"></i></div>
            <p class="mt-4 font-['Space_Grotesk'] text-3xl font-bold text-[#152238]">{{ $stats['processing'] }}</p>
            <p class="mt-1 text-xs text-[#8A96A8]">In the queue</p>
        </div>
        <div class="rounded-2xl border border-[#DDE4F0] bg-white p-5 shadow-[0_10px_30px_rgba(21,34,56,.04)]">
            <div class="flex items-center justify-between"><span class="text-xs font-semibold text-[#687684]">Completed</span><i class="fa-solid fa-circle-check text-[#2EAD79]" aria-hidden="true"></i></div>
            <p class="mt-4 font-['Space_Grotesk'] text-3xl font-bold text-[#152238]">{{ $stats['completed'] }}</p>
            <p class="mt-1 text-xs text-[#8A96A8]">Finished projects</p>
        </div>
    </section>

    <section class="overflow-hidden rounded-[1.75rem] border border-[#DDE4F0] bg-white shadow-[0_16px_50px_rgba(21,34,56,.05)]" aria-labelledby="projects-title">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#E8ECF3] px-6 py-5">
            <div>
                <h2 id="projects-title" class="font-['Space_Grotesk'] text-xl font-bold tracking-[-.03em] text-[#152238]">Your projects</h2>
                <p class="mt-1 text-sm text-[#687684]">Follow each video from source to finished short.</p>
            </div>
            @if($stats['processing'] > 0)
                <span class="inline-flex items-center gap-2 rounded-full bg-[#EEF0FF] px-3 py-1.5 text-xs font-semibold text-[#4251CE]"><span class="h-1.5 w-1.5 rounded-full bg-[#4D5CFF]"></span> Updating automatically</span>
            @endif
        </div>

        @if($projects->isEmpty())
            <div class="px-6 py-16 text-center">
                <span class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-2xl bg-[#EEF0FF] text-2xl text-[#4D5CFF]"><i class="fa-solid fa-film" aria-hidden="true"></i></span>
                <h3 class="font-['Space_Grotesk'] text-lg font-bold text-[#152238]">No projects yet</h3>
                <p class="mx-auto mt-2 max-w-sm text-sm text-[#687684]">Paste a YouTube link or upload a video to create your first short.</p>
                <a href="{{ route('projects.create') }}" class="mt-6 inline-flex min-h-11 items-center gap-2 rounded-xl bg-[#4D5CFF] px-5 py-2 text-sm font-bold text-white hover:bg-[#3949E9]"><i class="fa-solid fa-plus" aria-hidden="true"></i>Create a project</a>
            </div>
        @else
            <div class="divide-y divide-[#E8ECF3]">
                @foreach($projects as $project)
                    <article class="flex flex-col justify-between gap-5 px-5 py-5 transition-colors hover:bg-[#FAFBFE] sm:px-6 md:flex-row md:items-center">
                        <div class="flex min-w-0 items-start gap-4">
                            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl {{ $project->source_type === 'youtube' ? 'bg-[#FFF1EE] text-[#E26755]' : 'bg-[#EEF0FF] text-[#4D5CFF]' }}">
                                <i class="{{ $project->source_type === 'youtube' ? 'fa-brands fa-youtube' : 'fa-solid fa-file-video' }} text-lg" aria-hidden="true"></i>
                            </span>
                            <div class="min-w-0">
                                <a href="{{ route('projects.show', $project->id) }}" class="block truncate font-semibold text-[#152238] hover:text-[#4251CE]">{{ $project->title ?: 'Video Project #' . $project->id }}</a>
                                <div class="mt-2 flex flex-wrap items-center gap-x-3 gap-y-1.5 text-xs text-[#687684]">
                                    <span>{{ $project->created_at->diffForHumans() }}</span>
                                    <span>{{ $project->clips_count == 0 ? 'All possible cuts' : $project->clips_count . ' cuts' }}</span>
                                    <span>{{ ucfirst($project->mode ?? 'dialogue') }} mode</span>
                                </div>
                                @if(in_array($project->status, ['pending', 'processing']))
                                    <div class="mt-3 max-w-md">
                                        <div class="mb-1.5 flex items-center justify-between gap-3 text-xs font-medium text-[#4251CE]"><span class="truncate">{{ $project->progress_step }}</span><span>{{ $project->progress_percent }}%</span></div>
                                        <div class="h-1.5 overflow-hidden rounded-full bg-[#E8ECF8]"><div class="h-full rounded-full bg-[#4D5CFF] transition-all duration-500" style="width: {{ $project->progress_percent }}%"></div></div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="flex shrink-0 items-center justify-end gap-2">
                            @if($project->status === 'completed')
                                <span class="rounded-full bg-[#E9F9F0] px-3 py-1.5 text-xs font-bold text-[#208052]">{{ $project->shorts_count }} shorts ready</span>
                            @elseif($project->status === 'failed')
                                <span class="rounded-full bg-[#FFF1EE] px-3 py-1.5 text-xs font-bold text-[#B34A3E]" title="{{ $project->error_message }}">Failed</span>
                            @else
                                <span class="rounded-full bg-[#EEF0FF] px-3 py-1.5 text-xs font-bold text-[#4251CE]">Processing</span>
                            @endif
                            <a href="{{ route('projects.show', $project->id) }}" class="inline-flex min-h-10 items-center rounded-xl border border-[#D8DFFF] px-3 py-2 text-xs font-bold text-[#4251CE] hover:bg-[#EEF0FF]">Open studio</a>
                            <form action="{{ route('projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('Delete this project and its generated shorts?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="flex h-10 w-10 items-center justify-center rounded-xl text-[#8A96A8] hover:bg-[#FFF1EE] hover:text-[#C94F46]" title="Delete project" aria-label="Delete project"><i class="fa-regular fa-trash-can text-xs" aria-hidden="true"></i></button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>
            @if($projects->hasPages())<div class="border-t border-[#E8ECF3] p-4">{{ $projects->links() }}</div>@endif
        @endif
    </section>
</div>

@push('scripts')
<script>
function dashboardPoller() {
    return {
        init() {
            const hasProcessing = {{ $stats['processing'] > 0 ? 'true' : 'false' }};
            if (hasProcessing) {
                setInterval(() => window.location.reload(), 8000);
            }
        }
    }
}
</script>
@endpush
@endsection
