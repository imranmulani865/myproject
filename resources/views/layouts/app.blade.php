<!DOCTYPE html>
<html lang="en" class="h-full bg-studio-canvas text-studio-ink">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'AI Shorts Creator') }} - Long Video to Viral Shorts</title>
    <!-- Local / Fallback Tailwind CSS Browser Engine -->
    <script src="{{ asset('js/tailwindcss.js') }}"></script>
    <script>
        if (!window.tailwindcss && !document.querySelector('script[src*="@tailwindcss/browser"]')) {
            const fallbackScript = document.createElement('script');
            fallbackScript.src = 'https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4';
            document.head.appendChild(fallbackScript);
        }
    </script>
    <style type="text/tailwindcss">
        @theme {
            --color-studio-canvas: #f9f9f9;
            --color-studio-surface: #ffffff;
            --color-studio-ink: #0f0f0f;
            --color-studio-muted: #606060;
            --color-studio-line: #e5e5e5;
            --color-studio-lavender: #fee2e2;
            --color-studio-peach: #ffe4e6;
            --color-studio-lime: #ff0000;

            --color-brand-100: #fee2e2;
            --color-brand-200: #fecaca;
            --color-brand-300: #fca5a5;
            --color-brand-400: #f87171;
            --color-brand-500: #ef4444;
            --color-brand-600: #ff0000;
            --color-brand-700: #cc0000;
            --color-brand-800: #990000;
            --color-brand-900: #7f1d1d;
            --color-brand-950: #450a0a;

            --color-ocean-500: #ff0000;
            --color-ocean-600: #cc0000;
            --color-ocean-700: #990000;

            --color-aqua-500: #ff0000;
            --color-aqua-600: #cc0000;
            --color-aqua-700: #990000;
        }
    </style>
    <script>
        window.tailwind = window.tailwind || {};
        window.tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        studio: {
                            canvas: '#f9f9f9',
                            surface: '#ffffff',
                            ink: '#0f0f0f',
                            muted: '#606060',
                            line: '#e5e5e5',
                            lavender: '#fee2e2',
                            peach: '#ffe4e6',
                            lime: '#ff0000',
                        },
                        brand: {
                            100: '#fee2e2',
                            200: '#fecaca',
                            300: '#fca5a5',
                            400: '#f87171',
                            500: '#ef4444',
                            600: '#ff0000',
                            700: '#cc0000',
                            800: '#990000',
                            950: '#450a0a',
                        },
                        ocean: {
                            200: '#e6e4ff',
                            300: '#d1ceff',
                            400: '#a9a4f1',
                            500: '#6b63c4',
                            600: '#5149a6',
                            700: '#3d377f',
                            950: '#1a1942',
                        },
                        aqua: {
                            300: '#efe4ff',
                            400: '#cbb8f4',
                            500: '#7765bd',
                            600: '#5d4ea1',
                            700: '#493c7f',
                            950: '#241a45',
                        },
                    }
                }
            }
        };
    </script>
    <!-- Alpine.js (Local with CDN fallback) -->
    <script defer src="{{ asset('js/alpine.min.js') }}"></script>
    <!-- Font Awesome Icons (Local with CDN fallback) -->
    <link rel="stylesheet" href="{{ asset('css/all.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" media="print" onload="this.media='all'">
    <style>
        [x-cloak] { display: none !important; }
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f5f7ff; }
        ::-webkit-scrollbar-thumb { background: #c2c9e5; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #807ce7; }
        a:focus-visible, button:focus-visible {
            outline: 2px solid #4a42c6;
            outline-offset: 3px;
        }

        /* ===================================================
           YOUTUBE 3D CREATOR BOOK OPENING (With Proper Images & 3s Hold)
           =================================================== */
        .book-opening-stage {
            position: fixed;
            inset: 0;
            width: 100vw;
            height: 100vh;
            z-index: 999999;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            perspective: 1600px;
            perspective-origin: center center;
            animation: book-stage-vanish 0.45s ease 4.2s forwards;
            background: #0f0f0f;
            cursor: pointer;
        }

        @keyframes book-stage-vanish {
            0% { opacity: 1; visibility: visible; }
            100% { opacity: 0; visibility: hidden; display: none; }
        }

        /* Fast skip mode if clicked */
        .book-opening-stage.book-opening-fast {
            animation: book-stage-vanish 0.3s ease 0.35s forwards !important;
        }
        .book-opening-stage.book-opening-fast .book-leaf-left {
            animation: book-swing-left 0.45s cubic-bezier(0.25, 1, 0.35, 1) 0s forwards !important;
        }
        .book-opening-stage.book-opening-fast .book-leaf-right {
            animation: book-swing-right 0.45s cubic-bezier(0.25, 1, 0.35, 1) 0s forwards !important;
        }
        .book-opening-stage.book-opening-fast .book-spine-glow {
            opacity: 0 !important;
            transition: opacity 0.2s !important;
        }

        /* Left Book Leaf (MrBeast Short Card Cover) */
        .book-leaf-left {
            position: absolute;
            top: 0;
            left: 0;
            width: 50.1vw;
            height: 100vh;
            transform-origin: left center;
            transform-style: preserve-3d;
            background: linear-gradient(135deg, #0a0a0a 0%, #171717 50%, #0f0f0f 100%);
            border-right: 2px solid rgba(255, 0, 0, 0.85);
            box-shadow: inset -30px 0 70px rgba(0, 0, 0, 0.95), 0 0 100px rgba(0, 0, 0, 0.98);
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 4vw;
            animation: book-swing-left 1.15s cubic-bezier(0.25, 1, 0.35, 1) 3.0s forwards;
            will-change: transform;
        }

        /* Right Book Leaf (Casey Neistat Short Card Cover) */
        .book-leaf-right {
            position: absolute;
            top: 0;
            right: 0;
            width: 50.1vw;
            height: 100vh;
            transform-origin: right center;
            transform-style: preserve-3d;
            background: linear-gradient(225deg, #0a0a0a 0%, #171717 50%, #0f0f0f 100%);
            border-left: 2px solid rgba(255, 0, 0, 0.85);
            box-shadow: inset 30px 0 70px rgba(0, 0, 0, 0.95), 0 0 100px rgba(0, 0, 0, 0.98);
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding-left: 4vw;
            animation: book-swing-right 1.15s cubic-bezier(0.25, 1, 0.35, 1) 3.0s forwards;
            will-change: transform;
        }

        @keyframes book-swing-left {
            0% { transform: rotateY(0deg); }
            15% { transform: rotateY(-3deg); }
            100% { transform: rotateY(-112deg); }
        }

        @keyframes book-swing-right {
            0% { transform: rotateY(0deg); }
            15% { transform: rotateY(3deg); }
            100% { transform: rotateY(112deg); }
        }

        /* Framed Video Image Art on Covers */
        .book-cover-card {
            width: min(85%, 340px);
            background: #181818;
            border-radius: 20px;
            border: 2px solid rgba(255, 0, 0, 0.35);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.8), 0 0 30px rgba(255, 0, 0, 0.15);
            overflow: hidden;
            position: relative;
        }

        /* Center Spine Laser Glow during 3s hold and burst */
        .book-spine-glow {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 50%;
            width: 4px;
            transform: translateX(-50%);
            background: linear-gradient(180deg, transparent, #ff0000 25%, #ff4e45 75%, transparent);
            box-shadow: 0 0 35px #ff0000, 0 0 70px #cc0000;
            z-index: 20;
            pointer-events: none;
            animation: spine-pulse-hold 4.2s ease-out forwards;
        }

        @keyframes spine-pulse-hold {
            0% { opacity: 0; transform: translateX(-50%) scaleY(0.1); }
            8% { opacity: 1; transform: translateX(-50%) scaleY(1); width: 4px; box-shadow: 0 0 25px #ff0000; }
            65% { opacity: 0.85; width: 4px; box-shadow: 0 0 20px #ff0000, 0 0 40px #cc0000; }
            72% { opacity: 1; width: 6px; box-shadow: 0 0 50px #ff0000, 0 0 100px #ff4e45; }
            92% { opacity: 0.7; width: 2px; }
            100% { opacity: 0; transform: translateX(-50%) scaleY(1); }
        }

        /* Center Circular Seal Split - Iconic YouTube Play Button */
        .book-center-crest-half {
            position: absolute;
            top: 50%;
            width: 44px;
            height: 88px;
            transform: translateY(-50%);
            overflow: hidden;
            z-index: 15;
            pointer-events: none;
        }

        .book-center-crest-half.left-crest {
            right: 0;
        }

        .book-center-crest-half.right-crest {
            left: 0;
        }

        .yt-crest-circle {
            position: absolute;
            top: 0;
            width: 88px;
            height: 88px;
            border-radius: 50%;
            background: linear-gradient(135deg, #FF0000 0%, #CC0000 100%);
            border: 3px solid #ffffff;
            box-shadow: 0 0 35px rgba(255, 0, 0, 0.85), 0 0 70px rgba(255, 0, 0, 0.45);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .left-crest .yt-crest-circle {
            right: 0;
        }

        .right-crest .yt-crest-circle {
            left: 0;
        }

        /* YouTube Play Triangle */
        .yt-play-triangle {
            width: 0;
            height: 0;
            border-top: 13px solid transparent;
            border-bottom: 13px solid transparent;
            border-left: 22px solid #ffffff;
            margin-left: 4px;
        }

        /* YouTube Red Laser Sweeps as Book Opens */
        .opening-laser-bar {
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            width: 100%;
            z-index: 99999;
            pointer-events: none;
            background: linear-gradient(90deg, #ff0000 0%, #ff4e45 40%, #ff0000 80%, #cc0000 100%);
            background-size: 200% 100%;
            box-shadow: 0 0 16px rgba(255, 0, 0, 0.95), 0 0 30px rgba(255, 78, 69, 0.6);
            animation: laser-sweep 0.85s cubic-bezier(0.4, 0, 0.2, 1) 3.1s forwards;
        }

        @keyframes laser-sweep {
            0% { width: 0%; opacity: 1; }
            65% { width: 88%; opacity: 1; }
            90% { width: 100%; opacity: 0.9; }
            100% { width: 100%; opacity: 0; transform: translateY(-3px); }
        }

        .site-opening-reveal {
            animation: page-cinematic-enter 0.9s cubic-bezier(0.16, 1, 0.3, 1) 3.1s forwards;
            will-change: transform, opacity, filter;
        }

        @keyframes page-cinematic-enter {
            0% {
                opacity: 0;
                transform: translateY(16px) scale(0.985);
                filter: blur(5px);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
                filter: blur(0);
            }
        }

        .site-nav-enter {
            animation: nav-slide-down 0.8s cubic-bezier(0.16, 1, 0.3, 1) 3.1s forwards;
        }

        @keyframes nav-slide-down {
            0% { opacity: 0; transform: translateY(-12px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .ambient-glow-bloom {
            animation: aura-bloom 1.2s cubic-bezier(0.16, 1, 0.3, 1) 3.0s forwards;
        }

        @keyframes aura-bloom {
            0% { opacity: 0; transform: scale(0.85); }
            100% { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col antialiased bg-studio-canvas text-studio-ink selection:bg-studio-lavender selection:text-studio-ink" x-data="{ toast: { show: false, message: '' } }">
        <!-- 3D BOOK / CREATOR FOLIO OPENING (YouTube Style with Proper Images & 3s Hold) -->
    <div id="book-opening-stage" class="book-opening-stage" onclick="openBookNow()" aria-hidden="true" title="Click to skip">
        <!-- Center Spine Red Laser Pulse -->
        <div class="book-spine-glow"></div>

        <!-- LEFT BOOK LEAF / COVER (MrBeast Viral Short with Proper Image) -->
        <div class="book-leaf-left">
            <div class="book-cover-card">
                <div class="relative w-full h-[380px] overflow-hidden bg-black flex flex-col justify-between p-4">
                    <img src="{{ asset('creator-mrbeast.webp') }}" alt="MrBeast Short" class="absolute inset-0 w-full h-full object-cover opacity-85">
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent"></div>
                    
                    <!-- Top YouTube Badge -->
                    <div class="relative z-10 flex items-center justify-between">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md bg-[#FF0000] text-white text-[10px] font-black tracking-wider shadow-lg">
                            <i class="fa-brands fa-youtube text-xs"></i> SHORTS
                        </span>
                        <span class="px-2.5 py-1 rounded-md bg-black/75 backdrop-blur text-white text-[10px] font-bold border border-white/20">
                            🔥 98.4% Hook
                        </span>
                    </div>

                    <!-- Bottom Title & Info -->
                    <div class="relative z-10 text-left">
                        <div class="text-[10px] font-bold text-red-400 uppercase tracking-widest mb-0.5">Short #01 • 9:16 Vertical</div>
                        <h3 class="text-base font-black text-white leading-tight">MrBeast • $500,000 Challenge</h3>
                        <div class="mt-2 flex items-center gap-2 text-[11px] text-slate-300">
                            <span class="px-1.5 py-0.5 rounded bg-white/20 text-white font-bold">00:45</span>
                            <span>•</span>
                            <span>1080x1920 HD</span>
                            <span>•</span>
                            <span class="text-emerald-400 font-bold">● Ready</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Half Center Crest (Left Half of Red YouTube Play Button) -->
            <div class="book-center-crest-half left-crest">
                <div class="yt-crest-circle">
                    <div class="yt-play-triangle"></div>
                </div>
            </div>
        </div>

        <!-- RIGHT BOOK LEAF / COVER (Casey Neistat Vlog Short with Proper Image) -->
        <div class="book-leaf-right">
            <!-- Half Center Crest (Right Half of Red YouTube Play Button) -->
            <div class="book-center-crest-half right-crest">
                <div class="yt-crest-circle">
                    <div class="yt-play-triangle"></div>
                </div>
            </div>

            <div class="book-cover-card">
                <div class="relative w-full h-[380px] overflow-hidden bg-black flex flex-col justify-between p-4">
                    <img src="{{ asset('creator-casey-neistat.webp') }}" alt="Casey Neistat Short" class="absolute inset-0 w-full h-full object-cover opacity-85">
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent"></div>
                    
                    <!-- Top YouTube Badge -->
                    <div class="relative z-10 flex items-center justify-between">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md bg-[#FF0000] text-white text-[10px] font-black tracking-wider shadow-lg">
                            <i class="fa-brands fa-youtube text-xs"></i> CREATOR
                        </span>
                        <span class="px-2.5 py-1 rounded-md bg-black/75 backdrop-blur text-white text-[10px] font-bold border border-white/20">
                            ⚡ Auto 9:16
                        </span>
                    </div>

                    <!-- Bottom Title & Info -->
                    <div class="relative z-10 text-left">
                        <div class="text-[10px] font-bold text-red-400 uppercase tracking-widest mb-0.5">Short #02 • 9:16 Vertical</div>
                        <h3 class="text-base font-black text-white leading-tight">Casey Neistat • NYC Story Vlog</h3>
                        <div class="mt-2 flex items-center gap-2 text-[11px] text-slate-300">
                            <span class="px-1.5 py-0.5 rounded bg-white/20 text-white font-bold">00:58</span>
                            <span>•</span>
                            <span>1080x1920 HD</span>
                            <span>•</span>
                            <span class="text-emerald-400 font-bold">● Ready</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Interactive Skip Indicator with YouTube Red Accents -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-30 pointer-events-auto">
            <button type="button" onclick="openBookNow()" class="px-5 py-2 rounded-full bg-[#0F0F0F]/90 hover:bg-[#272727] text-white text-xs font-bold backdrop-blur border border-red-500/40 shadow-[0_4px_25px_rgba(255,0,0,0.35)] transition transform hover:scale-105 cursor-pointer flex items-center gap-2">
                <i class="fa-brands fa-youtube text-[#FF0000] text-sm"></i>
                <span>Opening Studio in 3s...</span>
                <span class="text-red-400 text-[11px] font-bold">| Skip ➔</span>
            </button>
        </div>
    </div>

    <!-- Top Cyber Neon Laser Opening Indicator -->
    <div class="opening-laser-bar" aria-hidden="true"></div>

    <!-- MAIN PAGE CONTENT WRAPPER WITH CINEMATIC REVEAL -->
    <div id="app-main-content" class="min-h-screen flex flex-col w-full relative site-opening-reveal">
        <!-- Ambient Glowing Background Lighting -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden -z-10 ambient-glow-bloom" aria-hidden="true">
            <div class="absolute -top-32 -left-32 w-[34rem] h-[34rem] bg-red-600/8 rounded-full blur-[120px] animate-pulse"></div>
            <div class="absolute top-1/4 -right-32 w-[30rem] h-[30rem] bg-red-500/7 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-10 left-1/4 w-[36rem] h-[36rem] bg-rose-600/6 rounded-full blur-[130px]"></div>
        </div>
    <!-- Toast Notification -->
    <div x-cloak x-show="toast.show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed bottom-6 right-6 z-50 flex items-center gap-3 bg-emerald-600 text-white px-5 py-3 rounded-xl shadow-2xl border border-emerald-500/50">
        <i class="fa-solid fa-circle-check text-xl"></i>
        <span x-text="toast.message" class="text-sm font-medium"></span>
    </div>

    <!-- Navigation Bar -->
    <nav class="sticky top-0 z-40 backdrop-blur-xl border-b bg-white/80 border-studio-line/80 shadow-[0_12px_32px_-24px_rgba(48,40,166,0.3)] site-nav-enter">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand / Logo -->
                <div class="flex items-center gap-3">
                    <a href="{{ auth()->check() ? route('dashboard') : url('/') }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-xl bg-[#FF0000] flex items-center justify-center shadow-lg shadow-red-500/30 group-hover:scale-105 transition">
                            <i class="fa-brands fa-youtube text-white text-xl"></i>
                        </div>
                        <div>
                            <span class="text-lg font-bold text-studio-ink">
                                <span class="sm:hidden">AI Shorts</span><span class="hidden sm:inline">AI Shorts Creator</span>
                            </span>
                            @auth
                            <span class="hidden sm:inline-block text-[10px] uppercase tracking-wider font-semibold px-2 py-0.5 rounded bg-studio-lavender/70 text-brand-700 border border-brand-200 ml-2">
                                Ollama Gemma
                            </span>
                            @endauth
                        </div>
                    </a>
                </div>

                @guest
                    <div class="hidden lg:flex items-center gap-7 text-sm font-medium text-studio-muted">
                        <a href="{{ url('/') }}#how-it-works" class="hover:text-brand-600 transition">How it works</a>
                        <a href="{{ url('/') }}#features" class="hover:text-brand-600 transition">Features</a>
                        <a href="{{ url('/') }}#faq" class="hover:text-brand-600 transition">FAQ</a>
                    </div>
                @endguest

                <!-- Navigation Links -->
                <div class="flex items-center gap-2 sm:gap-3">
                    @auth
                        <!-- Token Wallet Pill -->
                        <a href="{{ route('wallet') }}" class="hidden lg:inline-flex min-h-11 items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs font-semibold hover:bg-amber-100 transition group">
                            <i class="fa-solid fa-coins text-amber-600 group-hover:rotate-12 transition" aria-hidden="true"></i>
                            <span>{{ number_format(auth()->user()->tokens) }} Tokens</span>
                        </a>

                        @if(auth()->user()->isAdmin())
                            <!-- Admin Panel Badge -->
                            <a href="{{ route('admin.dashboard') }}" class="hidden xl:inline-flex min-h-11 items-center gap-1.5 px-3 py-1.5 rounded-xl bg-violet-50 border border-violet-200 text-violet-700 text-xs font-semibold hover:bg-violet-100 transition">
                                <i class="fa-solid fa-shield-halved text-violet-600" aria-hidden="true"></i>
                                <span>Admin</span>
                            </a>
                        @endif

                        <a href="{{ route('nostalgia.studio') }}" class="inline-flex min-h-11 items-center gap-1.5 px-3 py-1.5 rounded-xl bg-pink-50 border border-pink-200 text-pink-700 text-xs font-bold hover:bg-pink-100 transition">
                            <i class="fa-solid fa-clapperboard text-pink-500" aria-hidden="true"></i>
                            <span class="hidden md:inline">90s Song Studio</span>
                        </a>

                        <a href="{{ route('projects.create') }}" aria-label="Create a new short" class="inline-flex min-h-11 items-center gap-2 bg-brand-600 hover:bg-brand-500 text-white text-sm font-semibold px-3 sm:px-4 py-2 rounded-xl shadow-lg shadow-brand-500/20 transition hover:shadow-brand-500/40">
                            <i class="fa-solid fa-wand-magic-sparkles" aria-hidden="true"></i>
                            <span class="hidden sm:inline">New Short</span>
                        </a>

                        <a href="{{ route('dashboard') }}" aria-label="Dashboard" class="text-studio-muted hover:text-studio-ink px-2 sm:px-3 py-2 rounded-lg text-sm font-medium transition hover:bg-studio-lavender/50">
                            <i class="fa-solid fa-table-columns sm:mr-1"></i>
                            <span class="hidden sm:inline">Dashboard</span>
                        </a>

                        <!-- Notification bell -->
                        <a href="{{ route('notifications.index') }}" class="relative text-studio-muted hover:text-studio-ink p-2 rounded-lg transition hover:bg-studio-lavender/50" aria-label="Notifications">
                            <i class="fa-solid fa-bell text-lg"></i>
                            @if(auth()->user()->unreadNotificationsCount() > 0)
                                <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-aqua-500 rounded-full animate-ping"></span>
                                <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-aqua-500 rounded-full"></span>
                            @endif
                        </a>

                        <!-- User Profile & Logout -->
                        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                            <button @click="open = !open" class="flex items-center gap-2 text-sm text-studio-ink hover:text-brand-600 bg-white/85 border border-studio-line px-3 py-1.5 rounded-xl transition">
                                <div class="w-6 h-6 rounded-full bg-brand-600 flex items-center justify-center text-xs font-bold text-white">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <span class="hidden md:inline font-medium">{{ auth()->user()->name }}</span>
                                <i class="fa-solid fa-chevron-down text-xs text-studio-muted"></i>
                            </button>

                            <div x-cloak x-show="open" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 class="absolute right-0 mt-2 w-48 bg-white border border-studio-line rounded-xl shadow-xl py-1 z-50">
                                <div class="px-4 py-2 border-b border-studio-line text-xs text-studio-muted">
                                    Signed in as <br><span class="font-semibold text-studio-ink truncate block">{{ auth()->user()->email }}</span>
                                </div>
                                <a href="{{ route('wallet') }}" class="px-4 py-2 text-sm text-studio-ink hover:bg-amber-50 flex items-center gap-2">
                                    <i class="fa-solid fa-wallet text-amber-600" aria-hidden="true"></i>
                                    Token Wallet ({{ auth()->user()->tokens }})
                                </a>
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 text-sm text-violet-700 hover:bg-violet-50 flex items-center gap-2">
                                        <i class="fa-solid fa-shield-halved text-violet-600" aria-hidden="true"></i>
                                        Admin Panel
                                    </a>
                                @endif
                                <div class="border-t border-studio-line my-1"></div>
                                <form method="POST" action="{{ route('logout') }}" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-rose-700 hover:bg-rose-50 hover:text-rose-800 flex items-center gap-2">
                                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-studio-ink hover:text-brand-600 text-xs sm:text-sm font-semibold px-2 sm:px-4 py-2 rounded-xl hover:bg-studio-lavender/50 transition">
                            Sign In
                        </a>
                        <a href="{{ route('register') }}" class="bg-[#FF0000] hover:bg-[#CC0000] text-white text-xs sm:text-sm font-bold px-4 py-2.5 rounded-xl shadow-lg shadow-red-600/30 transition transform hover:-translate-y-0.5 flex items-center gap-1.5">
                            <span>Get Started Free</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Alerts -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div class="flex items-center justify-between p-4 mb-4 text-sm rounded-xl border shadow-lg text-emerald-800 bg-emerald-50 border-emerald-200">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-lg text-emerald-600"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="flex items-center justify-between p-4 mb-4 text-sm rounded-xl border shadow-lg text-rose-800 bg-rose-50 border-rose-200">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-triangle-exclamation text-lg text-rose-600"></i>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @if(session('info'))
            <div class="flex items-center justify-between p-4 mb-4 text-sm rounded-xl border shadow-lg text-brand-700 bg-brand-200/60 border-brand-300/70">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-circle-info text-lg text-brand-600"></i>
                    <span>{{ session('info') }}</span>
                </div>
            </div>
        @endif
    </div>

    <!-- Main Content Area -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="border-t py-6 text-center text-xs border-studio-line bg-white/55 text-studio-muted">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="font-bold text-studio-ink">AI Shorts Creator</span>
                <span>Find the moments worth sharing.</span>
            </div>
            <div class="flex items-center gap-5">
                <a href="{{ url('/') }}#how-it-works" class="hover:text-brand-600 transition">How it works</a>
                <a href="{{ url('/') }}#faq" class="hover:text-brand-600 transition">FAQ</a>
            </div>
        </div>
    </footer>

    </div><!-- /#app-main-content -->

    @stack('scripts')
    <script>
        function openBookNow() {
            const stage = document.getElementById('book-opening-stage');
            if (!stage) return;
            stage.classList.add('book-opening-fast');
            setTimeout(() => { if (stage) stage.remove(); }, 400);
        }
        setTimeout(() => {
            const stage = document.getElementById('book-opening-stage');
            if (stage) stage.remove();
        }, 4800);
    </script>
</body>
</html>
