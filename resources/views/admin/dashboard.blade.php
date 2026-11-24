@extends('layouts.app')

@section('title', 'Admin overview • AI Shorts Creator')

@section('content')
<div class="space-y-7">
    <header class="relative overflow-hidden rounded-[1.75rem] border border-[#DDE4F0] bg-white p-6 shadow-[0_16px_50px_rgba(21,34,56,.06)] sm:p-8">
        <div class="absolute -right-12 -top-24 h-64 w-64 rounded-full bg-[#DDE2FF]/70 blur-3xl" aria-hidden="true"></div>
        <div class="relative flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
            <div>
                <p class="mb-2 text-xs font-bold text-[#4D5CFF]">ADMIN WORKSPACE</p>
                <h1 class="font-['Space_Grotesk'] text-3xl font-bold tracking-[-.05em] text-[#152238] sm:text-4xl">Studio overview</h1>
                <p class="mt-2 max-w-xl text-sm text-[#687684]">Users, projects, credits, and payments in one clear view.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl border border-[#DDE4F0] bg-white px-4 py-2 text-sm font-bold text-[#4251CE] hover:bg-[#F1F3FF]">
                <i class="fa-solid fa-arrow-left text-xs" aria-hidden="true"></i>Creator dashboard
            </a>
        </div>
    </header>

    @include('admin.navigation')

    <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4" aria-label="Platform totals">
        <div class="rounded-2xl border border-[#DDE4F0] bg-white p-5 shadow-[0_10px_30px_rgba(21,34,56,.04)]">
            <div class="flex items-center justify-between text-xs font-semibold text-[#687684]"><span>Users</span><i class="fa-solid fa-users text-[#4D5CFF]" aria-hidden="true"></i></div>
            <p class="mt-4 font-['Space_Grotesk'] text-3xl font-bold text-[#152238]">{{ number_format($totalUsers) }}</p>
            <p class="mt-1 text-xs text-[#687684]">{{ number_format($activeUsers) }} active {{ $activeUsers === 1 ? 'account' : 'accounts' }}</p>
        </div>
        <div class="rounded-2xl border border-[#DDE4F0] bg-white p-5 shadow-[0_10px_30px_rgba(21,34,56,.04)]">
            <div class="flex items-center justify-between text-xs font-semibold text-[#687684]"><span>Generated shorts</span><i class="fa-solid fa-film text-[#FF735B]" aria-hidden="true"></i></div>
            <p class="mt-4 font-['Space_Grotesk'] text-3xl font-bold text-[#152238]">{{ number_format($totalShorts) }}</p>
            <p class="mt-1 text-xs text-[#687684]">From {{ number_format($totalProjects) }} projects</p>
        </div>
        <div class="rounded-2xl border border-[#DDE4F0] bg-white p-5 shadow-[0_10px_30px_rgba(21,34,56,.04)]">
            <div class="flex items-center justify-between text-xs font-semibold text-[#687684]"><span>Credits in wallets</span><i class="fa-solid fa-coins text-[#C47A21]" aria-hidden="true"></i></div>
            <p class="mt-4 font-['Space_Grotesk'] text-3xl font-bold text-[#152238]">{{ number_format($totalTokens) }}</p>
            <p class="mt-1 text-xs text-[#687684]">Across all users</p>
        </div>
        <div class="rounded-2xl border border-[#DDE4F0] bg-white p-5 shadow-[0_10px_30px_rgba(21,34,56,.04)]">
            <div class="flex items-center justify-between text-xs font-semibold text-[#687684]"><span>Paid revenue</span><i class="fa-solid fa-credit-card text-[#4D5CFF]" aria-hidden="true"></i></div>
            <p class="mt-4 font-['Space_Grotesk'] text-3xl font-bold text-[#152238]">₹{{ number_format($totalRevenuePaise / 100, 2) }}</p>
            <p class="mt-1 text-xs text-[#687684]">Total revenue received</p>
        </div>
    </section>

    <section class="grid grid-cols-2 gap-3 md:grid-cols-3 xl:grid-cols-6" aria-label="Project and queue status">
        <div class="rounded-2xl border border-[#DDE4F0] bg-[#F8FAFF] p-4"><p class="text-xs font-semibold text-[#687684]">Pending</p><p class="mt-2 font-['Space_Grotesk'] text-2xl font-bold text-[#152238]">{{ number_format($pendingProjects) }}</p></div>
        <div class="rounded-2xl border border-[#DDE4F0] bg-[#F8FAFF] p-4"><p class="text-xs font-semibold text-[#687684]">Processing</p><p class="mt-2 font-['Space_Grotesk'] text-2xl font-bold text-[#4251CE]">{{ number_format($processingProjects) }}</p></div>
        <div class="rounded-2xl border border-[#DDE4F0] bg-[#F8FAFF] p-4"><p class="text-xs font-semibold text-[#687684]">Completed</p><p class="mt-2 font-['Space_Grotesk'] text-2xl font-bold text-[#19734E]">{{ number_format($completedProjects) }}</p></div>
        <div class="rounded-2xl border border-[#DDE4F0] bg-[#F8FAFF] p-4"><p class="text-xs font-semibold text-[#687684]">Failed projects</p><p class="mt-2 font-['Space_Grotesk'] text-2xl font-bold text-[#B7473D]">{{ number_format($failedProjects) }}</p></div>
        <div class="rounded-2xl border border-[#DDE4F0] bg-[#F8FAFF] p-4"><p class="text-xs font-semibold text-[#687684]">Queued jobs</p><p class="mt-2 font-['Space_Grotesk'] text-2xl font-bold text-[#152238]">{{ $queueMetricsAvailable ? number_format($queueJobsCount) : '—' }}</p></div>
        <div class="rounded-2xl border border-[#DDE4F0] bg-[#F8FAFF] p-4"><p class="text-xs font-semibold text-[#687684]">Failed jobs</p><p class="mt-2 font-['Space_Grotesk'] text-2xl font-bold text-[#B7473D]">{{ $queueMetricsAvailable ? number_format($failedJobsCount) : '—' }}</p></div>
    </section>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <section class="overflow-hidden rounded-2xl border border-[#DDE4F0] bg-white shadow-[0_12px_34px_rgba(21,34,56,.05)]" aria-labelledby="recent-users-title">
            <div class="flex items-center justify-between gap-3 border-b border-[#E8ECF3] px-5 py-4 sm:px-6">
                <h2 id="recent-users-title" class="font-['Space_Grotesk'] text-lg font-bold text-[#152238]">Recent users</h2>
                <a href="{{ route('admin.users') }}" class="text-xs font-bold text-[#4251CE] hover:underline">View all users</a>
            </div>
            <div class="divide-y divide-[#E8ECF3]">
                @forelse($recentUsers as $u)
                    <div class="flex items-center justify-between gap-3 px-5 py-4 sm:px-6">
                        <div class="flex min-w-0 items-center gap-3">
                            <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-[#E8EAFF] font-bold text-[#4251CE]">{{ strtoupper(substr($u->name, 0, 1)) }}</span>
                            <div class="min-w-0">
                                <a href="{{ route('admin.users.show', $u->id) }}" class="block truncate text-sm font-bold text-[#152238] hover:text-[#4251CE]">{{ $u->name }}</a>
                                <p class="truncate text-xs text-[#687684]">{{ $u->email }}</p>
                            </div>
                        </div>
                        <div class="shrink-0 text-right">
                            <p class="text-sm font-bold text-[#152238]">{{ number_format($u->tokens) }} <span class="text-xs font-medium text-[#687684]">credits</span></p>
                            <p class="text-xs capitalize {{ $u->status === 'active' ? 'text-[#19734E]' : 'text-[#B7473D]' }}">{{ $u->status }}</p>
                        </div>
                    </div>
                @empty
                    <p class="px-6 py-9 text-sm text-[#687684]">No users have registered yet.</p>
                @endforelse
            </div>
        </section>

        <section class="overflow-hidden rounded-2xl border border-[#DDE4F0] bg-white shadow-[0_12px_34px_rgba(21,34,56,.05)]" aria-labelledby="recent-ledger-title">
            <div class="flex items-center justify-between gap-3 border-b border-[#E8ECF3] px-5 py-4 sm:px-6">
                <h2 id="recent-ledger-title" class="font-['Space_Grotesk'] text-lg font-bold text-[#152238]">Credit activity</h2>
                <a href="{{ route('admin.transactions') }}" class="text-xs font-bold text-[#4251CE] hover:underline">View full ledger</a>
            </div>
            <div class="divide-y divide-[#E8ECF3]">
                @forelse($recentTransactions as $tx)
                    <div class="flex items-center justify-between gap-3 px-5 py-4 sm:px-6">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-bold text-[#152238]">{{ $tx->user ? $tx->user->name : 'Deleted user' }}</p>
                            <p class="truncate text-xs text-[#687684]">{{ $tx->description }}</p>
                        </div>
                        <div class="shrink-0 text-right">
                            <p class="text-sm font-bold {{ $tx->amount > 0 ? 'text-[#19734E]' : 'text-[#B7473D]' }}">{{ $tx->amount > 0 ? '+' : '' }}{{ number_format($tx->amount) }}</p>
                            <p class="text-xs text-[#8792A6]">{{ $tx->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p class="px-6 py-9 text-sm text-[#687684]">No credit transactions yet.</p>
                @endforelse
            </div>
        </section>
    </div>

    <section class="overflow-hidden rounded-2xl border border-[#DDE4F0] bg-white shadow-[0_12px_34px_rgba(21,34,56,.05)]" aria-labelledby="recent-projects-title">
        <div class="flex items-center justify-between gap-3 border-b border-[#E8ECF3] px-5 py-4 sm:px-6">
            <h2 id="recent-projects-title" class="font-['Space_Grotesk'] text-lg font-bold text-[#152238]">Latest projects</h2>
            <a href="{{ route('admin.projects') }}" class="text-xs font-bold text-[#4251CE] hover:underline">View all projects</a>
        </div>
        <div class="divide-y divide-[#E8ECF3]">
            @forelse($recentProjects as $project)
                <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 sm:px-6">
                    <div class="min-w-0">
                        <a href="{{ route('admin.projects.show', $project->id) }}" class="block truncate text-sm font-bold text-[#152238] hover:text-[#4251CE]">{{ $project->title ?: 'Untitled video' }}</a>
                        <p class="text-xs text-[#687684]">{{ $project->user?->name ?? 'Deleted user' }} · {{ $project->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="rounded-full border px-3 py-1 text-xs font-bold capitalize {{ $project->status === 'completed' ? 'border-emerald-200 bg-emerald-50 text-[#19734E]' : ($project->status === 'failed' ? 'border-rose-200 bg-rose-50 text-[#B7473D]' : 'border-[#D8DFFF] bg-[#F1F3FF] text-[#4251CE]') }}">{{ $project->status }}</span>
                </div>
            @empty
                <p class="px-6 py-9 text-sm text-[#687684]">No projects have been submitted yet.</p>
            @endforelse
        </div>
    </section>
</div>
@endsection
