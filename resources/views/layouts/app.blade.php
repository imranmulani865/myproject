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
            --color-studio-canvas: #f5f7ff;
            --color-studio-surface: #ffffff;
            --color-studio-ink: #151b45;
            --color-studio-muted: #5f6a87;
            --color-studio-line: #dce3f2;
            --color-studio-lavender: #dde4ff;
            --color-studio-peach: #ffe8db;
            --color-studio-lime: #c8ff42;

            --color-brand-200: #e3e3ff;
            --color-brand-300: #b8b7f5;
            --color-brand-400: #807ce7;
            --color-brand-500: #4a42c6;
            --color-brand-600: #3028a6;
            --color-brand-700: #26207f;
            --color-brand-800: #1b175f;
            --color-brand-950: #121139;

            --color-ocean-200: #e6e4ff;
            --color-ocean-300: #d1ceff;
            --color-ocean-400: #a9a4f1;
            --color-ocean-500: #6b63c4;
            --color-ocean-600: #5149a6;
            --color-ocean-700: #3d377f;
            --color-ocean-950: #1a1942;

            --color-aqua-300: #efe4ff;
            --color-aqua-400: #cbb8f4;
            --color-aqua-500: #7765bd;
            --color-aqua-600: #5d4ea1;
            --color-aqua-700: #493c7f;
            --color-aqua-950: #241a45;
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
                            canvas: '#f5f7ff',
                            surface: '#ffffff',
                            ink: '#151b45',
                            muted: '#5f6a87',
                            line: '#dce3f2',
                            lavender: '#dde4ff',
                            peach: '#ffe8db',
                            lime: '#c8ff42',
                        },
                        slate: {
                            100: '#f1f7fa',
                            200: '#e3edf2',
                            300: '#d0dee5',
                            400: '#afc3ce',
                            500: '#829aa7',
                            600: '#637c8b',
                            700: '#3a5262',
                            800: '#203847',
                            900: '#102532',
                            950: '#071923',
                        },
                        brand: {
                            200: '#e3e3ff',
                            300: '#b8b7f5',
                            400: '#807ce7',
                            500: '#4a42c6',
                            600: '#3028a6',
                            700: '#26207f',
                            800: '#1b175f',
                            950: '#121139',
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
           3D BOOK / CREATOR FOLIO OPENING ANIMATION (3s Hold)
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

        /* Left Book Leaf (Holds for 3s then opens to left in 3D) */
        .book-leaf-left {
            position: absolute;
            top: 0;
            left: 0;
            width: 50.1vw;
            height: 100vh;
            transform-origin: left center;
            transform-style: preserve-3d;
            background: linear-gradient(135deg, #090d1a 0%, #111a30 45%, #080c18 100%);
            border-right: 2px solid rgba(129, 140, 248, 0.75);
            box-shadow: inset -25px 0 60px rgba(0, 0, 0, 0.9), 0 0 90px rgba(0, 0, 0, 0.95);
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 5vw;
            animation: book-swing-left 1.15s cubic-bezier(0.25, 1, 0.35, 1) 3.0s forwards;
            will-change: transform;
        }

        /* Right Book Leaf (Holds for 3s then opens to right in 3D) */
        .book-leaf-right {
            position: absolute;
            top: 0;
            right: 0;
            width: 50.1vw;
            height: 100vh;
            transform-origin: right center;
            transform-style: preserve-3d;
            background: linear-gradient(225deg, #090d1a 0%, #111a30 45%, #080c18 100%);
            border-left: 2px solid rgba(129, 140, 248, 0.75);
            box-shadow: inset 25px 0 60px rgba(0, 0, 0, 0.9), 0 0 90px rgba(0, 0, 0, 0.95);
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding-left: 5vw;
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

        /* Book Cover Panels & Borders */
        .book-cover-panel {
            width: min(85%, 360px);
            padding: 24px;
            background: rgba(15, 23, 42, 0.55);
            border-radius: 18px;
            border: 1px solid rgba(99, 102, 241, 0.3);
            box-shadow: inset 0 2px 4px rgba(255, 255, 255, 0.08), 0 10px 30px rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(10px);
        }

        .book-cover-border {
            border: 1px dashed rgba(165, 180, 252, 0.25);
            border-radius: 12px;
            padding: 20px 14px;
        }

        /* Center Spine Laser Glow during 3s hold and burst */
        .book-spine-glow {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 50%;
            width: 4px;
            transform: translateX(-50%);
            background: linear-gradient(180deg, transparent, #818cf8 30%, #ec4899 70%, transparent);
            box-shadow: 0 0 30px #818cf8, 0 0 60px #ec4899;
            z-index: 20;
            pointer-events: none;
            animation: spine-pulse-hold 4.2s ease-out forwards;
        }

        @keyframes spine-pulse-hold {
            0% { opacity: 0; transform: translateX(-50%) scaleY(0.1); }
            8% { opacity: 1; transform: translateX(-50%) scaleY(1); width: 4px; box-shadow: 0 0 25px #818cf8; }
            65% { opacity: 0.85; width: 4px; box-shadow: 0 0 20px #818cf8, 0 0 40px #ec4899; }
            72% { opacity: 1; width: 6px; box-shadow: 0 0 45px #818cf8, 0 0 90px #ec4899; } /* At 3s opening burst */
            92% { opacity: 0.7; width: 2px; }
            100% { opacity: 0; transform: translateX(-50%) scaleY(1); }
        }

        /* Center Circular Seal Split */
        .book-center-crest-half {
            position: absolute;
            top: 50%;
            width: 48px;
            height: 96px;
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

        .crest-circle {
            position: absolute;
            top: 0;
            width: 96px;
            height: 96px;
            border-radius: 50%;
            background: #0f172a;
            border: 2px solid #818cf8;
            box-shadow: 0 0 25px rgba(129, 140, 248, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .left-crest .crest-circle {
            right: 0;
        }

        .right-crest .crest-circle {
            left: 0;
        }

        /* Top Cyber Laser Sweeps as Book Opens */
        .opening-laser-bar {
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            width: 100%;
            z-index: 99999;
            pointer-events: none;
            background: linear-gradient(90deg, #4f46e5 0%, #a855f7 40%, #ec4899 75%, #06b6d4 100%);
            background-size: 200% 100%;
            box-shadow: 0 0 14px rgba(139, 92, 246, 0.85), 0 0 25px rgba(236, 72, 153, 0.5);
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
    <!-- 3D BOOK / FOLIO OPENING EFFECT (Holds 3s then swings open) -->
    <div id="book-opening-stage" class="book-opening-stage" onclick="openBookNow()" aria-hidden="true" title="Click to skip">
        <!-- Center Spine Light Beam -->
        <div class="book-spine-glow"></div>

        <!-- LEFT BOOK LEAF / COVER -->
        <div class="book-leaf-left">
            <div class="book-cover-panel">
                <div class="book-cover-border">
                    <div class="flex flex-col items-end gap-2 pr-2 text-right">
                        <div class="w-11 h-11 rounded-2xl bg-indigo-600/30 border border-indigo-400/50 flex items-center justify-center text-indigo-300 shadow-lg shadow-indigo-500/20">
                            <i class="fa-solid fa-clapperboard text-lg"></i>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-black tracking-wider text-white">AI SHORTS</h2>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-indigo-300">STUDIO FOLIO</span>
                    </div>
                </div>
            </div>
            <!-- Half Center Crest -->
            <div class="book-center-crest-half left-crest">
                <div class="crest-circle">
                    <i class="fa-solid fa-wand-magic-sparkles text-indigo-300 text-lg mr-6"></i>
                </div>
            </div>
        </div>

        <!-- RIGHT BOOK LEAF / COVER -->
        <div class="book-leaf-right">
            <!-- Half Center Crest -->
            <div class="book-center-crest-half right-crest">
                <div class="crest-circle">
                    <i class="fa-solid fa-bolt text-indigo-300 text-lg ml-6"></i>
                </div>
            </div>
            <div class="book-cover-panel">
                <div class="book-cover-border">
                    <div class="flex flex-col items-start gap-2 pl-2 text-left">
                        <span class="px-2 py-0.5 rounded-full bg-indigo-500/20 border border-indigo-400/40 text-indigo-300 text-[9px] font-bold tracking-widest uppercase">
                            2026 EDITION
                        </span>
                        <h2 class="text-xl sm:text-2xl font-black tracking-wider text-white">CREATOR</h2>
                        <p class="text-[11px] text-slate-400 font-medium">Long to Viral Shorts</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Interactive Skip Indicator -->
        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 z-30 pointer-events-auto">
            <button type="button" onclick="openBookNow()" class="px-4 py-1.5 rounded-full bg-slate-900/80 hover:bg-slate-800 text-white/90 text-xs font-semibold backdrop-blur border border-white/20 shadow-2xl transition transform hover:scale-105 cursor-pointer flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-indigo-400 animate-ping"></span>
                <span>Opening in 3s...</span>
                <span class="text-indigo-300 text-[11px]">| Click to Skip ➔</span>
            </button>
        </div>
    </div>
            <!-- Half Center Crest -->
            <div class="book-center-crest-half left-crest">
                <div class="crest-circle">
                    <i class="fa-solid fa-wand-magic-sparkles text-indigo-300 text-lg mr-6"></i>
                </div>
            </div>
        </div>

        <!-- RIGHT BOOK LEAF / COVER -->
        <div class="book-leaf-right">
            <!-- Half Center Crest -->
            <div class="book-center-crest-half right-crest">
                <div class="crest-circle">
                    <i class="fa-solid fa-bolt text-indigo-300 text-lg ml-6"></i>
                </div>
            </div>
            <div class="book-cover-panel">
                <div class="book-cover-border">
                    <div class="flex flex-col items-start gap-2 pl-2 text-left">
                        <span class="px-2 py-0.5 rounded-full bg-indigo-500/20 border border-indigo-400/40 text-indigo-300 text-[9px] font-bold tracking-widest uppercase">
                            2026 EDITION
                        </span>
                        <h2 class="text-xl sm:text-2xl font-black tracking-wider text-white">CREATOR</h2>
                        <p class="text-[11px] text-slate-400 font-medium">Long to Viral Shorts</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Cyber Neon Laser Opening Indicator -->
    <div class="opening-laser-bar" aria-hidden="true"></div>

    <!-- MAIN PAGE CONTENT WRAPPER WITH CINEMATIC REVEAL -->
    <div id="app-main-content" class="min-h-screen flex flex-col w-full relative site-opening-reveal">
        <!-- Ambient Glowing Background Lighting -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden -z-10 ambient-glow-bloom" aria-hidden="true">
            <div class="absolute -top-32 -left-32 w-[34rem] h-[34rem] bg-indigo-500/10 rounded-full blur-[110px] animate-pulse"></div>
            <div class="absolute top-1/4 -right-32 w-[30rem] h-[30rem] bg-pink-500/10 rounded-full blur-[110px]"></div>
            <div class="absolute bottom-10 left-1/4 w-[36rem] h-[36rem] bg-violet-500/10 rounded-full blur-[120px]"></div>
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
                        <div class="w-10 h-10 rounded-xl bg-brand-600 flex items-center justify-center shadow-lg shadow-brand-500/20 group-hover:scale-105 transition">
                            <i class="fa-solid fa-clapperboard text-white text-lg"></i>
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
                        <a href="{{ route('register') }}" class="bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-500 hover:to-indigo-500 text-white text-xs sm:text-sm font-bold px-4 py-2.5 rounded-xl shadow-lg shadow-brand-600/30 transition transform hover:-translate-y-0.5 flex items-center gap-1.5">
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
