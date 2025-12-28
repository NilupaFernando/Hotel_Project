<template>
    <Head title="District" />

    <AdminAuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Add New District
                </h2>
                <div class="text-xl font-semibold leading-tight text-gray-800">
                    <button
                        @click="goBack"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg shadow-sm hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400"
                    >
                        Back
                    </button>
                </div>
            </div>
        </template>

        <div
            class="max-w-full lg:max-w-6xl mx-auto sm:mt-0 px-4 sm:px-8 lg:px-20 add-scroll-bar District-style-2025-new"
        >
            <h2
                class="flex justify-center text-2xl sm:text-3xl font-semibold leading-7 text-white mt-2 mb-6 font-serif"
            >
                Add District Information
            </h2>
            <form
                @submit.prevent="saveDistrict"
                method="POST"
                class="max-w-full h-full"
            >
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 lg:gap-8 w-full h-full text-white table-container">
                    <div>
                        <label
                            for="province_id"
                            class="block text-sm font-medium leading-6"
                        >Province</label>
                        <div class="mt-2">
                            <select
                                id="province_id"
                                v-model="form.province_id"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                required
                            >
                                <option
                                    disabled
                                    value=""
                                >
                                    Please Select Province
                                </option>
                                <option v-for="province in provinces" :value="province.id" :key="province.id">
                                    {{ province.province_name }}
                                </option>
                            </select>
                        </div>
                    </div>


                    <div>
                        <label
                            for="district_id"
                            class="block text-sm font-medium leading-6"
                        >District</label>
                        <div class="mt-2">
                            <select
                                id="district_id"
                                v-model="form.name"
                                v-if="form.province_id"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                required
                            >
                                <option
                                    disabled
                                    value=""
                                >
                                    Please Select District
                                </option>
                                <option v-for="district in filteredDistricts" :value="district" :key="district">
                                    {{ district }}
                                </option>
                            </select>
                            <option v-else>Please select a province first.</option>
                        </div>
                        <p class="text-red-500 text-xs font-thin" v-if="form.errors.name">
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <!-- About -->
                    <div>
                        <label
                            for="about"
                            class="block text-sm font-medium leading-6"
                        >About</label>
                        <div class="mt-2">
                            <textarea
                                name="about"
                                id="about"
                                rows="4"
                                v-model="form.about"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                            ></textarea>
                        </div>
                    </div>

                    <!-- Mostly Popular -->
                    <div class="grid grid-cols-4 ">
                        <div class="col-span-2">
                            <label for="resource" class="block text-sm font-medium leading-6">Mostly Popular</label>
                            <input
                                type="text"
                                v-model="form.mostly_popular"
                                class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm"
                                placeholder="Enter custom resource"
                                required
                            />
                        </div>

                        <div class="col-span-2">
                            <label for="resource" class="block text-sm font-medium leading-6 invisible">Dropdown</label>
                            <select
                                v-model="form.mostly_popular"
                                :multiple="allowMultipleSelection"
                                class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm"
                            >
                                <option disabled value="">Please select one or more</option>
                                <option>Arts</option>
                                <option>Culture</option>
                                <option>Family friendly</option>
                                <option>Wildlife</option>
                                <option>nature</option>
                                <option>Adventure</option>
                                <option>Romantic</option>
                            </select>
                        </div>
                    </div>

                    <!-- Location -->
                    <div>
                        <label
                            for="location"
                            class="block text-sm font-medium leading-6"
                        >Location</label>
                        <div class="mt-2">
                            <input
                                type="text"
                                name="location"
                                id="location"
                                v-model="form.location"
                                autocomplete="location"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                            />
                        </div>
                    </div>

                    <!-- Image -->
                    <div class="grid grid-cols-4 ">
                        <div class="col-span-2">
                            <label
                                for="image"
                                class="block text-sm font-medium leading-6"
                            >Image</label>
                            <input
                                type="file"
                                name="image"
                                @change="handleImageUpload"
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
                                v-if="imagePreview"
                                :src="imagePreview"
                                alt="Preview"
                                class="w-3/4 h-3/4 object-cover"
                                style="height: 120px; width: 250px"
                            />
                        </div>
                    </div>

                    <!-- Travel Season -->
                    <div>
                        <label
                            for="travel_season"
                            class="block text-sm font-medium leading-6"
                        >Travel Season</label>
                        <div class="mt-2">
                            <input
                                type="text"
                                name="travel_season"
                                id="travel_season"
                                v-model="form.travel_season"
                                autocomplete="travel_season"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                            />
                        </div>
                    </div>

                    <!-- Popular -->
                </div>

                <div class="flex justify-end mt-6 gap-x-6 ">
                    <button
                        type="submit"
                        class="bg-green-600 text-white px-4 py-2 rounded shadow hover:bg-blue-600"
                    >
                        Save
                    </button>
                </div>
            </form>
        </div>
    </AdminAuthenticatedLayout>
</template>

<script setup>

import { Inertia } from '@inertiajs/inertia';
import AdminAuthenticatedLayout from '@/Layouts/Admin/AdminAuthenticatedLayout.vue';
import {Head, router, useForm} from '@inertiajs/vue3';
import {computed, ref, watch} from "vue";

const allowMultipleSelection = ref(false);
const imagePreview = ref(null); // Holds the preview URL

const props = defineProps({
    provinces: {
        type: Array,
    },
});

const filteredDistricts = computed(() => {
    if (!form.province_id) return [];
    const selectedProvince = props.provinces.find(
        (province) => province.id === form.province_id
    );
    return selectedProvince ? selectedProvince.districts.split(", ") : [];
});

const form = useForm({
    province_id: '',
    name: '',
    about: '',
    location: '',
    mostly_popular: [],
    travel_season:'',
    image: null,
});

watch(
    () => form.mostly_popular,
    (newValue) => {
        allowMultipleSelection.value = newValue.includes("Arts") || newValue.includes("Family friendly")|| newValue.includes("Culture");
    }
);

const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.image = file;

        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result; // Set the preview URL
        };
        reader.readAsDataURL(file);
    }
};

const saveDistrict = () => {
    // console.log(form.data());
    form.post(route('district.store'), {
        onSuccess: () => {
            alert("district Save Successfully");
            console.log("district submitted successfully");
        },
        onError: (errors) => {
            console.log("district Save errors:", errors);
            if (errors.name) {
                form.errors.name = "The district has already been added.";
            }
            if (errors.location) {
                form.errors.location = "The location is required.";
            }
            if (errors.image) {
                form.errors.image = errors.image;
            }
        },
    });
};

const goBack = () => {
    router.get(route('district.index'));
};
</script>

