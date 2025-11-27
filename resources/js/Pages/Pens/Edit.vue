<script setup>
import { onMounted } from 'vue';
import { useForm, router, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PenEditor from '@/Components/PenEditor.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    pen: {
        type: Object,
        required: true,
    },
});

const clone = (value) => JSON.parse(JSON.stringify(value ?? { resources: { css: [], js: [] } }));

const form = useForm({
    title: props.pen.title ?? 'Untitled Pen',
    description: props.pen.description ?? '',
    slug: props.pen.slug ?? '',
    html: props.pen.html ?? '',
    css: props.pen.css ?? '',
    js: props.pen.js ?? '',
    settings: clone(props.pen.settings),
    visibility: props.pen.visibility ?? 'public',
    status: props.pen.status ?? 'draft',
});

const ensureSettingsShape = () => {
    if (!form.settings || typeof form.settings !== 'object') {
        form.settings = { resources: { css: [], js: [] } };
    }

    form.settings.resources = form.settings.resources ?? { css: [], js: [] };
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

const updatePen = () => {
    ensureSettingsShape();
    form.put(route('dashboard.pens.update', props.pen.id));
};

const deletePen = () => {
    if (confirm('Delete this pen? This action cannot be undone.')) {
        router.delete(route('dashboard.pens.destroy', props.pen.id));
    }
};
</script>

<template>
    <AppLayout :title="`Edit ${pen.title}`">
        <template #header>
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm uppercase tracking-wide text-gray-500 font-semibold">
                        Editing pen
                    </p>
                    <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
                        {{ pen.title }}
                    </h2>
                </div>
                <div class="flex gap-2">
                    <SecondaryButton type="button" @click="deletePen">
                        Delete
                    </SecondaryButton>
                    <Link
                        :href="route('dashboard.pens.index')"
                        class="inline-flex items-center rounded-md border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50"
                    >
                        Back
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto space-y-8 sm:px-6 lg:px-8">
                <form @submit.prevent="updatePen" class="space-y-8">
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm space-y-6">
                        <div class="grid gap-6 md:grid-cols-2">
                            <div>
                                <InputLabel for="title" value="Title" />
                                <TextInput id="title" v-model="form.title" type="text" class="mt-1 block w-full" required autofocus />
                                <InputError :message="form.errors.title" class="mt-2" />
                            </div>
                            <div>
                                <InputLabel for="slug" value="Slug" />
                                <TextInput id="slug" v-model="form.slug" type="text" class="mt-1 block w-full" required />
                                <InputError :message="form.errors.slug" class="mt-2" />
                            </div>

                            <div class="md:col-span-2">
                                <InputLabel for="description" value="Description" />
                                <textarea
                                    id="description"
                                    v-model="form.description"
                                    rows="3"
                                    class="mt-1 block w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500"
                                />
                                <InputError :message="form.errors.description" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid gap-6 md:grid-cols-2">
                            <div>
                                <InputLabel value="Visibility" />
                                <div class="mt-2 flex gap-4">
                                    <label class="flex cursor-pointer items-center gap-2 rounded-xl border p-3 text-sm" :class="form.visibility === 'public' ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-gray-200 text-gray-600'">
                                        <input v-model="form.visibility" value="public" type="radio" class="text-indigo-600 focus:ring-indigo-500" />
                                        Public
                                    </label>
                                    <label class="flex cursor-pointer items-center gap-2 rounded-xl border p-3 text-sm" :class="form.visibility === 'private' ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-gray-200 text-gray-600'">
                                        <input v-model="form.visibility" value="private" type="radio" class="text-indigo-600 focus:ring-indigo-500" />
                                        Private
                                    </label>
                                    <label class="flex cursor-pointer items-center gap-2 rounded-xl border p-3 text-sm" :class="form.visibility === 'unlisted' ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-gray-200 text-gray-600'">
                                        <input v-model="form.visibility" value="unlisted" type="radio" class="text-indigo-600 focus:ring-indigo-500" />
                                        Unlisted
                                    </label>
                                </div>
                                <InputError :message="form.errors.visibility" class="mt-2" />
                            </div>
                            <div>
                                <InputLabel value="Status" />
                                <select
                                    v-model="form.status"
                                    class="mt-2 block w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                </select>
                                <InputError :message="form.errors.status" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm space-y-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">External resources</h3>
                                <p class="text-sm text-gray-500">
                                    Load CSS or JS libraries directly into the preview.
                                </p>
                            </div>
                            <div class="flex gap-2 text-sm font-semibold text-indigo-600">
                                <button type="button" @click="addResource('css')">
                                    + CSS
                                </button>
                                <button type="button" @click="addResource('js')">
                                    + JS
                                </button>
                            </div>
                        </div>

                        <div class="grid gap-6 md:grid-cols-2">
                            <div>
                                <p class="text-xs font-semibold uppercase text-gray-500">Stylesheets</p>
                                <div v-if="form.settings?.resources?.css?.length" class="mt-3 space-y-3">
                                    <div
                                        v-for="(url, index) in form.settings.resources.css"
                                        :key="`css-${index}`"
                                        class="flex items-center gap-2"
                                    >
                                        <TextInput v-model="form.settings.resources.css[index]" type="url" class="flex-1" />
                                        <button type="button" class="text-gray-400 hover:text-red-500" @click="removeResource('css', index)">
                                            &times;
                                        </button>
                                    </div>
                                </div>
                                <p v-else class="mt-3 text-sm text-gray-500">
                                    No external CSS resources attached.
                                </p>
                            </div>

                            <div>
                                <p class="text-xs font-semibold uppercase text-gray-500">Scripts</p>
                                <div v-if="form.settings?.resources?.js?.length" class="mt-3 space-y-3">
                                    <div
                                        v-for="(url, index) in form.settings.resources.js"
                                        :key="`js-${index}`"
                                        class="flex items-center gap-2"
                                    >
                                        <TextInput v-model="form.settings.resources.js[index]" type="url" class="flex-1" />
                                        <button type="button" class="text-gray-400 hover:text-red-500" @click="removeResource('js', index)">
                                            &times;
                                        </button>
                                    </div>
                                </div>
                                <p v-else class="mt-3 text-sm text-gray-500">
                                    No external JavaScript resources attached.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm space-y-6">
                        <PenEditor :form="form" />
                    </div>

                    <div class="flex items-center justify-end gap-4">
                        <SecondaryButton type="button" @click="deletePen">
                            Delete
                        </SecondaryButton>
                        <PrimaryButton :class="{ 'opacity-50': form.processing }" :disabled="form.processing">
                            Update Pen
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
