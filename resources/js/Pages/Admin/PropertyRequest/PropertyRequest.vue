<script setup>
import AdminAuthenticatedLayout from '@/Layouts/Admin/AdminAuthenticatedLayout.vue';
import { ref } from 'vue';
import { format } from "date-fns";
import { router } from '@inertiajs/vue3'
import AOS from 'aos';
import 'aos/dist/aos.css';

AOS.init();

const props = defineProps({
  hotelOwner: {
    type: Array,
    required: true
  },
  propertyRequest: {
    type: Array,
    required: true
  } 
});

console.log('Property Request', props.propertyRequest);

const lengthOfAllHotels = ref(props.hotelOwner.length);
const lengthOfPendingHotels = ref(props.hotelOwner.filter((hotelOwner) => hotelOwner.permit_status === 'pending').length);
const lengthOfApprovedHotels = ref(props.hotelOwner.filter((hotelOwner) => hotelOwner.permit_status === 'active').length);

const showDetails = ref(false);

const toggleDetails = (id) => {
  router.get(route('admin.propertyReqView',{id:id}));
  console.log(id);
};

const formatDate = (dateString) => {
      return format(new Date(dateString), "MMMM dd, yyyy");
  }

</script>

<template>
  <AdminAuthenticatedLayout>
    <div class="min-h-screen bg-gray-900">
      <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-orange-500" data-aos="fade-right">
            Property Requests
          </h1>
          <p class="mt-2 text-gray-600"data-aos="fade-right" data-aos-delay="100">
            Manage and review incoming property registration requests
          </p>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <div class="bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-700 transition duration-300 ease-in-out hover:shadow-md hover:scale-105 animate-fade-in">
            <div class="flex items-center">
              <div class="p-3 bg-orange-100 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
              </div>
              <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-200">Total Requests</h3>
                <p class="text-2xl font-bold text-orange-600">{{ lengthOfAllHotels }}</p>
              </div>
            </div>
          </div>

          <div class="bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-700 transition duration-300 ease-in-out hover:shadow-md hover:scale-105 animate-fade-in">
            <div class="flex items-center">
              <div class="p-3 bg-green-100 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-200">Approved</h3>
                <p class="text-2xl font-bold text-green-600">{{ lengthOfApprovedHotels }}</p>
              </div>
            </div>
          </div>

          <div class="bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-700 transition duration-300 ease-in-out hover:shadow-md hover:scale-105 animate-fade-in">
            <div class="flex items-center">
              <div class="p-3 bg-yellow-100 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-200">Pending</h3>
                <p class="text-2xl font-bold text-yellow-600">{{ lengthOfPendingHotels }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Property Requests List -->
        <div class="bg-gray-800 rounded-xl shadow-sm overflow-hidden">
          <div class="p-6 border-b border-gray-100">
            <h2 class="text-xl font-semibold text-gray-200">Recent Requests</h2>
          </div>

          <div class="divide-y divide-gray-100">
            <div v-for="property in props.propertyRequest" :key="property.id" 
                 class="group hover:bg-gray-600 transition-colors duration-200">
              <div class="p-6">
                <div class="flex items-center justify-between">
                  <div  v-for="hotel in property.hotels" >
                    <h3 class="text-lg font-semibold text-gray-300 group-hover:text-orange-600 transition-colors">
                      {{ hotel.name }}
                    </h3>
                    <div class="mt-1 flex items-center space-x-4 text-sm text-gray-400">
                      <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{ hotel.location }}
                      </span>
                      <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        {{ property.name }}
                      </span>
                      <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ formatDate(hotel.created_at) }}
                      </span>

                    </div>
                  </div>
                  <div class="flex items-center space-x-4">
                    <span class="px-3 py-1 text-sm rounded-full" 
                          :class="{
                            'bg-yellow-100 text-yellow-800': property.permit_status === 'pending',
                            // 'bg-blue-100 text-blue-800': property.permit_status === 'Under Review'
                          }">
                      {{ property.permit_status }}
                    </span>
                    <button class="p-2 hover:bg-orange-100 rounded-full transition-colors" @click="toggleDetails(property.id)">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Empty State -->
          <div v-if="propertyRequest.length === 0" class="p-12 text-center">
            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">No Property Requests</h3>
            <p class="text-gray-600">There are currently no pending property requests to review.</p>
          </div>
        </div>
      </div>
    </div>
  </AdminAuthenticatedLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>