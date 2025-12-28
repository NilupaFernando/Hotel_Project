<template>
    <Head title="Dashboard" />
    <HotelOwnerAuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <!-- District Header -->
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Hotel Information
                </h2>


                <!-- Add New District Section -->
                <div class="text-xl font-semibold leading-tight text-gray-800">
                    <button
                        class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400"
                        @click="addNewHotel"
                    >
                        Add New Hotel
                    </button>
                </div>
            </div>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 px-4">
                        <!-- Hotel List -->
                        <template v-if="hotels.data.length > 0">
                            <div v-for="hotel in hotels.data" :key="hotel.id" class="relative group">

                                    <a @click="showHotel(hotel.id)" class="block bg-white shadow-md rounded-lg overflow-hidden relative">
                                        <!-- Image -->
                                        <div  class="cursor-pointer">
                                            <img
                                                :src="hotel.thumbnail_image ? `/storage/${hotel.thumbnail_image}` : '/default-image.jpg'"
                                                :alt="hotel.name"
                                                class="w-full h-64 object-cover"
                                            />
                                        </div>

                                        <!-- Overlay with Icon & Message -->
                                        <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            <p class="text-white font-bold text-lg">View Details</p>
                                        </div>
                                    </a>
                                    <div class="p-4">
                                        <h2 class="font-bold text-xl">Name: {{ hotel.name }}</h2>
                                        <p v-if="hotel.star" class="text-gray-600 flex items-center">
                                            star:
                                            <span v-for="n in Math.floor(hotel.star)" :key="n" class="text-yellow-500">★</span>
                                        </p>
                                        <p v-if="hotel.travel_season!==null" class="text-gray-600">Sri Lanka {{ hotel.province_name }} {{ hotel.district.name }}.</p>
                                        <p class="text-gray-600">Travel Season: {{ hotel.district.travel_season }}</p>


                                        <div class="flex space-x-4 mt-2">
                                            <a :href="route('hotelOwner.hotel.edit', { id: hotel.id })" class="text-blue-500 font-bold">Edit Hotel</a>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <div class="flex space-x-4 mt-2">
                                                    <a @click="roomIndex(hotel.id)" class="text-blue-500 font-bold cursor-pointer">Room</a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <div class="flex space-x-4 mt-2">
                                                    <a @click="remove" class="text-blue-500 font-bold cursor-pointer">Room</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </template>

                        <!-- No Hotels Available -->
                        <template v-else>
                            <p>No hotels available.</p>
                        </template>
                    </div>

                    <div class="mt-4 gap-4 px-4 flex justify-center">
                        <template v-for="link in hotels.links" :key="link.url">
                            <Link
                                v-if="link.url"
                                :href="link.url"
                                class="mx-1 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                :class="{ 'font-bold': link.active }"
                            >
                                <span v-html="link.label"></span>
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </HotelOwnerAuthenticatedLayout>
</template>

<script setup>
import HotelOwnerAuthenticatedLayout from '@/Layouts/HotelOwner/HotelOwnerAuthenticatedLayout.vue';
import { Head,useForm,router,Link} from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    hotels: {
        type: Object,
    },
    favorites: {
        type: Array,
    },
});

// const favorites = props.favorites.data || props.favorites;
const isFavorite = (hotelId) => {
    return props.favorites.some(fav => fav.hotel_id === hotelId);
};

const addNewHotel = () => {
    router.get(route('hotelOwner.hotel.create'));
};


const testImagePart = () => {
    router.get(route('hotelOwner.imageTest.create'));
};


const showHotel = (hotelId) => {
    router.get(route('hotelOwner.hotel.show', { id: hotelId }));
};


const roomIndex = (id) => {
    router.get(route('hotelOwner.room.index',{ id: id }));
};

const remove = () => {
    router.get(route('hotelOwner.reservations.index'));
};

</script>
