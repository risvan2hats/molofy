<script setup>
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from '@/Common/Layouts/GuestLayout.vue';

defineProps({
    booking: {
        type: Object,
        required: true,
    },
});

const formatPrice = (amount) =>
    new Intl.NumberFormat('de-DE', {
        style: 'currency',
        currency: 'EUR',
    }).format(Number(amount));
</script>

<template>

    <Head title="Booking Confirmed" />

    <GuestLayout>
        <section class="bg-gray-50 px-6 py-16">
            <div class="mx-auto max-w-2xl rounded-2xl border border-gray-200 bg-white p-8 text-center shadow-sm">
                <div
                    class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-green-100 text-3xl text-green-700">
                    ✓
                </div>

                <h1 class="mt-6 text-3xl font-bold text-gray-900">
                    Booking confirmed!
                </h1>

                <p class="mt-3 text-gray-600">
                    Thank you, {{ booking.customer.name }}.
                    Your booking has been successfully registered.
                </p>

                <div class="mt-8 rounded-xl bg-gray-50 p-6 text-left">
                    <div class="text-center">
                        <p class="text-sm text-gray-500">Booking reference</p>
                        <p class="mt-1 text-2xl font-bold tracking-wider text-blue-700">
                            {{ booking.reference }}
                        </p>
                    </div>

                    <div class="my-6 border-t border-gray-200"></div>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between gap-4">
                            <span class="text-gray-500">Trip</span>
                            <span class="font-medium text-right">
                                {{ booking.departure.trip.name }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-gray-500">Departure</span>
                            <span class="font-medium text-right">
                                {{ new Date(booking.departure.departure_at).toLocaleString() }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-gray-500">Boat</span>
                            <span class="font-medium text-right">
                                {{ booking.departure.boat.name }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-gray-500">Guests</span>
                            <span class="font-medium text-right">
                                {{ booking.total_seats }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-gray-500">Total amount</span>
                            <span class="font-semibold">
                                {{ formatPrice(booking.total_amount) }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-gray-500">Deposit</span>
                            <span class="font-medium">
                                {{ formatPrice(booking.deposit_amount) }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-gray-500">Remaining balance</span>
                            <span class="font-medium">
                                {{ formatPrice(booking.remaining_amount) }}
                            </span>
                        </div>
                    </div>
                </div>

                <p class="mt-6 text-sm leading-6 text-gray-500">
                    Keep your booking reference safe. Payment has not been
                    collected online. The payment and boarding process will
                    be implemented in the next stages.
                </p>

                <div class="mt-6 border-t border-gray-200 pt-6 text-center">
                    <h2 class="text-lg font-semibold text-gray-900">
                        Your boarding QR code
                    </h2>

                    <p class="mt-2 text-sm text-gray-500">
                        Present this QR code to the boarding crew.
                    </p>

                    <div class="mt-4 flex justify-center">
                        <img :src="route('booking.qr', booking.confirmation_token)" alt="Booking QR Code"
                            class="h-64 w-64 rounded-lg border border-gray-200 bg-white p-3" />
                    </div>

                    <p class="mt-3 text-xs text-gray-500">
                        Booking reference: {{ booking.reference }}
                    </p>
                </div>

                <Link href="/"
                    class="mt-8 inline-flex rounded-lg bg-blue-700 px-6 py-3 font-semibold text-white hover:bg-blue-800">
                    Back to Home
                </Link>
            </div>
        </section>
    </GuestLayout>
</template>
