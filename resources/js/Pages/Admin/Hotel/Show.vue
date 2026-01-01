<template>
    <AdminAuthenticatedLayout>
    <div class="">
        <div>
            <ViewHotelHeadSection
                :users="users"
                :hotels="hotels"
                :favorites="favorites"/>
        </div>

            <div class="py-12 max-w-8xl mx-auto">
                <div class="h-10 p-3 mb-5 ml-14 rounded-lg">
                    <h2 class="text-2xl text-orange-500 font-['Black_Ops_One']" data-aos="fade-right" data-aos-duration="3000">Hotel Room</h2>
                </div>
                <div class="mx-auto ml-10 max-w-8xl sm:px-6 lg:px-8">
                    <template v-if="hotels.rooms.length > 0">
                        <div class="overflow-x-auto bg-white shadow-lg sm:rounded-lg border border-gray-200">
                            <table class="min-w-full border-collapse table-auto">
                                <thead>
                                <tr class="bg-gradient-to-r from-[#FF9B50] to-[#FF8C42] text-white">
                                    <th class="px-6 py-3 text-left font-bold text-sm border-b-2 border-orange-600">Room Type</th>
                                    <th class="px-6 py-3 text-left font-bold text-sm border-b-2 border-orange-600">Room Count</th>
                                    
                                    <th class="px-6 py-3 text-left font-bold text-sm border-b-2 border-orange-600">Numbers Of Guests</th>
                                    <th class="px-6 py-3 text-left font-bold text-sm border-b-2 border-orange-600">Price</th>
                                    <th class="px-6 py-3 text-left font-bold text-sm border-b-2 border-orange-600">Discount</th>
                                    <th class="px-6 py-3 text-left font-bold text-sm border-b-2 border-orange-600">Features</th>
                                    <th class="px-6 py-3 text-left font-bold text-sm border-b-2 border-orange-600">Free Services</th>
                                    <th class="px-6 py-3 text-left font-bold text-sm border-b-2 border-orange-600">Description</th>
                                </tr>
                                </thead>
                                <tbody>
                                <template v-for="room in hotels.rooms" :key="room.id">
                                    <tr class="transition-colors duration-200 hover:bg-orange-50 border-b border-gray-200">
                                        <td class="px-6 py-4 text-gray-900 font-medium text-sm">{{ room.type }}</td>
                                        <td class="px-6 py-4 text-gray-800 text-sm text-center">{{ room.bookable_rooms }}</td>
                                
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
                                        <td class="px-6 py-4 text-gray-800 text-sm text-center">
                                            <div v-if="room.option_room && room.option_room.length" class="space-y-2">
                                                <div v-for="option in room.option_room" :key="option.id" class="font-medium text-gray-900">
                                                    LKR {{ option.price }}
                                                </div>
                                            </div>
                                            <div v-else class="font-medium text-gray-900">
                                                LKR {{ hotels.price_per_night }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-gray-700 text-sm text-center">{{ room.discount || 'Not added' }}</td>
                                        <td class="px-6 py-4 text-gray-700 text-sm">
                                            <div v-if="room.features" class="flex flex-wrap gap-2">
                                                <div v-for="feature in room.features.split(',')" :key="feature" class="flex items-center gap-1 px-2 py-1 text-sm text-orange-700 bg-orange-100 rounded-full">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <span>{{ feature.trim() }}</span>
                                                </div>
                                            </div>
                                            <span v-else class="text-gray-500">Not added</span>
                                        </td>
                                        <td class="px-6 py-4 text-gray-700 text-sm">
                                            <div v-if="room.free_services" class="flex flex-wrap gap-2">
                                                <div v-for="service in room.free_services.split(',')" :key="service" class="flex items-center gap-1 px-2 py-1 text-sm text-green-700 bg-green-100 rounded-full">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span>{{ service.trim() }}</span>
                                                </div>
                                            </div>
                                            <span v-else class="text-gray-500">Not added</span>
                                        </td>
                                        <td class="px-6 py-4 text-gray-700 text-sm max-w-xs">{{ room.description || 'No description available' }}</td>
                                    </tr>
                                </template>
                                </tbody>
                            </table>
                        </div>

                    </template>

                    <!-- No Rooms Available -->
                    <template v-else>
                    <!-- Empty State -->
                                <div class="p-12 text-center">
                                    <div class="flex items-center justify-center w-24 h-24 mx-auto mb-4 bg-gray-100 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                        <h3 class="mb-2 text-lg font-semibold text-gray-800">No Available Rooms</h3>
                                        <p class="text-gray-600">There are currently no available rooms to review.</p>
                                </div>     
                    </template>
                </div>
            </div>
<ImageGallery
    :images="hotels.images"
    />

            
    </div>
</AdminAuthenticatedLayout>
</template>

<script setup>
import {ref, computed, watch,watchEffect} from 'vue';
import AdminAuthenticatedLayout from '@/Layouts/Admin/AdminAuthenticatedLayout.vue';
import { useForm, router } from '@inertiajs/vue3';
import ViewHotelHeadSection from '@/Components/ViewHotelHeadSection.vue';
import ImageGallery from '@/Components/ImageGallery.vue';
import AOS from 'aos';
import 'aos/dist/aos.css';

AOS.init();

const currentIndex = ref(0);

const adult_count = ref([]);
const child_count = ref([]);
const room_count = ref([]);
let day_count = ref([]);

let room_id = ref([]);
let price = ref([]);


const props = defineProps({
    users: Object,
    hotels: Object,
    districts: Object,
    favorites: Object,
});

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
            alert("Reservations saved successfully!");
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

const mapLink = computed(() => {
    return `https://www.google.com/maps?q=${props.hotels.latitude},${props.hotels.longitude}&hl=en&z=14&output=embed`;
});


</script>