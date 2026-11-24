@extends('layouts.app')

@section('title', 'AI Shorts Creator — Find the moments they’ll replay')

@section('content')
<div class="landing" x-data="{ videoUrl: '', selectedMode: 'song' }">
    <section class="landing-hero" id="top">
        <div class="landing-hero__ambient" aria-hidden="true"></div>
        <div class="landing-hero__motion" data-studio-motion aria-hidden="true"></div>

        <div class="landing-container landing-hero__grid">
            <div class="landing-hero__copy">
                <p class="landing-kicker"><span class="landing-kicker__mark" aria-hidden="true">✦</span> Your ideas deserve a bigger audience</p>
                <h1>Find the moment <span>they’ll replay.</span></h1>
                <p class="landing-hero__description">Turn long videos, songs and podcasts into ready-to-post Shorts. Let AI find the hook, frame the story and write the details.</p>

                <form action="{{ route('projects.create') }}" method="GET" class="landing-create" aria-label="Start creating shorts">
                    <label for="landing-video-url" class="landing-create__label">Start with a YouTube link</label>
                    <div class="landing-create__row">
                        <span class="landing-create__play" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M19.6 7.2a2.6 2.6 0 0 0-1.8-1.8C16.2 5 12 5 12 5s-4.2 0-5.8.4a2.6 2.6 0 0 0-1.8 1.8A27 27 0 0 0 4 12a27 27 0 0 0 .4 4.8 2.6 2.6 0 0 0 1.8 1.8C7.8 19 12 19 12 19s4.2 0 5.8-.4a2.6 2.6 0 0 0 1.8-1.8A27 27 0 0 0 20 12a27 27 0 0 0-.4-4.8ZM10 15.4V8.6l5.8 3.4L10 15.4Z"/></svg>
                        </span>
                        <input id="landing-video-url" type="url" name="url" x-model="videoUrl" placeholder="Paste a YouTube link" autocomplete="url" required>
                        <button type="submit" class="landing-button landing-button--primary">Create shorts <span aria-hidden="true">↗</span></button>
                    </div>
                    <fieldset class="landing-create__mode">
                        <legend>Choose the source</legend>
                        <label class="landing-mode" :class="selectedMode === 'song' ? 'is-active' : ''">
                            <input type="radio" name="mode" value="song" x-model="selectedMode">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>
                            Song
                        </label>
                        <label class="landing-mode" :class="selectedMode === 'dialogue' ? 'is-active' : ''">
                            <input type="radio" name="mode" value="dialogue" x-model="selectedMode">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true"><rect x="9" y="2" width="6" height="13" rx="3"/><path d="M5 11a7 7 0 0 0 14 0M12 18v4m-4 0h8"/></svg>
                            Dialogue / podcast
                        </label>
                    </fieldset>
                </form>

                <div class="landing-hero__benefits" aria-label="Included features">
                    <span><i aria-hidden="true">✓</i> Smart hook detection</span>
                    <span><i aria-hidden="true">✓</i> Titles, descriptions & tags</span>
                    <span><i aria-hidden="true">✓</i> Vertical 9:16 export</span>
                </div>
            </div>

            <div class="landing-workspace" data-clip-carousel aria-label="Playing carousel of illustrative short video previews">
                <div class="landing-workspace__top">
                    <div class="landing-workspace__brand"><span class="landing-workspace__brand-icon" aria-hidden="true">▶</span><span class="landing-workspace__brand-title">AI Shorts Creator</span><span class="landing-workspace__divider" aria-hidden="true"></span><span>Clip preview</span></div>
                    <span class="landing-workspace__sample">Live preview · muted</span>
                </div>
                <div class="landing-workspace__reels">
                    <button type="button" class="landing-workspace__reel" data-carousel-slide data-position="left" data-label="Song highlight" data-hook-start="10" data-hook-width="31" aria-label="Play song highlight preview">
                        <video muted playsinline preload="metadata" poster="{{ asset('showcase-song.webp') }}" aria-hidden="true"><source src="{{ asset('showcase-song.mp4') }}" type="video/mp4"></video>
                        <span class="landing-workspace__reel-tag">Song</span>
                        <span class="landing-workspace__caption">A chorus you remember.</span>
                    </button>
                    <button type="button" class="landing-workspace__reel" data-carousel-slide data-position="center" data-label="Podcast hook" data-hook-start="52" data-hook-width="27" aria-label="Play podcast hook preview">
                        <video muted playsinline preload="metadata" poster="{{ asset('showcase-dialogue.webp') }}" aria-hidden="true"><source src="{{ asset('showcase-dialogue.mp4') }}" type="video/mp4"></video>
                        <span class="landing-workspace__reel-tag">Dialogue</span>
                        <span class="landing-workspace__caption">A moment worth sharing.</span>
                    </button>
                    <button type="button" class="landing-workspace__reel" data-carousel-slide data-position="right" data-label="Story moment" data-hook-start="65" data-hook-width="25" aria-label="Play story moment preview">
                        <video muted playsinline preload="metadata" poster="{{ asset('showcase-story.webp') }}" aria-hidden="true"><source src="{{ asset('showcase-story.mp4') }}" type="video/mp4"></video>
                        <span class="landing-workspace__reel-tag">Story</span>
                        <span class="landing-workspace__caption">A story worth keeping.</span>
                    </button>
                </div>
                <div class="landing-workspace__timeline">
                    <div class="landing-workspace__timeline-meta"><span data-carousel-label>Podcast hook</span><span data-carousel-time>0:00 / 0:06</span></div>
                    <div class="landing-workspace__wave" aria-hidden="true">
                        @foreach ([18, 30, 47, 26, 58, 38, 68, 46, 23, 42, 70, 36, 56, 76, 39, 59, 27, 63, 82, 44, 65, 29, 52, 74, 33, 57, 23, 49, 69, 32, 54, 21] as $height)
                            <span style="--bar-height: {{ $height }}%"></span>
                        @endforeach
                        <span class="landing-workspace__hook" data-carousel-hook></span>
                        <span class="landing-workspace__playhead" data-carousel-playhead></span>
                    </div>
                    <div class="landing-workspace__timeline-footer"><span data-carousel-hint>Playing · Podcast preview</span><div class="landing-workspace__controls"><button type="button" data-carousel-prev aria-label="Previous preview">←</button><span data-carousel-count>02 / 03</span><button type="button" data-carousel-next aria-label="Next preview">→</button></div></div>
                </div>
            </div>
        </div>
    </section>

    <section class="landing-steps" id="how-it-works" aria-labelledby="landing-steps-title">
        <div class="landing-container">
            <div class="landing-section-heading landing-section-heading--compact">
                <span class="landing-section-heading__eyebrow">A simple creative flow</span>
                <h2 id="landing-steps-title">From long form to can’t-skip.</h2>
            </div>
            <div class="landing-steps__grid">
                <article class="landing-step">
                    <span class="landing-step__number">01</span>
                    <div class="landing-step__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M10 13a5 5 0 0 0 7.1 0l3-3a5 5 0 0 0-7.1-7.1l-1.7 1.7M14 11a5 5 0 0 0-7.1 0l-3 3a5 5 0 0 0 7.1 7.1l1.7-1.7"/></svg></div>
                    <h3>Add your video</h3>
                    <p>Paste a YouTube link or upload a video from your device.</p>
                </article>
                <article class="landing-step">
                    <span class="landing-step__number">02</span>
                    <div class="landing-step__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m12 3 1.9 5.1L19 10l-5.1 1.9L12 17l-1.9-5.1L5 10l5.1-1.9L12 3ZM19 17l.7 1.3L21 19l-1.3.7L19 21l-.7-1.3L17 19l1.3-.7L19 17Z"/></svg></div>
                    <h3>Find the best hooks</h3>
                    <p>AI picks engaging moments and prepares titles and tags.</p>
                </article>
                <article class="landing-step">
                    <span class="landing-step__number">03</span>
                    <div class="landing-step__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 16V3m0 0L7 8m5-5 5 5M4 15v4a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-4"/></svg></div>
                    <h3>Export & share</h3>
                    <p>Download vertical Shorts ready for YouTube, Reels and TikTok.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="landing-showcase" id="demo-shorts" aria-labelledby="landing-showcase-title">
        <div class="landing-container">
            <div class="landing-section-heading landing-section-heading--split">
                <div><span class="landing-section-heading__eyebrow">What you can make</span><h2 id="landing-showcase-title">Every story has a standout moment.</h2></div>
                <p>Illustrative previews of the formats your next video could become. Your own clips, titles and descriptions are generated from your source.</p>
            </div>
            <div class="landing-showcase__grid">
                <article class="landing-showcase-card">
                    <div class="landing-showcase-card__image"><img src="{{ asset('showcase-song.webp') }}" alt="Singer performing on stage" loading="lazy"><span>Song highlight</span></div>
                    <div class="landing-showcase-card__body"><h3>Let the chorus lead.</h3><p>Find the part of a song people want to hear again.</p><span>Music · 9:16 short</span></div>
                </article>
                <article class="landing-showcase-card">
                    <div class="landing-showcase-card__image"><img src="{{ asset('showcase-dialogue.webp') }}" alt="Speaker recording a podcast" loading="lazy"><span>Podcast hook</span></div>
                    <div class="landing-showcase-card__body"><h3>Keep the conversation moving.</h3><p>Pull a sharp idea out of a longer discussion.</p><span>Dialogue · 9:16 short</span></div>
                </article>
                <article class="landing-showcase-card">
                    <div class="landing-showcase-card__image"><img src="{{ asset('showcase-story.webp') }}" alt="Creator outdoors at sunset" loading="lazy"><span>Story moment</span></div>
                    <div class="landing-showcase-card__body"><h3>Give your story its own stage.</h3><p>Reframe a memorable moment for vertical viewing.</p><span>Video · 9:16 short</span></div>
                </article>
            </div>
        </div>
    </section>

    <section class="landing-recent" id="recent-shorts" data-recent-shorts data-feed-url="{{ route('api.recent-shorts') }}" aria-labelledby="landing-recent-title">
        <div class="landing-container">
            <div class="landing-section-heading landing-section-heading--split">
                <div><span class="landing-section-heading__eyebrow">Watch what creators are making</span><h2 id="landing-recent-title">Learn from the Shorts people watch.</h2></div>
                <p>Explore leading creators on YouTube, then create and download clips from videos you own or have permission to use.</p>
            </div>
            <div class="landing-recent__grid" data-recent-list aria-live="polite"></div>
            <div class="landing-recent__empty" data-recent-empty role="status">
                <p data-recent-empty-message>Live popular Shorts are unavailable right now. Browse these creators' latest Shorts on YouTube:</p>
                <div class="landing-recent__browse">
                    <a href="https://www.youtube.com/channel/UCX6OQ3DkcsbYNE6H8uQQuVA/shorts" target="_blank" rel="noopener noreferrer"><span>MB</span><strong>MrBeast</strong><small>Watch Shorts ↗</small></a>
                    <a href="https://www.youtube.com/channel/UCq8DICunczvLuJJq414110A/shorts" target="_blank" rel="noopener noreferrer"><span>ZK</span><strong>Zach King</strong><small>Watch Shorts ↗</small></a>
                    <a href="https://www.youtube.com/channel/UCb8vrqP8Z7Oz9ZTYvUtjUHQ/shorts" target="_blank" rel="noopener noreferrer"><span>DL</span><strong>Daniel LaBelle</strong><small>Watch Shorts ↗</small></a>
                </div>
            </div>
            <p class="landing-recent__note">YouTube videos play in the official player. <a href="https://www.youtube.com/t/terms" target="_blank" rel="noopener noreferrer">YouTube Terms of Service</a></p>
        </div>
    </section>

    <section class="landing-features" id="features" aria-labelledby="landing-features-title">
        <div class="landing-container landing-features__grid">
            <div class="landing-features__intro">
                <span class="landing-section-heading__eyebrow">Made for the whole process</span>
                <h2 id="landing-features-title">More than a crop tool.</h2>
                <p>From the first hook to the final download, keep your creative flow in one place.</p>
                <a href="{{ auth()->check() ? route('projects.create') : route('register') }}" class="landing-text-link">Start creating <span aria-hidden="true">↗</span></a>
            </div>
            <div class="landing-features__list">
                <div class="landing-feature"><span class="landing-feature__icon">✳</span><div><h3>Pick the peak moment</h3><p>Choose song and dialogue modes for the right kind of hook.</p></div></div>
                <div class="landing-feature"><span class="landing-feature__icon">▣</span><div><h3>Built for vertical</h3><p>Frame source videos into 9:16 clips with a clean vertical layout.</p></div></div>
                <div class="landing-feature"><span class="landing-feature__icon">✎</span><div><h3>Publish with context</h3><p>Get a title, description and tags alongside each short.</p></div></div>
            </div>
        </div>
    </section>

    <section class="landing-pricing" id="pricing" aria-labelledby="landing-pricing-title">
        <div class="landing-container">
            <div class="landing-section-heading landing-section-heading--split">
                <div><span class="landing-section-heading__eyebrow">Credits that fit your workflow</span><h2 id="landing-pricing-title">Start free. Grow on your terms.</h2></div>
                <p>New accounts receive 20 video credits. When you need more, pick a one-time credit pack.</p>
            </div>
            <div class="landing-pricing__grid">
                <div class="landing-price"><span>Trial pack</span><h3>₹99</h3><p><strong>3 credits</strong> for 3 full videos</p></div>
                <div class="landing-price landing-price--featured"><span>Popular · Pro pack</span><h3>₹499</h3><p><strong>20 credits</strong> for 20 full videos</p></div>
                <div class="landing-price"><span>Agency pack</span><h3>₹999</h3><p><strong>50 credits</strong> for 50 full videos</p></div>
            </div>
            <a href="{{ auth()->check() ? route('wallet') : route('register') }}" class="landing-button landing-button--outline">Explore all credit packs <span aria-hidden="true">↗</span></a>
        </div>
    </section>

    <section class="landing-nostalgia" aria-labelledby="landing-nostalgia-title">
        <div class="landing-container landing-nostalgia__panel">
            <div><span class="landing-nostalgia__label">A little something extra</span><h2 id="landing-nostalgia-title">The 90s nostalgia studio</h2><p>A playful home for music memories, childhood stories and throwback videos.</p></div>
            <a href="{{ auth()->check() ? route('nostalgia.studio') : route('register') }}" class="landing-button landing-button--light">Explore the studio <span aria-hidden="true">↗</span></a>
        </div>
    </section>

    <section class="landing-final" aria-labelledby="landing-final-title">
        <div class="landing-container"><span class="landing-final__spark" aria-hidden="true">✦</span><h2 id="landing-final-title">Your next great short is already in your video.</h2><p>Find it, shape it and share it.</p><a href="{{ auth()->check() ? route('projects.create') : route('register') }}" class="landing-button landing-button--primary">Create your first short <span aria-hidden="true">↗</span></a></div>
    </section>
</div>
@endsection
