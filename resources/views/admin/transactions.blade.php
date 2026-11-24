@extends('layouts.app')

@section('title', 'Credit ledger • Admin')

@section('content')
<div class="space-y-7">
    <header class="relative overflow-hidden rounded-[1.75rem] border border-[#DDE4F0] bg-white p-6 shadow-[0_16px_50px_rgba(21,34,56,.06)] sm:p-8">
        <div class="absolute -right-12 -top-24 h-64 w-64 rounded-full bg-[#FFF0DC]/80 blur-3xl" aria-hidden="true"></div>
        <div class="relative">
            <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-[#4251CE] hover:underline">Admin overview</a>
            <h1 class="mt-3 font-['Space_Grotesk'] text-3xl font-bold tracking-[-.05em] text-[#152238] sm:text-4xl">Credit ledger</h1>
            <p class="mt-2 text-sm text-[#687684]">Review every credit addition, deduction, and refund with its audit reason.</p>
        </div>
    </header>

    @include('admin.navigation')

    <section class="rounded-2xl border border-[#DDE4F0] bg-white p-4 shadow-[0_10px_30px_rgba(21,34,56,.04)] sm:p-5" aria-label="Filter credit activity">
        <form method="GET" action="{{ route('admin.transactions') }}" class="grid gap-3 md:grid-cols-[minmax(0,1fr)_210px_auto] md:items-end">
            <div>
                <label for="admin-ledger-search" class="mb-1.5 block text-xs font-bold text-[#526078]">Search activity</label>
                <input id="admin-ledger-search" type="search" name="search" value="{{ request('search') }}" placeholder="User, email, or description"
                       class="min-h-11 w-full rounded-xl border border-[#DDE4F0] bg-[#F8FAFF] px-4 text-sm text-[#152238] placeholder:text-[#8A96A8] focus:border-[#4D5CFF] focus:outline-none focus:ring-2 focus:ring-[#DDE2FF]">
            </div>
            <div>
                <label for="admin-ledger-type" class="mb-1.5 block text-xs font-bold text-[#526078]">Transaction type</label>
                <select id="admin-ledger-type" name="type" class="min-h-11 w-full rounded-xl border border-[#DDE4F0] bg-[#F8FAFF] px-4 text-sm text-[#152238] focus:border-[#4D5CFF] focus:outline-none focus:ring-2 focus:ring-[#DDE2FF]">
                    <option value="">All types</option>
                    @foreach(['bonus' => 'Bonus', 'purchase' => 'Purchase', 'usage' => 'Usage', 'manual_credit' => 'Admin credit', 'manual_debit' => 'Admin debit', 'refund' => 'Refund'] as $value => $label)
                        <option value="{{ $value }}" @selected(request('type') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="inline-flex min-h-11 flex-1 items-center justify-center rounded-xl bg-[#4D5CFF] px-5 text-sm font-bold text-white hover:bg-[#3949E9] md:flex-none">Apply filters</button>
                @if(request('search') || request('type'))
                    <a href="{{ route('admin.transactions') }}" class="inline-flex min-h-11 items-center justify-center rounded-xl border border-[#DDE4F0] bg-white px-4 text-sm font-bold text-[#526078] hover:bg-[#F6F7FF]">Reset</a>
                @endif
            </div>
        </form>
    </section>

    <section class="overflow-hidden rounded-2xl border border-[#DDE4F0] bg-white shadow-[0_12px_34px_rgba(21,34,56,.05)]" aria-labelledby="ledger-table-title">
        <div class="flex items-center justify-between border-b border-[#E8ECF3] px-5 py-4 sm:px-6">
            <h2 id="ledger-table-title" class="font-['Space_Grotesk'] text-lg font-bold text-[#152238]">Activity log</h2>
            <span class="text-xs font-semibold text-[#687684]">{{ number_format($transactions->total()) }} results</span>
        </div>
        <div class="overflow-x-auto" role="region" aria-label="Credit activity table" tabindex="0">
            <table class="min-w-[1000px] w-full text-left text-sm text-[#526078]">
                <thead class="bg-[#F8FAFF] text-xs font-bold text-[#687684]">
                    <tr>
                        <th scope="col" class="px-5 py-3.5">Date and time</th>
                        <th scope="col" class="px-5 py-3.5">User</th>
                        <th scope="col" class="px-5 py-3.5">Type</th>
                        <th scope="col" class="px-5 py-3.5">Description</th>
                        <th scope="col" class="px-5 py-3.5">Authorized by</th>
                        <th scope="col" class="px-5 py-3.5 text-right">Credits</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E8ECF3]">
                    @forelse($transactions as $transaction)
                        <tr class="hover:bg-[#F8FAFF]">
                            <td class="whitespace-nowrap px-5 py-4 text-xs text-[#687684]">{{ $transaction->created_at->format('M d, Y · h:i:s A') }}</td>
                            <td class="px-5 py-4">
                                @if($transaction->user)
                                    <a href="{{ route('admin.users.show', $transaction->user->id) }}" class="block max-w-[180px] truncate font-semibold text-[#152238] hover:text-[#4251CE]">{{ $transaction->user->name }}</a>
                                    <span class="block max-w-[180px] truncate text-xs text-[#8792A6]">{{ $transaction->user->email }}</span>
                                @else
                                    <span class="text-[#8792A6]">Deleted user</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex whitespace-nowrap rounded-full border px-2.5 py-1 text-xs font-bold {{ $transaction->amount >= 0 ? 'border-emerald-200 bg-emerald-50 text-[#19734E]' : 'border-rose-200 bg-rose-50 text-[#B7473D]' }}">
                                    {{ match($transaction->type) { 'manual_credit' => 'Admin credit', 'manual_debit' => 'Admin debit', default => ucfirst($transaction->type) } }}
                                </span>
                            </td>
                            <td class="max-w-[300px] px-5 py-4 text-xs text-[#526078]">
                                <span class="line-clamp-2">{{ $transaction->description }}</span>
                                @if($transaction->project_id)
                                    <a href="{{ route('admin.projects.show', $transaction->project_id) }}" class="mt-1 inline-block font-bold text-[#4251CE] hover:underline">Project #{{ $transaction->project_id }}</a>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-xs">{{ $transaction->admin?->name ?? 'System' }}</td>
                            <td class="whitespace-nowrap px-5 py-4 text-right font-['Space_Grotesk'] font-bold {{ $transaction->amount >= 0 ? 'text-[#19734E]' : 'text-[#B7473D]' }}">{{ $transaction->amount > 0 ? '+' : '' }}{{ number_format($transaction->amount) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-12 text-center text-sm text-[#687684]">No credit activity matches these filters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
            <div class="border-t border-[#E8ECF3] px-5 py-4">{{ $transactions->links() }}</div>
        @endif
    </section>
</div>
@endsection
