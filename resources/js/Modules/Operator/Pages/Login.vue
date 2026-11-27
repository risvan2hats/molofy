<script setup>
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    email: 'operator1@molofy.com',
    pin: '123456',
});

const submit = () => {
    form.post(route('operator.login.submit'));
};
</script>

<template>
    <Head title="Operator Login" />

    <div class="flex min-h-screen items-center justify-center bg-gray-100 px-6 py-12">
        <div class="w-full max-w-md rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
            <div class="text-center">
                <h1 class="text-2xl font-bold text-blue-700">Molofy</h1>
                <h2 class="mt-6 text-xl font-semibold text-gray-900">
                    Operator check-in
                </h2>
                <p class="mt-2 text-sm text-gray-500">
                    Sign in using your assigned email and PIN.
                </p>
            </div>

            <form class="mt-8 space-y-5" @submit.prevent="submit">
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Email address
                    </label>
                    <input
                        v-model="form.email"
                        type="email"
                        autocomplete="username"
                        required
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    />
                    <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">
                        {{ form.errors.email }}
                    </p>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Six-digit PIN
                    </label>
                    <input
                        v-model="form.pin"
                        type="password"
                        inputmode="numeric"
                        maxlength="6"
                        autocomplete="current-password"
                        required
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    />
                    <p v-if="form.errors.pin" class="mt-1 text-sm text-red-600">
                        {{ form.errors.pin }}
                    </p>
                </div>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full rounded-lg bg-blue-700 px-5 py-3 font-semibold text-white hover:bg-blue-800 disabled:opacity-50"
                >
                    {{ form.processing ? 'Signing in...' : 'Sign in' }}
                </button>
            </form>
        </div>
    </div>
</template>
