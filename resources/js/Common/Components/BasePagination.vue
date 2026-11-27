<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <div
        v-if="links.length > 3"
        class="flex flex-wrap items-center justify-end gap-1"
    >
        <template v-for="(link, index) in links" :key="index">
            <Link
                v-if="link.url"
                :href="link.url"
                preserve-scroll
                class="rounded-lg border px-3 py-2 text-sm transition"
                :class="link.active
                    ? 'border-blue-600 bg-blue-600 text-white'
                    : 'border-gray-200 text-gray-600 hover:bg-gray-50'"
            >
                <span v-html="link.label" />
            </Link>

            <span
                v-else
                class="rounded-lg border border-gray-100 px-3 py-2 text-sm text-gray-400"
            >
                <span v-html="link.label" />
            </span>
        </template>
    </div>
</template>