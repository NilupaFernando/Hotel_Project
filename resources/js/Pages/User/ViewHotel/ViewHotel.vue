<template>
    <AuthenticatedLayout>
        <div class="min-h-screen bg-white">
            <div>
                <ViewHotelHeadSection :users="users" :hotels="hotels" :favorites="favorites" :isOffer="isOffer" :offerPoints="offerPoints" />
            </div>

            <!-- Availability Section -->
            <div v-if="!haveRooms" class="px-4 py-12 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="mb-4" data-aos="fade-up" data-aos-duration="800">
                    <h2 class="text-3xl font-bold text-orange-500">
                        Explore Available Rooms & Book Instantly
                    </h2>
                </div>

                <!-- Date Picker Section -->
                <div class="mb-10 overflow-hidden shadow-lg rounded-xl">
                    <div class="flex flex-wrap gap-8 p-6 bg-white md:p-8">
                        <div class="flex-1 min-w-[240px] group">
                            <label
                                class="block mb-3 text-sm font-semibold text-gray-700 transition-colors duration-200 group-hover:text-orange-500">
                                <span class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-orange-500" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Check-in Date
                                </span>
                            </label>
                            <div class="relative">
                                <input type="date" v-model="form.check_in_date" :min="getCurrentDate()"
                                    @change="calculateDayCount"
                                    class="w-full p-4 transition-all duration-200 bg-white border-2 border-orange-200 rounded-lg focus:outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-200 hover:border-orange-300" />
                                <div class="absolute inset-y-0 flex items-center pointer-events-none right-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-orange-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="flex-1 min-w-[240px] group">
                            <label
                                class="block mb-3 text-sm font-semibold text-gray-700 transition-colors duration-200 group-hover:text-orange-500">
                                <span class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-orange-500" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Check-out Date
                                </span>
                            </label>
                            <div class="relative">
                                <input type="date" v-model="form.check_out_date" :min="getMinCheckoutDate()"
                                    @change="calculateDayCount"
                                    class="w-full p-4 transition-all duration-200 bg-white border-2 border-orange-200 rounded-lg focus:outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-200 hover:border-orange-300" />
                                <div class="absolute inset-y-0 flex items-center pointer-events-none right-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-orange-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div v-if="dayCount" class="flex items-end">
                            <div
                                class="px-5 py-3 text-white transition-transform duration-200 transform bg-orange-500 rounded-lg shadow-md hover:scale-105">
                                <span class="flex items-center font-semibold text-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ dayCount }} night<template v-if="dayCount > 1">s</template>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Room Availability Section with Table and Total Price Side by Side -->
                <div class="overflow-hidden bg-white shadow-lg rounded-xl">
                    <div class="flex flex-col lg:flex-row">
                        <!-- Room Availability Table (Left Side) -->
                        <div class="flex-1 overflow-x-auto">
                            <table class="w-full">
                                <!-- Table Header -->
                                <thead>
                                    <tr>
                                        <th class="p-2 font-semibold text-left text-white bg-orange-400">Room Type</th>
                                        <th class="p-4 font-semibold text-center text-white bg-orange-400">Guests</th>
                                        <th class="p-4 font-semibold text-center text-white bg-orange-400">Price per Night</th>
                                        <th class="p-4 font-semibold text-center text-white bg-orange-400">Discount</th>
                                        <th class="p-4 font-semibold text-center text-white bg-orange-400">Count</th>
                                    </tr>
                                </thead>

                                <!-- Table Body -->
                                <tbody v-if="hotels.rooms && hotels.rooms.length > 0">
                                    <!--  Room -->
                                    <tr v-for="room in hotels.rooms" :key="room.id" class="border-b">
                                        <td class="p-3 border border-orange-200">
                                            <div class="mb-4 font-medium text-orange-400">{{ room.type }}</div>
                                            <div class="grid grid-cols-2 gap-3 md:grid-cols-2">
                                                <div v-for="(feature, index) in room.features.split(',')" :key="index"
                                                    class="flex items-center space-x-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="w-4 h-4 text-orange-500" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <span>{{ feature }}</span>
                                                </div>
                                            </div>
                                        </td>

                                        <!--  Room Guest Options -->
                                        <td class="border border-orange-200">
                                            <div v-if="hotels.rooms.length && hotels.option_room.length > 0">
                                                <div v-for="option in hotels.option_room.filter(o => o.room_id === room.id)"
                                                    :key="option.id"
                                                    class="flex items-center justify-center p-3 space-x-4 border-b">
                                                    <!-- Adult Count -->
                                                    <div class="flex items-center">
                                                        <UserIcon class="w-6 h-6 text-orange-400" />
                                                        <span class="ml-1 text-sm font-medium text-gray-700">×</span>
                                                        <span class="ml-1 text-sm font-medium text-gray-700">{{
                                                            option.adult_count
                                                            }}</span>
                                                    </div>

                                                    <!-- Plus Icon -->
                                                    <div class="flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="w-4 h-4 text-orange-400" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M12 4v16m8-8H4" />
                                                        </svg>
                                                    </div>

                                                    <!-- Child Count -->
                                                    <div class="flex items-center">
                                                        <UserIcon class="w-5 h-5 text-orange-400" />
                                                        <span class="ml-1 text-sm font-medium text-gray-700">×</span>
                                                        <span class="ml-1 text-sm font-medium text-gray-700">{{
                                                            option.child_count
                                                            }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-else class="p-3 border-b">
                                                <div v-for="room in hotels.rooms" :key="room.id"
                                                    class="flex items-center justify-center space-x-4">
                                                    <!-- Adult Count -->
                                                    <div class="flex items-center">
                                                        <UserIcon class="w-6 h-6 text-orange-400" />
                                                        <span class="ml-1 text-sm font-medium text-gray-700">×</span>
                                                        <span class="ml-1 text-sm font-medium text-gray-700">{{
                                                            room.max_adult }}</span>
                                                    </div>

                                                    <!-- Plus Icon -->
                                                    <div class="flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="w-4 h-4 text-orange-400" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M12 4v16m8-8H4" />
                                                        </svg>
                                                    </div>

                                                    <!-- Child Count -->
                                                    <div class="flex items-center">
                                                        <UserIcon class="w-5 h-5 text-orange-400" />
                                                        <span class="ml-1 text-sm font-medium text-gray-700">×</span>
                                                        <span class="ml-1 text-sm font-medium text-gray-700">{{
                                                            room.max_children
                                                            }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Single Room Prices -->
                                        <td v-if="hotels.option_room.length > 0" class="border border-orange-200">
                                            <div v-for="option in hotels.option_room.filter(o => o.room_id === room.id)"
                                                :key="option.id" class="p-3 text-center border-b">
                                                {{ option.price }}
                                            </div>
                                        </td>
                                        <td v-else class="border border-orange-200">
                                            <div class="p-3 text-center border-b">
                                                {{ hotels.price_per_night }}
                                            </div>
                                        </td>

                                        <!-- Room Discount -->
                                        <td class="border border-orange-200">
                                            <div class="p-3 text-center border-b">
                                                <span v-if="room.discount && room.discount > 0" class="inline-flex items-center px-3 py-1 text-sm font-semibold text-white bg-red-500 rounded-full">
                                                    -{{ room.discount }}%
                                                </span>
                                                <span v-else class="text-sm text-gray-500">No discount</span>
                                            </div>
                                        </td>

                                        <!-- Single Room Count -->
                                        <td class="border border-orange-200">
                                            <div v-for="option in hotels.option_room.filter(o => o.room_id === room.id)"
                                                :key="option.id" class="p-2 border-b">
                                                <div class="flex flex-col items-center justify-center space-y-2">
                                                    <select class="w-16 p-1 text-center border rounded-md"
                                                        v-model="option.selectedCount"
                                                        @change="updateAvailableRooms(option, room)">
                                                        <option v-for="n in getRoomOptionAvailability(room, option)"
                                                            :key="n" :value="n">{{ n }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Total Price Section (Right Side) -->
                        <div v-if="calculateTotalPrice > 0"
                            class="flex flex-col justify-between w-full p-6 border-l border-orange-200 lg:w-80 bg-gray-50">
                            <div class="mb-6">
                                <h3 class="mb-4 text-xl font-bold text-orange-500">Your Booking Summary</h3>

                                <div v-if="selectedRoomsSummary.length > 0" class="mb-6">
                                    <h4 class="mb-2 font-medium text-gray-700">Selected Rooms:</h4>
                                    <ul class="space-y-2">
                                        <li v-for="(item, index) in selectedRoomsSummary" :key="index"
                                            class="flex items-start text-sm text-gray-600">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="flex-shrink-0 w-4 h-4 mt-1 mr-2 text-orange-400" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                            <span>{{ item }}</span>
                                        </li>
                                    </ul>
                                </div>

                                <div class="pt-4 border-t border-orange-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="font-medium text-gray-600">Total Price:</span>
                                        <span class="text-2xl font-bold text-orange-500">{{
                                            formatCurrency(calculateTotalPrice)
                                        }}</span>
                                    </div>

                                    <div v-if="isOffer" class="mt-2 space-y-2">
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-gray-600">Your Offer Points:</span>
                                            <span class="font-medium text-orange-500">{{ formatCurrency(offerPoints) }}</span>
                                        </div>
                                        <div v-if="calculateTotalPrice > offerPoints"
                                            class="p-2 text-sm text-red-600 bg-red-50 rounded-md">
                                            <div class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                                Your total exceeds available offer points by {{ formatCurrency(calculateTotalPrice - offerPoints) }}
                                            </div>
                                        </div>
                                        <div v-else-if="calculateTotalPrice > 0"
                                            class="p-2 text-sm text-green-600 bg-green-50 rounded-md">
                                            <div class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Booking is within your offer points limit
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button v-if="isOffer===false|| null"  @click="reserveRoom"
                                class="flex items-center justify-center px-6 py-3 text-white transition-all duration-300 transform bg-orange-500 rounded-lg shadow-md hover:bg-orange-600 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-orange-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Reserve Now
                            </button>
                            <button v-if="isOffer && calculateTotalPrice > 0 && calculateTotalPrice <= offerPoints"
                                @click="reserveRoom"
                                class="flex items-center justify-center px-6 py-3 text-white transition-all duration-300 transform bg-orange-500 rounded-lg shadow-md hover:bg-orange-600 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-orange-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Reserve Now
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <div>
                <ImageGallery
                :images="hotels.images"
                />
            </div>

        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, watch, watchEffect, onMounted, onUnmounted } from 'vue';
import { UserIcon } from '@heroicons/vue/24/solid';
import AuthenticatedLayout from '@/Layouts/User/AuthenticatedLayout.vue';
import { useForm, router } from '@inertiajs/vue3';
import SearchAvailableRoom from '@/Components/SearchAvailableRoom.vue';
import AOS from 'aos';
import 'aos/dist/aos.css';
import RoomTable from '@/Components/RoomTable.vue';
import ViewHotelHeadSection from '@/Components/ViewHotelHeadSection.vue';
import ImageGallery from '@/Components/ImageGallery.vue';
import axios from 'axios';
import Swal from 'sweetalert2';

AOS.init({
    duration: 800,
    easing: 'ease-in-out',
    once: true
});

const currentIndex = ref(0);

// check if the hotel has channel manager
const hasChannelManager = ref(true);

const adult_count = ref([]);
const child_count = ref([]);
const room_count = ref([]);
let day_count = ref([]);

let room_id = ref([]);
let price = ref([]);

const roomAvailability = ref(new Map());

const props = defineProps({
    hotels: Object,
    districts: Object,
    favorites: Object,
    users: Object,
    isOffer: Boolean,
    offerPoints :Number
});

console.log(props.isOffer);
console.log(props.offerPoints);

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


const getCurrentDate = () => {
    return new Date().toISOString().split('T')[0];
};

const getMinCheckoutDate = () => {
    if (!form.check_in_date) return getCurrentDate();
    const nextDay = new Date(form.check_in_date);
    nextDay.setDate(nextDay.getDate() + 1);
    return nextDay.toISOString().split('T')[0];
};

const dayCount = computed(() => {
    if (form.check_in_date && form.check_out_date) {
        const start = new Date(form.check_in_date);
        const end = new Date(form.check_out_date);
        const diffTime = Math.abs(end - start);
        return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    }
    return null;
});

const calculateDayCount = () => {
    if (form.check_in_date && form.check_out_date) {
        const checkIn = new Date(form.check_in_date);
        const checkOut = new Date(form.check_out_date);
        const timeDiff = checkOut - checkIn;
        const days = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));

        if (days > 0) {
            day_count.value = props.hotels.rooms.map(() => days);
            validateDateSelection();
        } else {
            day_count.value = props.hotels.rooms.map(() => 0);
            alert('Check-out date must be after check-in date');
            form.check_out_date = null;
        }
    }
};

const validateDateSelection = () => {
    if (!form.check_in_date || !form.check_out_date) {
        alert('Please select both check-in and check-out dates');
        return false;
    }
    return true;
};

const scrollToRoomAvailableSection = () => {
    this.$refs.availableRoomSection.scrollIntoView({ behavior: "smooth" });
};

const rooms = ref([]);

// Initialize when component mounts
onMounted(() => {
    // Reset all counts when component mounts or when coming back from reservation
    resetAllCounts();
    
    // Initialize room availability
    if (props.hotels.rooms) {
        props.hotels.rooms.forEach(room => {
            roomAvailability.value.set(room.id, room.bookable_rooms);
        });
    }
});

// Clean up when component unmounts
onUnmounted(() => {
    resetAllCounts();
});

// Fetch rooms and options dynamically
// Remove this onMounted block to avoid 404 error
// onMounted(async () => {
//     try {
//         const response = await axios.get('/api/rooms'); // Replace with your API endpoint
//         rooms.value = response.data.map(room => ({
//             ...room,
//             count: 0, // Initialize count for each room
//         }));
//         if (props.hotels.option_room) {
//             props.hotels.option_room.forEach(option => {
//                 option.selectedCount = 0; // Initialize selectedCount for each option
//             });
//         }
//     } catch (error) {
//         console.error('Error fetching rooms:', error);
//     }
//     resetAllCounts();
//
//     // Initialize room availability
//     props.hotels.rooms.forEach(room => {
//         roomAvailability.value.set(room.id, room.bookable_rooms);
//     });
// });


// Add new function to reset counts
const resetAllCounts = () => {
    // Reset room counts
    room_count.value = [];
    day_count.value = [];
    adult_count.value = [];
    child_count.value = [];
    room_id.value = [];
    price.value = [];
    
    // Reset form dates and values
    form.check_in_date = null;
    form.check_out_date = null;
    
    if (props.hotels && props.hotels.option_room) {
        props.hotels.option_room.forEach(option => {
            option.selectedCount = 0;
        });
    }
};

const getTotalSelectedCount = (room) => {
    if (!props.hotels.option_room) return 0;

    return props.hotels.option_room
        .filter(option => option.room_id === room.id)
        .reduce((total, option) => {
            return total + (option.selectedCount || 0);
        }, 0);
};

const haveRooms = computed(() => {
    return ['Apartment', 'Villa', 'Homestay', 'Chalet', 'Business Hotel', 'Extended Stay Hotel'].includes(props.hotels.accommodation_type);
});

// New functions for calculating total price
const calculateTotalPrice = computed(() => {
    if (!dayCount.value) return 0;

    let total = 0;

    if (props.hotels.option_room && props.hotels.option_room.length > 0) {
        props.hotels.option_room.forEach(option => {
            if (option.selectedCount && option.selectedCount > 0) {
                // Find the room associated with this option
                const room = props.hotels.rooms.find(r => r.id === option.room_id);
                
                let subtotal = option.price * option.selectedCount * dayCount.value;
                
                // Apply discount only if the room has a discount
                if (room && room.discount && room.discount > 0) {
                    const discountAmount = subtotal * (room.discount / 100);
                    subtotal = subtotal - discountAmount;
                }
                
                total += subtotal;
            }
        });
    }

    return total;
});

// Format currency
const formatCurrency = (value) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'LKR',
        minimumFractionDigits: 2
    }).format(value);
};

// Create a summary of selected rooms for display
const selectedRoomsSummary = computed(() => {
    if (!props.hotels.option_room) return [];

    const summary = [];

    props.hotels.rooms.forEach(room => {
        const options = props.hotels.option_room.filter(o => o.room_id === room.id && o.selectedCount > 0);

        options.forEach(option => {
            const nightText = dayCount.value === 1 ? 'night' : 'nights';
            let roomPrice = option.price * option.selectedCount * dayCount.value;
            
            // Apply discount if room has one
            if (room.discount && room.discount > 0) {
                const discountAmount = roomPrice * (room.discount / 100);
                roomPrice = roomPrice - discountAmount;
                summary.push(`${option.selectedCount} × ${room.type} (${option.adult_count} adult${option.adult_count > 1 ? 's' : ''}, ${option.child_count} child${option.child_count !== 1 ? 'ren' : ''}) - ${formatCurrency(roomPrice)} for ${dayCount.value} ${nightText} (${room.discount}% discount applied)`);
            } else {
                summary.push(`${option.selectedCount} × ${room.type} (${option.adult_count} adult${option.adult_count > 1 ? 's' : ''}, ${option.child_count} child${option.child_count !== 1 ? 'ren' : ''}) - ${formatCurrency(roomPrice)} for ${dayCount.value} ${nightText}`);
            }
        });
    });

    return summary;
});

const reserveRoom = () => {
    if (!validateDateSelection()) return;

    let roomData = [];
    let totalAmount = 0;

    for (const room of props.hotels.rooms) {
        const roomOptions = props.hotels.option_room.filter(option => option.room_id === room.id && option.selectedCount > 0);

        for (const option of roomOptions) {
            // Calculate subtotal for this option
            let roomTotal = option.price * option.selectedCount * dayCount.value;

            // Apply room-level discount if present
            if (room && room.discount && room.discount > 0) {
                const discountAmount = roomTotal * (room.discount / 100);
                roomTotal = roomTotal - discountAmount;
            }

            totalAmount += roomTotal;

            roomData.push({
                room_id: room.id,
                room_count: option.selectedCount,
                adult_count: option.adult_count,
                child_count: option.child_count,
                day_count: dayCount.value,
                // store the discounted price for this selection
                price: roomTotal
            });
        }
    }

    if (roomData.length === 0) {
        Swal.fire({
            title: 'Error!',
            text: 'Please select at least one room option',
            icon: 'error',
            confirmButtonText: 'OK',
            confirmButtonColor: '#f97316'
        });
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
        // use discounted total as payable amount
        total_price: totalAmount,
        status: 'pending',
        special_requests: '',
        room_id: roomData.map(r => r.room_id),
        room_count: roomData.map(r => r.room_count),
        adult_count: roomData.map(r => r.adult_count),
        child_count: roomData.map(r => r.child_count),
        day_count: roomData.map(r => r.day_count),
        // prices array now contains discounted per-selection totals
        price: roomData.map(r => r.price),
        offer: offer
    };

    Object.assign(form, formData);

    console.log('Submitting form data:', form.data());

    form.post(route('user.reservations.store'), {
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire({
                title: 'Success!',
                text: 'Reservation created successfully!',
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#f97316'
            }).then((result) => {
                if (result.isConfirmed) {
                    resetAllCounts();
                    router.visit(route('user.reservations.index'));
                }
            });
        },
        onError: (errors) => {
            console.error('Reservation failed:', errors);
            Swal.fire({
                title: 'Error!',
                text: 'Failed to create reservation: ' + (errors.message || 'Unknown error'),
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#f97316'
            });
        }
    });
};

const getRoomOptionAvailability = (room, option) => {
    const remaining = getRemainingRooms(room);
    return Array.from({ length: remaining + (option.selectedCount || 0) + 1 }, (_, i) => i);
};

const getRemainingRooms = (room) => {
    const totalBooked = props.hotels.option_room
        .filter(o => o.room_id === room.id)
        .reduce((sum, option) => sum + (option.selectedCount || 0), 0);
    return room.bookable_rooms - totalBooked;
};

const updateAvailableRooms = (option, room) => {
    if (!form.check_in_date || !form.check_out_date) {
        Swal.fire({
            title: 'Date Required',
            text: 'Please select check-in and check-out dates before choosing rooms',
            icon: 'warning',
            confirmButtonText: 'OK',
            confirmButtonColor: '#f97316'
        });
        option.selectedCount = 0;
        return;
    }

    const totalSelected = props.hotels.option_room
        .filter(o => o.room_id === room.id)
        .reduce((sum, o) => sum + (o.selectedCount || 0), 0);

    if (totalSelected > room.bookable_rooms) {
        const excess = totalSelected - room.bookable_rooms;
        option.selectedCount = Math.max(0, option.selectedCount - excess);
        alert(`Cannot select more than ${room.bookable_rooms} rooms in total for this room type`);
    }

    // Update the room availability in our tracking Map
    roomAvailability.value.set(room.id, room.bookable_rooms - totalSelected);
};

</script>

<style scoped>
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }

    50% {
        transform: scale(1.05);
    }

    100% {
        transform: scale(1);
    }
}

.fade-in {
    animation: fadeIn 0.5s ease-out forwards;
}

/* Custom styling for date inputs */
input[type="date"] {
    appearance: none;
    -webkit-appearance: none;
    padding-right: 30px;
    /* Space for custom dropdown icon */
}

input[type="date"]::-webkit-calendar-picker-indicator {
    opacity: 0;
    position: absolute;
    width: 100%;
    height: 100%;
    cursor: pointer;
}

/* Improve Select dropdown styling */
select {
    appearance: none;
    -webkit-appearance: none;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23f97316' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 0.5rem center;
    background-size: 1em;
    padding-right: 2rem;
}

/* Feature tags animation */
.inline-flex {
    transition: all 0.2s ease;
}

.inline-flex:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgba(249, 115, 22, 0.1), 0 2px 4px -1px rgba(249, 115, 22, 0.06);
}

/* Animation for Book Now button */
.bg-orange-500.hover\:bg-orange-600 {
    transition: all 0.3s ease;
}

.bg-orange-500.hover\:bg-orange-600:hover {
    box-shadow: 0 10px 15px -3px rgba(249, 115, 22, 0.2), 0 4px 6px -2px rgba(249, 115, 22, 0.1);
}
</style>
