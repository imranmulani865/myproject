@extends('layouts.app')

@section('title', 'Create an account • AI Shorts Creator')

@section('content')
<section class="mx-auto max-w-5xl py-6 sm:py-12" aria-labelledby="register-title">
    <div class="grid overflow-hidden rounded-[2rem] border border-[#DDE4F0] bg-white shadow-[0_24px_70px_rgba(21,34,56,.08)] lg:grid-cols-2">
        <div class="flex flex-col justify-between p-6 sm:p-10 lg:p-12">
            <div>
                <div class="mb-8 flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#4D5CFF] text-white"><i class="fa-solid fa-play text-sm" aria-hidden="true"></i></span>
                    <div>
                        <p class="font-['Space_Grotesk'] text-sm font-bold text-[#152238]">AI Shorts Creator</p>
                        <p class="text-xs text-[#687684]">Start your creator workspace</p>
                    </div>
                </div>
                <span class="inline-flex items-center gap-2 rounded-full bg-[#EEF0FF] px-3 py-1.5 text-xs font-bold text-[#4251CE]"><i class="fa-solid fa-sparkles" aria-hidden="true"></i> 20 free credits included</span>
                <h1 id="register-title" class="mt-5 font-['Space_Grotesk'] text-3xl font-bold tracking-[-.05em] text-[#152238] sm:text-4xl">Find your next great short.</h1>
                <p class="mt-3 text-sm leading-relaxed text-[#687684]">Create your account and start turning long videos into clips worth sharing.</p>

                <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-4">
                    @csrf
                    <div>
                        <label for="name" class="mb-2 block text-sm font-semibold text-[#344359]">Full name</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                               aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}"
                               @error('name') aria-describedby="register-name-error" @enderror
                               class="min-h-12 w-full rounded-xl border border-[#CCD6E5] bg-[#FAFBFE] px-4 text-sm text-[#152238] outline-none transition focus:border-[#4D5CFF] focus:ring-4 focus:ring-[#4D5CFF]/10"
                               placeholder="Your name">
                        @error('name')<p id="register-name-error" class="mt-2 text-xs font-medium text-[#C94F46]">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-[#344359]">Email address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                               aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                               @error('email') aria-describedby="register-email-error" @enderror
                               class="min-h-12 w-full rounded-xl border border-[#CCD6E5] bg-[#FAFBFE] px-4 text-sm text-[#152238] outline-none transition focus:border-[#4D5CFF] focus:ring-4 focus:ring-[#4D5CFF]/10"
                               placeholder="you@example.com">
                        @error('email')<p id="register-email-error" class="mt-2 text-xs font-medium text-[#C94F46]">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="password" class="mb-2 block text-sm font-semibold text-[#344359]">Password</label>
                        <input id="password" type="password" name="password" required autocomplete="new-password"
                               aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                               @error('password') aria-describedby="register-password-error" @enderror
                               class="min-h-12 w-full rounded-xl border border-[#CCD6E5] bg-[#FAFBFE] px-4 text-sm text-[#152238] outline-none transition focus:border-[#4D5CFF] focus:ring-4 focus:ring-[#4D5CFF]/10"
                               placeholder="At least 6 characters">
                        @error('password')<p id="register-password-error" class="mt-2 text-xs font-medium text-[#C94F46]">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-[#344359]">Confirm password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                               class="min-h-12 w-full rounded-xl border border-[#CCD6E5] bg-[#FAFBFE] px-4 text-sm text-[#152238] outline-none transition focus:border-[#4D5CFF] focus:ring-4 focus:ring-[#4D5CFF]/10"
                               placeholder="Repeat your password">
                    </div>
                    <button type="submit" class="mt-2 flex min-h-12 w-full items-center justify-center gap-2 rounded-xl bg-[#4D5CFF] px-5 py-3 text-sm font-bold text-white shadow-[0_10px_24px_rgba(77,92,255,.2)] transition hover:bg-[#3949E9] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#4D5CFF]">
                        Create free account <i class="fa-solid fa-arrow-right text-xs" aria-hidden="true"></i>
                    </button>
                </form>
            </div>
            <p class="mt-8 border-t border-[#E8ECF3] pt-6 text-sm text-[#687684]">Already have an account? <a href="{{ route('login') }}" class="font-bold text-[#4251CE] hover:underline">Sign in</a></p>
        </div>

        <aside class="relative hidden overflow-hidden bg-[#17223A] p-10 text-white lg:flex lg:flex-col lg:justify-between">
            <div class="absolute -right-24 -top-24 h-72 w-72 rounded-full bg-[#4D5CFF]/20 blur-3xl" aria-hidden="true"></div>
            <div class="relative">
                <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-semibold text-[#CAD0FF]"><i class="fa-solid fa-film" aria-hidden="true"></i> From long form to short form</span>
                <h2 class="mt-6 font-['Space_Grotesk'] text-3xl font-bold leading-tight tracking-[-.04em]">One video can hold more than one great moment.</h2>
                <p class="mt-4 text-sm leading-relaxed text-[#B8C3D7]">Explore the best hooks, then export vertical clips with a ready-to-use title kit.</p>
            </div>
            <div class="relative my-10 rounded-[1.5rem] border border-white/15 bg-[#222F4A] p-5 shadow-[0_24px_60px_rgba(0,0,0,.25)]" aria-hidden="true">
                <div class="mb-4 flex items-center justify-between text-xs text-[#BAC5DC]"><span>Long video</span><span>01:24</span></div>
                <div class="flex items-center gap-1.5"><span class="h-1 w-16 rounded-full bg-white/20"></span><span class="h-1 w-28 rounded-full bg-[#FF735B]"></span><span class="h-1 flex-1 rounded-full bg-[#8191FF]"></span></div>
                <div class="mt-5 grid grid-cols-3 gap-3">
                    <div class="aspect-[9/12] rounded-xl bg-gradient-to-br from-[#7A5DAB] via-[#DE8D9B] to-[#292A56]"></div>
                    <div class="aspect-[9/12] rounded-xl border-2 border-[#9AA5FF] bg-gradient-to-br from-[#315C9E] via-[#6256AD] to-[#1A274D]"></div>
                    <div class="aspect-[9/12] rounded-xl bg-gradient-to-br from-[#3C7487] via-[#9DCDB9] to-[#263D68]"></div>
                </div>
                <span class="absolute -bottom-4 left-1/2 -translate-x-1/2 rounded-full bg-[#FF735B] px-4 py-1.5 text-xs font-bold text-[#17223A] shadow-lg">Ready to export</span>
            </div>
            <p class="relative text-xs font-medium text-[#B8C3D7]">20 credits are added to your wallet when you sign up.</p>
        </aside>
    </div>
</section>
@endsection
