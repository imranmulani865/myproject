@extends('layouts.app')

@section('title', 'Create New AI Shorts')

@section('content')
<div class="max-w-3xl mx-auto py-6" x-data="createProjectForm()">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('dashboard') }}" class="inline-flex min-h-10 items-center gap-2 text-sm font-semibold text-studio-muted hover:text-brand-600 transition-colors mb-3">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                <span>Back to Dashboard</span>
            </a>
            <h1 class="text-3xl font-bold text-studio-ink flex items-center gap-3">
                <span>Create a new project</span>
                <i class="fa-solid fa-clapperboard text-brand-600 text-2xl" aria-hidden="true"></i>
            </h1>
            <p class="text-sm text-studio-muted mt-2">
                Add a YouTube link or upload a video, then choose Song or Dialogue mode for your shorts.
            </p>
        </div>

        <!-- Token Wallet Pill -->
        <div class="inline-flex items-center gap-3 bg-studio-surface border border-studio-line rounded-2xl p-2.5 px-4 shadow-sm">
            <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center text-sm border border-amber-200">
                <i class="fa-solid fa-coins"></i>
            </div>
            <div>
                <span class="block text-xs font-semibold text-studio-muted">Your balance</span>
                <span class="text-sm font-bold text-studio-ink">
                    {{ number_format(auth()->user()->tokens) }} Tokens
                </span>
            </div>
            <a href="{{ route('wallet') }}" class="ml-2 text-sm text-brand-600 hover:text-brand-700 font-semibold underline">
                Recharge
            </a>
        </div>
    </div>

    <!-- Error banner if any -->
    @if(session('error'))
        <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-start gap-3">
            <i class="fa-solid fa-circle-exclamation text-rose-700 text-lg mt-0.5"></i>
            <div>
                <p class="font-bold">Error Creating Project</p>
                <p class="text-sm text-rose-700 mt-0.5">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Main Card -->
    <div class="bg-studio-surface border border-studio-line rounded-3xl p-6 sm:p-8 shadow-[0_18px_48px_rgba(48,40,166,0.08)]">
        <form method="POST" action="{{ route('projects.store') }}" enctype="multipart/form-data" @submit="isSubmitting = true" class="space-y-6">
            @csrf

            <!-- 1. MODE SELECTION (Song vs Dialogue) -->
            <div>
                <div class="flex items-center justify-between mb-3">
                    <label class="block text-sm font-semibold text-studio-ink">
                        Processing mode <span class="text-rose-700">*</span>
                    </label>
                    <span class="text-xs text-studio-muted">1 Video Credit per project</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Dialogue Mode -->
                    <label class="cursor-pointer">
                        <input type="radio" name="mode" value="dialogue" x-model="mode" class="peer sr-only">
                        <div class="p-4 rounded-2xl bg-studio-canvas border-2 border-studio-line peer-checked:border-brand-500 peer-checked:bg-studio-lavender/60 transition-colors hover:border-brand-400 h-full flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-lg bg-studio-lavender text-brand-600 flex items-center justify-center text-sm">
                                            <i class="fa-solid fa-microphone"></i>
                                        </div>
                                        <span class="font-bold text-studio-ink text-sm">Dialogue / Podcast</span>
                                    </div>
                                    <span class="px-2 py-0.5 rounded text-xs font-semibold bg-studio-surface text-brand-700 border border-brand-200">
                                        1 Credit / Video
                                    </span>
                                </div>
                                <p class="text-sm text-studio-muted mt-2">
                                    Best for interviews, podcasts, speeches, explainers, and standup comedy. Cuts at natural sentence pauses.
                                </p>
                            </div>
                            <div class="text-xs text-brand-700 font-semibold mt-3 flex items-center gap-1.5">
                                <i class="fa-solid fa-bolt text-xs"></i>
                                <span>Speech Hook & Curiosity Detection</span>
                            </div>
                        </div>
                    </label>

                    <!-- Song Mode -->
                    <label class="cursor-pointer">
                        <input type="radio" name="mode" value="song" x-model="mode" class="peer sr-only">
                        <div class="p-4 rounded-2xl bg-studio-canvas border-2 border-studio-line peer-checked:border-pink-500 peer-checked:bg-pink-50 transition-colors hover:border-pink-300 h-full flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-lg bg-pink-100 text-pink-700 flex items-center justify-center text-sm">
                                            <i class="fa-solid fa-music"></i>
                                        </div>
                                        <span class="font-bold text-studio-ink text-sm">Song / Music Video</span>
                                    </div>
                                    <span class="px-2 py-0.5 rounded text-xs font-semibold bg-pink-50 text-pink-700 border border-pink-200">
                                        1 Credit / Video
                                    </span>
                                </div>
                                <p class="text-sm text-studio-muted mt-2">
                                    Best for songs, music tracks, Bollywood/Hindi songs. Detects high-energy Mukhda & Chorus peaks.
                                </p>
                            </div>
                            <div class="text-xs text-pink-700 font-semibold mt-3 flex items-center gap-1.5">
                                <i class="fa-solid fa-fire text-xs"></i>
                                <span>Mukhda (Chorus) Peak Extraction</span>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- 2. Video Source Type Switcher -->
            <div>
                <label class="block text-sm font-semibold text-studio-ink mb-3">
                    Video source
                </label>
                <div class="grid grid-cols-2 gap-3 p-1.5 bg-studio-canvas border border-studio-line rounded-2xl">
                    <button type="button" @click="sourceType = 'youtube'" :aria-pressed="sourceType === 'youtube'"
                            :class="sourceType === 'youtube' ? 'bg-brand-600 text-white shadow-lg shadow-brand-600/20' : 'text-studio-muted hover:text-brand-600'"
                            class="flex min-h-12 items-center justify-center gap-2 py-3 rounded-xl text-sm font-semibold transition-colors">
                        <i class="fa-brands fa-youtube" aria-hidden="true"></i>
                        <span>YouTube Video Link</span>
                    </button>
                    <button type="button" @click="sourceType = 'upload'" :aria-pressed="sourceType === 'upload'"
                            :class="sourceType === 'upload' ? 'bg-brand-600 text-white shadow-lg shadow-brand-600/20' : 'text-studio-muted hover:text-brand-600'"
                            class="flex min-h-12 items-center justify-center gap-2 py-3 rounded-xl text-sm font-semibold transition-colors">
                        <i class="fa-solid fa-cloud-arrow-up" aria-hidden="true"></i>
                        <span>Upload Video File</span>
                    </button>
                </div>
                <input type="hidden" name="source_type" :value="sourceType">
            </div>

            <!-- YouTube URL Input -->
            <div x-show="sourceType === 'youtube'" x-transition>
                <label for="youtube_url" class="block text-sm font-semibold text-studio-ink mb-2">
                    YouTube video URL <span class="text-rose-700">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-studio-muted">
                        <i class="fa-brands fa-youtube text-base"></i>
                    </div>
                    <input id="youtube_url" type="url" name="youtube_url" value="{{ old('youtube_url') }}"
                           class="w-full pl-10 pr-4 py-3 bg-studio-canvas border border-studio-line rounded-xl text-studio-ink placeholder:text-studio-muted text-sm focus:outline-none focus:ring-2 focus:ring-brand-400/30 focus:border-brand-500 transition"
                           placeholder="https://www.youtube.com/watch?v=... or https://youtu.be/..."
                           :required="sourceType === 'youtube'">
                </div>
                <p class="text-xs text-studio-muted mt-1.5 flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-info text-studio-muted"></i>
                    <span>Works with podcasts, tutorials, speeches, interviews, documentaries, and vlog videos.</span>
                </p>
                @error('youtube_url')
                    <p class="text-sm text-rose-700 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- File Upload Input -->
            <div x-cloak x-show="sourceType === 'upload'" x-transition>
                <label for="video_file" class="block text-sm font-semibold text-studio-ink mb-2">
                    Upload video file (MP4, MOV, MKV, WebM) <span class="text-rose-700">*</span>
                </label>
                <div class="border-2 border-dashed border-studio-line hover:border-brand-500 focus-within:border-brand-500 focus-within:ring-2 focus-within:ring-brand-400/30 rounded-2xl p-6 text-center bg-studio-canvas/70 transition cursor-pointer relative">
                    <input id="video_file" type="file" name="video_file" accept="video/mp4,video/quicktime,video/x-matroska,video/webm"
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                           @change="selectedFile = $event.target.files[0] ? $event.target.files[0].name : null">
                    <div class="space-y-2 pointer-events-none">
                        <div class="w-12 h-12 rounded-2xl bg-studio-lavender text-brand-600 flex items-center justify-center mx-auto text-xl">
                            <i class="fa-solid fa-film"></i>
                        </div>
                        <div class="text-sm font-medium text-studio-ink">
                            <span x-text="selectedFile || 'Click to select or drag and drop video file'"></span>
                        </div>
                        <p class="text-xs text-studio-muted">Supports files up to 500MB</p>
                    </div>
                </div>
                @error('video_file')
                    <p class="text-sm text-rose-700 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Project Title (Optional) -->
            <div>
                <label for="title" class="block text-sm font-semibold text-studio-ink mb-2">
                    Project title <span class="text-studio-muted text-xs font-normal">(Optional, auto-detected if empty)</span>
                </label>
                <input id="title" type="text" name="title" value="{{ old('title') }}"
                       class="w-full px-4 py-2.5 bg-studio-canvas border border-studio-line rounded-xl text-studio-ink placeholder:text-studio-muted text-sm focus:outline-none focus:ring-2 focus:ring-brand-400/30 focus:border-brand-500"
                       placeholder="e.g. Sheher Mein - Song Highlights or Podcast Ep 5">
                @error('title')
                    <p class="text-sm text-rose-700 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Settings: Duration & Count -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2">
                <!-- Clip Duration Preference -->
                <div>
                    <label class="block text-sm font-semibold text-studio-ink mb-2">
                        Target clip duration
                    </label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="clip_duration_pref" value="30" class="peer sr-only" checked>
                            <div class="p-3 text-center rounded-xl bg-studio-canvas border border-studio-line peer-checked:border-brand-500 peer-checked:bg-studio-lavender peer-checked:text-brand-700 text-studio-ink font-semibold text-xs sm:text-sm transition">
                                <i class="fa-regular fa-clock mr-1"></i>
                                <span>~30s Target</span>
                                <span class="block text-xs text-studio-muted font-normal mt-0.5">18s - 32s natural cut</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="clip_duration_pref" value="60" class="peer sr-only">
                            <div class="p-3 text-center rounded-xl bg-studio-canvas border border-studio-line peer-checked:border-brand-500 peer-checked:bg-studio-lavender peer-checked:text-brand-700 text-studio-ink font-semibold text-xs sm:text-sm transition">
                                <i class="fa-solid fa-stopwatch mr-1"></i>
                                <span>~60s Target</span>
                                <span class="block text-[10px] text-studio-muted font-normal mt-0.5">30s - 55s natural cut</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Number of Clips -->
                <div>
                    <label for="clips_count" class="block text-sm font-semibold text-studio-ink mb-2">
                        Number of shorts
                    </label>
                    <select id="clips_count" name="clips_count" x-model="clipsCount"
                            class="w-full px-4 py-3 bg-studio-canvas border border-studio-line rounded-xl text-studio-ink text-sm focus:outline-none focus:ring-2 focus:ring-brand-400/30 focus:border-brand-500">
                        <option value="1">1 Short (Top Best Moment)</option>
                        <option value="2">2 Shorts</option>
                        <option value="3" selected>3 Shorts (Recommended)</option>
                        <option value="5">5 Shorts (Deep Coverage)</option>
                        <option value="all">🔥 All Possible Cuts (Auto Full Video Scan - All Shorts!)</option>
                    </select>
                    <span class="block text-[11px] text-brand-600 dark:text-brand-400 mt-1 font-medium">
                        ✨ Full video analyzed for natural stanzas. 1 Credit covers all shorts!
                    </span>
                </div>
            </div>

            <!-- DYNAMIC CREDIT ESTIMATOR BOX -->
            <div class="p-5 rounded-2xl bg-studio-canvas border border-studio-line">
                <div class="flex items-center justify-between gap-3 mb-3 pb-3 border-b border-studio-line">
                    <span class="text-sm font-semibold text-studio-ink flex items-center gap-2">
                        <i class="fa-solid fa-coins text-amber-500"></i>
                        Video Credits Summary
                    </span>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-600 border border-emerald-500/20">
                        Fixed: 1 Credit / Video (Unlimited Cuts)
                    </span>
                </div>

                <div class="grid grid-cols-3 gap-3 text-center">
                    <div class="bg-studio-surface p-2.5 rounded-xl border border-studio-line">
                        <span class="block text-xs font-semibold text-studio-muted">Your Wallet</span>
                        <span class="text-sm font-bold text-studio-ink" x-text="userTokens + ' Credits'"></span>
                    </div>
                    <div class="bg-studio-lavender/60 p-2.5 rounded-xl border border-brand-200">
                        <span class="block text-xs font-semibold text-brand-700">Project Cost</span>
                        <span class="text-sm font-bold text-studio-ink">1 Video Credit</span>
                    </div>
                    <div :class="remainingTokens >= 0 ? 'bg-emerald-50 border-emerald-200' : 'bg-rose-50 border-rose-200'"
                         class="p-2.5 rounded-xl border">
                        <span class="block text-xs font-semibold" :class="remainingTokens >= 0 ? 'text-emerald-700' : 'text-rose-700'">
                            Remaining
                        </span>
                        <span class="text-sm font-bold" :class="remainingTokens >= 0 ? 'text-emerald-800' : 'text-rose-800'"
                              x-text="remainingTokens + ' Credits'"></span>
                    </div>
                </div>

                <!-- Insufficient Credit Alert -->
                <div x-cloak x-show="!canSubmit" class="mt-4 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-rose-700 text-base"></i>
                        <span>Insufficient Credits! You need 1 Video Credit, but your wallet has 0 credits.</span>
                    </div>
                    <a href="{{ route('wallet') }}" class="inline-flex min-h-10 items-center px-3 py-1.5 rounded-lg bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm whitespace-nowrap transition-colors">
                        Recharge Now
                    </a>
                </div>
            </div>

            <!-- Smart Cut Perfection & 30-Day Storage Notice Banner -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="p-3.5 rounded-2xl bg-studio-lavender/60 border border-brand-200 text-studio-muted text-xs flex items-start gap-2.5">
                    <i class="fa-solid fa-wand-magic-sparkles text-sm text-brand-600 mt-0.5 shrink-0"></i>
                    <div>
                        <span class="font-semibold text-studio-ink block">Smart Sentence & Lyric Perfection</span>
                        <span class="text-[11px] text-studio-muted">Cuts dynamically expand (18s-55s) to guarantee lines & verses complete naturally without being cut mid-word!</span>
                    </div>
                </div>
                <div class="p-3.5 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-studio-muted text-xs flex items-start gap-2.5">
                    <i class="fa-solid fa-clock-rotate-left text-sm text-amber-600 dark:text-amber-400 mt-0.5 shrink-0"></i>
                    <div>
                        <span class="font-semibold text-amber-800 dark:text-amber-300 block">30-Day Cloud Storage</span>
                        <span class="text-[11px] text-amber-900/80 dark:text-amber-200/80">Generated shorts are hosted for 30 days. Download your MP4 files to keep them permanently!</span>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" :disabled="isSubmitting || !canSubmit"
                    class="w-full bg-brand-600 hover:bg-brand-700 text-white font-bold py-4 px-6 rounded-2xl shadow-lg shadow-brand-600/20 transition-colors flex items-center justify-center gap-3 disabled:opacity-50 disabled:cursor-not-allowed">
                <template x-if="!isSubmitting">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                        <span x-text="canSubmit ? 'Generate Shorts (1 Video Credit)' : 'Insufficient Credits - Please Recharge'"></span>
                    </div>
                </template>
                <template x-if="isSubmitting">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-spinner fa-spin"></i>
                        <span>Starting project...</span>
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
        clipsCount: '3',
        userTokens: {{ auth()->user()->tokens }},

        get tokenCost() {
            return 1;
        },

        get remainingTokens() {
            return this.userTokens - 1;
        },

        get canSubmit() {
            return this.remainingTokens >= 0;
        }
    }
}
</script>
@endpush
@endsection
