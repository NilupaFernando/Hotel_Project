<script setup>
import HeroSection from '@/Components/HeroSection.vue';
import PropertyCard from '@/Components/PropertyCard.vue';
import AuthenticatedLayout from '@/Layouts/User/AuthenticatedLayout.vue';
import WelcomeLayout from '@/Layouts/WelcomeLayout.vue';

import { ref } from 'vue';
const props = defineProps({
            district: Object,
            hotels: Array,
            users: Object,
            favorites: Array
        });
</script>

<template>
  <WelcomeLayout>
    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100">
      <div>
        <HeroSection :district="district" />
      </div>

      <div class="container mx-auto px-4 py-16 max-w-[100rem]">
        <div class="mb-12 text-center">
          <h2 
            class="text-4xl font-['Black_Ops_One'] text-orange-500 mb-4 relative inline-block"
            data-aos="fade-right" 
            data-aos-duration="1500"
          >
            Explore Hotels in {{ district.name }}
            <div class="absolute bottom-0 left-0 w-full h-1 transition-transform duration-300 transform scale-x-0 bg-orange-300 group-hover:scale-x-100"></div>
          </h2>
          <p class="max-w-2xl mx-auto text-gray-600">
            Discover the perfect stay in {{ district.name }} with our carefully curated selection of hotels
          </p>
        </div>

        <!-- Render Hotel Cards -->
        <div 
          class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 max-w-7xl mx-auto rounded-xl shadow-sm overflow-hidden"
          data-aos="fade-up"
          data-aos-duration="1000"
        >
          <PropertyCard
            :hotels="hotels"
            :users="users"
            :favorites="favorites"
          />
        </div>
      </div>
    </div>
  </WelcomeLayout>
</template>

<style scoped>
.container {
  animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>