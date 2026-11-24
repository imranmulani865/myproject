function formatTime(seconds) {
    const value = Math.max(0, Math.floor(Number.isFinite(seconds) ? seconds : 0));
    return `${Math.floor(value / 60)}:${String(value % 60).padStart(2, '0')}`;
}

export function mountClipCarousel(host) {
    const slides = [...host.querySelectorAll('[data-carousel-slide]')];
    const hook = host.querySelector('[data-carousel-hook]');
    const label = host.querySelector('[data-carousel-label]');
    const time = host.querySelector('[data-carousel-time]');
    const hint = host.querySelector('[data-carousel-hint]');
    const count = host.querySelector('[data-carousel-count]');
    const wave = host.querySelector('.landing-workspace__wave');
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

    if (slides.length < 2 || !hook || !label || !time || !hint || !count || !wave) {
        return () => {};
    }

    let activeIndex = 1;
    let isVisible = true;
    let advanceTimer;
    let progressTimer;

    const activeVideo = () => slides[activeIndex].querySelector('video');

    const updateTimeline = () => {
        const video = activeVideo();
        const duration = Number.isFinite(video?.duration) && video.duration > 0 ? video.duration : 6;
        const currentTime = Number.isFinite(video?.currentTime) ? video.currentTime : 0;

        time.textContent = `${formatTime(currentTime)} / ${formatTime(duration)}`;
        wave.style.setProperty('--play-progress', `${Math.min(100, currentTime / duration * 100)}%`);
    };

    const stopPlayback = () => {
        window.clearTimeout(advanceTimer);
        window.clearInterval(progressTimer);
        slides.forEach((slide) => slide.querySelector('video')?.pause());
    };

    const canAutoplay = () => isVisible && !document.hidden && !reducedMotion.matches && !navigator.connection?.saveData;

    const startPlayback = () => {
        stopPlayback();

        if (!canAutoplay()) {
            return;
        }

        const video = activeVideo();
        if (video) {
            video.muted = true;
            video.play().catch(() => {});
        }

        progressTimer = window.setInterval(updateTimeline, 100);
        advanceTimer = window.setTimeout(() => activate((activeIndex + 1) % slides.length), 7000);
    };

    const activate = (index) => {
        activeIndex = index;
        host.dataset.activeSlide = String(index);

        slides.forEach((slide, slideIndex) => {
            const relative = (slideIndex - activeIndex + slides.length) % slides.length;
            slide.dataset.position = relative === 0 ? 'center' : relative === 1 ? 'right' : 'left';
            slide.setAttribute('aria-current', relative === 0 ? 'true' : 'false');
            slide.querySelector('video')?.pause();
        });

        const slide = slides[activeIndex];
        const video = activeVideo();
        if (video) {
            video.currentTime = 0;
        }

        label.textContent = slide.dataset.label || 'Short preview';
        hint.textContent = `Playing · ${slide.dataset.label || 'Short preview'}`;
        count.textContent = `${String(activeIndex + 1).padStart(2, '0')} / ${String(slides.length).padStart(2, '0')}`;
        hook.style.left = `${slide.dataset.hookStart || 0}%`;
        hook.style.width = `${slide.dataset.hookWidth || 25}%`;
        updateTimeline();
        startPlayback();
    };

    const clickHandlers = slides.map((slide, index) => {
        const onClick = () => activate(index);
        slide.addEventListener('click', onClick);
        return onClick;
    });

    const onPrevious = () => activate((activeIndex - 1 + slides.length) % slides.length);
    const onNext = () => activate((activeIndex + 1) % slides.length);
    host.querySelector('[data-carousel-prev]')?.addEventListener('click', onPrevious);
    host.querySelector('[data-carousel-next]')?.addEventListener('click', onNext);

    const onEnded = (event) => {
        if (event.currentTarget === activeVideo()) {
            activate((activeIndex + 1) % slides.length);
        }
    };
    const onMetadata = () => updateTimeline();
    slides.forEach((slide) => {
        slide.querySelector('video')?.addEventListener('ended', onEnded);
        slide.querySelector('video')?.addEventListener('loadedmetadata', onMetadata);
    });

    const observer = new IntersectionObserver(([entry]) => {
        isVisible = entry.isIntersecting;
        if (isVisible) {
            startPlayback();
        } else {
            stopPlayback();
        }
    }, { threshold: 0.1 });

    const onVisibilityChange = () => isVisible ? startPlayback() : stopPlayback();
    const onMotionChange = () => reducedMotion.matches ? stopPlayback() : startPlayback();
    observer.observe(host);
    document.addEventListener('visibilitychange', onVisibilityChange);
    reducedMotion.addEventListener('change', onMotionChange);
    activate(activeIndex);

    return () => {
        stopPlayback();
        observer.disconnect();
        document.removeEventListener('visibilitychange', onVisibilityChange);
        reducedMotion.removeEventListener('change', onMotionChange);
        slides.forEach((slide, index) => {
            slide.removeEventListener('click', clickHandlers[index]);
            slide.querySelector('video')?.removeEventListener('ended', onEnded);
            slide.querySelector('video')?.removeEventListener('loadedmetadata', onMetadata);
        });
        host.querySelector('[data-carousel-prev]')?.removeEventListener('click', onPrevious);
        host.querySelector('[data-carousel-next]')?.removeEventListener('click', onNext);
    };
}
