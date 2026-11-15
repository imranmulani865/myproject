@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-6" x-data="createProjectForm()">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-400 hover:text-white transition mb-3">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to Dashboard</span>
        </a>
        <h1 class="text-3xl font-extrabold text-white flex items-center gap-3">
            <span>Create New Viral Shorts</span>
            <span class="text-2xl">✨</span>
        </h1>
        <p class="text-sm text-slate-400 mt-1">
            Provide a YouTube video link or upload a long video. Ollama Gemma will extract the best 30s or 60s hooks.
        </p>
    </div>

    <!-- Main Card -->
    <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xl backdrop-blur-xl">
        <form method="POST" action="{{ route('projects.store') }}" enctype="multipart/form-data" @submit="isSubmitting = true" class="space-y-6">
            @csrf

            <!-- Source Type Switcher Tabs -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-3">
                    Video Source Type
                </label>
                <div class="grid grid-cols-2 gap-3 p-1.5 bg-slate-950 border border-slate-800 rounded-2xl">
                    <button type="button" @click="sourceType = 'youtube'"
                            :class="sourceType === 'youtube' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-slate-200'"
                            class="flex items-center justify-center gap-2 py-3 rounded-xl text-sm font-semibold transition">
                        <i class="fa-brands fa-youtube text-red-400"></i>
                        <span>YouTube Video Link</span>
                    </button>
                    <button type="button" @click="sourceType = 'upload'"
                            :class="sourceType === 'upload' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-slate-200'"
                            class="flex items-center justify-center gap-2 py-3 rounded-xl text-sm font-semibold transition">
                        <i class="fa-solid fa-cloud-arrow-up text-indigo-300"></i>
                        <span>Upload Video File</span>
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
                <p class="text-xs text-slate-500 mt-1.5 flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-info text-slate-400"></i>
                    <span>Works with podcasts, tutorials, speeches, interviews, documentaries, and vlog videos.</span>
                </p>
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
                       placeholder="e.g. AI Podcast Ep 12 Highlights">
                @error('title')
                    <p class="text-xs text-rose-400 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Settings: Duration & Count -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2">
                <!-- Clip Duration Preference -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">
                        Hook Duration (Sentence & Word-Boundary Cut)
                    </label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="clip_duration_pref" value="30" class="peer sr-only" checked>
                            <div class="p-3 text-center rounded-xl bg-slate-950 border border-slate-700/80 peer-checked:border-indigo-500 peer-checked:bg-indigo-950/40 peer-checked:text-indigo-300 text-slate-300 font-semibold text-xs sm:text-sm transition">
                                <i class="fa-regular fa-clock mr-1"></i>
                                <span>~30s Target</span>
                                <span class="block text-[10px] text-slate-400 font-normal mt-0.5">18s - 32s natural cut</span>
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
                    <select id="clips_count" name="clips_count"
                            class="w-full px-4 py-3 bg-slate-950 border border-slate-700/80 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="1">1 Short (Top Best Moment)</option>
                        <option value="2">2 Shorts</option>
                        <option value="3" selected>3 Shorts (Recommended)</option>
                        <option value="5">5 Shorts (Best for Podcasts)</option>
                    </select>
                </div>
            </div>

            <!-- Notice Banner -->
            <div class="p-4 rounded-2xl bg-indigo-950/40 border border-indigo-800/60 text-indigo-300 text-xs flex items-start gap-3">
                <i class="fa-solid fa-microchip text-base text-indigo-400 mt-0.5"></i>
                <div class="space-y-1">
                    <p class="font-semibold text-white">Smart Word Boundary & Subscribe Outro Enabled</p>
                    <p class="text-slate-400">
                        Ollama Gemma analyzes spoken thoughts so videos are clipped at natural sentence pauses (not cut off mid-word). Each short finishes at the complete thought and automatically embeds a high-converting <strong>Like, Comment & Subscribe</strong> call-to-action!
                    </p>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" :disabled="isSubmitting"
                    class="w-full bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 hover:from-indigo-500 hover:to-pink-500 text-white font-bold py-4 px-6 rounded-2xl shadow-xl shadow-indigo-600/30 transition hover:scale-[1.01] flex items-center justify-center gap-3 disabled:opacity-50">
                <template x-if="!isSubmitting">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                        <span>Start AI Shorts Extraction</span>
                    </div>
                </template>
                <template x-if="isSubmitting">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-spinner fa-spin"></i>
                        <span>Uploading & Dispatching to Queue...</span>
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
    }
}
</script>
@endpush
@endsection
