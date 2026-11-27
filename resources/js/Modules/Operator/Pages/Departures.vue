<script setup>
import { Head, router } from '@inertiajs/vue3';
import BaseLoader from '@/Common/Components/BaseLoader.vue';
import { useInertiaLoading } from '@/Common/Composables/useInertiaLoading';

const { isLoading } = useInertiaLoading();

defineProps({
    departures: {
        type: Array,
        default: () => [],
    },
    operator: {
        type: Object,
        required: true,
    },
});

const formatDate = (date) => new Date(date).toLocaleString();
</script>

<template>
    <Head title="Operator Departures" />

    <div class="min-h-screen bg-gray-50">
        <BaseLoader v-if="isLoading" overlay :text="$t('common.loading')" />
        <header class="border-b border-gray-200 bg-white px-6 py-4">
            <div class="mx-auto flex max-w-5xl items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold text-blue-700">Molofy</h1>
                    <p class="text-sm text-gray-500">
                        Welcome, {{ operator.name }}
                    </p>
                </div>

                <button
                    type="button"
                    @click="router.post(route('operator.logout'), {}, {
                    onSuccess: () => router.visit(route('access')),
                })" class="text-sm font-medium text-red-600 hover:underline"
                >
                    Sign out
                </button>
            </div>
        </header>

        <main class="mx-auto max-w-5xl px-6 py-10">
            <h2 class="text-2xl font-bold text-gray-900">
                Your departures
            </h2>

            <p class="mt-2 text-sm text-gray-500">
                Select a departure to start boarding check-in.
            </p>

            <div v-if="departures.length" class="mt-6 grid gap-4 md:grid-cols-2">
                <div
                    v-for="departure in departures"
                    :key="departure.id"
                    class="rounded-xl border border-gray-200 bg-white p-6"
                >
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ departure.trip.name }}
                    </h3>

                    <p class="mt-2 text-sm text-gray-500">
                        {{ formatDate(departure.departure_at) }}
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        Boat: {{ departure.boat.name }}
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        Meeting point: {{ departure.meeting_point }}
                    </p>

                    <button
                        type="button"
                        @click="router.visit(route('operator.scan', departure.id))"
                        class="mt-5 w-full rounded-lg bg-blue-700 px-5 py-3 font-semibold text-white hover:bg-blue-800"
                    >
                        Start Check-in
                    </button>
                </div>
            </div>

            <div v-else class="mt-6 rounded-xl border border-gray-200 bg-white p-8 text-center">
                <p class="font-medium text-gray-700">
                    No upcoming departures have been assigned to you.
                </p>
                <p class="mt-2 text-sm text-gray-500">
                    Please contact the administrator.
                </p>
            </div>
        </main>
    </div>
</template>
