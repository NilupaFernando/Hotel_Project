<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { EnvelopeIcon } from '@heroicons/vue/24/outline';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password" />

        <div class="mb-4 text-sm text-gray-600 text-center">
            Forgot your password? No problem.<br />
            Enter your registered email address below, and we’ll send you a reset link.
        </div>

        <!-- Success Message -->
        <div
            v-if="status"
            class="mb-4 text-sm font-medium text-green-600 bg-green-50 border border-green-200 rounded-lg p-2 text-center"
        >
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="max-w-md mx-auto bg-white p-6 rounded-xl shadow">
            <!-- Email Input with Icon -->
            <div class="relative">
                <InputLabel for="email" value="Email Address" class="text-gray-700" />
                <div class="mt-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <EnvelopeIcon class="h-5 w-5 text-gray-400" />
                    </div>
                    <TextInput
                        id="email"
                        type="email"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm 
                        focus:ring-primary-500 focus:border-primary-500"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                    />
                </div>
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <!-- Submit Button -->
            <div class="mt-6 flex items-center justify-end">
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    class="transition duration-150 ease-in-out"
                >
                    Send Password Reset Link
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
