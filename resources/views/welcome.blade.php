@extends('layouts.app')

@section('title', 'AI Shorts Studio • Turn Long Videos Into Viral YouTube Shorts')

@section('content')
<div class="w-full bg-[#0f0f0f] text-white selection:bg-red-600 selection:text-white"
     x-data="{ 
        videoUrl: '', 
        selectedMode: 'song',
        previewModal: false,
        previewSrc: '',
        previewTitle: '',
        activeTab: 'all',
        beforeAfterPos: 50,
        setSample(url, mode) {
            this.videoUrl = url;
            this.selectedMode = mode;
        },
        openPreview(src, title) {
            this.previewSrc = src;
            this.previewTitle = title;
            this.previewModal = true;
        }
     }">

    <!-- YOUTUBE TOP CATEGORY CHIPS BAR (Sticky below nav) -->
    <div class="sticky top-16 z-30 bg-[#0f0f0f]/95 backdrop-blur-md border-b border-[#272727] py-2.5 px-4 sm:px-6 lg:px-8 overflow-x-auto no-scrollbar shadow-md">
        <div class="max-w-7xl mx-auto flex items-center gap-2 text-xs font-bold whitespace-nowrap">
            <button @click="activeTab = 'all'" :class="activeTab === 'all' ? 'bg-white text-black' : 'bg-[#272727] text-neutral-300 hover:bg-[#333]'" class="px-3.5 py-1.5 rounded-lg transition flex items-center gap-1.5">
                <i class="fa-solid fa-fire text-red-500"></i>
                <span>All Viral Shorts</span>
            </button>
            <button @click="activeTab = 'song'" :class="activeTab === 'song' ? 'bg-white text-black' : 'bg-[#272727] text-neutral-300 hover:bg-[#333]'" class="px-3.5 py-1.5 rounded-lg transition flex items-center gap-1.5">
                <i class="fa-solid fa-music text-pink-500"></i>
                <span>🎵 Song / Mukhda Mode</span>
            </button>
            <button @click="activeTab = 'dialogue'" :class="activeTab === 'dialogue' ? 'bg-white text-black' : 'bg-[#272727] text-neutral-300 hover:bg-[#333]'" class="px-3.5 py-1.5 rounded-lg transition flex items-center gap-1.5">
                <i class="fa-solid fa-microphone text-indigo-400"></i>
                <span>🎙️ Podcast & Dialogue</span>
            </button>
            <a href="{{ route('nostalgia.studio') }}" class="px-3.5 py-1.5 rounded-lg bg-gradient-to-r from-red-600/30 to-pink-600/30 border border-red-500/40 text-red-300 hover:text-white transition flex items-center gap-1.5">
                <i class="fa-solid fa-sparkles text-amber-400"></i>
                <span>90s Nostalgia Studio (NEW)</span>
            </a>
            <span class="px-3.5 py-1.5 rounded-lg bg-[#1a1a1a] text-neutral-400 border border-[#272727]">
                <i class="fa-solid fa-closed-captioning text-yellow-400 mr-1"></i> Animated Subtitles
            </span>
            <span class="px-3.5 py-1.5 rounded-lg bg-[#1a1a1a] text-neutral-400 border border-[#272727]">
                <i class="fa-solid fa-mobile-screen text-emerald-400 mr-1"></i> 1080x1920 9:16 HD
            </span>
            <span class="px-3.5 py-1.5 rounded-lg bg-[#1a1a1a] text-neutral-400 border border-[#272727]">
                <i class="fa-solid fa-bolt text-red-500 mr-1"></i> 60s Fast Render
            </span>
        </div>
    </div>

    <!-- HERO SECTION: YOUTUBE STUDIO STYLE -->
    <section class="relative pt-12 pb-20 px-4 sm:px-6 lg:px-8 overflow-hidden">
        <!-- Ambient YouTube Red & Purple Glow Background -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[450px] bg-red-600/15 blur-[140px] rounded-full pointer-events-none -z-10"></div>
        <div class="absolute top-20 right-10 w-[500px] h-[350px] bg-purple-600/10 blur-[130px] rounded-full pointer-events-none -z-10"></div>

        <div class="max-w-5xl mx-auto text-center">
            <!-- Eyebrow Badge -->
            <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-[#1c1c1c] border border-red-500/30 text-xs font-extrabold text-neutral-200 mb-6 shadow-lg shadow-red-600/10 hover:border-red-500/60 transition cursor-default">
                <span class="w-2 h-2 rounded-full bg-[#FF0000] animate-ping"></span>
                <i class="fa-brands fa-youtube text-[#FF0000] text-sm"></i>
                <span>YouTube Creator AI Studio 2.0</span>
                <span class="text-neutral-500">•</span>
                <span class="text-red-400">Gemma AI Engine</span>
            </div>

            <!-- Bold YouTube Headline -->
            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight leading-[1.08] mb-6">
                Turn Any Long Video or Song Into <br class="hidden sm:inline">
                <span class="bg-gradient-to-r from-red-500 via-rose-500 to-amber-400 bg-clip-text text-transparent">
                    10 Viral YouTube Shorts
                </span>
                in Seconds.
            </h1>

            <!-- Subtitle -->
            <p class="text-sm sm:text-base lg:text-lg text-neutral-300 max-w-3xl mx-auto mb-10 leading-relaxed font-medium">
                Automatic peak <strong class="text-white">Mukhda & Chorus</strong> detection, sentence-level dialogue hooks, 
                <strong class="text-yellow-400">Hormozi kinetic animated subtitles</strong>, and smart 1080x1920 9:16 vertical framing — ready to explode on Shorts, Reels & TikTok.
            </p>

            <!-- INTERACTIVE YOUTUBE URL GENERATOR BOX -->
            <div class="max-w-3xl mx-auto p-2 sm:p-3 rounded-2xl sm:rounded-3xl bg-[#181818] border-2 border-red-500/40 shadow-[0_15px_60px_rgba(255,0,0,0.2)] mb-5">
                <form action="{{ route('projects.create') }}" method="GET" class="space-y-3">
                    <div class="flex flex-col sm:flex-row items-center gap-2">
                        <!-- Input Bar with Red Icon -->
                        <div class="relative w-full flex items-center">
                            <div class="absolute left-4 text-red-500 text-lg">
                                <i class="fa-brands fa-youtube"></i>
                            </div>
                            <input type="text" 
                                   name="url" 
                                   x-model="videoUrl" 
                                   placeholder="Paste YouTube Video / Song URL (e.g. https://www.youtube.com/watch?v=...)"
                                   required
                                   class="w-full bg-[#0f0f0f] border border-[#2e2e2e] text-white placeholder-neutral-500 text-xs sm:text-sm rounded-xl py-3.5 pl-12 pr-4 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition">
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" 
                                class="w-full sm:w-auto px-6 py-3.5 rounded-xl bg-[#FF0000] hover:bg-[#CC0000] text-white text-xs sm:text-sm font-black transition transform hover:scale-105 shadow-lg shadow-red-600/40 flex items-center justify-center gap-2 whitespace-nowrap cursor-pointer">
                            <i class="fa-solid fa-bolt text-yellow-300"></i>
                            <span>Generate Shorts</span>
                        </button>
                    </div>

                    <!-- Mode Selector Inside Box -->
                    <div class="flex flex-wrap items-center justify-between gap-2 px-1 pt-1">
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-bold text-neutral-400 uppercase tracking-wider">Mode:</span>
                            <label class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold cursor-pointer transition"
                                   :class="selectedMode === 'song' ? 'bg-pink-600/20 text-pink-300 border border-pink-500/40' : 'bg-[#222] text-neutral-400 hover:text-white'">
                                <input type="radio" name="mode" value="song" x-model="selectedMode" class="hidden">
                                <i class="fa-solid fa-music text-[10px]"></i>
                                <span>Song Mode (Peak Mukhda)</span>
                            </label>
                            <label class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold cursor-pointer transition"
                                   :class="selectedMode === 'dialogue' ? 'bg-indigo-600/20 text-indigo-300 border border-indigo-500/40' : 'bg-[#222] text-neutral-400 hover:text-white'">
                                <input type="radio" name="mode" value="dialogue" x-model="selectedMode" class="hidden">
                                <i class="fa-solid fa-microphone text-[10px]"></i>
                                <span>Dialogue / Podcast</span>
                            </label>
                        </div>

                        <div class="text-[11px] font-semibold text-neutral-400 flex items-center gap-1.5">
                            <i class="fa-solid fa-coins text-amber-400"></i>
                            <span>New Users Get <strong>20 Free Tokens</strong></span>
                        </div>
                    </div>
                </form>
            </div>

            <!-- 1-Click Demo Chips -->
            <div class="flex flex-wrap items-center justify-center gap-2 text-xs text-neutral-400">
                <span class="font-bold text-neutral-500">Try Instant Demos:</span>
                <button type="button" 
                        @click="setSample('https://www.youtube.com/watch?v=6YXt-FIZd6Q', 'song')"
                        class="px-2.5 py-1 rounded-md bg-[#1a1a1a] hover:bg-[#272727] text-neutral-300 border border-[#2f2f2f] hover:border-pink-500/50 transition flex items-center gap-1 text-[11px] font-semibold">
                    <i class="fa-solid fa-music text-pink-400"></i>
                    <span>90s Purani Gali (Mukhda)</span>
                </button>
                <button type="button" 
                        @click="setSample('https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'dialogue')"
                        class="px-2.5 py-1 rounded-md bg-[#1a1a1a] hover:bg-[#272727] text-neutral-300 border border-[#2f2f2f] hover:border-indigo-500/50 transition flex items-center gap-1 text-[11px] font-semibold">
                    <i class="fa-solid fa-microphone text-indigo-400"></i>
                    <span>Joe Rogan Podcast Clip</span>
                </button>
                <button type="button" 
                        @click="setSample('https://www.youtube.com/watch?v=2vjPBrBU-TM', 'dialogue')"
                        class="px-2.5 py-1 rounded-md bg-[#1a1a1a] hover:bg-[#272727] text-neutral-300 border border-[#2f2f2f] hover:border-amber-500/50 transition flex items-center gap-1 text-[11px] font-semibold">
                    <i class="fa-solid fa-fire text-amber-400"></i>
                    <span>Motivational Speech</span>
                </button>
            </div>
        </div>
    </section>

    <!-- LIVE YOUTUBE SHORTS REEL SHOWCASE (Playable Interactive Cards) -->
    <section id="demo-shorts" class="py-16 px-4 sm:px-6 lg:px-8 border-y border-[#222] bg-[#141414]/60 relative">
        <div class="max-w-7xl mx-auto">
            <!-- Section Header -->
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
                <div>
                    <div class="flex items-center gap-2 text-xs font-bold text-red-500 uppercase tracking-widest mb-1.5">
                        <i class="fa-brands fa-youtube"></i>
                        <span>Live AI Video Showcase</span>
                    </div>
                    <h2 class="text-2xl sm:text-4xl font-black text-white">
                        Watch Real Videos Generated By Our Platform
                    </h2>
                    <p class="text-xs sm:text-sm text-neutral-400 mt-1">
                        Click any video card to play directly in full HD. Tested with YouTube Shorts algorithm.
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('nostalgia.studio') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#222] hover:bg-[#2a2a2a] text-white text-xs font-bold border border-[#333] transition">
                        <i class="fa-solid fa-clapperboard text-pink-400"></i>
                        <span>Open 90s Song Studio</span>
                    </a>
                </div>
            </div>

            <!-- 4 Featured Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Video 1: 90s Nostalgia Viral Short (Sunday Shaktimaan) -->
                <div class="bg-[#1a1a1a] border border-[#2b2b2b] hover:border-red-500/60 rounded-3xl overflow-hidden shadow-xl group transition-all duration-300 hover:-translate-y-1">
                    <div class="relative aspect-[9/16] bg-black overflow-hidden cursor-pointer"
                         @click="openPreview('/media/nostalgia_shorts/90s_Nostalgia_Childhood_Short.mp4', '90s Nostalgia • Sunday Shaktimaan Short')">
                        <!-- Video or Poster Frame -->
                        <img src="/media/nostalgia_shorts/scene_1_shaktimaan.jpg" alt="Sunday Shaktimaan Short" class="w-full h-full object-cover group-hover:scale-105 transition duration-500 opacity-90">
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent"></div>

                        <!-- Top Badges -->
                        <div class="absolute top-3 left-3 right-3 flex items-center justify-between z-10">
                            <span class="px-2.5 py-1 rounded-md bg-[#FF0000] text-white text-[10px] font-black tracking-wider flex items-center gap-1 shadow">
                                <i class="fa-brands fa-youtube"></i> SHORTS
                            </span>
                            <span class="px-2 py-0.5 rounded bg-black/80 backdrop-blur text-white text-[10px] font-bold border border-white/20">
                                0:60 • 1080p
                            </span>
                        </div>

                        <!-- Center Play Button Glow -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-14 h-14 rounded-full bg-red-600/90 text-white flex items-center justify-center text-xl shadow-xl shadow-red-600/50 group-hover:scale-110 transition">
                                <i class="fa-solid fa-play ml-1"></i>
                            </div>
                        </div>

                        <!-- Bottom Info -->
                        <div class="absolute bottom-3 left-3 right-3 z-10">
                            <div class="flex items-center gap-1 text-[11px] font-bold text-amber-400 mb-0.5">
                                <i class="fa-solid fa-eye"></i> 1.8M Views • Viral Hit
                            </div>
                            <h4 class="text-sm font-black text-white leading-tight line-clamp-2">
                                Wo Sunday ki Subah aur Shaktimaan! 90s Ke Din
                            </h4>
                            <div class="mt-2 flex items-center justify-between text-[11px] text-neutral-300">
                                <span class="text-red-400 font-semibold">🎵 Purani Gali (Mukhda)</span>
                                <span class="text-emerald-400 font-bold">● Ready</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-3 bg-[#181818] border-t border-[#272727] flex items-center justify-between">
                        <span class="text-[11px] text-neutral-400 font-bold">Format: 9:16 Vertical</span>
                        <a href="/media/nostalgia_shorts/90s_Nostalgia_Childhood_Short.mp4" download class="text-xs font-bold text-red-400 hover:text-red-300 flex items-center gap-1">
                            <i class="fa-solid fa-download"></i> Save MP4
                        </a>
                    </div>
                </div>

                <!-- Video 2: 90s Full Music Video Long Format (16:9 Landscape) -->
                <div class="bg-[#1a1a1a] border border-[#2b2b2b] hover:border-indigo-500/60 rounded-3xl overflow-hidden shadow-xl group transition-all duration-300 hover:-translate-y-1">
                    <div class="relative aspect-[9/16] bg-black overflow-hidden cursor-pointer"
                         @click="openPreview('/media/nostalgia_shorts/90s_Nostalgia_Full_Song_Long_16x9.mp4', 'Purani Gali • 16:9 Long Format Full Music Video')">
                        <img src="/media/nostalgia_shorts/frame_16x9_scene_1.jpg" alt="16:9 Long Video" class="w-full h-full object-cover group-hover:scale-105 transition duration-500 opacity-90">
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent"></div>

                        <div class="absolute top-3 left-3 right-3 flex items-center justify-between z-10">
                            <span class="px-2.5 py-1 rounded-md bg-indigo-600 text-white text-[10px] font-black tracking-wider flex items-center gap-1 shadow">
                                <i class="fa-solid fa-desktop"></i> 16:9 LONG
                            </span>
                            <span class="px-2 py-0.5 rounded bg-black/80 backdrop-blur text-white text-[10px] font-bold border border-white/20">
                                2:36 • Full HD
                            </span>
                        </div>

                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-14 h-14 rounded-full bg-indigo-600/90 text-white flex items-center justify-center text-xl shadow-xl shadow-indigo-600/50 group-hover:scale-110 transition">
                                <i class="fa-solid fa-play ml-1"></i>
                            </div>
                        </div>

                        <div class="absolute bottom-3 left-3 right-3 z-10">
                            <div class="flex items-center gap-1 text-[11px] font-bold text-indigo-400 mb-0.5">
                                <i class="fa-solid fa-music"></i> Official YouTube Video
                            </div>
                            <h4 class="text-sm font-black text-white leading-tight line-clamp-2">
                                Purani Gali Anthem (16:9 Widescreen Master)
                            </h4>
                            <div class="mt-2 flex items-center justify-between text-[11px] text-neutral-300">
                                <span>8 Timestamps Chapters</span>
                                <span class="text-emerald-400 font-bold">● Ready</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-3 bg-[#181818] border-t border-[#272727] flex items-center justify-between">
                        <span class="text-[11px] text-neutral-400 font-bold">Format: 1920x1080 Full HD</span>
                        <a href="/media/nostalgia_shorts/90s_Nostalgia_Full_Song_Long_16x9.mp4" download class="text-xs font-bold text-indigo-400 hover:text-indigo-300 flex items-center gap-1">
                            <i class="fa-solid fa-download"></i> Save MP4
                        </a>
                    </div>
                </div>

                <!-- Video 3: Dialogue / Podcast Short Card -->
                <div class="bg-[#1a1a1a] border border-[#2b2b2b] hover:border-yellow-500/60 rounded-3xl overflow-hidden shadow-xl group transition-all duration-300 hover:-translate-y-1">
                    <div class="relative aspect-[9/16] bg-black overflow-hidden cursor-pointer"
                         @click="openPreview('/media/nostalgia_shorts/90s_Nostalgia_Full_Song_Vertical.mp4', 'Dialogue Mode • Kinetic Captions Sample')">
                        <img src="/media/nostalgia_shorts/scene_6_phone_loneliness.jpg" alt="Modern Phone Loneliness" class="w-full h-full object-cover group-hover:scale-105 transition duration-500 opacity-85">
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent"></div>

                        <div class="absolute top-3 left-3 right-3 flex items-center justify-between z-10">
                            <span class="px-2.5 py-1 rounded-md bg-yellow-500 text-black text-[10px] font-black tracking-wider flex items-center gap-1 shadow">
                                <i class="fa-solid fa-closed-captioning"></i> HORMOZI
                            </span>
                            <span class="px-2 py-0.5 rounded bg-black/80 backdrop-blur text-white text-[10px] font-bold border border-white/20">
                                2:29 • Subtitles
                            </span>
                        </div>

                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-14 h-14 rounded-full bg-yellow-500 text-black flex items-center justify-center text-xl shadow-xl shadow-yellow-500/40 group-hover:scale-110 transition">
                                <i class="fa-solid fa-play ml-1"></i>
                            </div>
                        </div>

                        <div class="absolute bottom-3 left-3 right-3 z-10">
                            <div class="flex items-center gap-1 text-[11px] font-bold text-yellow-400 mb-0.5">
                                <i class="fa-solid fa-chart-line"></i> 89% Retention Rate
                            </div>
                            <h4 class="text-sm font-black text-white leading-tight line-clamp-2">
                                "Aaj haath mein phone hai, par dil khali-khali hai..."
                            </h4>
                            <div class="mt-2 flex items-center justify-between text-[11px] text-neutral-300">
                                <span>Animated Captions</span>
                                <span class="text-emerald-400 font-bold">● Ready</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-3 bg-[#181818] border-t border-[#272727] flex items-center justify-between">
                        <span class="text-[11px] text-neutral-400 font-bold">Format: 9:16 Dialogue</span>
                        <a href="/media/nostalgia_shorts/90s_Nostalgia_Full_Song_Vertical.mp4" download class="text-xs font-bold text-yellow-400 hover:text-yellow-300 flex items-center gap-1">
                            <i class="fa-solid fa-download"></i> Save MP4
                        </a>
                    </div>
                </div>

                <!-- Video 4: Nostalgia Friends Playing (High Viral Hook) -->
                <div class="bg-[#1a1a1a] border border-[#2b2b2b] hover:border-emerald-500/60 rounded-3xl overflow-hidden shadow-xl group transition-all duration-300 hover:-translate-y-1">
                    <div class="relative aspect-[9/16] bg-black overflow-hidden cursor-pointer"
                         @click="openPreview('/media/nostalgia_shorts/90s_Nostalgia_Childhood_Short.mp4', 'Mitti Ki Khushboo • Friendship Viral Short')">
                        <img src="/media/nostalgia_shorts/scene_7_mitti_friends.jpg" alt="Mitti Ki Khushboo" class="w-full h-full object-cover group-hover:scale-105 transition duration-500 opacity-90">
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent"></div>

                        <div class="absolute top-3 left-3 right-3 flex items-center justify-between z-10">
                            <span class="px-2.5 py-1 rounded-md bg-emerald-600 text-white text-[10px] font-black tracking-wider flex items-center gap-1 shadow">
                                <i class="fa-solid fa-heart"></i> EMOTIONAL
                            </span>
                            <span class="px-2 py-0.5 rounded bg-black/80 backdrop-blur text-white text-[10px] font-bold border border-white/20">
                                1080x1920 HD
                            </span>
                        </div>

                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-14 h-14 rounded-full bg-emerald-600/90 text-white flex items-center justify-center text-xl shadow-xl shadow-emerald-600/50 group-hover:scale-110 transition">
                                <i class="fa-solid fa-play ml-1"></i>
                            </div>
                        </div>

                        <div class="absolute bottom-3 left-3 right-3 z-10">
                            <div class="flex items-center gap-1 text-[11px] font-bold text-emerald-400 mb-0.5">
                                <i class="fa-solid fa-share-nodes"></i> 45K Shares
                            </div>
                            <h4 class="text-sm font-black text-white leading-tight line-clamp-2">
                                Mitti ki Khushboo aur Doston ka Shor • 90s Memories
                            </h4>
                            <div class="mt-2 flex items-center justify-between text-[11px] text-neutral-300">
                                <span>Cycle Toli & Paper Boat</span>
                                <span class="text-emerald-400 font-bold">● Ready</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-3 bg-[#181818] border-t border-[#272727] flex items-center justify-between">
                        <span class="text-[11px] text-neutral-400 font-bold">Format: 9:16 Emotional</span>
                        <a href="/media/nostalgia_shorts/90s_Nostalgia_Childhood_Short.mp4" download class="text-xs font-bold text-emerald-400 hover:text-emerald-300 flex items-center gap-1">
                            <i class="fa-solid fa-download"></i> Save MP4
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- BEFORE VS AFTER INTERACTIVE COMPARISON SECTION -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 border-b border-[#222]">
        <div class="max-w-6xl mx-auto">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="text-xs font-black uppercase tracking-widest text-red-500">Why YouTube Creators Love Us</span>
                <h2 class="text-2xl sm:text-4xl font-black text-white mt-1">
                    Raw Boring Long Video vs Our Viral AI Short
                </h2>
                <p class="text-xs sm:text-sm text-neutral-400 mt-2">
                    See why standard horizontal videos die at 200 views while our AI shorts pull 1M+ views.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <!-- Left: Boring Raw Video Card -->
                <div class="rounded-3xl bg-[#141414] border border-[#272727] p-6 relative overflow-hidden">
                    <span class="inline-block px-3 py-1 rounded-md bg-neutral-800 text-neutral-300 text-xs font-bold mb-4">
                        ❌ Standard Video (Before AI)
                    </span>
                    <div class="aspect-video bg-black rounded-2xl overflow-hidden border border-[#2b2b2b] flex items-center justify-center relative mb-4">
                        <img src="/media/nostalgia_shorts/frame_16x9_scene_6.jpg" alt="Raw Video" class="w-full h-full object-cover filter grayscale contrast-75 opacity-60">
                        <div class="absolute inset-0 flex flex-col items-center justify-center p-4 text-center">
                            <i class="fa-regular fa-circle-pause text-3xl text-neutral-500 mb-2"></i>
                            <span class="text-xs font-bold text-neutral-400">Horizontal 16:9 • Long boring silence • No subtitles</span>
                        </div>
                    </div>
                    <ul class="text-xs text-neutral-400 space-y-2">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-xmark text-rose-500"></i> Viewer drops off in first 3 seconds</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-xmark text-rose-500"></i> No animated subtitles (60% watch on mute!)</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-xmark text-rose-500"></i> Takes 4 hours in Premiere Pro to edit manually</li>
                        <li class="flex items-center gap-2 font-bold text-rose-400"><i class="fa-solid fa-chart-line-down"></i> Result: Stuck at 240 views</li>
                    </ul>
                </div>

                <!-- Right: AI Shorts Studio Viral Card -->
                <div class="rounded-3xl bg-gradient-to-b from-[#1f1212] via-[#161616] to-[#121212] border-2 border-red-500/70 p-6 shadow-2xl shadow-red-600/20 relative overflow-hidden">
                    <div class="flex items-center justify-between mb-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md bg-[#FF0000] text-white text-xs font-black shadow">
                            <i class="fa-solid fa-sparkles"></i> AI Shorts Studio (After)
                        </span>
                        <span class="text-xs font-extrabold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-1 rounded-full">
                            🔥 +840% More Reach
                        </span>
                    </div>

                    <div class="aspect-video bg-black rounded-2xl overflow-hidden border border-red-500/40 relative mb-4 group cursor-pointer"
                         @click="openPreview('/media/nostalgia_shorts/90s_Nostalgia_Childhood_Short.mp4', 'Viral 9:16 Short Preview')">
                        <img src="/media/nostalgia_shorts/scene_1_shaktimaan.jpg" alt="Viral Short" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-black/30"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-12 h-12 rounded-full bg-[#FF0000] text-white flex items-center justify-center shadow-lg">
                                <i class="fa-solid fa-play ml-0.5"></i>
                            </div>
                        </div>
                        <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between text-xs">
                            <span class="px-2 py-0.5 rounded bg-black/80 text-yellow-400 font-extrabold text-[11px]">
                                "Shaktimaan ka aana..." 🚀
                            </span>
                            <span class="text-emerald-400 font-black text-xs">1.8M Views</span>
                        </div>
                    </div>

                    <ul class="text-xs text-neutral-200 space-y-2 font-medium">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> Peak Mukhda & Chorus hook detected automatically</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> Word-by-word animated kinetic subtitles (yellow/red glow)</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> Smart 1080x1920 9:16 Vertical crop & dual-layer blur</li>
                        <li class="flex items-center gap-2 font-black text-emerald-400"><i class="fa-solid fa-bolt text-yellow-400"></i> Generated in 60 seconds with 1-click</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- THE 4 CORE AI WEAPONS (Features) -->
    <section id="features" class="py-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-xs font-black uppercase tracking-widest text-red-500">The Powerhouse AI Stack</span>
            <h2 class="text-3xl sm:text-4xl font-black text-white mt-1">
                Built For Serious Content Creators & Studios
            </h2>
            <p class="text-xs sm:text-sm text-neutral-400 mt-2">
                Every tool you need to turn 1 hour of raw content into months of daily viral shorts.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Feature 1 -->
            <div class="p-6 rounded-3xl bg-[#171717] border border-[#272727] hover:border-red-500/50 transition duration-300">
                <div class="w-12 h-12 rounded-2xl bg-red-600/20 text-[#FF0000] flex items-center justify-center text-xl mb-5 border border-red-500/30">
                    <i class="fa-solid fa-waveform-lines"></i>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Song Mukhda Detector</h3>
                <p class="text-xs text-neutral-400 leading-relaxed">
                    AI analyzes audio energy and lyrics to extract the exact 30s-60s chorus hook that makes songs go viral on YouTube Shorts.
                </p>
            </div>

            <!-- Feature 2 -->
            <div class="p-6 rounded-3xl bg-[#171717] border border-[#272727] hover:border-yellow-500/50 transition duration-300">
                <div class="w-12 h-12 rounded-2xl bg-yellow-500/20 text-yellow-400 flex items-center justify-center text-xl mb-5 border border-yellow-500/30">
                    <i class="fa-solid fa-closed-captioning"></i>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Hormozi Animated Captions</h3>
                <p class="text-xs text-neutral-400 leading-relaxed">
                    Dynamic word-by-word animated subtitles with custom color highlights, auto-emojis, and 99.4% transcription accuracy.
                </p>
            </div>

            <!-- Feature 3 -->
            <div class="p-6 rounded-3xl bg-[#171717] border border-[#272727] hover:border-indigo-500/50 transition duration-300">
                <div class="w-12 h-12 rounded-2xl bg-indigo-600/20 text-indigo-400 flex items-center justify-center text-xl mb-5 border border-indigo-500/30">
                    <i class="fa-solid fa-mobile-screen"></i>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Smart 9:16 Vertical Crop</h3>
                <p class="text-xs text-neutral-400 leading-relaxed">
                    Intelligently converts landscape video to 1080x1920 9:16 portrait with dual-layer cinematic styling and speaker face centering.
                </p>
            </div>

            <!-- Feature 4 -->
            <div class="p-6 rounded-3xl bg-[#171717] border border-[#272727] hover:border-emerald-500/50 transition duration-300">
                <div class="w-12 h-12 rounded-2xl bg-emerald-600/20 text-emerald-400 flex items-center justify-center text-xl mb-5 border border-emerald-500/30">
                    <i class="fa-brands fa-youtube"></i>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">YouTube Studio SEO Kit</h3>
                <p class="text-xs text-neutral-400 leading-relaxed">
                    Auto-generated high-CTR YouTube titles, viral hashtags (`#Shorts`), timestamped chapters, and SEO tags ready to 1-click copy.
                </p>
            </div>
        </div>
    </section>

    <!-- LIVE STATS TICKER -->
    <section class="py-12 border-y border-[#222] bg-[#121212]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div>
                    <div class="text-3xl sm:text-4xl font-black text-white">1.8M+</div>
                    <div class="text-xs text-neutral-400 mt-1 font-semibold">Shorts Generated</div>
                </div>
                <div>
                    <div class="text-3xl sm:text-4xl font-black text-red-500">99.4%</div>
                    <div class="text-xs text-neutral-400 mt-1 font-semibold">Subtitle Precision</div>
                </div>
                <div>
                    <div class="text-3xl sm:text-4xl font-black text-yellow-400">&lt; 60s</div>
                    <div class="text-xs text-neutral-400 mt-1 font-semibold">Average Render Speed</div>
                </div>
                <div>
                    <div class="text-3xl sm:text-4xl font-black text-emerald-400">₹0.60</div>
                    <div class="text-xs text-neutral-400 mt-1 font-semibold">Cost Per Short</div>
                </div>
            </div>
        </div>
    </section>

    <!-- VIDEO CREDIT PRICING PACKS WITH RAZORPAY -->
    <section id="pricing" class="py-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="text-center max-w-3xl mx-auto mb-14">
            <span class="text-xs font-black uppercase tracking-widest text-red-500">Simple Transparent Pricing</span>
            <h2 class="text-3xl sm:text-4xl font-black text-white mt-1">
                Choose Your Video Credit Pack
            </h2>
            <p class="text-xs sm:text-sm text-neutral-400 mt-2">
                <strong>1 Video Credit = 1 Full Video</strong>. Generate unlimited stanzas, 1, 3, 5, or <strong>All Possible Cuts</strong> per video!
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-5 max-w-6xl mx-auto">
            <!-- 1. Trial: Rs 99 -->
            <div class="rounded-3xl bg-[#171717] border border-[#272727] p-6 flex flex-col justify-between hover:border-[#383838] transition">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[10px] font-extrabold uppercase px-2.5 py-0.5 rounded-full bg-[#272727] text-neutral-300">Trial</span>
                        <div class="text-2xl font-black text-white">₹99</div>
                    </div>
                    <div class="text-xl font-black text-amber-400 mb-1">3 Credits</div>
                    <p class="text-[11px] text-neutral-400 mb-4">3 Full Videos (Unlimited Shorts).</p>
                    <ul class="text-[11px] text-neutral-300 space-y-2 mb-6">
                        <li class="flex items-center gap-1.5"><i class="fa-solid fa-check text-emerald-400 text-[10px]"></i> 3 Full Videos</li>
                        <li class="flex items-center gap-1.5"><i class="fa-solid fa-check text-emerald-400 text-[10px]"></i> All Possible Cuts</li>
                        <li class="flex items-center gap-1.5"><i class="fa-solid fa-check text-emerald-400 text-[10px]"></i> 1080x1920 HD</li>
                    </ul>
                </div>
                <a href="{{ route('wallet') }}" class="w-full py-2.5 rounded-xl bg-[#272727] hover:bg-[#333] text-white text-xs font-bold transition text-center block border border-[#3a3a3a]">
                    Recharge ₹99
                </a>
            </div>

            <!-- 2. Starter: Rs 299 -->
            <div class="rounded-3xl bg-[#171717] border border-[#272727] p-6 flex flex-col justify-between hover:border-[#383838] transition">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[10px] font-extrabold uppercase px-2.5 py-0.5 rounded-full bg-[#272727] text-neutral-300">Starter</span>
                        <div class="text-2xl font-black text-white">₹299</div>
                    </div>
                    <div class="text-xl font-black text-amber-400 mb-1">10 Credits</div>
                    <p class="text-[11px] text-neutral-400 mb-4">10 Full Videos (Unlimited Shorts).</p>
                    <ul class="text-[11px] text-neutral-300 space-y-2 mb-6">
                        <li class="flex items-center gap-1.5"><i class="fa-solid fa-check text-emerald-400 text-[10px]"></i> 10 Full Videos</li>
                        <li class="flex items-center gap-1.5"><i class="fa-solid fa-check text-emerald-400 text-[10px]"></i> Unlimited cuts per video</li>
                        <li class="flex items-center gap-1.5"><i class="fa-solid fa-check text-emerald-400 text-[10px]"></i> Smart Boundary cut</li>
                    </ul>
                </div>
                <a href="{{ route('wallet') }}" class="w-full py-2.5 rounded-xl bg-[#272727] hover:bg-[#333] text-white text-xs font-bold transition text-center block border border-[#3a3a3a]">
                    Recharge ₹299
                </a>
            </div>

            <!-- 3. Pro Creator: Rs 499 (Most Popular) -->
            <div class="relative rounded-3xl bg-gradient-to-b from-[#2a1313] via-[#1a1414] to-[#141414] border-2 border-red-500 p-6 shadow-2xl shadow-red-600/20 flex flex-col justify-between">
                <span class="absolute -top-3 right-4 bg-[#FF0000] text-white text-[9px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full shadow-lg">
                    Popular
                </span>
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[10px] font-extrabold uppercase px-2.5 py-0.5 rounded-full bg-red-600/30 text-red-300 border border-red-500/40">Pro</span>
                        <div class="text-2xl font-black text-white">₹499</div>
                    </div>
                    <div class="text-xl font-black text-amber-400 mb-1">20 Credits</div>
                    <p class="text-[11px] text-red-200/80 mb-4">20 Full Videos (~100+ shorts).</p>
                    <ul class="text-[11px] text-neutral-200 space-y-2 mb-6">
                        <li class="flex items-center gap-1.5"><i class="fa-solid fa-check text-emerald-400 text-[10px]"></i> 20 Full Videos</li>
                        <li class="flex items-center gap-1.5"><i class="fa-solid fa-check text-emerald-400 text-[10px]"></i> Peak Mukhda detection</li>
                        <li class="flex items-center gap-1.5"><i class="fa-solid fa-check text-emerald-400 text-[10px]"></i> Priority queue</li>
                    </ul>
                </div>
                <a href="{{ route('wallet') }}" class="w-full py-2.5 rounded-xl bg-[#FF0000] hover:bg-[#CC0000] text-white text-xs font-black transition text-center block shadow-lg shadow-red-600/40">
                    Recharge ₹499
                </a>
            </div>

            <!-- 4. Agency Scale: Rs 999 -->
            <div class="rounded-3xl bg-[#171717] border border-[#272727] p-6 flex flex-col justify-between hover:border-[#383838] transition">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[10px] font-extrabold uppercase px-2.5 py-0.5 rounded-full bg-[#272727] text-neutral-300">Agency</span>
                        <div class="text-2xl font-black text-white">₹999</div>
                    </div>
                    <div class="text-xl font-black text-amber-400 mb-1">50 Credits</div>
                    <p class="text-[11px] text-neutral-400 mb-4">50 Full Videos (~300+ shorts).</p>
                    <ul class="text-[11px] text-neutral-300 space-y-2 mb-6">
                        <li class="flex items-center gap-1.5"><i class="fa-solid fa-check text-emerald-400 text-[10px]"></i> 50 Full Videos</li>
                        <li class="flex items-center gap-1.5"><i class="fa-solid fa-check text-emerald-400 text-[10px]"></i> Just ₹19.98 per video</li>
                        <li class="flex items-center gap-1.5"><i class="fa-solid fa-check text-emerald-400 text-[10px]"></i> VIP Instant Queue</li>
                    </ul>
                </div>
                <a href="{{ route('wallet') }}" class="w-full py-2.5 rounded-xl bg-[#272727] hover:bg-[#333] text-white text-xs font-bold transition text-center block border border-[#3a3a3a]">
                    Recharge ₹999
                </a>
            </div>

            <!-- 5. Mega Enterprise: Rs 4999 -->
            <div class="rounded-3xl bg-gradient-to-b from-[#241d13] to-[#171717] border border-amber-500/40 p-6 flex flex-col justify-between hover:border-amber-500 transition">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[10px] font-extrabold uppercase px-2.5 py-0.5 rounded-full bg-amber-500/20 text-amber-300 border border-amber-500/30">Enterprise</span>
                        <div class="text-2xl font-black text-white">₹4,999</div>
                    </div>
                    <div class="text-xl font-black text-amber-400 mb-1">1,000 Credits</div>
                    <p class="text-[11px] text-neutral-400 mb-4">1,000 Full Videos at ₹4.99/vid.</p>
                    <ul class="text-[11px] text-neutral-300 space-y-2 mb-6">
                        <li class="flex items-center gap-1.5"><i class="fa-solid fa-check text-amber-400 text-[10px]"></i> 1,000 Full Videos</li>
                        <li class="flex items-center gap-1.5"><i class="fa-solid fa-check text-amber-400 text-[10px]"></i> Thousands of shorts</li>
                        <li class="flex items-center gap-1.5"><i class="fa-solid fa-check text-amber-400 text-[10px]"></i> Dedicated priority</li>
                    </ul>
                </div>
                <a href="{{ route('wallet') }}" class="w-full py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-yellow-600 hover:from-amber-600 hover:to-yellow-700 text-slate-950 text-xs font-black transition text-center block shadow-lg shadow-amber-500/20">
                    Recharge ₹4,999
                </a>
            </div>
        </div>
    </section>

    <!-- NOSTALGIA SPOTLIGHT BANNER -->
    <section class="py-12 px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto">
        <div class="rounded-3xl bg-gradient-to-r from-red-950/60 via-purple-950/50 to-pink-950/60 border border-red-500/40 p-8 sm:p-10 flex flex-col lg:flex-row items-center justify-between gap-6 shadow-2xl">
            <div class="max-w-2xl">
                <div class="flex items-center gap-2 text-xs font-bold text-amber-400 uppercase tracking-wider mb-2">
                    <i class="fa-solid fa-sparkles"></i> Spotlight Release
                </div>
                <h3 class="text-2xl sm:text-3xl font-black text-white">
                    Experience the 90s Nostalgia Music Video Studio
                </h3>
                <p class="text-xs sm:text-sm text-neutral-300 mt-2">
                    Sunday Shaktimaan, tiffin stealing, cassette rewinding, kagaz ki naav & cycle toli — rendered in both 9:16 Shorts and 16:9 Long Format Full HD!
                </p>
            </div>
            <a href="{{ route('nostalgia.studio') }}" class="px-6 py-3.5 rounded-2xl bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white font-black text-xs sm:text-sm shadow-xl shadow-red-600/30 transition hover:scale-105 whitespace-nowrap">
                <i class="fa-solid fa-clapperboard mr-1.5"></i>
                <span>Explore Nostalgia Studio</span>
            </a>
        </div>
    </section>

    <!-- FINAL CALL TO ACTION -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 text-center relative overflow-hidden">
        <div class="max-w-4xl mx-auto">
            <div class="w-16 h-16 rounded-3xl bg-[#FF0000] text-white flex items-center justify-center text-3xl mx-auto mb-6 shadow-2xl shadow-red-600/50">
                <i class="fa-brands fa-youtube"></i>
            </div>
            <h2 class="text-3xl sm:text-5xl font-black text-white mb-4">
                Ready to 10x Your YouTube Views?
            </h2>
            <p class="text-sm sm:text-base text-neutral-400 max-w-2xl mx-auto mb-8 font-medium">
                Join hundreds of YouTubers and creators who turn boring horizontal recordings into viral Shorts machines.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ auth()->check() ? route('projects.create') : route('register') }}" 
                   class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-[#FF0000] hover:bg-[#CC0000] text-white font-black text-sm sm:text-base shadow-2xl shadow-red-600/50 transition transform hover:scale-105 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                    <span>Start Creating Shorts Now (Free)</span>
                </a>
                <a href="#demo-shorts" class="w-full sm:w-auto px-6 py-4 rounded-2xl bg-[#1c1c1c] hover:bg-[#272727] text-neutral-300 hover:text-white font-bold text-sm border border-[#2f2f2f] transition">
                    Watch Demo Reels
                </a>
            </div>
        </div>
    </section>

    <!-- LIVE VIDEO PREVIEW MODAL -->
    <div x-cloak x-show="previewModal"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90 backdrop-blur-md"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div class="relative bg-[#141414] border border-[#2e2e2e] rounded-3xl max-w-2xl w-full p-4 sm:p-6 shadow-2xl" @click.outside="previewModal = false">
            <div class="flex items-center justify-between pb-3 mb-3 border-b border-[#272727]">
                <h4 class="text-sm font-black text-white flex items-center gap-2">
                    <i class="fa-brands fa-youtube text-red-500"></i>
                    <span x-text="previewTitle"></span>
                </h4>
                <button type="button" @click="previewModal = false" class="text-neutral-400 hover:text-white p-1">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Modal Video Player -->
            <div class="relative aspect-video sm:aspect-auto sm:max-h-[70vh] bg-black rounded-2xl overflow-hidden flex items-center justify-center">
                <template x-if="previewModal">
                    <video :src="previewSrc" controls autoplay playsinline class="w-full max-h-[65vh] object-contain rounded-xl">
                        Your browser does not support video playback.
                    </video>
                </template>
            </div>

            <div class="mt-4 flex items-center justify-between text-xs text-neutral-400">
                <span>Rendered by AI Shorts Studio</span>
                <a :href="previewSrc" download class="text-red-400 hover:text-red-300 font-bold flex items-center gap-1">
                    <i class="fa-solid fa-download"></i> Direct Download MP4
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
