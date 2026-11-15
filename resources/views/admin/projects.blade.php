@extends('layouts.app')

@section('title', 'Admin - All Video Projects')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-400 transition">Admin Panel</a>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <span class="text-slate-200">All Video Projects</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-pink-500/20 text-pink-400 flex items-center justify-center border border-pink-500/30">
                    <i class="fa-solid fa-video"></i>
                </span>
                Global Video Projects
            </h1>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold px-4 py-2.5 rounded-xl border border-slate-700 transition">
                <i class="fa-solid fa-arrow-left"></i>
                Dashboard
            </a>
            <a href="{{ route('admin.users') }}" class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold px-4 py-2.5 rounded-xl border border-slate-700 transition">
                <i class="fa-solid fa-users text-indigo-400"></i>
                Users
            </a>
        </div>
    </div>

    <!-- Projects Table -->
    <div class="rounded-2xl bg-slate-900/80 border border-slate-800 overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="bg-slate-950/80 text-xs uppercase text-slate-400 tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Project Title</th>
                        <th class="px-6 py-4 font-semibold">Creator</th>
                        <th class="px-6 py-4 font-semibold">Mode</th>
                        <th class="px-6 py-4 font-semibold">Tokens Used</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold">Clips</th>
                        <th class="px-6 py-4 font-semibold">Created Date</th>
                        <th class="px-6 py-4 font-semibold text-right">View</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($projects as $p)
                        <tr class="hover:bg-slate-800/40 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-bold text-white max-w-xs truncate">{{ $p->title }}</div>
                                <div class="text-xs text-slate-500 uppercase">{{ $p->source_type }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-xs font-semibold text-slate-200">{{ $p->user ? $p->user->name : 'Unknown' }}</div>
                                <div class="text-[11px] text-slate-500">{{ $p->user ? $p->user->email : '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($p->mode === 'song')
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-pink-500/20 text-pink-300 border border-pink-500/30">
                                        <i class="fa-solid fa-music mr-1"></i> Song Mode
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                                        <i class="fa-solid fa-microphone mr-1"></i> Dialogue Mode
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-black text-amber-400 text-xs">{{ $p->tokens_used }} Tokens</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($p->status === 'completed')
                                    <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Completed</span>
                                @elseif($p->status === 'processing')
                                    <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 animate-pulse">Processing ({{ $p->progress_percent }}%)</span>
                                @elseif($p->status === 'failed')
                                    <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-rose-500/20 text-rose-300 border border-rose-500/30">Failed</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-800 text-slate-400">{{ ucfirst($p->status) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs font-semibold">
                                {{ $p->shorts->count() }} / {{ $p->clips_count }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-400">
                                {{ $p->created_at->format('M d, Y • h:i A') }}
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <a href="{{ route('projects.show', $p->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold transition border border-slate-700">
                                    <span>Studio</span>
                                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-500 text-sm">
                                No video projects created yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($projects->hasPages())
            <div class="px-6 py-4 border-t border-slate-800">
                {{ $projects->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
