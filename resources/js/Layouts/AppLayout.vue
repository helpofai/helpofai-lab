<script setup>
import { computed, ref } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Banner from '@/Components/Banner.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);
const sidebarCollapsed = ref(false);

const page = usePage();

const navigation = computed(() => [
    {
        name: 'Dashboard',
        href: route('dashboard'),
        active: route().current('dashboard'),
        icon: 'M3 12l9-9 9 9M4 10v10h16V10',
    },
    {
        name: 'Workbench',
        href: route('dashboard.pens.index'),
        active: route().current('dashboard.pens.*'),
        icon: 'M8 12l2-2 2 2-2 2zm6 0l2-2 2 2-2 2zM5 7h14M5 17h14',
    },
    {
        name: 'Live Editor',
        href: route('dashboard.editor.create'),
        active: route().current('dashboard.editor.create'),
        icon: 'M4 6h16v12H4zM8 18v2h8v-2',
    },
]);

const adminNavigation = computed(() => [
    {
        name: 'Users',
        href: route('dashboard'),
        icon: 'M7 8a4 4 0 1 1 10 0 4 4 0 0 1-10 0zm-2 10a6 6 0 0 1 12 0v2H5z',
    },
    {
        name: 'Front Settings',
        href: route('dashboard'),
        icon: 'M4 6h16M4 12h16M4 18h16',
    },
]);

const isAdmin = computed(() => {
    const user = page.props.auth?.user;
    if (!user) {
        return false;
    }

    if (typeof user.is_admin !== 'undefined') {
        return Boolean(user.is_admin);
    }

    return user.role?.slug === 'admin';
});

const sidebarStyle = computed(() => (sidebarCollapsed.value ? { width: '5rem' } : { width: '20%' }));

const toggleSidebar = () => {
    sidebarCollapsed.value = !sidebarCollapsed.value;
};

const switchToTeam = (team) => {
    router.put(
        route('current-team.update'),
        {
            team_id: team.id,
        },
        {
            preserveState: false,
        },
    );
};

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div class="min-h-screen bg-slate-950 text-slate-100">
        <Head :title="title" />

        <Banner />

        <div class="flex min-h-screen">
            <aside
                class="hidden border-r border-white/5 bg-slate-900/70 backdrop-blur-xl transition-all duration-300 md:flex md:flex-col"
                :class="sidebarCollapsed ? 'items-center' : ''"
                :style="sidebarStyle"
            >
                <div class="flex w-full items-center justify-between px-6 pt-8" :class="sidebarCollapsed ? 'px-4 justify-center' : 'px-6'">
                    <Link :href="route('dashboard')" class="flex items-center gap-3">
                        <ApplicationMark class="h-9 w-auto" />
                        <span v-if="!sidebarCollapsed" class="text-lg font-semibold tracking-wide text-white">
                            CodepenX
                        </span>
                    </Link>

                    <button
                        class="rounded-2xl border border-white/10 bg-white/5 p-2 text-white transition hover:border-white/30"
                        :class="sidebarCollapsed ? 'rotate-180' : ''"
                        @click="toggleSidebar"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.25 19.25 8.75 12l6.5-7.25" />
                        </svg>
                    </button>
                </div>

                <div class="mt-8 w-full px-4" v-if="!sidebarCollapsed">
                    <p class="text-xs uppercase tracking-[0.35em] text-white/50">Navigation</p>
                </div>

                <nav class="mt-4 flex w-full flex-1 flex-col gap-2 px-3">
                    <Link
                        v-for="item in navigation"
                        :key="item.name"
                        :href="item.href"
                        class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold transition"
                        :class="item.active ? 'bg-white/15 text-white shadow-inner shadow-white/10' : 'text-white/70 hover:bg-white/10 hover:text-white'"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="size-5 text-white/70 transition group-hover:text-white"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" :d="item.icon" />
                        </svg>
                        <span v-if="!sidebarCollapsed">{{ item.name }}</span>
                        <span
                            v-if="item.active && !sidebarCollapsed"
                            class="ms-auto text-[10px] uppercase tracking-widest text-emerald-300"
                        >
                            Live
                        </span>
                    </Link>
                </nav>

                <div v-if="isAdmin" class="w-full px-4 pb-6">
                    <div v-if="!sidebarCollapsed" class="mb-3 text-xs uppercase tracking-[0.35em] text-white/50">
                        Admin Control
                    </div>
                    <nav class="space-y-2">
                        <Link
                            v-for="item in adminNavigation"
                            :key="item.name"
                            :href="item.href"
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-white/70 transition hover:bg-white/10 hover:text-white"
                            :class="sidebarCollapsed ? 'justify-center px-3' : ''"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" :d="item.icon" />
                            </svg>
                            <span v-if="!sidebarCollapsed">{{ item.name }}</span>
                        </Link>
                    </nav>
                </div>

                <div class="w-full px-4 pb-6">
                    <div class="rounded-3xl bg-gradient-to-br from-indigo-600/90 to-purple-500/90 p-4 text-sm text-white shadow-2xl shadow-indigo-900/50">
                        <div class="flex items-center gap-3" :class="sidebarCollapsed ? 'justify-center' : ''">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-white/90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 17.25 7.5 20l1-5.5L5 10.75l5.6-.81L12 5l1.4 4.94 5.6.81-3.5 3.75 1 5.5z" />
                            </svg>
                            <div v-if="!sidebarCollapsed">
                                <p class="text-xs uppercase tracking-widest text-white/70">Premium Workspace</p>
                                <p class="mt-1 text-base font-semibold">Unlock collaboration</p>
                                <p class="mt-1 text-white/80">
                                    Invite reviewers, share assets, export cinematic embeds, and monitor performance in real time.
                                </p>
                                <button
                                    class="mt-4 inline-flex items-center rounded-full bg-white px-4 py-1 text-xs font-bold text-indigo-600 transition hover:scale-105"
                                >
                                    Upgrade
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            <div class="flex flex-1 flex-col">
                <header class="border-b border-white/5 bg-slate-900/40 backdrop-blur-xl">
                    <div class="flex items-center justify-between px-4 py-4 sm:px-6">
                        <div class="flex items-center gap-3">
                            <button class="rounded-2xl border border-white/10 p-2 text-white md:hidden" @click="showingNavigationDropdown = true">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7h16M4 12h16M4 17h16" />
                                </svg>
                            </button>

                            <div class="hidden items-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-2 sm:flex">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-white/60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m21 21-4.35-4.35M5 11a6 6 0 1 1 12 0 6 6 0 0 1-12 0z" />
                                </svg>
                                <input
                                    type="text"
                                    placeholder="Quick search or jump to a pen"
                                    class="w-52 bg-transparent text-sm text-white placeholder-white/60 focus:outline-none"
                                />
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <Link
                                :href="route('dashboard.editor.create')"
                                class="hidden items-center gap-2 rounded-2xl border border-white/10 bg-indigo-600/90 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-indigo-900/50 transition hover:bg-indigo-500 md:inline-flex"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 5v14m-7-7h14" />
                                </svg>
                                New Pen
                            </Link>

                            <div class="hidden sm:flex sm:items-center sm:gap-4">
                                <div class="relative">
                                <Dropdown v-if="$page.props.jetstream.hasTeamFeatures" align="right" width="60">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md shadow-sm">
                                            <button
                                                type="button"
                                                class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-3 py-2 text-sm font-medium text-white hover:border-white/40 focus:outline-none focus:ring"
                                            >
                                                {{ $page.props.auth.user.current_team?.name ?? 'Teams' }}

                                                    <svg class="ms-1 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                                    </svg>
                                                </button>
                                            </span>
                                        </template>

                                        <template #content>
                                            <div class="w-60">
                                                <div class="px-4 py-2 text-xs uppercase tracking-widest text-slate-400">
                                                    Manage Team
                                                </div>

                                                <DropdownLink v-if="$page.props.auth.user.current_team" :href="route('teams.show', $page.props.auth.user.current_team)">
                                                    Team Settings
                                                </DropdownLink>

                                                <DropdownLink v-if="$page.props.jetstream.canCreateTeams" :href="route('teams.create')">
                                                    Create New Team
                                                </DropdownLink>

                                                <template v-if="$page.props.auth.user.all_teams.length > 1">
                                                    <div class="border-t border-slate-700/60" />

                                                    <div class="px-4 py-2 text-xs uppercase tracking-widest text-slate-400">
                                                        Switch Teams
                                                    </div>

                                                    <template v-for="team in $page.props.auth.user.all_teams" :key="team.id">
                                                        <form @submit.prevent="switchToTeam(team)">
                                                            <DropdownLink as="button">
                                                                <div class="flex items-center gap-2">
                                                                    <svg
                                                                        v-if="team.id === $page.props.auth.user.current_team_id"
                                                                        class="size-4 text-emerald-400"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        fill="none"
                                                                        viewBox="0 0 24 24"
                                                                        stroke-width="1.5"
                                                                        stroke="currentColor"
                                                                    >
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                                                    </svg>

                                                                    <span>{{ team.name }}</span>
                                                                </div>
                                                            </DropdownLink>
                                                        </form>
                                                    </template>
                                                </template>
                                            </div>
                                        </template>
                                    </Dropdown>
                                </div>

                                <div class="relative">
                                    <Dropdown align="right" width="48">
                                        <template #trigger>
                                            <button
                                                v-if="$page.props.jetstream.managesProfilePhotos"
                                                class="flex rounded-full border-2 border-transparent focus:border-indigo-500 focus:outline-none"
                                            >
                                                <img class="size-9 rounded-full object-cover" :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name">
                                            </button>

                                            <span v-else class="inline-flex rounded-md">
                                                <button
                                                    type="button"
                                                    class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-3 py-2 text-sm font-medium text-white hover:border-white/40 focus:outline-none focus:ring"
                                                >
                                                    {{ $page.props.auth.user.name }}

                                                    <svg class="ms-1 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                                    </svg>
                                                </button>
                                            </span>
                                        </template>

                                        <template #content>
                                            <div class="block px-4 py-2 text-xs uppercase tracking-widest text-slate-400">
                                                Manage Account
                                            </div>

                                            <DropdownLink :href="route('profile.show')">
                                                Profile
                                            </DropdownLink>

                                            <DropdownLink v-if="$page.props.jetstream.hasApiFeatures" :href="route('api-tokens.index')">
                                                API Tokens
                                            </DropdownLink>

                                            <div class="border-t border-slate-700/60" />

                                            <form @submit.prevent="logout">
                                                <DropdownLink as="button">
                                                    Log Out
                                                </DropdownLink>
                                            </form>
                                        </template>
                                    </Dropdown>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <main class="flex-1 overflow-y-auto">
                    <section v-if="$slots.header" class="px-4 pt-6 sm:px-6">
                        <div class="max-w-7xl">
                            <slot name="header" />
                        </div>
                    </section>

                    <section class="px-4 pb-10 pt-4 sm:px-6">
                        <div class="max-w-7xl">
                            <slot />
                        </div>
                    </section>
                </main>
            </div>
        </div>

        <transition
            enter-active-class="duration-200 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="duration-150 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="showingNavigationDropdown" class="fixed inset-0 z-40 flex md:hidden">
                <div class="flex-1 bg-black/70" @click="showingNavigationDropdown = false" />

                <div class="w-72 bg-slate-900/95 p-6 text-white">
                    <div class="flex items-center justify-between">
                        <p class="text-lg font-semibold">Navigate</p>
                        <button class="text-white/70 transition hover:text-white" @click="showingNavigationDropdown = false">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m6 6 12 12M6 18 18 6" />
                            </svg>
                        </button>
                    </div>

                    <div class="mt-6 space-y-2">
                        <Link
                            v-for="item in navigation"
                            :key="`mobile-${item.name}`"
                            :href="item.href"
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold transition"
                            :class="item.active ? 'bg-white/10 text-white' : 'text-white/70 hover:bg-white/10'"
                            @click="showingNavigationDropdown = false"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" :d="item.icon" />
                            </svg>
                            <span>{{ item.name }}</span>
                        </Link>
                    </div>

                    <div class="mt-8 border-t border-white/10 pt-6 space-y-2">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Dashboard
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('dashboard.pens.index')" :active="route().current('dashboard.pens.*')">
                            Pens
                        </ResponsiveNavLink>
                    </div>

                    <div v-if="isAdmin" class="mt-8 border-t border-white/10 pt-6">
                        <p class="text-xs uppercase tracking-[0.35em] text-white/50">Admin Control</p>
                        <Link
                            v-for="item in adminNavigation"
                            :key="`mobile-admin-${item.name}`"
                            :href="item.href"
                            class="mt-3 flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-white/70 hover:bg-white/10"
                            @click="showingNavigationDropdown = false"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" :d="item.icon" />
                            </svg>
                            <span>{{ item.name }}</span>
                        </Link>
                    </div>

                    <div class="mt-6 space-y-2">
                        <ResponsiveNavLink :href="route('profile.show')" :active="route().current('profile.show')">
                            Profile
                        </ResponsiveNavLink>

                        <ResponsiveNavLink v-if="$page.props.jetstream.hasApiFeatures" :href="route('api-tokens.index')" :active="route().current('api-tokens.index')">
                            API Tokens
                        </ResponsiveNavLink>

                        <form method="POST" @submit.prevent="logout">
                            <ResponsiveNavLink as="button">
                                Log Out
                            </ResponsiveNavLink>
                        </form>
                    </div>

                            <template v-if="$page.props.jetstream.hasTeamFeatures">
                                <div class="mt-6 border-t border-white/10 pt-6">
                                    <p class="text-xs uppercase tracking-widest text-white/60">Manage Team</p>

                                    <ResponsiveNavLink v-if="$page.props.auth.user.current_team" :href="route('teams.show', $page.props.auth.user.current_team)" :active="route().current('teams.show')">
                                        Team Settings
                                    </ResponsiveNavLink>

                            <ResponsiveNavLink
                                v-if="$page.props.jetstream.canCreateTeams"
                                :href="route('teams.create')"
                                :active="route().current('teams.create')"
                            >
                                Create New Team
                            </ResponsiveNavLink>

                            <template v-if="$page.props.auth.user.all_teams.length > 1">
                                <div class="mt-4 border-t border-white/10 pt-4">
                                    <p class="text-xs uppercase tracking-widest text-white/60">Switch Teams</p>
                                    <template v-for="team in $page.props.auth.user.all_teams" :key="team.id">
                                        <form @submit.prevent="switchToTeam(team)">
                                            <ResponsiveNavLink as="button">
                                                <div class="flex items-center gap-2">
                                                    <svg
                                                        v-if="team.id === $page.props.auth.user.current_team_id"
                                                        class="size-4 text-emerald-400"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke-width="1.5"
                                                        stroke="currentColor"
                                                    >
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                                    </svg>
                                                    <span>{{ team.name }}</span>
                                                </div>
                                            </ResponsiveNavLink>
                                        </form>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </transition>
    </div>
</template>
