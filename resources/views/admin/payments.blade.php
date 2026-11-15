@extends('layouts.app')

@section('title', 'Admin - Razorpay Payments & Orders')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-400 transition">Admin Panel</a>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <span class="text-slate-200">Payment Orders</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center border border-blue-500/30">
                    <i class="fa-solid fa-credit-card"></i>
                </span>
                Razorpay Payment Transactions
            </h1>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold px-4 py-2.5 rounded-xl border border-slate-700 transition">
                <i class="fa-solid fa-arrow-left"></i>
                Dashboard
            </a>
            <a href="{{ route('admin.transactions') }}" class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold px-4 py-2.5 rounded-xl border border-slate-700 transition">
                <i class="fa-solid fa-receipt text-amber-400"></i>
                Token Ledger
            </a>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="rounded-2xl bg-slate-900/80 border border-slate-800 overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="bg-slate-950/80 text-xs uppercase text-slate-400 tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Date & Time</th>
                        <th class="px-6 py-4 font-semibold">User</th>
                        <th class="px-6 py-4 font-semibold">Plan</th>
                        <th class="px-6 py-4 font-semibold">Amount</th>
                        <th class="px-6 py-4 font-semibold">Tokens</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold">Order / Payment ID</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($payments as $pay)
                        <tr class="hover:bg-slate-800/40 transition">
                            <td class="px-6 py-4 text-xs text-slate-400 whitespace-nowrap">
                                {{ $pay->created_at->format('M d, Y • h:i:s A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-bold text-white text-xs">{{ $pay->user ? $pay->user->name : 'Deleted User' }}</div>
                                <div class="text-[11px] text-slate-500">{{ $pay->user ? $pay->user->email : '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $pay->plan_id === 'agency' ? 'bg-purple-500/20 text-purple-300 border border-purple-500/30' : ($pay->plan_id === 'pro' ? 'bg-indigo-500/20 text-indigo-300 border border-indigo-500/30' : 'bg-slate-800 text-slate-300') }}">
                                    {{ $pay->plan_id }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-extrabold text-white whitespace-nowrap">
                                {{ $pay->formatted_amount }}
                            </td>
                            <td class="px-6 py-4 font-extrabold text-amber-400 whitespace-nowrap">
                                +{{ number_format($pay->tokens) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($pay->status === 'paid')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                        <i class="fa-solid fa-circle-check text-[10px]"></i> Paid
                                    </span>
                                @elseif($pay->status === 'created')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                        <i class="fa-solid fa-clock text-[10px]"></i> Pending
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-500/20 text-rose-300 border border-rose-500/30">
                                        <i class="fa-solid fa-circle-xmark text-[10px]"></i> Failed
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs font-mono text-slate-400 whitespace-nowrap">
                                <div><span class="text-slate-500">Ord:</span> {{ $pay->razorpay_order_id }}</div>
                                @if($pay->razorpay_payment_id)
                                    <div><span class="text-emerald-500">Pay:</span> {{ $pay->razorpay_payment_id }}</div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500 text-sm">
                                No Razorpay payment orders recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payments->hasPages())
            <div class="px-6 py-4 border-t border-slate-800">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
