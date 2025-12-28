<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from "vue";
import Swal from 'sweetalert2';

// Props
const props = defineProps({
  districts: Array,
  users: Object,
  hotel: Object
});

console.log(props.hotel);
// Refs and reactive variables
const allowMultipleSelectionHotel = ref(true);
const allowMultipleSelectionAmenities = ref(true);
const MultipleCatogoryCreatPart = ref(true);
const imagePreviewThumb = ref(null);
const imagePreview = ref(null);
const imagePreviewHotelEdit = ref(null);
const removedImages = ref([]);
const arrayImage = ref([]);
const activeSection = ref('property');

// Static data
const amenities = [
  "Free Wi-Fi", "Air Conditioning", "Swimming Pool", "Gym", "Restaurant", "Spa", "Room Service",
  "Free Parking", "Bar", "Concierge", "Laundry Service", "24-Hour Front Desk", "Elevator",
  "Pet-Friendly", "Shuttle Service", "Mini Bar", "Jacuzzi", "Sauna", "Housekeeping",
  "Smoke-Free Rooms", "Non-Smoking Rooms", "Child Care", "Complimentary Breakfast"
];

const categories = [
  "Hotel", "Resort", "Guesthouse", "Villa", "Hostel", "Apartment", "Homestay", "Motel",
  "Business Hotel", "Chalet"
];


const stateCategories = [
  "Beach", "Forest", "Historical Sites", "Mountain", "Lakes", "Tourist Activities", "Wildlife"
];

// Form setup
const form = useForm({
  name: props.hotel.name || "",
  description: props.hotel.description || "",
  location: props.hotel.location || "",
  province_name: props.hotel.province_name || "",
  district_id: props.hotel.district?.id || "",
  latitude: props.hotel.latitude || "",
  longitude: props.hotel.longitude || "",
  accommodation_type: props.hotel.accommodation_type || "",
  star: props.hotel.star || "",
  price_per_night: props.hotel.price_per_night || "",
  amenities: props.hotel.amenities || [],
  room_types: props.hotel.rooms ? props.hotel.rooms.map(room => room.type) : [],
  check_in_time: props.hotel.check_in_time || "14:00",
  check_out_time: props.hotel.check_out_time || "11:00",
  contact_number: props.hotel.contact_number || "",
  email: props.hotel.email || "",
  website: props.hotel.website || "",
  category: props.hotel.category || [],
  images: props.hotel.images
    ? props.hotel.images.map(img => ({ url: img, preview: `/storage/${img}` }))
    : [],
  thumbnail_image: props.hotel.thumbnail_image || null,
  photo: [],
  removed_images: []
});

// Computed properties
const filterValueDistrict = computed(() => {
  if (!props.districts) return [];
  return props.districts.filter(district => {
    if (form.name && form.name === district.name) {
      return true;
    }
    return true;
  });
});


// Watchers
watch(() => form.district_id, (newDistrictId) => {
  if (!newDistrictId || !props.districts) return;
  const selectedDistrict = props.districts.find(d => d.id === newDistrictId);
  if (selectedDistrict && selectedDistrict.province?.province_name) {
    form.province_name = selectedDistrict.province.province_name;
  }
});


const isImageModalOpen = ref(false);
const fullImageUrl = ref('');

const showFullImage = (image) => {
    fullImageUrl.value = `/storage/${image}`;
    isImageModalOpen.value = true;
};
const closeModal = () => {
    isImageModalOpen.value = false;
};
const removeThumbnailImage = () => {
  form.thumbnail_image = null;
  imagePreviewThumb.value = null;
};
// Methods
const handleThumbImage = (event) => {
  const file = event.target.files[0];
  if (file) {
    form.thumbnail_image = file;
    const reader = new FileReader();
    reader.onload = (e) => {
      imagePreviewThumb.value = e.target.result;
    };
    reader.readAsDataURL(file);
  }
};

const handleImageUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    form.image = file;
    const reader = new FileReader();
    reader.onload = (e) => {
      imagePreview.value = e.target.result;
    };
    reader.readAsDataURL(file);
  }
};

const handleImageChangetest = (event) => {
  if (form.images.length < 8) {
    const file = event.target.files[0];
    if (file) {
      const newImage = {
        file,
        preview: URL.createObjectURL(file),
      };
      form.images.push(newImage);
    }
  } else {
    alert("You can only upload up to 8 images.");
  }
};

const removeImage = (index) => {
  if (form.images[index].url) {
    removedImages.value.push(form.images[index].url);
  }
  form.images.splice(index, 1);
};


const editHotel = (id) => {
  if (!form.thumbnail_image) {
    Swal.fire({
      title: "Missing Thumbnail",
      text: "Please upload a main thumbnail before updating the hotel.",
      icon: "warning",
      confirmButtonText: "OK",
      confirmButtonColor: "#3085d6",
    });
    return;
  }

  form.photo = form.images.map(image => image.file || image.url);
  form.removed_images = JSON.stringify(removedImages.value || []);
  form.post(route('hotelOwner.hotel.update', id), {
    preserveScroll: true, // Add this line
    onSuccess: () => {
      Swal.fire({
        title: "Success!",
        text: "Hotel updated successfully.",
        icon: "success",
        confirmButtonText: "OK",
        confirmButtonColor: "#ED9B40",
        iconColor: "#ED9B40"
      });
      form.images = [];
      removedImages.value = [];
    },
    onError: (errors) => {
      Swal.fire({
        title: "Error",
        text: Object.values(errors)[0] || "Failed to update the hotel. Please try again.",
        icon: "error",
        confirmButtonText: "OK",
        confirmButtonColor: "#d33",
      });
      console.error("Error updating hotel:", errors);
    },
  });
};
const hveNotRooms = computed(() => {
    return ['Apartment', 'Villa', 'Homestay', 'Chalet', 'Business Hotel', 'Extended Stay Hotel'].includes(props.hotel.accommodation_type);
});

</script>

<template>
  <div class="min-h-screen bg-gradient-to-b from-orange-50 to-white py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
      <!-- Header -->
      <div class="text-center mb-16">
        <h1 class="text-3xl md:text-4xl font-sans font-bold text-transparent bg-clip-text bg-gradient-to-r from-orange-400 via-yellow-400 to-orange-600 tracking-wide">
          Update Your Property
        </h1>
        <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto animate-fade-in">
          Showcase your property to travelers with a detailed and professional listing.
        </p>
      </div>

      <!-- Navigation Tabs -->
      <div class="flex flex-wrap justify-center mb-8 gap-2">
        <button
          v-for="section in ['property', 'location', 'amenities']"
          :key="section"
          @click="activeSection = section"
          :class="[
            'px-4 py-2 rounded-full text-sm font-medium transition-all duration-300',
            activeSection === section
              ? 'bg-orange-500 text-white shadow-md transform scale-105'
              : 'bg-white text-gray-600 hover:bg-orange-100'
          ]"
        >
          {{ section.charAt(0).toUpperCase() + section.slice(1).replace('_', ' ') }}
        </button>
      </div>

      <form @submit.prevent="editHotel(props.hotel.id)" method="POST"  class="space-y-8">
      
        <!-- Property Information Card -->
        <div v-show="activeSection === 'property'"
          class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100 transition-all duration-500 transform">
          <div class="flex items-center mb-6">
            <div class="p-3 bg-gradient-to-br from-orange-400 to-orange-600 rounded-lg mr-4 text-white">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h-14" />
              </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Property Information</h2>
          </div>

          <div class="space-y-6">
            <div class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
                  <label class="block text-sm font-medium text-gray-700">Property Name</label>
                  <input v-model="form.name" type="text" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm" />
                </div>
                <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
                  <label class="block text-sm font-medium text-gray-700">Accommodation Type</label>
                  <select v-model="form.accommodation_type"
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm appearance-none bg-white">
                    <option value="" disabled>Select accommodation type</option>
                    <option v-for="(category, index) in categories" :key="index" :value="category">
                      {{ category }}
                    </option>
                  </select>
                </div>
              </div>

              <!-- Conditional Fields for Villa -->
              <div v-if="haveNotRooms" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
                  <label class="block text-sm font-medium text-gray-700">Max Adults</label>
                  <input v-model="form.max_adults" type="number" min="1" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm" />
                </div>
                <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
                  <label class="block text-sm font-medium text-gray-700">Max Children</label>
                  <input v-model="form.max_children" type="number" min="0" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm" />
                </div>
              </div>
            </div>

            <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
              <label class="block text-sm font-medium text-gray-700">Description</label>
              <textarea v-model="form.description" rows="4"
                class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm"
                placeholder="Describe your property, its unique features, and what guests can expect"></textarea>
            </div>

            <div class="space-y-4 mb-10">
              <label class="block text-sm font-medium text-gray-700">State Category</label>
              <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <label v-for="state in stateCategories" :key="state"
                  class="flex items-center space-x-2 cursor-pointer transform transition-all duration-200 hover:translate-y-[-2px]">
                  <input type="checkbox" v-model="form.category" :value="state"
                    class="w-5 h-5 text-orange-600 border-gray-300 rounded focus:ring-orange-500" />
                  <span class="text-sm text-gray-700">{{ state }}</span>
                </label>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
                <label class="block text-sm font-medium text-gray-700">Star Rating</label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                      <path
                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                  </div>
                  <input v-model="form.star" type="number" step="0.1" min="0" max="5"
                    class="w-full pl-10 px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm" />
                </div>
              </div>
              <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
                <label class="block text-sm font-medium text-gray-700">Price per Night ($)</label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-gray-500">$</span>
                  </div>
                  <input v-model="form.price_per_night" type="number" step="0.01" min="0" required
                    class="w-full pl-8 px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm" />
                </div>
              </div>
              <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
                <label class="block text-sm font-medium text-gray-700">Cover Photo</label>
                <input type="file" @change="handleThumbImage" accept="image/*"
                  class="w-full px-4 py-3 rounded-lg border border-gray-200 file:bg-orange-50 file:border-0 file:rounded-lg file:px-4 file:py-2 file:text-orange-700 hover:file:bg-orange-100 transition-all shadow-sm" />
                <div class="relative group">
                  <img
                    :src="props.hotel.thumbnail_image ? `/storage/${props.hotel.thumbnail_image}` : imagePreviewThumb || '/default-image.jpg'"
                    class="w-full h-20 object-cover rounded-lg shadow-sm"
                    alt="Hotel Cover Image"
                  />
                  <button
                    v-if="props.hotel.thumbnail_image || imagePreviewThumb"
                    type="button"
                    @click="removeThumbnailImage"
                    class="absolute top-2 right-2 bg-red-500 text-white p-1 rounded-full shadow-md hover:bg-red-600 transition-all"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </div>
              </div>
            </div>

            <div v-if="imagePreviewThumb" class="flex justify-center">
              <div class="relative group">
                <img :src="imagePreviewThumb" alt="Thumbnail Preview"
                  class="h-32 w-32 object-cover rounded-lg shadow-md transition-all duration-300 group-hover:shadow-lg" />
                <div
                  class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 rounded-lg flex items-center justify-center">
                  <button type="button" @click="imagePreviewThumb = null"
                    class="opacity-0 group-hover:opacity-100 transition-opacity bg-red-500 text-white p-1 rounded-full">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </div>
              </div>
            </div>

            <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-700">Additional Photos (Max 8)</label>
              <div class="flex items-center justify-center w-full">
                <label
                  class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-all duration-300">
                  <div class="flex flex-col items-center justify-center pt-5 pb-6">
                    <svg class="w-10 h-10 mb-3 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    <p class="mb-2 text-sm text-gray-500">
                      <span class="font-semibold">Click to upload</span> or drag and drop
                    </p>
                    <p class="text-xs text-gray-500">PNG, JPG or JPEG (MAX. 2MB)</p>
                  </div>
                  <input type="file" @change="handleImageChangetest" accept="image/*" class="hidden" />
                </label>
              </div>
              <div class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div v-for="(img, index) in form.images" :key="index" class="relative group">
                  <img :src="typeof img === 'string' ? `/storage/${img}` : img.preview"
                    class="w-full h-24 object-cover rounded-lg shadow-md transition-all duration-300 group-hover:shadow-lg" />
                  <div
                    class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 rounded-lg flex items-center justify-center">
                    <button type="button" @click="removeImage(index)"
                      class="opacity-0 group-hover:opacity-100 transition-opacity bg-red-500 text-white p-1.5 rounded-full">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Location & Contact Card -->
        <div v-show="activeSection === 'location'"
          class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100 transition-all duration-500 transform">
          <div class="flex items-center mb-6">
            <div class="p-3 bg-gradient-to-br from-orange-400 to-orange-600 rounded-lg mr-4 text-white">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Location & Contact</h2>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
              <label class="block text-sm font-medium text-gray-700">District</label>
              <select v-model="form.district_id" required disabled
                class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm appearance-none bg-white">
                <option value="">Select a District</option>
                <option v-for="district in filterValueDistrict" :key="district.id" :value="district.id">
                  {{ district.name }}
                </option>
              </select>
            </div>
            <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
              <label class="block text-sm font-medium text-gray-700">Province</label>
              <input v-model="form.province_name" type="text" placeholder="Enter province"
                class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm" />
            </div>
            <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
              <label class="block text-sm font-medium text-gray-700">Latitude</label>
              <div class="relative">
                <input v-model="form.latitude" type="text"
                  class="w-full pl-10 px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm" />
              </div>
            </div>
            <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
              <label class="block text-sm font-medium text-gray-700">Longitude</label>
              <div class="relative">
                <input v-model="form.longitude" type="text"
                  class="w-full pl-10 px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm" />
              </div>
            </div>
            <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
              <label class="block text-sm font-medium text-gray-700">Address</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                  </svg>
                </div>
                <input v-model="form.location" type="text" disabled
                  class="w-full pl-10 px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm" />
              </div>
            </div>
            <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
              <label class="block text-sm font-medium text-gray-700">Contact Number</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                  </svg>
                </div>
                <input v-model="form.contact_number" type="text" required
                  class="w-full pl-10 px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm" />
              </div>
            </div>
            <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
              <label class="block text-sm font-medium text-gray-700">Email</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                  </svg>
                </div>
                <input v-model="form.email" type="email" required
                  class="w-full pl-10 px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm" />
              </div>
            </div>
            <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
              <label class="block text-sm font-medium text-gray-700">Website</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                  </svg>
                </div>
                <input v-model="form.website" type="url"
                  class="w-full pl-10 px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm" />
              </div>
            </div>
          </div>
        </div>

        <!-- Amenities & Details Card -->
        <div v-show="activeSection === 'amenities'"
          class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100 transition-all duration-500 transform">
          <div class="flex items-center mb-6">
            <div class="p-3 bg-gradient-to-br from-orange-400 to-orange-600 rounded-lg mr-4 text-white">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
              </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Amenities & Details</h2>
          </div>

          <div class="space-y-6">
            <div class="space-y-4">
              <label class="block text-sm font-medium text-gray-700">Amenities</label>
              <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <label v-for="amenity in amenities" :key="amenity"
                  class="flex items-center space-x-2 cursor-pointer transform transition-all duration-200 hover:translate-y-[-2px]">
                  <input type="checkbox" v-model="form.amenities" :value="amenity"
                    class="w-5 h-5 text-orange-600 border-gray-300 rounded focus:ring-orange-500" />
                  <span class="text-sm text-gray-700">{{ amenity }}</span>
                </label>
              </div>
            </div>
            <div v-if="!haveNotRooms" class="space-y-4">
              <label class="block text-sm font-medium text-gray-700">Room Types</label>
                  <div class="grid grid-cols-2 gap-3 md:grid-cols-3">
                    <div 
                      v-for="(room, index) in props.hotel.rooms" 
                      :key="index" 
                      class="flex items-center space-x-2"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                      </svg>
                      <span class="text-sm text-gray-700">{{ room.type }}</span>
                    </div>
                  </div>
                </div>


            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
                <label class="block text-sm font-medium text-gray-700">Check-in Time</label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                  <input v-model="form.check_in_time" type="time"
                    class="w-full pl-10 px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm" />
                </div>
              </div>
              <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
                <label class="block text-sm font-medium text-gray-700">Check-out Time</label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                  <input v-model="form.check_out_time" type="time"
                    class="w-full pl-10 px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm" />
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="flex justify-between pt-4">
          <button
            type="button"
            @click="activeSection = activeSection === 'property' ? 'property' :
              activeSection === 'location' ? 'property' : 'location'"
            class="px-6 py-3 bg-white text-orange-600 font-semibold rounded-lg border border-orange-200 hover:bg-orange-50"
            v-if="activeSection !== 'property'"
          >
            Previous
          </button>
          <button
            type="button"
            @click="activeSection = activeSection === 'property' ? 'location' :
              activeSection === 'location' ? 'amenities' : 'amenities'"
            class="px-6 py-3 bg-orange-600 text-white font-semibold rounded-lg hover:bg-orange-700"
            v-if="activeSection !== 'amenities'"
          >
            Next
          </button>
          <button
            type="submit"
            @click.prevent="editHotel(props.hotel.id)" 
            class="px-8 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold rounded-lg hover:from-orange-600 hover:to-orange-700"
            v-if="activeSection === 'amenities'"
          >
            Update Property
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<style>
/* Add these styles to your CSS */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}

.fade-enter,
.fade-leave-to {
  opacity: 0;
}

/* Hover animations */
input:focus,
select:focus,
textarea:focus {
  transform: translateY(-2px);
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

/* Gradient text animation */
@keyframes gradient {
  0% {
    background-position: 0% 50%;
  }

  50% {
    background-position: 100% 50%;
  }

  100% {
    background-position: 0% 50%;
  }
}

.bg-gradient-to-r {
  background-size: 200% auto;
  animation: gradient 5s ease infinite;
}
</style>


