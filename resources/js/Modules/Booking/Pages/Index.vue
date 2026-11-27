<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import AppLayout from '@/Common/Layouts/AppLayout.vue';

const props = defineProps({
    bookings: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
});

const search = ref(props.filters.search || '');
const status = ref(props.filters.status || '');

let searchTimeout;

watch([search, status], () => {
    clearTimeout(searchTimeout);

    searchTimeout = setTimeout(() => {
        router.get(
            route('admin.bookings.index'),
            {
                search: search.value,
                status: status.value,
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            }
        );
    }, 400);
});

const formatPrice = (amount) =>
    new Intl.NumberFormat('de-DE', {
        style: 'currency',
        currency: 'EUR',
    }).format(Number(amount));

const formatDate = (date) =>
    new Date(date).toLocaleString();

const statusClass = (value) => ({
    confirmed: 'bg-green-100 text-green-700',
    cancelled: 'bg-red-100 text-red-700',
    completed: 'bg-gray-100 text-gray-700',
}[value] || 'bg-gray-100 text-gray-700');
</script>

<template>
    <Head title="Bookings" />

    <AppLayout>
        <div class="space-y-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Bookings</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Manage customer reservations.
                </p>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5">
                <div class="grid gap-4 md:grid-cols-3">
                    <input
                        v-model="search"
                        type="search"
                        placeholder="Search reference, customer, email..."
                        class="rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 md:col-span-2"
                    />

                    <select
                        v-model="status"
                        class="rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    >
                        <option value="">All statuses</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
            </div>

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                            <tr>
                                <th class="px-5 py-4">Reference</th>
                                <th class="px-5 py-4">Customer</th>
                                <th class="px-5 py-4">Trip</th>
                                <th class="px-5 py-4">Departure</th>
                                <th class="px-5 py-4">Guests</th>
                                <th class="px-5 py-4">Total</th>
                                <th class="px-5 py-4">Status</th>
                                <th class="px-5 py-4">Action</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            <tr
                                v-for="booking in bookings.data"
                                :key="booking.id"
                                class="hover:bg-gray-50"
                            >
                                <td class="px-5 py-4 font-semibold text-blue-700">
                                    {{ booking.reference }}
                                </td>

                                <td class="px-5 py-4">
                                    <p class="font-medium text-gray-900">
                                        {{ booking.customer.name }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ booking.customer.email }}
                                    </p>
                                </td>

                                <td class="px-5 py-4">
                                    {{ booking.departure.trip.name }}
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap">
                                    {{ formatDate(booking.departure.departure_at) }}
                                </td>

                                <td class="px-5 py-4">
                                    {{ booking.total_seats }}
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap">
                                    {{ formatPrice(booking.total_amount) }}
                                </td>

                                <td class="px-5 py-4">
                                    <span
                                        class="rounded-full px-3 py-1 text-xs font-medium capitalize"
                                        :class="statusClass(booking.status)"
                                    >
                                        {{ booking.status }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <Link
                                        :href="route('admin.bookings.show', booking.id)"
                                        class="font-medium text-blue-700 hover:underline"
                                    >
                                        View
                                    </Link>
                                </td>
                            </tr>

                            <tr v-if="!bookings.data.length">
                                <td
                                    colspan="8"
                                    class="px-5 py-10 text-center text-gray-500"
                                >
                                    No bookings found.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 px-5 py-4">
                    <p class="text-sm text-gray-500">
                        Showing {{ bookings.from || 0 }} to
                        {{ bookings.to || 0 }} of {{ bookings.total }} bookings
                    </p>

                    <div class="flex gap-2">
                        <Link
                            v-for="link in bookings.links"
                            :key="link.label"
                            :href="link.url || '#'"
                            v-html="link.label"
                            class="rounded-lg border px-3 py-2 text-sm"
                            :class="[
                                link.active
                                    ? 'border-blue-700 bg-blue-700 text-white'
                                    : 'border-gray-200 text-gray-600',
                                !link.url ? 'pointer-events-none opacity-40' : '',
                            ]"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
