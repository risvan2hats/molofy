<script setup>
import { computed, ref, watch } from 'vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Common/Layouts/AppLayout.vue';

const props = defineProps({
    operators: {
        type: Array,
        default: () => [],
    },
    departures: {
        type: Array,
        default: () => [],
    },
});

const page = usePage();
const selectedOperatorId = ref(props.operators[0]?.id ?? '');

const selectedOperator = computed(() =>
    props.operators.find((op) => op.id === Number(selectedOperatorId.value))
);

const form = useForm({
    departure_ids: selectedOperator.value?.departures?.map((d) => d.id) ?? [],
});

watch(selectedOperatorId, () => {
    form.departure_ids = selectedOperator.value?.departures?.map((d) => d.id) ?? [];
    form.clearErrors();
});

const formatDate = (date) =>
    new Intl.DateTimeFormat('en-GB', {
        dateStyle: 'medium',
        timeStyle: 'short',
        timeZone: 'Europe/Rome',
    }).format(new Date(date));

const selectAll = () => {
    form.departure_ids = props.departures.map((d) => d.id);
};

const clearAll = () => {
    form.departure_ids = [];
};

const submit = () => {
    if (!selectedOperatorId.value) return;

    form.put(route('admin.operators.departures.sync', selectedOperatorId.value), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Assign Departures to Operator" />

    <AppLayout>
        <div class="mx-auto max-w-3xl space-y-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Assign Departures to Operator
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Select an operator and choose which departures they can manage and check in.
                </p>
            </div>

            <div
                v-if="page.props.flash?.success"
                class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700"
            >
                {{ page.props.flash.success }}
            </div>

            <div
                v-if="!operators.length"
                class="rounded-xl border border-dashed border-gray-300 bg-white p-10 text-center text-sm text-gray-500"
            >
                No active operators found.
            </div>

            <form
                v-else
                class="space-y-6 rounded-xl border border-gray-200 bg-white p-6"
                @submit.prevent="submit"
            >
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Operator
                    </label>
                    <select
                        v-model="selectedOperatorId"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                        <option
                            v-for="operator in operators"
                            :key="operator.id"
                            :value="operator.id"
                        >
                            {{ operator.name }} ({{ operator.email }})
                        </option>
                    </select>
                </div>

                <div>
                    <div class="mb-3 flex items-center justify-between">
                        <label class="text-sm font-medium text-gray-700">
                            Departures ({{ form.departure_ids.length }} selected)
                        </label>

                        <div v-if="departures.length" class="flex gap-3 text-xs font-medium">
                            <button
                                type="button"
                                class="text-blue-600 hover:underline"
                                @click="selectAll"
                            >
                                Select all
                            </button>
                            <button
                                type="button"
                                class="text-gray-500 hover:underline"
                                @click="clearAll"
                            >
                                Clear
                            </button>
                        </div>
                    </div>

                    <div
                        v-if="!departures.length"
                        class="rounded-lg border border-dashed border-gray-200 p-6 text-center text-sm text-gray-500"
                    >
                        No departures available.
                    </div>

                    <div v-else class="max-h-96 space-y-2 overflow-y-auto pr-1">
                        <label
                            v-for="departure in departures"
                            :key="departure.id"
                            class="flex cursor-pointer items-start gap-3 rounded-lg border border-gray-200 p-3.5 transition hover:bg-gray-50"
                        >
                            <input
                                v-model="form.departure_ids"
                                type="checkbox"
                                :value="departure.id"
                                class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                            />

                            <div class="flex-1 text-sm">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <span class="font-semibold text-gray-900">
                                        {{ departure.trip?.name }}
                                    </span>
                                    <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-xs capitalize text-gray-700">
                                        {{ departure.status }}
                                    </span>
                                </div>

                                <p class="mt-1 text-xs text-gray-500">
                                    {{ formatDate(departure.departure_at) }}
                                    <span v-if="departure.boat"> · {{ departure.boat.name }}</span>
                                    <span v-if="departure.meeting_point"> · {{ departure.meeting_point }}</span>
                                </p>
                            </div>
                        </label>
                    </div>

                    <p v-if="form.errors.departure_ids" class="mt-1 text-sm text-red-600">
                        {{ form.errors.departure_ids }}
                    </p>
                </div>

                <div class="flex justify-end border-t border-gray-100 pt-5">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-50"
                    >
                        {{ form.processing ? 'Saving...' : 'Save Assignments' }}
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
