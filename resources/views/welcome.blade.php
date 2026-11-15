@extends('layouts.app')

@section('content')
<div class="py-12 md:py-20 text-center relative overflow-hidden">
    <!-- Background Glow Effects -->
    <div class="absolute -top-24 left-1/2 -translate-x-1/2 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-1/2 left-1/4 w-72 h-72 bg-purple-500/15 rounded-full blur-3xl pointer-events-none"></div>

    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/30 text-indigo-300 text-xs font-semibold mb-8 animate-pulse">
        <i class="fa-solid fa-sparkles"></i>
        <span>Powered by Ollama Gemma AI & High-Speed Video Processing</span>
    </div>

    <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight max-w-4xl mx-auto leading-tight md:leading-tight mb-6">
        Turn Any <span class="bg-clip-text text-transparent bg-gradient-to-r from-red-500 via-pink-500 to-indigo-400">Long Video</span> Into <span class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-400 via-purple-300 to-pink-400">Viral 30s/60s Shorts</span>
    </h1>

    <p class="text-lg md:text-xl text-slate-400 max-w-2xl mx-auto mb-10 leading-relaxed">
        Simply paste a YouTube link or upload your long video. Ollama's Gemma AI identifies the highest-retention hooks, extracts 9:16 vertical shorts, and writes viral titles, descriptions, and tags automatically.
    </p>

    <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-16">
        <a href="{{ route('register') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 hover:from-indigo-500 hover:to-pink-500 text-white font-semibold px-8 py-3.5 rounded-xl shadow-xl shadow-indigo-500/25 transition hover:scale-105">
            <i class="fa-solid fa-bolt"></i>
            <span>Create Your First Short Now</span>
        </a>
        <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-slate-800/80 hover:bg-slate-800 text-slate-200 font-semibold px-8 py-3.5 rounded-xl border border-slate-700 transition">
            <i class="fa-solid fa-arrow-right-to-bracket"></i>
            <span>Sign In to Dashboard</span>
        </a>
    </div>

    <!-- Features Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-left max-w-5xl mx-auto pt-8">
        <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800/80 hover:border-indigo-500/40 transition">
            <div class="w-12 h-12 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 flex items-center justify-center text-xl mb-4">
                <i class="fa-brands fa-youtube"></i>
            </div>
            <h3 class="text-lg font-bold text-white mb-2">YouTube URL or Upload</h3>
            <p class="text-sm text-slate-400">Paste any public YouTube video link or upload your MP4/MOV raw footage directly.</p>
        </div>

        <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800/80 hover:border-purple-500/40 transition">
            <div class="w-12 h-12 rounded-xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center text-xl mb-4">
                <i class="fa-solid fa-brain"></i>
            </div>
            <h3 class="text-lg font-bold text-white mb-2">Ollama Gemma AI Hooks</h3>
            <p class="text-sm text-slate-400">Deep AI analysis finds high-curiosity moments, punchlines, and emotional peaks suited for 30s/60s cuts.</p>
        </div>

        <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800/80 hover:border-pink-500/40 transition">
            <div class="w-12 h-12 rounded-xl bg-pink-500/10 border border-pink-500/20 text-pink-400 flex items-center justify-center text-xl mb-4">
                <i class="fa-solid fa-cloud-arrow-down"></i>
            </div>
            <h3 class="text-lg font-bold text-white mb-2">Background Queue & Notify</h3>
            <p class="text-sm text-slate-400">Leave the tab anytime! Our background worker processes everything and alerts you when your clips are ready.</p>
        </div>
    </div>
</div>
@endsection
