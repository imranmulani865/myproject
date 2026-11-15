@extends('layouts.app')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto rounded-[2rem] bg-studio-surface text-studio-ink border border-studio-line p-4 sm:p-6 lg:p-8 shadow-[0_22px_55px_rgba(48,40,166,0.08)]" x-data="projectDetail({{ $project->id }}, '{{ $project->status }}')">
    <!-- Breadcrumb & Top Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-6 bg-studio-canvas/80 border border-studio-line rounded-3xl shadow-sm">
        <div class="space-y-1.5">
            <div class="flex items-center gap-2 text-xs font-medium text-studio-muted">
                <a href="{{ route('dashboard') }}" class="hover:text-brand-600 transition flex items-center gap-1.5">
                    <i class="fa-solid fa-table-columns"></i>
                    <span>Dashboard</span>
                </a>
                <span>/</span>
                <span class="text-studio-ink">Shorts Studio</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-studio-ink flex items-center gap-3">
                <span class="line-clamp-1">{{ $project->title ?: 'Video Project #' . $project->id }}</span>
            </h1>
            <div class="flex flex-wrap items-center gap-2.5 text-xs text-studio-muted pt-1">
                <span class="inline-flex items-center gap-1.5 uppercase font-bold text-[10px] px-2.5 py-0.5 rounded-full {{ $project->source_type === 'youtube' ? 'bg-red-50 text-red-700 border border-red-200' : 'bg-studio-lavender text-brand-700 border border-brand-200' }}">
                    <i class="{{ $project->source_type === 'youtube' ? 'fa-brands fa-youtube' : 'fa-solid fa-file-video' }}"></i>
                    {{ $project->source_type }}
                </span>
                <span>•</span>
                <span class="text-studio-muted"><i class="fa-regular fa-clock mr-1 text-studio-muted"></i>Target: {{ $project->clip_duration_pref }}s clips</span>
                <span>•</span>
                <span>Total Video: {{ $project->duration_seconds ? sprintf('%02d:%02d', floor($project->duration_seconds / 60), $project->duration_seconds % 60) : 'N/A' }}</span>
                <span>•</span>
                <span>{{ $project->created_at->diffForHumans() }}</span>
            </div>
        </div>

        <div class="flex items-center gap-3 flex-wrap">
            @if($project->status === 'completed')
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>{{ $project->shorts->count() }} Shorts Ready</span>
                </div>
            @elseif($project->status === 'failed')
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold shadow-sm">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>Processing Failed</span>
                </div>
            @else
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-studio-lavender text-brand-700 border border-brand-200 text-xs font-bold shadow-sm">
                    <i class="fa-solid fa-circle-notch fa-spin text-brand-600"></i>
                    <span>Processing in background</span>
                </div>
            @endif

            <a href="{{ route('projects.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-2xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-lg shadow-brand-600/20 transition">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>New project</span>
            </a>

            <form action="{{ route('projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('Delete this project and all its generated shorts?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="p-2.5 text-studio-muted hover:text-rose-700 hover:bg-rose-50 rounded-2xl transition border border-studio-line" title="Delete Project">
                    <i class="fa-regular fa-trash-can"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Active Processing Live Status Card -->
    <div x-show="status === 'pending' || status === 'processing'" x-cloak class="p-8 rounded-3xl bg-studio-lavender/35 border border-brand-200 shadow-sm relative overflow-hidden">
        <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-brand-200/35 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-20 -top-20 w-80 h-80 bg-studio-surface/70 rounded-full blur-3xl pointer-events-none"></div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-6">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-brand-600 to-ocean-600 text-white flex items-center justify-center text-2xl flex-shrink-0 shadow-lg shadow-brand-600/30 animate-pulse">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                </div>
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-0.5 rounded-full bg-studio-surface text-brand-700 border border-brand-200 text-xs font-semibold mb-1">
                        <i class="fa-solid fa-brain"></i>
                        <span>Ollama Gemma AI + FFmpeg Engine</span>
                    </div>
                    <h3 class="text-xl font-bold text-studio-ink flex items-center gap-2">
                        <span>Creating 9:16 shorts</span>
                        <i class="fa-solid fa-circle-notch fa-spin text-sm text-brand-600"></i>
                    </h3>
                    <p class="text-sm text-studio-muted mt-1" x-text="progressStep || 'Processing background job...'"></p>
                </div>
            </div>
            <div class="text-right">
                <span class="text-4xl font-black font-mono text-brand-700" x-text="progressPercent + '%'"></span>
                <p class="text-xs text-studio-muted font-medium">Render Progress</p>
            </div>
        </div>

        <!-- Animated Gradient Progress Bar -->
        <div class="w-full bg-studio-surface rounded-full h-3.5 p-0.5 border border-studio-line mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-brand-500 via-ocean-500 to-aqua-500 h-2.5 rounded-full transition-all duration-700 relative"
                 :style="'width: ' + progressPercent + '%'">
                <div class="absolute inset-0 bg-white/25 animate-pulse rounded-full"></div>
            </div>
        </div>

        <!-- Safe to leave banner -->
        <div class="flex items-center justify-between flex-wrap gap-4 pt-4 border-t border-studio-line text-xs text-studio-muted">
            <div class="flex items-center gap-2.5">
                <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                <span>Processing continues in the background. You can leave this page and check your dashboard or notifications for updates.</span>
            </div>
            <div class="flex items-center gap-2 font-mono text-[11px] text-studio-muted">
                <span class="w-2 h-2 rounded-full bg-brand-500 animate-ping"></span>
                <span>Auto-refreshing status</span>
            </div>
        </div>
    </div>

    <!-- Error Card if Failed -->
    <div x-show="status === 'failed'" x-cloak class="p-6 rounded-3xl bg-rose-50 border border-rose-200 text-rose-800 shadow-sm">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-700 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div class="space-y-2">
                <h3 class="text-base font-bold text-rose-900">Video Processing Error</h3>
                <p class="text-sm text-rose-800 leading-relaxed" x-text="errorMessage || '{{ $project->error_message ?: 'An error occurred during video download or rendering.' }}'"></p>
                <div class="pt-2">
                    <a href="{{ route('projects.create') }}" class="inline-flex items-center gap-2 bg-rose-700 hover:bg-rose-800 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition shadow-sm">
                        <i class="fa-solid fa-rotate-right"></i>
                        <span>Start a new project</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- AI Video & Hook Analysis Showcase Banner -->
    @if($project->raw_analysis)
        <div class="p-6 rounded-3xl bg-gradient-to-r from-studio-lavender/70 via-studio-surface to-studio-canvas border border-brand-200 shadow-sm">
            <div class="flex items-center justify-between flex-wrap gap-2 mb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-studio-surface text-brand-600 flex items-center justify-center text-sm border border-brand-200">
                        <i class="fa-solid fa-brain"></i>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider text-brand-700">AI hook analysis</span>
                </div>
                <span class="text-[11px] px-2.5 py-0.5 rounded-full bg-studio-surface text-brand-700 border border-brand-200 font-semibold">
                    <i class="fa-solid fa-sparkles mr-1"></i>Ollama Gemma analysis
                </span>
            </div>
            <p class="text-sm text-studio-ink leading-relaxed">
                {{ $project->raw_analysis }}
            </p>
        </div>
    @endif

    <!-- Generated Shorts Section -->
    @if($project->shorts->isNotEmpty())
        <div class="space-y-6">
            <div class="flex items-center justify-between flex-wrap gap-4 pb-2 border-b border-studio-line">
                <div>
                    <h2 class="text-xl md:text-2xl font-black text-studio-ink flex items-center gap-2.5">
                        <i class="fa-solid fa-scissors text-brand-600"></i>
                        <span>Your shorts</span>
                        <span class="px-2.5 py-0.5 rounded-full bg-studio-lavender text-brand-700 text-xs font-bold border border-brand-200">
                            {{ $project->shorts->count() }} Clips
                        </span>
                    </h2>
                    <p class="text-xs text-studio-muted mt-1">
                        Each MP4 is rendered in 9:16 with a blurred background. Review the suggested details, then download and publish where you choose.
                    </p>
                </div>
                
                <div class="flex items-center gap-2">
                    <span class="text-xs text-studio-muted hidden sm:inline">Your next step:</span>
                    <span class="text-xs px-3 py-1 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 font-semibold flex items-center gap-1.5">
                        <i class="fa-solid fa-check text-[10px]"></i>
                        <span>Download and publish</span>
                    </span>
                </div>
            </div>

            <!-- List of Shorts Studio Cards -->
            <div class="space-y-8">
                @foreach($project->shorts as $index => $short)
                    <div class="bg-studio-surface border border-studio-line hover:border-brand-200 rounded-3xl p-6 sm:p-8 shadow-[0_12px_32px_rgba(48,40,166,0.07)] transition-colors duration-300"
                         x-data="{ copiedTitle: false, copiedDesc: false, copiedTags: false, copiedAll: false }">
                        <div class="flex flex-col lg:flex-row gap-8 items-start">
                            
                            <!-- LEFT: Smartphone Video Player Frame -->
                            <div class="w-full lg:w-72 flex-shrink-0 flex flex-col items-center">
                                <!-- Phone Mockup Container -->
                                <div class="relative w-64 max-w-full rounded-[2.5rem] p-2 bg-gradient-to-b from-slate-700 via-slate-800 to-slate-900 shadow-[0_20px_42px_rgba(21,27,69,0.24)] border border-slate-700/60 group">
                                    <!-- Speaker Bar -->
                                    <div class="w-16 h-1 bg-slate-600 rounded-full mx-auto mb-2 opacity-60"></div>
                                    
                                    <!-- Screen Window -->
                                    <div class="relative w-full aspect-[9/16] rounded-[2rem] overflow-hidden bg-black border border-slate-900">
                                        <video controls class="w-full h-full object-cover" poster="{{ $short->thumbnail_url }}" preload="metadata">
                                            <source src="{{ $short->video_url }}" type="video/mp4">
                                            Your browser does not support video.
                                        </video>

                                        <!-- Top Overlays -->
                                        <div class="absolute top-3 left-3 right-3 flex items-center justify-between pointer-events-none">
                                            <span class="px-2.5 py-1 rounded-full bg-black/70 backdrop-blur-md text-white text-[11px] font-bold border border-white/20">
                                                <i class="fa-solid fa-bolt text-yellow-400 mr-1"></i>Short #{{ $index + 1 }}
                                            </span>
                                            <span class="px-2 py-0.5 rounded-full bg-brand-600/80 backdrop-blur-md text-white text-[10px] font-bold">
                                                {{ $short->duration_seconds }}s
                                            </span>
                                        </div>

                                        <!-- Bottom Floating Badge -->
                                        <div class="absolute bottom-12 left-3 right-3 pointer-events-none">
                                            <div class="px-2.5 py-1 rounded-xl bg-slate-950/80 backdrop-blur-md border border-rose-500/40 text-[10px] text-rose-300 font-semibold flex items-center justify-center gap-1.5 shadow-lg">
                                                <i class="fa-solid fa-bell text-rose-400"></i>
                                                <span>Subscribe Outro Embedded</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Big Download Button -->
                                <a href="{{ $short->video_url }}" download="{{ \Illuminate\Support\Str::slug($short->title) }}.mp4"
                                   class="mt-4 w-64 inline-flex items-center justify-center gap-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm py-3 px-5 rounded-2xl shadow-lg shadow-emerald-600/20 transition-colors border border-emerald-600">
                                    <i class="fa-solid fa-cloud-arrow-down text-base"></i>
                                    <span>Download Short (MP4)</span>
                                </a>
                                <div class="flex items-center gap-2 text-[11px] text-studio-muted mt-1.5 font-mono">
                                    <span>{{ $short->formatted_file_size }}</span>
                                    <span>•</span>
                                    <span>1080x1920 (9:16 HD)</span>
                                </div>
                            </div>

                            <!-- RIGHT: Metadata & Copy Centre -->
                            <div class="flex-1 w-full space-y-5">
                                <!-- Top Badges & Timestamps -->
                                <div class="flex items-center justify-between flex-wrap gap-3 pb-3 border-b border-studio-line">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="px-3 py-1 rounded-xl bg-studio-lavender text-brand-700 text-xs font-bold border border-brand-200">
                                            <i class="fa-solid fa-film mr-1.5"></i>Short Clip #{{ $index + 1 }}
                                        </span>
                                        <span class="px-3 py-1 rounded-xl bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">
                                            <i class="fa-solid fa-stopwatch mr-1.5"></i>{{ $short->duration_seconds }} Seconds Cut
                                        </span>
                                        <span class="px-3 py-1 rounded-xl bg-rose-50 text-rose-700 text-xs font-bold border border-rose-200">
                                            <i class="fa-solid fa-bell mr-1.5"></i>Subscribe Call-to-Action Outro
                                        </span>
                                    </div>
                                    <div class="font-mono text-xs text-studio-ink bg-studio-canvas px-3 py-1.5 rounded-xl border border-studio-line">
                                        <i class="fa-solid fa-timeline mr-1 text-studio-muted"></i>
                                        {{ sprintf('%02d:%02d', floor($short->start_seconds/60), $short->start_seconds%60) }} - {{ sprintf('%02d:%02d', floor($short->end_seconds/60), $short->end_seconds%60) }}
                                    </div>
                                </div>

                                <!-- Hook Highlight Card -->
                                @if($short->hook_reason)
                                    <div class="p-4 rounded-2xl bg-studio-lavender/45 border border-brand-200 text-xs text-studio-ink">
                                        <div class="flex items-center gap-2 font-bold text-studio-ink mb-1">
                                            <i class="fa-solid fa-bolt text-amber-600"></i>
                                            <span>Why this moment was selected</span>
                                        </div>
                                        <p class="leading-relaxed text-studio-muted">{{ $short->hook_reason }}</p>
                                    </div>
                                @endif

                                <!-- Suggested Title Box -->
                                <div class="space-y-1.5">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="font-bold uppercase tracking-wider text-studio-ink flex items-center gap-1.5">
                                            <i class="fa-solid fa-heading text-brand-600"></i>
                                            <span>Suggested title</span>
                                        </span>
                                        <button @click="copyText(`{{ addslashes($short->title) }}`, 'Title copied!'); copiedTitle = true; setTimeout(() => copiedTitle = false, 2000)"
                                                class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-xl border transition"
                                                :class="copiedTitle ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-studio-lavender text-brand-700 border-brand-200 hover:bg-brand-200'">
                                            <i :class="copiedTitle ? 'fa-solid fa-check text-xs' : 'fa-regular fa-copy'"></i>
                                            <span x-text="copiedTitle ? 'Copied!' : 'Copy Title'"></span>
                                        </button>
                                    </div>
                                    <div class="p-3.5 bg-studio-canvas rounded-2xl border border-studio-line text-sm md:text-base font-bold text-studio-ink select-all leading-snug">
                                        {{ $short->title }}
                                    </div>
                                </div>

                                <!-- Description & CTA Box -->
                                <div class="space-y-1.5">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="font-bold uppercase tracking-wider text-studio-ink flex items-center gap-1.5">
                                            <i class="fa-solid fa-align-left text-brand-600"></i>
                                            <span>Suggested description</span>
                                        </span>
                                        <button @click="copyText(`{{ addslashes($short->description) }}`, 'Description copied!'); copiedDesc = true; setTimeout(() => copiedDesc = false, 2000)"
                                                class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-xl border transition"
                                                :class="copiedDesc ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-studio-lavender text-brand-700 border-brand-200 hover:bg-brand-200'">
                                            <i :class="copiedDesc ? 'fa-solid fa-check text-xs' : 'fa-regular fa-copy'"></i>
                                            <span x-text="copiedDesc ? 'Copied!' : 'Copy Description'"></span>
                                        </button>
                                    </div>
                                    <div class="p-4 bg-studio-canvas rounded-2xl border border-studio-line text-xs text-studio-ink whitespace-pre-line select-all max-h-36 overflow-y-auto leading-relaxed font-sans">
                                        {{ $short->description }}
                                    </div>
                                </div>

                                <!-- SEO Tags Chips & Copy -->
                                <div class="space-y-1.5">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="font-bold uppercase tracking-wider text-studio-ink flex items-center gap-1.5">
                                            <i class="fa-solid fa-tags text-brand-600"></i>
                                            <span>Suggested tags</span>
                                        </span>
                                        <button @click="copyText(`{{ addslashes($short->tags) }}`, 'Tags copied!'); copiedTags = true; setTimeout(() => copiedTags = false, 2000)"
                                                class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-xl border transition"
                                                :class="copiedTags ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-studio-lavender text-brand-700 border-brand-200 hover:bg-brand-200'">
                                            <i :class="copiedTags ? 'fa-solid fa-check text-xs' : 'fa-regular fa-copy'"></i>
                                            <span x-text="copiedTags ? 'Copied!' : 'Copy Tags'"></span>
                                        </button>
                                    </div>
                                    <div class="p-3 bg-studio-canvas rounded-2xl border border-studio-line flex flex-wrap gap-1.5">
                                        @foreach(explode(',', $short->tags) as $tag)
                                            @if(trim($tag))
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-studio-surface border border-studio-line text-[11px] text-studio-ink font-mono">
                                                    #{{ trim($tag) }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Copy suggested details together -->
                                <div class="pt-2 flex items-center justify-end">
                                    <button @click="copyAllKit(`{{ addslashes($short->title) }}`, `{{ addslashes($short->description) }}`, `{{ addslashes($short->tags) }}`); copiedAll = true; setTimeout(() => copiedAll = false, 2000)"
                                            class="inline-flex items-center gap-2 text-xs font-bold py-2.5 px-4 rounded-xl border transition"
                                            :class="copiedAll ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-studio-lavender hover:bg-brand-200 text-brand-700 border-brand-200'">
                                        <i :class="copiedAll ? 'fa-solid fa-check-double text-emerald-700' : 'fa-solid fa-copy text-brand-700'"></i>
                                        <span x-text="copiedAll ? 'Suggested details copied!' : 'Copy title, description and tags'"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
function projectDetail(projectId, initialStatus) {
    return {
        projectId: projectId,
        status: initialStatus,
        progressPercent: {{ $project->progress_percent }},
        progressStep: "{{ addslashes($project->progress_step) }}",
        errorMessage: "{{ addslashes($project->error_message ?? '') }}",
        pollInterval: null,

        init() {
            if (this.status === 'pending' || this.status === 'processing') {
                this.pollInterval = setInterval(() => {
                    this.checkStatus();
                }, 3000);
            }
        },

        async checkStatus() {
            try {
                const response = await fetch(`/api/projects/${this.projectId}/status`);
                const data = await response.json();
                
                this.status = data.status;
                this.progressPercent = data.progress_percent;
                this.progressStep = data.progress_step;
                this.errorMessage = data.error_message;

                if (data.is_completed) {
                    clearInterval(this.pollInterval);
                    window.location.reload();
                } else if (data.is_failed) {
                    clearInterval(this.pollInterval);
                }
            } catch (err) {
                console.error("Status polling error:", err);
            }
        },

        copyText(text, successMsg) {
            navigator.clipboard.writeText(text).then(() => {
                const toastData = Alpine.$data(document.body).toast;
                toastData.message = successMsg;
                toastData.show = true;
                setTimeout(() => {
                    toastData.show = false;
                }, 2500);
            }).catch(err => {
                console.error("Copy failed", err);
            });
        },

        copyAllKit(title, desc, tags) {
            const bundle = `${title}\n\n${desc}\n\nTags:\n${tags}`;
            this.copyText(bundle, 'Suggested title, description and tags copied!');
        }
    }
}
</script>
@endpush
@endsection
