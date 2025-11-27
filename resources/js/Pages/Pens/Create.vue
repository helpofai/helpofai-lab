<script setup>
import { onMounted } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import MonacoPane from '@/Components/MonacoPane.vue';

const props = defineProps({
    defaults: {
        type: Object,
        default: () => ({}),
    },
});

const clone = (value) => JSON.parse(JSON.stringify(value));

const form = useForm({
    title: props.defaults.title ?? 'Untitled Pen',
    description: '',
    slug: '',
    html: props.defaults.html ?? '',
    css: props.defaults.css ?? '',
    js: props.defaults.js ?? '',
    settings: clone(props.defaults.settings ?? { resources: { css: [], js: [] } }),
    visibility: props.defaults.visibility ?? 'public',
    status: props.defaults.status ?? 'draft',
});

const ensureSettingsShape = () => {
    if (!form.settings || typeof form.settings !== 'object') {
        form.settings = { resources: { css: [], js: [] } };
    }

    if (!form.settings.resources) {
        form.settings.resources = { css: [], js: [] };
    }

    form.settings.resources.css = form.settings.resources.css ?? [];
    form.settings.resources.js = form.settings.resources.js ?? [];
};

onMounted(() => ensureSettingsShape());

const addResource = (type) => {
    ensureSettingsShape();
    form.settings.resources[type].push('');
};

const removeResource = (type, index) => {
    ensureSettingsShape();
    form.settings.resources[type].splice(index, 1);
};

const submit = () => {
    ensureSettingsShape();
    form.post(route('dashboard.pens.store'));
};
</script>

<template>
    <AppLayout title="Create Pen">
        <template #header>
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm uppercase tracking-[0.4em] text-white/60">
                        Workspace - Live
                    </p>
                    <h2 class="text-3xl font-semibold text-white">
                        {{ form.title || 'Untitled Pen' }}
                    </h2>
                    <p class="text-sm text-white/70">
                        HTML / CSS / JS playground with instant preview.
                    </p>
                </div>
                <Link
                    :href="route('dashboard.pens.index')"
                    class="inline-flex items-center gap-2 rounded-2xl border border-white/20 px-4 py-2 text-sm font-semibold text-white/80 hover:border-white/60"
                >
                    Back to list
                </Link>
            </div>
        </template>

        <div class="space-y-6 py-8">
            <form @submit.prevent="submit" class="space-y-8">
                <section class="rounded-3xl border border-white/10 bg-white/5 p-6 text-white shadow-2xl shadow-slate-900/30">
                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <InputLabel for="title" value="Title" class="text-white/70" />
                            <TextInput id="title" v-model="form.title" type="text" class="mt-1 block w-full border-white/20 bg-white/5 text-white" required autofocus />
                            <InputError :message="form.errors.title" class="mt-2" />
                        </div>
                        <div>
                            <InputLabel for="slug" value="Slug (optional)" class="text-white/70" />
                            <TextInput
                                id="slug"
                                v-model="form.slug"
                                type="text"
                                class="mt-1 block w-full border-white/20 bg-white/5 text-white"
                                placeholder="my-awesome-pen"
                            />
                            <InputError :message="form.errors.slug" class="mt-2" />
                        </div>
                        <div class="md:col-span-2">
                            <InputLabel for="description" value="Description" class="text-white/70" />
                            <textarea
                                id="description"
                                v-model="form.description"
                                rows="3"
                                class="mt-1 block w-full rounded-2xl border border-white/20 bg-white/5 p-3 text-white focus:border-emerald-400 focus:ring-emerald-400"
                            />
                            <InputError :message="form.errors.description" class="mt-2" />
                        </div>
                    </div>

                    <div class="mt-6 grid gap-6 md:grid-cols-2">
                        <div>
                            <InputLabel value="Visibility" class="text-white/70" />
                            <div class="mt-2 flex flex-wrap gap-3">
                                <label
                                    class="flex cursor-pointer items-center gap-2 rounded-2xl border px-4 py-2 text-sm"
                                    :class="form.visibility === 'public'
                                        ? 'border-emerald-400 bg-emerald-400/10 text-emerald-200'
                                        : 'border-white/10 text-white/70'"
                                >
                                    <input v-model="form.visibility" value="public" type="radio" class="text-emerald-500 focus:ring-emerald-500" />
                                    Public
                                </label>
                                <label
                                    class="flex cursor-pointer items-center gap-2 rounded-2xl border px-4 py-2 text-sm"
                                    :class="form.visibility === 'private'
                                        ? 'border-indigo-400 bg-indigo-400/10 text-indigo-200'
                                        : 'border-white/10 text-white/70'"
                                >
                                    <input v-model="form.visibility" value="private" type="radio" class="text-indigo-500 focus:ring-indigo-500" />
                                    Private
                                </label>
                                <label
                                    class="flex cursor-pointer items-center gap-2 rounded-2xl border px-4 py-2 text-sm"
                                    :class="form.visibility === 'unlisted'
                                        ? 'border-amber-400 bg-amber-400/10 text-amber-200'
                                        : 'border-white/10 text-white/70'"
                                >
                                    <input v-model="form.visibility" value="unlisted" type="radio" class="text-amber-500 focus:ring-amber-500" />
                                    Unlisted
                                </label>
                            </div>
                            <InputError :message="form.errors.visibility" class="mt-2" />
                        </div>
                        <div>
                            <InputLabel value="Status" class="text-white/70" />
                            <select
                                v-model="form.status"
                                class="mt-2 block w-full rounded-2xl border border-white/20 bg-white/5 py-2 text-white focus:border-emerald-400 focus:ring-emerald-400"
                            >
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                            </select>
                            <InputError :message="form.errors.status" class="mt-2" />
                        </div>
                    </div>
                </section>

                <section class="rounded-3xl border border-white/10 bg-slate-900 shadow-2xl shadow-slate-900/40">
                    <div class="grid flex-1 grid-cols-1 gap-0 bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 lg:grid-cols-3">
                        <MonacoPane
                            v-model="form.html"
                            language="html"
                            title="HTML"
                            badge-text="structure"
                            badge-color="emerald"
                            class="border-b border-white/5 lg:border-b-0 lg:border-r"
                        />
                        <MonacoPane
                            v-model="form.css"
                            language="css"
                            title="CSS"
                            badge-text="styles"
                            badge-color="sky"
                            class="border-b border-white/5 lg:border-b-0 lg:border-r"
                        />
                        <MonacoPane
                            v-model="form.js"
                            language="javascript"
                            title="JS"
                            badge-text="logic"
                            badge-color="amber"
                            class="border-b border-white/5 lg:border-b-0"
                        />
                    </div>
                    <div class="border-t border-white/10 bg-black/80">
                        <header class="flex items-center justify-between px-4 py-2 text-xs uppercase tracking-[0.3em] text-white/60">
                            <span>Preview</span>
                            <span class="rounded-full bg-white/10 px-3 py-1 text-[10px] text-white/50">Live</span>
                        </header>
                        <iframe class="h-[320px] w-full bg-white" :srcdoc="`
<!doctype html>
<html lang=&quot;en&quot;>
<head>
<meta charset=&quot;utf-8&quot;/>
<meta name=&quot;viewport&quot; content=&quot;width=device-width,initial-scale=1&quot;/>
<style>${form.css ?? ''}</style>
</head>
<body>
${form.html ?? ''}
<script>
try { ${form.js ?? ''} } catch (error) { console.error(error); }
<\/script>
</body>
</html>`" title="Live preview" />
                    </div>
                </section>

                <section class="rounded-3xl border border-white/10 bg-white/5 p-6 text-white shadow-2xl shadow-slate-900/30">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold">External resources</h3>
                            <p class="text-sm text-white/60">Bring in third-party CSS or JS files.</p>
                        </div>
                        <div class="flex gap-2 text-sm font-semibold text-emerald-300">
                            <button type="button" @click="addResource('css')">
                                + CSS
                            </button>
                            <button type="button" @click="addResource('js')">
                                + JS
                            </button>
                        </div>
                    </div>

                    <div class="mt-6 grid gap-6 md:grid-cols-2">
                        <div>
                            <p class="text-xs font-semibold uppercase text-white/50">Stylesheets</p>
                            <div v-if="form.settings?.resources?.css?.length" class="mt-3 space-y-3">
                                <div
                                    v-for="(url, index) in form.settings.resources.css"
                                    :key="`css-${index}`"
                                    class="flex items-center gap-2"
                                >
                                    <TextInput
                                        v-model="form.settings.resources.css[index]"
                                        type="url"
                                        class="flex-1 border-white/20 bg-white/5 text-white"
                                        placeholder="https://cdn.example.com/library.css"
                                    />
                                    <button type="button" class="text-white/50 hover:text-red-400" @click="removeResource('css', index)">
                                        &times;
                                    </button>
                                </div>
                            </div>
                            <p v-else class="mt-3 text-sm text-white/50">
                                No external CSS resources attached.
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold uppercase text-white/50">Scripts</p>
                            <div v-if="form.settings?.resources?.js?.length" class="mt-3 space-y-3">
                                <div
                                    v-for="(url, index) in form.settings.resources.js"
                                    :key="`js-${index}`"
                                    class="flex items-center gap-2"
                                >
                                    <TextInput
                                        v-model="form.settings.resources.js[index]"
                                        type="url"
                                        class="flex-1 border-white/20 bg-white/5 text-white"
                                        placeholder="https://cdn.example.com/library.js"
                                    />
                                    <button type="button" class="text-white/50 hover:text-red-400" @click="removeResource('js', index)">
                                        &times;
                                    </button>
                                </div>
                            </div>
                            <p v-else class="mt-3 text-sm text-white/50">
                                No external JavaScript resources attached.
                            </p>
                        </div>
                    </div>
                </section>

                <div class="flex flex-wrap items-center justify-end gap-4">
                    <Link :href="route('dashboard.pens.index')" class="text-sm font-medium text-white/70 hover:text-white">
                        Cancel
                    </Link>
                    <PrimaryButton :class="{ 'opacity-50': form.processing }" :disabled="form.processing">
                        Save Pen
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
