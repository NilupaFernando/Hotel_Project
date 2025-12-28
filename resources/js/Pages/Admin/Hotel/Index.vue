<script setup>
import AdminAuthenticatedLayout from '@/Layouts/Admin/AdminAuthenticatedLayout.vue';
import PropertyCard from '@/Components/PropertyCard.vue';
import { ref, computed } from 'vue';
import { MagnifyingGlassIcon } from '@heroicons/vue/24/outline';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  hotels: Object,
  favorites: Array,
  users: Object,
  userCount: Number,
});

const searchQuery = ref('');

const filteredHotels = computed(() => {
  if (searchQuery.value.trim() === '') return props.hotels;
  return props.hotels.filter(hotel =>
    hotel.name?.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});

const addNewHotel = () => {
  router.get(route('hotel.create'));
};
</script>

<template>
  <AdminAuthenticatedLayout>
    <!-- Hero Section -->
    <div class="bg-gray-900">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
          <div class="flex-1">
            <h1 class="text-3xl sm:text-3xl font-bold text-orange-500 animate-slide-in-left">
              Active Hotels
            </h1>
            <p class="mt-2 text-base text-gray-600 animate-slide-in-left delay-100">
              Manage properties and view insights
            </p>
          </div>

          <!-- Search and Add Hotel Section -->
          <div class="flex flex-col sm:flex-row gap-4 items-stretch sm:items-center">
            <div class="relative">
              <label for="search" class="sr-only">Search hotels</label>
              <MagnifyingGlassIcon class="h-5 w-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" />
              <input
                id="search"
                v-model="searchQuery"
                type="text"
                placeholder="Search hotels..."
                class="pl-10 pr-4 py-2 w-full sm:w-64 rounded-lg border border-gray-600 bg-gray-700 text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition duration-150 ease-in-out"
              />
            </div>
            <button
              @click="addNewHotel"
              class="inline-flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-lg transition duration-300 ease-in-out shadow-sm hover:shadow-md transform hover:scale-105 font-montserrat"
            >
              <span class="mr-2">+</span>
              Add New Hotel
            </button>
          </div>
        </div>
      </div>

      <!-- Main Content -->
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <div class="bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-700 transition duration-300 ease-in-out hover:shadow-md hover:scale-105 animate-fade-in">
            <div class="flex items-center">
              <div class="p-3 bg-orange-900 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
              </div>
              <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-200 font-montserrat">Hotel Count</h3>
                <p class="text-2xl font-bold text-orange-400 font-montserrat">{{ props.hotels.length }}</p>
              </div>
            </div>
          </div>

          <div class="bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-700 transition duration-300 ease-in-out hover:shadow-md hover:scale-105 animate-fade-in">
            <div class="flex items-center">
              <div class="p-3 bg-green-900 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-200 font-montserrat">Active Users</h3>
                <p class="text-2xl font-bold text-green-400 font-montserrat">{{ props.userCount }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Property Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
          <PropertyCard
            :hotels="filteredHotels"
            :favorites="props.favorites"
            :users="props.users"
            :admin="props.users.role === 'admin'"
            class="transition duration-300 ease-in-out hover:shadow-lg hover:-translate-y-1"
          />
        </div>

        <!-- No Hotels Found Message -->
        <div v-if="filteredHotels.length === 0" class="text-center py-8">
          <p class="text-gray-400 text-lg font-montserrat">No hotels found matching your search.</p>
        </div>
      </div>
    </div>
  </AdminAuthenticatedLayout>
</template>

<style>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap');

.font-montserrat {
  font-family: 'Montserrat', sans-serif;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  animation: fadeIn 0.5s ease-out;
}

@keyframes slideInLeft {
  from {
    opacity: 0;
    transform: translateX(-30px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

.animate-slide-in-left {
  animation: slideInLeft 0.6s ease-out;
}
</style>
