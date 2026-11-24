@extends('layouts.app')

@section('title', ($project->title ?: 'Video Project #' . $project->id) . ' • Shorts Studio')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto rounded-[1.75rem] bg-[#17223A] text-white border border-[#34415A] p-4 sm:p-6 lg:p-8 shadow-[0_24px_70px_rgba(21,34,56,.18)]" x-data="projectDetail({{ $project->id }}, '{{ $project->status }}')">
    <!-- Breadcrumb & Top Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-5 p-6 bg-white/[0.02] border border-white/[0.06] rounded-3xl shadow-sm">
        <div class="space-y-2">
            <div class="flex items-center gap-2 text-xs font-bold text-zinc-400">
                <a href="{{ route('dashboard') }}" class="hover:text-white transition flex items-center gap-1.5">
                    <i class="fa-solid fa-table-columns text-rose-500"></i>
                    <span>Dashboard</span>
                </a>
                <span>/</span>
                <span class="text-white">Shorts Studio</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-black text-white tracking-tight flex items-center gap-3">
                <span class="line-clamp-1">{{ $project->title ?: 'Video Project #' . $project->id }}</span>
            </h1>
            <div class="flex flex-wrap items-center gap-2.5 text-xs text-zinc-400 pt-1 font-medium">
                <span class="inline-flex items-center gap-1.5 uppercase font-bold text-[10px] px-2.5 py-1 rounded-lg {{ $project->source_type === 'youtube' ? 'bg-red-500/15 text-rose-400 border border-rose-500/25' : 'bg-indigo-500/15 text-indigo-300 border border-indigo-500/25' }}">
                    <i class="{{ $project->source_type === 'youtube' ? 'fa-brands fa-youtube' : 'fa-solid fa-file-video' }}"></i>
                    {{ $project->source_type }}
                </span>
                <span class="text-zinc-600">•</span>
                <span class="inline-flex items-center gap-1 font-bold text-[10px] px-2.5 py-1 rounded-lg {{ $project->mode === 'song' ? 'bg-pink-500/15 text-pink-300 border border-pink-500/25' : 'bg-indigo-500/15 text-indigo-300 border border-indigo-500/25' }}">
                    <i class="{{ $project->mode === 'song' ? 'fa-solid fa-music' : 'fa-solid fa-microphone' }}"></i>
                    {{ ucfirst($project->mode ?? 'dialogue') }} Mode
                </span>
                @if($project->tokens_used > 0)
                    <span class="text-zinc-600">•</span>
                    <span class="text-amber-400 text-xs font-bold flex items-center gap-1">
                        <i class="fa-solid fa-coins text-[10px]"></i> 1 Video Credit
                    </span>
                @endif
                <span class="text-zinc-600">•</span>
                <span class="text-zinc-300"><i class="fa-solid fa-scissors mr-1 text-rose-400"></i>{{ $project->clips_count == 0 ? 'All Possible Cuts' : $project->clips_count . ' Cuts' }}</span>
                <span class="text-zinc-600">•</span>
                <span>Duration: {{ $project->duration_seconds ? sprintf('%02d:%02d', floor($project->duration_seconds / 60), $project->duration_seconds % 60) : 'N/A' }}</span>
                <span class="text-zinc-600">•</span>
                <span>{{ $project->created_at->diffForHumans() }}</span>
            </div>
        </div>

        <div class="flex items-center gap-3 flex-wrap">
            @if($project->status === 'completed')
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 text-xs font-bold shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>{{ $project->shorts->count() }} Shorts Ready</span>
                </div>
            @elseif($project->status === 'failed')
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-rose-500/15 text-rose-400 border border-rose-500/30 text-xs font-bold shadow-sm">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>Processing Failed</span>
                </div>
            @else
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-amber-500/15 text-amber-400 border border-amber-500/30 text-xs font-bold shadow-sm">
                    <i class="fa-solid fa-circle-notch fa-spin text-amber-400"></i>
                    <span>Processing in Background</span>
                </div>
            @endif

            <a href="{{ route('projects.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#4D5CFF] hover:bg-[#3949E9] text-white text-xs font-bold shadow-[0_8px_18px_rgba(77,92,255,.2)] transition">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>New Project</span>
            </a>

            <form action="{{ route('projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('Delete this project and all its generated shorts?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="p-2.5 text-zinc-400 hover:text-rose-400 hover:bg-rose-500/10 rounded-2xl transition border border-white/[0.08]" title="Delete Project">
                    <i class="fa-regular fa-trash-can text-sm"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="flex items-center gap-3 rounded-2xl border border-[#53648B] bg-[#263555] p-4 text-xs text-[#DCE4F5] sm:text-sm">
        <i class="fa-solid fa-download text-[#9AA5FF]" aria-hidden="true"></i>
        <span>Download finished shorts to your device to keep your own copy.</span>
    </div>

    <!-- Active Processing Live Status Card -->
    <div x-show="status === 'pending' || status === 'processing'" x-cloak class="p-8 rounded-3xl bg-white/[0.03] border border-white/[0.1] shadow-2xl relative overflow-hidden">
        <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-rose-600/[0.15] rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-20 -top-20 w-80 h-80 bg-indigo-600/[0.1] rounded-full blur-3xl pointer-events-none"></div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-6 relative z-10">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-2xl bg-[#4D5CFF] text-white flex items-center justify-center text-2xl flex-shrink-0 shadow-[0_10px_22px_rgba(77,92,255,.2)]">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                </div>
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-0.5 rounded-full bg-white/[0.06] text-rose-300 border border-white/[0.1] text-xs font-bold mb-1.5">
                        <i class="fa-solid fa-brain text-rose-400"></i>
                        <span>Ollama Gemma AI + FFmpeg Engine</span>
                    </div>
                    <h3 class="text-xl font-black text-white flex items-center gap-2">
                        <span>Creating 9:16 Shorts</span>
                        <i class="fa-solid fa-circle-notch fa-spin text-sm text-rose-400"></i>
                    </h3>
                    <p class="text-xs sm:text-sm text-zinc-400 mt-1" x-text="progressStep || 'Processing background job...'"></p>
                </div>
            </div>
            <div class="text-right">
                <span class="font-['Space_Grotesk'] text-4xl font-bold text-[#9AA5FF]" x-text="progressPercent + '%'"></span>
                <p class="text-xs text-zinc-400 font-bold uppercase tracking-wider mt-1">Render Progress</p>
            </div>
        </div>

        <!-- Animated Shimmer Progress Bar -->
        <div class="w-full bg-black/60 rounded-full h-3.5 p-0.5 border border-white/[0.1] mb-6 overflow-hidden relative z-10">
            <div class="bg-gradient-to-r from-[#4D5CFF] to-[#FF735B] h-2.5 rounded-full transition-all duration-700 relative"
                 :style="'width: ' + progressPercent + '%'">
                <div class="absolute inset-0 bg-white/30 animate-pulse rounded-full"></div>
            </div>
        </div>

        <!-- Safe to leave banner -->
        <div class="flex items-center justify-between flex-wrap gap-4 pt-4 border-t border-white/[0.06] text-xs text-zinc-400 relative z-10">
            <div class="flex items-center gap-2.5">
                <i class="fa-solid fa-circle-check text-emerald-400 text-sm"></i>
                <span>Processing runs smoothly in the background. You can leave this page anytime and check your dashboard for updates.</span>
            </div>
            <div class="flex items-center gap-2 font-mono text-[11px] text-zinc-400">
                <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
                <span>Auto-refreshing status</span>
            </div>
        </div>
    </div>

    <!-- Error Card if Failed -->
    <div x-show="status === 'failed'" x-cloak class="p-6 rounded-3xl bg-rose-950/70 border border-rose-500/30 text-rose-200 shadow-xl backdrop-blur-xl">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-2xl bg-rose-500/20 text-rose-400 flex items-center justify-center text-xl flex-shrink-0 border border-rose-500/30">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div class="space-y-2">
                <h3 class="text-base font-black text-white">Video Processing Error</h3>
                <p class="text-xs sm:text-sm text-rose-300 leading-relaxed" x-text="errorMessage || '{{ $project->error_message ?: 'An error occurred during video download or rendering.' }}'"></p>
                <div class="pt-2">
                    <a href="{{ route('projects.create') }}" class="inline-flex items-center gap-2 bg-[#4D5CFF] hover:bg-[#3949E9] text-white text-xs font-bold px-4 py-2.5 rounded-xl transition shadow-md">
                        <i class="fa-solid fa-rotate-right text-xs"></i>
                        <span>Start a New Project</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- AI Video & Hook Analysis Showcase Banner -->
    @if($project->raw_analysis)
        <div class="p-6 rounded-3xl bg-white/[0.03] border border-white/[0.08] shadow-xl">
            <div class="flex items-center justify-between flex-wrap gap-2 mb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-indigo-500/15 text-indigo-400 flex items-center justify-center text-sm border border-indigo-500/25">
                        <i class="fa-solid fa-brain"></i>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-300">AI Hook Analysis</span>
                </div>
                <span class="text-[11px] px-3 py-1 rounded-full bg-white/[0.05] text-zinc-300 border border-white/[0.1] font-semibold">
                    <i class="fa-solid fa-sparkles text-amber-400 mr-1"></i>Ollama Gemma Analysis
                </span>
            </div>
            <p class="text-xs sm:text-sm text-zinc-300 leading-relaxed font-normal">
                {{ $project->raw_analysis }}
            </p>
        </div>
    @endif

    <!-- Generated Shorts Section -->
    @if($project->shorts->isNotEmpty())
        <div class="space-y-6">
            <div class="flex items-center justify-between flex-wrap gap-4 pb-3 border-b border-white/[0.08]">
                <div>
                    <h2 class="text-xl md:text-2xl font-black text-white tracking-tight flex items-center gap-2.5">
                        <i class="fa-solid fa-scissors text-rose-500"></i>
                        <span>Generated Shorts</span>
                        <span class="px-3 py-0.5 rounded-full bg-rose-500/15 text-rose-400 text-xs font-black border border-rose-500/30">
                            {{ $project->shorts->count() }} Clips
                        </span>
                    </h2>
                    <p class="text-xs text-zinc-400 mt-1">
                        Each MP4 is rendered for vertical viewing. Preview, download, and publish your finished shorts.
                    </p>
                </div>
                
                <div class="flex items-center gap-2">
                    <span class="text-xs px-3 py-1.5 rounded-xl bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 font-bold flex items-center gap-1.5">
                        <i class="fa-solid fa-check text-[10px]"></i>
                        <span>Ready to Publish</span>
                    </span>
                </div>
            </div>

            <!-- List of Shorts Studio Cards -->
            <div class="space-y-8">
                @foreach($project->shorts as $index => $short)
                    <div class="bg-[#141722] border border-white/[0.08] hover:border-rose-500/40 rounded-3xl p-6 sm:p-8 shadow-2xl transition-all duration-300"
                         x-data="{ copiedTitle: false, copiedDesc: false, copiedTags: false, copiedAll: false }">
                        <div class="flex flex-col lg:flex-row gap-8 items-start">
                            
                            <!-- LEFT: Smartphone Video Player Frame -->
                            <div class="w-full lg:w-72 flex-shrink-0 flex flex-col items-center">
                                <!-- Phone Mockup Container -->
                                <div class="relative w-64 max-w-full rounded-[2.5rem] p-2.5 bg-gradient-to-b from-zinc-700 via-zinc-800 to-zinc-900 shadow-2xl border border-white/[0.15] group">
                                    <!-- Speaker Bar -->
                                    <div class="w-16 h-1 bg-zinc-600 rounded-full mx-auto mb-2 opacity-60"></div>
                                    
                                    <!-- Screen Window -->
                                    <div class="relative w-full aspect-[9/16] rounded-[2rem] overflow-hidden bg-black border border-black shadow-inner">
                                        <video controls class="w-full h-full object-cover" poster="{{ $short->thumbnail_url }}" preload="metadata">
                                            <source src="{{ $short->video_url }}" type="video/mp4">
                                            Your browser does not support video.
                                        </video>

                                        <!-- Top Overlays -->
                                        <div class="absolute top-3 left-3 right-3 flex items-center justify-between pointer-events-none">
                                            <span class="px-2.5 py-1 rounded-full bg-black/80 backdrop-blur-md text-white text-[10px] font-black border border-white/20">
                                                <i class="fa-solid fa-bolt text-yellow-400 mr-1"></i>Short #{{ $index + 1 }}
                                            </span>
                                            <span class="px-2 py-0.5 rounded-full bg-rose-600/90 backdrop-blur-md text-white text-[10px] font-bold">
                                                {{ $short->duration_seconds }}s
                                            </span>
                                        </div>

                                        <!-- Bottom Floating Badge -->
                                        <div class="absolute bottom-12 left-3 right-3 pointer-events-none">
                                            <div class="px-2.5 py-1 rounded-xl bg-black/80 backdrop-blur-md border border-rose-500/40 text-[10px] text-rose-300 font-semibold flex items-center justify-center gap-1.5 shadow-lg">
                                                <i class="fa-solid fa-bell text-rose-400"></i>
                                                <span>Subscribe Outro Embedded</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Big Download Button -->
                                <a href="{{ $short->video_url }}" download="{{ \Illuminate\Support\Str::slug($short->title) }}.mp4"
                                   class="mt-4 w-64 inline-flex items-center justify-center gap-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-black text-xs sm:text-sm py-3.5 px-5 rounded-2xl shadow-xl shadow-emerald-600/30 transition hover:scale-[1.02] active:scale-[0.98]">
                                    <i class="fa-solid fa-cloud-arrow-down text-base"></i>
                                    <span>Download Short (MP4)</span>
                                </a>
                                <div class="flex items-center gap-2 text-[11px] text-zinc-400 mt-2 font-mono">
                                    <span>{{ $short->formatted_file_size }}</span>
                                    <span>•</span>
                                    <span>1080x1920 (9:16 HD)</span>
                                </div>
                            </div>

                            <!-- RIGHT: Metadata & Copy Centre -->
                            <div class="flex-1 w-full space-y-5">
                                <!-- Top Badges & Timestamps -->
                                <div class="flex items-center justify-between flex-wrap gap-3 pb-3 border-b border-white/[0.08]">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="px-3 py-1 rounded-xl bg-rose-500/15 text-rose-400 text-xs font-bold border border-rose-500/30">
                                            <i class="fa-solid fa-film mr-1.5"></i>Short Clip #{{ $index + 1 }}
                                        </span>
                                        <span class="px-3 py-1 rounded-xl bg-emerald-500/15 text-emerald-400 text-xs font-bold border border-emerald-500/30">
                                            <i class="fa-solid fa-stopwatch mr-1.5"></i>{{ $short->duration_seconds }} Seconds Cut
                                        </span>
                                        <span class="px-3 py-1 rounded-xl bg-white/[0.05] text-zinc-300 text-xs font-bold border border-white/[0.1]">
                                            <i class="fa-solid fa-bell mr-1.5 text-rose-400"></i>Subscribe CTA Outro
                                        </span>
                                    </div>
                                    <div class="font-mono text-xs text-white bg-black/50 px-3 py-1.5 rounded-xl border border-white/[0.08]">
                                        <i class="fa-solid fa-timeline mr-1 text-zinc-500"></i>
                                        {{ sprintf('%02d:%02d', floor($short->start_seconds/60), $short->start_seconds%60) }} - {{ sprintf('%02d:%02d', floor($short->end_seconds/60), $short->end_seconds%60) }}
                                    </div>
                                </div>

                                <!-- Hook Highlight Card -->
                                @if($short->hook_reason)
                                    <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-xs text-zinc-200">
                                        <div class="flex items-center gap-2 font-bold text-amber-300 mb-1">
                                            <i class="fa-solid fa-bolt text-amber-400"></i>
                                            <span>Why this moment was selected</span>
                                        </div>
                                        <p class="leading-relaxed text-zinc-300 font-normal">{{ $short->hook_reason }}</p>
                                    </div>
                                @endif

                                <!-- Suggested Title Box -->
                                <div class="space-y-1.5">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="font-bold uppercase tracking-wider text-zinc-300 flex items-center gap-1.5">
                                            <i class="fa-solid fa-heading text-rose-500"></i>
                                            <span>Suggested Title</span>
                                        </span>
                                        <button @click="copyText(`{{ addslashes($short->title) }}`, 'Title copied!'); copiedTitle = true; setTimeout(() => copiedTitle = false, 2000)"
                                                class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-xl border transition"
                                                :class="copiedTitle ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40' : 'bg-white/[0.05] text-zinc-300 border-white/[0.1] hover:bg-white/[0.1] hover:text-white'">
                                            <i :class="copiedTitle ? 'fa-solid fa-check text-xs' : 'fa-regular fa-copy'"></i>
                                            <span x-text="copiedTitle ? 'Copied!' : 'Copy Title'"></span>
                                        </button>
                                    </div>
                                    <div class="p-3.5 bg-black/50 rounded-2xl border border-white/[0.08] text-sm md:text-base font-bold text-white select-all leading-snug">
                                        {{ $short->title }}
                                    </div>
                                </div>

                                <!-- Description & CTA Box -->
                                <div class="space-y-1.5">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="font-bold uppercase tracking-wider text-zinc-300 flex items-center gap-1.5">
                                            <i class="fa-solid fa-align-left text-rose-500"></i>
                                            <span>Suggested Description</span>
                                        </span>
                                        <button @click="copyText(`{{ addslashes($short->description) }}`, 'Description copied!'); copiedDesc = true; setTimeout(() => copiedDesc = false, 2000)"
                                                class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-xl border transition"
                                                :class="copiedDesc ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40' : 'bg-white/[0.05] text-zinc-300 border-white/[0.1] hover:bg-white/[0.1] hover:text-white'">
                                            <i :class="copiedDesc ? 'fa-solid fa-check text-xs' : 'fa-regular fa-copy'"></i>
                                            <span x-text="copiedDesc ? 'Copied!' : 'Copy Description'"></span>
                                        </button>
                                    </div>
                                    <div class="p-4 bg-black/50 rounded-2xl border border-white/[0.08] text-xs text-zinc-300 whitespace-pre-line select-all max-h-36 overflow-y-auto leading-relaxed font-sans">
                                        {{ $short->description }}
                                    </div>
                                </div>

                                <!-- SEO Tags Chips & Copy -->
                                <div class="space-y-1.5">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="font-bold uppercase tracking-wider text-zinc-300 flex items-center gap-1.5">
                                            <i class="fa-solid fa-tags text-rose-500"></i>
                                            <span>Suggested Tags</span>
                                        </span>
                                        <button @click="copyText(`{{ addslashes($short->tags) }}`, 'Tags copied!'); copiedTags = true; setTimeout(() => copiedTags = false, 2000)"
                                                class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-xl border transition"
                                                :class="copiedTags ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40' : 'bg-white/[0.05] text-zinc-300 border-white/[0.1] hover:bg-white/[0.1] hover:text-white'">
                                            <i :class="copiedTags ? 'fa-solid fa-check text-xs' : 'fa-regular fa-copy'"></i>
                                            <span x-text="copiedTags ? 'Copied!' : 'Copy Tags'"></span>
                                        </button>
                                    </div>
                                    <div class="p-3 bg-black/50 rounded-2xl border border-white/[0.08] flex flex-wrap gap-1.5">
                                        @foreach(explode(',', $short->tags) as $tag)
                                            @if(trim($tag))
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-white/[0.05] border border-white/[0.08] text-[11px] text-zinc-300 font-mono">
                                                    #{{ trim($tag) }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Copy suggested details together -->
                                <div class="pt-2 flex items-center justify-end">
                                    <button @click="copyAllKit(`{{ addslashes($short->title) }}`, `{{ addslashes($short->description) }}`, `{{ addslashes($short->tags) }}`); copiedAll = true; setTimeout(() => copiedAll = false, 2000)"
                                            class="inline-flex items-center gap-2 text-xs font-bold py-3 px-5 rounded-xl border transition"
                                            :class="copiedAll ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40' : 'bg-white/[0.06] hover:bg-white/[0.1] text-white border-white/[0.1]'">
                                        <i :class="copiedAll ? 'fa-solid fa-check-double text-emerald-400' : 'fa-solid fa-copy text-rose-400'"></i>
                                        <span x-text="copiedAll ? 'Entire Kit Copied!' : 'Copy Title, Description & Tags'"></span>
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
