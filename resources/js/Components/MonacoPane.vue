<script setup>
import { computed, onBeforeUnmount, onMounted, ref, watch, useAttrs } from 'vue';
import loader from '@monaco-editor/loader';

loader.config({
    paths: {
        vs: 'https://cdn.jsdelivr.net/npm/monaco-editor@0.52.0/min/vs',
    },
});

const props = defineProps({
    modelValue: {
        type: String,
        default: '',
    },
    language: {
        type: String,
        default: 'html',
    },
    title: {
        type: String,
        default: '',
    },
    badgeText: {
        type: String,
        default: '',
    },
    badgeColor: {
        type: String,
        default: 'white',
    },
});

const emit = defineEmits(['update:modelValue']);

const editorRef = ref(null);
let editorInstance = null;

const badgeClasses = computed(() => {
    switch (props.badgeColor) {
        case 'emerald':
            return 'text-emerald-300';
        case 'sky':
            return 'text-sky-300';
        case 'amber':
            return 'text-amber-300';
        default:
            return 'text-white/60';
    }
});

const attrs = useAttrs();

const showLanguageMenu = computed(() => {
    const lang = (props.language ?? '').toLowerCase();
    return lang === 'css' || lang === 'javascript' || lang === 'js';
});

const languageMenuLabel = computed(() => {
    const lang = (props.language ?? '').toLowerCase();
    if (lang === 'css') {
        return 'CSS';
    }
    if (lang === 'javascript' || lang === 'js') {
        return 'JS';
    }
    return (props.language ?? 'Lang').toUpperCase();
});

onMounted(async () => {
    const monaco = await loader.init();
    editorInstance = monaco.editor.create(editorRef.value, {
        value: props.modelValue ?? '',
        language: props.language,
        theme: 'vs-dark',
        fontSize: 14,
        minimap: { enabled: false },
        automaticLayout: true,
        fontFamily: '"JetBrains Mono", ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas',
        scrollbar: {
            verticalScrollbarSize: 8,
            horizontalScrollbarSize: 8,
        },
        contextmenu: false,
    });

    editorInstance.onDidChangeModelContent(() => {
        const value = editorInstance?.getValue?.() ?? '';
        if (value !== props.modelValue) {
            emit('update:modelValue', value);
        }
    });
});

watch(
    () => props.modelValue,
    (value) => {
        if (editorInstance && value !== editorInstance.getValue()) {
            editorInstance.setValue(value ?? '');
        }
    },
);

onBeforeUnmount(() => {
    if (editorInstance) {
        editorInstance.dispose();
        editorInstance = null;
    }
});
</script>

<template>
    <div :class="['flex min-h-0 flex-col bg-slate-900/50', attrs.class]">
        <header class="flex items-center justify-between border-b border-white/5 px-4 py-2 text-xs uppercase tracking-[0.3em] text-white/60">
            <div class="flex items-center gap-2">
                <span>{{ title }}</span>
                <button
                    v-if="showLanguageMenu"
                    type="button"
                    class="inline-flex items-center gap-1 rounded-full border border-white/10 px-2 py-1 text-[10px] font-semibold tracking-[0.2em] text-white/70 transition hover:border-white/50 hover:text-white"
                    title="Select language / preprocessor"
                >
                    <span>{{ languageMenuLabel }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 9l6 6 6-6" />
                    </svg>
                </button>
            </div>
            <span class="text-[10px]" :class="badgeClasses">
                {{ badgeText }}
            </span>
        </header>
        <div ref="editorRef" class="flex-1 min-h-0" />
    </div>
</template>
