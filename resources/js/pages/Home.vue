<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { onBeforeUnmount, onMounted, ref } from 'vue';

interface ShortenedLink {
    id: string;
    original: string;
    short: string;
}

const url = ref('');
const isLoading = ref(false);
const error = ref('');
const copiedId = ref<string | null>(null);
const links = ref<ShortenedLink[]>([]);

function isValidUrl(value: string) {
    try {
        new URL(value);
        return true;
    } catch {
        return false;
    }
}
async function shortenUrl() {
    error.value = '';

    if (!url.value.trim()) {
        error.value = 'Paste a URL to get started.';
        return;
    }
    if (!isValidUrl(url.value)) {
        error.value = "That doesn't look like a valid URL.";
        return;
    }

    isLoading.value = true;

    axios
        .post('/shortify', {
            url: url.value,
        })
        .then((response: any) => {
            links.value.unshift({
                id: response.data.id,
                original: response.data.original_url,
                short: response.data.code,
            });
        })
        .catch((e: any) => {
            console.log(e.message);
        })
        .finally(()=>{
            url.value = '';
            isLoading.value = false;
        })
}

async function copyLink(link: ShortenedLink) {
    try {
        await navigator.clipboard.writeText(`https://shorta.io/${link.short}`);

        copiedId.value = link.id;

        setTimeout(() => {
            if (copiedId.value === link.id) {
                copiedId.value = null;
            }
        }, 1800);
    } catch (e: any) {
        console.error(e.message);
        error.value = `Couldn't copy the link! Manually copy: https://shorta.io/${link.short}`;
    }
}

const canvasRef = ref<HTMLCanvasElement | null>(null);

type Particle = {
    x: number;
    y: number;
    size: number;
    alpha: number;
    color: string;
};

let ctx: CanvasRenderingContext2D | null = null;
let particles: Particle[] = [];
let frameId = 0;
const colors = ['99,102,241', '217,70,239', '34,211,238']; // indigo, fuchsia, cyan

function resizeCanvas() {
    if (!canvasRef.value) return;
    canvasRef.value.width = window.innerWidth;
    canvasRef.value.height = window.innerHeight;
}

function handlePointerMove(e: PointerEvent) {
    particles.push({
        x: e.clientX,
        y: e.clientY,
        size: Math.random() * 3 + 2,
        alpha: 0.4,
        color: colors[Math.floor(Math.random() * colors.length)],
    });
    if (particles.length > 120) particles.splice(0, particles.length - 120);
}

function renderTrail() {
    if (!ctx || !canvasRef.value) return;
    ctx.clearRect(0, 0, canvasRef.value.width, canvasRef.value.height);

    for (const p of particles) {
        ctx.beginPath();
        ctx.fillStyle = `rgba(${p.color}, ${p.alpha})`;
        ctx.shadowBlur = 15;
        ctx.shadowColor = `rgba(${p.color}, ${p.alpha})`;
        ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
        ctx.fill();
        p.alpha *= 0.94;
        p.size *= 1.05;
    }

    particles = particles.filter((p) => p.alpha > 0.02);
    frameId = requestAnimationFrame(renderTrail);
}

onMounted(() => {
    const reducedMotion = window.matchMedia(
        '(prefers-reduced-motion: reduce)',
    ).matches;
    if (reducedMotion || !canvasRef.value) return;

    ctx = canvasRef.value.getContext('2d');
    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);
    window.addEventListener('pointermove', handlePointerMove);
    frameId = requestAnimationFrame(renderTrail);
});

onBeforeUnmount(() => {
    window.removeEventListener('resize', resizeCanvas);
    window.removeEventListener('pointermove', handlePointerMove);
    cancelAnimationFrame(frameId);
});
</script>

<template>
    <Head title="Home">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <div
        class="relative min-h-screen overflow-hidden bg-slate-950 text-slate-100"
    >
        <canvas ref="canvasRef" class="pointer-events-none fixed inset-0 z-50"></canvas>
        <!-- ambient glow -->
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div
                class="blob absolute -top-32 -left-32 h-96 w-96 rounded-full bg-indigo-600/30 blur-3xl"
            ></div>
            <div
                class="blob blob-delay absolute top-1/3 -right-24 h-[28rem] w-[28rem] rounded-full bg-fuchsia-600/20 blur-3xl"
            ></div>
            <div
                class="blob blob-delay-2 absolute bottom-0 left-1/3 h-80 w-80 rounded-full bg-cyan-500/20 blur-3xl"
            ></div>
        </div>

        <!-- subtle grid -->
        <div
            class="pointer-events-none absolute inset-0 bg-[linear-gradient(to_right,rgba(255,255,255,0.04)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.04)_1px,transparent_1px)] bg-[size:48px_48px]"
        ></div>

        <main
            class="relative mx-auto flex min-h-screen w-full max-w-2xl flex-col items-center justify-center gap-10 px-6 py-16"
        >
            <!-- badge -->
            <span
                class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-1.5 text-xs font-medium text-slate-300 backdrop-blur"
            >
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                No sign-up required
            </span>

            <!-- heading -->
            <div class="flex flex-col items-center gap-4 text-center">
                <h1
                    class="text-4xl font-bold tracking-tight text-white sm:text-5xl lg:text-6xl"
                >
                    Shorten links,
                    <span
                        class="bg-gradient-to-r from-indigo-400 via-fuchsia-400 to-cyan-400 bg-clip-text text-transparent"
                        >instantly</span
                    >
                </h1>
                <p class="max-w-md text-base text-slate-400 sm:text-lg">
                    Paste a long URL below and get a clean, shareable link in
                    seconds. Free, fast, and built for speed.
                </p>
            </div>

            <!-- shorten card -->
            <div
                class="w-full rounded-2xl border border-white/10 bg-white/5 p-2 shadow-2xl shadow-black/40 backdrop-blur-xl sm:p-2.5"
            >
                <form
                    @submit.prevent="shortenUrl"
                    class="flex flex-col gap-2 sm:flex-row"
                >
                    <input
                        v-model="url"
                        type="text"
                        placeholder="Paste your URL..."
                        class="min-h-[48px] grow rounded-xl bg-white/5 px-4 text-base text-white ring-1 ring-white/10 transition outline-none ring-inset placeholder:text-slate-500 focus:bg-white/10 focus:ring-2 focus:ring-indigo-400"
                        @keyup.enter="shortenUrl"
                    />
                    <button
                        type="submit"
                        :disabled="isLoading"
                        class="flex min-h-[48px] items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-fuchsia-500 px-6 text-base font-semibold text-white transition hover:opacity-90 active:scale-[0.98] disabled:cursor-not-allowed disabled:opacity-60"
                    >
                        <svg
                            v-if="isLoading"
                            class="h-4 w-4 animate-spin"
                            viewBox="0 0 24 24"
                            fill="none"
                        >
                            <circle
                                class="opacity-25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"
                            ></circle>
                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                            ></path>
                        </svg>
                        <span>{{
                            isLoading ? 'Shortening...' : 'Shorten'
                        }}</span>
                    </button>
                </form>
                <p v-if="error" class="px-3 pt-2 text-sm text-rose-400">
                    {{ error }}
                </p>
            </div>

            <!-- results -->
            <transition-group
                v-if="links.length"
                tag="ul"
                name="list"
                class="flex w-full flex-col gap-2"
            >
                <li
                    v-for="link in links"
                    :key="link.id"
                    class="flex items-center justify-between gap-3 rounded-xl border border-white/10 bg-white/5 px-4 py-3 backdrop-blur"
                >
                    <div class="min-w-0">
                        <p class="truncate text-sm font-medium text-indigo-300">
                            {{ link.short }}
                        </p>
                        <p class="truncate text-xs text-slate-500">
                            {{ link.original }}
                        </p>
                    </div>
                    <button
                        @click="copyLink(link)"
                        class="shrink-0 rounded-lg border border-white/10 px-3 py-1.5 text-xs font-medium text-slate-300 transition hover:bg-white/10"
                    >
                        {{ copiedId === link.id ? 'Copied!' : 'Copy' }}
                    </button>
                </li>
            </transition-group>

            <!-- features -->
            <div class="grid w-full grid-cols-1 gap-3 sm:grid-cols-3">
                <div
                    class="rounded-xl border border-white/10 bg-white/[0.03] p-4 text-center"
                >
                    <p class="text-sm font-semibold text-white">Fast</p>
                    <p class="mt-1 text-xs text-slate-500">
                        Links generate instantly
                    </p>
                </div>
                <div
                    class="rounded-xl border border-white/10 bg-white/[0.03] p-4 text-center"
                >
                    <p class="text-sm font-semibold text-white">Secure</p>
                    <p class="mt-1 text-xs text-slate-500">HTTPS by default</p>
                </div>
                <div
                    class="rounded-xl border border-white/10 bg-white/[0.03] p-4 text-center"
                >
                    <p class="text-sm font-semibold text-white">Free</p>
                    <p class="mt-1 text-xs text-slate-500">
                        No limits, no account
                    </p>
                </div>
            </div>
        </main>
    </div>
</template>

<style scoped>
.blob {
    animation: float 10s ease-in-out infinite;
}
.blob-delay {
    animation-delay: -3s;
}
.blob-delay-2 {
    animation-delay: -6s;
}
@keyframes float {
    0%,
    100% {
        transform: translate(0, 0) scale(1);
    }
    50% {
        transform: translate(20px, -30px) scale(1.05);
    }
}
.list-enter-active {
    transition: all 0.3s ease;
}
.list-enter-from {
    opacity: 0;
    transform: translateY(-8px);
}
</style>
