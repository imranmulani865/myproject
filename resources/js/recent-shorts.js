const youtubeIdPattern = /^[a-zA-Z0-9_-]{11}$/;
const viewFormatter = new Intl.NumberFormat('en', {
    notation: 'compact',
    maximumFractionDigits: 1,
});
const dateFormatter = new Intl.DateTimeFormat('en', {
    month: 'short',
    day: 'numeric',
    year: 'numeric',
});

function getThumbnailUrl(item) {
    const fallback = `https://i.ytimg.com/vi/${item.id}/hqdefault.jpg`;

    try {
        const url = new URL(item.thumbnail_url);

        if (url.protocol === 'https:' && (url.hostname === 'ytimg.com' || url.hostname.endsWith('.ytimg.com'))) {
            return url.href;
        }
    } catch {
        // A missing or malformed thumbnail can use YouTube's standard image URL.
    }

    return fallback;
}

function formatViews(views) {
    if (views === null || views === undefined || views === '') {
        return 'Views unavailable';
    }

    const count = Number(views);

    return Number.isFinite(count) && count >= 0
        ? `${viewFormatter.format(count)} views`
        : 'Views unavailable';
}

function formatPublishedDate(publishedAt) {
    if (!publishedAt) {
        return 'Date unavailable';
    }

    const date = new Date(publishedAt);

    return Number.isNaN(date.getTime()) ? 'Date unavailable' : dateFormatter.format(date);
}

function formatDuration(seconds) {
    const duration = Number(seconds);

    if (!Number.isFinite(duration) || duration <= 0) {
        return null;
    }

    const wholeSeconds = Math.floor(duration);

    return `${Math.floor(wholeSeconds / 60)}:${String(wholeSeconds % 60).padStart(2, '0')}`;
}

function makeCard(item, onPlay, signal) {
    const title = String(item.title || 'Untitled Short');
    const channel = String(item.channel || 'YouTube creator');
    const card = document.createElement('article');
    const media = document.createElement('div');
    const image = document.createElement('img');
    const imageFallback = document.createElement('span');
    const playButton = document.createElement('button');
    const body = document.createElement('div');
    const heading = document.createElement('h3');
    const channelName = document.createElement('p');
    const meta = document.createElement('p');
    const link = document.createElement('a');

    card.className = 'landing-recent-card';
    media.className = 'landing-recent-card__media';
    image.src = getThumbnailUrl(item);
    image.alt = `Thumbnail for ${title}`;
    image.loading = 'lazy';
    image.decoding = 'async';
    imageFallback.className = 'landing-recent-card__fallback';
    imageFallback.textContent = 'YouTube Short';
    imageFallback.hidden = true;
    image.addEventListener('error', () => {
        image.hidden = true;
        image.style.display = 'none';
        imageFallback.hidden = false;
        media.classList.add('landing-recent-card__media--fallback');
    }, { once: true, signal });
    playButton.className = 'landing-recent-card__play';
    playButton.type = 'button';
    playButton.textContent = 'Play short';
    playButton.setAttribute('aria-label', `Play ${title} in the YouTube player`);
    playButton.addEventListener('click', () => onPlay(item.id, title, media), { signal });

    const duration = formatDuration(item.duration_seconds);

    if (duration) {
        const durationLabel = document.createElement('span');
        durationLabel.className = 'landing-recent-card__duration';
        durationLabel.textContent = duration;
        media.append(image, imageFallback, durationLabel, playButton);
    } else {
        media.append(image, imageFallback, playButton);
    }

    body.className = 'landing-recent-card__body';
    heading.textContent = title;
    channelName.textContent = channel;
    meta.className = 'landing-recent-card__meta';
    meta.textContent = `${formatViews(item.views)} · ${formatPublishedDate(item.published_at)}`;
    link.className = 'landing-recent-card__link';
    link.href = `https://www.youtube.com/shorts/${item.id}`;
    link.target = '_blank';
    link.rel = 'noopener noreferrer';
    link.textContent = 'Watch on YouTube';
    body.append(heading, channelName, meta, link);
    card.append(media, body);

    return card;
}

export function mountRecentShorts(host) {
    const list = host.querySelector('[data-recent-list]');
    const empty = host.querySelector('[data-recent-empty]');
    const emptyMessage = empty?.querySelector('[data-recent-empty-message]');
    const feedUrl = host.dataset.feedUrl;

    if (!list || !empty || !feedUrl) {
        return () => {};
    }

    const controller = new AbortController();
    let isDisposed = false;
    let activePlayer = null;

    const restorePreviousCard = () => {
        if (!activePlayer) {
            return;
        }

        const { media, originalChildren } = activePlayer;
        media.replaceChildren(...originalChildren);
        activePlayer = null;
    };

    const play = (id, title, media) => {
        restorePreviousCard();

        const originalChildren = [...media.childNodes];
        const iframe = document.createElement('iframe');
        iframe.src = `https://www.youtube-nocookie.com/embed/${id}?autoplay=1&playsinline=1&rel=0`;
        iframe.title = `YouTube player: ${title}`;
        iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share';
        iframe.allowFullscreen = true;
        iframe.referrerPolicy = 'strict-origin-when-cross-origin';
        media.replaceChildren(iframe);
        activePlayer = { media, originalChildren };
    };

    const showEmpty = (message, state) => {
        restorePreviousCard();
        list.replaceChildren();

        if (emptyMessage) {
            emptyMessage.textContent = message;
        } else if (state === 'empty') {
            empty.textContent = message;
        }

        empty.hidden = false;
        host.dataset.feedState = state;
    };

    host.dataset.feedState = 'loading';
    host.setAttribute('aria-busy', 'true');
    empty.hidden = true;

    async function load() {
        try {
            const response = await fetch(feedUrl, {
                headers: { Accept: 'application/json' },
                signal: controller.signal,
            });

            if (!response.ok) {
                throw new Error('Recent Shorts request failed');
            }

            const feed = await response.json();

            if (isDisposed) {
                return;
            }

            if (!feed?.available) {
                showEmpty("Live popular Shorts are unavailable right now. Browse these creators' latest Shorts on YouTube:", 'unavailable');
                return;
            }

            const items = Array.isArray(feed.items)
                ? feed.items.filter((item) => item && typeof item.id === 'string' && youtubeIdPattern.test(item.id))
                : [];

            if (items.length === 0) {
                showEmpty("No recent high-view Shorts were found. Browse these creators' latest Shorts on YouTube:", 'empty');
                return;
            }

            list.replaceChildren(...items.map((item) => makeCard(item, play, controller.signal)));
            empty.hidden = true;
            host.dataset.feedState = 'ready';
        } catch {
            if (!isDisposed) {
                showEmpty("Live popular Shorts are unavailable right now. Browse these creators' latest Shorts on YouTube:", 'unavailable');
            }
        } finally {
            if (!isDisposed) {
                host.removeAttribute('aria-busy');
            }
        }
    }

    load();

    return () => {
        isDisposed = true;
        controller.abort();
        restorePreviousCard();
        list.replaceChildren();
    };
}
