@extends('layouts.app')

@section('title', 'Create New AI Shorts • AI Shorts Studio')

@section('content')
@php
    $requestedUrl = request()->query('url');
    $requestedMode = request()->query('mode');
    $initialYoutubeUrl = is_string($requestedUrl) && filter_var($requestedUrl, FILTER_VALIDATE_URL) && in_array(parse_url($requestedUrl, PHP_URL_SCHEME), ['http', 'https'], true) ? $requestedUrl : '';
    $initialMode = old('mode', in_array($requestedMode, ['song', 'dialogue'], true) ? $requestedMode : 'dialogue');
@endphp
<div class="max-w-4xl mx-auto py-6" x-data="createProjectForm()">
    <!-- Header -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-xs font-bold text-[#687684] hover:text-[#152238] transition-colors mb-3">
                <i class="fa-solid fa-arrow-left text-[10px]"></i>
                <span>Back to Dashboard</span>
            </a>
            <h1 class="font-['Space_Grotesk'] text-3xl sm:text-4xl font-bold text-[#152238] tracking-[-.05em] flex items-center gap-3">
                <span>Create New Shorts</span>
                <span class="w-10 h-10 rounded-xl bg-[#4D5CFF] flex items-center justify-center text-white text-lg shadow-[0_8px_18px_rgba(77,92,255,.18)]">
                    <i class="fa-solid fa-clapperboard"></i>
                </span>
            </h1>
            <p class="text-xs sm:text-sm text-[#687684] mt-1">
                Add a YouTube link or upload your video file, then choose Song or Dialogue mode.
            </p>
        </div>

        <!-- Token Wallet Pill -->
        <div class="inline-flex items-center gap-3 bg-white border border-[#DDE4F0] rounded-2xl p-3 px-4 shadow-xl">
            <div class="w-10 h-10 rounded-xl bg-amber-500/15 text-[#A86B1A] flex items-center justify-center text-base border border-amber-500/25">
                <i class="fa-solid fa-coins"></i>
            </div>
            <div>
                <span class="block text-[11px] font-bold uppercase tracking-wider text-[#687684]">Available Credits</span>
                <span class="text-base font-black font-mono text-[#152238]">
                    {{ number_format(auth()->user()->tokens) }} Credits
                </span>
            </div>
            <a href="{{ route('wallet') }}" class="ml-2 text-xs font-bold text-[#4251CE] hover:underline">
                Recharge
            </a>
        </div>
    </div>

    <!-- Error banner if any -->
    @if(session('error'))
        <div class="mb-6 p-4 rounded-2xl bg-[#FFF1EE] border border-[#FFD5CE] text-[#A84339] text-xs sm:text-sm flex items-start gap-3">
            <i class="fa-solid fa-circle-exclamation text-[#C94F46] text-lg mt-0.5 shrink-0"></i>
            <div>
                <p class="font-bold">Error Creating Project</p>
                <p class="text-xs sm:text-sm mt-0.5">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Main Card -->
    <div class="bg-white border border-[#DDE4F0] rounded-3xl p-6 sm:p-10 shadow-[0_16px_50px_rgba(21,34,56,.06)] backdrop-blur-xl">
        <form method="POST" action="{{ route('projects.store') }}" enctype="multipart/form-data" @submit="isSubmitting = true" class="space-y-8">
            @csrf

            <!-- 1. MODE SELECTION (Song vs Dialogue) -->
            <div>
                <div class="flex items-center justify-between mb-3">
                    <label class="block text-sm font-black text-[#152238] uppercase tracking-wider">
                        1. Select Processing Mode <span class="text-[#C94F46]">*</span>
                    </label>
                    <span class="text-xs font-bold text-[#A86B1A] bg-amber-500/10 px-2.5 py-0.5 rounded-full border border-amber-500/25">
                        Fixed 1 Credit / Video
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Dialogue Mode -->
                    <label class="cursor-pointer group">
                        <input type="radio" name="mode" value="dialogue" x-model="mode" class="peer sr-only">
                        <div class="p-5 rounded-2xl bg-[#FAFBFE] border-2 border-[#DDE4F0] peer-checked:border-[#4D5CFF] peer-checked:bg-[#EEF0FF] peer-checked:shadow-xl peer-checked:shadow-indigo-500/10 transition-all duration-200 hover:border-[#B4C0D2] h-full flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-indigo-500/20 text-[#4251CE] flex items-center justify-center text-base border border-indigo-500/30">
                                            <i class="fa-solid fa-microphone"></i>
                                        </div>
                                        <div>
                                            <span class="font-black text-[#152238] text-sm block">Dialogue / Podcast</span>
                                            <span class="text-[10px] text-[#687684]">Speeches, Interviews, Standup</span>
                                        </div>
                                    </div>
                                    <span x-cloak x-show="mode === 'dialogue'" class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#EEF0FF] text-[#4251CE] border border-[#D8DFFF]">
                                        Active
                                    </span>
                                </div>
                                <p class="text-xs text-[#687684] leading-relaxed font-normal">
                                    Best for interviews, podcasts, speeches, explainers, and stories. Finds strong moments around natural sentence pauses.
                                </p>
                            </div>
                            <div class="text-xs text-[#4251CE] font-bold mt-4 flex items-center gap-1.5 pt-3 border-t border-[#E8ECF3]">
                                <i class="fa-solid fa-bolt text-xs"></i>
                                <span>Speech Hook & Curiosity Detection</span>
                            </div>
                        </div>
                    </label>

                    <!-- Song Mode -->
                    <label class="cursor-pointer group">
                        <input type="radio" name="mode" value="song" x-model="mode" class="peer sr-only">
                        <div class="p-5 rounded-2xl bg-[#FAFBFE] border-2 border-[#DDE4F0] peer-checked:border-[#FF735B] peer-checked:bg-[#FFF1EE] peer-checked:shadow-xl peer-checked:shadow-pink-500/10 transition-all duration-200 hover:border-[#B4C0D2] h-full flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-pink-500/20 text-[#B34A6A] flex items-center justify-center text-base border border-pink-500/30">
                                            <i class="fa-solid fa-music"></i>
                                        </div>
                                        <div>
                                            <span class="font-black text-[#152238] text-sm block">Song / Music Video</span>
                                            <span class="text-[10px] text-[#687684]">Bollywood, Hindi Tracks, Anthems</span>
                                        </div>
                                    </div>
                                    <span x-cloak x-show="mode === 'song'" class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#FFF1EE] text-[#B34A3E] border border-[#FFD5CE]">
                                        Active
                                    </span>
                                </div>
                                <p class="text-xs text-[#687684] leading-relaxed font-normal">
                                    Best for music videos and songs. Searches for high-energy Mukhda and chorus moments.
                                </p>
                            </div>
                            <div class="text-xs text-[#B34A6A] font-bold mt-4 flex items-center gap-1.5 pt-3 border-t border-[#E8ECF3]">
                                <i class="fa-solid fa-fire text-xs"></i>
                                <span>Mukhda (Chorus) Peak Extraction</span>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- 2. Video Source Type Switcher -->
            <div>
                <label class="block text-sm font-black text-[#152238] uppercase tracking-wider mb-3">
                    2. Choose Video Source
                </label>
                <div class="grid grid-cols-2 gap-3 p-1.5 bg-[#FAFBFE] border border-[#DDE4F0] rounded-2xl">
                    <button type="button" @click="sourceType = 'youtube'" :aria-pressed="sourceType === 'youtube'"
                            :class="sourceType === 'youtube' ? 'bg-[#4D5CFF] text-white shadow-[0_6px_14px_rgba(77,92,255,.16)] font-bold' : 'text-[#687684] hover:text-[#152238] font-medium'"
                            class="flex items-center justify-center gap-2 py-3 rounded-xl text-xs sm:text-sm transition-all duration-200">
                        <i class="fa-brands fa-youtube text-base"></i>
                        <span>YouTube Video Link</span>
                    </button>
                    <button type="button" @click="sourceType = 'upload'" :aria-pressed="sourceType === 'upload'"
                            :class="sourceType === 'upload' ? 'bg-[#4D5CFF] text-white shadow-[0_6px_14px_rgba(77,92,255,.16)] font-bold' : 'text-[#687684] hover:text-[#152238] font-medium'"
                            class="flex items-center justify-center gap-2 py-3 rounded-xl text-xs sm:text-sm transition-all duration-200">
                        <i class="fa-solid fa-cloud-arrow-up text-base"></i>
                        <span>Upload Video File</span>
                    </button>
                </div>
                <input type="hidden" name="source_type" :value="sourceType">
            </div>

            <!-- YouTube URL Input -->
            <div x-show="sourceType === 'youtube'" x-transition>
                <label for="youtube_url" class="block text-xs font-bold uppercase tracking-wider text-[#526078] mb-2">
                    Paste YouTube Video Link <span class="text-[#C94F46]">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-[#C94F46] text-lg">
                        <i class="fa-brands fa-youtube"></i>
                    </div>
                    <input id="youtube_url" type="url" name="youtube_url" value="{{ old('youtube_url', $initialYoutubeUrl) }}"
                           class="w-full pl-12 pr-4 py-3.5 bg-[#FAFBFE] border border-[#CCD6E5] rounded-2xl text-[#152238] placeholder-[#8A96A8] text-xs sm:text-sm focus:outline-none focus:border-[#4D5CFF] focus:ring-2 focus:ring-[#4D5CFF]/10 transition"
                           placeholder="https://www.youtube.com/watch?v=... or https://youtu.be/..."
                           :required="sourceType === 'youtube'">
                </div>
                <p class="text-[11px] text-[#687684] mt-2 flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-info text-[#8A96A8]"></i>
                    <span>Supports podcast clips, songs, interviews, vlogs, and gaming streams.</span>
                </p>
                @error('youtube_url')
                    <p class="text-xs text-[#C94F46] mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- File Upload Input -->
            <div x-cloak x-show="sourceType === 'upload'" x-transition>
                <label for="video_file" class="block text-xs font-bold uppercase tracking-wider text-[#526078] mb-2">
                    Upload Local Video File (MP4, MOV, MKV, WebM) <span class="text-[#C94F46]">*</span>
                </label>
                <div class="border-2 border-dashed border-[#CCD6E5] hover:border-indigo-500 focus-within:border-indigo-500 rounded-3xl p-8 text-center bg-[#FAFBFE] transition cursor-pointer relative group">
                    <input id="video_file" type="file" name="video_file" accept="video/mp4,video/quicktime,video/x-matroska,video/webm"
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                           @change="selectedFile = $event.target.files[0] ? $event.target.files[0].name : null">
                    <div class="space-y-3 pointer-events-none">
                        <div class="w-14 h-14 rounded-2xl bg-indigo-500/15 text-[#4251CE] flex items-center justify-center mx-auto text-2xl border border-indigo-500/30 group-hover:scale-105 transition">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                        </div>
                        <div class="text-sm font-bold text-[#152238]">
                            <span x-text="selectedFile || 'Click to select or drag and drop video file'"></span>
                        </div>
                        <p class="text-xs text-[#8A96A8]">Supports HD MP4, MOV, MKV up to 500MB</p>
                    </div>
                </div>
                @error('video_file')
                    <p class="text-xs text-[#C94F46] mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Project Title (Optional) -->
            <div>
                <label for="title" class="block text-xs font-bold uppercase tracking-wider text-[#526078] mb-2">
                    Project Title <span class="text-[#8A96A8] font-normal lowercase">(optional, auto-generated from video)</span>
                </label>
                <input id="title" type="text" name="title" value="{{ old('title') }}"
                       class="w-full px-4 py-3 bg-[#FAFBFE] border border-[#CCD6E5] rounded-2xl text-[#152238] placeholder-[#8A96A8] text-xs sm:text-sm focus:outline-none focus:border-[#4D5CFF] focus:ring-2 focus:ring-[#4D5CFF]/10"
                       placeholder="e.g. Purani Gali 90s Song or The Diary of a CEO Highlight">
                @error('title')
                    <p class="text-xs text-[#C94F46] mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Settings: Duration & Count -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2">
                <!-- Clip Duration Preference -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-[#526078] mb-2">
                        Target Clip Duration
                    </label>
                    <div class="grid grid-cols-2 gap-2.5">
                        <label class="cursor-pointer">
                            <input type="radio" name="clip_duration_pref" value="30" class="peer sr-only" checked>
                            <div class="p-3 text-center rounded-2xl bg-[#FAFBFE] border border-[#DDE4F0] peer-checked:border-[#4D5CFF] peer-checked:bg-[#EEF0FF] peer-checked:text-[#152238] text-[#687684] font-bold text-xs sm:text-sm transition">
                                <i class="fa-regular fa-clock mr-1 text-[#C94F46]"></i>
                                <span>~30s Target</span>
                                <span class="block text-[10px] text-[#8A96A8] font-normal mt-0.5">18s - 32s natural cut</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="clip_duration_pref" value="60" class="peer sr-only">
                            <div class="p-3 text-center rounded-2xl bg-[#FAFBFE] border border-[#DDE4F0] peer-checked:border-[#4D5CFF] peer-checked:bg-[#EEF0FF] peer-checked:text-[#152238] text-[#687684] font-bold text-xs sm:text-sm transition">
                                <i class="fa-solid fa-stopwatch mr-1 text-[#A86B1A]"></i>
                                <span>~60s Target</span>
                                <span class="block text-[10px] text-[#8A96A8] font-normal mt-0.5">30s - 58s natural cut</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Number of Clips -->
                <div>
                    <label for="clips_count" class="block text-xs font-bold uppercase tracking-wider text-[#526078] mb-2">
                        Number of Shorts to Generate
                    </label>
                    <select id="clips_count" name="clips_count" x-model="clipsCount"
                            class="w-full px-4 py-3 bg-[#FAFBFE] border border-[#CCD6E5] rounded-2xl text-[#152238] text-xs sm:text-sm focus:outline-none focus:border-[#4D5CFF] focus:ring-2 focus:ring-[#4D5CFF]/10">
                        <option value="1">1 Short (Single Best Moment)</option>
                        <option value="2">2 Shorts</option>
                        <option value="3" selected>3 Shorts (Recommended)</option>
                        <option value="5">5 Shorts (Deep Coverage)</option>
                        <option value="all">🔥 All Possible Cuts (Full Video Scan - All Shorts!)</option>
                    </select>
                    <span class="block text-[11px] text-[#C94F46] mt-1.5 font-semibold">
                        ✨ Full video analyzed for natural stanzas. 1 Credit covers all shorts!
                    </span>
                </div>
            </div>

            <!-- DYNAMIC CREDIT ESTIMATOR BOX -->
            <div class="p-5 sm:p-6 rounded-3xl bg-[#FAFBFE] border border-[#DDE4F0]">
                <div class="flex items-center justify-between gap-3 mb-4 pb-3 border-b border-[#E8ECF3]">
                    <span class="text-xs sm:text-sm font-bold text-[#152238] flex items-center gap-2">
                        <i class="fa-solid fa-coins text-[#A86B1A]"></i>
                        Video Credits Summary
                    </span>
                    <span class="text-xs font-bold px-3 py-1 rounded-full bg-emerald-500/10 text-[#208052] border border-emerald-500/30">
                        Fixed: 1 Credit / Video (Unlimited Cuts)
                    </span>
                </div>

                <div class="grid grid-cols-3 gap-3 text-center">
                    <div class="bg-[#FAFBFE] p-3 rounded-2xl border border-[#E8ECF3]">
                        <span class="block text-[10px] uppercase font-bold text-[#687684]">Your Wallet</span>
                        <span class="text-sm sm:text-base font-black font-mono text-[#152238] mt-0.5 block" x-text="userTokens + ' Credits'"></span>
                    </div>
                    <div class="bg-[#EEF0FF] p-3 rounded-2xl border border-[#D8DFFF]">
                        <span class="block text-[10px] uppercase font-bold text-[#4251CE]">Project Cost</span>
                        <span class="text-sm sm:text-base font-black font-mono text-[#152238] mt-0.5 block">1 Video Credit</span>
                    </div>
                    <div :class="remainingTokens >= 0 ? 'bg-emerald-500/10 border-emerald-500/30 text-[#208052]' : 'bg-rose-500/10 border-rose-500/30 text-[#C94F46]'"
                         class="p-3 rounded-2xl border">
                        <span class="block text-[10px] uppercase font-bold">Remaining</span>
                        <span class="text-sm sm:text-base font-black font-mono mt-0.5 block"
                              x-text="remainingTokens + ' Credits'"></span>
                    </div>
                </div>

                <!-- Insufficient Credit Alert -->
                <div x-cloak x-show="!canSubmit" class="mt-4 p-4 rounded-2xl bg-[#FFF1EE] border border-[#FFD5CE] text-[#A84339] text-xs sm:text-sm flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-[#C94F46] text-base"></i>
                        <span>Insufficient Credits! You need 1 Video Credit, but your wallet has 0 credits.</span>
                    </div>
                    <a href="{{ route('wallet') }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-[#4D5CFF] hover:bg-[#3949E9] text-white font-bold text-xs whitespace-nowrap transition">
                        Recharge Now
                    </a>
                </div>
            </div>

            <!-- Processing and download guidance -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="p-4 rounded-2xl bg-[#FAFBFE] border border-[#DDE4F0] text-xs flex items-start gap-3">
                    <i class="fa-solid fa-wand-magic-sparkles text-sm text-[#C94F46] mt-0.5 shrink-0"></i>
                    <div>
                        <span class="font-bold text-[#152238] block">Smart Sentence & Lyric Perfection</span>
                        <span class="text-[11px] text-[#687684] mt-0.5 block">The processor searches for natural moments and adjusts clip length where possible.</span>
                    </div>
                </div>
                <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/25 text-xs flex items-start gap-3">
                    <i class="fa-solid fa-clock-rotate-left text-sm text-[#A86B1A] mt-0.5 shrink-0"></i>
                    <div>
                        <span class="font-bold text-[#805B2B] block">Download your shorts</span>
                        <span class="text-[11px] text-[#805B2B] mt-0.5 block">Keep a copy of each finished MP4 for your own archive.</span>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" :disabled="isSubmitting || !canSubmit"
                    class="w-full bg-[#4D5CFF] hover:bg-[#3949E9] text-white font-bold py-4 px-6 rounded-2xl shadow-[0_10px_24px_rgba(77,92,255,.2)] transition-all duration-200 flex items-center justify-center gap-3 disabled:opacity-40 disabled:cursor-not-allowed">
                <template x-if="!isSubmitting">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-wand-magic-sparkles text-white"></i>
                        <span x-text="canSubmit ? 'Generate Shorts (1 Video Credit)' : 'Insufficient Credits - Please Recharge'"></span>
                    </div>
                </template>
                <template x-if="isSubmitting">
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-circle-notch fa-spin text-lg"></i>
                        <span>Starting AI Video Studio...</span>
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
        sourceType: '{{ old('source_type') === 'upload' ? 'upload' : 'youtube' }}',
        selectedFile: null,
        isSubmitting: false,
        mode: '{{ $initialMode === 'song' ? 'song' : 'dialogue' }}',
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
