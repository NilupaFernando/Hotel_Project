<template>
  <AdminAuthenticatedLayout>
    <div class="bg-gray-900 min-h-screen p-4 md:p-6">
      <div class="max-w-7xl mx-auto rounded-xl shadow-sm overflow-hidden">
        <div class="p-6">
          <!-- Key Metrics -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mb-6">
            <!-- Order Count -->
            <div class="bg-gray-800 rounded-xl p-4 border border-gray-700">
              <div class="flex justify-between items-start">
                <div>
                  <div class="flex items-center gap-2 mb-1">
                    <div class="bg-orange-500 rounded-full p-1.5">
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4 text-white"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                      >
                        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                      </svg>
                    </div>
                    <span class="text-sm text-gray-300">Order Count</span>
                  </div>
                  <div class="text-2xl font-bold text-orange-400 ml-10">{{ bookings }}</div>
                </div>
              </div>
            </div>

            <!-- User Count -->
            <div class="bg-gray-800 rounded-xl p-4 border border-gray-700">
              <div class="flex justify-between items-start">
                <div>
                  <div class="flex items-center gap-2 mb-1">
                    <div class="bg-orange-500 rounded-full p-1.5">
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4 text-white"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                      >
                        <path d="M5 12h14"></path>
                        <path d="m12 5 7 7-7 7"></path>
                      </svg>
                    </div>
                    <span class="text-sm text-gray-300">User Count</span>
                  </div>
                  <div class="text-2xl font-bold text-orange-400 ml-10">{{ allUsers }}</div>
                </div>
              </div>
            </div>

            <!-- Property Count -->
            <div class="bg-gray-800 rounded-xl p-4 border border-gray-700">
              <div class="flex justify-between items-start">
                <div>
                  <div class="flex items-center gap-2 mb-1">
                    <div class="bg-orange-500 rounded-full p-1.5">
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4 text-white"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                      >
                        <path d="M3 3v18h18"></path>
                        <path d="m19 9-5 5-4-4-3 3"></path>
                      </svg>
                    </div>
                    <span class="text-sm text-gray-300">Property Count</span>
                  </div>
                  <div class="text-2xl font-bold text-orange-400 ml-10">{{ hotelOwnerCount }}</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Accommodations Section -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Welcome Message -->
            <div
              class="text-white rounded-xl p-6 mb-6 h-full"
              style="
                background: url('/img/MyImages.jpeg') no-repeat center center;
                background-size: cover;
                position: relative;
              "
            >
              <!-- Gradient overlay -->
              <div class="absolute inset-0 bg-gradient-to-r from-gray-800 to-orange-500 opacity-50 rounded-xl"></div>
              <div class="relative z-10">
                <h2 class="text-2xl font-semibold">Welcome Back, Admin!</h2>
                <p class="mt-2 text-lg">We are glad to have you back. Check out the latest updates and metrics below.</p>
              </div>
            </div>

            <!-- Accommodation Types -->
            <div class="bg-gray-800 rounded-xl border border-gray-700 p-4">
              <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-gray-200">Accommodation Types</h2>
                <div class="text-xs bg-gray-700 text-gray-300 px-3 py-1 rounded-full">{{ currentDate }}</div>
              </div>

              <!-- SVG Diagram -->
              <div class="flex justify-center">
                <div class="relative w-48 h-48">
                  <svg viewBox="0 0 100 100" class="w-full h-full">
                    <circle
                      v-for="(stroke, index) in strokeValues"
                      :key="index"
                      cx="50"
                      cy="50"
                      r="40"
                      fill="transparent"
                      :stroke="colors[stroke.key]"
                      stroke-width="20"
                      :stroke-dasharray="`${stroke.dashArray} ${totalCircleLength}`"
                      :stroke-dashoffset="stroke.strokeOffset"
                      transform="rotate(-90 50 50)"
                    ></circle>
                  </svg>
                </div>
              </div>

              <!-- Percentage Labels -->
              <div class="grid grid-cols-2 gap-2 mt-4">
                <div v-for="(percentage, type) in accommodationPercentages" :key="type" class="flex items-center gap-2">
                  <span class="w-3 h-3 rounded-full" :style="{ backgroundColor: colors[type] }"></span>
                  <span class="text-xs text-gray-300">{{ type }}</span>
                  <span class="text-xs font-medium ml-auto text-gray-200">{{ percentage }}%</span>
                </div>
              </div>
            </div>

            <!-- User Types -->
            <div class="bg-gray-800 rounded-xl border border-gray-700 p-4">
              <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-gray-200">Users</h2>
                <div class="text-xs bg-gray-700 text-gray-300 px-3 py-1 rounded-full">{{ currentDate }}</div>
              </div>
              <div class="flex justify-center">
                <div class="relative w-48 h-48">
                  <svg viewBox="0 0 100 100" class="w-full h-full">
                    <!-- Background Circles -->
                    <circle cx="50" cy="50" r="40" fill="transparent" stroke="#374151" stroke-width="8"></circle>
                    <circle cx="50" cy="50" r="30" fill="transparent" stroke="#374151" stroke-width="8"></circle>
                    <circle cx="50" cy="50" r="20" fill="transparent" stroke="#374151" stroke-width="8"></circle>

                    <!-- User -->
                    <circle
                      cx="50"
                      cy="50"
                      r="40"
                      fill="transparent"
                      stroke="#c4b5fd"
                      stroke-width="8"
                      :stroke-dasharray="`${(userPercentage / 100) * 251.3} 251.3`"
                      stroke-dashoffset="0"
                      transform="rotate(-90 50 50)"
                    ></circle>

                    <!-- Super Admin -->
                    <circle
                      cx="50"
                      cy="50"
                      r="30"
                      fill="transparent"
                      stroke="#ff8c00"
                      stroke-width="8"
                      :stroke-dasharray="`${(superAdminPercentage / 100) * 188.5} 188.5`"
                      stroke-dashoffset="0"
                      transform="rotate(-90 50 50)"
                    ></circle>

                    <!-- Hotel Owner -->
                    <circle
                      cx="50"
                      cy="50"
                      r="20"
                      fill="transparent"
                      stroke="#4ade80"
                      stroke-width="8"
                      :stroke-dasharray="`${(hotelOwnerPercentage / 100) * 125.7} 125.7`"
                      stroke-dashoffset="0"
                      transform="rotate(-90 50 50)"
                    ></circle>
                  </svg>
                </div>
              </div>
              <div class="grid grid-cols-1 gap-2 mt-4">
                <div class="flex items-center gap-2">
                  <span class="w-3 h-3 rounded-full bg-[#c4b5fd]"></span>
                  <span class="text-xs text-gray-300">User</span>
                  <span class="text-xs font-medium ml-auto text-gray-200">{{ props.userPercentage }} %</span>
                </div>
                <div class="flex items-center gap-2">
                  <span class="w-3 h-3 rounded-full bg-[#ff8c00]"></span>
                  <span class="text-xs text-gray-300">Super Admin</span>
                  <span class="text-xs font-medium ml-auto text-gray-200">{{ props.superAdminPercentage }} %</span>
                </div>
                <div class="flex items-center gap-2">
                  <span class="w-3 h-3 rounded-full bg-[#4ade80]"></span>
                  <span class="text-xs text-gray-300">Hotel Owner</span>
                  <span class="text-xs font-medium ml-auto text-gray-200">{{ props.hotelOwnerPercentage }} %</span>
                </div>
              </div>
            </div>

            <!-- Property Requests Section -->
            <div class="bg-gray-800 rounded-xl border border-gray-700 p-4">
              <div class="flex justify-between items-center mb-6">
                <h2 class="font-semibold text-gray-200">Property Requests</h2>
                <div class="text-xs bg-gray-700 text-gray-300 px-3 py-1 rounded-full">{{ currentDate }}</div>
              </div>
              <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                <div class="space-y-6 w-full">
                  <div class="flex items-center gap-6 w-full">
                    <span class="text-sm font-medium w-16 text-gray-200">All Requests</span>
                    <div class="h-2 bg-gray-700 rounded-full flex-1">
                      <div
                        class="h-full bg-[#f24b04] rounded-full"
                        :style="{ width: `${props.hotelOwnerCount}%` }"
                      ></div>
                    </div>
                    <span class="text-sm font-medium text-gray-200">{{ props.hotelOwnerCount }}</span>
                  </div>
                  <div class="flex items-center gap-6 w-full">
                    <span class="text-sm font-medium w-16 text-gray-200">Approved Requests</span>
                    <div class="h-2 bg-gray-700 rounded-full flex-1">
                      <div
                        class="h-full bg-[#884ab2] rounded-full"
                        :style="{ width: `${props.ApprovedPropertyRequests}%` }"
                      ></div>
                    </div>
                    <span class="text-sm font-medium text-gray-200">{{ props.ApprovedPropertyRequests }}</span>
                  </div>
                  <div class="flex items-center gap-6 w-full">
                    <span class="text-sm font-medium w-16 text-gray-200">Rejected Requests</span>
                    <div class="h-2 bg-gray-700 rounded-full flex-1">
                      <div
                        class="h-full bg-[#d741a7] rounded-full"
                        :style="{ width: `${props.RejectedPropertyRequests}%` }"
                      ></div>
                    </div>
                    <span class="text-sm font-medium text-gray-200">{{ props.RejectedPropertyRequests }}</span>
                  </div>
                  <div class="flex items-center gap-6 w-full">
                    <span class="text-sm font-medium w-16 text-gray-200">Pending Requests</span>
                    <div class="h-2 bg-gray-700 rounded-full flex-1">
                      <div
                        class="h-full bg-[#d1105a] rounded-full"
                        :style="{ width: `${props.PendingPropertyRequests}%` }"
                      ></div>
                    </div>
                    <span class="text-sm font-medium text-gray-200">{{ props.PendingPropertyRequests }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminAuthenticatedLayout>
</template>

<script setup>
import AdminAuthenticatedLayout from '@/Layouts/Admin/AdminAuthenticatedLayout.vue';
import { computed } from 'vue';

// Get current date in readable format
const currentDate = new Date().toLocaleDateString('en-US', {
  year: 'numeric',
  month: 'long',
  day: 'numeric',
});

// Define props
const props = defineProps({
  allUsers: Number,
  hotels: Number,
  superAdminPercentage: Number,
  hotelOwnerPercentage: Number,
  userPercentage: Number,
  accommodationPercentages: Object,
  PendingPropertyRequests: Number,
  ApprovedPropertyRequests: Number,
  RejectedPropertyRequests: Number,
  hotelOwnerCount: Number,
  order: Number,
  bookings: Number,
});
console.log(props.bookings);
// Define stroke dasharray values dynamically
const totalCircleLength = 188.5;
const strokeValues = computed(() => {
  let offset = 0;
  return Object.entries(props.accommodationPercentages).map(([key, value]) => {
    const dashArray = (value / 100) * totalCircleLength;
    const strokeOffset = offset;
    offset -= dashArray;
    return { key, dashArray, strokeOffset };
  });
});

// Define colors for accommodation types (adjusted for dark theme)
const colors = {
  Hotel: '#6d5acf', // Lighter purple
  Apartment: '#c4b5fd', // Lighter lavender
  Villa: '#ff9e80', // Lighter orange
  Motel: '#86efac', // Lighter green
  Hostel: '#d1c4e9', // Lighter muted purple
  'Guest House': '#f06292', // Lighter pink
  Resort: '#fb923c', // Bright orange
};
</script>

<style>
/* Add custom styles here if needed */
</style>