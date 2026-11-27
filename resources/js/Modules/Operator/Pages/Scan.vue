<script setup>
import { Head, router } from '@inertiajs/vue3';
import { Html5QrcodeScanner } from 'html5-qrcode';
import axios from 'axios';
import { onMounted, onBeforeUnmount, ref } from 'vue';

const props = defineProps({
    departure: {
        type: Object,
        required: true,
    },
});

const booking = ref(null);
const loading = ref(false);
const error = ref('');
const success = ref('');
const scannerMessage = ref('Point the camera at a booking QR code.');

let scanner = null;
let lastScannedToken = '';

const stopScanner = async () => {
    if (scanner) {
        try {
            await scanner.clear();
        } catch {
            // Scanner may already be stopped.
        }

        scanner = null;
    }
};

const startScanner = () => {
    if (scanner || booking.value) {
        return;
    }

    scanner = new Html5QrcodeScanner(
        'qr-reader',
        {
            fps: 10,
            qrbox: { width: 250, height: 250 },
            rememberLastUsedCamera: true,
        },
        false
    );

    scanner.render(
        (decodedText) => {
            if (decodedText === lastScannedToken || loading.value) {
                return;
            }

            lastScannedToken = decodedText;
            findBooking(decodedText);
        },
        () => {}
    );
};

const findBooking = async (token) => {
    loading.value = true;
    error.value = '';
    success.value = '';

    await stopScanner();

    try {
        const response = await axios.post(
            route('operator.scan.booking', props.departure.id),
            { token }
        );

        booking.value = response.data.booking;
        scannerMessage.value = 'Booking successfully verified.';
    } catch (exception) {
        error.value =
            exception.response?.data?.errors?.token?.[0] ||
            exception.response?.data?.message ||
            'Unable to verify this booking.';

        lastScannedToken = '';
        startScanner();
    } finally {
        loading.value = false;
    }
};

const boardGuest = async (guest) => {
    error.value = '';
    success.value = '';

    try {
        const response = await axios.post(
            route('operator.guest.board', [
                props.departure.id,
                guest.id,
            ])
        );

        guest.boarded_at = response.data.guest.boarded_at;
        success.value = `${guest.type} ${guest.number} successfully checked in.`;
    } catch (exception) {
        error.value =
            exception.response?.data?.errors?.guest?.[0] ||
            exception.response?.data?.message ||
            'Unable to check in this guest.';
    }
};

const scanAnother = async () => {
    booking.value = null;
    error.value = '';
    success.value = '';
    lastScannedToken = '';
    scannerMessage.value = 'Point the camera at another booking QR code.';

    await stopScanner();
    startScanner();
};

const formatDate = (date) => new Date(date).toLocaleString();

onMounted(() => {
    startScanner();
});

onBeforeUnmount(() => {
    stopScanner();
});
</script>

<template>
    <Head title="Boarding Check-in" />

    <div class="min-h-screen bg-gray-50">
        <header class="border-b border-gray-200 bg-white px-6 py-4">
            <div class="mx-auto flex max-w-3xl items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold text-blue-700">Molofy</h1>
                    <p class="text-sm text-gray-500">
                        Boarding check-in
                    </p>
                </div>

                <button
                    type="button"
                    @click="router.visit(route('operator.departures'))"
                    class="text-sm font-medium text-blue-700 hover:underline"
                >
                    Back to departures
                </button>
            </div>
        </header>

        <main class="mx-auto max-w-3xl space-y-6 px-6 py-8">
            <div class="rounded-xl border border-gray-200 bg-white p-5">
                <h2 class="text-lg font-semibold text-gray-900">
                    {{ departure.trip.name }}
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    {{ formatDate(departure.departure_at) }}
                </p>

                <p class="mt-1 text-sm text-gray-500">
                    {{ departure.boat.name }} · {{ departure.meeting_point }}
                </p>
            </div>

            <div v-if="error" class="rounded-lg bg-red-50 p-4 text-sm text-red-700">
                {{ error }}
            </div>

            <div v-if="success" class="rounded-lg bg-green-50 p-4 text-sm text-green-700">
                {{ success }}
            </div>

            <div v-if="!booking" class="rounded-xl border border-gray-200 bg-white p-5">
                <h2 class="font-semibold text-gray-900">
                    Scan booking QR code
                </h2>

                <p class="mt-2 text-sm text-gray-500">
                    {{ scannerMessage }}
                </p>

                <div id="qr-reader" class="mt-5 w-full overflow-hidden"></div>

                <p v-if="loading" class="mt-4 text-center text-sm text-gray-500">
                    Verifying booking...
                </p>
            </div>

            <div v-else class="rounded-xl border border-gray-200 bg-white p-6">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="text-sm text-gray-500">Booking reference</p>
                        <h2 class="mt-1 text-xl font-bold text-blue-700">
                            {{ booking.reference }}
                        </h2>
                    </div>

                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                        Confirmed
                    </span>
                </div>

                <div class="mt-5 border-t border-gray-100 pt-5">
                    <p class="text-sm text-gray-500">Customer</p>
                    <p class="mt-1 font-semibold text-gray-900">
                        {{ booking.customer }}
                    </p>

                    <p class="mt-4 text-sm text-gray-500">Total guests</p>
                    <p class="mt-1 font-semibold text-gray-900">
                        {{ booking.total_seats }}
                    </p>
                </div>

                <div class="mt-6">
                    <h3 class="font-semibold text-gray-900">
                        Guest boarding
                    </h3>

                    <div class="mt-3 space-y-3">
                        <div
                            v-for="guest in booking.guests"
                            :key="guest.id"
                            class="flex items-center justify-between gap-3 rounded-lg border border-gray-200 p-4"
                        >
                            <div>
                                <p class="font-medium capitalize text-gray-900">
                                    {{ guest.type }} {{ guest.number }}
                                </p>

                                <p
                                    v-if="guest.boarded_at"
                                    class="mt-1 text-xs text-green-700"
                                >
                                    Boarded at {{ new Date(guest.boarded_at).toLocaleTimeString() }}
                                </p>

                                <p v-else class="mt-1 text-xs text-gray-500">
                                    Waiting for check-in
                                </p>
                            </div>

                            <button
                                v-if="!guest.boarded_at"
                                type="button"
                                @click="boardGuest(guest)"
                                class="rounded-lg bg-blue-700 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-800"
                            >
                                Check in
                            </button>

                            <span
                                v-else
                                class="text-sm font-semibold text-green-700"
                            >
                                <span aria-hidden="true">✓</span> Boarded
                            </span>
                        </div>
                    </div>
                </div>

                <button
                    type="button"
                    @click="scanAnother"
                    class="mt-6 w-full rounded-lg border border-gray-300 px-5 py-3 font-semibold text-gray-700 hover:bg-gray-50"
                >
                    Scan Another Booking
                </button>
            </div>
        </main>
    </div>
</template>
