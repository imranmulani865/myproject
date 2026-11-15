@extends('layouts.app')

@section('title', 'Token Wallet & Recharge')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" 
     x-data="{ 
        rechargeModal: false, 
        payModal: false, 
        orderData: null, 
        loading: false, 
        successNotice: false,
        errorMessage: '',
        async initiatePayment(planId) {
            this.loading = true;
            this.errorMessage = '';
            try {
                const res = await fetch('{{ route('payment.order') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ plan_id: planId })
                });
                const data = await res.json();
                if (!data.success) {
                    alert(data.message || 'Error creating payment order');
                    this.loading = false;
                    return;
                }
                this.orderData = data;
                if (data.is_mock) {
                    this.payModal = true;
                } else {
                    const options = {
                        key: data.key_id,
                        amount: data.amount,
                        currency: 'INR',
                        name: 'AI Shorts Studio',
                        description: data.plan_name + ' (' + data.tokens + ' Tokens)',
                        order_id: data.order_id,
                        handler: async (response) => {
                            await this.verifyPayment(response.razorpay_order_id, response.razorpay_payment_id, response.razorpay_signature);
                        },
                        prefill: {
                            name: data.user.name,
                            email: data.user.email
                        },
                        theme: {
                            color: '#6366f1'
                        }
                    };
                    const rzp = new window.Razorpay(options);
                    rzp.open();
                }
            } catch (e) {
                alert('Failed to initiate payment: ' + e.message);
            } finally {
                this.loading = false;
            }
        },
        async verifyPayment(orderId, paymentId, signature = '') {
            this.loading = true;
            try {
                const res = await fetch('{{ route('payment.verify') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        razorpay_order_id: orderId,
                        razorpay_payment_id: paymentId,
                        razorpay_signature: signature
                    })
                });
                const data = await res.json();
                if (data.success) {
                    this.payModal = false;
                    this.successNotice = true;
                    setTimeout(() => window.location.reload(), 1800);
                } else {
                    alert(data.message || 'Payment verification failed');
                }
            } catch (e) {
                alert('Verification error: ' + e.message);
            } finally {
                this.loading = false;
            }
        }
     }">

    <!-- Success Celebration Toast -->
    <div x-cloak x-show="successNotice" 
         x-transition
         class="fixed top-8 right-8 z-50 p-4 rounded-2xl bg-emerald-600 text-white shadow-2xl flex items-center gap-3 border border-emerald-400">
        <i class="fa-solid fa-circle-check text-2xl"></i>
        <div>
            <div class="font-extrabold text-sm">Payment Successful!</div>
            <div class="text-xs text-emerald-100">Tokens have been credited to your wallet. Refreshing...</div>
        </div>
    </div>

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
            <a href="{{ route('nostalgia.studio') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-pink-500 to-indigo-600 hover:from-pink-600 hover:to-indigo-700 text-white font-bold text-xs sm:text-sm px-4 py-2.5 rounded-xl shadow-lg shadow-pink-500/20 transition">
                <i class="fa-solid fa-clapperboard"></i>
                <span>Nostalgia Studio</span>
            </a>
            <a href="{{ route('projects.create') }}" class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs sm:text-sm font-semibold px-4 py-2.5 rounded-xl border border-slate-700 transition">
                <i class="fa-solid fa-wand-magic-sparkles"></i>
                <span>Create Short</span>
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
                <span class="text-xs uppercase tracking-wider font-semibold text-slate-400">Token Consumption Rates</span>
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
                Tokens automatically deducted on project start; refunded automatically if processing fails.
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

    <!-- Pricing Packs Grid with Razorpay Integration -->
    <div class="mb-10">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-6">
            <div>
                <h2 class="text-xl font-black text-white flex items-center gap-2">
                    <i class="fa-solid fa-coins text-amber-400"></i>
                    <span>Video Credit Recharge Packs</span>
                </h2>
                <p class="text-xs text-slate-400">
                    <strong>1 Video Credit = 1 Full Video</strong> (Generate 1, 3, 5, or <strong>All Possible Cuts</strong> from each video!).
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-500/10 text-blue-400 border border-blue-500/20 text-xs font-semibold">
                    <i class="fa-solid fa-shield-halved text-[10px]"></i>
                    Razorpay Secure (UPI • Cards • NetBanking)
                </span>
            </div>
        </div>

        <!-- 30-Day Storage Notice -->
        <div class="mb-6 p-4 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-300 text-xs flex items-center gap-3">
            <i class="fa-solid fa-clock-rotate-left text-amber-400 text-base shrink-0"></i>
            <div>
                <strong class="font-bold">Cloud Storage Period:</strong> All rendered video cuts and project assets remain stored on our servers for <strong>30 days</strong>. Please download your final MP4 files before 30 days expire!
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- 1. Trial: Rs 99 - 3 Credits -->
            <div class="rounded-2xl bg-slate-900/80 border border-slate-800 hover:border-slate-700 p-6 transition flex flex-col justify-between group">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-800 text-slate-300">Mini Trial</span>
                        <span class="text-2xl font-black text-white">₹99</span>
                    </div>
                    <div class="text-2xl font-extrabold text-amber-400 mb-1">3 Video Credits</div>
                    <p class="text-xs text-slate-400 mb-4">Try 3 full videos with unlimited stanzas and cuts.</p>
                    <ul class="text-xs text-slate-300 space-y-2 mb-6">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> <strong>3 Videos</strong> (Any length)</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> All Possible Cuts per video</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> 1080x1920 HD with blurred background</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> Auto Subscribe & Like outro</li>
                    </ul>
                </div>
                <div class="space-y-2">
                    <button type="button" @click="initiatePayment('trial')" :disabled="loading"
                            class="w-full py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-extrabold transition shadow-lg shadow-blue-500/20 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-bolt text-amber-300"></i>
                        <span>Pay ₹99 with Razorpay</span>
                    </button>
                    <button type="button" @click="rechargeModal = true"
                            class="w-full py-2 rounded-xl bg-slate-800/80 hover:bg-slate-700 text-slate-400 hover:text-slate-200 text-[11px] font-semibold transition border border-slate-800">
                        Pay via Manual UPI QR
                    </button>
                </div>
            </div>

            <!-- 2. Starter: Rs 299 - 10 Credits -->
            <div class="rounded-2xl bg-slate-900/80 border border-slate-800 hover:border-slate-700 p-6 transition flex flex-col justify-between group">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-800 text-slate-300">Starter Pack</span>
                        <span class="text-2xl font-black text-white">₹299</span>
                    </div>
                    <div class="text-2xl font-extrabold text-amber-400 mb-1">10 Video Credits</div>
                    <p class="text-xs text-slate-400 mb-4">Ideal for test-running your next 10 videos.</p>
                    <ul class="text-xs text-slate-300 space-y-2 mb-6">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> <strong>10 Full Videos</strong></li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> Unlimited shorts & cuts per video</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> Dialogue & Song Mode access</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> Smart Natural Boundary detection</li>
                    </ul>
                </div>
                <div class="space-y-2">
                    <button type="button" @click="initiatePayment('starter')" :disabled="loading"
                            class="w-full py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-extrabold transition shadow-lg shadow-blue-500/20 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-bolt text-amber-300"></i>
                        <span>Pay ₹299 with Razorpay</span>
                    </button>
                    <button type="button" @click="rechargeModal = true"
                            class="w-full py-2 rounded-xl bg-slate-800/80 hover:bg-slate-700 text-slate-400 hover:text-slate-200 text-[11px] font-semibold transition border border-slate-800">
                        Pay via Manual UPI QR
                    </button>
                </div>
            </div>

            <!-- 3. Pro Creator: Rs 499 - 20 Credits (Most Popular) -->
            <div class="relative rounded-2xl bg-gradient-to-b from-indigo-950/60 to-slate-900 border-2 border-indigo-500/80 p-6 shadow-xl shadow-indigo-500/10 flex flex-col justify-between">
                <span class="absolute -top-3 right-6 bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-[10px] font-black uppercase tracking-wider px-3 py-1 rounded-full shadow-md">
                    Most Popular
                </span>
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">Pro Creator</span>
                        <span class="text-2xl font-black text-white">₹499</span>
                    </div>
                    <div class="text-2xl font-extrabold text-amber-400 mb-1">20 Video Credits</div>
                    <p class="text-xs text-indigo-200/80 mb-4">Best value for active creators & YouTubers.</p>
                    <ul class="text-xs text-slate-200 space-y-2 mb-6">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> <strong>20 Full Videos</strong></li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> Unlimited stanzas & shorts per video</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> Priority queue processing</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> Mukhda & Chorus peak detection</li>
                    </ul>
                </div>
                <div class="space-y-2">
                    <button type="button" @click="initiatePayment('pro')" :disabled="loading"
                            class="w-full py-2.5 rounded-xl bg-gradient-to-r from-indigo-500 via-purple-600 to-pink-600 hover:from-indigo-600 hover:to-pink-700 text-white text-xs font-extrabold transition shadow-lg shadow-indigo-500/25 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-bolt text-amber-300"></i>
                        <span>Pay ₹499 with Razorpay</span>
                    </button>
                    <button type="button" @click="rechargeModal = true"
                            class="w-full py-2 rounded-xl bg-slate-800/80 hover:bg-slate-700 text-slate-400 hover:text-slate-200 text-[11px] font-semibold transition border border-slate-800">
                        Pay via Manual UPI QR
                    </button>
                </div>
            </div>

            <!-- 4. Agency Scale: Rs 999 - 50 Credits -->
            <div class="rounded-2xl bg-slate-900/80 border border-slate-800 hover:border-slate-700 p-6 transition flex flex-col justify-between group">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-800 text-slate-300">Agency Scale</span>
                        <span class="text-2xl font-black text-white">₹999</span>
                    </div>
                    <div class="text-2xl font-extrabold text-amber-400 mb-1">50 Video Credits</div>
                    <p class="text-xs text-slate-400 mb-4">For digital marketing agencies & video studios.</p>
                    <ul class="text-xs text-slate-300 space-y-2 mb-6">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> <strong>50 Full Videos</strong></li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> All Possible Cuts per video (~300+ shorts!)</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> Just ₹19.98 per full video</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> VIP high-speed queue</li>
                    </ul>
                </div>
                <div class="space-y-2">
                    <button type="button" @click="initiatePayment('agency')" :disabled="loading"
                            class="w-full py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-extrabold transition shadow-lg shadow-blue-500/20 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-bolt text-amber-300"></i>
                        <span>Pay ₹999 with Razorpay</span>
                    </button>
                    <button type="button" @click="rechargeModal = true"
                            class="w-full py-2 rounded-xl bg-slate-800/80 hover:bg-slate-700 text-slate-400 hover:text-slate-200 text-[11px] font-semibold transition border border-slate-800">
                        Pay via Manual UPI QR
                    </button>
                </div>
            </div>

            <!-- 5. Mega Enterprise: Rs 4999 - 1,000 Credits -->
            <div class="rounded-2xl bg-gradient-to-b from-amber-950/40 via-slate-900 to-slate-900 border border-amber-500/40 hover:border-amber-500 p-6 transition flex flex-col justify-between group shadow-xl">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-amber-500/20 text-amber-300 border border-amber-500/30">Mega Enterprise</span>
                        <span class="text-2xl font-black text-white">₹4,999</span>
                    </div>
                    <div class="text-2xl font-extrabold text-amber-400 mb-1">1,000 Video Credits</div>
                    <p class="text-xs text-slate-400 mb-4">Massive scale for media production houses.</p>
                    <ul class="text-xs text-slate-300 space-y-2 mb-6">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-amber-400"></i> <strong>1,000 Full Videos</strong></li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-amber-400"></i> Unlimited cuts (generate thousands of shorts!)</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-amber-400"></i> Just ₹4.99 per full video project</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-amber-400"></i> Dedicated worker priority</li>
                    </ul>
                </div>
                <div class="space-y-2">
                    <button type="button" @click="initiatePayment('mega')" :disabled="loading"
                            class="w-full py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-yellow-600 hover:from-amber-600 hover:to-yellow-700 text-slate-950 text-xs font-black transition shadow-lg shadow-amber-500/20 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-bolt text-slate-950"></i>
                        <span>Pay ₹4,999 with Razorpay</span>
                    </button>
                    <button type="button" @click="rechargeModal = true"
                            class="w-full py-2 rounded-xl bg-slate-800/80 hover:bg-slate-700 text-slate-400 hover:text-slate-200 text-[11px] font-semibold transition border border-slate-800">
                        Pay via Manual UPI QR
                    </button>
                </div>
            </div>

            <!-- 6. Custom Enterprise: Contact for > 1,000 Credits -->
            <div class="rounded-2xl bg-slate-900/80 border border-slate-800 hover:border-slate-700 p-6 transition flex flex-col justify-between group">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-800 text-slate-300">Custom High Volume</span>
                        <span class="text-xl font-bold text-slate-300">Custom Deal</span>
                    </div>
                    <div class="text-2xl font-extrabold text-white mb-1">> 1,000 Credits</div>
                    <p class="text-xs text-slate-400 mb-4">Need higher volumes, automated API webhooks, or dedicated private GPU servers?</p>
                    <ul class="text-xs text-slate-300 space-y-2 mb-6">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> Custom credit packs (5,000+ videos)</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> Direct API integration</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> Custom watermark & intro/outro branding</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> Dedicated 24/7 account manager</li>
                    </ul>
                </div>
                <div class="space-y-2">
                    <a href="https://wa.me/919999999999?text=Hi%2C%20I%20need%20custom%20high-volume%20credits%20for%20AI%20Shorts%20Creator" target="_blank"
                       class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold transition shadow-lg shadow-emerald-600/20 flex items-center justify-center gap-2 text-center block">
                        <i class="fa-brands fa-whatsapp text-sm"></i>
                        <span>Contact Us on WhatsApp</span>
                    </a>
                    <a href="mailto:support@shortcreator.ai?subject=Enterprise%20Volume%20Inquiry"
                       class="w-full py-2 rounded-xl bg-slate-800/80 hover:bg-slate-700 text-slate-300 hover:text-white text-[11px] font-semibold transition border border-slate-800 text-center block">
                        Email Enterprise Team
                    </a>
                </div>
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
                                    @elseif($tx->type === 'purchase')
                                        <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">Razorpay</span>
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

    <!-- RAZORPAY TEST / SIMULATION MODAL -->
    <div x-cloak x-show="payModal"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/85 backdrop-blur-md"
         x-transition>
        <div class="bg-slate-900 border border-indigo-500/50 rounded-3xl max-w-md w-full p-6 shadow-2xl relative" @click.outside="payModal = false">
            <button @click="payModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <!-- Razorpay Brand Header -->
            <div class="flex items-center gap-3 pb-4 mb-4 border-b border-slate-800">
                <div class="w-10 h-10 rounded-xl bg-blue-600/20 text-blue-400 flex items-center justify-center text-lg border border-blue-500/30">
                    <i class="fa-solid fa-credit-card"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-base font-black text-white">Razorpay Secure Checkout</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">Test Mode</span>
                    </div>
                    <p class="text-xs text-slate-400">Order ID: <span class="font-mono text-slate-300" x-text="orderData?.order_id"></span></p>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800 mb-5 space-y-2">
                <div class="flex items-center justify-between text-xs">
                    <span class="text-slate-400">Pack Selected:</span>
                    <span class="font-bold text-white" x-text="orderData?.plan_name"></span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="text-slate-400">Tokens to Credit:</span>
                    <span class="font-extrabold text-amber-400" x-text="'+' + orderData?.tokens + ' Tokens'"></span>
                </div>
                <div class="flex items-center justify-between text-sm pt-2 border-t border-slate-800">
                    <span class="font-bold text-slate-300">Total Payable:</span>
                    <span class="font-black text-emerald-400 text-lg" x-text="'₹' + (orderData?.amount / 100).toFixed(2)"></span>
                </div>
            </div>

            <!-- Simulation Payment Options -->
            <div class="space-y-3 mb-6">
                <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Simulate Payment Method:</div>
                <div class="grid grid-cols-3 gap-2 text-center text-xs">
                    <div class="p-2.5 rounded-xl bg-slate-950 border border-indigo-500/40 text-indigo-300 font-bold flex flex-col items-center gap-1">
                        <i class="fa-solid fa-mobile-screen text-base"></i>
                        <span>UPI / QR</span>
                    </div>
                    <div class="p-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-300 font-bold flex flex-col items-center gap-1">
                        <i class="fa-regular fa-credit-card text-base"></i>
                        <span>Cards</span>
                    </div>
                    <div class="p-2.5 rounded-xl bg-slate-950 border border-slate-800 text-slate-300 font-bold flex flex-col items-center gap-1">
                        <i class="fa-solid fa-building-columns text-base"></i>
                        <span>NetBanking</span>
                    </div>
                </div>
            </div>

            <div class="space-y-2">
                <button type="button" 
                        @click="verifyPayment(orderData.order_id, 'pay_mock_' + Date.now(), 'mock_sig_pass')"
                        :disabled="loading"
                        class="w-full py-3 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-black text-sm shadow-xl shadow-emerald-500/25 transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-circle-check" x-show="!loading"></i>
                    <i class="fa-solid fa-spinner fa-spin" x-show="loading"></i>
                    <span x-text="loading ? 'Crediting Tokens...' : 'Simulate Successful Payment'"></span>
                </button>
                <button type="button" @click="payModal = false" class="w-full py-2 text-xs text-slate-400 hover:text-slate-200">
                    Cancel Transaction
                </button>
            </div>
        </div>
    </div>

    <!-- Manual Recharge Modal -->
    <div x-cloak x-show="rechargeModal"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
         x-transition>
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

<!-- Razorpay Official Checkout SDK -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@endsection
