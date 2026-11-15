@extends('layouts.app')

@section('content')
<section class="relative mx-auto max-w-6xl py-5 sm:py-10 lg:py-14" aria-labelledby="login-title">
    <div class="grid overflow-hidden rounded-[2rem] border border-studio-line bg-studio-surface/90 shadow-[0_24px_90px_rgba(48,40,166,0.12)] backdrop-blur-xl lg:grid-cols-2">
        <div class="p-6 sm:p-10 lg:p-14">
            <div class="mb-10 flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-studio-lavender text-brand-600">
                    <i class="fa-solid fa-clapperboard" aria-hidden="true"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-studio-ink">Creator workspace</p>
                    <p class="text-xs text-studio-muted">AI Shorts Creator</p>
                </div>
            </div>

            <h1 id="login-title" class="max-w-md text-3xl font-bold leading-tight text-studio-ink sm:text-4xl">Welcome back to the studio.</h1>
            <p class="mt-3 max-w-md text-sm leading-relaxed text-studio-muted sm:text-base">Sign in to manage your video projects and pick up where you left off.</p>

            <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
                @csrf

                <div>
                    <label for="email" class="mb-2 block text-sm font-semibold text-studio-ink">Email address</label>
                    <div class="relative">
                        <i class="fa-regular fa-envelope pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-studio-muted" aria-hidden="true"></i>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                               aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                               @error('email') aria-describedby="login-email-error" @enderror
                               class="min-h-12 w-full rounded-xl border border-studio-line bg-studio-canvas/70 py-3 pl-11 pr-4 text-sm text-studio-ink placeholder:text-studio-muted focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-400/30"
                               placeholder="you@example.com">
                    </div>
                    @error('email')
                        <p id="login-email-error" class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-semibold text-studio-ink">Password</label>
                    <div class="relative">
                        <i class="fa-solid fa-lock pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-studio-muted" aria-hidden="true"></i>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                               aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                               @error('password') aria-describedby="login-password-error" @enderror
                               class="min-h-12 w-full rounded-xl border border-studio-line bg-studio-canvas/70 py-3 pl-11 pr-4 text-sm text-studio-ink placeholder:text-studio-muted focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-400/30"
                               placeholder="Enter your password">
                    </div>
                    @error('password')
                        <p id="login-password-error" class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <label class="inline-flex min-h-11 items-center gap-3 text-sm text-studio-muted">
                    <input type="checkbox" name="remember" class="h-4 w-4 rounded border-studio-line bg-studio-surface text-brand-600 focus:ring-brand-400">
                    <span>Remember me</span>
                </label>

                <button type="submit" class="min-h-12 w-full rounded-xl bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-500 hover:to-indigo-500 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-brand-600/30 transition-all transform hover:-translate-y-0.5 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600 flex items-center justify-center gap-2">
                    <span>Sign in to studio</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </form>

            <p class="mt-8 border-t border-studio-line pt-6 text-sm text-studio-muted">
                New here?
                <a href="{{ route('register') }}" class="ml-1 font-semibold text-brand-600 underline-offset-4 hover:text-brand-700 hover:underline focus-visible:underline">Create an account</a>
            </p>
        </div>

        <aside class="relative hidden overflow-hidden border-l border-studio-line bg-gradient-to-br from-studio-lavender/70 via-studio-surface to-studio-peach/70 p-12 lg:flex lg:flex-col lg:justify-between">
            <div class="relative">
                <p class="text-sm font-semibold text-brand-600">A better cut of your content</p>
                <h2 class="mt-4 max-w-sm text-3xl font-bold leading-tight text-studio-ink">Your next short starts here.</h2>
                <p class="mt-4 max-w-sm text-sm leading-relaxed text-studio-muted">Find the strongest moments, frame them vertically, and keep every project in one place.</p>
            </div>

            <div class="relative mx-auto my-10 h-72 w-full max-w-sm" aria-hidden="true">
                <div class="absolute left-0 top-16 w-64 -rotate-6 overflow-hidden rounded-2xl border border-studio-line bg-studio-surface shadow-xl">
                    <div class="flex aspect-video items-center justify-center bg-gradient-to-br from-studio-lavender via-studio-canvas to-studio-peach">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-brand-600 text-white shadow-lg shadow-brand-600/20"><i class="fa-solid fa-play text-xs"></i></div>
                    </div>
                    <div class="flex items-center justify-between px-4 py-3 text-xs text-studio-muted"><span>Long video</span><span>16:9</span></div>
                </div>
                <div class="absolute right-2 top-0 w-36 rotate-6 overflow-hidden rounded-[1.5rem] border-[5px] border-studio-surface bg-studio-ink shadow-2xl">
                    <div class="relative flex aspect-[9/16] items-center justify-center bg-gradient-to-b from-brand-400 via-brand-500 to-brand-700">
                        <div class="absolute left-2 top-3 rounded-md bg-studio-lime px-1.5 py-1 text-[9px] font-extrabold text-studio-ink">9:16 short</div>
                        <i class="fa-solid fa-play text-xl text-white/90"></i>
                        <div class="absolute inset-x-3 bottom-4 rounded-md bg-studio-ink/70 px-2 py-1 text-center text-[10px] font-semibold text-white">Ready for the feed</div>
                    </div>
                </div>
            </div>

            <div class="relative flex items-center gap-3 border-t border-studio-line pt-6 text-xs font-semibold text-studio-muted">
                <span>Find the hook</span><span class="h-px flex-1 bg-studio-line"></span><span>Frame the short</span><span class="h-px flex-1 bg-studio-line"></span><span>Export</span>
            </div>
        </aside>
    </div>
</section>
@endsection