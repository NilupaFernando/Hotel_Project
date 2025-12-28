<template>
    <div class="">
        <!-- <Head title="Dashboard" /> -->
        <HotelOwnerAuthenticatedLayout>

            <div>
                <ViewHotelHeadSection :users="users" :hotels="hotels" :favorites="favorites" :bankDetail="bankDetails"/>
            </div>

            <div class="py-10 mt-10 ml-10 mr-14">
                <div class="flex items-center justify-between h-10 p-3 mb-8 rounded-lg">
                    <div class="mb-12 text-center">
                        <h2 class="text-4xl font-['Black_Ops_One'] text-orange-500 mb-4 relative inline-block"
                            data-aos="fade-right" data-aos-duration="1500">
                            Our Rooms
                        </h2>
                    </div>

                    <div
                        v-if="!['Villa', 'Homestay', 'Chalet', 'Business Hotel', 'Extended Stay Hotel', 'Apartment'].includes(hotels.accommodation_type)">
                        <a :href="route('hotelOwner.room.create', { id: hotels.id })"
                            class="inline-flex items-center px-5 py-2.5 font-semibold text-white bg-gradient-to-r from-gray-500 to-gray-600 rounded-lg shadow-lg hover:from-orange-400 hover:to-orange-600 transition-all duration-300 transform hover:scale-105 hover:shadow-xl gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Add Room
                        </a>
                    </div>
                </div>


                <div class="max-w-full mx-auto sm:px-6 lg:px-8">
                    <template v-if="hotels.rooms.length > 0">
                        <div class="relative overflow-hidden bg-white shadow-xl rounded-xl">
                            <div class="overflow-x-auto whitespace-nowrap custom-scrollbar"
                                style="max-width: 100%; -webkit-overflow-scrolling: touch;">
                                <table class="w-full divide-y divide-gray-200" style="min-width: 1200px;"data-aos="fade-up" data-aos-duration="1500">
                                    <thead>
                                        <tr class="bg-gradient-to-r from-[#FF9B50] to-[#FF9B50]">
                                            <th scope="col"
                                                class="px-10 py-6 font-semibold tracking-wider text-center text-white border-r border-white text-md">
                                                Room Type</th>
                                            <th scope="col"
                                                class="px-4 py-6 font-semibold tracking-wider text-center text-white border-r border-white text-md">
                                                Room Count</th>
                                            <th scope="col"
                                                class="px-10 py-6 font-semibold tracking-wider text-center text-white border-r border-white text-md">
                                                Numbers Of Geste</th>
                                            <th scope="col"
                                                class="px-10 py-6 font-semibold tracking-wider text-center text-white border-r border-white text-md">
                                                Price</th>
                                            <th scope="col"
                                                class="px-4 py-6 font-semibold tracking-wider text-center text-white border-r border-white text-md">
                                                Discount</th>
                                            <th scope="col"
                                                class="px-8 py-6 font-semibold tracking-wider text-center text-white border-r border-white text-md">
                                                Features</th>
                                            <th scope="col"
                                                class="px-8 py-6 font-semibold tracking-wider text-left text-white border-r border-white text-md">
                                                Free Services</th>
                                            <th scope="col"
                                                class="px-12 py-6 font-semibold tracking-wider text-center text-white text-md">
                                                Description</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-300">
                                        <tr v-for="room in hotels.rooms" :key="room.id"
                                            class="transition-all duration-200 ease-in-out hover:bg-orange-50">
                                            <td class="px-10 py-4 text-lg font-semibold text-orange-600 border-r border-[#FF9B50]">
                                                {{ room.type }}
                                                <div class="flex flex-col mt-5 space-y-2">
                                                    <button @click="goToUpdatePage(room.id)"
                                                        class="flex items-center justify-center px-5 py-2 text-sm font-semibold text-green-500 transition-all duration-300 transform bg-white border-2 border-green-500 rounded-lg shadow-sm hover:bg-green-500 hover:text-white hover:scale-105 hover:shadow-md">
                                                        Update
                                                    </button>
                                                    <button @click="deleteRoom(room.id)"
                                                        class="flex items-center justify-center px-5 py-2 text-sm font-semibold text-red-500 transition-all duration-300 transform bg-white border-2 border-red-500 rounded-lg shadow-sm hover:bg-red-500 hover:text-white hover:scale-105 hover:shadow-md">
                                                        Delete
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 text-center border-r border-[#FF9B50]">{{ room.bookable_rooms }}</td>
                                            <td class="px-10 py-4 border-r border-[#FF9B50]">
                                                <div v-if="room.option_room && room.option_room.length"
                                                    class="space-y-5">
                                                    <div v-for="option in room.option_room" :key="option.id"
                                                        class="font-medium text-gray-600 ">
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                                                            {{ option.adult_count }} Adults, {{ option.child_count }}
                                                            Children
                                                        </span>
                                                    </div>
                                                </div>
                                                <div v-else class="text-sm text-gray-600">
                                                    <p>Max adult: {{ room.max_adult }}</p>
                                                    <p>Max child: {{ room.max_children || "No children" }}</p>
                                                </div>
                                            </td>
                                            <td class="px-10 py-4 text-center border-r border-[#FF9B50]">
                                                <div v-if="room.option_room && room.option_room.length"
                                                    class="space-y-5">
                                                    <div v-for="option in room.option_room" :key="option.id"
                                                        class="font-medium text-gray-900">
                                                        LKR {{ option.price }}
                                                    </div>
                                                </div>
                                                <div v-else class="font-medium text-gray-900">
                                                    LKR {{ hotels.price_per_night }}
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 text-center text-gray-700 border-r border-[#FF9B50]">
                                                {{ room.discount || 'Not added' }}
                                            </td>
                                            <td class="px-8 py-4 text-gray-700 border-r border-[#FF9B50]">
                                                <div v-if="room.features" class="flex flex-wrap gap-2">
                                                    <div v-for="feature in room.features.split(',')" :key="feature" 
                                                         class="flex items-center gap-1 px-2 py-1 text-sm text-orange-700 bg-orange-100 rounded-full">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        <span>{{ feature.trim() }}</span>
                                                    </div>
                                                </div>
                                                <span v-else>Not added</span>
                                            </td>
                                            <td class="px-8 py-4 text-gray-700 border-r border-[#FF9B50]">
                                                <div v-if="room.free_services" class="flex flex-wrap gap-2">
                                                    <div v-for="service in room.free_services.split(',')" :key="service"
                                                         class="flex items-center gap-1 px-2 py-1 text-sm text-green-700 bg-green-100 rounded-full">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        <span>{{ service.trim() }}</span>
                                                    </div>
                                                </div>
                                                <span v-else>Not added</span>
                                            </td>
                                            <td class="px-12 py-4 text-gray-700">
                                                {{ room.description || 'No description available' }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </template>

                    <!-- No Rooms Available -->
                    <template v-else>
                        <!-- Empty State -->
                        <div class="p-12 text-center">
                            <div
                                class="flex items-center justify-center w-24 h-24 mx-auto mb-4 bg-gray-100 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <h3 class="mb-2 text-lg font-semibold text-gray-800">No Available Rooms</h3>
                            <p class="text-gray-600">There are currently no available rooms to review.</p>
                        </div>
                    </template>
                </div>
            </div>
            <div>
                <ImageGallery :images="hotels.images" />
            </div>
        </HotelOwnerAuthenticatedLayout>
    </div>
</template>

<script setup>
import { ref, computed, watch, watchEffect } from 'vue';
import HotelOwnerAuthenticatedLayout from '@/Layouts/HotelOwner/HotelOwnerAuthenticatedLayout.vue';
import { router, useForm } from '@inertiajs/vue3';
import ViewHotelHeadSection from '@/Components/ViewHotelHeadSection.vue';
import axios from 'axios';
import ImageGallery from '@/Components/ImageGallery.vue';
import Swal from 'sweetalert2';

const currentIndex = ref(0);

const adult_count = ref([]);
const child_count = ref([]);
const room_count = ref([]);
let day_count = ref([]);

let room_id = ref([]);
let price = ref([]);

const props = defineProps({
    hotels: Object,
    districts: Object,
    favorites: Object,
    users: Object,
    bankDetails: Object,
});

console.log(props.bankDetails);

props.hotels.rooms.forEach(room => {
    console.log(room.option_room);
});

const goToUpdatePage = (roomId) => {
    router.get(route('hotelOwner.room.edit', { id: roomId }));
};

const deleteRoom = async (roomId) => {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!",
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                await axios.delete(`/my-room/${roomId}/delete`);
                Swal.fire({
                    title: "Deleted!",
                    text: "The room has been deleted.",
                    icon: "success",
                    timer: 1500,
                    iconColor: "#ED9B40",
                });
                location.reload(); // Refresh page or update state
            } catch (error) {
                Swal.fire({
                    title: "Delete Failed",
                    text: "An error occurred while deleting the room.",
                    icon: "error",
                    confirmButtonColor: "#d33",
                });
                console.error("Delete failed:", error);
            }
        }
    });
};

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
    price: price.value
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

const submitFormResevation = () => {
    form.post(route('reservations.store'), {
        onSuccess: () => {
            alert("Hotel saved successfully!");;
        },
        onError: (errors) => {
            console.log("Save errors:", errors);
        },
    });
};

const calculateDayCount = () => {
    if (form.check_in_date && form.check_out_date) {
        const checkIn = new Date(form.check_in_date);
        const checkOut = new Date(form.check_out_date);
        const timeDiff = checkOut - checkIn;
        const days = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));

        if (days > 0) {
            day_count.value = props.hotels.rooms.map((_, index) => days);
            console.log('Day count:', day_count.value);
        } else {
            day_count.value = props.hotels.rooms.map((_, index) => 0);
            console.log('Day count (zero):', day_count.value);
        }
    }
};

const nextImage = () => {
    if (currentIndex.value < props.hotels.images.length - 1) {
        currentIndex.value++;
    } else {
        currentIndex.value = 0;
    }
};

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
        if (confirm('Are you sure you want to remove this favorite?')) {
            router.delete(route('favorites.delete', id), {
                onSuccess: () => {
                    alert("Favorite deleted successfully!");
                },
                onError: (error) => {
                    console.error('Error Deleting Hotel:', error);
                    alert("Failed to delete Hotel!");
                }
            });
        }
    } else {
        router.post(route('favorites.store'), { id: id }, {
            onSuccess: () => {
                alert("Added to your favorite list");
                console.log("Hotel added to favorites successfully");
            },
            onError: (errors) => {
                console.log("Error adding hotel to favorites:", errors);
            },
        });
    }
};

const mapLink = computed(() => {
    return `https://www.google.com/maps?q=${props.hotels.latitude},${props.hotels.longitude}&hl=en&z=14&output=embed`;
});

const goBack = () => {
    window.history.back();
    // Inertia.visit(route('hotel.index'));
};
</script>

<style>
.custom-scrollbar::-webkit-scrollbar {
    height: 8px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 4px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.7);
    border-radius: 4px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(240, 143, 46, 0.9);
}
</style>
