<script setup>
defineProps({
    text: {
        type: String,
        default: 'Loading...',
    },
    overlay: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <div :class="overlay
            ? 'fixed inset-0 z-50 flex items-center justify-center bg-white/70 backdrop-blur-[1px]'
            : 'flex items-center justify-center gap-3 py-8'
        ">
        <div class="flex items-center justify-center gap-3"
            :class="overlay ? 'rounded-xl border border-gray-200 bg-white px-5 py-3 shadow-sm' : ''">
            <div class="h-6 w-6 animate-spin rounded-full border-2 border-blue-600 border-t-transparent" />

            <span class="text-sm text-gray-500">
                {{ text }}
            </span>
        </div>
    </div>
</template>
