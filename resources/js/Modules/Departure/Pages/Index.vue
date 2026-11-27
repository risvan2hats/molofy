<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Common/Layouts/AppLayout.vue';

defineProps({
    departures: {
        type: Object,
        required: true,
    },
});

const deleteDeparture = (departure) => {
    if (!confirm('Delete this cancelled departure?')) {
        return;
    }

    router.delete(route('departures.destroy', departure.id));
};

const formatDate = (date) => {
    return new Intl.DateTimeFormat('en-GB', {
        dateStyle: 'medium',
        timeStyle: 'short',
        timeZone: 'Europe/Rome',
    }).format(new Date(date));
};
</script>

<template>
    <Head title="Departures" />

    <AppLayout>
        <div class="space-y-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Departures
                    </h1>

                    <p class="mt-1 text-sm text-gray-500">
                        Schedule and manage upcoming departures.
                    </p>
                </div>

                <Link
                    :href="route('departures.create')"
                    class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700"
                >
                    Add Departure
                </Link>
            </div>

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-gray-600">
                            <tr>
                                <th class="px-6 py-4">Trip</th>
                                <th class="px-6 py-4">Boat</th>
                                <th class="px-6 py-4">Departure</th>
                                <th class="px-6 py-4">Meeting Point</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            <tr
                                v-for="departure in departures.data"
                                :key="departure.id"
                            >
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {{ departure.trip?.name }}
                                </td>

                                <td class="px-6 py-4 text-gray-600">
                                    {{ departure.boat?.name }}
                                </td>

                                <td class="px-6 py-4 text-gray-600">
                                    {{ formatDate(departure.departure_at) }}
                                </td>

                                <td class="px-6 py-4 text-gray-600">
                                    {{ departure.meeting_point }}
                                </td>

                                <td class="px-6 py-4">
                                    <span
                                        class="rounded-full px-3 py-1 text-xs font-medium"
                                        :class="{
                                            'bg-green-100 text-green-700': departure.status === 'scheduled',
                                            'bg-red-100 text-red-700': departure.status === 'cancelled',
                                            'bg-gray-100 text-gray-700': departure.status === 'completed',
                                        }"
                                    >
                                        {{ departure.status }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex justify-end gap-3">
                                        <Link
                                            :href="route('departures.edit', departure.id)"
                                            class="font-medium text-blue-600"
                                        >
                                            Edit
                                        </Link>

                                        <button
                                            v-if="departure.status === 'cancelled'"
                                            type="button"
                                            class="font-medium text-red-600"
                                            @click="deleteDeparture(departure)"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="departures.data.length === 0">
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    No departures scheduled yet.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 px-6 py-4">
                    <p class="text-sm text-gray-500">
                        {{ departures.total }} departures
                    </p>

                    <div class="flex gap-2">
                        <template
                            v-for="link in departures.links"
                            :key="link.label"
                        >
                            <Link
                                v-if="link.url"
                                :href="link.url"
                                class="rounded-lg border px-3 py-1.5 text-sm"
                                :class="link.active
                                    ? 'border-blue-600 bg-blue-600 text-white'
                                    : 'border-gray-200 text-gray-600'"
                                v-html="link.label"
                            />

                            <span
                                v-else
                                class="rounded-lg border border-gray-100 px-3 py-1.5 text-sm text-gray-300"
                                v-html="link.label"
                            />
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
