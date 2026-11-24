@extends('layouts.app')

@section('title', '90s Nostalgia Studio · AI Shorts Creator')

@section('content')
<div class="pb-16">
    <a href="{{ route('home') }}" class="mb-6 inline-flex min-h-11 items-center gap-2 text-sm font-semibold text-[#526078] transition-colors hover:text-[#4D5CFF]">
        <i class="fa-solid fa-arrow-left text-xs" aria-hidden="true"></i>
        Back to the studio
    </a>

    <header class="relative overflow-hidden rounded-[30px] border border-[#DDE4F0] bg-white px-6 py-9 shadow-[0_25px_70px_rgba(21,34,56,.06)] sm:px-10 sm:py-12 lg:px-14 lg:py-14">
        <div class="pointer-events-none absolute -right-28 -top-36 h-96 w-96 rounded-full bg-[#DEE2FF]/80 blur-3xl" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -bottom-32 left-1/3 h-72 w-72 rounded-full bg-[#FFE4DD]/60 blur-3xl" aria-hidden="true"></div>

        <div class="relative grid items-center gap-10 lg:grid-cols-[.95fr_1.05fr] lg:gap-14">
            <div>
                <p class="mb-5 inline-flex items-center gap-2 rounded-full border border-[#DCE2FF] bg-[#F2F3FF] px-3 py-1.5 text-xs font-bold text-[#4251CE]">
                    <i class="fa-solid fa-compact-disc" aria-hidden="true"></i>
                    90s Nostalgia Studio
                </p>
                <h1 class="max-w-xl font-['Space_Grotesk'] text-4xl font-bold leading-[1.04] tracking-[-.065em] text-[#152238] sm:text-5xl lg:text-[62px]">
                    Make the memories worth replaying.
                </h1>
                <p class="mt-6 max-w-lg text-base leading-7 text-[#5E6C83] sm:text-lg">
                    Sunday morning TV, cassette rewinds and paper boats in the rain. Bring your own throwback video and find the moments that still feel close.
                </p>
                <div class="mt-8 flex flex-wrap items-center gap-3">
                    <a href="{{ route('projects.create') }}" class="inline-flex min-h-12 items-center justify-center gap-2 rounded-xl bg-[#4D5CFF] px-5 py-3 text-sm font-bold text-white shadow-[0_10px_24px_rgba(77,92,255,.22)] transition hover:-translate-y-0.5 hover:bg-[#3949E9] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#4D5CFF]">
                        Create from your video
                        <i class="fa-solid fa-arrow-up-right-from-square text-xs" aria-hidden="true"></i>
                    </a>
                    <span class="text-sm text-[#687684]">Song and dialogue modes available</span>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-[26px] border border-[#273456] bg-[#17223C] p-5 text-white shadow-[0_22px_45px_rgba(21,34,56,.2)] sm:p-7" aria-label="Illustrative scene board, not a playable video">
                <div class="flex items-center justify-between border-b border-white/15 pb-4 text-xs font-semibold text-[#C9D1EA]">
                    <span class="inline-flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-[#FF735B]"></span> A reel of familiar moments</span>
                    <span>Concept board</span>
                </div>
                <div class="mt-6 grid grid-cols-[1.15fr_.65fr] items-end gap-3 sm:gap-5">
                    <div class="relative aspect-video overflow-hidden rounded-2xl border border-white/20 bg-gradient-to-br from-[#515D9D] via-[#2B355F] to-[#11192D] p-4 shadow-[0_15px_30px_rgba(0,0,0,.22)]">
                        <div class="absolute -right-7 -top-10 h-36 w-36 rounded-full bg-[#FFB69D]/30 blur-2xl" aria-hidden="true"></div>
                        <div class="relative flex h-full flex-col justify-between">
                            <span class="text-[10px] font-bold text-[#DCE2FF]">Widescreen story · 16:9</span>
                            <div>
                                <div class="mb-3 flex gap-1.5" aria-hidden="true"><span class="h-8 w-1.5 rounded-full bg-[#FFB699]/80"></span><span class="h-12 w-1.5 rounded-full bg-[#CBD1FF]/80"></span><span class="h-7 w-1.5 rounded-full bg-[#FFB699]/80"></span><span class="h-10 w-1.5 rounded-full bg-[#CBD1FF]/80"></span></div>
                                <span class="font-['Space_Grotesk'] text-lg font-bold leading-tight sm:text-2xl">Purani Gali</span>
                                <span class="mt-0.5 block text-[11px] text-[#D9DEF4]">A story worth remembering</span>
                            </div>
                        </div>
                    </div>
                    <div class="relative aspect-[9/16] overflow-hidden rounded-2xl border-[3px] border-[#9DA8FF] bg-gradient-to-b from-[#FFB497] via-[#644D7D] to-[#1B2A4A] p-3 shadow-[0_15px_30px_rgba(0,0,0,.25)] sm:p-4">
                        <div class="absolute left-1/2 top-1 h-1 w-10 -translate-x-1/2 rounded-full bg-[#17223C]/70" aria-hidden="true"></div>
                        <div class="relative flex h-full flex-col justify-between">
                            <span class="text-[9px] font-bold text-[#17223C]">Short-form · 9:16</span>
                            <span class="font-['Space_Grotesk'] text-base font-bold leading-tight text-white drop-shadow sm:text-xl">The good old days.</span>
                        </div>
                    </div>
                </div>
                <div class="mt-5 flex items-center gap-2 text-[11px] font-medium text-[#B6C1E3]"><i class="fa-solid fa-circle-info" aria-hidden="true"></i> Format illustration · no video is attached</div>
            </div>
        </div>
    </header>

    <section class="mt-8 flex flex-col gap-4 rounded-2xl border border-[#DCE2FF] bg-[#EEF0FF] px-5 py-5 sm:flex-row sm:items-center sm:px-7" aria-labelledby="nostalgia-media-status">
        <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white text-[#4D5CFF]"><i class="fa-solid fa-film" aria-hidden="true"></i></span>
        <div>
            <h2 id="nostalgia-media-status" class="font-['Space_Grotesk'] text-base font-bold text-[#152238]">The sample video isn’t available right now</h2>
            <p class="mt-1 text-sm leading-6 text-[#5E6C83]">The previous preview and download files are missing. You can still create Shorts from your own YouTube link or uploaded video.</p>
        </div>
        <a href="{{ route('projects.create') }}" class="inline-flex min-h-11 shrink-0 items-center justify-center rounded-xl border border-[#B9C2FF] bg-white px-4 py-2 text-sm font-bold text-[#4251CE] transition hover:bg-[#F7F8FF] sm:ml-auto">Start a project</a>
    </section>

    <section class="mt-14" aria-labelledby="nostalgia-scenes-title">
        <div class="mb-7 flex flex-col justify-between gap-3 sm:flex-row sm:items-end">
            <div>
                <span class="text-xs font-bold text-[#4D5CFF]">The creative direction</span>
                <h2 id="nostalgia-scenes-title" class="mt-2 font-['Space_Grotesk'] text-3xl font-bold tracking-[-.045em] text-[#152238] sm:text-4xl">The moments that take us back.</h2>
            </div>
            <p class="max-w-sm text-sm leading-6 text-[#687684]">A simple scene board for the nostalgia theme. These are ideas, not playable clips.</p>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <article class="rounded-[22px] border border-[#DDE4F0] bg-white p-6 shadow-[0_12px_35px_rgba(21,34,56,.04)]">
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-[#E7EAFF] text-xl text-[#4D5CFF]"><i class="fa-solid fa-tv" aria-hidden="true"></i></span>
                <h3 class="mt-5 font-['Space_Grotesk'] text-xl font-bold text-[#152238]">Sunday morning TV</h3>
                <p class="mt-2 text-sm leading-6 text-[#687684]">That familiar wait for your favourite show, before the rest of the day began.</p>
            </article>
            <article class="rounded-[22px] border border-[#DDE4F0] bg-white p-6 shadow-[0_12px_35px_rgba(21,34,56,.04)]">
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-[#FFE9E3] text-xl text-[#E3614B]"><i class="fa-solid fa-music" aria-hidden="true"></i></span>
                <h3 class="mt-5 font-['Space_Grotesk'] text-xl font-bold text-[#152238]">Cassette rewind</h3>
                <p class="mt-2 text-sm leading-6 text-[#687684]">A pen, a tape and the song you played until every word felt like yours.</p>
            </article>
            <article class="rounded-[22px] border border-[#DDE4F0] bg-white p-6 shadow-[0_12px_35px_rgba(21,34,56,.04)]">
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-[#E2F0F6] text-xl text-[#287B9D]"><i class="fa-solid fa-cloud-rain" aria-hidden="true"></i></span>
                <h3 class="mt-5 font-['Space_Grotesk'] text-xl font-bold text-[#152238]">Paper boats in rain</h3>
                <p class="mt-2 text-sm leading-6 text-[#687684]">Little stories made on the street, and the friends who were always there.</p>
            </article>
        </div>
    </section>

    <section class="mt-14 rounded-[26px] bg-[#17223C] px-6 py-9 text-white sm:px-10 sm:py-11">
        <div class="flex flex-col items-start justify-between gap-6 sm:flex-row sm:items-center">
            <div><h2 class="font-['Space_Grotesk'] text-2xl font-bold tracking-[-.04em] sm:text-3xl">Have your own throwback video?</h2><p class="mt-2 max-w-xl text-sm leading-6 text-[#CAD4ED]">Upload it, choose Song mode, and let the studio find a short worth sharing.</p></div>
            <a href="{{ route('projects.create') }}" class="inline-flex min-h-12 shrink-0 items-center justify-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-bold text-[#27356A] transition hover:bg-[#EEF0FF]">Create your short <i class="fa-solid fa-arrow-up-right-from-square text-xs" aria-hidden="true"></i></a>
        </div>
    </section>
</div>
@endsection
