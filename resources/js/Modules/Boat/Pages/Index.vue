<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Common/Layouts/AppLayout.vue';
import BasePagination from '@/Common/Components/BasePagination.vue';

defineOptions({
    layout: AppLayout,
});

defineProps({
    boats: {
        type: Object,
        required: true,
    },
});

const deleteBoat = (boat) => {
    if (!confirm(`Delete "${boat.name}"?`)) {
        return;
    }

    router.delete(route('boats.destroy', boat.id));
};
</script>

<template>
    <Head title="Boats" />

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    {{ $t('navigation.boats') }}
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Manage your boat fleet.
                </p>
            </div>

            <Link
                :href="route('boats.create')"
                class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700"
            >
                Add Boat
            </Link>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Capacity</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="boat in boats.data"
                        :key="boat.id"
                        class="border-t border-gray-100"
                    >
                        <td class="px-6 py-4 font-medium text-gray-800">
                            {{ boat.name }}
                        </td>

                        <td class="px-6 py-4">
                            {{ boat.capacity }}
                        </td>

                        <td class="px-6 py-4 capitalize">
                            {{ boat.status }}
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex justify-end gap-3">
                                <Link
                                    :href="route('boats.edit', boat.id)"
                                    class="font-medium text-blue-600 hover:text-blue-800"
                                >
                                    Edit
                                </Link>

                                <button
                                    type="button"
                                    class="font-medium text-red-600 hover:text-red-800"
                                    @click="deleteBoat(boat)"
                                >
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr v-if="!boats.data.length">
                        <td
                            colspan="4"
                            class="px-6 py-10 text-center text-gray-500"
                        >
                            {{ $t('common.no_records') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <BasePagination :links="boats.links" />
    </div>
</template>