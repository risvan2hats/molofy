
<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Common/Layouts/AppLayout.vue';
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout>
        <div class="space-y-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Dashboard
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Welcome to your Molofy administration panel.
                </p>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-6">
                <h2 class="text-lg font-semibold text-gray-900">
                    Welcome to Molofy
                </h2>

                <p class="mt-2 text-sm text-gray-600">
                    Manage your boats, trips, bookings and other platform
                    operations from here.
                </p>
            </div>
        </div>
    </AppLayout>
</template>
