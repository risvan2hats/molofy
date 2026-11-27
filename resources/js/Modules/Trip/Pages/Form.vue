<script setup>
import { computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Common/Layouts/AppLayout.vue';

const props = defineProps({
    trip: {
        type: Object,
        default: null,
    },
});

const isEditing = computed(() => Boolean(props.trip));

const form = useForm({
    name: props.trip?.name ?? '',
    slug: props.trip?.slug ?? '',
    description: props.trip?.description ?? '',
    duration_minutes: props.trip?.duration_minutes ?? 60,
    price_adult: props.trip?.price_adult ?? 0,
    price_child: props.trip?.price_child ?? 0,
    price_infant: props.trip?.price_infant ?? 0,
    is_active: props.trip?.is_active ?? true,
});

const submit = () => {
    if (isEditing.value) {
        form.put(route('trips.update', props.trip.id));
        return;
    }

    form.post(route('trips.store'));
};
</script>

<template>
    <Head :title="isEditing ? 'Edit Trip' : 'Create Trip'" />

    <AppLayout>
        <div class="mx-auto max-w-3xl space-y-6">
            <div>
                <Link
                    :href="route('trips.index')"
                    class="text-sm font-medium text-blue-600 hover:text-blue-800"
                >
                    ← Back to Trips
                </Link>

                <h1 class="mt-3 text-2xl font-bold text-gray-900">
                    {{ isEditing ? 'Edit Trip' : 'Create Trip' }}
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Enter the trip details and pricing.
                </p>
            </div>

            <form
                class="space-y-6 rounded-xl border border-gray-200 bg-white p-6"
                @submit.prevent="submit"
            >
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Trip Name
                    </label>

                    <input
                        v-model="form.name"
                        type="text"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none"
                        placeholder="Example: Coastal Boat Tour"
                    />

                    <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">
                        {{ form.errors.name }}
                    </p>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        URL Slug
                    </label>

                    <input
                        v-model="form.slug"
                        type="text"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm"
                        placeholder="coastal-boat-tour"
                    />

                    <p class="mt-1 text-xs text-gray-500">
                        Leave blank to generate automatically from the trip name.
                    </p>

                    <p v-if="form.errors.slug" class="mt-1 text-sm text-red-600">
                        {{ form.errors.slug }}
                    </p>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Description
                    </label>

                    <textarea
                        v-model="form.description"
                        rows="4"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm"
                        placeholder="Describe this trip..."
                    />

                    <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                        {{ form.errors.description }}
                    </p>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Duration (minutes)
                    </label>

                    <input
                        v-model.number="form.duration_minutes"
                        type="number"
                        min="1"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm"
                    />

                    <p v-if="form.errors.duration_minutes" class="mt-1 text-sm text-red-600">
                        {{ form.errors.duration_minutes }}
                    </p>
                </div>

                <div class="border-t border-gray-100 pt-5">
                    <h2 class="mb-4 text-base font-semibold text-gray-900">
                        Ticket Pricing
                    </h2>

                    <div class="grid gap-4 sm:grid-cols-3">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Adult (€)
                            </label>

                            <input
                                v-model.number="form.price_adult"
                                type="number"
                                min="0"
                                step="0.01"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"
                            />

                            <p v-if="form.errors.price_adult" class="mt-1 text-sm text-red-600">
                                {{ form.errors.price_adult }}
                            </p>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Child (€)
                            </label>

                            <input
                                v-model.number="form.price_child"
                                type="number"
                                min="0"
                                step="0.01"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"
                            />

                            <p v-if="form.errors.price_child" class="mt-1 text-sm text-red-600">
                                {{ form.errors.price_child }}
                            </p>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Infant (€)
                            </label>

                            <input
                                v-model.number="form.price_infant"
                                type="number"
                                min="0"
                                step="0.01"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"
                            />

                            <p v-if="form.errors.price_infant" class="mt-1 text-sm text-red-600">
                                {{ form.errors.price_infant }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-5">
                    <label class="flex items-center gap-3">
                        <input
                            v-model="form.is_active"
                            type="checkbox"
                            class="h-4 w-4 rounded border-gray-300 text-blue-600"
                        />

                        <span class="text-sm font-medium text-gray-700">
                            Active trip
                        </span>
                    </label>
                </div>

                <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
                    <Link
                        :href="route('trips.index')"
                        class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                        Cancel
                    </Link>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-50"
                    >
                        {{ form.processing ? 'Saving...' : 'Save Trip' }}
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
