<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    pen: {
        type: Object,
        required: true,
    },
});

const compiledDocument = computed(() => {
    const cssLinks = props.pen.settings?.resources?.css ?? [];
    const jsLinks = props.pen.settings?.resources?.js ?? [];

    return `
        <!doctype html>
        <html lang="en">
            <head>
                <meta charset="utf-8" />
                <meta name="viewport" content="width=device-width,initial-scale=1" />
                ${cssLinks.map((href) => `<link rel="stylesheet" href="${href}">`).join('\n')}
                <style>${props.pen.css ?? ''}</style>
            </head>
            <body>
                ${props.pen.html ?? ''}
                ${jsLinks.map((src) => `<script src="${src}"><\/script>`).join('\n')}
                <script>
                    try {
                        ${props.pen.js ?? ''}
                    } catch (error) {
                        console.error(error);
                    }
                <\/script>
            </body>
        </html>
    `;
});
</script>

<template>
    <AppLayout :title="pen.title">
        <template #header>
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm uppercase tracking-wide text-gray-500 font-semibold">
                        Pen preview
                    </p>
                    <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
                        {{ pen.title }}
                    </h2>
                </div>
                <Link
                    :href="route('dashboard.pens.edit', pen.id)"
                    class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-500"
                >
                    Edit Pen
                </Link>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
                    <iframe
                        class="h-[80vh] w-full border-0"
                        :srcdoc="compiledDocument"
                        title="Pen preview"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
