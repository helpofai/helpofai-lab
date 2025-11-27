<script setup>
import { computed } from 'vue';

const props = defineProps({
    form: {
        type: Object,
        required: true,
    },
});

const compiledDocument = computed(() => {
    const cssLinks = props.form.settings?.resources?.css ?? [];
    const jsLinks = props.form.settings?.resources?.js ?? [];

    return `
        <!doctype html>
        <html lang="en">
            <head>
                <meta charset="utf-8" />
                <meta name="viewport" content="width=device-width,initial-scale=1" />
                ${cssLinks.map((href) => `<link rel="stylesheet" href="${href}">`).join('\n')}
                <style>${props.form.css ?? ''}</style>
            </head>
            <body>
                ${props.form.html ?? ''}
                ${jsLinks.map((src) => `<script src="${src}"><\/script>`).join('\n')}
                <script>
                    try {
                        ${props.form.js ?? ''}
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
    <div class="grid gap-6 lg:grid-cols-2">
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700">HTML</label>
                <textarea
                    v-model="form.html"
                    rows="8"
                    class="mt-2 w-full rounded-xl border-gray-200 bg-gray-50 font-mono text-sm text-gray-900 focus:border-indigo-500 focus:ring-indigo-500"
                />
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700">CSS</label>
                <textarea
                    v-model="form.css"
                    rows="8"
                    class="mt-2 w-full rounded-xl border-gray-200 bg-gray-50 font-mono text-sm text-gray-900 focus:border-indigo-500 focus:ring-indigo-500"
                />
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700">JavaScript</label>
                <textarea
                    v-model="form.js"
                    rows="8"
                    class="mt-2 w-full rounded-xl border-gray-200 bg-gray-50 font-mono text-sm text-gray-900 focus:border-indigo-500 focus:ring-indigo-500"
                />
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-slate-900 shadow-inner">
            <div class="border-b border-slate-800 px-4 py-3 text-sm font-semibold uppercase tracking-wide text-slate-300">
                Live Preview
            </div>
            <iframe
                title="Live preview"
                :srcdoc="compiledDocument"
                class="h-full min-h-[600px] w-full rounded-b-2xl bg-white"
            />
        </div>
    </div>
</template>
