@extends('layouts.app')

@section('title', 'Token Wallet & Transactions')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ rechargeModal: false }">
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <a href="{{ route('dashboard') }}" class="hover:text-indigo-400 transition">Dashboard</a>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <span class="text-slate-200">Token Wallet</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center border border-amber-500/30">
                    <i class="fa-solid fa-coins"></i>
                </span>
                Token Wallet & Credits
            </h1>
        </div>

        <div class="flex items-center gap-3">
            <button @click="rechargeModal = true" class="inline-flex items-center gap-2 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white font-semibold text-sm px-5 py-2.5 rounded-xl shadow-lg shadow-amber-500/20 transition">
                <i class="fa-solid fa-plus-circle"></i>
                Buy / Recharge Tokens
            </button>
            <a href="{{ route('projects.create') }}" class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-sm font-semibold px-4 py-2.5 rounded-xl border border-slate-700 transition">
                <i class="fa-solid fa-wand-magic-sparkles"></i>
                Create Short
            </a>
        </div>
    </div>

    <!-- Balance & Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Main Wallet Card -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-500/20 via-slate-900 to-slate-900 border border-amber-500/30 p-6 shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs uppercase tracking-wider font-semibold text-amber-300">Available Balance</span>
                <span class="w-8 h-8 rounded-lg bg-amber-500/20 text-amber-400 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-wallet"></i>
                </span>
            </div>
            <div class="text-4xl font-extrabold text-white mb-2 flex items-baseline gap-2">
                {{ number_format($user->tokens) }}
                <span class="text-sm font-medium text-amber-400">Tokens</span>
            </div>
            <p class="text-xs text-slate-400">
                Enough to generate ~<strong class="text-white">{{ floor($user->tokens / 10) }}</strong> Dialogue Shorts or ~<strong class="text-white">{{ floor($user->tokens / 15) }}</strong> Music Shorts.
            </p>
        </div>

        <!-- Token Consumption Rates -->
        <div class="rounded-2xl bg-slate-900/90 border border-slate-800 p-6 flex flex-col justify-between">
            <div>
                <span class="text-xs uppercase tracking-wider font-semibold text-slate-400">Token Rates</span>
                <div class="mt-3 space-y-2">
                    <div class="flex items-center justify-between text-xs py-1.5 border-b border-slate-800/80">
                        <span class="text-slate-300 flex items-center gap-2">
                            <i class="fa-solid fa-microphone text-indigo-400"></i>
                            Dialogue / Podcast Mode
                        </span>
                        <span class="font-bold text-slate-200">10 Tokens / short</span>
                    </div>
                    <div class="flex items-center justify-between text-xs py-1.5">
                        <span class="text-slate-300 flex items-center gap-2">
                            <i class="fa-solid fa-music text-pink-400"></i>
                            Song / Mukhda Mode
                        </span>
                        <span class="font-bold text-slate-200">15 Tokens / short</span>
                    </div>
                </div>
            </div>
            <div class="text-[11px] text-slate-500 mt-2">
                Tokens automatically deducted on project start; refunded if processing fails.
            </div>
        </div>

        <!-- Account Tier Card -->
        <div class="rounded-2xl bg-slate-900/90 border border-slate-800 p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs uppercase tracking-wider font-semibold text-slate-400">Account Role</span>
                    <span class="px-2 py-0.5 rounded text-[11px] font-semibold {{ $user->isAdmin() ? 'bg-purple-500/20 text-purple-300 border border-purple-500/30' : 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' }}">
                        {{ strtoupper($user->role) }}
                    </span>
                </div>
                <div class="text-lg font-bold text-white mb-1">{{ $user->name }}</div>
                <div class="text-xs text-slate-400 truncate">{{ $user->email }}</div>
            </div>
            <div class="text-[11px] text-slate-400 mt-4 flex items-center gap-1.5">
                <i class="fa-solid fa-circle-check text-emerald-400"></i>
                Status: <span class="text-emerald-400 font-semibold capitalize">{{ $user->status }}</span>
            </div>
        </div>
    </div>

    <!-- Pricing Packs Grid -->
    <div class="mb-10">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-tag text-indigo-400"></i>
                    Token Recharge Packs
                </h2>
                <p class="text-xs text-slate-400">Select a pack to buy tokens via instant UPI / manual recharge.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Starter -->
            <div class="rounded-2xl bg-slate-900/80 border border-slate-800 hover:border-slate-700 p-6 transition flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-800 text-slate-300">Starter</span>
                        <span class="text-2xl font-black text-white">₹299</span>
                    </div>
                    <div class="text-2xl font-extrabold text-amber-400 mb-1">100 Tokens</div>
                    <p class="text-xs text-slate-400 mb-4">Great for test-running your next 10-15 viral reels.</p>
                    <ul class="text-xs text-slate-300 space-y-2 mb-6">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> 10 Dialogue Shorts or ~7 Song Shorts</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> 1080x1920 HD rendering</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> Auto subscribe CTA overlay</li>
                    </ul>
                </div>
                <button @click="rechargeModal = true" class="w-full py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold transition border border-slate-700">
                    Recharge ₹299
                </button>
            </div>

            <!-- Pro Creator (Popular) -->
            <div class="relative rounded-2xl bg-gradient-to-b from-indigo-950/60 to-slate-900 border-2 border-indigo-500/80 p-6 shadow-xl shadow-indigo-500/10 flex flex-col justify-between">
                <span class="absolute -top-3 right-6 bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-[10px] font-black uppercase tracking-wider px-3 py-1 rounded-full shadow-md">
                    Most Popular
                </span>
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">Pro Creator</span>
                        <span class="text-2xl font-black text-white">₹799</span>
                    </div>
                    <div class="text-2xl font-extrabold text-amber-400 mb-1">350 Tokens</div>
                    <p class="text-xs text-indigo-200/80 mb-4">Best value for active content creators & YouTubers.</p>
                    <ul class="text-xs text-slate-200 space-y-2 mb-6">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> 35 Dialogue Shorts or ~23 Song Shorts</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> Priority queue processing</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> Mukhda & Chorus high-energy detection</li>
                    </ul>
                </div>
                <button @click="rechargeModal = true" class="w-full py-2.5 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white text-xs font-bold transition shadow-lg shadow-indigo-500/25">
                    Recharge ₹799
                </button>
            </div>

            <!-- Agency -->
            <div class="rounded-2xl bg-slate-900/80 border border-slate-800 hover:border-slate-700 p-6 transition flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-800 text-slate-300">Agency Pack</span>
                        <span class="text-2xl font-black text-white">₹1,999</span>
                    </div>
                    <div class="text-2xl font-extrabold text-amber-400 mb-1">1,000 Tokens</div>
                    <p class="text-xs text-slate-400 mb-4">For digital marketing agencies & video editing studios.</p>
                    <ul class="text-xs text-slate-300 space-y-2 mb-6">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> 100 Dialogue Shorts or ~66 Song Shorts</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> Lowest cost per short (~₹1.99 / short)</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> VIP priority queue</li>
                    </ul>
                </div>
                <button @click="rechargeModal = true" class="w-full py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold transition border border-slate-700">
                    Recharge ₹1,999
                </button>
            </div>
        </div>
    </div>

    <!-- Transaction Ledger Table -->
    <div class="rounded-2xl bg-slate-900/80 border border-slate-800 overflow-hidden shadow-xl">
        <div class="px-6 py-4 border-b border-slate-800 flex items-center justify-between">
            <h3 class="text-base font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-slate-400"></i>
                Token Transaction History
            </h3>
            <span class="text-xs text-slate-400">Showing recent activity</span>
        </div>

        @if($transactions->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-slate-950/60 text-xs uppercase text-slate-400 tracking-wider">
                        <tr>
                            <th class="px-6 py-3 font-semibold">Date & Time</th>
                            <th class="px-6 py-3 font-semibold">Type</th>
                            <th class="px-6 py-3 font-semibold">Description</th>
                            <th class="px-6 py-3 font-semibold text-right">Tokens</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/80">
                        @foreach($transactions as $tx)
                            <tr class="hover:bg-slate-800/30 transition">
                                <td class="px-6 py-3.5 text-xs text-slate-400 whitespace-nowrap">
                                    {{ $tx->created_at->format('M d, Y • h:i A') }}
                                </td>
                                <td class="px-6 py-3.5 whitespace-nowrap">
                                    @if($tx->type === 'bonus')
                                        <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Bonus</span>
                                    @elseif($tx->type === 'usage')
                                        <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-rose-500/20 text-rose-300 border border-rose-500/30">Usage</span>
                                    @elseif($tx->type === 'manual_credit')
                                        <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-amber-500/20 text-amber-300 border border-amber-500/30">Admin Credit</span>
                                    @elseif($tx->type === 'refund')
                                        <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-blue-500/20 text-blue-300 border border-blue-500/30">Refund</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-800 text-slate-300">{{ ucfirst($tx->type) }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3.5 text-xs text-slate-200">
                                    {{ $tx->description }}
                                    @if($tx->project_id)
                                        <a href="{{ route('projects.show', $tx->project_id) }}" class="text-indigo-400 hover:underline ml-1">
                                            (View Project #{{ $tx->project_id }})
                                        </a>
                                    @endif
                                </td>
                                <td class="px-6 py-3.5 text-right font-extrabold whitespace-nowrap">
                                    @if($tx->amount > 0)
                                        <span class="text-emerald-400">+{{ $tx->amount }}</span>
                                    @else
                                        <span class="text-rose-400">{{ $tx->amount }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($transactions->hasPages())
                <div class="px-6 py-4 border-t border-slate-800">
                    {{ $transactions->links() }}
                </div>
            @endif
        @else
            <div class="p-8 text-center text-slate-500 text-sm">
                No token transactions recorded yet.
            </div>
        @endif
    </div>

    <!-- Manual Recharge Modal -->
    <div x-cloak x-show="rechargeModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="bg-slate-900 border border-slate-800 rounded-2xl max-w-md w-full p-6 shadow-2xl relative" @click.outside="rechargeModal = false">
            <button @click="rechargeModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-lg border border-amber-500/30">
                    <i class="fa-solid fa-qrcode"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-white">Manual Token Recharge</h3>
                    <p class="text-xs text-slate-400">Pay via UPI & get instant credit from Admin</p>
                </div>
            </div>

            <div class="bg-slate-950 border border-slate-800 rounded-xl p-4 text-center mb-4">
                <div class="w-40 h-40 bg-white rounded-lg mx-auto flex items-center justify-center p-2 mb-3 shadow">
                    <!-- UPI QR Placeholder -->
                    <div class="text-center">
                        <i class="fa-solid fa-qrcode text-6xl text-slate-800 mb-1"></i>
                        <span class="block text-[10px] font-bold text-slate-600">Scan to Pay via UPI</span>
                    </div>
                </div>
                <div class="text-xs font-semibold text-slate-300">
                    UPI ID: <span class="text-amber-400 select-all font-mono">admin@upi</span>
                </div>
            </div>

            <div class="text-xs text-slate-300 space-y-2 mb-6">
                <p><strong>Steps to complete recharge:</strong></p>
                <ol class="list-decimal list-inside space-y-1 text-slate-400">
                    <li>Pay the amount for your selected pack (₹299, ₹799, or ₹1,999).</li>
                    <li>Share the Transaction ID / UTR or screenshot with Admin.</li>
                    <li>Admin will credit the tokens to your account in seconds!</li>
                </ol>
            </div>

            <div class="flex gap-3">
                <button @click="rechargeModal = false" class="flex-1 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold transition border border-slate-700">
                    Close
                </button>
                <a href="mailto:admin@example.com?subject=Token Recharge Request for {{ $user->email }}" class="flex-1 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-bold transition text-center shadow-lg shadow-amber-500/20">
                    Contact Admin
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
