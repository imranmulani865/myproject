@extends('layouts.app')

@section('title', '90s Nostalgia Video Studio (Vertical Shorts & 16:9 Long Format)')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ 
    activeVideo: 'long_16x9', 
    galleryMode: '16x9',
    copyTab: 'long',
    toast: { show: false, message: '' } 
}">
    <!-- Toast Notification -->
    <div x-cloak x-show="toast.show" 
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-2"
         class="fixed bottom-6 right-6 z-50 bg-emerald-600 text-white px-5 py-3 rounded-2xl shadow-2xl flex items-center gap-3 text-xs font-bold border border-emerald-400">
        <i class="fa-solid fa-circle-check text-base"></i>
        <span x-text="toast.message"></span>
    </div>

    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 text-xs text-amber-400 font-semibold mb-1">
                <i class="fa-solid fa-sparkles"></i>
                <span>90s Nostalgia • AI Generated Music Video Studio</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-white flex items-center gap-3">
                <span class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-amber-500 via-pink-500 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-pink-500/25">
                    <i class="fa-solid fa-clapperboard"></i>
                </span>
                Purani Gali • 90s Memories Studio
            </h1>
            <p class="text-xs sm:text-sm text-slate-400 mt-1">
                Shaktimaan on CRT TV, tiffin chori, cassette winding with pen, paper boats & doston ki toli. Both Vertical 9:16 and Long Format 16:9 ready.
            </p>
        </div>

        <!-- Quick Download Action Buttons in Header -->
        <div class="flex flex-wrap items-center gap-2 sm:gap-3">
            <a href="/media/nostalgia_shorts/90s_Nostalgia_Full_Song_Long_16x9.mp4" download="90s_Nostalgia_Full_Song_Long_16x9.mp4"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-extrabold text-xs shadow-lg shadow-indigo-500/25 transition hover:scale-105">
                <i class="fa-solid fa-desktop"></i>
                <span>Download 16:9 Long Video (1080p)</span>
            </a>
            <a href="/media/nostalgia_shorts/90s_Nostalgia_Childhood_Short.mp4" download="90s_Nostalgia_Childhood_Short.mp4"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-extrabold text-xs shadow-lg shadow-emerald-500/25 transition hover:scale-105">
                <i class="fa-brands fa-youtube text-rose-300"></i>
                <span>60s Short (9:16)</span>
            </a>
            <a href="/media/nostalgia_shorts/90s_Nostalgia_Full_Song_Vertical.mp4" download="90s_Nostalgia_Full_Song_Vertical.mp4"
               class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs border border-slate-700 transition">
                <i class="fa-solid fa-mobile-screen text-pink-400"></i>
                <span>Full Song (9:16)</span>
            </a>
        </div>
    </div>

    <!-- FORMAT SWITCHER TABS -->
    <div class="flex items-center justify-center sm:justify-start mb-6">
        <div class="inline-flex p-1.5 bg-slate-900 border border-slate-800 rounded-2xl shadow-xl gap-1">
            <button type="button" @click="activeVideo = 'long_16x9'; copyTab = 'long'"
                    :class="activeVideo === 'long_16x9' ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-400 hover:text-slate-200'"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2">
                <i class="fa-solid fa-desktop text-indigo-300"></i>
                <span>🖥️ Long Format (16:9 Full HD)</span>
                <span class="px-1.5 py-0.5 rounded text-[10px] bg-indigo-500/40 text-indigo-100 font-extrabold">NEW</span>
            </button>
            <button type="button" @click="activeVideo = 'short'; copyTab = 'short'"
                    :class="activeVideo === 'short' ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-400 hover:text-slate-200'"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2">
                <i class="fa-brands fa-youtube text-rose-400"></i>
                <span>📱 60s Viral Short (9:16)</span>
            </button>
            <button type="button" @click="activeVideo = 'full_vertical'; copyTab = 'short'"
                    :class="activeVideo === 'full_vertical' ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-400 hover:text-slate-200'"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2">
                <i class="fa-solid fa-music text-pink-400"></i>
                <span>📱 Full Song (9:16 Vertical)</span>
            </button>
        </div>
    </div>

    <!-- MAIN STUDIO PLAYER SECTION -->
    <div class="mb-12">
        <!-- MODE A: 16:9 Widescreen Cinema Theater Display -->
        <div x-show="activeVideo === 'long_16x9'" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <div class="lg:col-span-8 flex flex-col items-center">
                <!-- TV / Cinema Display Frame -->
                <div class="w-full relative rounded-3xl overflow-hidden bg-black border-4 border-slate-800 shadow-2xl ring-1 ring-indigo-500/30 group">
                    <!-- Subtle Ambient Top Glow -->
                    <div class="absolute -top-24 left-1/2 -translate-x-1/2 w-3/4 h-28 bg-indigo-500/20 blur-3xl rounded-full pointer-events-none"></div>

                    <div class="relative w-full aspect-video bg-slate-950 flex items-center justify-center">
                        <video controls playsinline class="w-full h-full object-contain">
                            <source src="/media/nostalgia_shorts/90s_Nostalgia_Full_Song_Long_16x9.mp4" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>

                    <!-- TV Bottom Bar Status -->
                    <div class="px-5 py-2.5 bg-slate-950 border-t border-slate-800 flex items-center justify-between text-xs text-slate-400">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span class="text-slate-300 font-semibold">16:9 YouTube Widescreen Master</span>
                            <span class="text-slate-600">•</span>
                            <span>1920x1080 Full HD</span>
                            <span class="text-slate-600">•</span>
                            <span>Stereo AAC</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-slate-500">Duration: 2m 36s</span>
                            <a href="/media/nostalgia_shorts/90s_Nostalgia_Full_Song_Long_16x9.mp4" download="90s_Nostalgia_Full_Song_Long_16x9.mp4"
                               class="text-indigo-400 hover:text-indigo-300 font-bold flex items-center gap-1">
                                <i class="fa-solid fa-download"></i> Save MP4
                            </a>
                        </div>
                    </div>
                </div>

                <div class="mt-3 flex items-center gap-2 text-xs text-slate-400">
                    <i class="fa-solid fa-circle-check text-emerald-400"></i>
                    <span>Optimized for <strong>YouTube Desktop, Smart TV & Laptop</strong> landscape view with ambient backdrop.</span>
                </div>
            </div>

            <!-- Right Column for 16:9: YouTube Video Metadata & Chapters -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Publishing Kit for Long Format -->
                <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 shadow-xl">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                        <h3 class="text-base font-bold text-white flex items-center gap-2">
                            <i class="fa-brands fa-youtube text-red-500"></i>
                            <span>YouTube Long Video Publish Kit</span>
                        </h3>
                        <span class="text-xs font-semibold px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                            16:9 Format
                        </span>
                    </div>

                    <div class="mt-5 space-y-4">
                        <!-- Title -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="text-[11px] font-bold uppercase tracking-wider text-slate-400">YouTube Video Title</label>
                                <button @click="navigator.clipboard.writeText('Purani Gali (90s Childhood Anthem) | Shaktimaan, Cassette Tape, Kagaz Ki Naav | Official Nostalgia Video'); toast = { show: true, message: 'Long Video Title Copied!' }; setTimeout(() => toast.show = false, 2500)"
                                        class="text-xs text-indigo-400 hover:text-indigo-300 font-semibold flex items-center gap-1">
                                    <i class="fa-regular fa-copy"></i> Copy
                                </button>
                            </div>
                            <div class="p-3 bg-slate-950 rounded-xl border border-slate-800 text-xs font-semibold text-white select-all">
                                Purani Gali (90s Childhood Anthem) | Shaktimaan, Cassette Tape, Kagaz Ki Naav | Official Nostalgia Video
                            </div>
                        </div>

                        <!-- Chapters / Timestamps -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Video Chapters (Timestamps)</label>
                                <button @click="navigator.clipboard.writeText('0:00 Sunday Shaktimaan on CRT TV\n0:20 Mom Ka Danta & Tiffin Chori\n0:39 Cassette Tape & Pen Rewind\n0:58 Baarish Mein Kagaz Ki Naav\n1:17 Cycle Ki Ghanti & Doston Ki Rangoli\n1:36 Aaj Haath Mein Phone Hai (Modern Loneliness)\n1:55 Mitti Ki Khushboo & Yaari\n2:14 Purani Gali (Outro)'); toast = { show: true, message: 'Chapters Copied!' }; setTimeout(() => toast.show = false, 2500)"
                                        class="text-xs text-indigo-400 hover:text-indigo-300 font-semibold flex items-center gap-1">
                                    <i class="fa-regular fa-copy"></i> Copy Chapters
                                </button>
                            </div>
                            <div class="p-3 bg-slate-950 rounded-xl border border-slate-800 text-xs font-mono text-emerald-400 whitespace-pre-line select-all">
0:00 Sunday Shaktimaan on CRT TV
0:20 Mom Ka Danta & Tiffin Chori
0:39 Cassette Tape & Pen Rewind
0:58 Baarish Mein Kagaz Ki Naav
1:17 Cycle Ki Ghanti & Doston Ki Rangoli
1:36 Aaj Haath Mein Phone Hai (Loneliness)
1:55 Mitti Ki Khushboo & Yaari
2:14 Purani Gali (Outro)
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Description</label>
                                <button @click="navigator.clipboard.writeText('A heartwarming emotional journey back to the golden 90s childhood.\n\nFrom waking up on Sunday mornings for Shaktimaan, stealing tiffin with friends, winding cassette tape ribbons with a Natraj pen, to floating paper boats in rainwater puddles and ringing cycle bells down the street...\n\nSong: Purani Gali (90s Pop-Rock Anthem)\nVisuals & Lyrics: 90s Nostalgia AI Studio\n\n📌 Chapters:\n0:00 Sunday Shaktimaan\n0:20 Tiffin Chori\n0:39 Cassette Tape\n0:58 Kagaz Ki Naav\n1:17 Cycle Toli\n1:36 Aaj Haath Mein Phone Hai\n1:55 Mitti Ki Khushboo\n2:14 Purani Gali\n\n❤️ Agar aapka bachpan bhi 90s mein beeta hai, toh like kijiye aur apne bachpan ke yaaron ke sath share kijiye!\n\n#90s #90sKids #PuraniGali #Nostalgia #Shaktimaan #ChildhoodMemories #IndianMusic #90sSong'); toast = { show: true, message: 'Description Copied!' }; setTimeout(() => toast.show = false, 2500)"
                                        class="text-xs text-indigo-400 hover:text-indigo-300 font-semibold flex items-center gap-1">
                                    <i class="fa-regular fa-copy"></i> Copy
                                </button>
                            </div>
                            <div class="p-3 bg-slate-950 rounded-xl border border-slate-800 text-xs text-slate-300 space-y-2 whitespace-pre-line select-all max-h-36 overflow-y-auto">
A heartwarming emotional journey back to the golden 90s childhood.

From waking up on Sunday mornings for Shaktimaan, stealing tiffin with friends, winding cassette tape ribbons with a Natraj pen, to floating paper boats in rainwater puddles and ringing cycle bells down the street...

Song: Purani Gali (90s Pop-Rock Anthem)
Visuals & Lyrics: 90s Nostalgia AI Studio

❤️ Agar aapka bachpan bhi 90s mein beeta hai, toh like kijiye aur apne bachpan ke yaaron ke sath share kijiye!

#90s #90sKids #PuraniGali #Nostalgia #Shaktimaan #ChildhoodMemories #IndianMusic #90sSong
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Direct Links Card -->
                <div class="bg-gradient-to-br from-indigo-950/60 via-slate-900 to-purple-950/60 border border-indigo-500/30 rounded-3xl p-5 shadow-xl">
                    <h4 class="text-sm font-bold text-white mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-cloud-arrow-down text-indigo-400"></i>
                        <span>Ready-To-Download Files</span>
                    </h4>
                    <div class="space-y-2.5">
                        <a href="/media/nostalgia_shorts/90s_Nostalgia_Full_Song_Long_16x9.mp4" download="90s_Nostalgia_Full_Song_Long_16x9.mp4"
                           class="p-3 rounded-xl bg-slate-950/90 border border-indigo-500/50 hover:border-indigo-400 transition flex items-center justify-between group">
                            <div>
                                <div class="text-xs font-bold text-white group-hover:text-indigo-400">16:9 Long Video (Full HD)</div>
                                <div class="text-[11px] text-slate-400">1920x1080 • 10.0 MB • 2m 36s</div>
                            </div>
                            <i class="fa-solid fa-download text-indigo-400 text-sm"></i>
                        </a>

                        <a href="/media/nostalgia_shorts/90s_Nostalgia_Childhood_Short.mp4" download="90s_Nostalgia_Childhood_Short.mp4"
                           class="p-3 rounded-xl bg-slate-950/90 border border-slate-800 hover:border-emerald-500/50 transition flex items-center justify-between group">
                            <div>
                                <div class="text-xs font-bold text-white group-hover:text-emerald-400">60s Viral Short (9:16)</div>
                                <div class="text-[11px] text-slate-400">1080x1920 • 27.8 MB • Shorts & Reels</div>
                            </div>
                            <i class="fa-solid fa-download text-slate-400 group-hover:text-emerald-400 text-sm"></i>
                        </a>

                        <a href="/media/nostalgia_shorts/90s_Nostalgia_Full_Song_Vertical.mp4" download="90s_Nostalgia_Full_Song_Vertical.mp4"
                           class="p-3 rounded-xl bg-slate-950/90 border border-slate-800 hover:border-pink-500/50 transition flex items-center justify-between group">
                            <div>
                                <div class="text-xs font-bold text-white group-hover:text-pink-400">Full Song Vertical (9:16)</div>
                                <div class="text-[11px] text-slate-400">1080x1920 • 16.4 MB • 2m 29s</div>
                            </div>
                            <i class="fa-solid fa-download text-slate-400 group-hover:text-pink-400 text-sm"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODE B: 9:16 Vertical Smartphone View (60s Short or Full Song) -->
        <div x-show="activeVideo !== 'long_16x9'" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left: 9:16 Vertical Smartphone Video Player Frame -->
            <div class="lg:col-span-5 flex flex-col items-center">
                <!-- Phone Mockup Container -->
                <div class="relative w-full max-w-[340px] aspect-[9/16] bg-black rounded-[40px] p-3 shadow-2xl border-4 border-slate-800 ring-1 ring-slate-700/50">
                    <!-- Phone Speaker / Camera Notch -->
                    <div class="absolute top-4 left-1/2 -translate-x-1/2 w-28 h-4 bg-slate-900 rounded-full z-20 flex items-center justify-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-slate-800"></div>
                        <div class="w-8 h-1.5 rounded-full bg-slate-800"></div>
                    </div>

                    <!-- Video Frame -->
                    <div class="w-full h-full rounded-[30px] overflow-hidden bg-slate-950 relative">
                        <!-- 60s Short Video -->
                        <video x-show="activeVideo === 'short'" controls loop playsinline class="w-full h-full object-cover">
                            <source src="/media/nostalgia_shorts/90s_Nostalgia_Childhood_Short.mp4" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>

                        <!-- Full Song Video -->
                        <video x-show="activeVideo === 'full_vertical'" controls loop playsinline class="w-full h-full object-cover">
                            <source src="/media/nostalgia_shorts/90s_Nostalgia_Full_Song_Vertical.mp4" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                </div>

                <div class="mt-4 flex items-center gap-2 text-xs text-slate-400">
                    <i class="fa-solid fa-mobile-screen text-indigo-400"></i>
                    <span>Format: <strong>1080x1920 (Vertical 9:16)</strong> • Ready for YouTube Shorts & Instagram Reels</span>
                </div>
            </div>

            <!-- Right: Ready-to-Publish Copy Kit (Title, Description, Tags) -->
            <div class="lg:col-span-7 space-y-6">
                <!-- Details Card -->
                <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 shadow-xl">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                        <h3 class="text-base font-bold text-white flex items-center gap-2">
                            <i class="fa-brands fa-youtube text-pink-400"></i>
                            <span>YouTube Shorts & Reels Publish Kit</span>
                        </h3>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                            1-Click Copy Ready
                        </span>
                    </div>

                    <div class="mt-5 space-y-4">
                        <!-- Title -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="text-xs font-bold uppercase tracking-wider text-slate-400">Short Video Title</label>
                                <button @click="navigator.clipboard.writeText('Wo Sunday ki Subah aur Shaktimaan! 🥺 90s Ke Woh Din Yaad Hain? #Shorts #90sNostalgia'); toast = { show: true, message: 'Title Copied to Clipboard!' }; setTimeout(() => toast.show = false, 2500)"
                                        class="text-xs text-indigo-400 hover:text-indigo-300 font-semibold flex items-center gap-1">
                                    <i class="fa-regular fa-copy"></i> Copy Title
                                </button>
                            </div>
                            <div class="p-3 bg-slate-950 rounded-xl border border-slate-800 text-xs font-semibold text-white select-all">
                                Wo Sunday ki Subah aur Shaktimaan! 🥺 90s Ke Woh Din Yaad Hain? #Shorts #90sNostalgia
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="text-xs font-bold uppercase tracking-wider text-slate-400">Shorts Description</label>
                                <button @click="navigator.clipboard.writeText('Sunday ki subah woh Shaktimaan ka aana, mom ki daant ke baad tiffin churana, cassette ke fite ko pen se firana aur baarish ke paani mein kagaz ki naav tairana... \n\nAaj haath mein phone hai par dil khali-khali hai, waqt ki is daud mein kahan wo purani gali hai? 🥺❤️\n\nTag your 90s childhood friends in comments! \n🔔 Like, Share & Subscribe for more emotional 90s memories.\n\n#90s #90sKids #PuraniGali #Nostalgia #Shaktimaan #ChildhoodMemories #IndianShorts #ViralReels'); toast = { show: true, message: 'Description Copied!' }; setTimeout(() => toast.show = false, 2500)"
                                        class="text-xs text-indigo-400 hover:text-indigo-300 font-semibold flex items-center gap-1">
                                    <i class="fa-regular fa-copy"></i> Copy Description
                                </button>
                            </div>
                            <div class="p-3 bg-slate-950 rounded-xl border border-slate-800 text-xs text-slate-300 space-y-2 whitespace-pre-line select-all">
Sunday ki subah woh Shaktimaan ka aana, mom ki daant ke baad tiffin churana, cassette ke fite ko pen se firana aur baarish ke paani mein kagaz ki naav tairana...

Aaj haath mein phone hai par dil khali-khali hai, waqt ki is daud mein kahan wo purani gali hai? 🥺❤️

Tag your 90s childhood friends in comments!
🔔 Like, Share & Subscribe for more emotional 90s memories.

#90s #90sKids #PuraniGali #Nostalgia #Shaktimaan #ChildhoodMemories #IndianShorts #ViralReels
                            </div>
                        </div>

                        <!-- SEO Tags -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="text-xs font-bold uppercase tracking-wider text-slate-400">SEO Tags (For YouTube Studio)</label>
                                <button @click="navigator.clipboard.writeText('90s nostalgia, 90s kids, shaktimaan, purani gali, childhood memories, 90s songs, nostalgic songs, viral shorts, reels, indian childhood, cassette tape, rain paper boat'); toast = { show: true, message: 'Tags Copied!' }; setTimeout(() => toast.show = false, 2500)"
                                        class="text-xs text-indigo-400 hover:text-indigo-300 font-semibold flex items-center gap-1">
                                    <i class="fa-regular fa-copy"></i> Copy Tags
                                </button>
                            </div>
                            <div class="p-3 bg-slate-950 rounded-xl border border-slate-800 text-xs text-slate-400 select-all font-mono">
                                90s nostalgia, 90s kids, shaktimaan, purani gali, childhood memories, 90s songs, nostalgic songs, viral shorts, reels, indian childhood, cassette tape, rain paper boat
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Download Direct Links Box -->
                <div class="bg-gradient-to-br from-indigo-950/50 via-slate-900 to-purple-950/50 border border-indigo-500/30 rounded-3xl p-6 shadow-xl">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-circle-down"></i>
                        </div>
                        <div>
                            <h4 class="text-base font-bold text-white">Direct Video Downloads</h4>
                            <p class="text-xs text-slate-400">High-bitrate rendered MP4s stored locally</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4">
                        <a href="/media/nostalgia_shorts/90s_Nostalgia_Childhood_Short.mp4" download="90s_Nostalgia_Childhood_Short.mp4"
                           class="p-4 rounded-2xl bg-slate-950/80 border border-slate-800 hover:border-emerald-500/50 transition flex items-center justify-between group">
                            <div>
                                <span class="block text-xs font-bold text-white group-hover:text-emerald-400">60s Viral Short</span>
                                <span class="text-[11px] text-slate-500">1080x1920 • 27.8 MB</span>
                            </div>
                            <i class="fa-solid fa-arrow-down text-slate-400 group-hover:text-emerald-400 text-base"></i>
                        </a>

                        <a href="/media/nostalgia_shorts/90s_Nostalgia_Full_Song_Vertical.mp4" download="90s_Nostalgia_Full_Song_Vertical.mp4"
                           class="p-4 rounded-2xl bg-slate-950/80 border border-slate-800 hover:border-pink-500/50 transition flex items-center justify-between group">
                            <div>
                                <span class="block text-xs font-bold text-white group-hover:text-pink-400">Full Song Video (149s)</span>
                                <span class="text-[11px] text-slate-500">1080x1920 • 16.4 MB</span>
                            </div>
                            <i class="fa-solid fa-arrow-down text-slate-400 group-hover:text-pink-400 text-base"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 8 SCENE IMAGE GALLERY (Matching Lyrics) -->
    <div class="mt-12 pt-8 border-t border-slate-800">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h3 class="text-2xl font-black text-white flex items-center gap-2">
                    <i class="fa-solid fa-images text-indigo-400"></i>
                    <span>All 8 Generated Lyrics-Matched Visuals</span>
                </h3>
                <p class="text-xs text-slate-400">Switch between 16:9 Landscape Cinema frames and 9:16 Portrait artworks.</p>
            </div>

            <div class="inline-flex p-1 bg-slate-900 border border-slate-800 rounded-xl gap-1">
                <button type="button" @click="galleryMode = '16x9'"
                        :class="galleryMode === '16x9' ? 'bg-indigo-600 text-white shadow' : 'text-slate-400 hover:text-slate-200'"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5">
                    <i class="fa-solid fa-desktop"></i>
                    <span>16:9 Landscape Frames</span>
                </button>
                <button type="button" @click="galleryMode = '9x16'"
                        :class="galleryMode === '9x16' ? 'bg-indigo-600 text-white shadow' : 'text-slate-400 hover:text-slate-200'"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5">
                    <i class="fa-solid fa-mobile-screen"></i>
                    <span>9:16 Portrait Artwork</span>
                </button>
            </div>
        </div>

        <!-- 16:9 Landscape Frames View -->
        <div x-show="galleryMode === '16x9'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Frame 1 -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-lg group">
                <div class="relative aspect-video overflow-hidden bg-slate-950">
                    <img src="/media/nostalgia_shorts/frame_16x9_scene_1.jpg" alt="Sunday Shaktimaan 16:9" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    <span class="absolute top-2 left-2 px-2 py-0.5 rounded text-[10px] font-black bg-rose-600 text-white shadow">Scene 1 • 0:00</span>
                </div>
                <div class="p-3">
                    <div class="text-xs font-bold text-white truncate">Sunday Shaktimaan (CRT TV)</div>
                    <p class="text-[11px] text-slate-400 mt-0.5 line-clamp-1">"Sunday ki subah woh Shaktimaan ka aana..."</p>
                    <a href="/media/nostalgia_shorts/frame_16x9_scene_1.jpg" download class="mt-2 inline-flex items-center gap-1 text-[11px] text-indigo-400 hover:text-indigo-300 font-semibold">
                        <i class="fa-solid fa-download"></i> Download 16:9 Frame
                    </a>
                </div>
            </div>

            <!-- Frame 2 -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-lg group">
                <div class="relative aspect-video overflow-hidden bg-slate-950">
                    <img src="/media/nostalgia_shorts/frame_16x9_scene_2.jpg" alt="Tiffin Chori 16:9" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    <span class="absolute top-2 left-2 px-2 py-0.5 rounded text-[10px] font-black bg-amber-600 text-white shadow">Scene 2 • 0:20</span>
                </div>
                <div class="p-3">
                    <div class="text-xs font-bold text-white truncate">Mom Ka Danta & Tiffin Chori</div>
                    <p class="text-[11px] text-slate-400 mt-0.5 line-clamp-1">"Mom ki daant ke baad, wo tiffin churaana..."</p>
                    <a href="/media/nostalgia_shorts/frame_16x9_scene_2.jpg" download class="mt-2 inline-flex items-center gap-1 text-[11px] text-indigo-400 hover:text-indigo-300 font-semibold">
                        <i class="fa-solid fa-download"></i> Download 16:9 Frame
                    </a>
                </div>
            </div>

            <!-- Frame 3 -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-lg group">
                <div class="relative aspect-video overflow-hidden bg-slate-950">
                    <img src="/media/nostalgia_shorts/frame_16x9_scene_3.jpg" alt="Cassette Tape & Pen 16:9" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    <span class="absolute top-2 left-2 px-2 py-0.5 rounded text-[10px] font-black bg-indigo-600 text-white shadow">Scene 3 • 0:39</span>
                </div>
                <div class="p-3">
                    <div class="text-xs font-bold text-white truncate">Cassette Tape & Pen Rewind</div>
                    <p class="text-[11px] text-slate-400 mt-0.5 line-clamp-1">"Cassette ke fite ko pen se phiraana..."</p>
                    <a href="/media/nostalgia_shorts/frame_16x9_scene_3.jpg" download class="mt-2 inline-flex items-center gap-1 text-[11px] text-indigo-400 hover:text-indigo-300 font-semibold">
                        <i class="fa-solid fa-download"></i> Download 16:9 Frame
                    </a>
                </div>
            </div>

            <!-- Frame 4 -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-lg group">
                <div class="relative aspect-video overflow-hidden bg-slate-950">
                    <img src="/media/nostalgia_shorts/frame_16x9_scene_4.jpg" alt="Kagaz Ki Naav 16:9" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    <span class="absolute top-2 left-2 px-2 py-0.5 rounded text-[10px] font-black bg-blue-600 text-white shadow">Scene 4 • 0:58</span>
                </div>
                <div class="p-3">
                    <div class="text-xs font-bold text-white truncate">Baarish Mein Kagaz Ki Naav</div>
                    <p class="text-[11px] text-slate-400 mt-0.5 line-clamp-1">"Wo barish ke paani me kagaz ki naav tairana..."</p>
                    <a href="/media/nostalgia_shorts/frame_16x9_scene_4.jpg" download class="mt-2 inline-flex items-center gap-1 text-[11px] text-indigo-400 hover:text-indigo-300 font-semibold">
                        <i class="fa-solid fa-download"></i> Download 16:9 Frame
                    </a>
                </div>
            </div>

            <!-- Frame 5 -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-lg group">
                <div class="relative aspect-video overflow-hidden bg-slate-950">
                    <img src="/media/nostalgia_shorts/frame_16x9_scene_5.jpg" alt="Cycle Ki Ghanti 16:9" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    <span class="absolute top-2 left-2 px-2 py-0.5 rounded text-[10px] font-black bg-emerald-600 text-white shadow">Scene 5 • 1:17</span>
                </div>
                <div class="p-3">
                    <div class="text-xs font-bold text-white truncate">Cycle Ki Ghanti & Doston Ki Toli</div>
                    <p class="text-[11px] text-slate-400 mt-0.5 line-clamp-1">"Cycle ki ganti pe nikalti thi rangoli..."</p>
                    <a href="/media/nostalgia_shorts/frame_16x9_scene_5.jpg" download class="mt-2 inline-flex items-center gap-1 text-[11px] text-indigo-400 hover:text-indigo-300 font-semibold">
                        <i class="fa-solid fa-download"></i> Download 16:9 Frame
                    </a>
                </div>
            </div>

            <!-- Frame 6 -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-lg group">
                <div class="relative aspect-video overflow-hidden bg-slate-950">
                    <img src="/media/nostalgia_shorts/frame_16x9_scene_6.jpg" alt="Aaj Haath Mein Phone 16:9" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    <span class="absolute top-2 left-2 px-2 py-0.5 rounded text-[10px] font-black bg-purple-600 text-white shadow">Scene 6 • 1:36</span>
                </div>
                <div class="p-3">
                    <div class="text-xs font-bold text-white truncate">Aaj Haath Mein Phone (Loneliness)</div>
                    <p class="text-[11px] text-slate-400 mt-0.5 line-clamp-1">"Aaj haath mein phone hai, par dil khali-khali hai..."</p>
                    <a href="/media/nostalgia_shorts/frame_16x9_scene_6.jpg" download class="mt-2 inline-flex items-center gap-1 text-[11px] text-indigo-400 hover:text-indigo-300 font-semibold">
                        <i class="fa-solid fa-download"></i> Download 16:9 Frame
                    </a>
                </div>
            </div>

            <!-- Frame 7 -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-lg group">
                <div class="relative aspect-video overflow-hidden bg-slate-950">
                    <img src="/media/nostalgia_shorts/frame_16x9_scene_7.jpg" alt="Mitti Ki Khushboo 16:9" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    <span class="absolute top-2 left-2 px-2 py-0.5 rounded text-[10px] font-black bg-teal-600 text-white shadow">Scene 7 • 1:55</span>
                </div>
                <div class="p-3">
                    <div class="text-xs font-bold text-white truncate">Mitti Ki Khushboo & Yaari</div>
                    <p class="text-[11px] text-slate-400 mt-0.5 line-clamp-1">"Mitti ki khushboo aur doston ka shor..."</p>
                    <a href="/media/nostalgia_shorts/frame_16x9_scene_7.jpg" download class="mt-2 inline-flex items-center gap-1 text-[11px] text-indigo-400 hover:text-indigo-300 font-semibold">
                        <i class="fa-solid fa-download"></i> Download 16:9 Frame
                    </a>
                </div>
            </div>

            <!-- Frame 8 -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-lg group">
                <div class="relative aspect-video overflow-hidden bg-slate-950">
                    <img src="/media/nostalgia_shorts/frame_16x9_scene_8.jpg" alt="Purani Gali 16:9" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    <span class="absolute top-2 left-2 px-2 py-0.5 rounded text-[10px] font-black bg-slate-700 text-white shadow">Scene 8 • 2:14</span>
                </div>
                <div class="p-3">
                    <div class="text-xs font-bold text-white truncate">Purani Gali (Twilight Outro)</div>
                    <p class="text-[11px] text-slate-400 mt-0.5 line-clamp-1">"Wahi gali hai, par ab koi nahi..."</p>
                    <a href="/media/nostalgia_shorts/frame_16x9_scene_8.jpg" download class="mt-2 inline-flex items-center gap-1 text-[11px] text-indigo-400 hover:text-indigo-300 font-semibold">
                        <i class="fa-solid fa-download"></i> Download 16:9 Frame
                    </a>
                </div>
            </div>
        </div>

        <!-- 9:16 Portrait Artworks View -->
        <div x-show="galleryMode === '9x16'" class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <!-- Scene 1 -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-lg group">
                <div class="relative aspect-[9/16] overflow-hidden bg-slate-950">
                    <img src="/media/nostalgia_shorts/scene_1_shaktimaan.jpg" alt="Sunday Shaktimaan" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    <span class="absolute top-2 left-2 px-2 py-0.5 rounded text-[10px] font-black bg-rose-600 text-white shadow">Scene 1</span>
                </div>
                <div class="p-3">
                    <div class="text-xs font-bold text-white truncate">Sunday Shaktimaan</div>
                    <p class="text-[11px] text-slate-400 mt-0.5 line-clamp-2">"Sunday ki subah woh Shaktimaan ka aana..."</p>
                    <a href="/media/nostalgia_shorts/scene_1_shaktimaan.jpg" download class="mt-2 block text-[11px] text-indigo-400 hover:underline">Download 9:16 Art</a>
                </div>
            </div>

            <!-- Scene 2 -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-lg group">
                <div class="relative aspect-[9/16] overflow-hidden bg-slate-950">
                    <img src="/media/nostalgia_shorts/scene_2_tiffin.jpg" alt="Tiffin Chori" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    <span class="absolute top-2 left-2 px-2 py-0.5 rounded text-[10px] font-black bg-amber-600 text-white shadow">Scene 2</span>
                </div>
                <div class="p-3">
                    <div class="text-xs font-bold text-white truncate">Tiffin Chori</div>
                    <p class="text-[11px] text-slate-400 mt-0.5 line-clamp-2">"Mom ki daant ke baad, wo tiffin churaana..."</p>
                    <a href="/media/nostalgia_shorts/scene_2_tiffin.jpg" download class="mt-2 block text-[11px] text-indigo-400 hover:underline">Download 9:16 Art</a>
                </div>
            </div>

            <!-- Scene 3 -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-lg group">
                <div class="relative aspect-[9/16] overflow-hidden bg-slate-950">
                    <img src="/media/nostalgia_shorts/scene_3_cassette.jpg" alt="Cassette Tape & Pen" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    <span class="absolute top-2 left-2 px-2 py-0.5 rounded text-[10px] font-black bg-indigo-600 text-white shadow">Scene 3</span>
                </div>
                <div class="p-3">
                    <div class="text-xs font-bold text-white truncate">Cassette Tape & Pen</div>
                    <p class="text-[11px] text-slate-400 mt-0.5 line-clamp-2">"Cassette ke fite ko pen se phiraana..."</p>
                    <a href="/media/nostalgia_shorts/scene_3_cassette.jpg" download class="mt-2 block text-[11px] text-indigo-400 hover:underline">Download 9:16 Art</a>
                </div>
            </div>

            <!-- Scene 4 -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-lg group">
                <div class="relative aspect-[9/16] overflow-hidden bg-slate-950">
                    <img src="/media/nostalgia_shorts/scene_4_paper_boats.jpg" alt="Kagaz Ki Naav" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    <span class="absolute top-2 left-2 px-2 py-0.5 rounded text-[10px] font-black bg-blue-600 text-white shadow">Scene 4</span>
                </div>
                <div class="p-3">
                    <div class="text-xs font-bold text-white truncate">Kagaz Ki Naav</div>
                    <p class="text-[11px] text-slate-400 mt-0.5 line-clamp-2">"Wo barish ke paani me kagaz ki naav tairana..."</p>
                    <a href="/media/nostalgia_shorts/scene_4_paper_boats.jpg" download class="mt-2 block text-[11px] text-indigo-400 hover:underline">Download 9:16 Art</a>
                </div>
            </div>

            <!-- Scene 5 -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-lg group">
                <div class="relative aspect-[9/16] overflow-hidden bg-slate-950">
                    <img src="/media/nostalgia_shorts/scene_5_cycle_toli.jpg" alt="Yaaron Ki Toli" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    <span class="absolute top-2 left-2 px-2 py-0.5 rounded text-[10px] font-black bg-emerald-600 text-white shadow">Scene 5</span>
                </div>
                <div class="p-3">
                    <div class="text-xs font-bold text-white truncate">Yaaron Ki Toli (Cycle)</div>
                    <p class="text-[11px] text-slate-400 mt-0.5 line-clamp-2">"Cycle ki ganti pe nikalti thi rangoli..."</p>
                    <a href="/media/nostalgia_shorts/scene_5_cycle_toli.jpg" download class="mt-2 block text-[11px] text-indigo-400 hover:underline">Download 9:16 Art</a>
                </div>
            </div>

            <!-- Scene 6 -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-lg group">
                <div class="relative aspect-[9/16] overflow-hidden bg-slate-950">
                    <img src="/media/nostalgia_shorts/scene_6_phone_loneliness.jpg" alt="Modern Loneliness" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    <span class="absolute top-2 left-2 px-2 py-0.5 rounded text-[10px] font-black bg-purple-600 text-white shadow">Scene 6</span>
                </div>
                <div class="p-3">
                    <div class="text-xs font-bold text-white truncate">Aaj Haath Mein Phone Hai</div>
                    <p class="text-[11px] text-slate-400 mt-0.5 line-clamp-2">"Aaj haath mein phone hai, par dil khali-khali hai..."</p>
                    <a href="/media/nostalgia_shorts/scene_6_phone_loneliness.jpg" download class="mt-2 block text-[11px] text-indigo-400 hover:underline">Download 9:16 Art</a>
                </div>
            </div>

            <!-- Scene 7 -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-lg group">
                <div class="relative aspect-[9/16] overflow-hidden bg-slate-950">
                    <img src="/media/nostalgia_shorts/scene_7_mitti_friends.jpg" alt="Mitti Ki Khushboo" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    <span class="absolute top-2 left-2 px-2 py-0.5 rounded text-[10px] font-black bg-teal-600 text-white shadow">Scene 7</span>
                </div>
                <div class="p-3">
                    <div class="text-xs font-bold text-white truncate">Mitti Ki Khushboo</div>
                    <p class="text-[11px] text-slate-400 mt-0.5 line-clamp-2">"Mitti ki khushboo aur doston ka shor..."</p>
                    <a href="/media/nostalgia_shorts/scene_7_mitti_friends.jpg" download class="mt-2 block text-[11px] text-indigo-400 hover:underline">Download 9:16 Art</a>
                </div>
            </div>

            <!-- Scene 8 -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-lg group">
                <div class="relative aspect-[9/16] overflow-hidden bg-slate-950">
                    <img src="/media/nostalgia_shorts/scene_8_empty_street.jpg" alt="Purani Gali" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    <span class="absolute top-2 left-2 px-2 py-0.5 rounded text-[10px] font-black bg-slate-700 text-white shadow">Scene 8</span>
                </div>
                <div class="p-3">
                    <div class="text-xs font-bold text-white truncate">Purani Gali (Dusk)</div>
                    <p class="text-[11px] text-slate-400 mt-0.5 line-clamp-2">"Wahi gali hai, par ab koi nahi..."</p>
                    <a href="/media/nostalgia_shorts/scene_8_empty_street.jpg" download class="mt-2 block text-[11px] text-indigo-400 hover:underline">Download 9:16 Art</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
