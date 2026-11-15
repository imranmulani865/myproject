@extends('layouts.app')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto" x-data="projectDetail({{ $project->id }}, '{{ $project->status }}')">
    <!-- Breadcrumb & Top Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-6 bg-slate-900/60 backdrop-blur-xl border border-slate-800 rounded-3xl shadow-xl">
        <div class="space-y-1.5">
            <div class="flex items-center gap-2 text-xs font-medium text-slate-400">
                <a href="{{ route('dashboard') }}" class="hover:text-indigo-400 transition flex items-center gap-1.5">
                    <i class="fa-solid fa-table-columns"></i>
                    <span>Dashboard</span>
                </a>
                <span>/</span>
                <span class="text-slate-300">Shorts Studio</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-white flex items-center gap-3">
                <span class="line-clamp-1">{{ $project->title ?: 'Video Project #' . $project->id }}</span>
            </h1>
            <div class="flex flex-wrap items-center gap-2.5 text-xs text-slate-400 pt-1">
                <span class="inline-flex items-center gap-1.5 uppercase font-bold text-[10px] px-2.5 py-0.5 rounded-full {{ $project->source_type === 'youtube' ? 'bg-red-500/10 text-red-400 border border-red-500/30' : 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/30' }}">
                    <i class="{{ $project->source_type === 'youtube' ? 'fa-brands fa-youtube' : 'fa-solid fa-file-video' }}"></i>
                    {{ $project->source_type }}
                </span>
                <span>•</span>
                <span class="text-slate-300"><i class="fa-regular fa-clock mr-1 text-slate-400"></i>Target: {{ $project->clip_duration_pref }}s clips</span>
                <span>•</span>
                <span>Total Video: {{ $project->duration_seconds ? sprintf('%02d:%02d', floor($project->duration_seconds / 60), $project->duration_seconds % 60) : 'N/A' }}</span>
                <span>•</span>
                <span>{{ $project->created_at->diffForHumans() }}</span>
            </div>
        </div>

        <div class="flex items-center gap-3 flex-wrap">
            @if($project->status === 'completed')
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-emerald-950/80 text-emerald-400 border border-emerald-800 text-xs font-bold shadow-lg shadow-emerald-950/50">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>{{ $project->shorts->count() }} Shorts Ready</span>
                </div>
            @elseif($project->status === 'failed')
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-rose-950/80 text-rose-400 border border-rose-800 text-xs font-bold shadow-lg shadow-rose-950/50">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>Processing Failed</span>
                </div>
            @else
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-indigo-950/80 text-indigo-300 border border-indigo-800 text-xs font-bold shadow-lg shadow-indigo-950/50">
                    <i class="fa-solid fa-circle-notch fa-spin text-indigo-400"></i>
                    <span>Processing Background Queue</span>
                </div>
            @endif

            <a href="{{ route('projects.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-2xl bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white text-xs font-bold shadow-lg shadow-indigo-600/25 transition">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>New Video</span>
            </a>

            <form action="{{ route('projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('Delete this project and all its generated shorts?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="p-2.5 text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 rounded-2xl transition border border-slate-800" title="Delete Project">
                    <i class="fa-regular fa-trash-can"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Active Processing Live Status Card -->
    <div x-show="status === 'pending' || status === 'processing'" x-cloak class="p-8 rounded-3xl bg-slate-900/90 border border-indigo-500/40 shadow-2xl relative overflow-hidden backdrop-blur-2xl">
        <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-indigo-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-20 -top-20 w-80 h-80 bg-purple-500/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-6">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-indigo-600 to-purple-600 text-white flex items-center justify-center text-2xl flex-shrink-0 shadow-lg shadow-indigo-600/30 animate-pulse">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                </div>
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-0.5 rounded-full bg-indigo-500/10 text-indigo-300 border border-indigo-500/20 text-xs font-semibold mb-1">
                        <i class="fa-solid fa-brain"></i>
                        <span>Ollama Gemma AI + FFmpeg Engine</span>
                    </div>
                    <h3 class="text-xl font-bold text-white flex items-center gap-2">
                        <span>Rendering Viral 9:16 Shorts</span>
                        <i class="fa-solid fa-circle-notch fa-spin text-sm text-indigo-400"></i>
                    </h3>
                    <p class="text-sm text-indigo-300 mt-1" x-text="progressStep || 'Processing background job...'"></p>
                </div>
            </div>
            <div class="text-right">
                <span class="text-4xl font-black font-mono bg-clip-text text-transparent bg-gradient-to-r from-indigo-300 via-purple-300 to-pink-300" x-text="progressPercent + '%'"></span>
                <p class="text-xs text-slate-400 font-medium">Render Progress</p>
            </div>
        </div>

        <!-- Animated Gradient Progress Bar -->
        <div class="w-full bg-slate-950 rounded-full h-3.5 p-0.5 border border-slate-800 mb-6 overflow-hidden shadow-inner">
            <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 h-2.5 rounded-full transition-all duration-700 relative"
                 :style="'width: ' + progressPercent + '%'">
                <div class="absolute inset-0 bg-white/25 animate-pulse rounded-full"></div>
            </div>
        </div>

        <!-- Safe to leave banner -->
        <div class="flex items-center justify-between flex-wrap gap-4 pt-4 border-t border-slate-800/80 text-xs text-slate-400">
            <div class="flex items-center gap-2.5">
                <i class="fa-solid fa-circle-check text-emerald-400 text-sm"></i>
                <span>Queue worker is active in background. You can leave this page anytime and check your dashboard or notification bell!</span>
            </div>
            <div class="flex items-center gap-2 font-mono text-[11px] text-slate-500">
                <span class="w-2 h-2 rounded-full bg-indigo-500 animate-ping"></span>
                <span>Auto-refreshing status</span>
            </div>
        </div>
    </div>

    <!-- Error Card if Failed -->
    <div x-show="status === 'failed'" x-cloak class="p-6 rounded-3xl bg-rose-950/40 border border-rose-800/80 text-rose-200 shadow-2xl">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-2xl bg-rose-500/20 text-rose-400 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div class="space-y-2">
                <h3 class="text-base font-bold text-white">Video Processing Error</h3>
                <p class="text-sm text-rose-300 leading-relaxed" x-text="errorMessage || '{{ $project->error_message ?: 'An error occurred during video download or rendering.' }}'"></p>
                <div class="pt-2">
                    <a href="{{ route('projects.create') }}" class="inline-flex items-center gap-2 bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition shadow-lg shadow-rose-600/30">
                        <i class="fa-solid fa-rotate-right"></i>
                        <span>Try Again</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- AI Video & Hook Analysis Showcase Banner -->
    @if($project->raw_analysis)
        <div class="p-6 rounded-3xl bg-gradient-to-r from-slate-900/90 via-purple-950/20 to-slate-900/90 border border-purple-500/30 shadow-2xl backdrop-blur-xl">
            <div class="flex items-center justify-between flex-wrap gap-2 mb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-purple-500/20 text-purple-300 flex items-center justify-center text-sm border border-purple-500/30">
                        <i class="fa-solid fa-brain"></i>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider text-purple-300">AI Hook & Mukhda Analysis</span>
                </div>
                <span class="text-[11px] px-2.5 py-0.5 rounded-full bg-purple-500/10 text-purple-300 border border-purple-500/20 font-semibold">
                    <i class="fa-solid fa-sparkles mr-1"></i>Ollama Gemma Intelligence
                </span>
            </div>
            <p class="text-sm text-slate-300 leading-relaxed">
                {{ $project->raw_analysis }}
            </p>
        </div>
    @endif

    <!-- Generated Shorts Section -->
    @if($project->shorts->isNotEmpty())
        <div class="space-y-6">
            <div class="flex items-center justify-between flex-wrap gap-4 pb-2 border-b border-slate-800">
                <div>
                    <h2 class="text-xl md:text-2xl font-black text-white flex items-center gap-2.5">
                        <i class="fa-solid fa-scissors text-indigo-400"></i>
                        <span>Extracted Shorts Studio</span>
                        <span class="px-2.5 py-0.5 rounded-full bg-indigo-500/20 text-indigo-300 text-xs font-bold border border-indigo-500/30">
                            {{ $project->shorts->count() }} Clips
                        </span>
                    </h2>
                    <p class="text-xs text-slate-400 mt-1">
                        Formatted in 9:16 vertical HD (1080x1920) with blurred background & Channel Subscribe outro embedded.
                    </p>
                </div>
                
                <div class="flex items-center gap-2">
                    <span class="text-xs text-slate-400 hidden sm:inline">1-Click Viral Publishing:</span>
                    <span class="text-xs px-3 py-1 rounded-xl bg-slate-800/80 text-emerald-400 border border-slate-700/80 font-semibold flex items-center gap-1.5">
                        <i class="fa-solid fa-check text-[10px]"></i>
                        <span>Ready to Post</span>
                    </span>
                </div>
            </div>

            <!-- List of Shorts Studio Cards -->
            <div class="space-y-8">
                @foreach($project->shorts as $index => $short)
                    <div class="bg-slate-900/90 border border-slate-800 hover:border-slate-700 rounded-3xl p-6 sm:p-8 shadow-2xl backdrop-blur-2xl transition duration-300"
                         x-data="{ copiedTitle: false, copiedDesc: false, copiedTags: false, copiedAll: false }">
                        <div class="flex flex-col lg:flex-row gap-8 items-start">
                            
                            <!-- LEFT: Smartphone Video Player Frame -->
                            <div class="w-full lg:w-72 flex-shrink-0 flex flex-col items-center">
                                <!-- Phone Mockup Container -->
                                <div class="relative w-64 max-w-full rounded-[2.5rem] p-2 bg-gradient-to-b from-slate-700 via-slate-800 to-slate-900 shadow-2xl shadow-black/80 border border-slate-700/60 group">
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
                                            <span class="px-2 py-0.5 rounded-full bg-indigo-600/80 backdrop-blur-md text-white text-[10px] font-bold">
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
                                   class="mt-4 w-64 inline-flex items-center justify-center gap-2.5 bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-sm py-3 px-5 rounded-2xl shadow-xl shadow-emerald-950/80 transition hover:scale-[1.02] border border-emerald-500/30">
                                    <i class="fa-solid fa-cloud-arrow-down text-base"></i>
                                    <span>Download Short (MP4)</span>
                                </a>
                                <div class="flex items-center gap-2 text-[11px] text-slate-400 mt-1.5 font-mono">
                                    <span>{{ $short->formatted_file_size }}</span>
                                    <span>•</span>
                                    <span>1080x1920 (9:16 HD)</span>
                                </div>
                            </div>

                            <!-- RIGHT: Metadata & Copy Centre -->
                            <div class="flex-1 w-full space-y-5">
                                <!-- Top Badges & Timestamps -->
                                <div class="flex items-center justify-between flex-wrap gap-3 pb-3 border-b border-slate-800">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="px-3 py-1 rounded-xl bg-indigo-500/10 text-indigo-300 text-xs font-bold border border-indigo-500/30">
                                            <i class="fa-solid fa-film mr-1.5"></i>Short Clip #{{ $index + 1 }}
                                        </span>
                                        <span class="px-3 py-1 rounded-xl bg-emerald-500/10 text-emerald-300 text-xs font-bold border border-emerald-500/30">
                                            <i class="fa-solid fa-stopwatch mr-1.5"></i>{{ $short->duration_seconds }} Seconds Cut
                                        </span>
                                        <span class="px-3 py-1 rounded-xl bg-rose-500/10 text-rose-300 text-xs font-bold border border-rose-500/30">
                                            <i class="fa-solid fa-bell mr-1.5"></i>Subscribe Call-to-Action Outro
                                        </span>
                                    </div>
                                    <div class="font-mono text-xs text-slate-300 bg-slate-950 px-3 py-1.5 rounded-xl border border-slate-800">
                                        <i class="fa-solid fa-timeline mr-1 text-slate-400"></i>
                                        {{ sprintf('%02d:%02d', floor($short->start_seconds/60), $short->start_seconds%60) }} - {{ sprintf('%02d:%02d', floor($short->end_seconds/60), $short->end_seconds%60) }}
                                    </div>
                                </div>

                                <!-- Hook Highlight Card -->
                                @if($short->hook_reason)
                                    <div class="p-4 rounded-2xl bg-gradient-to-r from-purple-950/40 via-indigo-950/30 to-purple-950/40 border border-purple-500/30 text-xs text-purple-200">
                                        <div class="flex items-center gap-2 font-bold text-white mb-1">
                                            <i class="fa-solid fa-bolt text-yellow-400"></i>
                                            <span>Why This Hook Performs (Scroll-Stopping Reason):</span>
                                        </div>
                                        <p class="leading-relaxed text-purple-200/90">{{ $short->hook_reason }}</p>
                                    </div>
                                @endif

                                <!-- Viral Title Box -->
                                <div class="space-y-1.5">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="font-bold uppercase tracking-wider text-slate-300 flex items-center gap-1.5">
                                            <i class="fa-solid fa-heading text-indigo-400"></i>
                                            <span>Viral Short Title</span>
                                        </span>
                                        <button @click="copyText(`{{ addslashes($short->title) }}`, 'Title copied!'); copiedTitle = true; setTimeout(() => copiedTitle = false, 2000)"
                                                class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-xl transition"
                                                :class="copiedTitle ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/40' : 'bg-slate-800 text-indigo-400 hover:text-white hover:bg-indigo-600'">
                                            <i :class="copiedTitle ? 'fa-solid fa-check text-xs' : 'fa-regular fa-copy'"></i>
                                            <span x-text="copiedTitle ? 'Copied!' : 'Copy Title'"></span>
                                        </button>
                                    </div>
                                    <div class="p-3.5 bg-slate-950 rounded-2xl border border-slate-800 text-sm md:text-base font-bold text-white select-all shadow-inner leading-snug">
                                        {{ $short->title }}
                                    </div>
                                </div>

                                <!-- Description & CTA Box -->
                                <div class="space-y-1.5">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="font-bold uppercase tracking-wider text-slate-300 flex items-center gap-1.5">
                                            <i class="fa-solid fa-align-left text-purple-400"></i>
                                            <span>SEO Description & Hashtags</span>
                                        </span>
                                        <button @click="copyText(`{{ addslashes($short->description) }}`, 'Description copied!'); copiedDesc = true; setTimeout(() => copiedDesc = false, 2000)"
                                                class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-xl transition"
                                                :class="copiedDesc ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/40' : 'bg-slate-800 text-indigo-400 hover:text-white hover:bg-indigo-600'">
                                            <i :class="copiedDesc ? 'fa-solid fa-check text-xs' : 'fa-regular fa-copy'"></i>
                                            <span x-text="copiedDesc ? 'Copied!' : 'Copy Description'"></span>
                                        </button>
                                    </div>
                                    <div class="p-4 bg-slate-950 rounded-2xl border border-slate-800 text-xs text-slate-300 whitespace-pre-line select-all max-h-36 overflow-y-auto leading-relaxed font-sans shadow-inner">
                                        {{ $short->description }}
                                    </div>
                                </div>

                                <!-- SEO Tags Chips & Copy -->
                                <div class="space-y-1.5">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="font-bold uppercase tracking-wider text-slate-300 flex items-center gap-1.5">
                                            <i class="fa-solid fa-tags text-pink-400"></i>
                                            <span>Keywords & Tags</span>
                                        </span>
                                        <button @click="copyText(`{{ addslashes($short->tags) }}`, 'Tags copied!'); copiedTags = true; setTimeout(() => copiedTags = false, 2000)"
                                                class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-xl transition"
                                                :class="copiedTags ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/40' : 'bg-slate-800 text-indigo-400 hover:text-white hover:bg-indigo-600'">
                                            <i :class="copiedTags ? 'fa-solid fa-check text-xs' : 'fa-regular fa-copy'"></i>
                                            <span x-text="copiedTags ? 'Copied!' : 'Copy Tags'"></span>
                                        </button>
                                    </div>
                                    <div class="p-3 bg-slate-950 rounded-2xl border border-slate-800 shadow-inner flex flex-wrap gap-1.5">
                                        @foreach(explode(',', $short->tags) as $tag)
                                            @if(trim($tag))
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-900 border border-slate-800 text-[11px] text-slate-300 font-mono">
                                                    #{{ trim($tag) }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>

                                <!-- 1-Click All-in-One Copy Pack -->
                                <div class="pt-2 flex items-center justify-end">
                                    <button @click="copyAllKit(`{{ addslashes($short->title) }}`, `{{ addslashes($short->description) }}`, `{{ addslashes($short->tags) }}`); copiedAll = true; setTimeout(() => copiedAll = false, 2000)"
                                            class="inline-flex items-center gap-2 bg-slate-800/80 hover:bg-slate-800 text-slate-200 hover:text-white text-xs font-bold py-2.5 px-4 rounded-xl border border-slate-700/80 transition"
                                            :class="copiedAll ? 'bg-emerald-600/20 text-emerald-400 border-emerald-500/50' : ''">
                                        <i :class="copiedAll ? 'fa-solid fa-check-double text-emerald-400' : 'fa-solid fa-copy text-indigo-400'"></i>
                                        <span x-text="copiedAll ? 'Entire Creator Kit Copied!' : 'Copy All (Title + Description + Tags)'"></span>
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
            this.copyText(bundle, 'Full Creator Kit (Title + Description + Tags) copied!');
        }
    }
}
</script>
@endpush
@endsection
