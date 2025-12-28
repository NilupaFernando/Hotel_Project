<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="w-full max-w-5xl flex overflow-hidden rounded-xl shadow-2xl">
      <!-- Image Section -->
      <div class="hidden md:block md:w-1/2 bg-cover bg-center"
           style="background-image: url('/img/login.jpg');">
        <div class="h-full w-full bg-black/30 flex flex-col justify-between p-12">
          <div>
            <ApplicationLogo class="w-20 h-20" />
          </div>
          <div class="text-white">
            <h2 class="text-3xl font-bold mb-2">Welcome Back</h2>
            <p class="text-gray-200">Access your account and manage your experience</p>
          </div>
        </div>
      </div>

      <!-- Form Section -->
      <div class="w-full md:w-1/2 bg-white p-8">
        <slot />
      </div>
    </div>
  </div>
</template>
