<script setup>
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from '@/Common/Layouts/GuestLayout.vue';

defineProps({
    trip: {
        type: Object,
        required: true,
    },
    departures: {
        type: Array,
        default: () => [],
    },
});

const formatDuration = (minutes) => {
    const hours = Math.floor(minutes / 60);
    const remainingMinutes = minutes % 60;

    if (!hours) return `${remainingMinutes} min`;

    return remainingMinutes
        ? `${hours}h ${remainingMinutes}m`
        : `${hours}h`;
};

const formatPrice = (amount) =>
    new Intl.NumberFormat('de-DE', {
        style: 'currency',
        currency: 'EUR',
    }).format(Number(amount));

const formatDateTime = (value) =>
    new Date(value).toLocaleString([], {
        dateStyle: 'medium',
        timeStyle: 'short',
    });
</script>

<template>
    <Head :title="trip.name" />

    <GuestLayout>
        <section class="bg-slate-950 py-16 text-white">
            <div class="mx-auto max-w-5xl px-6">
                <Link
                    :href="route('booking.home')"
                    class="text-sm font-medium text-cyan-300 hover:underline"
                >
                    ← Back to all trips
                </Link>

                <div class="mt-6 flex flex-wrap items-center gap-3">
                    <span class="rounded-full bg-cyan-500/20 px-3 py-1 text-xs font-semibold text-cyan-300">
                        Boat Experience
                    </span>

                    <span
                        v-if="trip.duration_minutes"
                        class="text-sm text-slate-300"
                    >
                        Duration: {{ formatDuration(trip.duration_minutes) }}
                    </span>
                </div>

                <h1 class="mt-4 text-3xl font-bold sm:text-5xl">
                    {{ trip.name }}
                </h1>

                <p class="mt-4 max-w-3xl text-base leading-7 text-slate-300">
                    {{ trip.description || 'Discover an unforgettable experience at sea.' }}
                </p>

                <div class="mt-8 flex flex-wrap gap-6 border-t border-slate-800 pt-6 text-sm">
                    <div>
                        <p class="text-slate-400">Adult</p>
                        <p class="mt-1 text-xl font-bold text-white">
                            {{ formatPrice(trip.price_adult) }}
                        </p>
                    </div>

                    <div v-if="trip.price_child !== null && trip.price_child !== undefined">
                        <p class="text-slate-400">Child</p>
                        <p class="mt-1 text-xl font-bold text-white">
                            {{ formatPrice(trip.price_child) }}
                        </p>
                    </div>

                    <div v-if="trip.price_infant !== null && trip.price_infant !== undefined">
                        <p class="text-slate-400">Infant</p>
                        <p class="mt-1 text-xl font-bold text-white">
                            {{ formatPrice(trip.price_infant) }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-slate-50 py-16">
            <div class="mx-auto max-w-5xl px-6">
                <h2 class="text-2xl font-bold text-slate-900">
                    Upcoming Departures
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Select a scheduled departure to proceed with your booking.
                </p>

                <div
                    v-if="departures.length"
                    class="mt-8 space-y-4"
                >
                    <div
                        v-for="departure in departures"
                        :key="departure.id"
                        class="flex flex-col justify-between gap-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:flex-row sm:items-center"
                    >
                        <div class="space-y-2">
                            <p class="text-lg font-bold text-slate-900">
                                {{ formatDateTime(departure.departure_at) }}
                            </p>

                            <p
                                v-if="departure.arrival_at"
                                class="text-sm text-slate-500"
                            >
                                Arrival: {{ formatDateTime(departure.arrival_at) }}
                            </p>

                            <div class="flex flex-wrap gap-x-6 gap-y-1 text-sm text-slate-600">
                                <span v-if="departure.boat">
                                    Boat: <strong class="font-medium text-slate-900">{{ departure.boat.name }}</strong>
                                    (Capacity: {{ departure.boat.capacity }})
                                </span>

                                <span v-if="departure.meeting_point">
                                    Meeting point: <strong class="font-medium text-slate-900">{{ departure.meeting_point }}</strong>
                                </span>
                            </div>

                            <p
                                v-if="departure.meeting_instructions"
                                class="text-xs text-slate-500"
                            >
                                {{ departure.meeting_instructions }}
                            </p>
                        </div>

                        <div class="shrink-0">
                            <Link
                                :href="route('booking.create', departure.id)"
                                class="inline-flex rounded-full bg-cyan-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-cyan-700"
                            >
                                Book Now
                            </Link>
                        </div>
                    </div>
                </div>

                <div
                    v-else
                    class="mt-8 rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center"
                >
                    <p class="text-lg font-semibold text-slate-900">
                        No upcoming departures scheduled
                    </p>
                    <p class="mt-2 text-sm text-slate-500">
                        Please check back soon or explore our other trips.
                    </p>
                </div>
            </div>
        </section>
    </GuestLayout>
</template>
