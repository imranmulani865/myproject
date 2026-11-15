<!DOCTYPE html>
<html lang="en" class="h-full bg-studio-canvas text-studio-ink">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'AI Shorts Creator') }} - Long Video to Viral Shorts</title>
    <script>
        (() => {
            const root = document.documentElement;
            root.dataset.door = 'closed';

            let doorStarted = false;

            function startGrandDoorSequence() {
                if (doorStarted) return;
                doorStarted = true;

                const portal = document.getElementById('grand-door-portal');
                if (!portal) return;

                // Ensure visible
                portal.style.display = 'flex';
                portal.style.opacity = '1';

                // Phase 1: Keep doors closed for 700ms so user clearly sees the grand doors & lock
                setTimeout(() => {
                    portal.classList.add('doors-unlocking');
                    const hudText = portal.querySelector('.hud-status-text');
                    if (hudText) {
                        hudText.textContent = 'ACCESS GRANTED • OPENING GATES';
                    }
                }, 700);

                // Phase 2: Swing doors open wide in 3D
                setTimeout(() => {
                    portal.classList.add('doors-open');
                    root.dataset.door = 'opened';
                }, 1200);

                // Phase 3: Fade out portal overlay
                setTimeout(() => {
                    portal.classList.add('doors-hidden');
                }, 2900);

                // Phase 4: Completely remove portal overlay from document flow
                setTimeout(() => {
                    portal.style.display = 'none';
                    portal.style.pointerEvents = 'none';
                }, 3700);
            }

            if (document.readyState === 'complete') {
                setTimeout(startGrandDoorSequence, 100);
            } else {
                window.addEventListener('load', () => {
                    setTimeout(startGrandDoorSequence, 150);
                });
                // Reliable fallback: in case external CDNs/fonts delay window.load
                setTimeout(startGrandDoorSequence, 1200);
            }
        })();
    </script>
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
           3D GRAND VAULT / CINEMATIC DOUBLE DOOR OPENING
           =================================================== */
        .door-portal-wrap {
            position: fixed !important;
            inset: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            z-index: 99999999 !important;
            background: transparent !important;
            overflow: hidden !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            perspective: 1200px !important;
            perspective-origin: center center !important;
            pointer-events: all;
            transition: opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.8s;
        }

        .door-portal-wrap.doors-hidden {
            opacity: 0 !important;
            visibility: hidden !important;
            pointer-events: none !important;
        }

        /* 3D Perspective Stage */
        .door-perspective-stage {
            position: absolute;
            inset: 0;
            width: 100vw;
            height: 100vh;
            perspective: 1200px;
            perspective-origin: center center;
            transform-style: preserve-3d;
            pointer-events: none;
        }

        /* Left & Right Door Leaves */
        .door-leaf {
            position: absolute;
            top: 0;
            width: 50.2vw;
            height: 100vh;
            transform-style: preserve-3d;
            will-change: transform;
            transition: transform 1.6s cubic-bezier(0.25, 1, 0.35, 1);
            box-shadow: 0 0 90px rgba(0, 0, 0, 0.98);
        }

        .door-leaf-left {
            left: 0;
            transform-origin: left center;
            transform: rotateY(0deg);
        }

        .door-leaf-right {
            right: 0;
            transform-origin: right center;
            transform: rotateY(0deg);
        }

        /* Swing Open 3D States */
        #grand-door-portal.doors-open .door-leaf-left {
            transform: rotateY(-105deg);
        }

        #grand-door-portal.doors-open .door-leaf-right {
            transform: rotateY(105deg);
        }

        /* Door Panel Faces with Obsidian Metallic Finish */
        .door-panel-face {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #070a12 0%, #0d1527 35%, #080c17 100%);
            border-top: 3px solid rgba(99, 102, 241, 0.45);
            border-bottom: 3px solid rgba(99, 102, 241, 0.45);
            overflow: hidden;
        }

        .door-leaf-left .door-panel-face {
            border-right: 2px solid rgba(129, 140, 248, 0.9);
            box-shadow: inset -24px 0 50px rgba(0, 0, 0, 0.85), inset 0 0 60px rgba(99, 102, 241, 0.2);
        }

        .door-leaf-right .door-panel-face {
            border-left: 2px solid rgba(129, 140, 248, 0.9);
            box-shadow: inset 24px 0 50px rgba(0, 0, 0, 0.85), inset 0 0 60px rgba(99, 102, 241, 0.2);
        }

        /* Futuristic Carbon Cyber Grid Texture */
        .door-metal-grid {
            position: absolute;
            inset: 0;
            background-image: 
                radial-gradient(circle at 50% 50%, rgba(99, 102, 241, 0.12) 0%, transparent 70%),
                linear-gradient(rgba(255, 255, 255, 0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.04) 1px, transparent 1px);
            background-size: 100% 100%, 40px 40px, 40px 40px;
            opacity: 0.95;
        }

        /* Inset Heavy Metal Structural Boxes */
        .door-inset-box {
            position: absolute;
            left: 8%;
            right: 8%;
            border: 1px solid rgba(148, 163, 184, 0.18);
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.45) 0%, rgba(15, 23, 42, 0.75) 100%);
            border-radius: 12px;
            box-shadow: inset 0 2px 4px rgba(255, 255, 255, 0.06), 0 10px 32px rgba(0, 0, 0, 0.6);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 20px;
        }

        .door-inset-box.box-top { top: 7%; height: 22%; }
        .door-inset-box.box-mid { top: 35%; height: 28%; }
        .door-inset-box.box-btm { bottom: 7%; height: 22%; }

        .box-tag {
            font-family: ui-monospace, monospace;
            font-size: 11px;
            font-weight: 800;
            color: rgba(165, 180, 252, 0.85);
            letter-spacing: 0.2em;
        }

        .tech-lines {
            display: flex;
            flex-direction: column;
            gap: 7px;
            width: 60%;
        }

        .tech-lines span {
            display: block;
            height: 2px;
            background: rgba(99, 102, 241, 0.35);
            border-radius: 2px;
        }
        .tech-lines span:nth-child(2) { width: 80%; background: rgba(168, 85, 247, 0.4); }
        .tech-lines span:nth-child(3) { width: 45%; }

        /* Neon Laser Track running vertically near the center */
        .door-energy-track {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(to bottom, transparent 0%, #6366f1 20%, #a855f7 50%, #ec4899 80%, transparent 100%);
            box-shadow: 0 0 14px #6366f1, 0 0 28px #a855f7;
        }

        .door-leaf-left .door-energy-track { right: 28px; }
        .door-leaf-right .door-energy-track { left: 28px; }

        /* Industrial Strut Handles */
        .door-grip-column {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 38%;
            background: linear-gradient(90deg, #1e293b 0%, #475569 50%, #0f172a 100%);
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.9);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            z-index: 5;
        }

        .door-leaf-left .door-grip-column { right: 52px; }
        .door-leaf-right .door-grip-column { left: 52px; }

        .grip-rivet {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #94a3b8;
            box-shadow: inset 0 1px 2px #fff;
        }

        .grip-core {
            width: 2px;
            height: 72%;
            background: #6366f1;
            box-shadow: 0 0 10px #6366f1;
        }

        /* Central Circular Vault Lock (Split perfectly across both doors) */
        .door-lock-half {
            position: absolute;
            top: 50%;
            width: 80px;
            height: 160px;
            margin-top: -80px;
            overflow: hidden;
            pointer-events: none;
            z-index: 25;
        }

        .lock-half-left { right: 0; }
        .lock-half-right { left: 0; }

        /* The full circle rendered in overflow position */
        .lock-circle-full {
            position: absolute;
            top: 0;
            width: 160px;
            height: 160px;
            border-radius: 50%;
            background: radial-gradient(circle at center, #1e1b4b 0%, #0f172a 65%, #020617 100%);
            border: 3px solid rgba(129, 140, 248, 0.9);
            box-shadow: 0 0 40px rgba(99, 102, 241, 0.75), inset 0 0 30px rgba(99, 102, 241, 0.55);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .lock-half-left .lock-circle-full { left: 0; }
        .lock-half-right .lock-circle-full { left: -80px; }

        .lock-ring-dashed {
            position: absolute;
            inset: 10px;
            border: 2px dashed rgba(165, 180, 252, 0.6);
            border-radius: 50%;
            animation: ring-rotate 14s linear infinite;
        }

        @keyframes ring-rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .lock-center-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            filter: drop-shadow(0 0 14px #818cf8);
            animation: core-glow 2s infinite ease-in-out;
        }

        @keyframes core-glow {
            0%, 100% { transform: scale(1); filter: drop-shadow(0 0 10px #818cf8); }
            50% { transform: scale(1.12); filter: drop-shadow(0 0 26px #c084fc); }
        }

        /* Center Light Beam Burst */
        .door-light-beam {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 50%;
            width: 3px;
            transform: translateX(-50%);
            background: linear-gradient(180deg, #818cf8 0%, #ffffff 50%, #c084fc 100%);
            box-shadow: 0 0 25px 10px rgba(129, 140, 248, 0.85), 0 0 60px 25px rgba(192, 132, 252, 0.5);
            opacity: 0.9;
            z-index: 30;
            pointer-events: none;
            transition: opacity 0.5s ease, width 0.7s cubic-bezier(0.2, 0.8, 0.2, 1);
        }

        #grand-door-portal.doors-unlocking .door-light-beam {
            opacity: 1;
            width: 14px;
            box-shadow: 0 0 45px 25px rgba(255, 255, 255, 0.98), 0 0 120px 60px rgba(129, 140, 248, 0.85), 0 0 220px 110px rgba(192, 132, 252, 0.7);
        }

        #grand-door-portal.doors-open .door-light-beam {
            opacity: 0;
            width: 100vw;
            transition: opacity 0.7s ease 0.3s, width 0.9s ease;
        }

        /* Center HUD Status Badge */
        .door-hud-badge {
            position: absolute;
            bottom: 10%;
            left: 50%;
            transform: translateX(-50%);
            z-index: 40;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            background: rgba(15, 23, 42, 0.92);
            backdrop-filter: blur(16px);
            padding: 10px 28px;
            border-radius: 999px;
            border: 1px solid rgba(129, 140, 248, 0.5);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.85), 0 0 30px rgba(99, 102, 241, 0.4);
            pointer-events: none;
            transition: opacity 0.4s ease, transform 0.4s ease;
        }

        #grand-door-portal.doors-open .door-hud-badge {
            opacity: 0;
            transform: translateX(-50%) scale(0.85);
        }

        .hud-brand-title {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: #a5b4fc;
        }

        .hud-status-indicator {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 700;
            color: #ffffff;
            font-family: ui-monospace, monospace;
        }

        .hud-status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #34d399;
            box-shadow: 0 0 10px #34d399;
            animation: dot-pulse 1s infinite alternate;
        }

        @keyframes dot-pulse {
            from { opacity: 0.3; }
            to { opacity: 1; transform: scale(1.25); }
        }

        /* Page Content Smooth Unveil Zoom */
        #app-main-content {
            will-change: transform, opacity, filter;
            transition: transform 1.5s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.9s ease, filter 0.9s ease;
        }

        html[data-door='closed'] #app-main-content {
            transform: scale(0.94);
            opacity: 0.35;
            filter: blur(8px);
        }

        html[data-door='opened'] #app-main-content {
            transform: scale(1);
            opacity: 1;
            filter: blur(0);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col antialiased bg-studio-canvas text-studio-ink selection:bg-studio-lavender selection:text-studio-ink" x-data="{ toast: { show: false, message: '' } }">
    <!-- 3D GRAND VAULT DOUBLE DOOR PORTAL -->
    <div id="grand-door-portal" class="door-portal-wrap" role="dialog" aria-modal="true" aria-label="Grand Portal Entry">
        <div class="door-light-beam"></div>

        <div class="door-perspective-stage">
            <!-- LEFT DOOR PANEL -->
            <div class="door-leaf door-leaf-left">
                <div class="door-panel-face">
                    <div class="door-metal-grid"></div>
                    <div class="door-inset-box box-top">
                        <span class="box-tag">SYS // ALPHA</span>
                    </div>
                    <div class="door-inset-box box-mid">
                        <div class="tech-lines">
                            <span></span><span></span><span></span>
                        </div>
                    </div>
                    <div class="door-inset-box box-btm">
                        <span class="box-tag">PORTAL // GATE 01</span>
                    </div>

                    <div class="door-energy-track"></div>

                    <div class="door-grip-column">
                        <span class="grip-rivet"></span>
                        <span class="grip-core"></span>
                        <span class="grip-rivet"></span>
                    </div>

                    <!-- Left Half of Central Lock -->
                    <div class="door-lock-half lock-half-left">
                        <div class="lock-circle-full">
                            <div class="lock-ring-dashed"></div>
                            <div class="lock-center-icon">
                                <svg class="lock-svg-bolt" viewBox="0 0 24 24" width="42" height="42">
                                    <path fill="#818cf8" stroke="#ffffff" stroke-width="0.8" d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT DOOR PANEL -->
            <div class="door-leaf door-leaf-right">
                <div class="door-panel-face">
                    <div class="door-metal-grid"></div>
                    <div class="door-inset-box box-top">
                        <span class="box-tag">VER // 2.4</span>
                    </div>
                    <div class="door-inset-box box-mid">
                        <div class="tech-lines">
                            <span></span><span></span><span></span>
                        </div>
                    </div>
                    <div class="door-inset-box box-btm">
                        <span class="box-tag">GEMMA // AI CORE</span>
                    </div>

                    <div class="door-energy-track"></div>

                    <div class="door-grip-column">
                        <span class="grip-rivet"></span>
                        <span class="grip-core"></span>
                        <span class="grip-rivet"></span>
                    </div>

                    <!-- Right Half of Central Lock -->
                    <div class="door-lock-half lock-half-right">
                        <div class="lock-circle-full">
                            <div class="lock-ring-dashed"></div>
                            <div class="lock-center-icon">
                                <svg class="lock-svg-bolt" viewBox="0 0 24 24" width="42" height="42">
                                    <path fill="#818cf8" stroke="#ffffff" stroke-width="0.8" d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Center HUD Badge -->
        <div class="door-hud-badge">
            <div class="hud-brand-title">
                <i class="fa-solid fa-shield-halved mr-1.5 text-indigo-400"></i>
                <span>AI SHORTS STUDIO</span>
            </div>
            <div class="hud-status-indicator">
                <span class="hud-status-dot"></span>
                <span class="hud-status-text">INITIALIZING QUANTUM GATE...</span>
            </div>
        </div>
    </div>

    <!-- MAIN PAGE CONTENT WRAPPER -->
    <div id="app-main-content" class="min-h-screen flex flex-col w-full">
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
    <nav class="sticky top-0 z-40 backdrop-blur-xl border-b bg-white/80 border-studio-line/80 shadow-[0_12px_32px_-24px_rgba(48,40,166,0.3)]">
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
                        <a href="{{ route('register') }}" class="bg-brand-600 hover:bg-brand-500 text-white text-xs sm:text-sm font-semibold px-3 sm:px-4 py-2 rounded-xl shadow-lg shadow-brand-600/25 transition">
                            Try Free
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
</body>
</html>
