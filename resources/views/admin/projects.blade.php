@extends('layouts.app')

@section('title', 'Projects • Admin')

@section('content')
<div class="space-y-7">
    <header class="relative overflow-hidden rounded-[1.75rem] border border-[#DDE4F0] bg-white p-6 shadow-[0_16px_50px_rgba(21,34,56,.06)] sm:p-8">
        <div class="absolute -right-12 -top-24 h-64 w-64 rounded-full bg-[#FFE9DE]/80 blur-3xl" aria-hidden="true"></div>
        <div class="relative">
            <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-[#4251CE] hover:underline">Admin overview</a>
            <h1 class="mt-3 font-['Space_Grotesk'] text-3xl font-bold tracking-[-.05em] text-[#152238] sm:text-4xl">Video projects</h1>
            <p class="mt-2 text-sm text-[#687684]">Track every creator project from upload through finished shorts.</p>
        </div>
    </header>

    @include('admin.navigation')

    <section class="rounded-2xl border border-[#DDE4F0] bg-white p-4 shadow-[0_10px_30px_rgba(21,34,56,.04)] sm:p-5" aria-label="Filter projects">
        <form method="GET" action="{{ route('admin.projects') }}" class="grid gap-3 md:grid-cols-[minmax(0,1fr)_170px_170px_auto] md:items-end">
            <div>
                <label for="admin-project-search" class="mb-1.5 block text-xs font-bold text-[#526078]">Search projects</label>
                <input id="admin-project-search" type="search" name="search" value="{{ request('search') }}" placeholder="Title, creator, or email"
                       class="min-h-11 w-full rounded-xl border border-[#DDE4F0] bg-[#F8FAFF] px-4 text-sm text-[#152238] placeholder:text-[#8A96A8] focus:border-[#4D5CFF] focus:outline-none focus:ring-2 focus:ring-[#DDE2FF]">
            </div>
            <div>
                <label for="admin-project-status" class="mb-1.5 block text-xs font-bold text-[#526078]">Status</label>
                <select id="admin-project-status" name="status" class="min-h-11 w-full rounded-xl border border-[#DDE4F0] bg-[#F8FAFF] px-4 text-sm text-[#152238] focus:border-[#4D5CFF] focus:outline-none focus:ring-2 focus:ring-[#DDE2FF]">
                    <option value="">All statuses</option>
                    @foreach(['pending' => 'Pending', 'processing' => 'Processing', 'completed' => 'Completed', 'failed' => 'Failed'] as $value => $label)
                        <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="admin-project-mode" class="mb-1.5 block text-xs font-bold text-[#526078]">Mode</label>
                <select id="admin-project-mode" name="mode" class="min-h-11 w-full rounded-xl border border-[#DDE4F0] bg-[#F8FAFF] px-4 text-sm text-[#152238] focus:border-[#4D5CFF] focus:outline-none focus:ring-2 focus:ring-[#DDE2FF]">
                    <option value="">All modes</option>
                    <option value="song" @selected(request('mode') === 'song')>Song</option>
                    <option value="dialogue" @selected(request('mode') === 'dialogue')>Dialogue</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="inline-flex min-h-11 flex-1 items-center justify-center rounded-xl bg-[#4D5CFF] px-5 text-sm font-bold text-white hover:bg-[#3949E9] md:flex-none">Apply filters</button>
                @if(request('search') || request('status') || request('mode'))
                    <a href="{{ route('admin.projects') }}" class="inline-flex min-h-11 items-center justify-center rounded-xl border border-[#DDE4F0] bg-white px-4 text-sm font-bold text-[#526078] hover:bg-[#F6F7FF]">Reset</a>
                @endif
            </div>
        </form>
    </section>

    <section class="overflow-hidden rounded-2xl border border-[#DDE4F0] bg-white shadow-[0_12px_34px_rgba(21,34,56,.05)]" aria-labelledby="projects-table-title">
        <div class="flex items-center justify-between border-b border-[#E8ECF3] px-5 py-4 sm:px-6">
            <h2 id="projects-table-title" class="font-['Space_Grotesk'] text-lg font-bold text-[#152238]">All projects</h2>
            <span class="text-xs font-semibold text-[#687684]">{{ number_format($projects->total()) }} results</span>
        </div>
        <div class="overflow-x-auto" role="region" aria-label="Projects table" tabindex="0">
            <table class="min-w-[1080px] w-full text-left text-sm text-[#526078]">
                <thead class="bg-[#F8FAFF] text-xs font-bold text-[#687684]">
                    <tr>
                        <th scope="col" class="px-5 py-3.5">Project</th>
                        <th scope="col" class="px-5 py-3.5">Creator</th>
                        <th scope="col" class="px-5 py-3.5">Mode</th>
                        <th scope="col" class="px-5 py-3.5">Status</th>
                        <th scope="col" class="px-5 py-3.5">Shorts</th>
                        <th scope="col" class="px-5 py-3.5">Credits used</th>
                        <th scope="col" class="px-5 py-3.5">Created</th>
                        <th scope="col" class="px-5 py-3.5 text-right">Details</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E8ECF3]">
                    @forelse($projects as $project)
                        <tr class="hover:bg-[#F8FAFF]">
                            <td class="px-5 py-4">
                                <a href="{{ route('admin.projects.show', $project->id) }}" class="block max-w-[220px] truncate font-bold text-[#152238] hover:text-[#4251CE]">{{ $project->title }}</a>
                                <span class="text-xs capitalize text-[#8792A6]">{{ $project->source_type }}</span>
                            </td>
                            <td class="px-5 py-4">
                                @if($project->user)
                                    <a href="{{ route('admin.users.show', $project->user->id) }}" class="block max-w-[180px] truncate font-semibold text-[#152238] hover:text-[#4251CE]">{{ $project->user->name }}</a>
                                    <span class="block max-w-[180px] truncate text-xs text-[#8792A6]">{{ $project->user->email }}</span>
                                @else
                                    <span class="text-[#8792A6]">Deleted user</span>
                                @endif
                            </td>
                            <td class="px-5 py-4"><span class="inline-flex rounded-full border px-2.5 py-1 text-xs font-bold {{ $project->mode === 'song' ? 'border-[#FFD2C5] bg-[#FFF2ED] text-[#BB5B46]' : 'border-[#D8DFFF] bg-[#F1F3FF] text-[#4251CE]' }}">{{ $project->mode === 'song' ? 'Song' : 'Dialogue' }}</span></td>
                            <td class="px-5 py-4">
                                <span class="inline-flex rounded-full border px-2.5 py-1 text-xs font-bold {{ $project->status === 'completed' ? 'border-emerald-200 bg-emerald-50 text-[#19734E]' : ($project->status === 'failed' ? 'border-rose-200 bg-rose-50 text-[#B7473D]' : ($project->status === 'processing' ? 'border-[#D8DFFF] bg-[#F1F3FF] text-[#4251CE]' : 'border-amber-200 bg-amber-50 text-[#8D5D14]')) }}">
                                    {{ ucfirst($project->status) }}@if($project->status === 'processing') · {{ $project->progress_percent }}%@endif
                                </span>
                            </td>
                            <td class="px-5 py-4 font-semibold text-[#152238]">{{ $project->shorts->count() }} <span class="font-normal text-[#8792A6]">/ {{ $project->clips_count }}</span></td>
                            <td class="px-5 py-4 font-semibold text-[#152238]">{{ number_format($project->tokens_used) }}</td>
                            <td class="whitespace-nowrap px-5 py-4 text-xs text-[#687684]">{{ $project->created_at->format('M d, Y · h:i A') }}</td>
                            <td class="px-5 py-4 text-right"><a href="{{ route('admin.projects.show', $project->id) }}" class="inline-flex min-h-10 items-center rounded-xl border border-[#DDE4F0] px-3 text-xs font-bold text-[#4251CE] hover:bg-[#F1F3FF]">View project</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-6 py-12 text-center text-sm text-[#687684]">No projects match these filters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($projects->hasPages())
            <div class="border-t border-[#E8ECF3] px-5 py-4">{{ $projects->links() }}</div>
        @endif
    </section>
</div>
@endsection
