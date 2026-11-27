<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import GuestLayout from '@/Common/Layouts/GuestLayout.vue';

const props = defineProps({
    departure: {
        type: Object,
        required: true,
    },
    availableSeats: {
        type: Number,
        required: true,
    },
    depositPercentage: {
        type: Number,
        required: true,
    },
});

const form = useForm({
    adults: 1,
    children: 0,
    infants: 0,
    customer_name: '',
    customer_email: '',
    customer_phone: '',
});

const totalGuests = computed(() =>
    Number(form.adults) + Number(form.children) + Number(form.infants)
);

const totalAmount = computed(() => {
    const trip = props.departure.trip;

    return (
        Number(form.adults) * Number(trip.price_adult) +
        Number(form.children) * Number(trip.price_child) +
        Number(form.infants) * Number(trip.price_infant)
    );
});

const depositAmount = computed(() =>
    totalAmount.value * props.depositPercentage / 100
);

const remainingAmount = computed(() =>
    totalAmount.value - depositAmount.value
);

const formatPrice = (amount) =>
    new Intl.NumberFormat('de-DE', {
        style: 'currency',
        currency: 'EUR',
    }).format(amount);

const submit = () => {
    form.post(route('booking.store', props.departure.id));
};
</script>

<template>
    <Head title="Complete Your Booking" />

    <GuestLayout>
        <section class="bg-gray-50 py-12">
            <div class="mx-auto max-w-5xl px-6">
                <Link href="/" class="text-sm font-medium text-blue-700 hover:underline">
                    ← Back to trips
                </Link>

                <div class="mt-6">
                    <h1 class="text-3xl font-bold text-gray-900">
                        Complete your booking
                    </h1>

                    <p class="mt-2 text-gray-600">
                        Enter your guest details to reserve your seats.
                    </p>
                </div>

                <div class="mt-8 grid gap-8 lg:grid-cols-3">
                    <div class="space-y-6 lg:col-span-2">
                        <div class="rounded-xl border border-gray-200 bg-white p-6">
                            <h2 class="text-lg font-semibold text-gray-900">
                                Guest details
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                {{ availableSeats }} seats available
                            </p>

                            <div class="mt-6 space-y-5">
                                <div
                                    v-for="guest in [
                                        { key: 'adults', label: 'Adults', price: departure.trip.price_adult },
                                        { key: 'children', label: 'Children', price: departure.trip.price_child },
                                        { key: 'infants', label: 'Infants', price: departure.trip.price_infant },
                                    ]"
                                    :key="guest.key"
                                    class="flex items-center justify-between gap-4"
                                >
                                    <div>
                                        <p class="font-medium text-gray-900">
                                            {{ guest.label }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ formatPrice(Number(guest.price)) }} per person
                                        </p>
                                    </div>

                                    <input
                                        v-model.number="form[guest.key]"
                                        type="number"
                                        min="0"
                                        :max="availableSeats"
                                        class="w-24 rounded-lg border-gray-300 text-center focus:border-blue-500 focus:ring-blue-500"
                                    />
                                </div>

                                <p
                                    v-if="form.errors.adults"
                                    class="text-sm text-red-600"
                                >
                                    {{ form.errors.adults }}
                                </p>

                                <p
                                    v-if="totalGuests > availableSeats"
                                    class="text-sm text-red-600"
                                >
                                    You selected more guests than the available seats.
                                </p>
                            </div>
                        </div>

                        <div class="rounded-xl border border-gray-200 bg-white p-6">
                            <h2 class="text-lg font-semibold text-gray-900">
                                Contact information
                            </h2>

                            <div class="mt-5 space-y-4">
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">
                                        Full name
                                    </label>
                                    <input
                                        v-model="form.customer_name"
                                        type="text"
                                        autocomplete="name"
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="Enter your full name"
                                    />
                                    <p v-if="form.errors.customer_name" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.customer_name }}
                                    </p>
                                </div>

                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">
                                        Email address
                                    </label>
                                    <input
                                        v-model="form.customer_email"
                                        type="email"
                                        autocomplete="email"
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="Enter your email"
                                    />
                                    <p v-if="form.errors.customer_email" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.customer_email }}
                                    </p>
                                </div>

                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">
                                        Phone number
                                    </label>
                                    <input
                                        v-model="form.customer_phone"
                                        type="tel"
                                        autocomplete="tel"
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="Enter your phone number"
                                    />
                                    <p v-if="form.errors.customer_phone" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.customer_phone }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <aside class="h-fit rounded-xl border border-gray-200 bg-white p-6">
                        <h2 class="text-lg font-semibold text-gray-900">
                            Booking summary
                        </h2>

                        <div class="mt-5 space-y-3 text-sm">
                            <div>
                                <p class="font-medium text-gray-900">
                                    {{ departure.trip.name }}
                                </p>
                                <p class="mt-1 text-gray-500">
                                    {{ new Date(departure.departure_at).toLocaleString() }}
                                </p>
                                <p class="text-gray-500">
                                    {{ departure.meeting_point }}
                                </p>
                            </div>

                            <div class="border-t border-gray-100 pt-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Guests</span>
                                    <span class="font-medium">{{ totalGuests }}</span>
                                </div>

                                <div class="mt-3 flex justify-between">
                                    <span class="text-gray-500">Total</span>
                                    <span class="font-semibold">{{ formatPrice(totalAmount) }}</span>
                                </div>

                                <div class="mt-3 flex justify-between">
                                    <span class="text-gray-500">
                                        Deposit ({{ depositPercentage }}%)
                                    </span>
                                    <span class="font-medium">{{ formatPrice(depositAmount) }}</span>
                                </div>

                                <div class="mt-3 flex justify-between">
                                    <span class="text-gray-500">Remaining balance</span>
                                    <span class="font-medium">{{ formatPrice(remainingAmount) }}</span>
                                </div>
                            </div>
                        </div>

                        <p class="mt-5 rounded-lg bg-blue-50 p-3 text-xs leading-5 text-blue-800">
                            Payment is not collected online at this stage.
                            Your deposit and remaining balance are shown for reference.
                        </p>

                        <button
                            type="button"
                            @click="submit"
                            :disabled="
                                form.processing ||
                                totalGuests < 1 ||
                                totalGuests > availableSeats ||
                                availableSeats < 1
                            "
                            class="mt-6 w-full rounded-lg bg-blue-700 px-5 py-3 font-semibold text-white hover:bg-blue-800 disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            {{ form.processing ? 'Confirming...' : 'Confirm Booking' }}
                        </button>

                        <p class="mt-3 text-center text-xs text-gray-500">
                            No online payment will be processed.
                        </p>
                    </aside>
                </div>
            </div>
        </section>
    </GuestLayout>
</template>
