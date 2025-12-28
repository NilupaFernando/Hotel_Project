<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps({
  topHotels: Array,
  default: () => [],
});

const roomIndex = (id) => {
    router.get(route('view-hotel.show', { id: id }));
};
</script>

<template>
  <section class="trending-hotels-section">
    <div class="max-w-7xl mx-auto px-6">
      <div class="flex justify-between items-center mb-8">
        <div>
          <h2 class="text-3xl font-bold text-orange-500" style="font-family: 'Calistoga', serif;" data-aos="fade-right">
            Trending Hotels & Resorts
          </h2>
          <p class="text-gray-600 mt-2" data-aos="fade-right" data-aos-delay="100">
            Handpicked accommodations for an unforgettable stay
          </p>
        </div>
        <a :href="route('explore.hotels')" class="text-orange-400 hover:text-emerald-700 font-medium"
          data-aos="fade-left">
          View all hotels →
        </a>
      </div>

      <div class="grid grid-cols-12 gap-6">
        <!-- First two large hotels -->
        <div v-for="hotel in (topHotels || []).slice(0, 2)" :key="hotel.id" class="col-span-12 md:col-span-6">
          <div class="hotel-card" @click="roomIndex(hotel.id)">
            <div class="relative overflow-hidden rounded-lg h-80 group">
              <img 
  :src="`/storage/${hotel.thumbnail_image}`" 
  :alt="hotel.name"
  class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
/>
              <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
              <div class="absolute bottom-4 left-4 text-white">
                <h3 class="font-semibold text-xl">{{ hotel.name }}</h3>
                <div class="flex items-center mt-2">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-500" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                      d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                      clip-rule="evenodd" />
                  </svg>
                  <span class="ml-1">{{ hotel.location }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Remaining smaller hotels -->
        <div v-for="hotel in (topHotels || []).slice(2)" :key="hotel.id" class="col-span-12 md:col-span-4">
          <div class="hotel-card" @click="roomIndex(hotel.id)">
            <div class="relative overflow-hidden rounded-lg h-48 group">
              <img 
  :src="`/storage/${hotel.thumbnail_image}`" 
  :alt="hotel.name"
  class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
/>
              <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
              <div class="absolute bottom-4 left-4 text-white">
                <h3 class="font-semibold text-lg">{{ hotel.name }}</h3>
                <div class="flex items-center mt-1">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-orange-500" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                      d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                      clip-rule="evenodd" />
                  </svg>
                  <span class="ml-1 text-sm">{{ hotel.location }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
.trending-hotels-section {
  @apply py-12 bg-gray-50;
}

.hotel-card {
  @apply bg-white rounded-lg shadow-md overflow-hidden;
}
</style>