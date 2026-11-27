<script setup>
import AppLayout from '@/Common/Layouts/AppLayout.vue';
import BaseButton from '@/Common/Components/BaseButton.vue';
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
</script>

<template>
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

            <BaseButton>
                Add Boat
            </BaseButton>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Capacity</th>
                        <th class="px-6 py-4">Status</th>
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

                        <td class="px-6 py-4">
                            {{ boat.status }}
                        </td>
                    </tr>

                    <tr v-if="!boats.data.length">
                        <td
                            colspan="3"
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