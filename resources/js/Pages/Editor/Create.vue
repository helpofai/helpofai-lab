<script setup>
import { computed, onBeforeUnmount, onMounted, reactive, ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import MonacoPane from '@/Components/MonacoPane.vue';

const code = reactive({
    html: '<div class="hero">\n  <h1>CodepenX Live Editor</h1>\n  <p>Build experiences in real time.</p>\n</div>',
    css: `.hero {
  min-height: 100vh;
  display: grid;
  place-items: center;
  background: radial-gradient(circle at top, #0ea5e9, #020617);
  color: white;
  text-align: center;
  font-family: "Space Grotesk", sans-serif;
}
.hero h1 { font-size: clamp(2rem, 5vw, 4rem); }
.hero p { opacity: 0.75; }`,
    js: 'console.log("Ready to create!");',
});

const compiledDocument = computed(() => {
    return `
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>${code.css}</style>
</head>
<body>
${code.html}
<script>
try { ${code.js} } catch (error) { console.error(error); }
<\/script>
</body>
</html>`;
});

const editorLayoutRef = ref(null);
const previewHeight = ref(360);
const dragging = ref(false);
const device = ref('desktop');

const editorPaneStyle = computed(() => ({
    height: `calc(100% - ${previewHeight.value}px)`,
}));

const previewPaneStyle = computed(() => ({
    height: `${previewHeight.value}px`,
}));

const previewDeviceClass = computed(() => {
    switch (device.value) {
        case 'tablet':
            return 'max-w-4xl w-full';
        case 'mobile':
            return 'max-w-md w-full';
        default:
            return 'w-full';
    }
});

const startResize = () => {
    dragging.value = true;
};

const stopResize = () => {
    dragging.value = false;
};

const handleMouseMove = (event) => {
    if (!dragging.value || !editorLayoutRef.value) {
        return;
    }

    const rect = editorLayoutRef.value.getBoundingClientRect();
    const minHeight = 220;
    const maxHeight = rect.height - 260;
    const desired = rect.bottom - event.clientY;
    previewHeight.value = Math.min(Math.max(desired, minHeight), maxHeight);
};

const setDevice = (target) => {
    device.value = target;
};

onMounted(() => {
    window.addEventListener('mousemove', handleMouseMove);
    window.addEventListener('mouseup', stopResize);
});

onBeforeUnmount(() => {
    window.removeEventListener('mousemove', handleMouseMove);
    window.removeEventListener('mouseup', stopResize);
});
</script>

<template>
    <div class="min-h-screen bg-slate-950 text-slate-100">
        <Head title="Live Editor" />

<header class="flex items-center justify-between border-b border-white/10 bg-slate-900/80 px-6 py-4 backdrop-blur">
            <div class="flex items-center gap-6">
                <Link href="/" class="flex items-center gap-2 text-white">
                    <div class="rounded-xl bg-gradient-to-br from-cyan-400 to-indigo-500 px-3 py-1 text-sm font-semibold tracking-wide">
                        CodepenX
                    </div>
                    <span class="text-sm uppercase tracking-[0.4em] text-white/60">Lab</span>
                </Link>

                <div class="hidden items-center gap-3 rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-xs uppercase tracking-[0.3em] text-white/70 md:flex">
                    <span>Status: Live Preview</span>
                    <span class="size-2 rounded-full bg-emerald-400"></span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button class="hidden items-center gap-2 rounded-2xl border border-white/10 px-4 py-2 text-sm font-semibold text-white/80 hover:border-white/40 md:flex">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7.5V4.5A1.5 1.5 0 0 1 9.5 3h5A1.5 1.5 0 0 1 16 4.5v3" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 9h10l1 11H6l1-11Z" />
                    </svg>
                    Assets
                </button>

                <button class="inline-flex items-center gap-2 rounded-2xl bg-emerald-500 px-5 py-2 text-sm font-semibold text-emerald-950 shadow-lg shadow-emerald-800/40 transition hover:-translate-y-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 5v14m-7-7h14" />
                    </svg>
                    Save Pen
                </button>
            </div>
        </header>

<main ref="editorLayoutRef" class="flex h-[calc(100vh-64px)] flex-col bg-slate-950">
    <section class="flex flex-col border-b border-white/10" :style="editorPaneStyle">
        <div class="grid flex-1 grid-cols-1 gap-0 bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 lg:grid-cols-3">
            <MonacoPane
                v-model="code.html"
                language="html"
                title="HTML"
                badge-text="structure"
                badge-color="emerald"
                class="border-b border-white/5 lg:border-b-0 lg:border-r"
            />
            <MonacoPane
                v-model="code.css"
                language="css"
                title="CSS"
                badge-text="styles"
                badge-color="sky"
                class="border-b border-white/5 lg:border-b-0 lg:border-r"
            />
            <MonacoPane
                v-model="code.js"
                language="javascript"
                title="JS"
                badge-text="logic"
                badge-color="amber"
                class="border-b border-white/5 lg:border-b-0"
            />
        </div>

        <footer class="flex items-center justify-between border-t border-white/10 bg-slate-900 px-4 py-3 text-xs text-white/60">
            <div class="flex items-center gap-2">
                <span class="size-2 rounded-full bg-emerald-400"></span>
                Auto-run enabled
            </div>
            <div class="flex items-center gap-3">
                <button class="rounded-full border border-white/10 px-3 py-1 text-white/70 hover:border-white/40">Console</button>
                <button class="rounded-full border border-white/10 px-3 py-1 text-white/70 hover:border-white/40">Assets</button>
            </div>
        </footer>
    </section>

    <div class="group flex h-3 cursor-row-resize items-center justify-center bg-slate-900/80 text-white/40" @mousedown.prevent="startResize">
        <div class="h-1 w-32 rounded-full bg-white/20 group-hover:bg-white/40" />
    </div>

    <section class="flex flex-col bg-slate-950" :style="previewPaneStyle">
        <header class="flex items-center justify-between border-b border-white/10 px-6 py-3 text-xs uppercase tracking-[0.35em] text-white/60">
            <div class="flex items-center gap-3">
                <span>Preview</span>
                <span class="rounded-full bg-emerald-400/10 px-3 py-1 text-[10px] text-emerald-200">Live</span>
            </div>
            <div class="flex gap-2 text-[10px] text-white/60">
                <button
                    class="rounded-full border px-2 py-1"
                    :class="device === 'desktop' ? 'border-emerald-400 text-emerald-200' : 'border-white/10'"
                    @click="setDevice('desktop')"
                >
                    Desktop
                </button>
                <button
                    class="rounded-full border px-2 py-1"
                    :class="device === 'tablet' ? 'border-emerald-400 text-emerald-200' : 'border-white/10'"
                    @click="setDevice('tablet')"
                >
                    Tablet
                </button>
                <button
                    class="rounded-full border px-2 py-1"
                    :class="device === 'mobile' ? 'border-emerald-400 text-emerald-200' : 'border-white/10'"
                    @click="setDevice('mobile')"
                >
                    Mobile
                </button>
            </div>
        </header>
        <div class="flex flex-1 items-center justify-center bg-slate-950/90 px-4 py-6">
            <div :class="['h-full max-h-full rounded-3xl bg-white shadow-2xl shadow-slate-900/80 ring-1 ring-black/10', previewDeviceClass]">
                <iframe class="h-full w-full rounded-3xl" :srcdoc="compiledDocument" title="Live output" />
            </div>
        </div>
    </section>
</main>
    </div>
</template>

