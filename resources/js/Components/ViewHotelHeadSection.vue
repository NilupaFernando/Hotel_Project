<template>
    <div class="min-h-screen" :class="{'bg-gradient-to-b from-orange-50 to-white': !isAdmin, 'bg-gray-900 text-white': isAdmin}">
      <!-- Hero Section with Image Slider -->
      <div class="relative w-full h-[80vh] overflow-hidden ">
        <transition-group name="fade">
          <div
            :key="hotels.thumbnail_image"
            class="absolute inset-0 w-full h-full bg-black/20">
            <img
              :src="`/storage/${hotels.thumbnail_image}`"
              :alt="hotels.name"
              class="object-cover w-full h-full mt-20"
            />
          </div>
        </transition-group>

        <!-- Hotel Name and Category -->
        <div class="absolute inset-x-0 top-0 z-10 flex items-start justify-between p-6 mt-20">
          <div class="space-y-2">
            <div class="animate-fadeIn">
              <span class="px-3 py-1 rounded-md inline-flex items-center" :class="{'text-white bg-orange-500/80 backdrop-blur-sm': !isAdmin, 'text-gray-900 bg-gray-100/80 backdrop-blur-sm': isAdmin}">
                <svg class="w-4 h-4 mr-2 text-orange-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <span class="font-semibold">{{ hotels.star }}-star Resort</span>
              </span>
            </div>
            <h1
              class="text-3xl font-bold md:text-4xl lg:text-5xl drop-shadow-md animate-slideInLeft"
              :class="{'text-white': !isAdmin, 'text-gray-100': isAdmin}"
            >
              {{ hotels.name }}
            </h1>
            <div
              class="flex items-center space-x-1 animate-slideInLeft"
              :class="{'text-white': !isAdmin, 'text-gray-100': isAdmin}"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <span class="text-sm md:text-base">
                {{ hotels.location }}
              </span>
            </div>
          </div>

          <div class="absolute z-10 bottom-36 right-2">
                        <button v-if="isUser() || isAdmin"
                            @click.stop="toggleFavorite(hotels.id)"
                                class="absolute p-2 transition-colors duration-200 rounded-full top-3 right-3"
                                :class="[isFavorite(hotels.id) ? 'bg-orange-500' : 'bg-white', {'bg-orange-500': isFavorite(hotels.id), 'bg-gray-700': isAdmin && !isFavorite(hotels.id)}]"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5"
                                    :class="[isFavorite(hotels.id) ? 'text-white' : 'text-white stroke-orange-500', {'text-white': isFavorite(hotels.id), 'stroke-orange-500': !isFavorite(hotels.id) && !isAdmin, 'stroke-gray-100 text-gray-100': !isFavorite(hotels.id) && isAdmin}]"
                                    viewBox="0 0 24 24"
                                    :stroke="isFavorite(hotels.id) ? 'white' : '#f97316'"
                                    :stroke-width="2"
                                    fill="none"
                                >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                        </button>
                    </div>


        </div>

        <!-- Star Rating -->
        <div class="absolute z-10 flex bottom-6 right-6">
          <svg
            v-for="n in starsArray"
            :key="n"
            xmlns="http://www.w3.org/2000/svg"
            class="w-5 h-5 text-orange-400 fill-current"
            viewBox="0 0 20 20"
          >
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
          </svg>
        </div>
      </div>

      <!-- Main Content -->
      <div class="container px-4 py-8 mx-auto">
        <div class="relative z-20 grid grid-cols-1 gap-8 -mt-16 lg:grid-cols-3">
          <!-- Left Column - Hotel Details -->
          <div class="col-span-1 rounded-lg shadow-xl lg:col-span-2" :class="{'bg-white': !isAdmin, 'bg-gray-800': isAdmin}">
            <div class="p-6">
              <div class="mb-6">
                <div class="grid grid-cols-3 gap-2 mb-6">
                  <button
                    v-for="(tab, index) in ['details', 'location', 'amenities']"
                    :key="index"
                    :class="[
                      'py-2 px-4 text-center rounded-md transition-all',
                      activeTab === tab
                        ? 'font-medium'
                        : 'hover:bg-orange-200',
                        {'bg-orange-500 text-white': activeTab === tab && !isAdmin, 'bg-gray-700 text-white': activeTab === tab && isAdmin},
                        {'bg-orange-100 text-orange-800': activeTab !== tab && !isAdmin, 'bg-gray-900 text-gray-300': activeTab !== tab && isAdmin}
                    ]"
                    @click="activeTab = tab"
                  >
                    {{ tab.charAt(0).toUpperCase() + tab.slice(1).replace('_', ' ') }}
                  </button>
                </div>

                <div v-if="activeTab === 'details'" class="space-y-6">
                  <div>
                    <h2 class="mb-4 text-2xl font-bold" :class="{'text-gray-800': !isAdmin, 'text-gray-100': isAdmin}">About This Hotel</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                      <div>
                        <h5 class="font-bold" :class="{'text-gray-800': !isAdmin, 'text-gray-100': isAdmin}">State Category:</h5>
                        <p class="mb-4" :class="{'text-gray-600': !isAdmin, 'text-gray-300': isAdmin}">{{ hotels.category }}</p>
                      </div>

                      <div v-if="haveNotRooms && hotels.rooms && hotels.rooms.length > 0" class="space-y-1">
                        <p class="font-bold" :class="{'text-gray-800': !isAdmin, 'text-gray-100': isAdmin}">Accommodation For:</p>
                        <div class="flex items-center">
                          <span :class="{'text-gray-600': !isAdmin, 'text-gray-300': isAdmin}">{{ hotels.rooms[0].max_adult }} Adults </span>
                          <span v-if="hotels.rooms[0].max_children != 0" class="ml-2" :class="{'text-gray-600': !isAdmin, 'text-gray-300': isAdmin}">, {{ hotels.rooms[0].max_children }} Children </span>
                        </div>
                      </div>
                    </div>

                    <p :class="{'text-gray-600': !isAdmin, 'text-gray-300': isAdmin}">{{ hotels.description }}</p>
                  </div>

                  <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="flex items-center space-x-3">
                      <div class="p-2 rounded-full" :class="{'text-orange-500 bg-orange-100': !isAdmin, 'text-gray-200 bg-gray-700': isAdmin}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                      </div>
                      <div>
                        <p class="text-sm" :class="{'text-gray-500': !isAdmin, 'text-gray-400': isAdmin}">Email</p>
                        <p class="font-medium" :class="{'text-gray-800': !isAdmin, 'text-gray-100': isAdmin}">{{ hotels.email }}</p>
                      </div>
                    </div>

                    <div class="flex items-center space-x-3">
                      <div class="p-2 rounded-full" :class="{'text-orange-500 bg-orange-100': !isAdmin, 'text-gray-200 bg-gray-700': isAdmin}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                      </div>
                      <div>
                        <p class="text-sm" :class="{'text-gray-500': !isAdmin, 'text-gray-400': isAdmin}">Contact</p>
                        <p class="font-medium" :class="{'text-gray-800': !isAdmin, 'text-gray-100': isAdmin}">{{ hotels.contact_number }}</p>
                      </div>
                    </div>
                  </div>
                </div>

                <div v-if="activeTab === 'location'" class="space-y-4">
                  <h2 class="text-2xl font-bold" :class="{'text-gray-800': !isAdmin, 'text-gray-100': isAdmin}">Location</h2>
                  <p :class="{'text-gray-600': !isAdmin, 'text-gray-300': isAdmin}">
                    {{ hotels.location }}, {{ hotels.district.name }}, {{ hotels.province_name }}
                  </p>

                  <div class="w-full overflow-hidden border rounded-lg aspect-video" :class="{'border-orange-100': !isAdmin, 'border-gray-700': isAdmin}">
                    <iframe
                      :src="mapLink"
                      width="100%"
                      height="100%"
                      style="border: none"
                      allowfullscreen
                      loading="lazy"
                    ></iframe>
                  </div>
                </div>

                <div v-if="activeTab === 'amenities'" class="space-y-4">
                  <h2 class="text-2xl font-bold" :class="{'text-gray-800': !isAdmin, 'text-gray-100': isAdmin}">Amenities</h2>
                  <div class="grid grid-cols-2 gap-3 md:grid-cols-3">
                    <div
                      v-for="(amenity, index) in hotels.amenities.split(',')"
                      :key="index"
                      class="flex items-center space-x-2"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                      </svg>
                      <span :class="{'text-gray-800': !isAdmin, 'text-gray-100': isAdmin}">{{ amenity }}</span>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>

          <!-- Right Column - Price Card -->
          <div class="animate-slideInRight">
            <div class="rounded-lg shadow-xl" :class="{'bg-white': !isAdmin, 'bg-gray-800': isAdmin}">
              <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                  <h2 class="text-2xl font-bold" :class="{'text-gray-800': !isAdmin, 'text-gray-100': isAdmin}">Price Details</h2>
                  <div class="text-2xl font-bold text-orange-500">
                    Rs {{hotels.price_per_night}}
                    <span class="text-sm" :class="{'text-gray-500': !isAdmin, 'text-gray-400': isAdmin}">/night</span>
                  </div>
                </div>

                <hr :class="{'border-orange-100': !isAdmin, 'border-gray-700': isAdmin}" />

                <div class="space-y-4">
                  <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                      <p class="text-sm" :class="{'text-gray-500': !isAdmin, 'text-gray-400': isAdmin}">Rating</p>
                      <div class="flex items-center">
                        <div class="flex">
                          <button
                            v-for="n in 5"
                            :key="n"
                            type="button"
                            class="p-0.5"
                            @mouseover="hoverRating = n"
                            @mouseleave="hoverRating = 0"
                            @click="isUser() ? submitRating(n) : null"
                          >
                            <svg xmlns="http://www.w3.org/2000/svg" :class="['w-4 h-4 mr-1', (n <= (hoverRating || userRating || Math.round(displayRating))) ? 'text-orange-500' : 'text-gray-300']" viewBox="0 0 20 20" fill="currentColor">
                              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                          </button>
                        </div>
                        <span class="font-medium ml-2" :class="{'text-gray-800': !isAdmin, 'text-gray-100': isAdmin}">{{ displayRating.toFixed(1) }}</span>
                      </div>
                    </div>

                    <div class="space-y-1">
                      <p class="text-sm" :class="{'text-gray-500': !isAdmin, 'text-gray-400': isAdmin}">Accommodation Type</p>
                      <p class="font-medium" :class="{'text-gray-800': !isAdmin, 'text-gray-100': isAdmin}">{{ hotels.accommodation_type }}</p>
                    </div>
                  </div>
                  <div>
                    <div v-if="isUser()">

                        <div v-if="haveNotRooms" >
                          <div class="mb-4">
                            <label for="check_in_date" class="block text-sm font-medium" :class="{'text-gray-700': !isAdmin, 'text-gray-200': isAdmin}">Check-in Date</label>
                            <input type="date" id="check_in_date" v-model="form.check_in_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500" />
                            <p v-if="checkInDateError" class="text-red-500 text-sm">{{ checkInDateError }}</p>
                        </div>
                        <div class="mb-4">
                            <label for="check_out_date" class="block text-sm font-medium" :class="{'text-gray-700': !isAdmin, 'text-gray-200': isAdmin}">Check-out Date</label>
                            <input type="date" id="check_out_date" v-model="form.check_out_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500" />
                            <p v-if="checkOutDateError" class="text-red-500 text-sm">{{ checkOutDateError }}</p>
                        </div>
                        <div v-if="dayCount" class="flex items-center justify-center p-2 mb-4 rounded-lg shadow-md" :class="{'bg-orange-50': !isAdmin, 'bg-gray-700': isAdmin}">
                            <div>
                                <span class="text-md font-semibold" :class="{'text-gray-600': !isAdmin, 'text-gray-300': isAdmin}">

                                    <span class="text-md font-bold text-orange-500">
                                        Rs: {{ dayCount * hotels.price_per_night }}
                                    </span>
                                    price for
                                    <span class="text-md font-bold text-orange-500">
                                      {{ dayCount }} night<template v-if="dayCount > 1">s</template>
                                    </span>
                                </span>
                            </div>

                        </div>
                        <button v-if="isOffer===false || null"
                            class="w-full px-4 py-3 font-medium text-white transition-all duration-300 ease-in-out bg-orange-400 rounded-full hover:bg-orange-600 hover:scale-105"
                            @click="reserveRoom()">
                            Book Now
                        </button>

                        <button v-if="isOffer===true"
                          @click="reserveRoom()"
                            class="w-full px-4 py-3 font-medium text-white transition-all duration-300 ease-in-out bg-orange-400 rounded-full hover:bg-orange-600 hover:scale-105">
                            Book Now
                        </button>
                        </div>

                    </div>
                    <div v-if="isHotelOwner()" class="flex justify-center ml-auto mr-2 space-x-1 text-sm">
                        <button
                            @click="goToUpdatePage(hotels.id)"
                            class="px-2 py-1 text-white transition-all duration-300 ease-in-out bg-orange-400 rounded-full hover:bg-orange-600 hover:scale-105">
                            Update Hotel Info
                        </button>
                        <button
                            @click="goToReservationPage(hotels.id)"
                            class="px-2 py-1 text-white transition-all duration-300 ease-in-out bg-orange-400 rounded-full hover:bg-orange-600 hover:scale-105">
                            Reservation
                        </button>
                        <button
                          @click="showBankModal = true"
                          class="px-2 py-1 text-white transition-all duration-300 ease-in-out bg-orange-400 rounded-full hover:bg-orange-600 hover:scale-105">
                          Bank Details
                        </button>
                    </div>
                  </div>
                </div>


                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- Bank Details Modal -->
      <div v-if="showBankModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
        <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full p-0 relative">
          <!-- Modal Header -->
          <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 rounded-t-xl bg-gradient-to-r from-orange-50 to-white">
            <div class="flex items-center space-x-2">
              <svg class="w-7 h-7 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="3" y="5" width="18" height="14" rx="2" stroke="currentColor" fill="none"/>
                <path d="M3 10h18" stroke="currentColor"/>
                <circle cx="7" cy="15" r="1" fill="currentColor"/>
                <circle cx="11" cy="15" r="1" fill="currentColor"/>
              </svg>
              <h2 class="text-lg md:text-xl font-bold text-gray-800">Bank Details</h2>
            </div>
            <button @click="showBankModal = false" class="text-gray-400 hover:text-orange-500 text-2xl font-bold focus:outline-none">&times;</button>
          </div>
          <!-- Modal Content -->
          <div class="px-6 py-6">
            <div v-if="props.bankDetail && props.bankDetail.length > 0" class="space-y-6">
              <div v-for="(bank, idx) in props.bankDetail" :key="idx" class="relative rounded-lg border border-orange-100 bg-white shadow-sm p-5">
                <!-- Delete Button -->
                <button
                  @click="destroy(bank.id)"
                  class="absolute top-3 right-3 p-1 rounded-full hover:bg-red-50 transition"
                  title="Delete Bank Detail"
                >
                  <!-- Trash/Bucket Icon -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-500 hover:text-red-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-7 0h10" />
                  </svg>
                </button>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <span class="block text-xs text-gray-500 font-semibold uppercase mb-1">Bank Name</span>
                    <span class="text-base font-medium text-gray-900">{{ bank.bank_name }}</span>
                  </div>
                  <div>
                    <span class="block text-xs text-gray-500 font-semibold uppercase mb-1">Branch</span>
                    <span class="text-base font-medium text-gray-900">{{ bank.branch }}</span>
                  </div>
                  <div class="col-span-1 md:col-span-2 flex items-center space-x-2">
                    <div class="flex-1">
                      <span class="block text-xs text-gray-500 font-semibold uppercase mb-1">Account Number</span>
                      <span class="text-base font-mono text-gray-900 tracking-wider">{{ bank.acc_nomber }}</span>
                    </div>
                    <button @click="copyToClipboard(bank.acc_nomber)" class="ml-2 px-2 py-1 rounded bg-orange-50 hover:bg-orange-100 text-orange-600 text-xs font-semibold border border-orange-100 transition">
                      Copy
                    </button>
                  </div>
                  <div class="col-span-1 md:col-span-2 flex items-center space-x-2">
                    <div class="flex-1">
                      <span class="block text-xs text-gray-500 font-semibold uppercase mb-1">Account Holder Name</span>
                      <span class="text-base font-medium text-gray-900">{{ bank.acc_holder_name }}</span>
                    </div>
                    <button @click="copyToClipboard(bank.acc_holder_name)" class="ml-2 px-2 py-1 rounded bg-orange-50 hover:bg-orange-100 text-orange-600 text-xs font-semibold border border-orange-100 transition">
                      Copy
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <div v-else class="text-gray-500 text-center py-8">No bank details available.</div>
          </div>
        </div>
      </div>
      <!-- End Bank Details Modal -->
  </template>

  <script setup>

import {ref, computed, watch,watchEffect} from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import axios from 'axios';
import SearchAvailableRoom from '@/Components/SearchAvailableRoom.vue';
import AOS from 'aos';
import 'aos/dist/aos.css';
import ViewHotelOfferCard from './ViewHotelOfferCard.vue';

AOS.init();

const currentIndex = ref(0);

const adult_count = ref([]);
const child_count = ref([]);
const room_count = ref([]);
let day_count = ref([]);

let room_id = ref([]);
let price = ref([]);
const activeTab = ref('details');

const checkInDateError = ref(null);
const checkOutDateError = ref(null);

const props = defineProps({
    users: Object,
    hotels: Object,
    districts: Object,
    favorites: Object,
    isOffer: Boolean,
    offerPoints :Number,
    bankDetail:Object
});

console.log(props.bankDetail);

const starsArray = computed(() => {
  const s = Number(props.hotels && props.hotels.star) || 0;
  const count = Math.max(0, Math.floor(s));
  return Array.from({ length: count }, (_, i) => i + 1);
});

// Local rating state (displayed rating and interactive controls)
const displayRating = ref(Number((props.hotels && (props.hotels.avg_rating ?? props.hotels.star ?? props.hotels.rating)) || 0));
const userRating = ref(props.hotels && props.hotels.user_rating ? Number(props.hotels.user_rating) : null);
const hoverRating = ref(0);
const submittingRating = ref(false);
function destroy(id) {
    if (confirm('Are you sure?')) {
        router.delete(`/bank-detail/${id}`, {
            onSuccess: () => {
                showBankModal.value = false;
            }
        });
    }
}
const isAdmin = computed(() => {
    return props.users.role === 'admin';
});

const totalPrice = computed(() => {
    return props.hotels.rooms.reduce((total, room, index) => {
        const rooms = room_count.value[index] || 0;
        const days = day_count.value[index] || 0;
        const child = child_count.value[index] || 0;
        const adult = adult_count.value[index] || 0;

        const adultPrice = room.price_per_adult * rooms * days * adult;
        const childPrice = room.price_per_child * rooms * days * child;

        price.value[index] = adultPrice + childPrice;

        return total + adultPrice + childPrice;
    }, 0);
});

const calculateDayCount = (start, end) => {
    const diffTime = Math.abs(end.getTime() - start.getTime());
    return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
};

const calculatedDayCount = ref(null);

const dayCount = computed(() => {
    checkInDateError.value = null;
    checkOutDateError.value = null;

    if (!form.check_in_date) {
        checkInDateError.value = '';
        return null;
    }

    if (!form.check_out_date) {
        checkOutDateError.value = 'Please select a check-out date.';
        return null;
    }

    const start = new Date(form.check_in_date);
    const end = new Date(form.check_out_date);

    if (start >= end) {
        checkOutDateError.value = 'Check-out date must be after check-in date.';
        return null;
    }

    calculatedDayCount.value = calculateDayCount(start, end);
    return calculatedDayCount.value;
});

const isUser=()=>{
    return props.users.role === 'user';
}
const isHotelOwner=()=>{
    return props.users.role === 'hotel_owner';
}

// Submit rating (defined after isUser() so we can call it directly)
const submitRating = async (value) => {
  if (!isUser()) {
    Swal.fire({ icon: 'info', title: 'Please sign in as a user to rate.' });
    return;
  }
  userRating.value = value;
  submittingRating.value = true;
  try {
    const url = `/hotels/${props.hotels.id}/rating`;
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const headers = {
      Accept: 'application/json'
    };
    if (csrfMeta && csrfMeta.content) headers['X-CSRF-TOKEN'] = csrfMeta.content;

    const res = await axios.post(url, { rating: value }, { headers });

    const newAvg = res.data.rating ?? res.data.avg_rating ?? res.data.average_rating ?? null;
    if (newAvg !== null) {
      displayRating.value = Number(newAvg);
    } else if (res.data && typeof res.data === 'number') {
      displayRating.value = Number(res.data);
    }

    // Replace current Inertia page snapshot so browser back/forward shows updated rating
    try {
      router.get(route('user.hotel.show', { id: props.hotels.id }), { preserveState: true, replace: true });
    } catch (e) {
      // non-fatal; proceed even if router call fails
      console.warn('Inertia page replace failed:', e);
    }

    Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Thanks for rating!', showConfirmButton: false, timer: 1300, background: '#fff7ed', color: '#ea580c' });
  } catch (err) {
    console.error('Rating error:', err);
    let message = 'Failed to submit rating';
    if (err.response) {
      const data = err.response.data || {};
      if (data.errors) {
        // Validation errors
        const firstKey = Object.keys(data.errors)[0];
        message = Array.isArray(data.errors[firstKey]) ? data.errors[firstKey][0] : String(data.errors[firstKey]);
      } else if (data.message) {
        message = data.message;
      } else if (typeof data === 'string') {
        message = data;
      }
    } else if (err.message) {
      message = err.message;
    }

    Swal.fire({ icon: 'error', title: 'Rating Error', text: message });
  } finally {
    submittingRating.value = false;
  }
};

const goToUpdatePage = (hotelId) => {
    router.get(route('hotelOwner.hotel.edit', { id: hotelId }));
};

const goToReservationPage = (hotelId) => {
    router.get(route('hotelOwner.reservations.index', { hotel_id: hotelId }));
};

watch(totalPrice, (newValue) => {
    form.total_price = newValue;
});

watchEffect(() => {
    room_id.value = props.hotels.rooms.map((room, index) => {
        return room_count.value[index] && room_count.value[index] > 0 ? room.id : null;
    });

    adult_count.value = props.hotels.rooms.map((room, index) => {
        return room_count.value[index] && room_count.value[index] > 0 ? adult_count.value[index] || 0 : null;
    });

    child_count.value = props.hotels.rooms.map((room, index) => {
        return room_count.value[index] && room_count.value[index] > 0 ? child_count.value[index] || 0 : null;
    });

});

const form = useForm({
    hotel_id: props.hotels.id,
    check_out_date: null,
    check_in_date: null,
    total_price: totalPrice.value,

    room_id: room_id.value,
    adult_count: adult_count.value,
    child_count: child_count.value,
    room_count: room_count.value,
    day_count: day_count.value,
    price: price.value,
    offer:props.isOffer
});


watchEffect(() => {
    form.room_id = room_id.value;
    form.adult_count = adult_count.value;
    form.child_count = child_count.value;
    form.room_count = room_count.value;
    form.day_count = day_count.value;
    form.room_id = room_id.value;
    form.price = price.value;
});

const nextImage = () => {
    if (currentIndex.value < props.hotels.images.length - 1) {
        currentIndex.value++;
    } else {
        currentIndex.value = 0;
    }
};

// Keep local rating state in sync when server props change (e.g., navigating back)
watch(() => props.hotels && props.hotels.avg_rating, (val) => {
  const newVal = Number(val ?? (props.hotels && (props.hotels.star ?? 0)));
  if (!isNaN(newVal)) displayRating.value = newVal;
});

watch(() => props.hotels && props.hotels.user_rating, (val) => {
  userRating.value = val ? Number(val) : null;
});

const prevImage = () => {
    if (currentIndex.value > 0) {
        currentIndex.value--;
    } else {
        currentIndex.value = props.hotels.images.length - 1;
    }
};

const isFavorite = (hotelId) => {
    return props.favorites.some(fav => fav.hotel_id === hotelId);
};

const toggleFavorite = (id) => {
    if (isFavorite(id)) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to remove this from favorites?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f97316',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, remove it!'
        }).then((result) => {
            if (result.isConfirmed) {
                router.delete(route('favorites.delete', id), {
                    onSuccess: () => {
                        Swal.fire(
                            'Deleted!',
                            'Successfully removed from favorites.',
                            'success'
                        );
                    },
                    onError: (error) => {
                        console.error('Error Deleting Hotel:', error);
                        Swal.fire(
                            'Error!',
                            'Failed to remove from favorites.',
                            'error'
                        );
                    }
                });
            }
        });
    } else {
        router.post(route('favorites.store'), { id: id }, {
            onSuccess: () => {
                Swal.fire({
                    icon: 'success',
                    title: 'Added to Favorites!',
                    text: 'Hotel has been added to your favorites list.',
                    confirmButtonColor: '#f97316'
                });
            },
            onError: (errors) => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to add to favorites.',
                    confirmButtonColor: '#f97316'
                });
            },
        });
    }
};

const mapLink = computed(() => {
    return `https://www.google.com/maps?q=${props.hotels.latitude},${props.hotels.longitude}&hl=en&z=14&output=embed`;
});


const haveNotRooms = computed(() => {
  return ['Apartment', 'Villa', 'Homestay', 'Chalet', 'Business Hotel', 'Extended Stay Hotel'].includes(props.hotels.accommodation_type);
});

const reserveRoom = () => {
    if (!validateDateSelection()) return;

    let roomData = [];
    let totalAmount = dayCount.value * props.hotels.price_per_night; // Calculate total amount correctly

    // Check if rooms are available
    if (props.hotels.rooms && Array.isArray(props.hotels.rooms)) {
        for (const room of props.hotels.rooms) {
            // Push each room to roomData with correct price calculation
            roomData.push({
                room_id: room.id,
                room_count: room.available_rooms,
                adult_count: room.max_adult,
                child_count: room.max_children,
                day_count: dayCount.value,
                price: dayCount.value * props.hotels.price_per_night
            });
        }
    } else {
        alert('No rooms available for this hotel.');
        return;
    }

    if (roomData.length === 0) {
        alert('No rooms available for booking.');
        return;
    }

    const checkInDate = form.check_in_date;
    const checkOutDate = form.check_out_date;
    const offer = form.offer;
    form.reset();

    const formData = {
        hotel_id: props.hotels.id,
        check_in_date: checkInDate,
        check_out_date: checkOutDate,
        total_price: totalAmount,
        status: 'pending',
        special_requests: '',
        room_id: roomData.map(r => r.room_id),
        room_count: roomData.map(r => r.room_count),
        adult_count: roomData.map(r => r.adult_count),
        child_count: roomData.map(r => r.child_count),
        day_count: roomData.map(r => r.day_count),
        price: roomData.map(r => r.price),
        offer: offer
    };

    Object.assign(form, formData);

    console.log('Submitting form data:', form.data());

    form.post(route('user.reservations.store'), {
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Reservation created successfully!',
                confirmButtonColor: '#f97316'
            }).then(() => {
                resetAllCounts();
                router.visit(route('user.reservations.index'));
            });
        },
        onError: (errors) => {
            console.error('Reservation failed:', errors);
            Swal.fire({
                icon: 'error',
                title: 'Reservation Failed',
                text: errors.message || 'An unknown error occurred',
                confirmButtonColor: '#f97316'
            });
        }
    });
};

const validateDateSelection = () => {
    if (!form.check_in_date || !form.check_out_date) {
        Swal.fire({
            icon: 'warning',
            title: 'Missing Dates',
            text: 'Please select both check-in and check-out dates.',
            confirmButtonColor: '#f97316'
        });
        return false;
    }

    const checkInDate = new Date(form.check_in_date);
    const checkOutDate = new Date(form.check_out_date);

    if (checkInDate >= checkOutDate) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid Dates',
            text: 'Check-out date must be after check-in date.',
            confirmButtonColor: '#f97316'
        });
        return false;
    }

    calculatedDayCount.value = calculateDayCount(checkInDate, checkOutDate);

      // Add validation for offer points
    if (props.isOffer && props.offerPoints) {
        const totalAmount = calculatedDayCount.value * props.hotels.price_per_night;
        if (totalAmount > props.offerPoints) {
            Swal.fire({
                icon: 'error',
                title: 'Insufficient Offer Points',
                text: `Your offer points (${props.offerPoints}) are not enough for this booking (${totalAmount} points needed).`,
                confirmButtonColor: '#f97316'
            });
            return false;
        }
    }

    return true;
};



const resetAllCounts = () => {
    props.hotels.rooms.forEach(room => {
        props.hotels.option_room.filter(option => option.room_id === room.id).forEach(option => {
            option.selectedCount = 0;
        });
    });
};

const showBankModal = ref(false);

function copyToClipboard(text) {
  navigator.clipboard.writeText(text).then(() => {
    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: 'success',
      title: 'Copied!',
      text: 'Copied to clipboard',
      showConfirmButton: false,
      timer: 1200,
      timerProgressBar: true,
      background: '#fff7ed',
      color: '#ea580c'
    });
  });
}

// Delete bank detail handler (implement backend logic as needed)
function deleteBankDetail(idx) {
  Swal.fire({
    title: 'Delete Bank Detail?',
    text: 'Are you sure you want to delete this bank detail?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ef4444',
    cancelButtonColor: '#d1d5db',
    confirmButtonText: 'Delete'
  }).then((result) => {
    if (result.isConfirmed) {
      // Emit event or call API to delete bank detail
      // Example: router.delete(route('bankDetail.delete', props.bankDetail[idx].id))
      // For now, just show a success message
      Swal.fire({
        icon: 'success',
        title: 'Deleted!',
        text: 'Bank detail deleted.',
        confirmButtonColor: '#f97316'
      });
      // Optionally, remove from local array if you want instant UI update:
      // props.bankDetail.splice(idx, 1);
    }
  });
}
</script>

  <style>
  /* Animations */
  .fade-enter-active, .fade-leave-active {
    transition: opacity 0.5s;
  }
  .fade-enter-from, .fade-leave-to {
    opacity: 0;
  }

  @keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
  }

  @keyframes slideInLeft {
    from { transform: translateX(-20px); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
  }

  @keyframes slideInRight {
    from { transform: translateX(20px); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
  }

  .animate-fadeIn {
    animation: fadeIn 0.8s ease-in-out;
  }

  .animate-slideInLeft {
    animation: slideInLeft 0.8s ease-in-out;
  }

  .animate-slideInRight {
    animation: slideInRight 0.8s ease-in-out;
  }

  /* Transitions for hover effects */
  .transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
  }

  .transition-colors {
    transition-property: color, background-color, border-color, text-decoration-color, fill, stroke;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
  }

  .transition-transform {
    transition-property: transform;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
  }

  /* Hover scale effect */
  .hover\:scale-105:hover {
    transform: scale(1.05);
  }

  .group:hover .group-hover\:scale-105 {
    transform: scale(1.05);
  }
  </style>

