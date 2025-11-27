<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    pens: {
        type: Object,
        required: true,
    },
});

const buildPreview = (pen) => {
    const css = pen.css ?? '';
    const html = pen.html ?? '';
    const js = pen.js ?? '';

    return `
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>${css}</style>
</head>
<body>
${html}
<script>
try { ${js} } catch (error) { console.error(error); }
<\/script>
</body>
</html>`;
};
</script>

<template>
    <AppLayout title="Pens">
        <template #header>
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm uppercase tracking-[0.35em] text-white/60">
                        Marketplace
                    </p>
                    <h2 class="text-3xl font-semibold text-white">
                        Your Projects
                    </h2>
                </div>
                <Link
                    :href="route('dashboard.editor.create')"
                    class="inline-flex items-center gap-2 rounded-2xl bg-emerald-500 px-5 py-2 text-sm font-semibold text-emerald-950 shadow-lg shadow-emerald-900/40 transition hover:-translate-y-0.5"
                >
                    + New Project
                </Link>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto space-y-8 sm:px-6 lg:px-8">
                <div v-if="props.pens.data.length" class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                    <article
                        v-for="pen in props.pens.data"
                        :key="pen.id"
                        class="group flex flex-col overflow-hidden rounded-3xl border border-white/10 bg-white/5 text-white shadow-2xl shadow-slate-950/30 backdrop-blur"
                    >
                        <div class="relative aspect-video bg-slate-950/70">
                            <iframe
                                class="h-full w-full border-0"
                                :srcdoc="buildPreview(pen)"
                                :title="`${pen.title} preview`"
                                referrerpolicy="no-referrer"
                                loading="lazy"
                                :class="[
                                    'transition-transform duration-700 ease-out',
                                    'group-hover:scale-105 group-hover:rotate-1',
                                ]"
                            />
                            <div class="absolute inset-0 pointer-events-none bg-gradient-to-t from-slate-950/40 to-transparent" />
                        </div>
                        <div class="space-y-4 p-5">
                            <div>
                                <p class="text-xs uppercase tracking-[0.4em] text-white/50">
                                    {{ pen.visibility }} • {{ pen.updated_at }}
                                </p>
                                <h3 class="mt-1 text-xl font-semibold">
                                    {{ pen.title }}
                                </h3>
                            </div>
                            <div class="flex items-center gap-3">
                                <span
                                    class="rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-widest"
                                    :class="pen.status === 'published' ? 'bg-emerald-400/10 text-emerald-200' : 'bg-amber-400/10 text-amber-200'"
                                >
                                    {{ pen.status }}
                                </span>
                                <span class="text-xs text-white/60">
                                    Preview available
                                </span>
                            </div>
                            <div class="flex gap-3">
                                <Link
                                    :href="route('dashboard.pens.show', pen.id)"
                                    class="inline-flex flex-1 items-center justify-center rounded-2xl border border-white/30 px-4 py-2 text-sm font-semibold text-white/90 hover:border-white/60"
                                >
                                    Show Preview
                                </Link>
                                <button class="inline-flex flex-1 items-center justify-center rounded-2xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-emerald-950 shadow-lg shadow-emerald-900/40">
                                    Buy Access
                                </button>
                            </div>
                            <Link
                                :href="route('dashboard.pens.edit', pen.id)"
                                class="block text-center text-sm font-semibold text-indigo-200 hover:text-white"
                            >
                                Edit Project
                            </Link>
                        </div>
                    </article>
                </div>

                <div v-else class="rounded-3xl border border-white/10 bg-white/5 px-6 py-16 text-center text-white/70">
                    No projects yet. Kick things off with a new pen!
                </div>

                <div v-if="props.pens.links?.length" class="flex flex-wrap items-center justify-between gap-4 border border-white/10 bg-white/5 px-6 py-4 text-sm text-white/80 rounded-3xl">
                    <p>
                        Showing {{ props.pens.data.length }} of {{ props.pens.total }} pens
                    </p>
                    <div class="flex flex-wrap gap-2">
                        <Link
                            v-for="link in props.pens.links"
                            :key="link.url ?? link.label"
                            :href="link.url ?? '#'"
                            v-html="link.label"
                            class="rounded-full px-3 py-1 font-medium transition"
                            :class="[
                                link.active ? 'bg-emerald-500 text-emerald-950' : 'bg-white/10 text-white/70 hover:bg-white/20',
                                !link.url ? 'pointer-events-none opacity-40' : '',
                            ]"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
