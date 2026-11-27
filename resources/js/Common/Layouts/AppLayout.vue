<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import BaseLoader from '@/Common/Components/BaseLoader.vue';
import LanguageSwitcher from '@/Common/Components/LanguageSwitcher.vue';
import { useInertiaLoading } from '@/Common/Composables/useInertiaLoading';

const page = usePage();
const { isLoading } = useInertiaLoading();

const navigation = [
    {
        label: 'navigation.home',
        route: 'dashboard',
    },
    {
        label: 'navigation.boats',
        route: 'boats.index',
    },
    {
        label: 'navigation.trips',
        route: 'trips.index',
    },
    {
        label: 'navigation.bookings',
        route: 'admin.bookings.index',
    },
    {
        label: 'navigation.departures',
        route: 'departures.index',
    }
];

const availableNavigation = navigation.filter((item) =>
    route().has(item.route)
);

const isActive = (routeName) => {
    return page.url === route(routeName)
        || page.url.startsWith(route(routeName) + '/');
};
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <header class="border-b border-gray-200 bg-white">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
                <Link :href="route('dashboard')" class="text-xl font-bold text-blue-700">
                    Molofy
                </Link>

                <nav class="flex items-center gap-6">
                    <Link v-for="item in availableNavigation" :key="item.route" :href="route(item.route)"
                        class="text-sm font-medium transition" :class="isActive(item.route)
                            ? 'text-blue-600'
                            : 'text-gray-600 hover:text-blue-600'">
                        {{ $t(item.label) }}
                    </Link>
                </nav>

                <div class="flex items-center gap-4">
                    <LanguageSwitcher />

                    <Link :href="route('logout')" method="post" as="button"
                        class="text-sm text-gray-600 hover:text-red-600">
                        {{ $t('navigation.logout') }}
                    </Link>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-6 py-8">
            <slot />
        </main>

        <BaseLoader v-if="isLoading" overlay :text="$t('common.loading')" />
    </div>
</template>