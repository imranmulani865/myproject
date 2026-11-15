@extends('layouts.app')

@section('content')
<style>
    .studio-landing {
        --studio-canvas: #f5f7ff;
        --studio-surface: #ffffff;
        --studio-ink: #151b45;
        --studio-muted: #5f6a87;
        --studio-line: #dce3f2;
        --studio-brand: #3028a6;
        --studio-brand-soft: #4a42c6;
        --studio-lavender: #dde4ff;
        --studio-peach: #ffe8db;
        --studio-lime: #c8ff42;
        color: var(--studio-ink);
    }

    .studio-hero-shell {
        position: relative;
        isolation: isolate;
        overflow: hidden;
        display: grid;
        grid-template-columns: minmax(0, 1.08fr) minmax(0, .92fr);
        align-items: center;
        min-height: 570px;
        gap: clamp(1rem, 3vw, 3rem);
        padding: clamp(2rem, 4vw, 4.5rem);
        border: 1px solid rgba(255, 255, 255, .92);
        border-radius: 2rem;
        background: radial-gradient(circle at 12% 40%, rgba(221, 228, 255, .82), transparent 38%), radial-gradient(circle at 95% 18%, rgba(255, 232, 219, .68), transparent 34%), rgba(255, 255, 255, .8);
        box-shadow: 0 34px 90px rgba(54, 65, 125, .08), inset 0 1px rgba(255, 255, 255, .94);
    }

    .studio-hero-shell::after {
        position: absolute;
        z-index: -1;
        inset: auto -7% -28% 22%;
        height: 45%;
        border-radius: 50%;
        background: rgba(221, 228, 255, .52);
        filter: blur(54px);
        content: '';
        pointer-events: none;
    }

    .studio-copy {
        position: relative;
        z-index: 5;
        max-width: 510px;
        justify-self: start;
    }

    .studio-kicker {
        display: inline-flex;
        align-items: center;
        gap: .55rem;
        margin: 0 0 1.15rem;
        color: var(--studio-brand);
        font-size: .85rem;
        font-weight: 700;
    }

    .studio-kicker i {
        font-size: .78rem;
    }

    .studio-copy h1 {
        margin: 0;
        color: var(--studio-ink);
        font-size: clamp(3.1rem, 4.55vw, 5.1rem);
        font-weight: 800;
        line-height: 1.01;
        letter-spacing: -.062em;
        text-wrap: balance;
    }

    .studio-description {
        max-width: 490px;
        margin: 1.35rem 0 0;
        color: var(--studio-muted);
        font-size: clamp(.99rem, 1.25vw, 1.12rem);
        line-height: 1.7;
    }

    .studio-entry {
        display: flex;
        align-items: center;
        gap: .7rem;
        max-width: 490px;
        margin-top: 2rem;
        padding: .42rem .45rem .42rem .95rem;
        border: 1px solid #c9d0eb;
        border-radius: 1.1rem;
        background: rgba(255, 255, 255, .88);
        box-shadow: 0 11px 26px rgba(54, 65, 125, .1);
    }

    .studio-entry-icon {
        flex: 0 0 auto;
        color: #dc2626;
        font-size: 1.17rem;
    }

    .studio-entry-copy {
        flex: 1 1 auto;
        min-width: 0;
        color: #6a7391;
        font-size: .83rem;
        line-height: 1.3;
    }

    .studio-entry-action,
    .studio-final-action {
        display: inline-flex;
        min-height: 43px;
        flex: 0 0 auto;
        align-items: center;
        justify-content: center;
        gap: .55rem;
        padding: .68rem 1rem;
        border-radius: .78rem;
        color: #fff;
        background: var(--studio-brand);
        box-shadow: 0 8px 18px rgba(48, 40, 166, .22);
        font-size: .83rem;
        font-weight: 700;
        transition: background-color .2s ease, transform .2s ease, box-shadow .2s ease;
    }

    .studio-entry-action:hover,
    .studio-final-action:hover {
        background: var(--studio-brand-soft);
        box-shadow: 0 10px 23px rgba(48, 40, 166, .28);
        transform: translateY(-2px);
    }

    .studio-entry-action:focus-visible,
    .studio-final-action:focus-visible,
    .studio-text-link:focus-visible,
    .studio-faq summary:focus-visible {
        outline: 2px solid var(--studio-brand);
        outline-offset: 4px;
    }

    .studio-entry-note {
        margin-top: .85rem;
        color: var(--studio-muted);
        font-size: .78rem;
    }

    .studio-text-link {
        color: var(--studio-brand);
        font-weight: 700;
        text-decoration: underline;
        text-underline-offset: 3px;
    }

    .studio-scroll-cue {
        display: inline-flex;
        align-items: center;
        gap: .55rem;
        margin-top: 1.15rem;
        color: var(--studio-brand);
        font-size: .85rem;
        font-weight: 750;
        text-decoration: underline;
        text-underline-offset: 4px;
    }

    .studio-scroll-cue:focus-visible {
        outline: 2px solid var(--studio-brand);
        outline-offset: 4px;
    }

    .studio-stage {
        position: relative;
        width: 100%;
        min-height: 460px;
        perspective: 1100px;
        transform-style: preserve-3d;
    }

    .studio-stage::before,
    .studio-stage::after {
        position: absolute;
        border-radius: 50%;
        content: '';
        pointer-events: none;
    }

    .studio-stage::before {
        inset: 0 18% 21% -8%;
        background: rgba(184, 196, 255, .48);
        filter: blur(45px);
    }

    .studio-stage::after {
        inset: 35% -11% 5% 39%;
        background: rgba(255, 200, 173, .44);
        filter: blur(45px);
    }

    .studio-video-card,
    .studio-wave-card {
        position: absolute;
        overflow: hidden;
        border: 1px solid rgba(220, 227, 242, .98);
        background: #fff;
        box-shadow: 0 24px 45px rgba(41, 47, 103, .19), 0 5px 12px rgba(41, 47, 103, .1), inset 0 1px rgba(255, 255, 255, .95);
        transform-style: preserve-3d;
        transition: transform .35s ease, box-shadow .35s ease;
    }

    .studio-source {
        z-index: 2;
        top: 27%;
        left: 4%;
        width: 66%;
        border-radius: 17px;
        transform: rotateY(12deg) rotateZ(-7deg) translateZ(15px);
    }

    .studio-short-main {
        z-index: 4;
        top: 5%;
        right: 7%;
        width: 33%;
        border-radius: 18px;
        transform: rotateY(-12deg) rotateZ(7deg) translateZ(75px);
    }

    .studio-short-second {
        z-index: 3;
        top: 2%;
        left: 1%;
        width: 24%;
        border-radius: 15px;
        transform: rotateY(15deg) rotateZ(-12deg) translateZ(45px);
    }

    .studio-bubble {
        position: absolute;
        z-index: 2;
        width: 30%;
        aspect-ratio: 1;
        border: 1px solid rgba(255, 255, 255, .8);
        border-radius: 50%;
        background: radial-gradient(circle at 30% 25%, rgba(255, 255, 255, .7), rgba(187, 209, 255, .38) 54%, rgba(113, 145, 226, .08) 72%);
        box-shadow: inset 0 0 22px rgba(255, 255, 255, .7), 0 0 30px rgba(127, 157, 234, .22);
        opacity: 0;
        pointer-events: none;
    }

    .studio-bubble-main {
        top: 9%;
        right: 7%;
        width: 34%;
    }

    .studio-bubble-second {
        top: 4%;
        left: 1%;
        width: 26%;
    }

    .studio-stage:hover .studio-source {
        transform: rotateY(9deg) rotateZ(-5deg) translateZ(20px);
    }

    .studio-stage:hover .studio-short-main {
        transform: rotateY(-8deg) rotateZ(5deg) translateZ(86px);
    }

    .studio-stage:hover .studio-short-second {
        transform: rotateY(12deg) rotateZ(-10deg) translateZ(53px);
    }

    .studio-stage.is-scroll-active .studio-video-card,
    .studio-stage.is-scroll-active .studio-wave-card {
        transition: none;
        will-change: transform;
    }

    .studio-stage.is-scroll-active .studio-short-main,
    .studio-stage.is-scroll-active .studio-short-second,
    .studio-stage.is-scroll-active .studio-bubble {
        will-change: transform, opacity;
    }

    .studio-stage.is-scroll-active .studio-crop-guide {
        will-change: opacity;
    }

    .studio-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .3rem;
        min-height: 34px;
        padding: .42rem .65rem;
        color: var(--studio-ink);
        font-size: .63rem;
        font-weight: 750;
    }

    .studio-card-head span:last-child {
        color: var(--studio-muted);
        font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
        font-size: .56rem;
        font-weight: 600;
    }

    .studio-scene {
        position: relative;
        overflow: hidden;
        background: linear-gradient(160deg, #6b8fbd 0%, #9db5d7 38%, #f0c9b2 72%, #e6bba5 100%);
    }

    .studio-source .studio-scene {
        aspect-ratio: 16 / 9;
    }

    .studio-short-main .studio-scene,
    .studio-short-second .studio-scene {
        aspect-ratio: 9 / 13.2;
    }

    .studio-scene::before,
    .studio-scene::after {
        position: absolute;
        right: -7%;
        bottom: -1%;
        left: -7%;
        content: '';
    }

    .studio-scene::before {
        height: 58%;
        background: #566c91;
        clip-path: polygon(0 72%, 16% 43%, 35% 71%, 53% 20%, 71% 59%, 86% 31%, 100% 70%, 100% 100%, 0 100%);
    }

    .studio-scene::after {
        height: 34%;
        background: linear-gradient(180deg, #394d73, #26385e);
        clip-path: polygon(0 66%, 23% 34%, 40% 63%, 60% 28%, 80% 60%, 100% 20%, 100% 100%, 0 100%);
    }

    .studio-scene-sun {
        position: absolute;
        top: 17%;
        left: 69%;
        width: 15%;
        aspect-ratio: 1;
        border-radius: 50%;
        background: #ffe6c7;
        box-shadow: 0 0 26px 8px rgba(255, 230, 199, .47);
    }

    .studio-photo-scene::before,
    .studio-photo-scene::after {
        content: none;
    }

    .studio-photo-scene img {
        position: absolute;
        inset: 0;
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center 12%;
    }

    .studio-source .studio-scene::selection {
        background: transparent;
    }

    .studio-crop-guide {
        position: absolute;
        z-index: 2;
        top: 6%;
        bottom: 6%;
        left: 61%;
        width: 22%;
        border: 2px solid rgba(255, 255, 255, .88);
        border-radius: 7px;
        box-shadow: 0 0 0 100vmax rgba(19, 26, 69, .1);
    }

    .studio-play {
        position: absolute;
        z-index: 3;
        top: 50%;
        left: 50%;
        display: grid;
        width: 38px;
        height: 38px;
        place-items: center;
        border: 1px solid rgba(255, 255, 255, .8);
        border-radius: 50%;
        color: #fff;
        background: rgba(21, 27, 69, .43);
        backdrop-filter: blur(5px);
        transform: translate(-50%, -50%);
    }

    .studio-play i {
        margin-left: 2px;
        font-size: .68rem;
    }

    .studio-source-foot {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: .5rem .65rem;
        color: #5f6a87;
        font-size: .59rem;
        font-weight: 650;
    }

    .studio-source-foot span:last-child {
        font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
    }

    .studio-short-main .studio-scene,
    .studio-short-second .studio-scene {
        background-position: 56% center;
    }

    .studio-short-main .studio-scene::before,
    .studio-short-main .studio-scene::after,
    .studio-short-second .studio-scene::before,
    .studio-short-second .studio-scene::after {
        left: -45%;
        width: 190%;
    }

    .studio-short-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .35rem;
        min-height: 32px;
        padding: .4rem .55rem;
        color: var(--studio-ink);
        font-size: .58rem;
        font-weight: 750;
    }

    .studio-short-footer i {
        color: #4a42c6;
    }

    .studio-lime-badge {
        position: absolute;
        z-index: 4;
        top: 26%;
        right: 5%;
        padding: .3rem .48rem;
        border: 1px solid rgba(21, 27, 69, .08);
        border-radius: .5rem;
        color: var(--studio-ink);
        background: var(--studio-lime);
        box-shadow: 0 7px 16px rgba(37, 49, 86, .16);
        font-size: .58rem;
        font-weight: 800;
        transform: rotate(5deg);
        white-space: nowrap;
    }

    .studio-short-second .studio-lime-badge {
        top: 30%;
        right: 5%;
        font-size: .5rem;
    }

    .studio-wave-card {
        z-index: 5;
        right: 11%;
        bottom: 1%;
        left: 11%;
        display: flex;
        min-height: 76px;
        align-items: center;
        gap: .75rem;
        padding: .75rem .9rem;
        border-radius: 14px;
        transform: rotateZ(-3deg) translateZ(95px);
    }

    .studio-wave-label {
        flex: 0 0 108px;
        color: var(--studio-ink);
        font-size: .67rem;
        font-weight: 800;
        line-height: 1.25;
    }

    .studio-wave-label small {
        display: block;
        margin-top: .2rem;
        color: var(--studio-muted);
        font-size: .56rem;
        font-weight: 550;
    }

    .studio-wave-bars {
        display: flex;
        min-width: 0;
        width: 100%;
        height: 36px;
        align-items: center;
        gap: 2px;
        overflow: hidden;
    }

    .studio-wave-bars span {
        flex: 1 1 2px;
        max-width: 5px;
        height: var(--bar-height);
        border-radius: 3px;
        background: #8987df;
    }

    .studio-wave-bars span:nth-child(n + 9):nth-child(-n + 14) {
        background: #2c2bb0;
    }

    .studio-section {
        padding: clamp(4rem, 7vw, 6rem) .3rem 0;
    }

    .studio-section-heading {
        max-width: 750px;
        margin-bottom: 2rem;
    }

    .studio-section-heading p {
        margin: 0 0 .55rem;
        color: var(--studio-brand);
        font-size: .85rem;
        font-weight: 750;
    }

    .studio-section-heading h2 {
        margin: 0;
        color: var(--studio-ink);
        font-size: clamp(2rem, 3.1vw, 3rem);
        font-weight: 780;
        line-height: 1.12;
        letter-spacing: -.045em;
        text-wrap: balance;
    }

    .studio-section-heading > span {
        display: block;
        max-width: 650px;
        margin-top: .85rem;
        color: var(--studio-muted);
        font-size: 1rem;
        line-height: 1.65;
    }

    .studio-creators {
        padding: clamp(4rem, 7vw, 6rem) .3rem 0;
    }

    .studio-creators .studio-section-heading {
        max-width: 850px;
    }

    .studio-creator-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1rem;
    }

    .studio-creator-card {
        min-width: 0;
        overflow: hidden;
        border: 1px solid var(--studio-line);
        border-radius: 1.15rem;
        background: #fff;
        box-shadow: 0 20px 50px rgba(54, 65, 125, .09);
    }

    .studio-creator-photo {
        position: relative;
        overflow: hidden;
        aspect-ratio: 4 / 3;
        background: var(--studio-lavender);
    }

    .studio-creator-photo img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center 4%;
    }

    .studio-creator-format {
        position: absolute;
        bottom: .85rem;
        left: .85rem;
        padding: .38rem .62rem;
        border: 1px solid rgba(255, 255, 255, .75);
        border-radius: .5rem;
        color: var(--studio-ink);
        background: rgba(255, 255, 255, .92);
        box-shadow: 0 4px 14px rgba(21, 27, 69, .12);
        font-size: .73rem;
        font-weight: 750;
    }

    .studio-creator-detail {
        display: flex;
        min-height: 92px;
        flex-direction: column;
        justify-content: center;
        padding: 1rem 1.15rem;
    }

    .studio-creator-detail h3 {
        margin: 0;
        color: var(--studio-ink);
        font-size: 1.1rem;
        font-weight: 800;
    }

    .studio-creator-detail p {
        margin: .18rem 0 0;
        color: var(--studio-muted);
        font-size: .85rem;
        line-height: 1.5;
    }

    .studio-creator-disclosure,
    .studio-creator-credits {
        color: var(--studio-muted);
        font-size: .78rem;
        line-height: 1.65;
    }

    .studio-creator-disclosure {
        margin: 1rem 0 .25rem;
        font-weight: 650;
    }

    .studio-creator-credits {
        margin: 0;
    }

    .studio-creator-credits a {
        color: var(--studio-brand);
        text-decoration: underline;
        text-underline-offset: 2px;
    }

    .studio-creator-credits a:focus-visible {
        outline: 2px solid var(--studio-brand);
        outline-offset: 3px;
    }

    .studio-showcase {
        padding-top: clamp(4rem, 7vw, 6rem);
        scroll-margin-top: 80px;
    }

    .studio-showcase-heading {
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 1.5rem;
        padding: 0 .3rem;
        margin-bottom: 1.5rem;
    }

    .studio-showcase-heading .studio-section-heading {
        margin-bottom: 0;
    }

    .studio-showcase-preview {
        position: absolute;
        z-index: 9;
        top: 1rem;
        right: 1rem;
        display: inline-flex;
        min-height: 44px;
        flex: 0 0 auto;
        align-items: center;
        justify-content: center;
        gap: .55rem;
        padding: .65rem 1rem;
        border: 1px solid #c9c8ef;
        border-radius: .8rem;
        color: var(--studio-brand);
        background: #fff;
        box-shadow: 0 8px 20px rgba(48, 40, 166, .08);
        cursor: pointer;
        font-size: .82rem;
        font-weight: 750;
    }

    .studio-showcase-preview[hidden] {
        display: none;
    }

    .studio-showcase-preview:focus-visible {
        outline: 2px solid var(--studio-brand);
        outline-offset: 3px;
    }

    .studio-showcase-track {
        position: relative;
        min-height: 1250px;
        height: 220vh;
    }

    .studio-showcase-scene {
        position: sticky;
        top: 72px;
        isolation: isolate;
        overflow: hidden;
        height: calc(100svh - 72px);
        min-height: 570px;
        border: 1px solid #e2e5fa;
        border-radius: 1.65rem;
        background: radial-gradient(circle at 50% 80%, rgba(203, 214, 255, .86), transparent 38%), radial-gradient(circle at 86% 26%, rgba(255, 226, 207, .83), transparent 42%), linear-gradient(140deg, #f8faff, #eef1ff 55%, #fff8f3);
        box-shadow: 0 30px 75px rgba(60, 67, 125, .09);
    }

    .studio-showcase-scene::before {
        position: absolute;
        top: 12%;
        left: 32%;
        width: 36%;
        aspect-ratio: 1;
        border: 1px solid rgba(255, 255, 255, .85);
        border-radius: 50%;
        background: rgba(255, 255, 255, .24);
        content: '';
        pointer-events: none;
    }

    .studio-showcase-caption {
        position: absolute;
        z-index: 7;
        top: 1.35rem;
        left: 1.55rem;
        display: grid;
        gap: .2rem;
        color: var(--studio-brand);
        font-size: .8rem;
        font-weight: 800;
    }

    .studio-showcase-caption small {
        color: var(--studio-muted);
        font-size: .73rem;
        font-weight: 600;
    }

    .studio-showcase-stream {
        position: absolute;
        z-index: 1;
        bottom: 19%;
        left: 50%;
        width: 2px;
        height: 24%;
        background: linear-gradient(transparent, rgba(82, 79, 195, .45));
        transform: translateX(-50%);
    }

    .studio-showcase-orbit {
        position: absolute;
        z-index: 2;
        top: 19%;
        width: 21%;
        max-width: 235px;
        aspect-ratio: 1;
        border: 1px solid rgba(255, 255, 255, .9);
        border-radius: 50%;
        background: radial-gradient(circle at 30% 27%, rgba(255, 255, 255, .9), rgba(181, 200, 255, .58) 50%, rgba(105, 125, 221, .08) 75%);
        box-shadow: inset 0 0 35px rgba(255, 255, 255, .8), 0 0 50px rgba(89, 113, 225, .2);
        opacity: .35;
        pointer-events: none;
    }

    .studio-showcase-orbit--one { left: 10%; }
    .studio-showcase-orbit--two { left: 39.5%; }
    .studio-showcase-orbit--three { right: 10%; }

    .studio-showcase-card {
        position: absolute;
        z-index: 3;
        top: 18%;
        overflow: hidden;
        width: 19%;
        max-width: 220px;
        border: 1px solid #d9dff3;
        border-radius: 1rem;
        background: #fff;
        box-shadow: 0 25px 55px rgba(36, 45, 111, .23), 0 7px 15px rgba(36, 45, 111, .08);
        transform-origin: center center;
    }

    .studio-showcase-card--one { left: 11%; transform: rotate(-7deg); }
    .studio-showcase-card--two { left: 40.5%; z-index: 4; transform: rotate(2deg); }
    .studio-showcase-card--three { right: 11%; z-index: 5; transform: rotate(7deg); }

    .studio-showcase-card-head,
    .studio-showcase-card-foot {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .35rem;
        min-height: 36px;
        padding: .5rem .65rem;
        color: var(--studio-ink);
        font-size: .7rem;
        font-weight: 800;
    }

    .studio-showcase-card-head span:last-child,
    .studio-showcase-card-foot span:last-child {
        color: var(--studio-muted);
        font-size: .62rem;
        font-weight: 650;
    }

    .studio-showcase-card .studio-scene {
        aspect-ratio: 9 / 11;
    }

    .studio-showcase-card--two .studio-photo-scene img {
        object-position: center 8%;
    }

    .studio-showcase-card-foot i {
        color: var(--studio-brand);
    }

    .studio-showcase-source {
        position: absolute;
        z-index: 6;
        bottom: 7%;
        left: 50%;
        display: flex;
        width: min(380px, 70%);
        min-height: 112px;
        align-items: center;
        gap: 1rem;
        padding: .65rem .95rem .65rem .65rem;
        border: 1px solid #d9dff3;
        border-radius: 1.1rem;
        background: rgba(255, 255, 255, .96);
        box-shadow: 0 18px 40px rgba(36, 45, 111, .15);
        transform: translateX(-50%);
    }

    .studio-showcase-source-art {
        position: relative;
        display: flex;
        width: 43%;
        height: 88px;
        flex: 0 0 43%;
        overflow: hidden;
        border-radius: .7rem;
        background: var(--studio-lavender);
    }

    .studio-showcase-source-art img {
        display: block;
        width: 33.333%;
        height: 100%;
        object-fit: cover;
        object-position: center 12%;
    }

    .studio-showcase-source strong,
    .studio-showcase-source small {
        display: block;
    }

    .studio-showcase-source strong {
        color: var(--studio-ink);
        font-size: .95rem;
    }

    .studio-showcase-source small {
        margin-top: .3rem;
        color: var(--studio-muted);
        font-size: .7rem;
        line-height: 1.35;
    }

    .studio-showcase.is-motion-active .studio-showcase-card,
    .studio-showcase.is-motion-active .studio-showcase-orbit {
        will-change: transform, opacity;
    }

    .studio-steps {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1rem;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .studio-steps li {
        min-width: 0;
        padding: 1.5rem;
        border: 1px solid var(--studio-line);
        border-radius: 1rem;
        background: rgba(255, 255, 255, .78);
    }

    .studio-step-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.65rem;
        color: var(--studio-brand);
        font-size: .77rem;
        font-weight: 750;
    }

    .studio-step-top i {
        display: grid;
        width: 35px;
        height: 35px;
        place-items: center;
        border-radius: .6rem;
        color: var(--studio-brand);
        background: #eaeaff;
    }

    .studio-steps h3,
    .studio-use-cases h3 {
        margin: 0 0 .45rem;
        color: var(--studio-ink);
        font-size: 1.08rem;
        font-weight: 750;
    }

    .studio-steps p,
    .studio-use-cases p {
        margin: 0;
        color: var(--studio-muted);
        font-size: .92rem;
        line-height: 1.65;
    }

    .studio-use-cases {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0 2.5rem;
        border-top: 1px solid var(--studio-line);
    }

    .studio-use-cases article {
        display: flex;
        gap: 1rem;
        padding: 1.4rem 0;
        border-bottom: 1px solid var(--studio-line);
    }

    .studio-use-cases i {
        display: grid;
        flex: 0 0 42px;
        height: 42px;
        place-items: center;
        border-radius: .7rem;
        color: var(--studio-brand);
        background: #eaeaff;
    }

    .studio-faq {
        display: grid;
        grid-template-columns: minmax(0, .7fr) minmax(0, 1.3fr);
        gap: clamp(1.5rem, 4vw, 4rem);
        align-items: start;
    }

    .studio-faq .studio-section-heading {
        margin-bottom: 0;
    }

    .studio-faq-list {
        border-top: 1px solid var(--studio-line);
    }

    .studio-faq details {
        border-bottom: 1px solid var(--studio-line);
    }

    .studio-faq summary {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1.2rem .2rem;
        color: var(--studio-ink);
        cursor: pointer;
        font-size: .97rem;
        font-weight: 730;
        list-style: none;
    }

    .studio-faq summary::-webkit-details-marker {
        display: none;
    }

    .studio-faq summary::after {
        content: '+';
        color: var(--studio-brand);
        font-size: 1.4rem;
        font-weight: 400;
        line-height: 1;
    }

    .studio-faq details[open] summary::after {
        content: '−';
    }

    .studio-faq details p {
        max-width: 650px;
        margin: -.2rem 0 1.25rem;
        padding-right: 1.5rem;
        color: var(--studio-muted);
        font-size: .91rem;
        line-height: 1.7;
    }

    .studio-final {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.5rem;
        margin: clamp(4rem, 7vw, 6rem) 0 2rem;
        padding: clamp(1.6rem, 3vw, 2.6rem);
        border: 1px solid #deddf7;
        border-radius: 1.3rem;
        background: linear-gradient(110deg, #e8ebff, #fff4ed);
    }

    .studio-final h2 {
        max-width: 590px;
        margin: 0;
        font-size: clamp(1.4rem, 2vw, 2rem);
        font-weight: 780;
        line-height: 1.2;
        letter-spacing: -.035em;
    }

    .studio-final p {
        margin: .5rem 0 0;
        color: var(--studio-muted);
        font-size: .89rem;
    }

    @media (max-width: 1000px) {
        .studio-hero-shell {
            grid-template-columns: 1fr;
        }

        .studio-copy {
            grid-row: 1;
            max-width: 730px;
        }

        .studio-stage {
            grid-row: 2;
            width: min(100%, 650px);
            justify-self: center;
        }

        .studio-showcase-card {
            width: 24%;
        }

        .studio-showcase-card--one { left: 6%; }
        .studio-showcase-card--two { left: 38%; }
        .studio-showcase-card--three { right: 6%; }

        .studio-showcase-orbit {
            width: 26%;
        }

        .studio-showcase-orbit--one { left: 5%; }
        .studio-showcase-orbit--two { left: 37%; }
        .studio-showcase-orbit--three { right: 5%; }
    }

    @media (min-width: 641px) and (max-width: 800px) {
        .studio-creator-grid {
            grid-template-columns: 1fr;
        }

        .studio-creator-card {
            display: grid;
            height: 220px;
            grid-template-columns: minmax(0, 38%) minmax(0, 1fr);
            grid-template-rows: minmax(0, 1fr);
        }

        .studio-creator-photo {
            min-height: 0;
            height: 100%;
            aspect-ratio: auto;
        }

        .studio-creator-photo img {
            min-height: 0;
        }

        .studio-creator-detail {
            min-height: 0;
        }
    }

    @media (max-width: 640px) {
        .studio-creator-grid {
            grid-template-columns: 1fr;
        }

        .studio-hero-shell {
            min-height: 0;
            padding: 1.5rem 1.1rem 1rem;
            border-radius: 1.3rem;
        }

        .studio-copy h1 {
            font-size: clamp(2.65rem, 11vw, 4rem);
        }

        .studio-entry {
            flex-wrap: wrap;
            padding: .8rem;
        }

        .studio-entry-copy {
            flex-basis: calc(100% - 2rem);
        }

        .studio-entry-action {
            width: 100%;
        }

        .studio-stage {
            min-height: 330px;
        }

        .studio-showcase-heading {
            align-items: start;
            flex-direction: column;
        }

        .studio-showcase-track {
            height: 205vh;
            min-height: 1350px;
        }

        .studio-showcase-scene {
            top: 62px;
            height: calc(100svh - 62px);
            min-height: 570px;
            border-radius: 1.15rem;
        }

        .studio-showcase-caption {
            top: 1rem;
            left: 1rem;
            max-width: calc(100% - 5rem);
            font-size: .72rem;
        }

        .studio-showcase-preview {
            top: .75rem;
            right: .75rem;
            width: 44px;
            padding: .55rem;
        }

        .studio-showcase-preview span {
            display: none;
        }

        .studio-showcase-caption small {
            font-size: .64rem;
        }

        .studio-showcase-card {
            top: 24%;
            width: 36%;
            border-radius: .75rem;
        }

        .studio-showcase-card--one { left: 9%; }
        .studio-showcase-card--two { top: 30%; left: 32%; }
        .studio-showcase-card--three { right: 9%; }

        .studio-showcase-card-head,
        .studio-showcase-card-foot {
            min-height: 28px;
            padding: .35rem .4rem;
            font-size: .52rem;
        }

        .studio-showcase-card-head span:last-child,
        .studio-showcase-card-foot span:last-child {
            font-size: .46rem;
        }

        .studio-showcase-card .studio-scene {
            aspect-ratio: 9 / 10.5;
        }

        .studio-showcase-orbit {
            top: 27%;
            width: 39%;
        }

        .studio-showcase-orbit--one { left: 7.5%; }
        .studio-showcase-orbit--two { top: 33%; left: 30.5%; }
        .studio-showcase-orbit--three { right: 7.5%; }

        .studio-showcase-source {
            bottom: 9%;
            width: calc(100% - 2rem);
            min-height: 96px;
        }

        .studio-showcase-source-art {
            height: 72px;
        }

        .studio-showcase-stream {
            bottom: 19%;
            height: 20%;
        }

        .studio-source {
            top: 28%;
            width: 69%;
        }

        .studio-short-main {
            top: 8%;
            right: 1%;
            width: 38%;
        }

        .studio-short-second {
            top: 7%;
            width: 27%;
        }

        .studio-card-head,
        .studio-short-footer {
            min-height: 26px;
            padding: .32rem .4rem;
            font-size: .48rem;
        }

        .studio-card-head span:last-child {
            font-size: .46rem;
        }

        .studio-source-foot {
            padding: .36rem .4rem;
            font-size: .49rem;
        }

        .studio-wave-card {
            right: 2%;
            left: 2%;
            min-height: 58px;
            padding: .5rem .6rem;
        }

        .studio-wave-label {
            flex-basis: 84px;
            font-size: .54rem;
        }

        .studio-wave-label small {
            font-size: .46rem;
        }

        .studio-steps,
        .studio-use-cases,
        .studio-faq {
            grid-template-columns: 1fr;
        }

        .studio-final {
            align-items: stretch;
            flex-direction: column;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .studio-bubble {
            display: none;
        }

        .studio-showcase-preview {
            transition: none;
        }

        .studio-video-card,
        .studio-wave-card,
        .studio-entry-action,
        .studio-final-action {
            transition: none;
        }

        .studio-stage:hover .studio-source {
            transform: rotateY(12deg) rotateZ(-7deg) translateZ(15px);
        }

        .studio-stage:hover .studio-short-main {
            transform: rotateY(-12deg) rotateZ(7deg) translateZ(75px);
        }

        .studio-stage:hover .studio-short-second {
            transform: rotateY(15deg) rotateZ(-12deg) translateZ(45px);
        }

        .studio-entry-action:hover,
        .studio-final-action:hover {
            transform: none;
        }
    }
</style>

<div class="studio-landing">
    <section class="studio-hero-shell" aria-labelledby="studio-title">
        <div class="studio-stage" aria-hidden="true">
            <div class="studio-video-card studio-source">
                <div class="studio-card-head"><span>Source video</span><span>16:9</span></div>
                <div class="studio-scene studio-photo-scene">
                    <img src="{{ asset('creator-mrbeast.webp') }}" alt="" width="960" height="1440" fetchpriority="high" decoding="async">
                    <span class="studio-crop-guide"></span>
                    <span class="studio-play"><i class="fa-solid fa-play"></i></span>
                </div>
                <div class="studio-source-foot"><span>Full-length footage</span><span>18:42</span></div>
            </div>

            <span class="studio-bubble studio-bubble-second"></span>
            <span class="studio-bubble studio-bubble-main"></span>

            <div class="studio-video-card studio-short-second">
                <div class="studio-card-head"><span>Short 02</span><span>9:16</span></div>
                <div class="studio-scene studio-photo-scene"><img src="{{ asset('creator-mrbeast.webp') }}" alt="" width="960" height="1440" decoding="async"></div>
                <div class="studio-short-footer"><span>Vertical clip</span><i class="fa-solid fa-check"></i></div>
                <span class="studio-lime-badge">Hook found</span>
            </div>

            <div class="studio-video-card studio-short-main">
                <div class="studio-card-head"><span>Short 01</span><span>9:16</span></div>
                <div class="studio-scene studio-photo-scene"><img src="{{ asset('creator-mrbeast.webp') }}" alt="" width="960" height="1440" decoding="async"></div>
                <div class="studio-short-footer"><span>Ready to review</span><i class="fa-solid fa-check"></i></div>
                <span class="studio-lime-badge">30s / 60s</span>
            </div>

            <div class="studio-wave-card">
                <div class="studio-wave-label">Hook analysis<small>Strong moments found</small></div>
                <div class="studio-wave-bars">
                    <span style="--bar-height: 28%"></span><span style="--bar-height: 53%"></span>
                    <span style="--bar-height: 41%"></span><span style="--bar-height: 73%"></span>
                    <span style="--bar-height: 47%"></span><span style="--bar-height: 83%"></span>
                    <span style="--bar-height: 60%"></span><span style="--bar-height: 35%"></span>
                    <span style="--bar-height: 67%"></span><span style="--bar-height: 95%"></span>
                    <span style="--bar-height: 68%"></span><span style="--bar-height: 88%"></span>
                    <span style="--bar-height: 77%"></span><span style="--bar-height: 55%"></span>
                    <span style="--bar-height: 85%"></span><span style="--bar-height: 52%"></span>
                    <span style="--bar-height: 31%"></span><span style="--bar-height: 64%"></span>
                    <span style="--bar-height: 45%"></span><span style="--bar-height: 72%"></span>
                    <span style="--bar-height: 38%"></span>
                </div>
            </div>
        </div>

        <div class="studio-copy">
            <p class="studio-kicker"><i class="fa-solid fa-sparkles" aria-hidden="true"></i> Your video, reimagined for short-form</p>
            <h1 id="studio-title">Turn long videos into standout shorts.</h1>
            <p class="studio-description">
                Start with a public YouTube link or your own video. Gemma finds promising moments, creates vertical clips, and suggests a title, description, and tags for each short.
            </p>
            <div class="studio-entry">
                <i class="fa-brands fa-youtube studio-entry-icon" aria-hidden="true"></i>
                <span class="studio-entry-copy">Add your YouTube link after signing up</span>
                <a href="{{ route('register') }}" class="studio-entry-action">Get started <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
            </div>
            <p class="studio-entry-note">Prefer an upload? Add an MP4, MOV, MKV, or WebM file in your project. Already have an account? <a href="{{ route('login') }}" class="studio-text-link">Sign in</a>.</p>
            <a href="#shorts-preview" class="studio-scroll-cue">Scroll to see the shorts <i class="fa-solid fa-arrow-down" aria-hidden="true"></i></a>
            <p class="studio-entry-note">Creator portraits are editorial visual examples, not clips made with this product.</p>
        </div>
    </section>

    <section id="creator-inspiration" class="studio-creators" aria-labelledby="creator-inspiration-title">
        <div class="studio-section-heading">
            <p>Creator inspiration</p>
            <h2 id="creator-inspiration-title">Real faces behind different kinds of videos.</h2>
            <span>Entertainment, podcasts, and vlogs each have moments people remember. Explore the formats, then make shorts from your own footage.</span>
        </div>
        <div class="studio-creator-grid">
            <article class="studio-creator-card studio-creator-card--mrbeast">
                <div class="studio-creator-photo">
                    <img src="{{ asset('creator-mrbeast.webp') }}" alt="Portrait of MrBeast" width="960" height="1440" loading="lazy" decoding="async">
                    <span class="studio-creator-format">Entertainment</span>
                </div>
                <div class="studio-creator-detail"><h3>MrBeast</h3><p>Big ideas and memorable hooks.</p></div>
            </article>
            <article class="studio-creator-card studio-creator-card--raj">
                <div class="studio-creator-photo">
                    <img src="{{ asset('creator-raj-shamani.webp') }}" alt="Portrait of Raj Shamani" width="274" height="395" loading="lazy" decoding="async">
                    <span class="studio-creator-format">Podcasts</span>
                </div>
                <div class="studio-creator-detail"><h3>Raj Shamani</h3><p>Conversations with moments to revisit.</p></div>
            </article>
            <article class="studio-creator-card studio-creator-card--casey">
                <div class="studio-creator-photo">
                    <img src="{{ asset('creator-casey-neistat.webp') }}" alt="Portrait of Casey Neistat" width="960" height="1200" loading="lazy" decoding="async">
                    <span class="studio-creator-format">Vlogs</span>
                </div>
                <div class="studio-creator-detail"><h3>Casey Neistat</h3><p>Everyday footage with a strong point of view.</p></div>
            </article>
        </div>
        <p class="studio-creator-disclosure">Editorial examples only. These creators are not affiliated with or endorsing this product.</p>
        <p class="studio-creator-credits">Photos cropped for display and converted to WebP: <a href="https://commons.wikimedia.org/wiki/File:MrBeast_2023.jpg" target="_blank" rel="noopener noreferrer">MrBeast by Steven Khan</a> (<a href="https://creativecommons.org/licenses/by/4.0/" target="_blank" rel="noopener noreferrer">CC BY 4.0</a>); <a href="https://commons.wikimedia.org/wiki/File:Raj_Shamani.jpg" target="_blank" rel="noopener noreferrer">Raj Shamani by JeevanPant</a> (<a href="https://creativecommons.org/publicdomain/zero/1.0/" target="_blank" rel="noopener noreferrer">CC0</a>); <a href="https://commons.wikimedia.org/wiki/File:Casey_Neistat.jpg" target="_blank" rel="noopener noreferrer">Casey Neistat by Tessa Bury</a> (<a href="https://creativecommons.org/licenses/by/4.0/" target="_blank" rel="noopener noreferrer">CC BY 4.0</a>).</p>
    </section>

    <section id="shorts-preview" class="studio-showcase" aria-labelledby="shorts-preview-title">
        <div class="studio-showcase-heading">
            <div class="studio-section-heading">
                <p>Three creator formats</p>
                <h2 id="shorts-preview-title">Watch the short cards emerge.</h2>
                <span>Scroll to reveal portraits from entertainment, podcasts, and vlogs. These editorial examples show formats, not clips made with this product.</span>
            </div>
        </div>
        <div class="studio-showcase-track">
            <div class="studio-showcase-scene">
                <div class="studio-showcase-caption" aria-hidden="true"><span>Three formats, three creators</span><small>Keep scrolling to reveal each card</small></div>
                <button type="button" class="studio-showcase-preview" aria-label="Pause scroll animation" title="Pause scroll animation" hidden><i class="fa-solid fa-pause" aria-hidden="true"></i><span>Pause animation</span></button>
                <span class="studio-showcase-stream" aria-hidden="true"></span>
                <span class="studio-showcase-orbit studio-showcase-orbit--one" aria-hidden="true"></span>
                <span class="studio-showcase-orbit studio-showcase-orbit--two" aria-hidden="true"></span>
                <span class="studio-showcase-orbit studio-showcase-orbit--three" aria-hidden="true"></span>

                <div class="studio-showcase-card studio-showcase-card--one" aria-hidden="true">
                    <div class="studio-showcase-card-head"><span>Short 01</span><span>9:16</span></div>
                    <div class="studio-scene studio-photo-scene"><img src="{{ asset('creator-mrbeast.webp') }}" alt="" width="960" height="1440" loading="lazy" decoding="async"></div>
                    <div class="studio-showcase-card-foot"><span>MrBeast</span><i class="fa-solid fa-bolt"></i></div>
                </div>
                <div class="studio-showcase-card studio-showcase-card--two" aria-hidden="true">
                    <div class="studio-showcase-card-head"><span>Short 02</span><span>9:16</span></div>
                    <div class="studio-scene studio-photo-scene"><img src="{{ asset('creator-raj-shamani.webp') }}" alt="" width="274" height="395" loading="lazy" decoding="async"></div>
                    <div class="studio-showcase-card-foot"><span>Raj Shamani</span><i class="fa-solid fa-microphone-lines"></i></div>
                </div>
                <div class="studio-showcase-card studio-showcase-card--three" aria-hidden="true">
                    <div class="studio-showcase-card-head"><span>Short 03</span><span>9:16</span></div>
                    <div class="studio-scene studio-photo-scene"><img src="{{ asset('creator-casey-neistat.webp') }}" alt="" width="960" height="1200" loading="lazy" decoding="async"></div>
                    <div class="studio-showcase-card-foot"><span>Casey Neistat</span><i class="fa-solid fa-video"></i></div>
                </div>

                <div class="studio-showcase-source" aria-hidden="true">
                    <div class="studio-showcase-source-art">
                        <img src="{{ asset('creator-mrbeast.webp') }}" alt="" width="960" height="1440" loading="lazy" decoding="async">
                        <img src="{{ asset('creator-raj-shamani.webp') }}" alt="" width="274" height="395" loading="lazy" decoding="async">
                        <img src="{{ asset('creator-casey-neistat.webp') }}" alt="" width="960" height="1200" loading="lazy" decoding="async">
                    </div>
                    <div><strong>Creator formats</strong><small>Entertainment, podcasts, vlogs</small></div>
                </div>
            </div>
        </div>
    </section>

    <section id="how-it-works" class="studio-section" aria-labelledby="workflow-title">
        <div class="studio-section-heading">
            <p>How it works</p>
            <h2 id="workflow-title">From one full video to clips you can review and download.</h2>
            <span>Choose your source and clip length. The video runs through background processing, so you can return to the result later.</span>
        </div>
        <ol class="studio-steps">
            <li>
                <div class="studio-step-top"><span>01 / Add a video</span><i class="fa-solid fa-link" aria-hidden="true"></i></div>
                <h3>Bring your footage</h3>
                <p>Use a public YouTube link or upload an MP4, MOV, MKV, or WebM file up to 500 MB.</p>
            </li>
            <li>
                <div class="studio-step-top"><span>02 / Choose a cut</span><i class="fa-solid fa-wand-magic-sparkles" aria-hidden="true"></i></div>
                <h3>Set your short format</h3>
                <p>Choose a roughly 30-second or 60-second target and request 1, 2, 3, or 5 clips.</p>
            </li>
            <li>
                <div class="studio-step-top"><span>03 / Review</span><i class="fa-solid fa-film" aria-hidden="true"></i></div>
                <h3>Get your clips</h3>
                <p>Review 9:16 MP4 shorts with suggested titles, descriptions, and tags, then download the ones you want.</p>
            </li>
        </ol>
    </section>

    <section id="features" class="studio-section" aria-labelledby="use-cases-title">
        <div class="studio-section-heading">
            <p>Made for real footage</p>
            <h2 id="use-cases-title">Give the videos you already make another format.</h2>
            <span>Whether your audience is in India, the US, or elsewhere, the workflow starts with the same thing: a video you want to turn into shorter cuts.</span>
        </div>
        <div class="studio-use-cases">
            <article>
                <i class="fa-solid fa-microphone-lines" aria-hidden="true"></i>
                <div><h3>Interviews and podcasts</h3><p>Pull shorter moments from a longer conversation for review.</p></div>
            </article>
            <article>
                <i class="fa-solid fa-graduation-cap" aria-hidden="true"></i>
                <div><h3>Tutorials and explainers</h3><p>Turn a lesson or walkthrough into focused clips.</p></div>
            </article>
            <article>
                <i class="fa-solid fa-video" aria-hidden="true"></i>
                <div><h3>Creator videos</h3><p>Start with your full upload and inspect the shorter cuts.</p></div>
            </article>
            <article>
                <i class="fa-solid fa-people-group" aria-hidden="true"></i>
                <div><h3>Talks and events</h3><p>Revisit the key moments from longer recorded sessions.</p></div>
            </article>
        </div>
    </section>

    <section id="faq" class="studio-section studio-faq" aria-labelledby="faq-title">
        <div class="studio-section-heading">
            <p>Questions, answered</p>
            <h2 id="faq-title">Know what goes in and what comes out.</h2>
            <span>Short, practical answers before you start a project.</span>
        </div>
        <div class="studio-faq-list">
            <details>
                <summary>What video sources can I use?</summary>
                <p>Use a public YouTube URL or upload an MP4, MOV, MKV, or WebM file. Uploaded files can be up to 500 MB.</p>
            </details>
            <details>
                <summary>Can I choose the number and length of clips?</summary>
                <p>Yes. You can request 1, 2, 3, or 5 clips and select a roughly 30-second or 60-second target.</p>
            </details>
            <details>
                <summary>What do I get when processing finishes?</summary>
                <p>You can review and download vertical 9:16 MP4 shorts. Each clip also includes a suggested title, description, and tags.</p>
            </details>
            <details>
                <summary>Do I need to keep the page open?</summary>
                <p>No. Processing runs in the background. Check your dashboard for progress and in-app notifications when clips are ready.</p>
            </details>
        </div>
    </section>

    <section class="studio-final" aria-labelledby="studio-final-title">
        <div>
            <h2 id="studio-final-title">Your next short can start with a video you already have.</h2>
            <p>Create an account, add a source, and choose the clips you want to make.</p>
        </div>
        <a href="{{ route('register') }}" class="studio-final-action">Start a project <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
    </section>
</div>

@push('scripts')
<script>
(() => {
    const hero = document.querySelector('.studio-hero-shell');
    const stage = hero?.querySelector('.studio-stage');

    if (!hero || !stage || !('IntersectionObserver' in window)) {
        return;
    }

    const source = stage.querySelector('.studio-source');
    const mainShort = stage.querySelector('.studio-short-main');
    const secondShort = stage.querySelector('.studio-short-second');
    const mainBubble = stage.querySelector('.studio-bubble-main');
    const secondBubble = stage.querySelector('.studio-bubble-second');
    const waveform = stage.querySelector('.studio-wave-card');
    const cropGuide = stage.querySelector('.studio-crop-guide');
    const motionPreference = window.matchMedia('(prefers-reduced-motion: reduce)');

    let stageTop = 0;
    let scrollDistance = 0;
    let isHeroVisible = false;
    let animationFrame = 0;

    const updateBounds = () => {
        stageTop = stage.getBoundingClientRect().top + window.scrollY;
        scrollDistance = Math.max(280, stage.offsetHeight * 0.8);
    };

    const revealProgress = (progress, start, end) => Math.min(1, Math.max(0, (progress - start) / (end - start)));
    const easeOut = (progress) => 1 - Math.pow(1 - progress, 3);

    const renderMotion = () => {
        animationFrame = 0;

        if (motionPreference.matches) {
            return;
        }

        const start = Math.max(0, stageTop - window.innerHeight * 0.5);
        const progress = Math.min(1, Math.max(0, (window.scrollY - start) / scrollDistance));
        const travel = window.innerWidth < 640 ? 0.55 : 1;
        const mainReveal = revealProgress(progress, 0, 0.34);
        const secondReveal = revealProgress(progress, 0.14, 0.54);
        const mainEase = easeOut(mainReveal);
        const secondEase = easeOut(secondReveal);
        const mainPop = Math.sin(Math.PI * mainReveal);
        const secondPop = Math.sin(Math.PI * secondReveal);

        source.style.transform = `translate3d(${-34 * progress * travel}px, ${18 * progress * travel}px, ${15 - 42 * progress}px) rotateY(${12 + 5 * progress}deg) rotateZ(${-7 - 4 * progress}deg) scale(${1 - 0.05 * progress})`;
        source.style.opacity = String(1 - 0.16 * progress);
        mainShort.style.transform = `translate3d(${(-30 * progress - 82 * (1 - mainEase)) * travel}px, ${(-25 * progress + 58 * (1 - mainEase)) * travel}px, ${75 + 48 * progress - 55 * (1 - mainEase)}px) rotateY(${-12 + 10 * progress - 8 * (1 - mainEase)}deg) rotateZ(${7 - 7 * progress - 10 * (1 - mainEase)}deg) scale(${0.68 + 0.4 * mainEase + 0.065 * mainPop})`;
        mainShort.style.opacity = String(0.52 + 0.48 * mainEase);
        secondShort.style.transform = `translate3d(${(12 * progress + 94 * (1 - secondEase)) * travel}px, ${(-16 * progress + 57 * (1 - secondEase)) * travel}px, ${45 + 22 * progress - 45 * (1 - secondEase)}px) rotateY(${15 - 9 * progress + 8 * (1 - secondEase)}deg) rotateZ(${-12 + 5 * progress + 13 * (1 - secondEase)}deg) scale(${0.66 + 0.375 * secondEase + 0.06 * secondPop})`;
        secondShort.style.opacity = String(0.34 + 0.66 * secondEase);
        mainBubble.style.transform = `translate3d(${(-30 * progress - 82 * (1 - mainEase)) * travel}px, ${(-25 * progress + 58 * (1 - mainEase)) * travel}px, 0) scale(${0.42 + 0.8 * mainEase})`;
        mainBubble.style.opacity = String(0.58 * mainPop);
        secondBubble.style.transform = `translate3d(${(12 * progress + 94 * (1 - secondEase)) * travel}px, ${(-16 * progress + 57 * (1 - secondEase)) * travel}px, 0) scale(${0.42 + 0.8 * secondEase})`;
        secondBubble.style.opacity = String(0.5 * secondPop);
        waveform.style.transform = `translate3d(${7 * progress * travel}px, ${-10 * progress * travel}px, ${95 + 20 * progress}px) rotateZ(${-3 + 3 * progress}deg) scale(${1 + 0.025 * progress})`;
        cropGuide.style.opacity = String(1 - 0.55 * progress);
    };

    const requestMotion = () => {
        if (isHeroVisible && !motionPreference.matches && !animationFrame) {
            animationFrame = window.requestAnimationFrame(renderMotion);
        }
    };

    const observer = new IntersectionObserver(([entry]) => {
        isHeroVisible = entry.isIntersecting;
        stage.classList.toggle('is-scroll-active', isHeroVisible && !motionPreference.matches);
        requestMotion();
    }, { rootMargin: '80px 0px' });

    const resetMotion = () => {
        if (animationFrame) {
            window.cancelAnimationFrame(animationFrame);
            animationFrame = 0;
        }

        stage.classList.remove('is-scroll-active');
        for (const element of [source, mainShort, secondShort, mainBubble, secondBubble, waveform]) {
            element.style.removeProperty('transform');
        }
        for (const element of [source, mainShort, secondShort, mainBubble, secondBubble, cropGuide]) {
            element.style.removeProperty('opacity');
        }
    };

    motionPreference.addEventListener('change', () => {
        if (motionPreference.matches) {
            resetMotion();
        } else {
            updateBounds();
            stage.classList.toggle('is-scroll-active', isHeroVisible);
            requestMotion();
        }
    });

    window.addEventListener('scroll', requestMotion, { passive: true });
    window.addEventListener('resize', () => {
        updateBounds();
        requestMotion();
    }, { passive: true });

    updateBounds();
    if (!motionPreference.matches) {
        stage.classList.add('is-scroll-active');
        renderMotion();
    }
    observer.observe(hero);
})();

(() => {
    const showcase = document.querySelector('.studio-showcase');
    const track = showcase?.querySelector('.studio-showcase-track');
    const previewButton = showcase?.querySelector('.studio-showcase-preview');

    if (!showcase || !track || !previewButton || !('IntersectionObserver' in window)) {
        return;
    }

    const clips = [
        { card: showcase.querySelector('.studio-showcase-card--one'), orbit: showcase.querySelector('.studio-showcase-orbit--one'), start: 0.02, end: 0.3, fromX: 195, fromY: 250, tilt: -7 },
        { card: showcase.querySelector('.studio-showcase-card--two'), orbit: showcase.querySelector('.studio-showcase-orbit--two'), start: 0.31, end: 0.59, fromX: 0, fromY: 270, tilt: 2 },
        { card: showcase.querySelector('.studio-showcase-card--three'), orbit: showcase.querySelector('.studio-showcase-orbit--three'), start: 0.6, end: 0.88, fromX: -195, fromY: 250, tilt: 7 },
    ];

    let isMotionPaused = false;
    let isShowcaseVisible = false;
    let animationFrame = 0;

    const isMotionEnabled = () => !isMotionPaused;
    const clamp = (value) => Math.min(1, Math.max(0, value));
    const easeOutBack = (value) => 1 + 2.70158 * Math.pow(value - 1, 3) + 1.70158 * Math.pow(value - 1, 2);

    const renderMotion = () => {
        animationFrame = 0;

        if (!isMotionEnabled()) {
            return;
        }

        const trackTop = track.getBoundingClientRect().top;
        const scrollDistance = Math.max(1, track.offsetHeight - window.innerHeight);
        const progress = clamp(-trackTop / scrollDistance);
        const travel = window.innerWidth < 640 ? 0.52 : 1;

        for (const clip of clips) {
            const reveal = clamp((progress - clip.start) / (clip.end - clip.start));
            const eased = easeOutBack(reveal);
            const x = clip.fromX * (1 - eased) * travel;
            const y = clip.fromY * (1 - eased) * travel;
            const scale = 0.22 + 0.78 * eased;
            const orbitPulse = Math.sin(Math.PI * reveal);

            clip.card.style.transform = `translate3d(${x}px, ${y}px, 0) rotate(${clip.tilt * eased}deg) scale(${scale})`;
            clip.card.style.opacity = String(clamp(reveal * 2.5));
            clip.orbit.style.transform = `translate3d(${x}px, ${y}px, 0) scale(${0.45 + 0.85 * eased})`;
            clip.orbit.style.opacity = String(0.7 * orbitPulse);
        }
    };

    const requestMotion = () => {
        if (isShowcaseVisible && isMotionEnabled() && !animationFrame) {
            animationFrame = window.requestAnimationFrame(renderMotion);
        }
    };

    const resetMotion = () => {
        if (animationFrame) {
            window.cancelAnimationFrame(animationFrame);
            animationFrame = 0;
        }

        showcase.classList.remove('is-motion-active');
        for (const clip of clips) {
            clip.card.style.removeProperty('transform');
            clip.card.style.removeProperty('opacity');
            clip.orbit.style.removeProperty('transform');
            clip.orbit.style.removeProperty('opacity');
        }
    };

    const updateMotionButton = () => {
        const action = isMotionPaused ? 'Replay scroll animation' : 'Pause scroll animation';
        previewButton.hidden = false;
        previewButton.setAttribute('aria-label', action);
        previewButton.title = action;
        previewButton.querySelector('span').textContent = isMotionPaused ? 'Replay animation' : 'Pause animation';
        previewButton.querySelector('i').className = isMotionPaused ? 'fa-solid fa-play' : 'fa-solid fa-pause';
    };

    const observer = new IntersectionObserver(([entry]) => {
        isShowcaseVisible = entry.isIntersecting;
        requestMotion();
    }, { rootMargin: '120px 0px' });

    previewButton.addEventListener('click', () => {
        isMotionPaused = !isMotionPaused;
        updateMotionButton();

        if (!isMotionEnabled()) {
            resetMotion();
            return;
        }

        showcase.classList.add('is-motion-active');
        const trackTop = track.getBoundingClientRect().top + window.scrollY;
        window.scrollTo({ top: Math.max(0, trackTop - 72), behavior: 'instant' });
        renderMotion();
    });

    window.addEventListener('scroll', requestMotion, { passive: true });
    window.addEventListener('resize', requestMotion, { passive: true });

    updateMotionButton();
    showcase.classList.add('is-motion-active');
    renderMotion();
    observer.observe(track);
})();
</script>
@endpush
@endsection
