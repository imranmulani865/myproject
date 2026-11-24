let disposeHeroMotion;
let disposeClipCarousel;
let disposeRecentShorts;
let isDisposed = false;

async function startHeroMotion() {
    const motionHost = document.querySelector('[data-studio-motion]');

    if (!motionHost) {
        return;
    }

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches || navigator.connection?.saveData) {
        return;
    }

    try {
        const { mountHeroMotion } = await import('./hero-motion.js');

        if (!isDisposed) {
            disposeHeroMotion = mountHeroMotion(motionHost);
        }
    } catch (error) {
        console.warn('The animated background could not start.', error);
    }
}

async function startClipCarousel() {
    const carouselHost = document.querySelector('[data-clip-carousel]');

    if (!carouselHost) {
        return;
    }

    try {
        const { mountClipCarousel } = await import('./clip-carousel.js');

        if (!isDisposed) {
            disposeClipCarousel = mountClipCarousel(carouselHost);
        }
    } catch (error) {
        console.warn('The clip preview could not start.', error);
    }
}

async function startRecentShorts() {
    const feedHost = document.querySelector('[data-recent-shorts]');

    if (!feedHost) {
        return;
    }

    try {
        const { mountRecentShorts } = await import('./recent-shorts.js');

        if (!isDisposed) {
            disposeRecentShorts = mountRecentShorts(feedHost);
        }
    } catch (error) {
        console.warn('Recent Shorts could not load.', error);
    }
}

function startPageEffects() {
    startHeroMotion();
    startClipCarousel();
    startRecentShorts();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', startPageEffects, { once: true });
} else {
    startPageEffects();
}

if (import.meta.hot) {
    import.meta.hot.dispose(() => {
        isDisposed = true;
        disposeHeroMotion?.();
        disposeClipCarousel?.();
        disposeRecentShorts?.();
    });
}
