<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    recentPens: {
        type: Array,
        default: () => [],
    },
    collections: {
        type: Array,
        default: () => [],
    },
    stats: {
        type: Object,
        default: () => ({
            pens_total: 0,
            pens_published: 0,
            collections_total: 0,
        }),
    },
});

const publishedPercent = computed(() => {
    if (!props.stats.pens_total) {
        return 0;
    }

    return Math.round((props.stats.pens_published / props.stats.pens_total) * 100);
});

const draftCount = computed(() => Math.max(props.stats.pens_total - props.stats.pens_published, 0));

const collectionCoverage = computed(() => {
    if (!props.stats.pens_total || !props.stats.collections_total) {
        return 0;
    }

    return Math.min(100, Math.round((props.stats.collections_total / props.stats.pens_total) * 100));
});

const featuredCollections = computed(() => props.collections.slice(0, 3));
const timeline = computed(() => props.recentPens.slice(0, 5));
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <div class="rounded-3xl bg-gradient-to-br from-slate-900 via-indigo-900/80 to-purple-900/70 p-8 text-white shadow-2xl">
                <div class="flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">
                    <div class="space-y-4">
                        <p class="text-xs uppercase tracking-[0.35em] text-white/60">Premium workspace</p>
                        <h2 class="text-3xl font-semibold sm:text-4xl">
                            Welcome back, {{ $page.props.auth.user.name }}!
                        </h2>
                        <p class="text-sm text-white/70 sm:max-w-xl">
                            Continue exactly where you left off, review team activity at a glance, and fast-launch a new experiment with your preferred stack.
                        </p>
                        <div class="flex flex-wrap gap-3">
                            <Link
                                :href="route('dashboard.editor.create')"
                                class="inline-flex items-center gap-2 rounded-2xl bg-white px-5 py-2 text-sm font-semibold text-slate-900 transition hover:-translate-y-px"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 5v14m-7-7h14" />
                                </svg>
                                Launch live editor
                            </Link>
                            <Link
                                :href="route('dashboard.pens.index')"
                                class="inline-flex items-center gap-2 rounded-2xl border border-white/30 px-5 py-2 text-sm font-semibold text-white/90 transition hover:border-white/60"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h10" />
                                </svg>
                                Browse pens
                            </Link>
                        </div>
                    </div>

                    <div class="grid w-full gap-4 sm:grid-cols-3 lg:w-auto">
                        <div class="rounded-2xl border border-white/20 bg-white/5 p-4 text-center">
                            <p class="text-xs uppercase tracking-widest text-white/60">Pens</p>
                            <p class="mt-2 text-3xl font-semibold">{{ props.stats.pens_total }}</p>
                            <p class="text-xs text-emerald-300/80">{{ publishedPercent }}% published</p>
                        </div>
                        <div class="rounded-2xl border border-white/20 bg-white/5 p-4 text-center">
                            <p class="text-xs uppercase tracking-widest text-white/60">Drafts</p>
                            <p class="mt-2 text-3xl font-semibold text-amber-300">{{ draftCount }}</p>
                            <p class="text-xs text-white/70">finishing touches</p>
                        </div>
                        <div class="rounded-2xl border border-white/20 bg-white/5 p-4 text-center">
                            <p class="text-xs uppercase tracking-widest text-white/60">Collections</p>
                            <p class="mt-2 text-3xl font-semibold text-indigo-200">{{ props.stats.collections_total }}</p>
                            <p class="text-xs text-white/70">{{ collectionCoverage }}% coverage</p>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <div class="space-y-8 py-8">
            <section class="grid gap-6 lg:grid-cols-3">
                <div class="rounded-3xl border border-white/5 bg-white/5 p-6 text-white shadow-xl shadow-slate-900/20 lg:col-span-2">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.4em] text-white/60">Creative pulse</p>
                            <h3 class="mt-2 text-xl font-semibold">Studio performance</h3>
                        </div>
                        <span class="rounded-full bg-emerald-400/10 px-3 py-1 text-xs font-semibold text-emerald-200">
                            {{ publishedPercent }}% shipping rate
                        </span>
                    </div>

                    <div class="mt-6 space-y-5">
                        <div>
                            <div class="flex items-center justify-between text-xs uppercase tracking-widest text-white/60">
                                <span>Publishing velocity</span>
                                <span>{{ publishedPercent }}%</span>
                            </div>
                            <div class="mt-2 h-2 rounded-full bg-white/10">
                                <div class="h-full rounded-full bg-gradient-to-r from-emerald-400 to-cyan-400" :style="{ width: `${publishedPercent}%` }" />
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between text-xs uppercase tracking-widest text-white/60">
                                <span>Draft queue</span>
                                <span>{{ draftCount }} pens</span>
                            </div>
                            <div class="mt-2 h-2 rounded-full bg-white/10">
                                <div
                                    class="h-full rounded-full bg-gradient-to-r from-amber-400 to-rose-400"
                                    :style="{ width: `${Math.min(100, draftCount * 12)}%` }"
                                />
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between text-xs uppercase tracking-widest text-white/60">
                                <span>Collection coverage</span>
                                <span>{{ collectionCoverage }}%</span>
                            </div>
                            <div class="mt-2 h-2 rounded-full bg-white/10">
                                <div class="h-full rounded-full bg-gradient-to-r from-indigo-400 to-violet-400" :style="{ width: `${collectionCoverage}%` }" />
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 grid gap-4 sm:grid-cols-3">
                        <div class="rounded-2xl border border-white/5 bg-white/5 p-4 text-sm">
                            <p class="text-white/60">Avg. review cycle</p>
                            <p class="mt-2 text-2xl font-semibold">3.2 hrs</p>
                            <p class="text-xs text-emerald-300">+12% faster week over week</p>
                        </div>
                        <div class="rounded-2xl border border-white/5 bg-white/5 p-4 text-sm">
                            <p class="text-white/60">Stack focus</p>
                            <p class="mt-2 text-2xl font-semibold">Vue · Tailwind</p>
                            <p class="text-xs text-white/70">most used in last 7 pens</p>
                        </div>
                        <div class="rounded-2xl border border-white/5 bg-white/5 p-4 text-sm">
                            <p class="text-white/60">Collab slots</p>
                            <p class="mt-2 text-2xl font-semibold">2 / 5</p>
                            <p class="text-xs text-white/70">available this week</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl border border-white/5 bg-gradient-to-b from-slate-900/70 to-slate-900/30 p-6 text-white shadow-xl shadow-slate-900/20">
                    <p class="text-xs uppercase tracking-[0.4em] text-white/50">Signal feed</p>
                    <h3 class="mt-2 text-xl font-semibold">Active timeline</h3>
                    <p class="text-sm text-white/70">
                        Track the five most recent edits, commits, or publish events.
                    </p>

                    <div class="mt-6 space-y-4">
                        <template v-if="timeline.length">
                            <div
                                v-for="pen in timeline"
                                :key="pen.id"
                                class="flex items-start gap-3 rounded-2xl border border-white/5 bg-white/5 p-4"
                            >
                                <div class="rounded-full bg-gradient-to-br from-indigo-400 to-cyan-400 p-2 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h10" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between gap-2 text-xs text-white/60">
                                        <span class="capitalize">{{ pen.visibility }}</span>
                                        <span>{{ pen.updated_at || 'Just now' }}</span>
                                    </div>
                                    <p class="mt-1 font-semibold text-white">{{ pen.title }}</p>
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        <span
                                            class="rounded-full px-2 py-0.5 text-[10px] font-semibold uppercase tracking-widest"
                                            :class="pen.status === 'published' ? 'bg-emerald-400/10 text-emerald-200' : 'bg-amber-400/10 text-amber-200'"
                                        >
                                            {{ pen.status }}
                                        </span>
                                        <span
                                            v-for="collection in pen.collections"
                                            :key="collection.id"
                                            class="rounded-full bg-white/10 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-widest text-white/70"
                                        >
                                            {{ collection.name }}
                                        </span>
                                    </div>
                                </div>
                                <Link :href="route('dashboard.pens.edit', pen.id)" class="text-xs font-semibold text-indigo-200 hover:text-white">
                                    Edit
                                </Link>
                            </div>
                        </template>
                        <p v-else class="rounded-2xl border border-dashed border-white/10 px-4 py-10 text-center text-sm text-white/60">
                            No activity yet. Craft your first pen to spark this timeline.
                        </p>
                    </div>
                </div>
            </section>

            <section class="grid gap-6 lg:grid-cols-3">
                <div class="rounded-3xl border border-white/5 bg-white/5 p-6 text-white shadow-xl shadow-slate-900/20">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.4em] text-white/60">Collections</p>
                            <h3 class="mt-2 text-xl font-semibold">Curated series</h3>
                        </div>
                        <Link :href="route('dashboard.editor.create')" class="text-xs font-semibold text-indigo-200 hover:text-white">
                            Add pen
                        </Link>
                    </div>

                    <div class="mt-6 space-y-4">
                        <template v-if="featuredCollections.length">
                            <article
                                v-for="collection in featuredCollections"
                                :key="collection.id"
                                class="rounded-2xl border border-white/5 bg-white/5 p-4"
                            >
                                <div class="flex items-center justify-between">
                                    <p class="font-semibold text-white">{{ collection.name }}</p>
                                    <span
                                        class="rounded-full px-3 py-1 text-[10px] font-semibold uppercase tracking-widest"
                                        :class="collection.is_private ? 'bg-white/10 text-white/70' : 'bg-emerald-400/10 text-emerald-200'"
                                    >
                                        {{ collection.is_private ? 'Private' : 'Public' }}
                                    </span>
                                </div>
                                <p class="mt-2 text-sm text-white/70">Contains {{ collection.pens_count }} pens</p>
                                <p class="text-xs text-white/40">Created {{ collection.created_at }}</p>
                            </article>
                        </template>
                        <p v-else class="rounded-2xl border border-dashed border-white/10 px-4 py-10 text-center text-sm text-white/60">
                            Start grouping pens into curated sets for showcases or clients.
                        </p>
                    </div>
                </div>

                <div class="rounded-3xl border border-white/5 bg-gradient-to-b from-indigo-500/20 to-slate-900/40 p-6 text-white shadow-xl shadow-slate-900/20">
                    <p class="text-xs uppercase tracking-[0.4em] text-white/60">Launch checklist</p>
                    <h3 class="mt-2 text-xl font-semibold">Next up</h3>
                    <ul class="mt-6 space-y-4 text-sm">
                        <li class="flex items-start gap-3">
                            <span class="mt-1 size-2 rounded-full bg-emerald-300"></span>
                            <div>
                                <p class="font-semibold">Schedule publish window</p>
                                <p class="text-white/60">Pick the optimal visibility moment for your highlighted pen.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-1 size-2 rounded-full bg-sky-300"></span>
                            <div>
                                <p class="font-semibold">Sync assets</p>
                                <p class="text-white/60">Upload hero thumbnails and shared scripts into the asset locker.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-1 size-2 rounded-full bg-violet-300"></span>
                            <div>
                                <p class="font-semibold">Invite collaborator</p>
                                <p class="text-white/60">Share edit access or request a code review before launch.</p>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="rounded-3xl border border-white/5 bg-white/5 p-6 text-white shadow-xl shadow-slate-900/20">
                    <p class="text-xs uppercase tracking-[0.4em] text-white/60">Focus Mode</p>
                    <h3 class="mt-2 text-xl font-semibold">Personal score</h3>
                    <div class="mt-6 space-y-4 text-sm">
                        <div class="rounded-2xl border border-white/5 bg-white/5 p-4">
                            <p class="text-white/60">Deep work sessions</p>
                            <p class="mt-2 text-2xl font-semibold">09</p>
                            <p class="text-xs text-white/60">this week</p>
                        </div>
                        <div class="rounded-2xl border border-white/5 bg-white/5 p-4">
                            <p class="text-white/60">Average pen length</p>
                            <p class="mt-2 text-2xl font-semibold">148 lines</p>
                            <p class="text-xs text-white/60">across latest 5 pens</p>
                        </div>
                        <div class="rounded-2xl border border-white/5 bg-white/5 p-4">
                            <p class="text-white/60">Saved themes</p>
                            <p class="mt-2 text-2xl font-semibold">03</p>
                            <p class="text-xs text-white/60">ready to deploy</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </AppLayout>
</template>
