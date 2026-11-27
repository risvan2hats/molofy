<script setup>
defineProps({
    text: {
        type: String,
        default: 'Loading...',
    },
});
</script>

<template>
    <div class="flex items-center justify-center gap-3 py-8">
        <div
            class="h-6 w-6 animate-spin rounded-full border-2 border-blue-600 border-t-transparent"
        />

        <span class="text-sm text-gray-500">
            {{ text }}
        </span>
    </div>
</template>