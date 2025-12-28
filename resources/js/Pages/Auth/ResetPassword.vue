<script setup>
import { ref } from 'vue'
import GuestLayout from '@/Layouts/GuestLayout.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { LockIcon } from 'lucide-vue-next'

const props = defineProps({
  email: {
    type: String,
    required: true,
  },
  token: {
    type: String,
    required: true,
  },
})

// Form setup
const form = useForm({
  token: props.token,
  email: props.email,
  password: '',
  password_confirmation: '',
})

// Toggle states
const showPassword = ref(false)
const showConfirmPassword = ref(false)

const togglePassword = () => {
  showPassword.value = !showPassword.value
}
const toggleConfirmPassword = () => {
  showConfirmPassword.value = !showConfirmPassword.value
}

const submit = () => {
  form.post(route('password.store'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
}
</script>

<template>
  <GuestLayout>
    <Head title="Reset Password" />

    <form @submit.prevent="submit">
      <!-- Email -->
      <div>
        <InputLabel for="email" value="Email" />

        <TextInput
          id="email"
          type="email"
          class="mt-1 block w-full"
          v-model="form.email"
          required
          autofocus
          autocomplete="username"
        />

        <InputError class="mt-2" :message="form.errors.email" />
      </div>

      <!-- New Password -->
      <div class="mt-4">
        <InputLabel for="password" value="New Password" />
        <div class="relative mt-1">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <LockIcon class="h-5 w-5 text-gray-400" />
          </div>

          <TextInput
            id="password"
            :type="showPassword ? 'text' : 'password'"
            class="block w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500"
            v-model="form.password"
            required
            autocomplete="new-password"
          />

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

      <!-- Confirm Password -->
      <div class="mt-4">
        <InputLabel for="password_confirmation" value="Confirm Password" />
        <div class="relative mt-1">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <LockIcon class="h-5 w-5 text-gray-400" />
          </div>

          <TextInput
            id="password_confirmation"
            :type="showConfirmPassword ? 'text' : 'password'"
            class="block w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500"
            v-model="form.password_confirmation"
            required
            autocomplete="new-password"
          />

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

      <div class="mt-4 flex items-center justify-end">
        <PrimaryButton
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          Reset Password
        </PrimaryButton>
      </div>
    </form>
  </GuestLayout>
</template>
