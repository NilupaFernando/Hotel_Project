<script setup>
import { ref, watch, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/User/AuthenticatedLayout.vue';
import PropertyCard from '@/Components/PropertyCard.vue';

const props = defineProps({
  hotels: {
    type: Array,
    required: true,
  },
  favorites: {
    type: Array,
    required: true,
  },
  provinces: {
    type: Array,
    required: true,
  },
  users: {
    type: Object,
  },
    isOffer: {
        type: Boolean,
        default: false
    }
});

console.log(props.isOffer);

const accommodationTypes = ["Hotel", "Resort","Guesthouse","Villa","Hostel","Apartment","Homestay","Motel","Chalet","Banglow","Cabana","Pavilion","Cottage"];
const categories = ['Beach', 'Mountains', 'Forests', 'Historical Sites', 'Lakes', 'Wild life', 'Tourist Activities'];
const HotelStars = ['5', '4', '3', '2'];

const selectedAccommodations = ref([]);
const selectedCategories = ref([]);
const selectedHotelStars = ref('');
const priceRange = ref({ min: 0, max: 100000 });
const selectedProvince = ref('All Provinces');
const searchQuery = ref('');
const sortOrder = ref(null);
const showFilters = ref(false);



const isEmptySearch = ref(false);
const showToast = ref(false);
let toastTimeout = null;

const filteredHotels = computed(() => {
  let result = props.hotels.filter(hotel => {
    if (searchQuery.value.trim() !== '' && !hotel.name.toLowerCase().includes(searchQuery.value.toLowerCase())) {
      return false;
    }

    if (selectedProvince.value !== 'All Provinces' && hotel.province_name !== selectedProvince.value) {
      return false;
    }

    if (selectedAccommodations.value.length > 0 && !selectedAccommodations.value.includes(hotel.accommodation_type)) {
      return false;
    }

    if (selectedCategories.value.length > 0) {
      const normalizeCategory = (str) => {
        if (!str) return '';
        return String(str)
          .toLowerCase()
          .trim()
          .replace(/[^a-z0-9]/g, '')
          .replace(/s$/,'');
      };

      const hotelCategories = (Array.isArray(hotel.category) ? hotel.category : (hotel.category ? hotel.category.split(',') : []))
        .map(cat => normalizeCategory(cat));

      const selectedCats = selectedCategories.value.map(cat => normalizeCategory(cat));

      if (!selectedCats.some(category => hotelCategories.includes(category))) {
        return false;
      }
    }

    if (selectedHotelStars.value && parseInt(hotel.star) < parseInt(selectedHotelStars.value)) {
      return false;
    }

    if (hotel.price_per_night < priceRange.value.min || hotel.price_per_night > priceRange.value.max) {
      return false;
    }

    return true;
  });

  // Sorting logic
  if (sortOrder.value === 'lowToHigh') {
    result.sort((a, b) => a.price_per_night - b.price_per_night);
  } else if (sortOrder.value === 'highToLow') {
    result.sort((a, b) => b.price_per_night - a.price_per_night);
  }

  return result.length > 0 ? result : [];
});

const handleSearch = () => {
  if (searchQuery.value.trim() === '') {
    isEmptySearch.value = true;
    showToast.value = true;
    if (toastTimeout) clearTimeout(toastTimeout);
    toastTimeout = setTimeout(() => {
      showToast.value = false;
    }, 2500);
  } else {
    isEmptySearch.value = false;
  }
};

const handleSort = (event) => {
  const value = event.target.value;
  if (value === 'Price: Low to High') {
    sortOrder.value = 'lowToHigh';
  } else if (value === 'Price: High to Low') {
    sortOrder.value = 'highToLow';
  } else {
    sortOrder.value = null;
  }
};

// Watchers to apply filters when any criteria change
watch([selectedProvince, selectedAccommodations, selectedCategories, selectedHotelStars, priceRange, searchQuery], () => {
  console.log('Filters applied:', filteredHotels.value);
});
</script>



<style>
@import url('https://fonts.googleapis.com/css2?family=ADLaM+Display&family=Advent+Pro:ital,wght@0,100..900;1,100..900&family=Aleo:ital,wght@0,100..900;1,100..900&family=Baskervville:ital@0;1&family=Black+Ops+One&family=Calistoga&family=Cantora+One&family=Concert+One&display=swap');
</style>

<template>
  <AuthenticatedLayout>
    <div class="min-h-screen bg-gray-100">
      <div class="max-w-7xl mx-auto px-4 py-4">
        <h1 class="text-3xl text-orange-500 mt-20 font-serif font-bold text-center sm:text-left">
          Explore The Beautiful World
        </h1>
      </div>
      <main class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
          <!-- Filters Section -->
          <div class="lg:w-64">
            <!-- Toggle Button for Small Screens -->
            <button
              @click="showFilters = !showFilters"
              class="lg:hidden bg-orange-500 text-white px-4 py-2 rounded-md mb-4"
            >
              {{ showFilters ? 'Hide Filters' : 'Show Filters' }}
            </button>

            <!-- Filters Panel -->
            <div
              :class="{'hidden': !showFilters, 'block': showFilters}"
              class="lg:block bg-white p-6 rounded-lg shadow-md transition-all duration-300"
            >
              <h2 class="text-xl font-bold mb-6 text-black">Categories</h2>
              
              <!-- Province Filter -->
              <div class="mb-6">
                <h3 class="font-semibold mb-3 text-gray-700">Province</h3>
                <select v-model="selectedProvince" class="w-full p-2 border rounded-md focus:ring-orange-300 focus:border-orange-300">
                  <option value="All Provinces">All Provinces</option>
                  <option v-for="province in props.provinces" :key="province.id" :value="province.province_name">
                    {{ province.province_name }}
                  </option>
                </select>
              </div>

              <!-- Accommodation Type -->
              <div class="mb-6">
                <h3 class="font-semibold mb-3 text-gray-700">Accommodation type</h3>
                <div class="space-y-2">
                  <div v-for="type in accommodationTypes" :key="type" class="flex items-center">
                    <input
                      type="checkbox"
                      :id="type"
                      v-model="selectedAccommodations"
                      :value="type"
                      class="rounded border-gray-300 text-orange-400 focus:ring-orange-300"
                    />
                    <label :for="type" class="ml-2 text-sm text-gray-600">{{ type }}</label>
                  </div>
                </div>
              </div>

              <!-- State Categories -->
              <div class="mb-6">
                <h3 class="font-semibold mb-3 text-gray-700">State Categories</h3>
                <div class="space-y-2">
                  <div v-for="category in categories" :key="category" class="flex items-center">
                    <input
                      type="checkbox"
                      :id="category"
                      v-model="selectedCategories"
                      :value="category"
                      class="rounded border-gray-300 text-orange-400 focus:ring-orange-300"
                    />
                    <label :for="category" class="ml-2 text-sm text-gray-600">{{ category }}</label>
                  </div>
                </div>
              </div>

              <!-- Price Range -->
              <div class="mb-6">
                <h3 class="font-semibold mb-3 text-gray-700">Price</h3>
                <div class="flex gap-2">
                  <input
                    type="number"
                    v-model="priceRange.min"
                    placeholder="Min"
                    class="w-1/2 p-2 border rounded-md focus:ring-orange-300 focus:border-orange-300"
                  />
                  <input
                    type="number"
                    v-model="priceRange.max"
                    placeholder="Max"
                    class="w-1/2 p-2 border rounded-md focus:ring-orange-300 focus:border-orange-300"
                  />
                </div>
              </div>

              <!-- Hotel Class -->
              <div class="mb-6">
                <h3 class="font-semibold mb-3 text-gray-700">Hotel Class</h3>
                <div class="space-y-2">
                  <div v-for="hotelStar in HotelStars" :key="hotelStar" class="flex items-center">
                    <input
                      type="radio"
                      :id="hotelStar"
                      v-model="selectedHotelStars"
                      :value="hotelStar"
                      class="rounded-full border-gray-300 text-orange-400 focus:ring-orange-300"
                    />
                    <label :for="hotelStar" class="ml-2 text-sm text-gray-600">{{ hotelStar }}-star Hotel</label>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Properties Grid -->
          <div class="flex-1">
            <!-- Sort Options -->
            <div class="flex flex-col sm:flex-row mb-6 justify-between gap-4">
              <input
                v-model="searchQuery"
                @keyup.enter="handleSearch"
                class="input focus:ring-orange-300 focus:border-orange-300 w-full sm:w-auto"
                name="text"
                type="text"
                placeholder="Search ..."
              />

              <select
                @change="handleSort($event)"
                class="p-2 border border-orange-400 rounded-md focus:ring-orange-300 focus:border-orange-300 w-full sm:w-60"
              >
                <option>Sort by</option>
                <option>Price: Low to High</option>
                <option>Price: High to Low</option>
                <option>Most Popular</option>
              </select>
            </div>

            <!-- Property cards -->
            <!-- Toast Notification -->
            <transition name="fade">
              <div v-if="showToast" class="fixed top-6 right-6 z-50 bg-red-500 text-white px-6 py-3 rounded shadow-lg flex items-center min-w-[220px]">
                <span class="mr-2">&#9888;</span> Please enter a search term.
              </div>
            </transition>
            <div>
              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <template v-if="filteredHotels.length > 0">
                  <PropertyCard
                    :hotels="filteredHotels"
                    :favorites="favorites"
                    :users="users"
                    :isOffer="isOffer"
                  />
                </template>
                <template v-else>
                  <div class="col-span-full text-center text-gray-500">
                    No results found. Please adjust your filters.
                  </div>
                </template>
              </div>
            </div>
            <div id="sentinel" class="h-1"></div>
          </div>
        </div>
      </main>
    </div>
  </AuthenticatedLayout>
</template>

<style>
@import url('https://fonts.googleapis.com/css2?family=ADLaM+Display&family=Advent+Pro:ital,wght@0,100..900;1,100..900&family=Aleo:ital,wght@0,100..900;1,100..900&family=Baskervville:ital@0;1&family=Black+Ops+One&family=Calistoga&family=Cantora+One&family=Concert+One&display=swap');

footer {
  margin-left: 0;
  margin-right: 0;
  padding-left: 0;
  padding-right: 0;
}
:root {
  font-family: Inter, system-ui, Avenir, Helvetica, Arial, sans-serif;
  line-height: 1.5;
  font-weight: 400;
}

body {
  margin: 0;
  min-width: 320px;
  min-height: 100vh;
}
</style>

/* Toast fade animation */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
