<script setup>
defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    title: {
        type: String,
        default: '',
    },
    maxWidth: {
        type: String,
        default: 'max-w-lg',
    },
});

defineEmits(['close']);
</script>

<template>
    <Teleport to="body">
        <div
            v-if="show"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
            @click.self="$emit('close')"
        >
            <div
                class="w-full rounded-xl bg-white shadow-xl"
                :class="maxWidth"
            >
                <div
                    class="flex items-center justify-between border-b border-gray-100 px-5 py-4"
                >
                    <h3 class="font-semibold text-gray-800">
                        {{ title }}
                    </h3>

                    <button
                        type="button"
                        class="text-xl text-gray-400 hover:text-gray-700"
                        @click="$emit('close')"
                    >
                        &times;
                    </button>
                </div>

                <div class="p-5">
                    <slot />
                </div>

                <div
                    v-if="$slots.footer"
                    class="border-t border-gray-100 px-5 py-4"
                >
                    <slot name="footer" />
                </div>
            </div>
        </div>
    </Teleport>
</template>