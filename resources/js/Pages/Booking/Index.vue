
<script setup>
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from '@/Common/Layouts/GuestLayout.vue';

defineProps({
    trips: {
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
</script>

<template>
    <Head title="Explore Boat Trips" />

    <GuestLayout>
        <section class="relative overflow-hidden bg-slate-950">
            <div
                class="absolute inset-0 bg-cover bg-center opacity-40"
                style="background-image: url('https://images.unsplash.com/photo-1500375592092-40eb2168fd21?q=80&w=2000')"
            />

            <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/70 to-transparent" />

            <div class="relative mx-auto max-w-7xl px-6 py-28 sm:py-36">
                <div class="max-w-2xl">
                    <p class="text-sm font-semibold uppercase tracking-[0.25em] text-cyan-300">
                        Discover the Mediterranean
                    </p>

                    <h1 class="mt-5 text-4xl font-bold leading-tight text-white sm:text-6xl">
                        Your next adventure starts at sea.
                    </h1>

                    <p class="mt-6 max-w-xl text-base leading-7 text-slate-200">
                        Explore beautiful destinations, discover unforgettable
                        boat experiences and book your next journey with Molofy.
                    </p>

                    <a
                        href="#trips"
                        class="mt-9 inline-flex rounded-full bg-cyan-500 px-7 py-3.5 text-sm font-semibold text-white transition hover:bg-cyan-600"
                    >
                        Explore Trips
                    </a>
                </div>
            </div>
        </section>

        <section id="trips" class="bg-slate-50 py-20">
            <div class="mx-auto max-w-7xl px-6">
                <div class="mb-10 flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wider text-cyan-700">
                            Explore
                        </p>

                        <h2 class="mt-2 text-3xl font-bold text-slate-900">
                            Available Boat Trips
                        </h2>

                        <p class="mt-2 text-sm text-slate-500">
                            Choose your experience and find your perfect departure.
                        </p>
                    </div>
                </div>

                <div
                    v-if="trips.length"
                    class="grid gap-7 sm:grid-cols-2 lg:grid-cols-3"
                >
                    <article
                        v-for="trip in trips"
                        :key="trip.id"
                        class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg"
                    >
                        <div class="flex h-48 items-center justify-center bg-gradient-to-br from-cyan-100 to-blue-100">
                            <span class="text-5xl">⛵</span>
                        </div>

                        <div class="p-6">
                            <div class="flex items-center justify-between gap-3">
                                <span class="rounded-full bg-cyan-50 px-3 py-1 text-xs font-semibold text-cyan-700">
                                    Boat Experience
                                </span>

                                <span class="text-sm text-slate-500">
                                    {{ formatDuration(trip.duration_minutes) }}
                                </span>
                            </div>

                            <h3 class="mt-4 text-xl font-bold text-slate-900">
                                {{ trip.name }}
                            </h3>

                            <p class="mt-3 line-clamp-3 text-sm leading-6 text-slate-600">
                                {{ trip.description || 'Discover an unforgettable experience at sea.' }}
                            </p>

                            <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-5">
                                <div>
                                    <p class="text-xs text-slate-500">
                                        Starting from
                                    </p>

                                    <p class="mt-1 text-xl font-bold text-slate-900">
                                        €{{ Number(trip.price_adult).toFixed(2) }}
                                    </p>
                                </div>

                                <Link
                                    :href="route('booking.trips.show', trip.slug)"
                                    class="rounded-full bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-700"
                                >
                                    View Trip
                                </Link>
                            </div>
                        </div>
                    </article>
                </div>

                <div
                    v-else
                    class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-20 text-center"
                >
                    <div class="text-5xl">⛵</div>

                    <h3 class="mt-5 text-xl font-semibold text-slate-900">
                        No trips available yet
                    </h3>

                    <p class="mt-2 text-sm text-slate-500">
                        Please check back soon for upcoming boat experiences.
                    </p>
                </div>
            </div>
        </section>

        <section class="bg-white py-20">
            <div class="mx-auto max-w-7xl px-6 text-center">
                <h2 class="text-3xl font-bold text-slate-900">
                    Experience the sea with Molofy
                </h2>

                <p class="mx-auto mt-4 max-w-2xl text-sm leading-7 text-slate-600">
                    Discover memorable destinations and enjoy your time on the
                    water with our carefully scheduled boat experiences.
                </p>
            </div>
        </section>
    </GuestLayout>
</template>
