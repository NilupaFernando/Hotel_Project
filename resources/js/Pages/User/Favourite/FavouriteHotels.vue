<template>
  <AuthenticatedLayout>
    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 p-4 sm:p-8">
      <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <!-- Header with animation -->
        <div class="mb-8 flex items-center justify-between p-6 mt-5">
          <h1 class="font-serif text-3xl font-bold tracking-tight text-gray-600 sm:text-4xl">
            My Favorites
          </h1>
          <button 
            @click="deleteFavorites" 
            class="group rounded-full p-3 transition-all duration-300 hover:bg-red-50 disabled:opacity-50"
            :disabled="selectedItems.length === 0"
            :class="selectedItems.length > 0 ? 'text-red-500' : 'text-gray-400'"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transition-transform duration-300 group-hover:rotate-12" fill="none" viewBox="0 0 25 25" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
          </button>
        </div>

        <!-- Selected count badge -->
        <div v-if="selectedItems.length > 0" class="mb-4 flex items-center">
          <span class="rounded-full bg-orange-100 px-3 py-1 text-sm font-medium text-orange-800">
            {{ selectedItems.length }} item{{ selectedItems.length > 1 ? 's' : '' }} selected
          </span>
        </div>

        <!-- Favorites list with animation -->
        <div class="space-y-4">
          <div 
            v-for="(favorite, index) in favorites" 
            :key="favorite.id" 
            class="transform overflow-hidden rounded-xl bg-white opacity-0 shadow-md transition-all duration-500 hover:shadow-xl"
            :class="{ 'translate-y-0 opacity-100': isLoaded, 'translate-y-4': !isLoaded }"
            :style="{ transitionDelay: `${index * 100}ms` }"
          >
            <div class="flex flex-col gap-6 p-5 md:flex-row">
              <div class="flex items-start gap-4">
                <div class="pt-2">
                  <input 
                    type="checkbox" 
                    class="h-5 w-5 cursor-pointer rounded-md border-gray-300 text-orange-500 transition-transform duration-200 hover:scale-110 focus:ring-orange-400"
                    v-model="selectedItems"
                    :value="favorite.id"
                  >
                </div>
                <div class="overflow-hidden rounded-lg">
                  <div class="relative overflow-hidden rounded-lg">
                    <img 
  :src="`/storage/${favorite.hotel.thumbnail_image}`" 
  :alt="favorite.hotel.name" 
  class="h-52 w-72 object-cover transition-transform duration-700 hover:scale-110"
/>
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-3">
                      <span class="text-sm font-medium text-white">{{ favorite.hotel.name }}</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="flex flex-1 flex-col">
                <div class="mb-2 flex items-center justify-between">
                  <h2 class="text-xl font-bold text-gray-800 sm:text-2xl">{{ favorite.hotel.name }}</h2>
                  <div class="flex items-center rounded-full bg-orange-50 px-3 py-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-orange-500" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                    </svg>
                    <span class="ml-1 text-sm font-medium text-orange-700">{{ favorite.hotel.location }}</span>
                  </div>
                </div>
                <p class="mb-6 text-gray-600">{{ favorite.hotel.description }}</p>
                <div class="mt-auto">
                  <button 
                    class="group flex items-center rounded-lg bg-gradient-to-r from-orange-400 to-orange-500 px-6 py-2.5 text-sm font-medium text-white shadow-md transition-all duration-300 hover:shadow-lg"
                    @click="goToHotelDetails(favorite.hotel.id)"
                  >
                    View Details
                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-5 w-5 transform transition-transform duration-300 group-hover:translate-x-1" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Empty state -->
        <div v-if="favorites.length === 0" class="mt-10 flex flex-col items-center justify-center rounded-xl bg-white p-10 text-center shadow-sm">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
          </svg>
          <h3 class="mt-4 text-lg font-medium text-gray-700">No favorites yet</h3>
          <p class="mt-1 text-gray-500">Start exploring hotels and add some to your favorites</p>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/User/AuthenticatedLayout.vue';
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';


const props = defineProps({
  favorites: {
    type: Array,
    required: true,
  }
});

const isLoaded = ref(false);
const selectedItems = ref([]);

console.log(selectedItems);

onMounted(() => {
  setTimeout(() => {
    isLoaded.value = true;
  }, 100);
});

const deleteFavorites = async () => {
  if (selectedItems.value.length === 0) {
    alert('Please select at least one favorite to delete.');
    return;
  }

  try {
    const response = await axios.delete('/favorites/delete-multiple', {
      data: { favorite_ids: selectedItems.value },
    });
    console.log(response.data.message);
    // SweetAlert success
    await Swal.fire({
      icon: 'success',
      title: 'Deleted!',
      text: 'Favorites deleted successfully.',
      timer: 1500,
      showConfirmButton: false
    });
    selectedItems.value = [];
    location.reload();
  } catch (error) {
    console.error('Error deleting favorites:', error);
    alert('Something went wrong!');
  }
};

const goToHotelDetails = (hotelId) => {
    router.get(route('view-hotel.show', { id: hotelId }));
};
</script>

