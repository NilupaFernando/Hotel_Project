<template>
    <Head title="District" />

    <AdminAuthenticatedLayout>
        <div class="bg-gray-900 min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                    <!-- Header Section -->
                    <div class="bg-gray-900 px-8 py-6">
                        <h2 class="text-3xl font-bold text-orange-500 text-center">
                            Edit District Information
                        </h2>
                    </div>

                    <form @submit.prevent="editDistrict(districts.id)" method="POST" class="p-8 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Province Selection -->
                            <div class="space-y-2">
                                <label for="province_id" class="text-sm font-semibold text-gray-300">
                                    Province
                                </label>
                                <select
                                    id="province_id"
                                    v-model="form.province_id"
                                    class="w-full rounded-md border-gray-600 bg-gray-700 text-gray-300 focus:border-orange-500 focus:ring focus:ring-orange-500 transition-all"
                                    required
                                >
                                    <option disabled value="">Select Province</option>
                                    <option v-for="province in provinces" :value="province.id" :key="province.id">
                                        {{ province.province_name }}
                                    </option>
                                </select>
                            </div>

                            <!-- District Selection -->
                            <div class="space-y-2">
                                <label for="district_id" class="text-sm font-semibold text-gray-300">
                                    District
                                </label>
                                <select
                                    id="district_id"
                                    v-model="form.name"
                                    v-if="form.province_id"
                                    class="w-full rounded-md border-gray-600 bg-gray-700 text-gray-300 focus:border-orange-500 focus:ring focus:ring-orange-500 transition-all"
                                    required
                                >
                                    <option disabled value="">Select District</option>
                                    <option v-for="district in filteredUpdateDistricts" :value="district" :key="district">
                                        {{ district }}
                                    </option>
                                </select>
                                <p v-else class="text-sm text-gray-500 italic">Please select a province first</p>
                                <p class="text-red-500 text-xs mt-1" v-if="form.errors.name">
                                    {{ form.errors.name }}
                                </p>
                            </div>

                            <!-- About Section -->
                            <div class="space-y-2 md:col-span-2">
                                <label for="about" class="text-sm font-semibold text-gray-300">
                                    About
                                </label>
                                <textarea
                                    id="about"
                                    v-model="form.about"
                                    @input="filterAboutInput"
                                    rows="4"
                                    class="w-full rounded-md border-gray-600 bg-gray-700 text-gray-300 focus:border-orange-500 focus:ring focus:ring-orange-500 transition-all"
                                    placeholder="Describe the district..."
                                ></textarea>
                                <p v-if="aboutError" class="text-red-500 text-xs mt-1">
                                    Special characters are not allowed in the About section.
                                </p>
                            </div>

                            <!-- Mostly Popular Section -->
                            <div class="space-y-4">
                                <label class="text-sm font-semibold text-gray-300">Mostly Popular</label><br>
                                <label class="text-sm text-gray-500">Use ctrl for select multiple options</label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <input
                                        type="text"
                                        v-model="form.MostlyPopular"
                                        @input="filterMostlyPopularInput"
                                        class="rounded-md border-gray-600 bg-gray-700 text-gray-300 focus:border-orange-500 focus:ring focus:ring-orange-500 transition-all"
                                        placeholder="Enter custom category"
                                        required
                                    />
                                    <p v-if="mostlyPopularError" class="text-red-500 text-xs mt-1">
                                        Only letters, spaces, and commas are allowed in the custom category.
                                    </p>
                                    <select
                                        v-model="form.MostlyPopular"
                                        :multiple="allowMultipleSelection"
                                        class="rounded-md border-gray-600 bg-gray-700 text-gray-300 focus:border-orange-500 focus:ring focus:ring-orange-500 transition-all"
                                    >
                                        <option disabled value="">Select categories</option>
                                        <option>Arts</option>
                                        <option>Culture</option>
                                        <option>Family friendly</option>
                                        <option>Wildlife</option>
                                        <option>Nature</option>
                                        <option>Adventure</option>
                                        <option>Romantic</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Location -->
                            <div class="space-y-2">
                                <label for="location" class="text-sm font-semibold text-gray-300">
                                    Location
                                </label>
                                <input
                                    type="text"
                                    id="location"
                                    value="6.9271° N, 79.8612° E"
                                    v-model="form.location"
                                    @input="validateLocationFormat"
                                    class="w-full rounded-md border-gray-600 bg-gray-700 text-gray-300 focus:border-orange-500 focus:ring focus:ring-orange-500 transition-all"
                                    placeholder="e.g. 6.9271° N, 79.8612° E"
                                />
                                <p v-if="locationFormatError" class="text-red-500 text-xs mt-1">
                                    Please enter coordinates in the format: 6.9271° N, 79.8612° E
                                </p>
                            </div>

                            <!-- Image Upload -->
                            <div class="space-y-4">
                                <label class="text-sm font-semibold text-gray-300">District Image</label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <input
                                            type="file"
                                            @change="handleUpdateImageUpload"
                                            class="w-full text-sm text-gray-500
                                            file:mr-4 file:py-2 file:px-4
                                            file:rounded-full file:border-0
                                            file:text-sm file:font-semibold
                                            file:bg-orange-50 file:text-orange-700
                                            hover:file:bg-orange-100
                                            transition-all"
                                        />
                                    </div>
                                    <div class="relative h-32 bg-gray-700 rounded-md overflow-hidden">
                                        <img
                                            v-if="imagePreview"
                                            :src="imagePreview"
                                            alt="Preview"
                                            class="h-full w-full object-cover"
                                        />
                                        <img
                                            v-else
                                            :src="`/storage/${form.image}`"
                                            :alt="form.name"
                                            class="h-full w-full object-cover"
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Travel Season -->
                            <div class="space-y-2">
                                <label for="travel_season" class="text-sm font-semibold text-gray-300">
                                    Travel Season
                                </label>
                                <input
                                    type="text"
                                    id="travel_season"
                                    v-model="form.travelSeason"
                                    @input="validateTravelSeasonInput"
                                    class="w-full rounded-md border-gray-600 bg-gray-700 text-gray-300 focus:border-orange-500 focus:ring focus:ring-orange-500 transition-all"
                                    placeholder="Best season to visit"
                                />
                                <p v-if="travelSeasonError" class="text-red-500 text-xs mt-1">
                                    Only letters and spaces are allowed in the travel season.
                                </p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end mt-8 space-x-4">
                            <button
                                type="button"
                                @click="goBack"
                                class="px-6 py-2 rounded-md text-gray-300 bg-gray-700 hover:bg-gray-600 transition-all"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                class="px-6 py-2 rounded-md text-white bg-orange-500 hover:bg-orange-600 transition-all"
                            >
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminAuthenticatedLayout>
</template>

<script setup>

import { Inertia } from '@inertiajs/inertia';
import AdminAuthenticatedLayout from '@/Layouts/Admin/AdminAuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from "vue";
import Swal from 'sweetalert2';

import { ref as vueRef } from 'vue';
const aboutError = vueRef(false);
const filterAboutInput = (event) => {
    const value = event.target.value;
    // Allow only letters, numbers, spaces, and . , ! ? ' " - ( ) : ;
    const filtered = value.replace(/[^a-zA-Z0-9 .,!?'"\-()\n:;]/g, '');
    if (value !== filtered) {
        event.target.value = filtered;
        form.about = filtered;
        aboutError.value = true;
    } else {
        aboutError.value = false;
    }
};

// Prevent numbers and special characters in Mostly Popular input (allow only letters, spaces, and commas)
const mostlyPopularError = vueRef(false);
const filterMostlyPopularInput = (event) => {
    const value = event.target.value;
    // Allow only letters, spaces, and commas
    const filtered = value.replace(/[^a-zA-Z ,]/g, '');
    if (value !== filtered) {
        event.target.value = filtered;
        form.MostlyPopular = filtered;
        mostlyPopularError.value = true;
    } else {
        mostlyPopularError.value = false;
    }
};

// Validate location input for latitude/longitude format (e.g., 6.9271° N, 79.8612° E)
const locationFormatError = vueRef(false);
const validateLocationFormat = (event) => {
    const value = event.target.value;
    // Regex for format: 6.9271° N, 79.8612° E (latitude and longitude with degree symbol, direction, comma, and space)
    const regex = /^\s*-?\d{1,2}\.\d+°\s*[NS],\s*-?\d{1,3}\.\d+°\s*[EW]\s*$/i;
    locationFormatError.value = !regex.test(value);
    form.location = value;
};
// Unified input validation for Travel Season
const travelSeasonError = vueRef(false);
const validateTravelSeasonInput = (event) => {
    const value = event.target.value;
    const filtered = value.replace(/[^a-zA-Z ]/g, '');
    if (value !== filtered) {
        event.target.value = filtered;
        form.travelSeason = filtered;
        travelSeasonError.value = true;
    } else {
        travelSeasonError.value = false;
    }
};

const allowMultipleSelection = ref(false);
const imagePreview = ref(null);

const props = defineProps({
    provinces: {
        type: Array,
    },
    districts: {
        type: Object,
    },
    getProvincesIdValue: {
        type: Object,
    },
});

const filteredUpdateDistricts = computed(() => {
    if (!form.province_id) return [];
    const selectedProvince = props.provinces.find(
        (province) => province.id === form.province_id
    );
    return selectedProvince ? selectedProvince.districts.split(", ") : [];
});

const form = useForm({
    province_id: props.getProvincesIdValue?.id || '',
    name: props.districts?.name || '',
    about: props.districts.about || '',
    location: props.districts.location || '',
    MostlyPopular: props.districts.mostly_popular || [],
    travelSeason: props.districts.travel_season,
    image: props.districts.image,
});

watch(
    () => form.MostlyPopular,
    (newValue) => {
        allowMultipleSelection.value = newValue.includes("Arts") || newValue.includes("Family friendly") || newValue.includes("Culture");
    }
);

const handleUpdateImageUpload = (event) => {
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

const editDistrict = (id) => {
    // Check for empty required fields
    if (!form.province_id || !form.name || !form.about || !form.MostlyPopular || !form.location || !form.travelSeason || !form.image) {
        Swal.fire({
            title: "Missing Fields!",
            text: "All fields are required. Please fill in all fields before saving.",
            icon: "warning",
            confirmButtonText: "OK",
            confirmButtonColor: "#d33",
        });
        return;
    }
    form.post(route('district.update', id), {
        onSuccess: () => {
            Swal.fire({
                title: "Update Successful!",
                text: "District information has been updated.",
                icon: "success",
                confirmButtonText: "OK",
                confirmButtonColor: "#ED9B40",
                iconColor: "#ED9B40"
            });
        },
        onError: (errors) => {
            Swal.fire({
                title: "Failed",
                text: "Please check the form for errors.",
                icon: "error",
                confirmButtonText: "Retry",
                confirmButtonColor: "#d33",
            });
            console.log("district Save errors:", errors);
        },
    });
};

const goBack = () => {
    router.get(route('district.index'));
};

const DeleteDistrict = (id) => {
    if (confirm('Are you sure you want to delete this district?')) {
        router.delete(route('district.delete', id), {
            onSuccess: () => {
                alert("District deleted successfully");
            },
            onError: (error) => {
                console.error('Error deleting district:', error);
                alert("Failed to delete district!");
                if (errors.name) {
                    form.errors.name = "The district has already been added.";
                }
                if (errors.location) {
                    form.errors.location = "The location is required.";
                }
                if (errors.image) {
                    form.errors.image = errors.image;
                }
            }
        });
    }
};
</script>