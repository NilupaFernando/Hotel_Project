<template>
    <Head title="Add Hotel" />

    <HotelOwnerAuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Add New Hotel
                </h2>
                <button
                    @click="goBack"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg shadow-sm hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400"
                >
                    Back
                </button>
            </div>
        </template>

        <div
            class="max-w-full lg:max-w-6xl mx-auto px-4 sm:px-8 lg:px-20 Hotel-style-2025-new add-scroll-bar"

        >
            <h2 class="flex justify-center text-2xl sm:text-3xl font-semibold leading-7 text-white mt-2 mb-6 font-serif">
                Add Hotel Information
            </h2>
            <form @submit.prevent="saveHotel" method="POST" class="space-y-6">

                <!-- 1. Basic Information -->
                <div class="bg-gray-800 p-4 rounded-lg mb-6">
                    <h3 class="text-xl text-white mb-4">Basic Information</h3>

                    <!-- Hotel Name -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-white">Hotel Name</label>
                            <input v-model="form.name" type="text" required class="w-full p-2 border rounded-lg text-black" />
                        </div>
                        <!-- Price per Night -->
                        <div class="grid grid-cols-4 ">
                            <div class="col-span-2">
                                <label for="resource" class="block text-sm font-medium text-white leading-6">Category</label>
                                <input
                                    type="text"
                                    v-model="form.category"
                                    class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm"
                                    placeholder="Enter or select resource"
                                    required
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-white">Category</label>
                                <select
                                    v-model="form.category"
                                    :multiple="MultipleCatogoryCreatPart"
                                    class="w-full p-2 border rounded-lg text-black">
                                    <option value="All">All</option>
                                    <option value="Beach">Beach</option>
                                    <option value="Mountain">Mountain</option>
                                    <option value="Forest">Forest</option>
                                    <option value="Historical Sites">Historical Sites</option>
                                    <option value="City">City</option>
                                    <option value="Luxury">Luxury</option>
                                    <option value="Resort">Resort</option>
                                    <option value="Business">Business</option>
                                    <option value="Budget">Budget</option>
                                    <option value="Boutique">Boutique</option>
                                    <option value="Eco-Friendly">Eco-Friendly</option>
                                    <option value="Family">Family</option>
                                    <option value="Romantic">Romantic</option>
                                    <option value="Wellness & Spa">Wellness & Spa</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-white">Description</label>
                        <textarea v-model="form.description" class="w-full p-2 border rounded-lg text-black"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- star -->
                        <div>
                            <div>
                                <label class="block text-sm font-medium text-white">Star</label>
                                <input v-model="form.star"  type="number" step="0.1" class="w-full p-2 border rounded-lg text-black" />
                            </div>
                            <p class="text-red-500 text-xs font-thin" v-if="form.errors.star">
                                {{ form.errors.star }}
                            </p>
                        </div>
                        <!-- Price per Night -->
                        <div>
                            <div>
                                <label class="block text-sm font-medium text-white">Price per Night ($)</label>
                                <input v-model="form.price_per_night" type="number" step="0.01" required class="w-full p-2 border rounded-lg text-black" />
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-800 p-4 rounded-lg mb-6">
                        <div class="grid grid-cols-4 ">
                            <div class="col-span-2">
                                <label
                                    for="image"
                                    class="block text-white text-sm font-medium leading-6"
                                >Upload Main Thumbnail</label>
                                <input
                                    type="file"
                                    name="image"
                                    @change="handleThumbImage"
                                    id="image"
                                    class="block w-full text-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                    required
                                />
                                <p class="text-red-500 text-xs font-thin" v-if="form.errors.image">
                                    {{ form.errors.image }}
                                </p>
                            </div>
                            <div class="col-span-2">
                                <img
                                    v-if="imagePreviewThumb"
                                    :src="imagePreviewThumb"
                                    alt="Preview"
                                    class="w-3/4 h-3/4 object-cover"
                                    style="height: 120px; width: 250px"
                                />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-white">add images</label>
                                <input type="file" id="imageUpload2" accept="image/*" @change="handleImageChangetest" />
                            </div>

                            <div class="flex flex-wrap gap-4">
                                <div v-for="(img, index) in form.images" :key="'existing-' + index" class="relative group">
                                    <img :src="typeof img === 'string' ? `/storage/${img}` : img.preview"
                                         class="w-24 h-24 object-cover rounded-lg shadow-md" />
                                    <input
                                        type="button"
                                        value="Remove"
                                        class="absolute top-0 right-0 bg-red-500 text-white px-2 rounded" @click="removeImage(index)"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Location and Contact -->
                <div class="bg-gray-800 p-4 rounded-lg mb-6">
                    <h3 class="text-xl text-white mb-4">Location & Contact</h3>

                    <!-- Location -->
                    <div>
                        <label class="block text-sm font-medium text-white">Location (Address)</label>
                        <input v-model="form.location" type="text" required class="w-full p-2 border rounded-lg text-black" />
                    </div>

                    <!-- Province & District -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-white">District</label>
                            <select required v-model="form.district_id" class="w-full p-2 border rounded-lg text-black">
                                <option value="">Select a District</option>
                                <option v-for="district in filterValueDistrict" :key="district.id" :value="district.id">
                                    {{ district.name }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <p class="text-white mt-2">Province</p>
                            <p class="text-white mt-2"> {{ form.province_name }}</p>
                        </div>
                    </div>


                    <!-- Latitude & Longitude -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-white">Latitude</label>
                            <input v-model="form.latitude" type="text" class="w-full p-2 border rounded-lg text-black" />
                            <p class="text-red-500 text-xs font-thin" v-if="form.errors.latitude">
                                {{ form.errors.latitude }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-white">Longitude</label>
                            <input v-model="form.longitude" type="text" class="w-full p-2 border rounded-lg text-black" />
                            <p class="text-red-500 text-xs font-thin" v-if="form.errors.longitude">
                                {{ form.errors.longitude }}
                            </p>
                        </div>
                    </div>


                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div>
                                <label class="block text-sm font-medium text-white">Contact Number</label>
                                <input v-model="form.contact_number" type="text" required class="w-full p-2 border rounded-lg text-black" />
                            </div>
                        </div>
                        <div>
                            <div>
                                <label class="block text-sm font-medium text-white">Email</label>
                                <input v-model="form.email" type="email" required class="w-full p-2 border rounded-lg text-black" />
                            </div>
                            <p class="text-red-500 text-xs font-thin" v-if="form.errors.email">
                                {{ form.errors.email }}
                            </p>
                        </div>
                    </div>
                    <!-- Contact Information -->
                    <div>
                        <label class="block text-sm font-medium text-white">Website</label>
                        <input v-model="form.website" type="url" class="w-full p-2 border rounded-lg text-black" />
                    </div>
                </div>

                <!-- 3. Amenities and Additional Details -->
                <div class="bg-gray-800 p-4 rounded-lg mb-6">
                    <h3 class="text-xl text-white mb-4">Amenities & Additional Details</h3>

                    <!-- Amenities -->

                    <div class="grid grid-cols-4 ">
                        <div class="col-span-2">
                            <label for="resource" class="block text-sm font-medium leading-6">Amenities</label>
                            <input
                                type="text"
                                v-model="form.amenities"
                                class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm"
                                placeholder="Enter or select resource"
                                required
                            />
                        </div>
                        <div class="col-span-2">
                            <label for="resource" class="block text-sm font-medium leading-6 invisible">Amenities</label>
                            <select
                                v-model="form.amenities"
                                :multiple="allowMultipleSelectionAmenities"
                                class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm"
                            >
                                <option disabled value="">Please select one or more</option>
                                <option>WiFi</option>
                                <option>Pool</option>
                                <option>Spa</option>
                                <option>Parking</option>
                                <option>Gym</option>
                                <option>Restaurant</option>
                                <option>Bar</option>
                                <option>Garden</option>
                                <option>Conference Hall</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-4 ">
                        <div class="col-span-2">
                            <label for="resource" class="block text-sm font-medium leading-6">Room Types</label>
                            <input
                                type="text"
                                v-model="form.room_types"
                                class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm"
                                placeholder="Enter or select resource"
                            />
                        </div>

                        <div class="col-span-2">
                            <label for="resource" class="block text-sm font-medium leading-6 invisible">Dropdown</label>
                            <select
                                v-model="form.room_types"
                                :multiple="allowMultipleSelectionHotel"
                                class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm"
                            >
                                <option disabled value="">Please select one or more</option>
                                <option>Single Room</option>
                                <option>Double Room</option>
                                <option>Twin Room</option>
                                <option>Triple Room</option>
                                <option>Quad Room</option>
                                <option>Deluxe Room</option>
                                <option>Suite</option>
                                <option>Junior Suite</option>
                                <option>Presidential Suite</option>
                                <option>Penthouse</option>
                                <option>Villa</option>
                                <option>Standard Room</option>
                                <option>Honeymoon Suite</option>
                                <option>Bungalow</option>
                                <option>Cottage</option>
                                <option>Dormitory</option>
                                <option>Accessible Room</option>
                            </select>
                        </div>
                    </div>

                    <!-- Check-in & Check-out Time -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-white">Check-in Time</label>
                            <input v-model="form.check_in_time" type="time" class="w-full p-2 border rounded-lg text-black" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-white">Check-out Time</label>
                            <input v-model="form.check_out_time" type="time" class="w-full p-2 border rounded-lg text-black" />
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end mt-6 gap-x-6">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded shadow hover:bg-blue-600">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </HotelOwnerAuthenticatedLayout>
</template>

<script setup>
import HotelOwnerAuthenticatedLayout from '@/Layouts/HotelOwner/HotelOwnerAuthenticatedLayout.vue';
import {Head, router, useForm} from '@inertiajs/vue3';
import { Inertia } from "@inertiajs/inertia";
import {computed, ref, watch} from "vue";

const allowMultipleSelectionHotel = ref(true);
const allowMultipleSelectionAmenities = ref(true);
const MultipleCatogoryCreatPart = ref(true);
const imagePreviewThumb = ref(null); // Holds the preview URL

const props = defineProps({
    districts: {
        type: Array,
    },
});

const form = useForm({
    name: "",
    description: "",
    location: "",
    province_name: "",
    district_id: "",
    latitude: "",
    longitude: "",
    star: "",
    price_per_night: "",
    amenities: "",
    room_types: [],
    check_in_time: "14:00",
    check_out_time: "11:00",
    contact_number: "",
    email: "",
    website: "",
    images: [],
    category: "All",
    thumbnail_image: null,

    photo: [],
    removed_images: []
});

const filterValueDistrict = computed(() => {
    console.log("Filtering for province:", form.province_name);
    return props.districts.filter(district => {
        if (form.name && form.name === district.name) {
            return true;  }
        return true;
    });
});

watch(() => form.district_id, (newDistrictId) => {
    if (!newDistrictId) return;
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
            imagePreviewThumb.value = e.target.result; // Set the preview URL
        };
        reader.readAsDataURL(file);
    }
};


let arrayImage = ref([])
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



const saveHotel = () => {
    form.photo = [];
    form.images.forEach((image) => {
        if (image.file) {
            form.photo.push(image.file);
        } else {
            form.photo.push(image.url); // Keep DB images
        }
    });

    form.removed_images = JSON.stringify(removedImages.value || []);

    form.post(route('hotelOwner.hotel.store'), {
        onSuccess: () => {
            alert("Hotel Save Successfully");
        },
        onError: (errors) => {
            console.log("district Save errors:", errors);
            if (errors.email) {
                form.errors.name = errors.email;
            }
            if (errors.latitude) {
                form.errors.latitude = errors.latitude;
            }
            if (errors.longitude) {
                form.errors.longitude = errors.longitude;
            }
            if (errors.location) {
                form.errors.location = "The location is required.";
            }
            if (errors.image) {
                form.errors.image = errors.image;
            }
            if (errors.star) {
                form.errors.star = errors.star;
            }
        },
    });
};

const goBack = () => {
    router.get(route('hotelOwner.hotel.index'));
};
</script>
