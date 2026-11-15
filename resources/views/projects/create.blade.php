@extends('layouts.app')

@section('title', 'Create New AI Shorts')

@section('content')
<div class="max-w-3xl mx-auto py-6" x-data="createProjectForm()">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-400 hover:text-white transition mb-2">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to Dashboard</span>
            </a>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white flex items-center gap-3">
                <span>Create New Viral Shorts</span>
                <span class="text-2xl">✨</span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-400 mt-1">
                Provide a YouTube link or upload a video. Choose Song or Dialogue mode with smart hook extraction.
            </p>
        </div>

        <!-- Token Wallet Pill -->
        <div class="inline-flex items-center gap-3 bg-slate-900 border border-slate-800 rounded-2xl p-2.5 px-4 shadow-lg">
            <div class="w-9 h-9 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-sm border border-amber-500/30">
                <i class="fa-solid fa-coins"></i>
            </div>
            <div>
                <span class="block text-[10px] uppercase font-bold text-slate-400 tracking-wider">Your Balance</span>
                <span class="text-sm font-black text-amber-300">
                    {{ number_format(auth()->user()->tokens) }} Tokens
                </span>
            </div>
            <a href="{{ route('wallet') }}" class="ml-2 text-xs text-indigo-400 hover:text-indigo-300 font-semibold underline">
                Recharge
            </a>
        </div>
    </div>

    <!-- Error banner if any -->
    @if(session('error'))
        <div class="mb-6 p-4 rounded-2xl bg-rose-950/80 border border-rose-800/80 text-rose-200 text-sm flex items-start gap-3 shadow-lg">
            <i class="fa-solid fa-circle-exclamation text-rose-400 text-lg mt-0.5"></i>
            <div>
                <p class="font-bold">Error Creating Project</p>
                <p class="text-xs text-rose-300 mt-0.5">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Main Card -->
    <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xl backdrop-blur-xl">
        <form method="POST" action="{{ route('projects.store') }}" enctype="multipart/form-data" @submit="isSubmitting = true" class="space-y-6">
            @csrf

            <!-- 1. MODE SELECTION (Song vs Dialogue) -->
            <div>
                <div class="flex items-center justify-between mb-3">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300">
                        1. Select Processing Mode <span class="text-rose-400">*</span>
                    </label>
                    <span class="text-[11px] text-slate-400">Tokens vary by compute requirements</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Dialogue Mode -->
                    <label class="cursor-pointer">
                        <input type="radio" name="mode" value="dialogue" x-model="mode" class="peer sr-only">
                        <div class="p-4 rounded-2xl bg-slate-950 border-2 border-slate-800 peer-checked:border-indigo-500 peer-checked:bg-indigo-950/30 transition hover:border-slate-700 h-full flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-500/20 text-indigo-400 flex items-center justify-center text-sm">
                                            <i class="fa-solid fa-microphone"></i>
                                        </div>
                                        <span class="font-bold text-white text-sm">Dialogue / Podcast</span>
                                    </div>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                                        10 Tokens/short
                                    </span>
                                </div>
                                <p class="text-xs text-slate-400 mt-2">
                                    Best for interviews, podcasts, speeches, explainers, and standup comedy. Cuts at natural sentence pauses.
                                </p>
                            </div>
                            <div class="text-[11px] text-indigo-400/80 font-semibold mt-3 flex items-center gap-1.5">
                                <i class="fa-solid fa-bolt text-xs"></i>
                                <span>Speech Hook & Curiosity Detection</span>
                            </div>
                        </div>
                    </label>

                    <!-- Song Mode -->
                    <label class="cursor-pointer">
                        <input type="radio" name="mode" value="song" x-model="mode" class="peer sr-only">
                        <div class="p-4 rounded-2xl bg-slate-950 border-2 border-slate-800 peer-checked:border-pink-500 peer-checked:bg-pink-950/30 transition hover:border-slate-700 h-full flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-lg bg-pink-500/20 text-pink-400 flex items-center justify-center text-sm">
                                            <i class="fa-solid fa-music"></i>
                                        </div>
                                        <span class="font-bold text-white text-sm">Song / Music Video</span>
                                    </div>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase bg-pink-500/20 text-pink-300 border border-pink-500/30">
                                        15 Tokens/short
                                    </span>
                                </div>
                                <p class="text-xs text-slate-400 mt-2">
                                    Best for songs, music tracks, Bollywood/Hindi songs. Detects high-energy Mukhda & Chorus peaks.
                                </p>
                            </div>
                            <div class="text-[11px] text-pink-400/80 font-semibold mt-3 flex items-center gap-1.5">
                                <i class="fa-solid fa-fire text-xs"></i>
                                <span>Mukhda (Chorus) Peak Extraction</span>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- 2. Video Source Type Switcher -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-3">
                    2. Video Source Type
                </label>
                <div class="grid grid-cols-2 gap-3 p-1.5 bg-slate-950 border border-slate-800 rounded-2xl">
                    <button type="button" @click="sourceType = 'youtube'"
                            :class="sourceType === 'youtube' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-slate-200'"
                            class="flex items-center justify-center gap-2 py-3 rounded-xl text-sm font-semibold transition">
                        <i class="fa-brands fa-youtube text-red-400"></i>
                        <span>YouTube Link</span>
                    </button>
                    <button type="button" @click="sourceType = 'upload'"
                            :class="sourceType === 'upload' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-slate-200'"
                            class="flex items-center justify-center gap-2 py-3 rounded-xl text-sm font-semibold transition">
                        <i class="fa-solid fa-cloud-arrow-up text-indigo-300"></i>
                        <span>Upload Video</span>
                    </button>
                </div>
                <input type="hidden" name="source_type" :value="sourceType">
            </div>

            <!-- YouTube URL Input -->
            <div x-show="sourceType === 'youtube'" x-transition>
                <label for="youtube_url" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">
                    YouTube Video URL <span class="text-rose-400">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                        <i class="fa-brands fa-youtube text-base"></i>
                    </div>
                    <input id="youtube_url" type="url" name="youtube_url" value="{{ old('youtube_url') }}"
                           class="w-full pl-10 pr-4 py-3 bg-slate-950 border border-slate-700/80 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                           placeholder="https://www.youtube.com/watch?v=... or https://youtu.be/..."
                           :required="sourceType === 'youtube'">
                </div>
                @error('youtube_url')
                    <p class="text-xs text-rose-400 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- File Upload Input -->
            <div x-show="sourceType === 'upload'" x-transition>
                <label for="video_file" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">
                    Upload Video File (MP4, MOV, MKV) <span class="text-rose-400">*</span>
                </label>
                <div class="border-2 border-dashed border-slate-700 hover:border-indigo-500 rounded-2xl p-6 text-center bg-slate-950/60 transition cursor-pointer relative">
                    <input id="video_file" type="file" name="video_file" accept="video/mp4,video/quicktime,video/x-matroska,video/webm"
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                           @change="selectedFile = $event.target.files[0] ? $event.target.files[0].name : null">
                    <div class="space-y-2 pointer-events-none">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center mx-auto text-xl">
                            <i class="fa-solid fa-film"></i>
                        </div>
                        <div class="text-sm font-medium text-slate-300">
                            <span x-text="selectedFile || 'Click to select or drag and drop video file'"></span>
                        </div>
                        <p class="text-xs text-slate-500">Supports files up to 500MB</p>
                    </div>
                </div>
                @error('video_file')
                    <p class="text-xs text-rose-400 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Project Title (Optional) -->
            <div>
                <label for="title" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">
                    Project Title <span class="text-slate-500 text-[10px] normal-case">(Optional - auto-detected if empty)</span>
                </label>
                <input id="title" type="text" name="title" value="{{ old('title') }}"
                       class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700/80 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                       placeholder="e.g. Sheher Mein - Song Highlights or Podcast Ep 5">
                @error('title')
                    <p class="text-xs text-rose-400 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Settings: Duration & Count -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2">
                <!-- Clip Duration Preference -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">
                        Target Duration (Exact Hook Cut)
                    </label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="clip_duration_pref" value="30" class="peer sr-only" checked>
                            <div class="p-3 text-center rounded-xl bg-slate-950 border border-slate-700/80 peer-checked:border-indigo-500 peer-checked:bg-indigo-950/40 peer-checked:text-indigo-300 text-slate-300 font-semibold text-xs sm:text-sm transition">
                                <i class="fa-regular fa-clock mr-1"></i>
                                <span>~30s Target</span>
                                <span class="block text-[10px] text-slate-400 font-normal mt-0.5">Strict ~31s Mukhda cap</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="clip_duration_pref" value="60" class="peer sr-only">
                            <div class="p-3 text-center rounded-xl bg-slate-950 border border-slate-700/80 peer-checked:border-indigo-500 peer-checked:bg-indigo-950/40 peer-checked:text-indigo-300 text-slate-300 font-semibold text-xs sm:text-sm transition">
                                <i class="fa-solid fa-stopwatch mr-1"></i>
                                <span>~60s Target</span>
                                <span class="block text-[10px] text-slate-400 font-normal mt-0.5">30s - 55s natural cut</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Number of Clips -->
                <div>
                    <label for="clips_count" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">
                        Number of Shorts to Extract
                    </label>
                    <select id="clips_count" name="clips_count" x-model.number="clipsCount"
                            class="w-full px-4 py-3 bg-slate-950 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="1">1 Short (Top Best Peak)</option>
                        <option value="2">2 Shorts</option>
                        <option value="3" selected>3 Shorts (Recommended)</option>
                        <option value="5">5 Shorts (Maximum Coverage)</option>
                    </select>
                </div>
            </div>

            <!-- DYNAMIC TOKEN ESTIMATOR BOX -->
            <div class="p-5 rounded-2xl bg-gradient-to-br from-slate-950 to-slate-900 border border-slate-800 shadow-xl">
                <div class="flex items-center justify-between mb-3 pb-3 border-b border-slate-800/80">
                    <span class="text-xs uppercase font-bold text-slate-400 tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-calculator text-indigo-400"></i>
                        Token Cost Calculator
                    </span>
                    <span class="text-xs font-medium text-slate-400">
                        Rate: <strong class="text-white" x-text="mode === 'song' ? '15 Tokens / short' : '10 Tokens / short'"></strong>
                    </span>
                </div>

                <div class="grid grid-cols-3 gap-3 text-center">
                    <div class="bg-slate-900/60 p-2.5 rounded-xl border border-slate-800">
                        <span class="block text-[10px] uppercase font-semibold text-slate-500">Your Wallet</span>
                        <span class="text-sm font-extrabold text-white" x-text="userTokens + ' Tokens'"></span>
                    </div>
                    <div class="bg-indigo-950/40 p-2.5 rounded-xl border border-indigo-800/50">
                        <span class="block text-[10px] uppercase font-semibold text-indigo-400">Project Cost</span>
                        <span class="text-sm font-black text-amber-400" x-text="tokenCost + ' Tokens'"></span>
                    </div>
                    <div :class="remainingTokens >= 0 ? 'bg-emerald-950/40 border-emerald-800/50' : 'bg-rose-950/40 border-rose-800/50'"
                         class="p-2.5 rounded-xl border">
                        <span class="block text-[10px] uppercase font-semibold" :class="remainingTokens >= 0 ? 'text-emerald-400' : 'text-rose-400'">
                            Remaining
                        </span>
                        <span class="text-sm font-black" :class="remainingTokens >= 0 ? 'text-emerald-300' : 'text-rose-400'"
                              x-text="remainingTokens + ' Tokens'"></span>
                    </div>
                </div>

                <!-- Insufficient Token Alert -->
                <div x-show="!canSubmit" class="mt-4 p-3 rounded-xl bg-rose-950/80 border border-rose-800 text-rose-200 text-xs flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-rose-400 text-base"></i>
                        <span>Insufficient Tokens! You need <strong x-text="tokenCost"></strong> tokens, but your wallet only has <strong x-text="userTokens"></strong> tokens.</span>
                    </div>
                    <a href="{{ route('wallet') }}" class="px-3 py-1.5 rounded-lg bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs whitespace-nowrap transition">
                        Recharge Now
                    </a>
                </div>
            </div>

            <!-- Notice Banner -->
            <div class="p-4 rounded-2xl bg-indigo-950/40 border border-indigo-800/60 text-indigo-300 text-xs flex items-start gap-3">
                <i class="fa-solid fa-shield-halved text-base text-indigo-400 mt-0.5"></i>
                <div class="space-y-1">
                    <p class="font-semibold text-white">Full Refund Guarantee on Failure & Subscribe CTA Included</p>
                    <p class="text-slate-400">
                        Agar kisi reason se video processing fail hoti hai, to aapke tokens <strong>automatically refund</strong> ho jayenge. Har short ke end me 3.5s ka <strong>Subscribe & Like CTA Card</strong> automatically embed hoga.
                    </p>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" :disabled="isSubmitting || !canSubmit"
                    class="w-full bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 hover:from-indigo-500 hover:to-pink-500 text-white font-bold py-4 px-6 rounded-2xl shadow-xl shadow-indigo-600/30 transition hover:scale-[1.01] flex items-center justify-center gap-3 disabled:opacity-50 disabled:cursor-not-allowed">
                <template x-if="!isSubmitting">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                        <span x-text="canSubmit ? 'Start AI Extraction (' + tokenCost + ' Tokens)' : 'Insufficient Tokens - Please Recharge'"></span>
                    </div>
                </template>
                <template x-if="isSubmitting">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-spinner fa-spin"></i>
                        <span>Dispatching to Queue Worker...</span>
                    </div>
                </template>
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
function createProjectForm() {
    return {
        sourceType: 'youtube',
        selectedFile: null,
        isSubmitting: false,
        mode: 'dialogue',
        clipsCount: 3,
        userTokens: {{ auth()->user()->tokens }},

        get tokenCost() {
            const rate = (this.mode === 'song') ? 15 : 10;
            return this.clipsCount * rate;
        },

        get remainingTokens() {
            return this.userTokens - this.tokenCost;
        },

        get canSubmit() {
            return this.remainingTokens >= 0;
        }
    }
}
</script>
@endpush
@endsection
