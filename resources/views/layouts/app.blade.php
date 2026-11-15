<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'AI Shorts Creator') }} - Long Video to Viral Shorts</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        [x-cloak] { display: none !important; }
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }
    </style>
</head>
<body class="h-full flex flex-col bg-slate-950 text-slate-100 antialiased selection:bg-indigo-500 selection:text-white" x-data="{ toast: { show: false, message: '' } }">
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
    <nav class="sticky top-0 z-40 bg-slate-900/80 backdrop-blur-md border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand / Logo -->
                <div class="flex items-center gap-3">
                    <a href="{{ auth()->check() ? route('dashboard') : url('/') }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 via-purple-600 to-pink-500 flex items-center justify-center shadow-lg shadow-indigo-500/25 group-hover:scale-105 transition">
                            <i class="fa-solid fa-bolt text-white text-lg"></i>
                        </div>
                        <div>
                            <span class="text-lg font-bold bg-clip-text text-transparent bg-gradient-to-r from-white via-indigo-200 to-purple-300">
                                AI Shorts Creator
                            </span>
                            <span class="hidden sm:inline-block text-[10px] uppercase tracking-wider font-semibold px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 ml-2">
                                Ollama Gemma
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="flex items-center gap-3">
                    @auth
                        <!-- Token Wallet Pill -->
                        <a href="{{ route('wallet') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-300 text-xs font-semibold hover:bg-amber-500/20 transition group">
                            <i class="fa-solid fa-coins text-amber-400 group-hover:rotate-12 transition"></i>
                            <span>{{ auth()->user()->tokens }} <span class="hidden sm:inline">Tokens</span></span>
                        </a>

                        @if(auth()->user()->isAdmin())
                            <!-- Admin Panel Badge -->
                            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-purple-500/15 border border-purple-500/30 text-purple-300 text-xs font-semibold hover:bg-purple-500/25 transition">
                                <i class="fa-solid fa-shield-halved text-purple-400"></i>
                                <span class="hidden sm:inline">Admin</span>
                            </a>
                        @endif

                        <a href="{{ route('projects.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white text-sm font-semibold px-4 py-2 rounded-xl shadow-lg shadow-indigo-500/20 transition hover:shadow-indigo-500/40">
                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                            <span class="hidden sm:inline">New Short</span>
                        </a>

                        <a href="{{ route('dashboard') }}" class="text-slate-300 hover:text-white px-3 py-2 rounded-lg text-sm font-medium transition hover:bg-slate-800/60">
                            <i class="fa-solid fa-table-columns sm:mr-1"></i>
                            <span class="hidden sm:inline">Dashboard</span>
                        </a>

                        <!-- Notification bell -->
                        <a href="{{ route('notifications.index') }}" class="relative text-slate-300 hover:text-white p-2 rounded-lg transition hover:bg-slate-800/60">
                            <i class="fa-solid fa-bell text-lg"></i>
                            @if(auth()->user()->unreadNotificationsCount() > 0)
                                <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-pink-500 rounded-full animate-ping"></span>
                                <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-pink-500 rounded-full"></span>
                            @endif
                        </a>

                        <!-- User Profile & Logout -->
                        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                            <button @click="open = !open" class="flex items-center gap-2 text-sm text-slate-300 hover:text-white bg-slate-800/80 border border-slate-700/80 px-3 py-1.5 rounded-xl transition">
                                <div class="w-6 h-6 rounded-full bg-gradient-to-tr from-indigo-500 to-pink-500 flex items-center justify-center text-xs font-bold text-white">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <span class="hidden md:inline font-medium">{{ auth()->user()->name }}</span>
                                <i class="fa-solid fa-chevron-down text-xs text-slate-400"></i>
                            </button>

                            <div x-cloak x-show="open" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 class="absolute right-0 mt-2 w-48 bg-slate-900 border border-slate-800 rounded-xl shadow-xl py-1 z-50">
                                <div class="px-4 py-2 border-b border-slate-800 text-xs text-slate-400">
                                    Signed in as <br><span class="font-semibold text-slate-200 truncate block">{{ auth()->user()->email }}</span>
                                </div>
                                <a href="{{ route('wallet') }}" class="px-4 py-2 text-sm text-slate-300 hover:bg-slate-800 hover:text-white flex items-center gap-2">
                                    <i class="fa-solid fa-wallet text-amber-400"></i>
                                    Token Wallet ({{ auth()->user()->tokens }})
                                </a>
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 text-sm text-purple-300 hover:bg-purple-900/20 hover:text-purple-200 flex items-center gap-2">
                                        <i class="fa-solid fa-shield-halved text-purple-400"></i>
                                        Admin Panel
                                    </a>
                                @endif
                                <div class="border-t border-slate-800 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-rose-400 hover:bg-rose-500/10 hover:text-rose-300 flex items-center gap-2">
                                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-slate-300 hover:text-white text-sm font-medium px-4 py-2 rounded-xl hover:bg-slate-800 transition">
                            Sign In
                        </a>
                        <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium px-4 py-2 rounded-xl shadow-lg shadow-indigo-600/30 transition">
                            Get Started
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Alerts -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div class="flex items-center justify-between p-4 mb-4 text-sm text-emerald-300 rounded-xl bg-emerald-950/60 border border-emerald-800/80 shadow-lg">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-emerald-400 text-lg"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="flex items-center justify-between p-4 mb-4 text-sm text-rose-300 rounded-xl bg-rose-950/60 border border-rose-800/80 shadow-lg">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-triangle-exclamation text-rose-400 text-lg"></i>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @if(session('info'))
            <div class="flex items-center justify-between p-4 mb-4 text-sm text-indigo-300 rounded-xl bg-indigo-950/60 border border-indigo-800/80 shadow-lg">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-circle-info text-indigo-400 text-lg"></i>
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
    <footer class="border-t border-slate-900 bg-slate-950/80 py-6 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span>
                <span>Ollama Gemma Video AI Engine Ready</span>
            </div>
            <div>
                Built with Laravel, Python, FFmpeg & Ollama AI
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
