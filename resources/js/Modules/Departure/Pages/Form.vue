<script setup>
import { computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Common/Layouts/AppLayout.vue';

const props = defineProps({
    departure: {
        type: Object,
        default: null,
    },
    trips: {
        type: Array,
        default: () => [],
    },
    boats: {
        type: Array,
        default: () => [],
    },
});

const isEditing = computed(() => Boolean(props.departure));

const toLocalDateTime = (value) => {
    if (!value) return '';

    const date = new Date(value);
    const offset = date.getTimezoneOffset() * 60000;

    return new Date(date.getTime() - offset)
        .toISOString()
        .slice(0, 16);
};

const form = useForm({
    trip_id: props.departure?.trip_id ?? '',
    boat_id: props.departure?.boat_id ?? '',
    departure_at: toLocalDateTime(props.departure?.departure_at),
    arrival_at: toLocalDateTime(props.departure?.arrival_at),
    meeting_point: props.departure?.meeting_point ?? '',
    meeting_instructions: props.departure?.meeting_instructions ?? '',
    status: props.departure?.status ?? 'scheduled',
});

const submit = () => {
    if (isEditing.value) {
        form.put(route('departures.update', props.departure.id));
        return;
    }

    form.post(route('departures.store'));
};
</script>

<template>
    <Head :title="isEditing ? 'Edit Departure' : 'Create Departure'" />

    <AppLayout>
        <div class="mx-auto max-w-3xl space-y-6">
            <div>
                <Link
                    :href="route('departures.index')"
                    class="text-sm font-medium text-blue-600"
                >
                    ← Back to Departures
                </Link>

                <h1 class="mt-3 text-2xl font-bold text-gray-900">
                    {{ isEditing ? 'Edit Departure' : 'Create Departure' }}
                </h1>
            </div>

            <form
                class="space-y-6 rounded-xl border border-gray-200 bg-white p-6"
                @submit.prevent="submit"
            >
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Trip
                    </label>

                    <select
                        v-model="form.trip_id"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm"
                    >
                        <option value="" disabled>Select trip</option>

                        <option
                            v-for="trip in trips"
                            :key="trip.id"
                            :value="trip.id"
                        >
                            {{ trip.name }}
                        </option>
                    </select>

                    <p v-if="form.errors.trip_id" class="mt-1 text-sm text-red-600">
                        {{ form.errors.trip_id }}
                    </p>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Boat
                    </label>

                    <select
                        v-model="form.boat_id"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm"
                    >
                        <option value="" disabled>Select boat</option>

                        <option
                            v-for="boat in boats"
                            :key="boat.id"
                            :value="boat.id"
                        >
                            {{ boat.name }}
                        </option>
                    </select>

                    <p v-if="form.errors.boat_id" class="mt-1 text-sm text-red-600">
                        {{ form.errors.boat_id }}
                    </p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Departure Date and Time
                        </label>

                        <input
                            v-model="form.departure_at"
                            type="datetime-local"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm"
                        />

                        <p v-if="form.errors.departure_at" class="mt-1 text-sm text-red-600">
                            {{ form.errors.departure_at }}
                        </p>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Arrival Date and Time
                        </label>

                        <input
                            v-model="form.arrival_at"
                            type="datetime-local"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm"
                        />

                        <p v-if="form.errors.arrival_at" class="mt-1 text-sm text-red-600">
                            {{ form.errors.arrival_at }}
                        </p>
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Meeting Point
                    </label>

                    <input
                        v-model="form.meeting_point"
                        type="text"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm"
                        placeholder="Example: Marina Port"
                    />

                    <p v-if="form.errors.meeting_point" class="mt-1 text-sm text-red-600">
                        {{ form.errors.meeting_point }}
                    </p>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Meeting Instructions
                    </label>

                    <textarea
                        v-model="form.meeting_instructions"
                        rows="3"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm"
                        placeholder="Additional information for guests..."
                    />

                    <p v-if="form.errors.meeting_instructions" class="mt-1 text-sm text-red-600">
                        {{ form.errors.meeting_instructions }}
                    </p>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Status
                    </label>

                    <select
                        v-model="form.status"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm"
                    >
                        <option value="scheduled">Scheduled</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="completed">Completed</option>
                    </select>

                    <p v-if="form.errors.status" class="mt-1 text-sm text-red-600">
                        {{ form.errors.status }}
                    </p>
                </div>

                <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
                    <Link
                        :href="route('departures.index')"
                        class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm"
                    >
                        Cancel
                    </Link>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white disabled:opacity-50"
                    >
                        {{ form.processing ? 'Saving...' : 'Save Departure' }}
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
