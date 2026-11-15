@extends('layouts.app')

@section('title', '90s Nostalgia Vertical Shorts Studio')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ activeVideo: 'short' }">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 text-xs text-amber-400 font-semibold mb-1">
                <i class="fa-solid fa-sparkles"></i>
                <span>90s Nostalgia • AI Generated Music Video</span>
            </div>
            <h1 class="text-3xl font-black text-white flex items-center gap-3">
                <span class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-amber-500 to-pink-500 flex items-center justify-center text-white shadow-lg shadow-pink-500/25">
                    <i class="fa-solid fa-clapperboard"></i>
                </span>
                Purani Gali • 90s Memories Vertical Shorts
            </h1>
            <p class="text-xs sm:text-sm text-slate-400 mt-1">
                Sunday Shaktimaan, tiffin chori, cassette tape, kagaz ki naav aur doston ki cycle toli.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="/media/nostalgia_shorts/90s_Nostalgia_Childhood_Short.mp4" download="90s_Nostalgia_Childhood_Short.mp4"
               class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-extrabold text-sm shadow-xl shadow-emerald-500/25 transition hover:scale-105">
                <i class="fa-solid fa-download"></i>
                <span>Download 60s Short (HD MP4)</span>
            </a>
            <a href="/media/nostalgia_shorts/90s_Nostalgia_Full_Song_Vertical.mp4" download="90s_Nostalgia_Full_Song_Vertical.mp4"
               class="inline-flex items-center gap-2 px-4 py-3 rounded-2xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs border border-slate-700 transition">
                <i class="fa-solid fa-file-video text-pink-400"></i>
                <span>Full Song (2m29s)</span>
            </a>
        </div>
    </div>

    <!-- Main Studio Player & Metadata Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-12">
        <!-- Left: 9:16 Vertical Smartphone Video Player Frame -->
        <div class="lg:col-span-5 flex flex-col items-center">
            <!-- Video Switcher Tabs -->
            <div class="w-full max-w-[340px] grid grid-cols-2 gap-2 p-1.5 bg-slate-900 border border-slate-800 rounded-2xl mb-4">
                <button type="button" @click="activeVideo = 'short'"
                        :class="activeVideo === 'short' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-slate-200'"
                        class="py-2 rounded-xl text-xs font-bold transition flex items-center justify-center gap-2">
                    <i class="fa-brands fa-youtube text-rose-400"></i>
                    <span>60s Viral Short</span>
                </button>
                <button type="button" @click="activeVideo = 'full'"
                        :class="activeVideo === 'full' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-slate-200'"
                        class="py-2 rounded-xl text-xs font-bold transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-music text-pink-400"></i>
                    <span>Full Song (149s)</span>
                </button>
            </div>

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
                    <video x-show="activeVideo === 'short'" controls autoplay loop playsinline class="w-full h-full object-cover">
                        <source src="/media/nostalgia_shorts/90s_Nostalgia_Childhood_Short.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>

                    <!-- Full Song Video -->
                    <video x-show="activeVideo === 'full'" controls loop playsinline class="w-full h-full object-cover">
                        <source src="/media/nostalgia_shorts/90s_Nostalgia_Full_Song_Vertical.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>

            <div class="mt-4 flex items-center gap-2 text-xs text-slate-400">
                <i class="fa-solid fa-mobile-screen text-indigo-400"></i>
                <span>Format: <strong>1080x1920 (Vertical 9:16)</strong> • Ready for YouTube Shorts & Reels</span>
            </div>
        </div>

        <!-- Right: Ready-to-Publish Copy Kit (Title, Description, Tags) -->
        <div class="lg:col-span-7 space-y-6">
            <!-- Details Card -->
            <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 shadow-xl">
                <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                    <h3 class="text-base font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-bullhorn text-pink-400"></i>
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
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400">Video Description</label>
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
                        <h4 class="text-base font-bold text-white">Direct Video Files Saved On Server</h4>
                        <p class="text-xs text-slate-400">Download directly or embed in your YouTube channel</p>
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

    <!-- 8 SCENE IMAGE GALLERY (Matching Lyrics) -->
    <div class="mt-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-2xl font-black text-white flex items-center gap-2">
                    <i class="fa-solid fa-images text-indigo-400"></i>
                    <span>All 8 Generated 9:16 Vertical Visuals</span>
                </h3>
                <p class="text-xs text-slate-400">Each image was generated in 9:16 portrait orientation to match specific lyrics of the song.</p>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <!-- Scene 1 -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-lg group">
                <div class="relative aspect-[9/16] overflow-hidden bg-slate-950">
                    <img src="/media/nostalgia_shorts/scene_1_shaktimaan.jpg" alt="Sunday Shaktimaan" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    <span class="absolute top-2 left-2 px-2 py-0.5 rounded text-[10px] font-black bg-rose-600 text-white shadow">Scene 1</span>
                </div>
                <div class="p-3">
                    <div class="text-xs font-bold text-white truncate">Sunday Shaktimaan</div>
                    <p class="text-[11px] text-slate-400 mt-0.5 line-clamp-2">"Sunday ki subah woh Shaktimaan ka aana..."</p>
                    <a href="/media/nostalgia_shorts/scene_1_shaktimaan.jpg" download class="mt-2 block text-[11px] text-indigo-400 hover:underline">Download Image</a>
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
                    <a href="/media/nostalgia_shorts/scene_2_tiffin.jpg" download class="mt-2 block text-[11px] text-indigo-400 hover:underline">Download Image</a>
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
                    <a href="/media/nostalgia_shorts/scene_3_cassette.jpg" download class="mt-2 block text-[11px] text-indigo-400 hover:underline">Download Image</a>
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
                    <a href="/media/nostalgia_shorts/scene_4_paper_boats.jpg" download class="mt-2 block text-[11px] text-indigo-400 hover:underline">Download Image</a>
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
                    <a href="/media/nostalgia_shorts/scene_5_cycle_toli.jpg" download class="mt-2 block text-[11px] text-indigo-400 hover:underline">Download Image</a>
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
                    <a href="/media/nostalgia_shorts/scene_6_phone_loneliness.jpg" download class="mt-2 block text-[11px] text-indigo-400 hover:underline">Download Image</a>
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
                    <a href="/media/nostalgia_shorts/scene_7_mitti_friends.jpg" download class="mt-2 block text-[11px] text-indigo-400 hover:underline">Download Image</a>
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
                    <a href="/media/nostalgia_shorts/scene_8_empty_street.jpg" download class="mt-2 block text-[11px] text-indigo-400 hover:underline">Download Image</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
