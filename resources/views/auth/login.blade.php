@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto my-12">
    <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-8 shadow-2xl backdrop-blur-xl">
        <div class="text-center mb-8">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-600 to-purple-600 flex items-center justify-center mx-auto mb-4 shadow-lg shadow-indigo-500/20">
                <i class="fa-solid fa-lock text-white text-lg"></i>
            </div>
            <h2 class="text-2xl font-bold text-white">Welcome Back</h2>
            <p class="text-sm text-slate-400 mt-1">Sign in to manage your AI Shorts projects</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                        <i class="fa-regular fa-envelope"></i>
                    </div>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full pl-10 pr-4 py-2.5 bg-slate-950 border border-slate-700/80 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                           placeholder="you@example.com">
                </div>
                @error('email')
                    <p class="text-xs text-rose-400 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                        <i class="fa-solid fa-key"></i>
                    </div>
                    <input id="password" type="password" name="password" required
                           class="w-full pl-10 pr-4 py-2.5 bg-slate-950 border border-slate-700/80 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                           placeholder="••••••••">
                </div>
                @error('password')
                    <p class="text-xs text-rose-400 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center gap-2 cursor-pointer text-slate-400 hover:text-slate-200">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-slate-950 border-slate-700 text-indigo-600 focus:ring-indigo-500">
                    <span>Remember me</span>
                </label>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-semibold py-3 px-4 rounded-xl shadow-lg shadow-indigo-600/30 transition hover:shadow-indigo-600/50">
                Sign In
            </button>
        </form>

        <div class="mt-6 text-center text-xs text-slate-400 border-t border-slate-800/80 pt-6">
            Don't have an account yet? 
            <a href="{{ route('register') }}" class="text-indigo-400 hover:text-indigo-300 font-semibold ml-1">Create an account</a>
        </div>
    </div>
</div>
@endsection
