@extends('layouts.app')

@section('title', 'Token Wallet & Credits • AI Shorts Studio')

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
                            color: '#e11d48'
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
         class="fixed top-8 right-8 z-50 p-4 rounded-2xl bg-emerald-600 text-white shadow-[0_16px_50px_rgba(21,34,56,.08)] flex items-center gap-3 border border-emerald-400">
        <i class="fa-solid fa-circle-check text-2xl"></i>
        <div>
            <div class="font-black text-sm">Payment Successful!</div>
            <div class="text-xs text-emerald-100">Tokens have been credited to your wallet. Refreshing...</div>
        </div>
    </div>

    <!-- Breadcrumb & Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 text-xs text-[#687684] mb-1 font-semibold">
                <a href="{{ route('dashboard') }}" class="hover:text-[#152238] transition">Dashboard</a>
                <i class="fa-solid fa-chevron-right text-[10px] text-[#687684]"></i>
                <span class="text-[#526078]">Token Wallet</span>
            </div>
            <h1 class="font-['Space_Grotesk'] text-2xl sm:text-3xl font-bold text-[#152238] tracking-[-.05em] flex items-center gap-3">
                <span class="w-10 h-10 rounded-2xl bg-amber-500/15 text-[#A86B1A] border border-amber-500/25 flex items-center justify-center text-lg shadow-sm">
                    <i class="fa-solid fa-coins"></i>
                </span>
                Token Wallet & Credits
            </h1>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('nostalgia.studio') }}" class="inline-flex items-center gap-2 bg-pink-500/15 hover:bg-pink-500/25 text-[#B34A6A] font-bold text-xs sm:text-sm px-4 py-2.5 rounded-2xl border border-pink-500/30 transition">
                <i class="fa-solid fa-clapperboard text-pink-400"></i>
                <span>Nostalgia Studio</span>
            </a>
            <a href="{{ route('projects.create') }}" class="inline-flex items-center gap-2 bg-[#4D5CFF] hover:bg-[#3949E9] text-white text-xs sm:text-sm font-bold px-4 py-2.5 rounded-2xl shadow-lg shadow-rose-600/30 transition hover:scale-[1.02]">
                <i class="fa-solid fa-wand-magic-sparkles"></i>
                <span>Create Short</span>
            </a>
        </div>
    </div>

    <!-- Balance & Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Main Wallet Card -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#EEF0FF] via-white to-white border border-amber-500/30 p-6 sm:p-8 shadow-[0_16px_50px_rgba(21,34,56,.08)]">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs uppercase tracking-wider font-bold text-[#805B2B]">Available Balance</span>
                <span class="w-9 h-9 rounded-xl bg-amber-500/20 text-[#A86B1A] flex items-center justify-center text-sm border border-amber-500/30">
                    <i class="fa-solid fa-wallet"></i>
                </span>
            </div>
            <div class="font-['Space_Grotesk'] text-4xl sm:text-5xl font-bold text-[#152238] mb-2 flex items-baseline gap-2">
                {{ number_format($user->tokens) }}
                <span class="text-sm font-bold text-[#A86B1A]">Credits</span>
            </div>
            <p class="text-xs text-[#687684]">
                Use one credit to process one video and generate its selected cuts.
            </p>
        </div>

        <!-- Token Consumption Rates -->
        <div class="rounded-3xl bg-white border border-[#DDE4F0] p-6 sm:p-8 flex flex-col justify-between shadow-[0_16px_50px_rgba(21,34,56,.08)]">
            <div>
                <span class="text-xs uppercase tracking-wider font-bold text-[#687684]">Credit Consumption Rate</span>
                <div class="mt-4 space-y-2.5">
                    <div class="flex items-center justify-between text-xs py-2 border-b border-[#E8ECF3]">
                        <span class="text-[#526078] flex items-center gap-2 font-medium">
                            <i class="fa-solid fa-microphone text-indigo-400"></i>
                            Dialogue / Podcast Mode
                        </span>
                        <span class="font-bold text-[#152238]">1 Credit / Video</span>
                    </div>
                    <div class="flex items-center justify-between text-xs py-2">
                        <span class="text-[#526078] flex items-center gap-2 font-medium">
                            <i class="fa-solid fa-music text-pink-400"></i>
                            Song / Mukhda Mode
                        </span>
                        <span class="font-bold text-[#152238]">1 Credit / Video</span>
                    </div>
                </div>
            </div>
            <div class="text-[11px] text-[#8A96A8] mt-3">
                1 video credit processes one video. Choose the number of cuts when you create a project.
            </div>
        </div>

        <!-- Account Tier Card -->
        <div class="rounded-3xl bg-white border border-[#DDE4F0] p-6 sm:p-8 flex flex-col justify-between shadow-[0_16px_50px_rgba(21,34,56,.08)]">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs uppercase tracking-wider font-bold text-[#687684]">Account Role</span>
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $user->isAdmin() ? 'bg-purple-500/20 text-[#6650B5] border border-purple-500/30' : 'bg-emerald-500/20 text-[#208052] border border-emerald-500/30' }}">
                        {{ strtoupper($user->role) }}
                    </span>
                </div>
                <div class="text-lg font-bold text-[#152238] mb-0.5">{{ $user->name }}</div>
                <div class="text-xs text-[#687684] truncate">{{ $user->email }}</div>
            </div>
            <div class="text-[11px] text-[#687684] mt-4 flex items-center gap-1.5">
                <i class="fa-solid fa-circle-check text-[#208052]"></i>
                Status: <span class="text-[#208052] font-bold capitalize">{{ $user->status }}</span>
            </div>
        </div>
    </div>

    <!-- Pricing Packs Grid with Razorpay Integration -->
    <div class="mb-12">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-[#152238] flex items-center gap-2 tracking-tight">
                    <i class="fa-solid fa-coins text-[#A86B1A]"></i>
                    <span>Video Credit Recharge Packs</span>
                </h2>
                <p class="text-xs text-[#687684] mt-0.5">
                    <strong>1 credit processes 1 video.</strong> Choose 1, 3, 5, or all suggested cuts.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-500/10 text-[#4251CE] border border-blue-500/20 text-xs font-semibold">
                    <i class="fa-solid fa-shield-halved text-[10px]"></i>
                    Razorpay Secure (UPI • Cards • NetBanking)
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- 1. Trial: Rs 99 - 3 Credits -->
            <div class="rounded-3xl bg-white border border-[#DDE4F0] hover:border-[#B4C0D2] p-6 sm:p-7 transition flex flex-col justify-between group shadow-xl">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-bold px-3 py-1 rounded-full bg-[#F4F7FB] text-[#526078]">Mini Trial</span>
                        <span class="text-2xl font-black text-[#152238]">₹99</span>
                    </div>
                    <div class="text-2xl font-black text-[#A86B1A] mb-1">3 Video Credits</div>
                    <p class="text-xs text-[#687684] mb-5">Try the studio with three video projects.</p>
                    <ul class="text-xs text-[#526078] space-y-2.5 mb-6">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#208052]"></i> <strong>3 Videos</strong> (Any length)</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#208052]"></i> Choose from suggested cuts</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#208052]"></i> 1080x1920 HD with blurred background</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#208052]"></i> Auto Subscribe & Like outro</li>
                    </ul>
                </div>
                <div class="space-y-2 pt-2">
                    <button type="button" @click="initiatePayment('trial')" :disabled="loading"
                            class="w-full py-3 rounded-xl bg-[#4D5CFF] hover:bg-[#3949E9] text-white text-xs font-black transition shadow-lg shadow-indigo-600/15 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-bolt text-[#805B2B]"></i>
                        <span>Pay ₹99 with Razorpay</span>
                    </button>
                    <button type="button" @click="rechargeModal = true"
                            class="w-full py-2.5 rounded-xl bg-[#FAFBFE] hover:bg-[#EEF0FF] text-[#687684] hover:text-[#152238] text-[11px] font-semibold transition border border-[#E8ECF3]">
                        Pay via Manual UPI QR
                    </button>
                </div>
            </div>

            <!-- 2. Starter: Rs 299 - 10 Credits -->
            <div class="rounded-3xl bg-white border border-[#DDE4F0] hover:border-[#B4C0D2] p-6 sm:p-7 transition flex flex-col justify-between group shadow-xl">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-bold px-3 py-1 rounded-full bg-[#F4F7FB] text-[#526078]">Starter Pack</span>
                        <span class="text-2xl font-black text-[#152238]">₹299</span>
                    </div>
                    <div class="text-2xl font-black text-[#A86B1A] mb-1">10 Video Credits</div>
                    <p class="text-xs text-[#687684] mb-5">Ideal for test-running your next 10 videos.</p>
                    <ul class="text-xs text-[#526078] space-y-2.5 mb-6">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#208052]"></i> <strong>10 Full Videos</strong></li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#208052]"></i> Flexible cuts per video</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#208052]"></i> Dialogue & Song Mode access</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#208052]"></i> Smart Natural Boundary detection</li>
                    </ul>
                </div>
                <div class="space-y-2 pt-2">
                    <button type="button" @click="initiatePayment('starter')" :disabled="loading"
                            class="w-full py-3 rounded-xl bg-[#4D5CFF] hover:bg-[#3949E9] text-white text-xs font-black transition shadow-lg shadow-indigo-600/15 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-bolt text-[#805B2B]"></i>
                        <span>Pay ₹299 with Razorpay</span>
                    </button>
                    <button type="button" @click="rechargeModal = true"
                            class="w-full py-2.5 rounded-xl bg-[#FAFBFE] hover:bg-[#EEF0FF] text-[#687684] hover:text-[#152238] text-[11px] font-semibold transition border border-[#E8ECF3]">
                        Pay via Manual UPI QR
                    </button>
                </div>
            </div>

            <!-- 3. Pro Creator: Rs 499 - 20 Credits (Most Popular) -->
            <div class="relative rounded-3xl bg-gradient-to-b from-[#F3F4FF] via-white to-white border-2 border-[#4D5CFF] p-6 sm:p-7 shadow-[0_16px_50px_rgba(21,34,56,.08)] shadow-indigo-600/15 flex flex-col justify-between">
                <span class="absolute -top-3 right-6 bg-[#4D5CFF] text-white text-[10px] font-black uppercase tracking-wider px-3 py-1 rounded-full shadow-lg">
                    Most Popular
                </span>
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-bold px-3 py-1 rounded-full bg-rose-500/20 text-[#C94F46] border border-rose-500/30">Pro Creator</span>
                        <span class="text-2xl font-black text-[#152238]">₹499</span>
                    </div>
                    <div class="text-2xl font-black text-[#A86B1A] mb-1">20 Video Credits</div>
                    <p class="text-xs text-[#687684] mb-5">Best value for active creators & YouTubers.</p>
                    <ul class="text-xs text-[#526078] space-y-2.5 mb-6">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#208052]"></i> <strong>20 Full Videos</strong></li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#208052]"></i> Choose 1, 3, 5, or all suggested cuts</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#208052]"></i> Dialogue and song modes</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#208052]"></i> Mukhda & Chorus peak detection</li>
                    </ul>
                </div>
                <div class="space-y-2 pt-2">
                    <button type="button" @click="initiatePayment('pro')" :disabled="loading"
                            class="w-full py-3 rounded-xl bg-[#4D5CFF] hover:bg-[#3949E9] text-white text-xs font-black transition shadow-xl shadow-indigo-600/15 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-bolt text-amber-200"></i>
                        <span>Pay ₹499 with Razorpay</span>
                    </button>
                    <button type="button" @click="rechargeModal = true"
                            class="w-full py-2.5 rounded-xl bg-[#FAFBFE] hover:bg-[#EEF0FF] text-[#687684] hover:text-[#152238] text-[11px] font-semibold transition border border-[#E8ECF3]">
                        Pay via Manual UPI QR
                    </button>
                </div>
            </div>

            <!-- 4. Agency Scale: Rs 999 - 50 Credits -->
            <div class="rounded-3xl bg-white border border-[#DDE4F0] hover:border-[#B4C0D2] p-6 sm:p-7 transition flex flex-col justify-between group shadow-xl">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-bold px-3 py-1 rounded-full bg-[#F4F7FB] text-[#526078]">Agency Scale</span>
                        <span class="text-2xl font-black text-[#152238]">₹999</span>
                    </div>
                    <div class="text-2xl font-black text-[#A86B1A] mb-1">50 Video Credits</div>
                    <p class="text-xs text-[#687684] mb-5">For digital marketing agencies & video studios.</p>
                    <ul class="text-xs text-[#526078] space-y-2.5 mb-6">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#208052]"></i> <strong>50 Full Videos</strong></li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#208052]"></i> Choose all suggested cuts per video</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#208052]"></i> Just ₹19.98 per full video</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#208052]"></i> Song and dialogue modes</li>
                    </ul>
                </div>
                <div class="space-y-2 pt-2">
                    <button type="button" @click="initiatePayment('agency')" :disabled="loading"
                            class="w-full py-3 rounded-xl bg-[#4D5CFF] hover:bg-[#3949E9] text-white text-xs font-black transition shadow-lg shadow-indigo-600/15 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-bolt text-[#805B2B]"></i>
                        <span>Pay ₹999 with Razorpay</span>
                    </button>
                    <button type="button" @click="rechargeModal = true"
                            class="w-full py-2.5 rounded-xl bg-[#FAFBFE] hover:bg-[#EEF0FF] text-[#687684] hover:text-[#152238] text-[11px] font-semibold transition border border-[#E8ECF3]">
                        Pay via Manual UPI QR
                    </button>
                </div>
            </div>

            <!-- 5. Mega Enterprise: Rs 4999 - 1,000 Credits -->
            <div class="rounded-3xl bg-gradient-to-b from-[#FFF8EE] to-white border border-amber-500/40 hover:border-amber-500 p-6 sm:p-7 transition flex flex-col justify-between group shadow-xl">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-bold px-3 py-1 rounded-full bg-amber-500/20 text-[#805B2B] border border-amber-500/30">Mega Enterprise</span>
                        <span class="text-2xl font-black text-[#152238]">₹4,999</span>
                    </div>
                    <div class="text-2xl font-black text-[#A86B1A] mb-1">1,000 Video Credits</div>
                    <p class="text-xs text-[#687684] mb-5">Massive scale for media production houses.</p>
                    <ul class="text-xs text-[#526078] space-y-2.5 mb-6">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#A86B1A]"></i> <strong>1,000 Full Videos</strong></li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#A86B1A]"></i> Flexible cut selection for each video</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#A86B1A]"></i> Just ₹4.99 per full video project</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#A86B1A]"></i> Song and dialogue modes</li>
                    </ul>
                </div>
                <div class="space-y-2 pt-2">
                    <button type="button" @click="initiatePayment('mega')" :disabled="loading"
                            class="w-full py-3 rounded-xl bg-gradient-to-r from-amber-500 to-yellow-600 hover:from-amber-600 hover:to-yellow-700 text-zinc-950 text-xs font-black transition shadow-lg shadow-amber-500/20 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-bolt text-zinc-950"></i>
                        <span>Pay ₹4,999 with Razorpay</span>
                    </button>
                    <button type="button" @click="rechargeModal = true"
                            class="w-full py-2.5 rounded-xl bg-[#FAFBFE] hover:bg-[#EEF0FF] text-[#687684] hover:text-[#152238] text-[11px] font-semibold transition border border-[#E8ECF3]">
                        Pay via Manual UPI QR
                    </button>
                </div>
            </div>

            <!-- 6. Custom Enterprise: Contact for > 1,000 Credits -->
            <div class="rounded-3xl bg-white border border-[#DDE4F0] hover:border-[#B4C0D2] p-6 sm:p-7 transition flex flex-col justify-between group shadow-xl">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-bold px-3 py-1 rounded-full bg-[#F4F7FB] text-[#526078]">Custom High Volume</span>
                        <span class="text-lg font-bold text-[#526078]">Custom Deal</span>
                    </div>
                    <div class="text-2xl font-black text-[#152238] mb-1">> 1,000 Credits</div>
                    <p class="text-xs text-[#687684] mb-5">Need higher volumes, automated API webhooks, or dedicated private GPU servers?</p>
                    <ul class="text-xs text-[#526078] space-y-2.5 mb-6">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#208052]"></i> Custom credit packs (5,000+ videos)</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#208052]"></i> Direct API integration</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#208052]"></i> Custom watermark & intro/outro branding</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#208052]"></i> Dedicated 24/7 account manager</li>
                    </ul>
                </div>
                <div class="space-y-2 pt-2">
                    <a href="https://wa.me/919999999999?text=Hi%2C%20I%20need%20custom%20high-volume%20credits%20for%20AI%20Shorts%20Creator" target="_blank"
                       class="w-full py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black transition shadow-lg shadow-emerald-600/20 flex items-center justify-center gap-2 text-center block">
                        <i class="fa-brands fa-whatsapp text-sm"></i>
                        <span>Contact Us on WhatsApp</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction Ledger Table -->
    <div class="rounded-3xl bg-white border border-[#DDE4F0] overflow-hidden shadow-[0_16px_50px_rgba(21,34,56,.08)]">
        <div class="px-6 sm:px-8 py-5 border-b border-[#DDE4F0] flex items-center justify-between">
            <h3 class="text-base font-bold text-[#152238] flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-[#687684]"></i>
                <span>Credit Transaction History</span>
            </h3>
            <span class="text-xs text-[#687684] font-semibold">Recent activity</span>
        </div>

        @if($transactions->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-[#526078]">
                    <thead class="bg-[#FAFBFE] text-xs uppercase text-[#687684] tracking-wider">
                        <tr>
                            <th class="px-6 py-4 font-bold">Date & Time</th>
                            <th class="px-6 py-4 font-bold">Type</th>
                            <th class="px-6 py-4 font-bold">Description</th>
                            <th class="px-6 py-4 font-bold text-right">Credits</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E8ECF3]">
                        @foreach($transactions as $tx)
                            <tr class="hover:bg-[#FAFBFE] transition">
                                <td class="px-6 py-4 text-xs text-[#687684] whitespace-nowrap">
                                    {{ $tx->created_at->format('M d, Y • h:i A') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($tx->type === 'bonus')
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-500/20 text-[#208052] border border-emerald-500/30">Bonus</span>
                                    @elseif($tx->type === 'purchase')
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-indigo-500/20 text-[#4251CE] border border-indigo-500/30">Razorpay</span>
                                    @elseif($tx->type === 'usage')
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-500/20 text-[#C94F46] border border-rose-500/30">Usage</span>
                                    @elseif($tx->type === 'manual_credit')
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-500/20 text-[#805B2B] border border-amber-500/30">Admin Credit</span>
                                    @elseif($tx->type === 'refund')
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[#EEF0FF] text-[#4251CE] border border-[#D8DFFF]">Refund</span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[#F4F7FB] text-[#526078]">{{ ucfirst($tx->type) }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs text-[#526078]">
                                    {{ $tx->description }}
                                    @if($tx->project_id)
                                        <a href="{{ route('projects.show', $tx->project_id) }}" class="text-[#C94F46] hover:underline ml-1">
                                            (View Project #{{ $tx->project_id }})
                                        </a>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right font-black font-mono whitespace-nowrap">
                                    @if($tx->amount > 0)
                                        <span class="text-[#208052]">+{{ $tx->amount }}</span>
                                    @else
                                        <span class="text-[#C94F46]">{{ $tx->amount }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($transactions->hasPages())
                <div class="px-6 py-4 border-t border-[#DDE4F0]">
                    {{ $transactions->links() }}
                </div>
            @endif
        @else
            <div class="p-12 text-center text-[#8A96A8] text-xs">
                No credit transactions recorded yet.
            </div>
        @endif
    </div>

    <!-- RAZORPAY TEST / SIMULATION MODAL -->
    <div x-cloak x-show="payModal"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/85 backdrop-blur-xl"
         x-transition>
        <div class="bg-white border border-[#CCD6E5] rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-[0_16px_50px_rgba(21,34,56,.08)] relative" @click.outside="payModal = false">
            <button @click="payModal = false" class="absolute top-5 right-5 text-[#687684] hover:text-[#152238] p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <!-- Razorpay Brand Header -->
            <div class="flex items-center gap-3.5 pb-4 mb-5 border-b border-[#DDE4F0]">
                <div class="w-11 h-11 rounded-2xl bg-rose-500/15 text-[#C94F46] flex items-center justify-center text-lg border border-rose-500/25">
                    <i class="fa-solid fa-credit-card"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-base font-black text-[#152238]">Razorpay Secure Checkout</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/20 text-[#805B2B] border border-amber-500/30">Test Mode</span>
                    </div>
                    <p class="text-xs text-[#687684] mt-0.5">Order ID: <span class="font-mono text-[#526078]" x-text="orderData?.order_id"></span></p>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="p-4 rounded-2xl bg-[#FAFBFE] border border-[#DDE4F0] mb-5 space-y-2.5">
                <div class="flex items-center justify-between text-xs">
                    <span class="text-[#687684]">Pack Selected:</span>
                    <span class="font-bold text-[#152238]" x-text="orderData?.plan_name"></span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="text-[#687684]">Credits to Add:</span>
                    <span class="font-black font-mono text-[#A86B1A]" x-text="'+' + orderData?.tokens + ' Credits'"></span>
                </div>
                <div class="flex items-center justify-between text-sm pt-2.5 border-t border-[#DDE4F0]">
                    <span class="font-bold text-[#526078]">Total Payable:</span>
                    <span class="font-black text-[#208052] text-lg" x-text="'₹' + (orderData?.amount / 100).toFixed(2)"></span>
                </div>
            </div>

            <!-- Simulation Payment Options -->
            <div class="space-y-3 mb-6">
                <div class="text-[11px] font-bold uppercase tracking-wider text-[#687684]">Simulate Payment Method:</div>
                <div class="grid grid-cols-3 gap-2 text-center text-xs">
                    <div class="p-3 rounded-2xl bg-[#FAFBFE] border border-rose-500/40 text-[#C94F46] font-bold flex flex-col items-center gap-1.5">
                        <i class="fa-solid fa-mobile-screen text-base"></i>
                        <span>UPI / QR</span>
                    </div>
                    <div class="p-3 rounded-2xl bg-[#FAFBFE] border border-[#DDE4F0] text-[#526078] font-bold flex flex-col items-center gap-1.5">
                        <i class="fa-regular fa-credit-card text-base"></i>
                        <span>Cards</span>
                    </div>
                    <div class="p-3 rounded-2xl bg-[#FAFBFE] border border-[#DDE4F0] text-[#526078] font-bold flex flex-col items-center gap-1.5">
                        <i class="fa-solid fa-building-columns text-base"></i>
                        <span>NetBanking</span>
                    </div>
                </div>
            </div>

            <div class="space-y-2.5">
                <button type="button" 
                        @click="verifyPayment(orderData.order_id, 'pay_mock_' + Date.now(), 'mock_sig_pass')"
                        :disabled="loading"
                        class="w-full py-3.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs sm:text-sm shadow-xl shadow-emerald-600/30 transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-circle-check" x-show="!loading"></i>
                    <i class="fa-solid fa-spinner fa-spin" x-show="loading"></i>
                    <span x-text="loading ? 'Crediting Tokens...' : 'Simulate Successful Payment'"></span>
                </button>
                <button type="button" @click="payModal = false" class="w-full py-2 text-xs text-[#687684] hover:text-[#152238]">
                    Cancel Transaction
                </button>
            </div>
        </div>
    </div>

    <!-- Manual Recharge Modal -->
    <div x-cloak x-show="rechargeModal"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/85 backdrop-blur-xl"
         x-transition>
        <div class="bg-white border border-[#CCD6E5] rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-[0_16px_50px_rgba(21,34,56,.08)] relative" @click.outside="rechargeModal = false">
            <button @click="rechargeModal = false" class="absolute top-5 right-5 text-[#687684] hover:text-[#152238] p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <div class="flex items-center gap-3.5 mb-5">
                <div class="w-11 h-11 rounded-2xl bg-amber-500/15 text-[#A86B1A] flex items-center justify-center text-lg border border-amber-500/25">
                    <i class="fa-solid fa-qrcode"></i>
                </div>
                <div>
                    <h3 class="text-base font-black text-[#152238]">Manual Token Recharge</h3>
                    <p class="text-xs text-[#687684]">Pay via UPI & get instant credit from Admin</p>
                </div>
            </div>

            <div class="bg-[#FAFBFE] border border-[#DDE4F0] rounded-2xl p-4 text-center mb-5">
                <div class="w-40 h-40 bg-white rounded-xl mx-auto flex items-center justify-center p-2 mb-3 shadow-lg">
                    <div class="text-center">
                        <i class="fa-solid fa-qrcode text-6xl text-[#344359] mb-1"></i>
                        <span class="block text-[10px] font-bold text-[#687684]">Scan to Pay via UPI</span>
                    </div>
                </div>
                <div class="text-xs font-semibold text-[#526078]">
                    UPI ID: <span class="text-[#A86B1A] select-all font-mono font-bold">admin@upi</span>
                </div>
            </div>

            <div class="text-xs text-[#526078] space-y-2 mb-6">
                <p class="font-bold text-[#152238]">Steps to complete recharge:</p>
                <ol class="list-decimal list-inside space-y-1 text-[#687684] font-normal">
                    <li>Pay the amount for your selected pack (₹99, ₹299, ₹499, or ₹999).</li>
                    <li>Share the Transaction ID / UTR screenshot with Admin.</li>
                    <li>Admin will credit the tokens to your account instantly!</li>
                </ol>
            </div>

            <div class="flex gap-3">
                <button @click="rechargeModal = false" class="flex-1 py-3 rounded-xl bg-[#F4F7FB] hover:bg-[#E8ECF3] text-[#526078] text-xs font-bold transition border border-[#DDE4F0]">
                    Close
                </button>
                <a href="mailto:admin@example.com?subject=Token Recharge Request for {{ $user->email }}" class="flex-1 py-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-zinc-950 text-xs font-black transition text-center shadow-lg shadow-amber-500/20">
                    Contact Admin
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Razorpay Official Checkout SDK -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@endsection
