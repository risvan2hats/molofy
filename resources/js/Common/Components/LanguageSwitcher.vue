<script setup>
import { useLanguageStore } from '@/Stores/languageStore';

const languageStore = useLanguageStore();

const languages = [
    {
        code: 'de',
        label: 'Deutsch',
    },
    {
        code: 'en',
        label: 'English',
    },
    {
        code: 'it',
        label: 'Italiano',
    },
];
</script>

<template>
    <select
        :value="languageStore.locale"
        @change="languageStore.setLanguage($event.target.value)"
        class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm outline-none focus:border-blue-500"
        aria-label="Select language"
    >
        <option
            v-for="language in languages"
            :key="language.code"
            :value="language.code"
        >
            {{ language.label }}
        </option>
    </select>
</template>