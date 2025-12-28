<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps({
    hotels: {
        type: Array,
        required: true,
    },
    favorites: {
        type: Array,
        required: true,
    },
    users: {
        type: Object,
        required: false,
    },
    admin: {
        type: Boolean,
        required: false,
    },
    isOffer: {
        type: Boolean,
        required: false,
    },
});

console.log(props.isOffer);

const isUser = () => {
    return props.users?.role === 'user';
};

const isFavorite = (hotelId) => {
    return props.favorites.some(favorite => favorite.hotel_id === hotelId);
};

const toggleFavorite = (id) => {
    console.log('favourit hotel id:', id);

    if (isFavorite(id)) {
        console.log('favourite');
        router.delete(route('favorites.delete', id), {
            onSuccess: () => {
                console.log('favourite deleted');
            },
            onError: () => {
                console.log('favourite not deleted');
            }
        });
    } else {
        console.log('not favourite');
        router.post(route('favorites.store'), { id: id }, {
            onSuccess: () => {
                console.log('favourite added');
            },
            onError: () => {
                console.log('favourite not added');
            }
        });
    }
};
const roomIndex = (id) => {
    if (isUser()) {
        router.get(route('view-hotel.show', { id: id, isOffer: props.isOffer }));
    } else {
        router.get(route('hotel.show', { id: id }));
    }
};

</script>

<template>
    <div v-for="hotel in hotels" :key="hotel.id" :class="admin ? 'bg-gray-700' : 'bg-white'"
         class="rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300"
         @click="roomIndex(hotel.id)"
         style="cursor: pointer;">
        <div class="relative">
            <!-- Correct dynamic image path for Vue template -->
            <img :src="`/storage/${hotel.thumbnail_image}`" :alt="hotel.name" class="w-full h-48 object-cover" />
            <button @click.stop="toggleFavorite(hotel.id)"
                    class="absolute top-3 right-3 rounded-full p-2 transition-colors duration-200" v-if="isUser()"
                    :class="isFavorite(hotel.id) ? 'bg-orange-500' : 'bg-white'">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                     :class="isFavorite(hotel.id) ? 'text-white' : 'text-white stroke-orange-500'" viewBox="0 0 24 24"
                     :stroke="isFavorite(hotel.id) ? 'white' : '#f97316'" :stroke-width="2" fill="none">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            </button>
        </div>
        <div class="p-4">
            <h3 :class="admin ? 'text-gray-200' : 'text-gray-800'" class="text-lg font-semibold">
                {{ hotel.name }}
            </h3>
            <div class="flex items-center mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-500" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span :class="admin ? 'text-gray-400' : 'text-gray-600'" class="ml-2 text-sm">{{ hotel.location }}</span>
            </div>
        </div>
    </div>
</template>
