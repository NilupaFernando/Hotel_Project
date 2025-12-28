<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { UserIcon, MailIcon, LockIcon } from 'lucide-vue-next';
import { ref, watch } from 'vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const nameError = ref('');

const validateName = () => {
    // Only letters (upper/lowercase) and spaces allowed
    const regex = /^[A-Za-z\s]+$/;
    if (!regex.test(form.name)) {
        nameError.value = 'Name can only contain letters and spaces.';
        return false;
    }
    nameError.value = '';
    return true;
};
const showPassword = ref(false)
const showConfirmPassword = ref(false)

const togglePassword = () => {
  showPassword.value = !showPassword.value
}

const toggleConfirmPassword = () => {
  showConfirmPassword.value = !showConfirmPassword.value
}

// Real-time validation for name
watch(() => form.name, () => {
    validateName();
});

const submit = () => {
    if (!validateName()) {
        return;
    }
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <div class="w-full max-w-md mx-auto">
            <!-- Logo for mobile view -->
            <div class="md:hidden flex justify-center mb-6">
                <ApplicationLogo class="w-16 h-16" />
            </div>

            <h1 class="text-3xl font-bold text-gray-800 mb-1">Create Account</h1>
            <p class="text-gray-600 mb-8">Sign up now and get full access to our app.</p>

            <form @submit.prevent="submit">
                <!-- Name Input -->
                <div class="mb-4">
                    <InputLabel for="name" value="Full Name" class="text-gray-700" />
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <UserIcon class="h-5 w-5 text-gray-400" />
                        </div>
                        <TextInput
                            id="name"
                            type="text"
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500"
                            v-model="form.name"
                            required
                            autofocus
                            autocomplete="name"
                        />
                    </div>
                    <InputError class="mt-2" :message="form.errors.name" />
                    <InputError class="mt-2" :message="nameError" />
                </div>

                <!-- Email Input -->
                <div class="mb-4">
                    <InputLabel for="email" value="Email" class="text-gray-700" />
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <MailIcon class="h-5 w-5 text-gray-400" />
                        </div>
                        <TextInput
                            id="email"
                            type="email"
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500"
                            v-model="form.email"
                            required
                            autocomplete="username"
                        />
                    </div>
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <!-- Password Input -->
<div class="mb-4">
  <InputLabel for="password" value="Password" class="text-gray-700" />

  <div class="mt-1 relative">
    <!-- Lock Icon -->
    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
      <LockIcon class="h-5 w-5 text-gray-400" />
    </div>

    <!-- Password Input -->
    <TextInput
      id="password"
      :type="showPassword ? 'text' : 'password'"
      class="block w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500"
      v-model="form.password"
      required
      autocomplete="new-password"
    />

    <!-- Toggle Icon -->
    <span
      class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer"
      @click="togglePassword"
    >
      <!-- Eye open -->
      <svg
        v-if="!showPassword"
        xmlns="http://www.w3.org/2000/svg"
        class="h-5 w-5 text-gray-400"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0c0 5-4.477 9-10 9S2 17 2 12 6.477 3 12 3s10 4 10 9z"
        />
      </svg>

      <!-- Eye closed -->
      <svg
        v-else
        xmlns="http://www.w3.org/2000/svg"
        class="h-5 w-5 text-gray-400"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M3 3l18 18M9.88 9.88A3 3 0 0012 15a3 3 0 002.12-5.12M7.05 7.05A9.956 9.956 0 002 12c0 5 4.477 9 10 9a9.956 9.956 0 005.95-1.95M17.95 17.95A9.956 9.956 0 0022 12c0-5-4.477-9-10-9a9.956 9.956 0 00-5.95 1.95"
        />
      </svg>
    </span>
  </div>

  <InputError class="mt-2" :message="form.errors.password" />
</div>

<!-- Confirm Password Input -->
<div class="mb-4">
  <InputLabel for="password_confirmation" value="Confirm Password" class="text-gray-700" />

  <div class="mt-1 relative">
    <!-- Lock Icon -->
    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
      <LockIcon class="h-5 w-5 text-gray-400" />
    </div>

    <!-- Confirm Password Input -->
    <TextInput
      id="password_confirmation"
      :type="showConfirmPassword ? 'text' : 'password'"
      class="block w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500"
      v-model="form.password_confirmation"
      required
      autocomplete="new-password"
    />

    <!-- Toggle Icon -->
    <span
      class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer"
      @click="toggleConfirmPassword"
    >
      <!-- Eye open -->
      <svg
        v-if="!showConfirmPassword"
        xmlns="http://www.w3.org/2000/svg"
        class="h-5 w-5 text-gray-400"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0c0 5-4.477 9-10 9S2 17 2 12 6.477 3 12 3s10 4 10 9z"
        />
      </svg>

      <!-- Eye closed -->
      <svg
        v-else
        xmlns="http://www.w3.org/2000/svg"
        class="h-5 w-5 text-gray-400"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M3 3l18 18M9.88 9.88A3 3 0 0012 15a3 3 0 002.12-5.12M7.05 7.05A9.956 9.956 0 002 12c0 5 4.477 9 10 9a9.956 9.956 0 005.95-1.95M17.95 17.95A9.956 9.956 0 0022 12c0-5-4.477-9-10-9a9.956 9.956 0 00-5.95 1.95"
        />
      </svg>
    </span>
  </div>

  <InputError class="mt-2" :message="form.errors.password_confirmation" />
</div>
                <!-- Submit Button -->
                <PrimaryButton
                    class="w-full mt-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition duration-200 flex items-center justify-center"
                    :class="{ 'opacity-75 cursor-not-allowed': form.processing }"
                    :disabled="form.processing"
                >
                    Register
                </PrimaryButton>

                <!-- Login Link -->
                <div class="text-center mt-6">
                    <span class="text-gray-600 text-sm">Already have an account?</span>
                    <Link :href="route('login')" class="ml-1 text-sm font-medium text-primary-600 hover:text-primary-500">
                        Sign in
                    </Link>
                </div>
            </form>
        </div>
    </GuestLayout>
</template>

<style scoped>
:deep(.primary-button) {
  background-color: #ED9B40;
  transition: all 0.3s ease;
}

:deep(.primary-button:hover) {
  background-color: #ec8c1d;
}

/* Define primary colors to match your orange theme */
:root {
  --primary-50: #fff8f1;
  --primary-100: #feecdc;
  --primary-200: #fcd9bd;
  --primary-300: #fdba8c;
  --primary-400: #ff8a4c;
  --primary-500: #ff5a1f;
  --primary-600: #ED9B40;
  --primary-700: #ec8c1d;
  --primary-800: #c2410c;
  --primary-900: #9a3412;
}
</style>
