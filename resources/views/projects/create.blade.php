@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-6" x-data="createProjectForm()">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('dashboard') }}" class="inline-flex min-h-10 items-center gap-2 text-sm font-semibold text-studio-muted hover:text-brand-600 transition-colors mb-3">
            <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
            <span>Back to Dashboard</span>
        </a>
        <h1 class="text-3xl font-bold text-studio-ink flex items-center gap-3">
            <span>Create a new project</span>
            <i class="fa-solid fa-clapperboard text-brand-600 text-2xl" aria-hidden="true"></i>
        </h1>
        <p class="text-sm text-studio-muted mt-2">
            Add a YouTube link or upload a video, then choose the shorts you want to create.
        </p>
    </div>

    <!-- Main Card -->
    <div class="bg-studio-surface border border-studio-line rounded-3xl p-6 sm:p-8 shadow-[0_18px_48px_rgba(48,40,166,0.08)]">
        <form method="POST" action="{{ route('projects.store') }}" enctype="multipart/form-data" @submit="isSubmitting = true" class="space-y-6">
            @csrf

            <!-- Source Type Switcher Tabs -->
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
                       placeholder="e.g. AI Podcast Ep 12 Highlights">
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
                                <span class="block text-[10px] text-studio-muted font-normal mt-0.5">18s - 32s natural cut</span>
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
                    <select id="clips_count" name="clips_count"
                            class="w-full px-4 py-3 bg-studio-canvas border border-studio-line rounded-xl text-studio-ink text-sm focus:outline-none focus:ring-2 focus:ring-brand-400/30 focus:border-brand-500">
                        <option value="1">1 Short (Top Best Moment)</option>
                        <option value="2">2 Shorts</option>
                        <option value="3" selected>3 Shorts (Recommended)</option>
                        <option value="5">5 Shorts (Best for Podcasts)</option>
                    </select>
                </div>
            </div>

            <!-- Notice Banner -->
            <div class="p-4 rounded-2xl bg-studio-lavender/60 border border-brand-200 text-studio-muted text-xs flex items-start gap-3">
                <i class="fa-solid fa-microchip text-base text-brand-600 mt-0.5"></i>
                <div class="space-y-1">
                    <p class="font-semibold text-studio-ink">Natural cuts and subscribe outro</p>
                    <p class="text-studio-muted">
                        Ollama Gemma looks for complete spoken thoughts and cuts near sentence pauses. Each short includes a <strong>Like, Comment & Subscribe</strong> outro.
                    </p>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" :disabled="isSubmitting"
                    class="w-full bg-brand-600 hover:bg-brand-700 text-white font-bold py-4 px-6 rounded-2xl shadow-lg shadow-brand-600/20 transition-colors flex items-center justify-center gap-3 disabled:opacity-50">
                <template x-if="!isSubmitting">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                        <span>Create shorts</span>
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
    }
}
</script>
@endpush
@endsection
