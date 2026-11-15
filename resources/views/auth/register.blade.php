@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto my-12">
    <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-8 shadow-2xl backdrop-blur-xl">
        <div class="text-center mb-8">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-purple-600 to-pink-600 flex items-center justify-center mx-auto mb-4 shadow-lg shadow-purple-500/20">
                <i class="fa-solid fa-user-plus text-white text-lg"></i>
            </div>
            <h2 class="text-2xl font-bold text-white">Create an Account</h2>
            <p class="text-sm text-slate-400 mt-1">Start converting long videos to viral shorts</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Full Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                       class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700/80 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                       placeholder="e.g. Rahul Sharma">
                @error('name')
                    <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                       class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700/80 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                       placeholder="you@example.com">
                @error('email')
                    <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Password</label>
                <input id="password" type="password" name="password" required
                       class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700/80 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                       placeholder="At least 6 characters">
                @error('password')
                    <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                       class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700/80 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                       placeholder="Repeat password">
            </div>

            <button type="submit" class="w-full mt-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-500 hover:to-pink-500 text-white font-semibold py-3 px-4 rounded-xl shadow-lg shadow-purple-600/30 transition hover:shadow-purple-600/50">
                Register & Get Started
            </button>
        </form>

        <div class="mt-6 text-center text-xs text-slate-400 border-t border-slate-800/80 pt-6">
            Already have an account? 
            <a href="{{ route('login') }}" class="text-indigo-400 hover:text-indigo-300 font-semibold ml-1">Sign in here</a>
        </div>
    </div>
</div>
@endsection
