@extends('layouts.app')

@section('content')
<section class="relative mx-auto max-w-6xl py-5 sm:py-10 lg:py-14" aria-labelledby="register-title">
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

            <h1 id="register-title" class="max-w-md text-3xl font-bold leading-tight text-studio-ink sm:text-4xl">Make room for your next idea.</h1>
            <p class="mt-3 max-w-md text-sm leading-relaxed text-studio-muted sm:text-base">Create an account to turn long videos into ready-to-share shorts.</p>

            <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-4">
                @csrf

                <div>
                    <label for="name" class="mb-2 block text-sm font-semibold text-studio-ink">Full name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                           aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}"
                           @error('name') aria-describedby="register-name-error" @enderror
                           class="min-h-12 w-full rounded-xl border border-studio-line bg-studio-canvas/70 px-4 py-3 text-sm text-studio-ink placeholder:text-studio-muted focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-400/30"
                           placeholder="Your name">
                    @error('name')
                        <p id="register-name-error" class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="mb-2 block text-sm font-semibold text-studio-ink">Email address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                           aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                           @error('email') aria-describedby="register-email-error" @enderror
                           class="min-h-12 w-full rounded-xl border border-studio-line bg-studio-canvas/70 px-4 py-3 text-sm text-studio-ink placeholder:text-studio-muted focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-400/30"
                           placeholder="you@example.com">
                    @error('email')
                        <p id="register-email-error" class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-semibold text-studio-ink">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                           aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                           aria-describedby="register-password-help @error('password') register-password-error @enderror"
                           class="min-h-12 w-full rounded-xl border border-studio-line bg-studio-canvas/70 px-4 py-3 text-sm text-studio-ink placeholder:text-studio-muted focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-400/30"
                           placeholder="Create a password">
                    <p id="register-password-help" class="mt-2 text-xs text-studio-muted">Use at least 6 characters.</p>
                    @error('password')
                        <p id="register-password-error" class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-studio-ink">Confirm password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                           class="min-h-12 w-full rounded-xl border border-studio-line bg-studio-canvas/70 px-4 py-3 text-sm text-studio-ink placeholder:text-studio-muted focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-400/30"
                           placeholder="Repeat your password">
                </div>

                <button type="submit" class="min-h-12 w-full rounded-xl bg-brand-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-brand-600/20 transition-colors hover:bg-brand-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600">
                    Create account
                </button>
            </form>

            <p class="mt-8 border-t border-studio-line pt-6 text-sm text-studio-muted">
                Already have an account?
                <a href="{{ route('login') }}" class="ml-1 font-semibold text-brand-600 underline-offset-4 hover:text-brand-700 hover:underline focus-visible:underline">Sign in</a>
            </p>
        </div>

        <aside class="relative hidden overflow-hidden border-l border-studio-line bg-gradient-to-br from-studio-lavender/70 via-studio-surface to-studio-peach/70 p-12 lg:flex lg:flex-col lg:justify-between">
            <div class="relative">
                <p class="text-sm font-semibold text-brand-600">Made for video creators</p>
                <h2 class="mt-4 max-w-sm text-3xl font-bold leading-tight text-studio-ink">More stories from every recording.</h2>
                <p class="mt-4 max-w-sm text-sm leading-relaxed text-studio-muted">Bring a YouTube link or your own footage. Find the strongest moments and build vertical clips in one workspace.</p>
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