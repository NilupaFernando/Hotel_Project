<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import Swal from 'sweetalert2';

const props = defineProps({
  user: Object,
  districts:Array,
});

const imagePreviewThumb = ref(null);
const imagePreview = ref(null);
const isImageModalOpen = ref(false);
const fullImageUrl = ref('');

const form = useForm({
    permit_status: ''
});

const categories = [
    "Hotel",
    "Resort",
    "Guesthouse",
    "Villa",
    "Hostel",
    "Apartment",
    "Homestay",
    "Motel",
    "Business Hotel",
    "Chalet",
];

const showFullImage = (image) => {
    fullImageUrl.value = `/storage/${image}`;
    isImageModalOpen.value = true;
};

const closeModal = () => {
    isImageModalOpen.value = false;
};

const findDistrictName = computed(() => {
    if (!props.districts || !props.user.hotels || !props.user.hotels.length) return '';
    const district = props.districts.find(d => d.id === props.user.hotels[0].district_id);
    return district ? district.name : '';
});

const updatePermitStatus = (item, newStatus) => {
    if (item.permit_status === newStatus) {
        Swal.fire({
            title: "No Changes Made",
            text: `The permit status is already set to "${newStatus}".`,
            icon: "info",
            confirmButtonText: "OK",
            confirmButtonColor: "#3085d6",
        });
        return;
    }

    Swal.fire({
        title: `Change status to "${newStatus}"?`,
        text: "Are you sure you want to update the permit status?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, Update",
        cancelButtonText: "Cancel",
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
    }).then((result) => {
        if (result.isConfirmed) {
            form.post(route('update-permit-status.update', item.id), {
                onSuccess: () => {
                    item.permit_status = newStatus; // Update status locally
                    Swal.fire({
                        title: "Success!",
                        text: "Permit status updated successfully.",
                        icon: "success",
                        confirmButtonText: "OK",
                        confirmButtonColor: "#ED9B40",
                        iconColor: "#ED9B40"                 
                         }).then(() => {
                            window.location.href = route('admin.propertyRequest');
                        });
                },
                onError: (error) => {
                    Swal.fire({
                        title: "Error",
                        text: "Failed to update permit status. Please try again.",
                        icon: "error",
                        confirmButtonText: "OK",
                        confirmButtonColor: "#d33",
                    });
                    console.log("Status update failed:", error);
                },
            });
        }
    });
};

const filterValueDistrict = computed(() => {
    if (!props.districts) return [];
    
    console.log("Filtering for province:", form.province_name);
    return props.districts.filter(district => {
        if (form.name && form.name === district.name) {
            return true;
        }
        return true;
    });
});

watch(() => form.district_id, (newDistrictId) => {
    if (!newDistrictId || !props.districts) return;
    const selectedDistrict = props.districts.find(d => d.id === newDistrictId);
    if (selectedDistrict && selectedDistrict.province?.province_name) {
        form.province_name = selectedDistrict.province.province_name;
        console.log("Updated province_name:", form.province_name);
    }
});

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

let arrayImage = ref([]);
let removedImages = ref([]);

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
        alert("only you can upload 8 images");
    }
};

const removeImage = (index) => {
    if (form.images[index].url) {
        removedImages.value.push(form.images[index].url);
    }
    form.images.splice(index, 1);
};

const submit = () => {
    form.photo = [];
    form.images.forEach((image) => {
        if (image.file) {
            form.photo.push(image.file);
        } else {
            form.photo.push(image.url);
        }
    });

    // form.removed_images = JSON.stringify(removedImages.value || []);

    form.post(route('hotel-owner.register'), {
        onSuccess: () => {
            alert("Hotel Save Successfully");
        },
        onError: (errors) => {
            console.log("district Save errors:", errors);
        },
    });
};
</script>

<template>
  <div class="min-h-screen bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
      <!-- Header -->
      <div class="text-center mb-12">
        <h1 class="text-3xl md:text-4xl font-extrabold text-orange-500 tracking-tight">
          Manage Property Requests
        </h1>
        <p class="mt-3 text-lg text-gray-600 max-w-2xl mx-auto">
          Review & Manage Property Requests
        </p>
      </div>

      <form @submit.prevent="submit" class="space-y-8">
        <!-- Account Information Card -->
        <div class="bg-gray-800 rounded-2xl shadow-xl p-8 border border-gray-700">
          <div class="flex items-center mb-6">
            <div class="p-2 bg-orange-100 rounded-lg mr-3">
              <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-200">Account Details</h2>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-400">Full Name</label>
              <input :value="props.user.name" type="text" required 
                class="w-full px-4 py-3 bg-gray-700 rounded-lg border border-gray-600 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all text-gray-200" />
            </div>
            <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-400">Email Address</label>
              <input :value="props.user.email" type="email" required 
                class="w-full px-4 py-3 bg-gray-700 rounded-lg border border-gray-600 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all text-gray-200" />
            </div>
            <div class="space-y-2 md:col-span-2">
              <label class="block text-sm font-medium text-gray-400">Registration Number</label>
              <input :value="props.user.registration_number" type="text" required 
                class="w-full px-4 py-3 bg-gray-700 rounded-lg border border-gray-600 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all text-gray-200" />
            </div>
            <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-400">Business Registration Image</label>
              <img
                  :src="props.user.image ? `/storage/${props.user.image}` : '/default-image.jpg'"
                  class="w-full h-20 object-cover"
                  @click="showFullImage(props.user.image)"
                  alt="Hotel Registration Image"
              />
            </div>
          </div>
        </div>

        <!-- Property Information Card -->
        <div class="bg-gray-800 rounded-2xl shadow-xl p-8 border border-gray-700">
          <div class="flex items-center mb-6">
            <div class="p-2 bg-orange-100 rounded-lg mr-3">
              <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h-14"/>
              </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-200">Property Information</h2>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-400">Property Name</label>
              <input :value="props.user.hotels[0].name" type="text" required 
                class="w-full px-4 py-3 bg-gray-700 rounded-lg border border-gray-600 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all text-gray-200" />
            </div>
            <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-400">Accommodation Type</label>
              <select :value="props.user.hotels[0].accommodation_type" 
                class="w-full px-4 py-3 bg-gray-700 rounded-lg border border-gray-600 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all text-gray-200">
                <option v-for="(category, index) in categories" :key="index" :value="category">
                  {{ category }}
                </option>
              </select>
            </div>
          </div>

          <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-400">Description</label>
            <textarea :value="props.user.hotels[0].description" rows="4" 
              class="w-full px-4 py-3 bg-gray-700 rounded-lg border border-gray-600 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all text-gray-200"></textarea>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-400">Star Rating</label>
              <input :value="props.user.hotels[0].star" type="number" step="1" min="0" max="5" 
                class="w-full px-4 py-3 bg-gray-700 rounded-lg border border-gray-600 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all text-gray-200" />
            </div>
            <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-400">Price per Night ($)</label>
              <input :value="props.user.hotels[0].price_per_night" type="number" step="0.01" min="0" required 
                class="w-full px-4 py-3 bg-gray-700 rounded-lg border border-gray-600 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all text-gray-200" />
            </div>
            <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-400">Cover Photo</label>
              <img
                :src="props.user.hotels[0].thumbnail_image ? `/storage/${props.user.hotels[0].thumbnail_image}` : '/default-image.jpg'"
                class="w-full h-20 object-cover"
                @click="showFullImage(props.user.hotels[0].thumbnail_image)"
                alt="Hotel Cover Image"
              />
            </div>
          </div>
        </div>

        <!-- Location & Contact Card -->
        <div class="bg-gray-800 rounded-2xl shadow-xl p-8 border border-gray-700">
          <div class="flex items-center mb-6">
            <div class="p-2 bg-orange-100 rounded-lg mr-3">
              <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4-243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-200">Location & Contact</h2>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-400">District</label>
              <input type="text" v-model="findDistrictName"
                class="w-full px-4 py-3 bg-gray-700 rounded-lg border border-gray-600 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all text-gray-200" />
            </div>
            <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-400">Province</label>
              <input :value="props.user.hotels[0].province_name" type="text" placeholder="Enter province" 
                class="w-full px-4 py-3 bg-gray-700 rounded-lg border border-gray-600 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all text-gray-200" />
            </div>
            <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-400">Address</label>
              <input :value="props.user.hotels[0].location" type="text"  
                class="w-full px-4 py-3 bg-gray-700 rounded-lg border border-gray-600 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all text-gray-200" />
            </div>
            <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-400">Contact Number</label>
              <input :value="props.user.hotels[0].contact_number" type="text" required 
                class="w-full px-4 py-3 bg-gray-700 rounded-lg border border-gray-600 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all text-gray-200" />
            </div>
            <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-400">Email</label>
              <input :value="props.user.hotels[0].email" type="email" required 
                class="w-full px-4 py-3 bg-gray-700 rounded-lg border border-gray-600 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all text-gray-200" />
            </div>
            <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-400">Website</label>
              <input :value="props.user.hotels[0].website" type="url" 
                class="w-full px-4 py-3 bg-gray-700 rounded-lg border border-gray-600 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all text-gray-200" />
            </div>
          </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end pt-4 space-x-2">
          <button
              @click="updatePermitStatus(props.user, form.permit_status='active')"
              class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 px-8 rounded-full shadow-md transition-transform transform hover:scale-105">     
              Approve
          </button>
          <button
              @click="updatePermitStatus(props.user , form.permit_status='inactive')"
              class="bg-red-500 hover:bg-red-600 text-white font-semibold py-3 px-8 rounded-full shadow-md transition-transform transform hover:scale-105">     
              Reject
          </button>
        </div>
      </form>
    </div>
  </div>

  <div v-if="isImageModalOpen" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50">
    <div class="relative">
      <img :src="fullImageUrl" class="max-w-full max-h-screen rounded-lg" alt="Full Size Hotel Image" />
      <button @click="closeModal" class="absolute top-2 right-2 text-white bg-red-500 p-2 rounded-full">
        ✕
      </button>
    </div>
  </div>
</template>