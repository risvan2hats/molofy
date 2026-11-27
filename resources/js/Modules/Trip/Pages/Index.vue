<script setup>
import { router, Link, Head } from '@inertiajs/vue3';
import AppLayout from '@/Common/Layouts/AppLayout.vue';
import BaseButton from '@/Common/Components/BaseButton.vue';

defineProps({
    trips: {
        type: Object,
        required: true,
    },
});

const deleteTrip = (trip) => {
    if (!confirm(`Delete "${trip.name}"?`)) {
        return;
    }

    router.delete(route('trips.destroy', trip.id));
};
</script>

<template>
    <Head title="Trips" />

    <AppLayout>
        <div class="space-y-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Trips
                    </h1>

                    <p class="mt-1 text-sm text-gray-500">
                        Manage your available boat trips.
                    </p>
                </div>

                <Link
                    :href="route('trips.create')"
                    class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700"
                >
                    Add Trip
                </Link>
            </div>

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-gray-600">
                            <tr>
                                <th class="px-6 py-4 font-semibold">Trip</th>
                                <th class="px-6 py-4 font-semibold">Duration</th>
                                <th class="px-6 py-4 font-semibold">Adult Price</th>
                                <th class="px-6 py-4 font-semibold">Status</th>
                                <th class="px-6 py-4 text-right font-semibold">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="trip in trips.data" :key="trip.id">
                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-900">
                                        {{ trip.name }}
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ trip.slug }}
                                    </p>
                                </td>

                                <td class="px-6 py-4 text-gray-600">
                                    {{ trip.duration_minutes }} minutes
                                </td>

                                <td class="px-6 py-4 text-gray-600">
                                    €{{ Number(trip.price_adult).toFixed(2) }}
                                </td>

                                <td class="px-6 py-4">
                                    <span
                                        class="rounded-full px-3 py-1 text-xs font-medium"
                                        :class="trip.is_active
                                            ? 'bg-green-100 text-green-700'
                                            : 'bg-gray-100 text-gray-600'"
                                    >
                                        {{ trip.is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex justify-end gap-3">
                                        <Link
                                            :href="route('trips.edit', trip.id)"
                                            class="font-medium text-blue-600 hover:text-blue-800"
                                        >
                                            Edit
                                        </Link>

                                        <button
                                            type="button"
                                            class="font-medium text-red-600 hover:text-red-800"
                                            @click="deleteTrip(trip)"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="trips.data.length === 0">
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    No trips found. Create your first trip.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 px-6 py-4">
                    <p class="text-sm text-gray-500">
                        Showing {{ trips.from ?? 0 }}–{{ trips.to ?? 0 }}
                        of {{ trips.total }} trips
                    </p>

                    <div class="flex gap-2">
                        <template v-for="link in trips.links" :key="link.label">
                            <Link
                                v-if="link.url"
                                :href="link.url"
                                class="rounded-lg border px-3 py-1.5 text-sm"
                                :class="link.active
                                    ? 'border-blue-600 bg-blue-600 text-white'
                                    : 'border-gray-200 text-gray-600 hover:bg-gray-50'"
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
