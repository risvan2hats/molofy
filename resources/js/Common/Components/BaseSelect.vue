<script setup>
defineProps({
    modelValue: {
        type: [String, Number],
        default: '',
    },
    label: {
        type: String,
        default: '',
    },
    options: {
        type: Array,
        default: () => [],
    },
    placeholder: {
        type: String,
        default: 'Select an option',
    },
    error: {
        type: String,
        default: '',
    },
});

defineEmits(['update:modelValue']);
</script>

<template>
    <div class="w-full space-y-1.5">
        <label
            v-if="label"
            class="block text-sm font-medium text-gray-700"
        >
            {{ label }}
        </label>

        <select
            :value="modelValue"
            @change="$emit('update:modelValue', $event.target.value)"
            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
        >
            <option value="" disabled>
                {{ placeholder }}
            </option>

            <option
                v-for="option in options"
                :key="option.value"
                :value="option.value"
            >
                {{ option.label }}
            </option>
        </select>

        <p v-if="error" class="text-xs text-red-500">
            {{ error }}
        </p>
    </div>
</template>