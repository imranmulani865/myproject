@extends('layouts.app')

@section('title', 'Admin - Token Audit Ledger')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-400 transition">Admin Panel</a>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <span class="text-slate-200">Token Audit Ledger</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center border border-amber-500/30">
                    <i class="fa-solid fa-receipt"></i>
                </span>
                Global Token Audit Ledger
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

    <!-- Ledger Table -->
    <div class="rounded-2xl bg-slate-900/80 border border-slate-800 overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="bg-slate-950/80 text-xs uppercase text-slate-400 tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Timestamp</th>
                        <th class="px-6 py-4 font-semibold">User</th>
                        <th class="px-6 py-4 font-semibold">Type</th>
                        <th class="px-6 py-4 font-semibold">Description / Audit Reason</th>
                        <th class="px-6 py-4 font-semibold">Authorized By</th>
                        <th class="px-6 py-4 font-semibold text-right">Token Flow</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($transactions as $tx)
                        <tr class="hover:bg-slate-800/40 transition">
                            <td class="px-6 py-4 text-xs text-slate-400 whitespace-nowrap">
                                {{ $tx->created_at->format('M d, Y • h:i:s A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-bold text-white text-xs">{{ $tx->user ? $tx->user->name : 'Deleted User' }}</div>
                                <div class="text-[11px] text-slate-500">{{ $tx->user ? $tx->user->email : '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($tx->type === 'bonus')
                                    <span class="px-2.5 py-0.5 rounded text-[11px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Bonus</span>
                                @elseif($tx->type === 'usage')
                                    <span class="px-2.5 py-0.5 rounded text-[11px] font-bold bg-rose-500/20 text-rose-300 border border-rose-500/30">Usage</span>
                                @elseif($tx->type === 'manual_credit')
                                    <span class="px-2.5 py-0.5 rounded text-[11px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">Admin Credit</span>
                                @elseif($tx->type === 'manual_debit')
                                    <span class="px-2.5 py-0.5 rounded text-[11px] font-bold bg-purple-500/20 text-purple-300 border border-purple-500/30">Admin Debit</span>
                                @elseif($tx->type === 'refund')
                                    <span class="px-2.5 py-0.5 rounded text-[11px] font-bold bg-blue-500/20 text-blue-300 border border-blue-500/30">Refund</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded text-[11px] font-bold bg-slate-800 text-slate-300">{{ ucfirst($tx->type) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-200">
                                {{ $tx->description }}
                                @if($tx->project_id)
                                    <a href="{{ route('projects.show', $tx->project_id) }}" class="text-indigo-400 hover:underline ml-1">
                                        (Project #{{ $tx->project_id }})
                                    </a>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-400 whitespace-nowrap">
                                @if($tx->admin)
                                    <span class="text-purple-300 font-semibold">{{ $tx->admin->name }}</span>
                                @else
                                    <span class="text-slate-600">System (Auto)</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                @if($tx->amount > 0)
                                    <span class="text-emerald-400 font-black text-sm">+{{ $tx->amount }}</span>
                                @else
                                    <span class="text-rose-400 font-black text-sm">{{ $tx->amount }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500 text-sm">
                                No token transactions recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="px-6 py-4 border-t border-slate-800">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
