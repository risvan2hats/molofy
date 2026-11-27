
<script setup>
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from '@/Common/Layouts/GuestLayout.vue';
</script>

<template>
    <Head title="Dashboard Access" />

    <GuestLayout>
        <div class="flex min-h-[75vh] items-center justify-center px-6 py-16">
            <div class="w-full max-w-4xl">
                <div class="mb-10 text-center">
                    <h1 class="text-3xl font-bold text-gray-900">
                        Dashboard Access
                    </h1>

                    <p class="mt-3 text-gray-500">
                        Select your account type to continue.
                    </p>
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <!-- Admin Login -->
                    <div class="rounded-2xl border border-gray-200 p-8 shadow-sm transition hover:border-blue-300 hover:shadow-md">
                        <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-xl bg-blue-50 text-blue-700">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-7 w-7"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-7a2 2 0 00-2-2H6a2 2 0 00-2 2v7a2 2 0 002 2zm10-11V7a4 4 0 00-8 0v4h8z"
                                />
                            </svg>
                        </div>

                        <h2 class="text-xl font-semibold text-gray-900">
                            Login as Admin
                        </h2>

                        <p class="mt-3 text-sm leading-6 text-gray-500">
                            Access the administration dashboard to manage
                            boats, trips, departures, bookings and operators.
                        </p>

                        <Link
                            :href="route('login')"
                            class="mt-8 inline-flex w-full items-center justify-center rounded-lg bg-blue-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-800"
                        >
                            Admin Login
                        </Link>
                    </div>

                    <!-- Operator Login -->
                    <div class="rounded-2xl border border-gray-200 p-8 shadow-sm transition hover:border-emerald-300 hover:shadow-md">
                        <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-7 w-7"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m16 0v-2a4 4 0 00-3-3.87M14 3.13a4 4 0 010 7.75M14 7a4 4 0 11-8 0 4 4 0 018 0z"
                                />
                            </svg>
                        </div>

                        <h2 class="text-xl font-semibold text-gray-900">
                            Login as Operator
                        </h2>

                        <p class="mt-3 text-sm leading-6 text-gray-500">
                            Access assigned departures, scan booking QR
                            codes and manage passenger boarding.
                        </p>

                        <Link
                            :href="route('operator.login')"
                            class="mt-8 inline-flex w-full items-center justify-center rounded-lg bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700"
                        >
                            Operator Login
                        </Link>
                    </div>
                </div>

                <div class="mt-8 text-center">
                    <Link
                        href="/"
                        class="text-sm font-medium text-gray-500 hover:text-blue-700"
                    >
                        Back to Homepage
                    </Link>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
