<script setup>
import { ref, computed } from 'vue';
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/24/solid';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    title: String,
    categories: Array,
    showViewAll: Boolean
});

const currentIndex = ref(0);
const itemsPerPage = 4;
let navigationTimeout = null;

const totalPages = computed(() => Math.ceil(props.categories.length / itemsPerPage));
const visibleCategories = computed(() => {
    const start = currentIndex.value * itemsPerPage;
    return props.categories.slice(start, start + itemsPerPage);
});

const nextPage = () => {
    if (currentIndex.value < totalPages.value - 1) {
        currentIndex.value++;
    }
};

const prevPage = () => {
    if (currentIndex.value > 0) {
        currentIndex.value--;
    }
};

const clickimage = (name) => {
    router.get(route('explore.search.hotel', { name: name }));
};

const navigateToPage = (name) => {
    // Debounce navigation to prevent rapid clicks
    if (navigationTimeout) return;
    navigationTimeout = setTimeout(() => { navigationTimeout = null; }, 500);
    router.get(route('user.hotel.findByState', { name: name }), { preserveScroll: true });
} 
</script>


<template>
  <div class="px-4 mx-auto my-16 max-w-7xl sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-orange-500" style="font-family: 'Calistoga', serif;" data-aos="fade-right">
                {{ title }}
            </h2>
            <p class="text-gray-600 mt-2" data-aos="fade-right" data-aos-delay="100">
                Discover Sri Lanka's diverse attractions and activities
            </p>
        </div>
        <a  :href="route('explore.hotels')" class="text-orange-400 hover:text-emerald-700 font-medium" data-aos="fade-left">
            View all categories →
        </a>
    </div>

    <div class="relative">
      <div class="flex gap-4 transition-all duration-300 ease-in-out" style="will-change: transform;">
        <div v-for="category in visibleCategories" :key="category.id" @click="navigateToPage(category.title)"
             class="relative flex-1 overflow-hidden rounded-lg group cursor-pointer" style="will-change: transform;">
          <img :src="category.image" :alt="category.title" loading="lazy"
               class="object-cover w-full transition-transform duration-300 h-96 group-hover:scale-110" style="will-change: transform;">
          <div class="absolute inset-0 flex items-end p-4 bg-black bg-opacity-40">
            <h3 class="text-xl font-semibold text-white">{{ category.title }}</h3>
          </div>
        </div>
      </div>

      <!-- Navigation Arrows -->
      <button @click="prevPage"
              :class="['absolute left-0 top-1/2 transform -translate-y-1/2 -translate-x-4',
                       'bg-white p-2 rounded-full shadow-lg hover:bg-gray-100 transition-colors',
                       { 'opacity-50 cursor-not-allowed': currentIndex === 0 }]"
              :disabled="currentIndex === 0">
        <ChevronLeftIcon class="w-6 h-6 text-gray-600" />
      </button>

      <button @click="nextPage"
              :class="['absolute right-0 top-1/2 transform -translate-y-1/2 translate-x-4',
                       'bg-white p-2 rounded-full shadow-lg hover:bg-gray-100 transition-colors',
                       { 'opacity-50 cursor-not-allowed': currentIndex === totalPages - 1 }]"
              :disabled="currentIndex === totalPages - 1">
        <ChevronRightIcon class="w-6 h-6 text-gray-600" />
      </button>
    </div>
  </div>
</template>
