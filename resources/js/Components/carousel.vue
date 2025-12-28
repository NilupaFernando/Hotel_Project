<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3'

const currentSlide = ref(0);
const totalSlides = 3;

const exploreAllHotels = () => {
  if (isUser()) {
    router.get(route('user.hotel.index'));
  } else {
    router.get(route('explore.hotels'));
  }
};

const props = defineProps({
  user: {
    type: Object,
  }
});
const isUser = () => {
  return props.users?.role === 'user';
};
</script>

<template>
  <div class="relative flex items-center justify-center h-screen text-white hero-section">
    <div class="absolute inset-0 bg-black bg-opacity-5"></div>

    <!-- middle content -->
    <div class="container relative z-10 px-6 mx-auto lg:px-12 ">
      <div class="max-w-4xl mx-auto">
        <div class="p-8 bg-black border-2 border-orange-500 rounded-lg shadow-lg md:p-12 bg-opacity-40">
          <h1 class="mb-4 text-4xl font-extrabold leading-tight md:text-6xl">Find Your Perfect Stay.</h1>
          <h2 class="mb-6 text-lg font-medium md:text-2xl">Easy, Fast & Hassle-Free Booking!</h2>

          <div class="flex flex-wrap gap-8 mb-8 text-sm">
            <div class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
              </svg>
              Bedrooms
            </div>
            <div class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
              Apartments
            </div>
            <div class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 10h11M9 21V3m0 18l-3-3m3 3l3-3" />
              </svg>
              Room Service
            </div>
            <div class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              Parking
            </div>
          </div>

          <button @click="exploreAllHotels"
            class="px-8 py-3 font-semibold text-white transition-transform transform bg-orange-500 rounded-full shadow-md hover:bg-orange-600 hover:scale-105">
            Explore Now
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style>
.hero-section {
  background-image: url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1770&q=80');
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
}
</style>
