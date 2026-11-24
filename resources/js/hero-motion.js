import {
    BufferGeometry,
    Color,
    DoubleSide,
    Float32BufferAttribute,
    Group,
    Line,
    LineBasicMaterial,
    LineLoop,
    Mesh,
    MeshBasicMaterial,
    PerspectiveCamera,
    Points,
    PointsMaterial,
    Scene,
    WebGLRenderer,
} from 'three';

const WAVE_POINTS = 144;
const TWO_PI = Math.PI * 2;

function createWave(color, opacity, baseY, phase) {
    const positions = new Float32Array(WAVE_POINTS * 6);
    const spinePositions = new Float32Array(WAVE_POINTS * 3);
    const indices = [];
    const colors = [];
    const startColor = new Color(color);
    const endColor = new Color('#ff866e');

    for (let index = 0; index < WAVE_POINTS; index += 1) {
        const shade = startColor.clone().lerp(endColor, (index / WAVE_POINTS) * 0.42);
        colors.push(shade.r, shade.g, shade.b, shade.r, shade.g, shade.b);

        if (index < WAVE_POINTS - 1) {
            const vertex = index * 2;
            indices.push(vertex, vertex + 1, vertex + 2, vertex + 1, vertex + 3, vertex + 2);
        }
    }

    const geometry = new BufferGeometry();
    geometry.setAttribute('position', new Float32BufferAttribute(positions, 3));
    geometry.setAttribute('color', new Float32BufferAttribute(colors, 3));
    geometry.setIndex(indices);
    const ribbon = new Mesh(geometry, new MeshBasicMaterial({
        vertexColors: true,
        transparent: true,
        opacity,
        side: DoubleSide,
        depthWrite: false,
    }));
    ribbon.frustumCulled = false;

    const spineGeometry = new BufferGeometry();
    spineGeometry.setAttribute('position', new Float32BufferAttribute(spinePositions, 3));
    const line = new Line(spineGeometry, new LineBasicMaterial({
        color,
        transparent: true,
        opacity: Math.min(1, opacity * 2.8),
        depthWrite: false,
    }));
    line.frustumCulled = false;

    return { ribbon, line, positions, spinePositions, baseY, phase };
}

function createOrbit(radiusX, radiusY, color, opacity, rotation) {
    const vertices = [];

    for (let index = 0; index < 96; index += 1) {
        const angle = (index / 96) * TWO_PI;
        vertices.push(Math.cos(angle) * radiusX, Math.sin(angle) * radiusY, 0);
    }

    const geometry = new BufferGeometry();
    geometry.setAttribute('position', new Float32BufferAttribute(vertices, 3));

    const orbit = new LineLoop(geometry, new LineBasicMaterial({
        color,
        transparent: true,
        opacity,
        depthWrite: false,
    }));
    orbit.rotation.x = rotation;

    return orbit;
}

function createVideoFrame(color, opacity) {
    const width = 0.85;
    const height = 1.5;
    const cut = 0.12;
    const vertices = [
        -width + cut, height, 0,
        width - cut, height, 0,
        width, height - cut, 0,
        width, -height + cut, 0,
        width - cut, -height, 0,
        -width + cut, -height, 0,
        -width, -height + cut, 0,
        -width, height - cut, 0,
    ];
    const geometry = new BufferGeometry();
    geometry.setAttribute('position', new Float32BufferAttribute(vertices, 3));

    return new LineLoop(geometry, new LineBasicMaterial({
        color,
        transparent: true,
        opacity,
        depthWrite: false,
    }));
}

function createParticles(isCompact) {
    const count = isCompact ? 90 : 230;
    const positions = [];
    const colors = [];
    const palette = [new Color('#5968f1'), new Color('#9fa8ff'), new Color('#ff866e')];

    for (let index = 0; index < count; index += 1) {
        const angle = (index / count) * TWO_PI * 6.5;
        const radius = 1.8 + Math.random() * 5.7;
        const color = palette[index % palette.length];

        positions.push(
            Math.cos(angle) * radius + (Math.random() - 0.5) * 3,
            Math.sin(angle) * radius * 0.48 + (Math.random() - 0.5) * 2.5,
            -3 + Math.random() * 5,
        );
        colors.push(color.r, color.g, color.b);
    }

    const geometry = new BufferGeometry();
    geometry.setAttribute('position', new Float32BufferAttribute(positions, 3));
    geometry.setAttribute('color', new Float32BufferAttribute(colors, 3));

    return new Points(geometry, new PointsMaterial({
        size: isCompact ? 0.075 : 0.095,
        vertexColors: true,
        transparent: true,
        opacity: 0.72,
        depthWrite: false,
    }));
}

function createInteraction(host, reducedMotion, saveData) {
    const surface = host.closest('.landing-hero') ?? host.parentElement;
    const state = { x: 0, y: 0, pulse: 0 };

    const updatePosition = (clientX, clientY) => {
        if (reducedMotion.matches || saveData) {
            return;
        }

        const bounds = surface.getBoundingClientRect();
        state.x = Math.max(-1, Math.min(1, ((clientX - bounds.left) / bounds.width - 0.5) * 2));
        state.y = Math.max(-1, Math.min(1, ((clientY - bounds.top) / bounds.height - 0.5) * 2));
        host.style.setProperty('--motion-shift-x', `${(state.x * 38).toFixed(1)}px`);
        host.style.setProperty('--motion-shift-y', `${(state.y * 24).toFixed(1)}px`);
    };

    const handlePointerMove = (event) => updatePosition(event.clientX, event.clientY);
    const handlePointerDown = (event) => {
        updatePosition(event.clientX, event.clientY);
        state.pulse = 1.25;
    };
    const handleTouchStart = (event) => {
        const touch = event.touches[0];

        if (touch) {
            updatePosition(touch.clientX, touch.clientY);
            state.pulse = 1.45;
        }
    };
    const handleTouchMove = (event) => {
        const touch = event.touches[0];

        if (touch) {
            updatePosition(touch.clientX, touch.clientY);
        }
    };
    const handleLeave = () => {
        state.x = 0;
        state.y = 0;
        host.style.setProperty('--motion-shift-x', '0px');
        host.style.setProperty('--motion-shift-y', '0px');
    };

    surface.addEventListener('pointermove', handlePointerMove, { passive: true });
    surface.addEventListener('pointerdown', handlePointerDown, { passive: true });
    surface.addEventListener('pointerleave', handleLeave);
    surface.addEventListener('touchstart', handleTouchStart, { passive: true });
    surface.addEventListener('touchmove', handleTouchMove, { passive: true });
    surface.addEventListener('touchend', handleLeave, { passive: true });

    return {
        state,
        dispose: () => {
            surface.removeEventListener('pointermove', handlePointerMove);
            surface.removeEventListener('pointerdown', handlePointerDown);
            surface.removeEventListener('pointerleave', handleLeave);
            surface.removeEventListener('touchstart', handleTouchStart);
            surface.removeEventListener('touchmove', handleTouchMove);
            surface.removeEventListener('touchend', handleLeave);
        },
    };
}

export function mountHeroMotion(host) {
    const isCompact = window.matchMedia('(max-width: 768px)').matches;
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    const saveData = navigator.connection?.saveData ?? false;
    const interaction = createInteraction(host, reducedMotion, saveData);

    let renderer;

    try {
        renderer = new WebGLRenderer({
            alpha: true,
            antialias: !isCompact,
            powerPreference: 'low-power',
        });
    } catch {
        return interaction.dispose;
    }

    renderer.setClearColor(0xffffff, 0);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, isCompact ? 1 : 1.6));
    renderer.domElement.style.cssText = 'position:absolute;inset:0;width:100%;height:100%;pointer-events:none;display:block;';
    host.appendChild(renderer.domElement);

    const scene = new Scene();
    const camera = new PerspectiveCamera(40, 1, 0.1, 100);
    camera.position.z = 12;

    const artwork = new Group();
    scene.add(artwork);

    const waves = [
        createWave('#5365f5', 0.26, 2.1, 0),
        createWave('#8d78eb', 0.19, -0.2, 1.4),
        createWave('#ff735b', 0.23, -2.35, 2.6),
    ];
    waves.forEach(({ ribbon, line }) => artwork.add(ribbon, line));

    const orbitGroup = new Group();
    orbitGroup.position.set(3.5, 0.25, -1.6);
    orbitGroup.add(createOrbit(3.1, 2.0, '#5365f5', 0.32, 0.42));
    orbitGroup.add(createOrbit(4.1, 2.7, '#8d78eb', 0.26, -0.32));
    orbitGroup.add(createOrbit(2.15, 3.4, '#ff735b', 0.27, 0.92));
    artwork.add(orbitGroup);

    const frames = [
        createVideoFrame('#5365f5', 0.38),
        createVideoFrame('#ff735b', 0.34),
    ];
    frames[0].position.set(-5.4, 1.6, -1.8);
    frames[0].rotation.z = -0.25;
    frames[1].position.set(6.2, -1.5, -2.3);
    frames[1].rotation.z = 0.18;
    frames.forEach((frame) => artwork.add(frame));

    const particles = createParticles(isCompact);
    artwork.add(particles);

    let isVisible = true;
    let isDisposed = false;
    let lastFrame = 0;
    const startTime = performance.now();
    const pointer = interaction.state;
    const frameInterval = isCompact ? 42 : 33;

    const resize = () => {
        const { width, height } = host.getBoundingClientRect();

        if (width < 1 || height < 1) {
            return;
        }

        camera.aspect = width / height;
        camera.updateProjectionMatrix();
        renderer.setSize(width, height, false);
        renderer.render(scene, camera);
    };

    const renderFrame = (timestamp) => {
        if (timestamp - lastFrame < frameInterval) {
            return;
        }

        lastFrame = timestamp;
        const elapsed = (timestamp - startTime) / 1000;

        waves.forEach((wave, waveIndex) => {
            for (let index = 0; index < WAVE_POINTS; index += 1) {
                const x = -9 + (index / (WAVE_POINTS - 1)) * 18;
                const distance = x - pointer.x * 7;
                const proximity = Math.exp(-(distance * distance) / 7);
                const ripple = Math.sin(distance * 2.2 - elapsed * 4.5 + waveIndex) * proximity * pointer.pulse * 0.54;
                const centerY = wave.baseY
                    + Math.sin(x * 0.52 + elapsed * 0.38 + wave.phase) * 0.42
                    + Math.sin(x * 1.22 - elapsed * 0.23 + wave.phase) * 0.1
                    - pointer.y * proximity * 0.27 + ripple;
                const depth = -2 + Math.cos(x * 0.42 + elapsed * 0.2 + waveIndex) * 0.52;
                const thickness = 0.14 + Math.sin(x * 0.6 + elapsed * 0.7 + wave.phase) * 0.035;
                const ribbonOffset = index * 6;
                const spineOffset = index * 3;

                wave.positions[ribbonOffset] = x;
                wave.positions[ribbonOffset + 1] = centerY + thickness;
                wave.positions[ribbonOffset + 2] = depth;
                wave.positions[ribbonOffset + 3] = x;
                wave.positions[ribbonOffset + 4] = centerY - thickness;
                wave.positions[ribbonOffset + 5] = depth;
                wave.spinePositions[spineOffset] = x;
                wave.spinePositions[spineOffset + 1] = centerY;
                wave.spinePositions[spineOffset + 2] = depth + 0.01;
            }

            wave.ribbon.geometry.attributes.position.needsUpdate = true;
            wave.line.geometry.attributes.position.needsUpdate = true;
        });

        pointer.pulse *= 0.955;
        artwork.rotation.y += (pointer.x * 0.12 - artwork.rotation.y) * 0.06;
        artwork.rotation.x += (-pointer.y * 0.08 - artwork.rotation.x) * 0.06;
        orbitGroup.rotation.z = elapsed * 0.025;
        orbitGroup.rotation.y = Math.sin(elapsed * 0.2) * 0.11 + pointer.x * 0.2;
        orbitGroup.position.x = 3.5 + pointer.x * 0.5;
        orbitGroup.position.y = 0.25 - pointer.y * 0.38;
        frames[0].position.y = 1.6 + Math.sin(elapsed * 0.7) * 0.18;
        frames[1].position.y = -1.5 + Math.sin(elapsed * 0.61 + 1) * 0.16;
        frames[0].rotation.y = pointer.x * 0.19;
        frames[1].rotation.y = -pointer.x * 0.17;
        particles.rotation.z = Math.sin(elapsed * 0.12) * 0.06;

        renderer.render(scene, camera);
    };

    const renderStill = () => {
        lastFrame = 0;
        renderFrame(performance.now());
    };

    const syncAnimation = () => {
        const shouldAnimate = !isDisposed && isVisible && !document.hidden && !reducedMotion.matches && !saveData;

        if (shouldAnimate) {
            lastFrame = 0;
            renderer.setAnimationLoop(renderFrame);
        } else {
            renderer.setAnimationLoop(null);
            renderStill();
        }
    };

    const visibilityObserver = new IntersectionObserver(([entry]) => {
        isVisible = entry.isIntersecting;
        syncAnimation();
    }, { threshold: 0.02 });
    const resizeObserver = new ResizeObserver(resize);

    visibilityObserver.observe(host);
    resizeObserver.observe(host);
    document.addEventListener('visibilitychange', syncAnimation);
    reducedMotion.addEventListener('change', syncAnimation);
    resize();
    syncAnimation();

    return () => {
        isDisposed = true;
        renderer.setAnimationLoop(null);
        visibilityObserver.disconnect();
        resizeObserver.disconnect();
        document.removeEventListener('visibilitychange', syncAnimation);
        reducedMotion.removeEventListener('change', syncAnimation);
        interaction.dispose();
        scene.traverse((object) => {
            object.geometry?.dispose();
            object.material?.dispose();
        });
        renderer.dispose();
        renderer.domElement.remove();
    };
}
