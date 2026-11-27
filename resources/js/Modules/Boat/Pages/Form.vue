<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Common/Layouts/AppLayout.vue';

const props = defineProps({
    boat: {
        type: Object,
        default: null,
    },
});

const isEditing = Boolean(props.boat);

const form = useForm({
    name: props.boat?.name ?? '',
    description: props.boat?.description ?? '',
    capacity: props.boat?.capacity ?? 10,
    status: props.boat?.status ?? 'active',
});

const submit = () => {
    if (isEditing) {
        form.put(route('boats.update', props.boat.id));
        return;
    }

    form.post(route('boats.store'));
};
</script>

<template>
    <Head :title="isEditing ? 'Edit Boat' : 'Add Boat'" />

    <AppLayout>
        <div class="mx-auto max-w-2xl space-y-6">
            <div>
                <Link
                    :href="route('boats.index')"
                    class="text-sm font-medium text-blue-600 hover:underline"
                >
                    ← Back to boats
                </Link>

                <h1 class="mt-3 text-2xl font-bold text-gray-900">
                    {{ isEditing ? 'Edit Boat' : 'Add Boat' }}
                </h1>
            </div>

            <form
                class="space-y-5 rounded-xl border border-gray-200 bg-white p-6"
                @submit.prevent="submit"
            >
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Boat Name
                    </label>
                    <input
                        v-model="form.name"
                        type="text"
                        required
                        class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    />
                    <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">
                        {{ form.errors.name }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Description
                    </label>
                    <textarea
                        v-model="form.description"
                        rows="3"
                        class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    />
                    <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                        {{ form.errors.description }}
                    </p>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Capacity
                        </label>
                        <input
                            v-model="form.capacity"
                            type="number"
                            min="1"
                            required
                            class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        />
                        <p v-if="form.errors.capacity" class="mt-1 text-sm text-red-600">
                            {{ form.errors.capacity }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Status
                        </label>
                        <select
                            v-model="form.status"
                            class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                        <p v-if="form.errors.status" class="mt-1 text-sm text-red-600">
                            {{ form.errors.status }}
                        </p>
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
                    <Link
                        :href="route('boats.index')"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                        Cancel
                    </Link>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-50"
                    >
                        {{ form.processing ? 'Saving...' : 'Save Boat' }}
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
