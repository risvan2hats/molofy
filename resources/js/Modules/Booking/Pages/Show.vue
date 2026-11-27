
<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Common/Layouts/AppLayout.vue';

const props = defineProps({
    booking: {
        type: Object,
        required: true,
    },
    refundableAmount: {
        type: Number,
        required: true,
    },
});

const page = usePage();

const cancelForm = useForm({});
const refundForm = useForm({});

const formatPrice = (amount) =>
    new Intl.NumberFormat('de-DE', {
        style: 'currency',
        currency: 'EUR',
    }).format(Number(amount));

const formatDate = (date) =>
    date ? new Date(date).toLocaleString() : '-';

const cancelBooking = () => {
    if (!confirm('Are you sure you want to cancel this booking?')) {
        return;
    }

    cancelForm.post(
        route('admin.bookings.cancel', props.booking.id)
    );
};

const processRefund = () => {
    if (!confirm('Have you manually processed this refund?')) {
        return;
    }

    refundForm.post(
        route('admin.bookings.refunds.process', props.booking.id)
    );
};
</script>

<template>
    <Head :title="`Booking ${booking.reference}`" />

    <AppLayout>
        <div class="mx-auto max-w-5xl space-y-6">
            <div>
                <Link
                    :href="route('admin.bookings.index')"
                    class="text-sm font-medium text-blue-700 hover:underline"
                >
                    ← Back to bookings
                </Link>

                <div class="mt-4 flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">
                            {{ booking.reference }}
                        </h1>

                        <p class="mt-1 text-sm text-gray-500">
                            Booking details
                        </p>
                    </div>

                    <span
                        class="rounded-full px-4 py-2 text-sm font-semibold capitalize"
                        :class="booking.status === 'confirmed'
                            ? 'bg-green-100 text-green-700'
                            : booking.status === 'cancelled'
                                ? 'bg-red-100 text-red-700'
                                : 'bg-gray-100 text-gray-700'"
                    >
                        {{ booking.status }}
                    </span>
                </div>
            </div>

            <div
                v-if="page.props.flash?.success"
                class="rounded-lg bg-green-50 p-4 text-sm text-green-700"
            >
                {{ page.props.flash.success }}
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <section class="rounded-xl border border-gray-200 bg-white p-6">
                    <h2 class="text-lg font-semibold text-gray-900">
                        Customer information
                    </h2>

                    <dl class="mt-5 space-y-4 text-sm">
                        <div>
                            <dt class="text-gray-500">Name</dt>
                            <dd class="mt-1 font-medium text-gray-900">
                                {{ booking.customer.name }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-gray-500">Email</dt>
                            <dd class="mt-1 font-medium text-gray-900">
                                {{ booking.customer.email }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-gray-500">Phone</dt>
                            <dd class="mt-1 font-medium text-gray-900">
                                {{ booking.customer.phone }}
                            </dd>
                        </div>
                    </dl>
                </section>

                <section class="rounded-xl border border-gray-200 bg-white p-6">
                    <h2 class="text-lg font-semibold text-gray-900">
                        Trip information
                    </h2>

                    <dl class="mt-5 space-y-4 text-sm">
                        <div>
                            <dt class="text-gray-500">Trip</dt>
                            <dd class="mt-1 font-medium text-gray-900">
                                {{ booking.departure.trip.name }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-gray-500">Departure</dt>
                            <dd class="mt-1 font-medium text-gray-900">
                                {{ formatDate(booking.departure.departure_at) }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-gray-500">Boat</dt>
                            <dd class="mt-1 font-medium text-gray-900">
                                {{ booking.departure.boat.name }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-gray-500">Meeting point</dt>
                            <dd class="mt-1 font-medium text-gray-900">
                                {{ booking.departure.meeting_point }}
                            </dd>
                        </div>
                    </dl>
                </section>
            </div>

            <section class="rounded-xl border border-gray-200 bg-white p-6">
                <h2 class="text-lg font-semibold text-gray-900">
                    Guest information
                </h2>

                <div class="mt-5 grid gap-5 sm:grid-cols-4">
                    <div>
                        <p class="text-sm text-gray-500">Adults</p>
                        <p class="mt-1 text-xl font-semibold">
                            {{ booking.adults }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Children</p>
                        <p class="mt-1 text-xl font-semibold">
                            {{ booking.children }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Infants</p>
                        <p class="mt-1 text-xl font-semibold">
                            {{ booking.infants }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Total seats</p>
                        <p class="mt-1 text-xl font-semibold">
                            {{ booking.total_seats }}
                        </p>
                    </div>
                </div>
            </section>

            <section class="rounded-xl border border-gray-200 bg-white p-6">
                <h2 class="text-lg font-semibold text-gray-900">
                    Payment breakdown
                </h2>

                <dl class="mt-5 space-y-4 text-sm">
                    <div class="flex justify-between gap-4">
                        <dt class="text-gray-500">Total amount</dt>
                        <dd class="font-semibold">
                            {{ formatPrice(booking.total_amount) }}
                        </dd>
                    </div>

                    <div class="flex justify-between gap-4">
                        <dt class="text-gray-500">Deposit amount</dt>
                        <dd class="font-medium">
                            {{ formatPrice(booking.deposit_amount) }}
                        </dd>
                    </div>

                    <div class="flex justify-between gap-4">
                        <dt class="text-gray-500">Remaining balance</dt>
                        <dd class="font-medium">
                            {{ formatPrice(booking.remaining_amount) }}
                        </dd>
                    </div>

                    <div class="flex justify-between gap-4">
                        <dt class="text-gray-500">Payment status</dt>
                        <dd class="font-medium capitalize">
                            {{ booking.payment_status }}
                        </dd>
                    </div>

                    <template v-if="booking.status === 'cancelled'">
                        <div class="border-t border-gray-100 pt-4"></div>

                        <div class="flex justify-between gap-4">
                            <dt class="text-gray-500">Cancelled at</dt>
                            <dd class="font-medium">
                                {{ formatDate(booking.cancelled_at) }}
                            </dd>
                        </div>

                        <div class="flex justify-between gap-4">
                            <dt class="text-gray-500">Refund amount</dt>
                            <dd class="font-semibold">
                                {{ formatPrice(booking.cancellation_refund_amount) }}
                            </dd>
                        </div>

                        <div class="flex justify-between gap-4">
                            <dt class="text-gray-500">Refund status</dt>
                            <dd
                                class="font-semibold capitalize"
                                :class="{
                                    'text-green-700': booking.refund_status === 'processed',
                                    'text-yellow-700': booking.refund_status === 'pending',
                                    'text-gray-500': booking.refund_status === 'not_eligible',
                                }"
                            >
                                {{ (booking.refund_status || 'not_eligible').replace('_', ' ') }}
                            </dd>
                        </div>

                        <div
                            v-if="booking.refund_status === 'processed'"
                            class="space-y-3 rounded-lg bg-green-50 p-4"
                        >
                            <p class="font-semibold text-green-800">
                                Refund processed successfully
                            </p>

                            <div class="flex justify-between gap-4 text-sm">
                                <span class="text-green-700">Processed at</span>
                                <span class="font-medium text-green-800">
                                    {{ formatDate(booking.refund_processed_at) }}
                                </span>
                            </div>

                            <div class="flex justify-between gap-4 text-sm">
                                <span class="text-green-700">Processed by admin ID</span>
                                <span class="font-medium text-green-800">
                                    {{ booking.refund_processed_by ?? '-' }}
                                </span>
                            </div>
                        </div>

                        <p class="text-xs text-gray-500">
                            Refunds are handled manually outside Molofy.
                            Mark a refund as processed only after completing
                            the actual refund.
                        </p>
                    </template>
                </dl>
            </section>

            <section
                v-if="booking.status === 'cancelled' && booking.refund_status === 'pending'"
                class="flex flex-wrap items-center justify-between gap-4 rounded-xl border border-yellow-200 bg-white p-6"
            >
                <div>
                    <h2 class="font-semibold text-gray-900">
                        Process deposit refund
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Refund amount:
                        {{ formatPrice(booking.cancellation_refund_amount) }}
                    </p>
                </div>

                <button
                    type="button"
                    @click="processRefund"
                    :disabled="refundForm.processing"
                    class="rounded-lg bg-green-700 px-5 py-3 text-sm font-semibold text-white hover:bg-green-800 disabled:opacity-50"
                >
                    {{ refundForm.processing ? 'Processing...' : 'Mark Refund as Processed' }}
                </button>
            </section>

            <section
                v-if="booking.status === 'confirmed'"
                class="flex flex-wrap items-center justify-between gap-4 rounded-xl border border-red-100 bg-white p-6"
            >
                <div>
                    <h2 class="font-semibold text-gray-900">
                        Cancel booking
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Refundable deposit:
                        {{ formatPrice(refundableAmount) }}
                    </p>
                </div>

                <button
                    type="button"
                    @click="cancelBooking"
                    :disabled="cancelForm.processing"
                    class="rounded-lg bg-red-600 px-5 py-3 text-sm font-semibold text-white hover:bg-red-700 disabled:opacity-50"
                >
                    {{ cancelForm.processing ? 'Cancelling...' : 'Cancel Booking' }}
                </button>
            </section>

            <section
                v-if="booking.status === 'confirmed'"
                class="rounded-xl border border-gray-200 bg-white p-6 text-center"
            >
                <h2 class="text-lg font-semibold text-gray-900">
                    Boarding QR Code
                </h2>

                <div class="mt-4 flex justify-center">
                    <img
                        :src="route('booking.qr', booking.confirmation_token)"
                        alt="Booking QR Code"
                        class="h-64 w-64 rounded-lg border border-gray-200 p-3"
                    />
                </div>
            </section>
        </div>
    </AppLayout>
</template>
