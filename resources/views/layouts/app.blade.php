<!DOCTYPE html>
<html lang="en" class="h-full bg-[#F4F7FB] text-[#152238]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'AI Shorts Creator'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="{{ asset('js/alpine.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/all.min.css') }}">
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="min-h-screen flex flex-col antialiased bg-[#F4F7FB] text-[#152238] selection:bg-[#DDE2FF] selection:text-[#152238]" x-data="{ toast: { show: false, message: '' } }">
    <div x-cloak x-show="toast.show"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-2"
         class="fixed bottom-5 right-5 z-50 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-white px-5 py-3 text-sm font-semibold text-[#152238] shadow-xl">
        <i class="fa-solid fa-circle-check text-emerald-500" aria-hidden="true"></i>
        <span x-text="toast.message"></span>
    </div>

    <nav class="sticky top-0 z-40 border-b border-[#DDE4F0] bg-[#F4F7FB]/90 backdrop-blur-2xl" aria-label="Main navigation">
        <div class="mx-auto flex h-[72px] max-w-7xl items-center justify-between gap-2 px-4 sm:gap-4 sm:px-6 lg:px-8">
            <a href="{{ url('/') }}" class="group inline-flex shrink-0 items-center gap-2 sm:gap-2.5" aria-label="AI Shorts Creator home">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-[#4D5CFF] text-white shadow-[0_7px_18px_rgba(77,92,255,.22)] transition-transform group-hover:-rotate-6 sm:h-9 sm:w-9">
                    <i class="fa-solid fa-play text-sm" aria-hidden="true"></i>
                </span>
                <span class="font-['Space_Grotesk'] text-sm font-bold tracking-[-.04em] text-[#152238] sm:text-lg"><span class="sm:hidden">Shorts</span><span class="hidden sm:inline">AI Shorts Creator</span></span>
            </a>

            @guest
                <div class="hidden items-center gap-8 text-sm font-semibold text-[#526078] lg:flex">
                    <a href="{{ url('/') }}#how-it-works" class="transition-colors hover:text-[#4D5CFF]">How it works</a>
                    <a href="{{ url('/') }}#demo-shorts" class="transition-colors hover:text-[#4D5CFF]">Showcase</a>
                    <a href="{{ url('/') }}#pricing" class="transition-colors hover:text-[#4D5CFF]">Pricing</a>
                </div>
            @endguest

            <div class="flex items-center gap-2 sm:gap-3">
                @auth
                    <a href="{{ route('wallet') }}" class="hidden items-center gap-2 rounded-full border border-[#D8DFFF] bg-white px-3 py-2 text-xs font-bold text-[#4251CE] transition-colors hover:bg-[#EEF0FF] sm:inline-flex">
                        <i class="fa-solid fa-coins" aria-hidden="true"></i>
                        {{ number_format(auth()->user()->tokens) }} credits
                    </a>
                    <a href="{{ route('dashboard') }}" class="hidden rounded-xl px-3 py-2 text-sm font-semibold text-[#526078] transition-colors hover:bg-[#E9ECF8] hover:text-[#152238] md:inline-flex">Dashboard</a>
                    <a href="{{ route('notifications.index') }}" class="relative inline-flex h-10 w-10 items-center justify-center rounded-xl text-[#526078] transition-colors hover:bg-[#E9ECF8]" aria-label="Notifications">
                        <i class="fa-regular fa-bell" aria-hidden="true"></i>
                        @if(auth()->user()->unreadNotificationsCount() > 0)
                            <span class="absolute right-2 top-2 h-2 w-2 rounded-full bg-[#FF735B]"></span>
                        @endif
                    </a>
                    <a href="{{ route('projects.create') }}" class="inline-flex min-h-10 items-center justify-center rounded-xl bg-[#4D5CFF] px-3.5 py-2 text-xs font-bold text-white shadow-[0_8px_18px_rgba(77,92,255,.2)] transition-colors hover:bg-[#3949E9] sm:px-4 sm:text-sm">
                        <i class="fa-solid fa-plus sm:mr-2" aria-hidden="true"></i><span class="hidden sm:inline">Create shorts</span>
                    </a>
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button type="button" @click="open = !open" :aria-expanded="open.toString()" aria-label="Account menu"
                                class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-[#DDE4F0] bg-white font-bold text-[#4251CE] transition-colors hover:border-[#AAB7F7]">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </button>
                        <div x-cloak x-show="open" x-transition
                             class="absolute right-0 z-50 mt-3 w-56 rounded-2xl border border-[#DDE4F0] bg-white p-2 shadow-[0_20px_50px_rgba(21,34,56,.14)]">
                            <div class="border-b border-[#E8ECF3] px-3 py-2.5 text-xs text-[#718096]">
                                Signed in as <span class="mt-1 block truncate font-semibold text-[#152238]">{{ auth()->user()->email }}</span>
                            </div>
                            <a href="{{ route('dashboard') }}" class="mt-1 flex items-center gap-2 rounded-xl px-3 py-2 text-sm font-medium text-[#152238] hover:bg-[#F1F3FF]"><i class="fa-solid fa-table-columns w-4 text-[#4D5CFF]" aria-hidden="true"></i>Dashboard</a>
                            <a href="{{ route('wallet') }}" class="flex items-center gap-2 rounded-xl px-3 py-2 text-sm font-medium text-[#152238] hover:bg-[#F1F3FF]"><i class="fa-solid fa-coins w-4 text-[#4D5CFF]" aria-hidden="true"></i>Credit wallet</a>
                            <a href="{{ route('nostalgia.studio') }}" class="flex items-center gap-2 rounded-xl px-3 py-2 text-sm font-medium text-[#152238] hover:bg-[#F1F3FF]"><i class="fa-solid fa-clapperboard w-4 text-[#FF735B]" aria-hidden="true"></i>90s studio</a>
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 rounded-xl px-3 py-2 text-sm font-medium text-[#152238] hover:bg-[#F1F3FF]"><i class="fa-solid fa-shield-halved w-4 text-[#4D5CFF]" aria-hidden="true"></i>Admin panel</a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" class="mt-1 border-t border-[#E8ECF3] pt-1">
                                @csrf
                                <button type="submit" class="flex w-full items-center gap-2 rounded-xl px-3 py-2 text-left text-sm font-medium text-[#D64C43] hover:bg-[#FFF1EE]"><i class="fa-solid fa-arrow-right-from-bracket w-4" aria-hidden="true"></i>Sign out</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="inline-flex rounded-xl px-2 py-2 text-xs font-semibold text-[#526078] transition-colors hover:text-[#152238] sm:px-3 sm:text-sm">Sign in</a>
                    <a href="{{ route('register') }}" class="inline-flex min-h-10 items-center justify-center rounded-xl bg-[#4D5CFF] px-3 py-2 text-xs font-bold text-white shadow-[0_8px_18px_rgba(77,92,255,.2)] transition-colors hover:bg-[#3949E9] sm:px-4 sm:text-sm"><span class="sm:hidden">Start free</span><span class="hidden sm:inline">Get started free</span><i class="fa-solid fa-arrow-right ml-2 hidden text-[11px] sm:inline" aria-hidden="true"></i></a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="mx-auto mt-4 w-full max-w-7xl space-y-3 px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div role="status" class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-[#F0FBF4] px-4 py-3 text-sm font-medium text-[#1B6545]"><i class="fa-solid fa-circle-check mt-0.5" aria-hidden="true"></i>{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div role="alert" class="flex items-start gap-3 rounded-2xl border border-[#FFD5CE] bg-[#FFF3F0] px-4 py-3 text-sm font-medium text-[#A84339]"><i class="fa-solid fa-triangle-exclamation mt-0.5" aria-hidden="true"></i>{{ session('error') }}</div>
        @endif
        @if(session('info'))
            <div role="status" class="flex items-start gap-3 rounded-2xl border border-[#D8DFFF] bg-[#F1F3FF] px-4 py-3 text-sm font-medium text-[#4251CE]"><i class="fa-solid fa-circle-info mt-0.5" aria-hidden="true"></i>{{ session('info') }}</div>
        @endif
    </div>

    <main class="w-full flex-1 {{ request()->routeIs('home') ? '' : 'mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8' }}">
        @yield('content')
    </main>

    <footer class="mt-16 border-t border-[#DDE4F0] bg-white/70 py-9 text-sm text-[#687684]">
        <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-5 px-4 text-center sm:px-6 lg:flex-row lg:px-8 lg:text-left">
            <div class="flex items-center gap-2">
                <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-[#4D5CFF] text-white"><i class="fa-solid fa-play text-[10px]" aria-hidden="true"></i></span>
                <span class="font-['Space_Grotesk'] font-bold text-[#152238]">AI Shorts Creator</span>
                <span class="hidden text-[#A0AABD] sm:inline">/</span>
                <span class="hidden sm:inline">Find the moments worth sharing.</span>
            </div>
            <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 font-medium">
                <a href="{{ url('/') }}#how-it-works" class="hover:text-[#4D5CFF]">How it works</a>
                <a href="{{ url('/') }}#demo-shorts" class="hover:text-[#4D5CFF]">Showcase</a>
                <a href="{{ url('/') }}#pricing" class="hover:text-[#4D5CFF]">Pricing</a>
                <a href="{{ route('nostalgia.studio') }}" class="hover:text-[#4D5CFF]">90s studio</a>
            </div>
        </div>
        <div class="mx-auto mt-6 max-w-7xl px-4 text-center text-xs text-[#8A96A8] sm:px-6 lg:px-8">&copy; {{ date('Y') }} AI Shorts Creator.</div>
    </footer>

    @stack('scripts')
</body>
</html>
