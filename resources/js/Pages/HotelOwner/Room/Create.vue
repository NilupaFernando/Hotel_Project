<template>

  <Head title="Add Hotel" />

  <HotelOwnerAuthenticatedLayout>
    <div class="min-h-screen py-8 bg-[#FFEFD7]">
      <div class="max-w-6xl px-4 mx-auto sm:px-6 lg:px-8">
        <h2 class="mb-1 text-3xl font-bold text-center text-[#E08219] font-['Black_Ops_One'] font-weight-400">
          Room Information
        </h2>
        <p class="mb-10 text-center text-xm">Add your hotel room details, including specifications and availability, to
          manage your listings easily.</p>

        <form @submit.prevent="saveRoom()" method="POST" class="space-y-8">
          <!-- Hotel Information Section -->
          <div
            class="p-6 transition duration-200 transform bg-white border-l-4 border-orange-500 shadow-lg rounded-2xl hover:shadow-xl hover:-translate-y-1">
            <h3 class="mb-6 text-2xl font-semibold text-center text-orange-600">Hotel Information</h3>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
              <div>
                <label class="block mb-2 font-medium text-gray-700">Hotel Name</label>
                <input v-model="hotel.name" type="text" required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" />
              </div>
              <div>
                <label class="block mb-2 font-medium text-gray-700">Location</label>
                <input v-model="hotel.location" type="text" required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" />
              </div>
              <div>
                <label class="block mb-2 font-medium text-gray-700">Contact Number</label>
                <input v-model="hotel.contact_number" type="text" required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" />
              </div>
              <div>
                <label class="block mb-2 font-medium text-gray-700">Email</label>
                <input v-model="hotel.email" type="text" required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" />
              </div>
            </div>
          </div>

          <!-- Room Information Section -->
          <div
            class="p-6 transition duration-200 transform bg-white border-l-4 border-orange-500 shadow-lg rounded-2xl hover:shadow-xl hover:-translate-y-1">
            <h3 class="mb-6 text-2xl font-semibold text-center text-orange-600">Room Types</h3>

            <div>
              <div>
                <!-- <label class="block mb-2 font-medium text-gray-700">Room Type</label> -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                  <label v-for="type in roomTypes" :key="type" class="flex items-center p-3">
                    <input type="radio" :value="type" v-model="form.type" name="roomType"
                      class="w-5 h-5 text-orange-500 focus:ring-orange-500" />
                    <span class="ml-2 text-gray-700">{{ type }}</span>
                  </label>
                </div>
                <p v-if="form.errors.type" class="mt-1 text-sm text-red-600">{{ form.errors.type }}</p>
              </div>
            </div>
          </div>

          <!-- Room Details Section -->

          <div
            class="p-6 transition duration-200 transform bg-white border-l-4 border-orange-500 shadow-lg rounded-2xl hover:shadow-xl hover:-translate-y-1">
            <h3 class="mb-6 text-2xl font-semibold text-center text-orange-600">Room Details</h3>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
              <div>
                <label class="block mb-2 font-medium text-gray-700">Selected Room Type</label>
                <input v-model="form.type" type="text"
                  class="w-full px-4 py-2 bg-orange-100 border border-orange-300 rounded-lg focus:border-orange-400 focus:ring-orange-300"
                  readonly />
              </div>
              <div>
                <label class="block mb-2 font-medium text-gray-700">Room Count</label>
                <input v-model="form.available_rooms" type="number"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" />
                <p v-if="form.errors.available_rooms" class="mt-1 text-sm text-red-600">
                  {{ form.errors.available_rooms }}
                </p>
              </div>
              <div class="md:col-span-2">
                <label class="block mb-2 font-medium text-gray-700">Description</label>
                <textarea 
                  v-model="form.description"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                  rows="3"
                ></textarea>
                <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                  {{ form.errors.description }}
                </p>
              </div>
              <div class="md:col-span-2">
                <label class="block mb-2 font-medium text-gray-700">Options for Guest Count</label>
                <p class="text-sm text-gray-500">Add an option to specify the guest count for each room type when adding
                  room details. This should allow you to set the maximum number of guests per room and price per night
                  to
                  ensure accurate booking information.</p>
                <button @click="showGuestOptionModal = true" type="button"
                  class="px-4 py-2 mt-2 font-semibold text-white bg-orange-500 rounded-lg shadow-md hover:bg-orange-600 hover:shadow-lg">
                  Add Guest Option
                </button>

                <!-- Dynamically Added Guest Options -->
                <div v-for="(option, index) in guestOptions" :key="index"
                  class="mt-4 overflow-hidden transition-all duration-200 border-0 rounded-lg shadow-md bg-gradient-to-r from-slate-50 to-white hover:shadow-lg">
                  <div class="flex flex-col justify-between sm:flex-row sm:items-center">
                    <div class="relative flex-1">
                      <div class="p-5">
                        <div class="flex items-center mb-3">
                          <span
                            class="px-3 py-1 text-sm font-medium text-orange-600 bg-orange-100 border-0 rounded-full">
                            Guest Option {{ index + 1 }}
                          </span>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                          <div class="flex items-center gap-2 text-slate-700">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                              fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"
                              strokeLinejoin="round" class="w-4 h-4 text-orange-500/70">
                              <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                              <circle cx="9" cy="7" r="4"></circle>
                              <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                              <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                            <span class="text-sm font-medium">
                              <strong>Max Adults:</strong> {{ option.max_adult }}
                            </span>
                          </div>

                          <div class="flex items-center gap-2 text-slate-700">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                              fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"
                              strokeLinejoin="round" class="w-4 h-4 text-orange-500/70">
                              <path d="M9 12h.01"></path>
                              <path d="M15 12h.01"></path>
                              <path d="M10 16c.5.3 1.5.5 2 .5s1.5-.2 2-.5"></path>
                              <path
                                d="M19 6.3a9 9 0 0 1 1.8 3.9 2 2 0 0 1 0 3.6 9 9 0 0 1-17.6 0 2 2 0 0 1 0-3.6A9 9 0 0 1 12 3c2 0 3.5 1.1 3.5 2.5s-.9 2.5-2 2.5c-.8 0-1.5-.4-1.5-1">
                              </path>
                            </svg>
                            <span class="text-sm font-medium">
                              <strong>Max Children:</strong> {{ option.max_children }}
                            </span>
                          </div>
                          <div class="flex items-center gap-2 text-slate-700">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                              fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"
                              strokeLinejoin="round" class="w-4 h-4 text-orange-500/70">
                              <line x1="12" y1="1" x2="12" y2="23"></line>
                              <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                            <span class="text-sm font-medium">
                              <strong>Price per Night:</strong> ${{ option.price_per_night }}
                            </span>
                          </div>
                        </div>
                      </div>

                      <div class="absolute top-0 right-0 items-center hidden h-full pr-4 sm:flex">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                          stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"
                          class="w-5 h-5 text-gray-400/40">
                          <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                      </div>
                    </div>

                    <div class="flex justify-end p-4 bg-slate-50 sm:p-5 sm:border-l">
                      <button @click="guestOptions.splice(index, 1)"
                        class="inline-flex items-center gap-1 px-3 py-1 text-sm font-semibold text-white transition-colors bg-red-500 rounded-lg shadow-md hover:bg-red-600 hover:shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                          stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"
                          class="w-4 h-4">
                          <path d="M3 6h18"></path>
                          <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                          <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                          <line x1="10" y1="11" x2="10" y2="17"></line>
                          <line x1="14" y1="11" x2="14" y2="17"></line>
                        </svg>
                        Remove
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Popup Modal -->
              <div v-if="showGuestOptionModal"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-lg">
                  <h3 class="mb-4 text-xl font-semibold text-center text-orange-600">Add Guest Option</h3>
                  <div class="space-y-4">
                    <div>
                      <label class="block mb-2 font-medium text-gray-700">Max Adults</label>
                      <input v-model="guestOption.max_adult" type="number"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" />
                    </div>
                    <div>
                      <label class="block mb-2 font-medium text-gray-700">Max Children</label>
                      <input v-model="guestOption.max_children" type="number"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" />
                    </div>
                    <div>
                      <label class="block mb-2 font-medium text-gray-700">Price per Night</label>
                      <input v-model="guestOption.price_per_night" type="number" step="0.01"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" />
                    </div>
                  </div>
                  <div class="flex justify-end mt-6 space-x-4">
                    <button @click="saveGuestOption" type="button"
                      class="px-4 py-2 font-semibold text-white bg-orange-500 rounded-lg shadow-md hover:bg-orange-600 hover:shadow-lg">
                      Save
                    </button>
                    <button @click="showGuestOptionModal = false" type="button"
                      class="px-4 py-2 font-semibold text-gray-700 bg-gray-200 rounded-lg shadow-md hover:bg-gray-300 hover:shadow-lg">
                      Cancel
                    </button>
                  </div>
                </div>
              </div>
              <div>
                <label class="block mb-2 font-medium text-gray-700">Discount</label>
                <input v-model="form.discount" type="number" step="0.01"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" />
                <p v-if="form.errors.discount" class="mt-1 text-sm text-red-600">{{ form.errors.discount }}</p>
              </div>
            </div>

            <!-- Features Section -->
            <div class="mt-8">
              <label class="block mb-3 text-lg font-medium text-gray-700">Features</label>
              <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                <label v-for="feature in features" :key="feature" class="flex items-center p-3">
                  <input type="checkbox" :value="feature" v-model="form.features"
                    class="w-5 h-5 text-orange-500 rounded focus:ring-orange-500" />
                  <span class="ml-2 text-gray-700">{{ feature }}</span>
                </label>
              </div>
              <p v-if="form.errors.features" class="mt-1 text-sm text-red-600">{{ form.errors.features }}</p>
            </div>

            <!-- Free Services Section -->
            <div class="mt-8">
              <label class="block mb-3 text-lg font-medium text-gray-700">Free Services</label>
              <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                <label v-for="service in freeServices" :key="service" class="flex items-center p-3">
                  <input type="checkbox" :value="service" v-model="form.free_services"
                    class="w-5 h-5 text-orange-500 rounded focus:ring-orange-500" />
                  <span class="ml-2 text-gray-700">{{ service }}</span>
                </label>
              </div>
              <p v-if="form.errors.free_services" class="mt-1 text-sm text-red-600">{{ form.errors.free_services }}</p>
            </div>
          </div>

          <!-- Submit Button -->
          <div class="flex justify-end mt-8">
            <button type="submit"
              class="px-8 py-3 font-semibold text-white transition duration-300 transform bg-orange-500 rounded-lg shadow-md hover:bg-orange-600 hover:shadow-lg hover:-translate-y-1">
              Save Room
            </button>
          </div>
        </form>
      </div>
    </div>
  </HotelOwnerAuthenticatedLayout>
</template>

<script setup>
import HotelOwnerAuthenticatedLayout from '@/Layouts/HotelOwner/HotelOwnerAuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Inertia } from "@inertiajs/inertia";
import {computed, reactive, ref} from "vue";
import Swal from 'sweetalert2';



const props = defineProps({
  hotel: {
    type: Object,
  },
});
console.log(props.hotel);

const roomTypes = [
  "Single Room",
  "Double Room",
  "Twin Room",
  "Triple Room",
  "Quad Room",
  "Suite",
  "Junior Suite",
  "Family Room",
  "Studio",
  "Penthouse",
  "Deluxe Room",
  "Superior Room",
  "Accessible Room",
  "Bunk Room",
  "Connecting Rooms",
];

const features = [
  'WiFi',
  'Air Conditioning',
  'Balcony',
  'Bathtub',
  'Flat-screen TV',
  'Mini Bar',
  'Safe Deposit Box',
  'Work Desk',
  'Soundproofing',
  'Sea View',
  'Mountain View',
  'Pool Access',
  'Jacuzzi',
  'Smart Home Controls',
  'Pet Friendly'
];

const freeServices = [
  'Breakfast',
  'Gym Access',
  'Swimming Pool',
  'WiFi',
  'Parking',
  'Room Cleaning',
  'Concierge Service',
  'Airport Shuttle',
  'Business Center',
  'Tea/Coffee',
  'Laundry Service',
  'Welcome Drink',
  'Bike Rental',
  'Beach Towels',
  'Childcare Services'
];
const guestOptions = ref([]);
const showGuestOptionModal = ref(false);

const guestOption = ref({
    max_adult: 1,
    max_children: 0,
    price_per_night: 0.0,
});

const form = useForm({
  hotel_id: props.hotel.id,
  type: "",
  available_rooms: "",
  description: "",
  max_adult: 1,
  max_children: 0,
  features: [],
  free_services: [],
  price_per_adult: '00.0',
  price_per_child: '00.0',
  discount: '00.0',
    guestOptions: guestOptions.value
});

console.log(form)

const formattedRoomTypes = computed(() => {
  return typeof props.hotel.room_types === "string"
    ? props.hotel.room_types.split(",")
    : props.hotel.room_types;
});

const saveRoom = () => {
    form.guestOptions = guestOptions.value;
    console.log("Form before sending:", form);
  form.post(route('hotelOwner.room.store'), {
    onSuccess: () => {
      Swal.fire({
        title: "Room Saved Successfully!",
        text: "The room information has been saved.",
        icon: "success",
        iconColor: "#ED9B40",
        confirmButtonText: "OK",
        confirmButtonColor: "#ED9B40",
      });
    },
    onError: (errors) => {
      Swal.fire({
        title: "Save Failed",
        text: "Please check the form for errors and try again.",
        icon: "error",
        confirmButtonText: "Retry",
        confirmButtonColor: "#d33",
      });
      console.log("Room Save errors:", errors);
    },
  });
  console.log("Form after sending:", form);
};

const goBackRoom = () => {
  window.history.back();
};

const saveGuestOption = () => {
    console.log("Before:",guestOptions.value);
  guestOptions.value.push({ ...guestOption.value });
  guestOption.value = { max_adult: 1, max_children: 0, price_per_night: 0.0 };
  showGuestOptionModal.value = false;
    console.log("Updated guestOptions:", guestOptions.value);
};
// console.log(guestOptions.value);
</script>