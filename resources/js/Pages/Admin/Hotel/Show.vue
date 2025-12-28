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
                        <div class="overflow-x-auto bg-white shadow-sm sm:rounded-lg">
                            <table class="min-w-full border-separate border-black table-auto dark:bg-gray-800 dark:text-white">
                                <thead>
                                <tr class="bg-[#FF9B50] text-white dark:bg-gray-700">
                                    <th class="px-4 py-2 text-left">Room Type</th>
                                    <th class="px-4 py-2 text-left">Bookable Rooms</th>
                                    <th class="px-4 py-2 text-left">Booked Rooms</th>
                                    <th class="px-4 py-2 text-left">Available Rooms</th>
                                    <th class="px-4 py-2 text-left">Numbers Of Geste</th>
                                    <th class="px-4 py-2 text-left">Price</th>
                                    <th class="px-4 py-2 text-left">Discount</th>
                                    <th class="px-4 py-2 text-left">Features</th>
                                    <th class="px-4 py-2 text-left">Free Services</th>
                                    <th class="px-4 py-2 text-left">Description</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="room in hotels.rooms" :key="room.id">
                                    <td class="px-4 py-2">{{ room.type }}</td>
                                    <td class="px-4 py-2">{{ room.bookable_rooms }}</td>
                                    <td class="px-4 py-2">{{ room.booked_rooms }}</td>
                                    <td class="px-4 py-2">{{ room.available_rooms }}</td>
                                    <td class="px-4 py-2">
                                        <div>
                                            <p class="text-left">Max adult: {{ room.max_adult }}</p>
                                            <p class="text-left">Max child: {{  room.max_children? room.max_children : "no added children"}}</p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2">
                                        <div>
                                            <p class="text-left">adult Price: {{ room.price_per_adult }}</p>
                                            <p class="text-left">child Price: {{ room.price_per_child }}</p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2">{{ room.discount }}</td>
                                    <td class="px-4 py-2">{{ room.features }}</td>
                                    <td class="px-4 py-2">{{ room.free_services }}</td>
                                    <td class="px-4 py-2">
                                        <span v-if="room.description === null">No description available</span>
                                        <span v-else>{{ room.description }}</span>
                                    </td>
                                </tr>
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