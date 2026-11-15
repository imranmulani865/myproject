@extends('layouts.app')

@section('title', 'Admin Master Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <a href="{{ route('dashboard') }}" class="hover:text-indigo-400 transition">User Dashboard</a>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <span class="text-purple-300 font-semibold">Admin Panel</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center border border-purple-500/30">
                    <i class="fa-solid fa-shield-halved"></i>
                </span>
                Admin Master Dashboard
            </h1>
        </div>

        <!-- Navigation Tabs -->
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.users') }}" class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold px-4 py-2.5 rounded-xl border border-slate-700 transition">
                <i class="fa-solid fa-users text-indigo-400"></i>
                Manage Users
            </a>
            <a href="{{ route('admin.projects') }}" class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold px-4 py-2.5 rounded-xl border border-slate-700 transition">
                <i class="fa-solid fa-video text-pink-400"></i>
                All Projects
            </a>
            <a href="{{ route('admin.transactions') }}" class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold px-4 py-2.5 rounded-xl border border-slate-700 transition">
                <i class="fa-solid fa-receipt text-amber-400"></i>
                Token Ledger
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="mb-6 p-4 rounded-2xl bg-emerald-950/80 border border-emerald-800/80 text-emerald-200 text-xs sm:text-sm flex items-center gap-3 shadow-lg">
            <i class="fa-solid fa-circle-check text-emerald-400 text-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 rounded-2xl bg-rose-950/80 border border-rose-800/80 text-rose-200 text-xs sm:text-sm flex items-center gap-3 shadow-lg">
            <i class="fa-solid fa-circle-xmark text-rose-400 text-lg"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Metric Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <!-- Total Users -->
        <div class="p-6 rounded-2xl bg-slate-900/90 border border-slate-800 shadow-xl">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs uppercase font-semibold text-slate-400 tracking-wider">Total Users</span>
                <span class="w-8 h-8 rounded-lg bg-indigo-500/20 text-indigo-400 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-users"></i>
                </span>
            </div>
            <div class="text-3xl font-extrabold text-white mb-1">{{ number_format($totalUsers) }}</div>
            <div class="text-xs text-emerald-400 flex items-center gap-1.5">
                <i class="fa-solid fa-circle text-[8px]"></i>
                <span>{{ $activeUsers }} Active Accounts</span>
            </div>
        </div>

        <!-- Total Circulating Tokens -->
        <div class="p-6 rounded-2xl bg-slate-900/90 border border-slate-800 shadow-xl">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs uppercase font-semibold text-amber-400 tracking-wider">Circulating Tokens</span>
                <span class="w-8 h-8 rounded-lg bg-amber-500/20 text-amber-400 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-coins"></i>
                </span>
            </div>
            <div class="text-3xl font-extrabold text-amber-300 mb-1">{{ number_format($totalTokens) }}</div>
            <div class="text-xs text-slate-400">
                Total tokens held across all wallets
            </div>
        </div>

        <!-- Video Projects & Shorts -->
        <div class="p-6 rounded-2xl bg-slate-900/90 border border-slate-800 shadow-xl">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs uppercase font-semibold text-slate-400 tracking-wider">Generated Shorts</span>
                <span class="w-8 h-8 rounded-lg bg-pink-500/20 text-pink-400 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-film"></i>
                </span>
            </div>
            <div class="text-3xl font-extrabold text-white mb-1">{{ number_format($totalShorts) }}</div>
            <div class="text-xs text-slate-400">
                From {{ number_format($totalProjects) }} submitted projects
            </div>
        </div>

        <!-- Queue Status -->
        <div class="p-6 rounded-2xl bg-slate-900/90 border border-slate-800 shadow-xl">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs uppercase font-semibold text-slate-400 tracking-wider">Queue Worker</span>
                <span class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-server"></i>
                </span>
            </div>
            <div class="text-3xl font-extrabold text-white mb-1">{{ $queueJobsCount }}</div>
            <div class="text-xs text-slate-400 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full {{ $queueJobsCount > 0 ? 'bg-amber-400 animate-ping' : 'bg-emerald-400' }}"></span>
                <span>{{ $queueJobsCount > 0 ? 'Jobs in processing queue' : 'Queue idle & healthy' }}</span>
            </div>
        </div>
    </div>

    <!-- Content Sections: Recent Users & Recent Transactions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Users Table -->
        <div class="rounded-2xl bg-slate-900/80 border border-slate-800 overflow-hidden shadow-xl">
            <div class="px-6 py-4 border-b border-slate-800 flex items-center justify-between">
                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-user-plus text-indigo-400"></i>
                    Recent Registered Users
                </h3>
                <a href="{{ route('admin.users') }}" class="text-xs text-indigo-400 hover:text-indigo-300 font-semibold">
                    View All Users &rarr;
                </a>
            </div>
            <div class="divide-y divide-slate-800/80">
                @foreach($recentUsers as $u)
                    <div class="p-4 px-6 flex items-center justify-between hover:bg-slate-800/30 transition">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 flex items-center justify-center text-xs font-bold text-white">
                                {{ strtoupper(substr($u->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-white flex items-center gap-2">
                                    <span>{{ $u->name }}</span>
                                    @if($u->isAdmin())
                                        <span class="px-1.5 py-0.2 rounded text-[10px] font-bold bg-purple-500/20 text-purple-300 border border-purple-500/30">Admin</span>
                                    @endif
                                </div>
                                <div class="text-xs text-slate-400 truncate max-w-xs">{{ $u->email }}</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs font-extrabold text-amber-400">
                                {{ number_format($u->tokens) }} Tokens
                            </div>
                            <div class="text-[11px] text-slate-500 capitalize">
                                Status: <strong class="{{ $u->status === 'active' ? 'text-emerald-400' : 'text-rose-400' }}">{{ $u->status }}</strong>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Token Transactions -->
        <div class="rounded-2xl bg-slate-900/80 border border-slate-800 overflow-hidden shadow-xl">
            <div class="px-6 py-4 border-b border-slate-800 flex items-center justify-between">
                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-amber-400"></i>
                    Recent Token Ledger Activity
                </h3>
                <a href="{{ route('admin.transactions') }}" class="text-xs text-indigo-400 hover:text-indigo-300 font-semibold">
                    View Full Ledger &rarr;
                </a>
            </div>
            <div class="divide-y divide-slate-800/80">
                @forelse($recentTransactions as $tx)
                    <div class="p-4 px-6 flex items-center justify-between hover:bg-slate-800/30 transition text-xs">
                        <div>
                            <div class="font-semibold text-slate-200">
                                {{ $tx->user ? $tx->user->name : 'Unknown User' }}
                            </div>
                            <div class="text-slate-400 text-[11px] truncate max-w-xs">
                                {{ $tx->description }}
                            </div>
                        </div>
                        <div class="text-right whitespace-nowrap">
                            <div class="font-black text-sm {{ $tx->amount > 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                                {{ $tx->amount > 0 ? '+'.$tx->amount : $tx->amount }}
                            </div>
                            <div class="text-[10px] text-slate-500">
                                {{ $tx->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-slate-500 text-xs">
                        No transactions recorded yet.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
