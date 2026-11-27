
<script setup>
import { Link } from '@inertiajs/vue3';
import LanguageSwitcher from '@/Common/Components/LanguageSwitcher.vue';
</script>

<template>
    <div class="min-h-screen bg-white">
        <header class="border-b border-gray-100">
            <div
                class="mx-auto flex max-w-7xl items-center justify-between px-6 py-5"
            >
                <a
                    href="/"
                    class="text-2xl font-bold text-blue-700"
                >
                    Molofy
                </a>

                <div class="flex items-center gap-4">
                    <Link
                        :href="route('access')"
                        class="rounded-lg border border-blue-700 px-4 py-2 text-sm font-medium text-blue-700 transition hover:bg-blue-50"
                    >
                        Dashboard
                    </Link>

                    <LanguageSwitcher />
                </div>
            </div>
        </header>

        <main>
            <slot />
        </main>

        <footer class="border-t border-gray-100 py-6 text-center">
            <p class="text-sm text-gray-500">
                © {{ new Date().getFullYear() }} Molofy.
                All rights reserved.
            </p>
        </footer>
    </div>
</template>
