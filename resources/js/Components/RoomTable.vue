<!-- <script setup>
// import { ref } from 'vue';
// import { computed, watch,watchEffect} from 'vue';
// import { useForm, router } from '@inertiajs/vue3';

// interface RoomOption {
//   guests: number;
//   children: number;
//   price: number;
// }
// const props = defineProps({
//   RoomOption: Array,
//   hotels: Object,
// });

// console.log(props.hotels.rooms);
// interface Room {
//   type: string;
//   features: string[];
//   options: RoomOption[];
//   count: number;
// }

// const rooms = ref<Room[]>([
//   {
//     type: 'Single Room',
//     features: ['1 single bed', 'Towels', 'Fan', 'Clothes rak', 'TV'],
//     options: [
//       { guests: 1, children: 0, price: 2500 },
//       { guests: 1, children: 1, price: 3000 },
//     ],
//     count: 0
//   },
//   {
//     type: 'Double Room',
//     features: ['1 double bed', 'Towels', 'Fan', 'Clothes rak', 'TV'],
//     options: [
//       { guests: 2, children: 0, price: 3500 },
//       { guests: 2, children: 2, price: 4500 },
//     ],
//     count: 0
//   },
//   {
//     type: 'Twin Room',
//     features: ['2 single beds', 'Towels', 'Fan', 'Clothes rak', 'TV'],
//     options: [
//       { guests: 2, children: 0, price: 3500 },
//       { guests: 2, children: 1, price: 3000 },
//     ],
//     count: 0
//   }
// ]);


// const currentIndex = ref(0);

// const adult_count = ref([]);
// const child_count = ref([]);
// const room_count = ref([]);
// let day_count = ref([]);

// let room_id = ref([]);
// let price = ref([]);

// const totalPrice = computed(() => {
//     return props.hotels.rooms.reduce((total, room, index) => {
//         const rooms = room_count.value[index] || 0;
//         const days = day_count.value[index] || 0;
//         const child = child_count.value[index] || 0;
//         const adult = adult_count.value[index] || 0;

//         const adultPrice = room.price_per_adult * rooms * days * adult;
//         const childPrice = room.price_per_child * rooms * days * child;

//         price.value[index] = adultPrice + childPrice;

//         return total + adultPrice + childPrice;
//     }, 0);
// });

// watch(totalPrice, (newValue) => {
//     form.total_price = newValue;
// });

// watchEffect(() => {
//     room_id.value = props.hotels.rooms.map((room, index) => {
//         return room_count.value[index] && room_count.value[index] > 0 ? room.id : null;
//     });

//     adult_count.value = props.hotels.rooms.map((room, index) => {
//         return room_count.value[index] && room_count.value[index] > 0 ? adult_count.value[index] || 0 : null;
//     });

//     child_count.value = props.hotels.rooms.map((room, index) => {
//         return room_count.value[index] && room_count.value[index] > 0 ? child_count.value[index] || 0 : null;
//     });

// });

// const form = useForm({
//     hotel_id: props.hotels.id,
//     check_out_date: null,
//     check_in_date: null,
//     total_price: totalPrice.value,

//     room_id: room_id.value,
//     adult_count: adult_count.value,
//     child_count: child_count.value,
//     room_count: room_count.value,
//     day_count: day_count.value,
//     price: price.value
// });


// watchEffect(() => {
//     form.room_id = room_id.value;
//     form.adult_count = adult_count.value;
//     form.child_count = child_count.value;
//     form.room_count = room_count.value;
//     form.day_count = day_count.value;
//     form.room_id = room_id.value;
//     form.price = price.value;
// });

// const submitFormResevation = () => {
//     form.post(route('reservations.store'), {
//         onSuccess: () => {
//             alert("Hotel saved successfully!");;
//         },
//         onError: (errors) => {
//             console.log("Save errors:", errors);
//         },
//     });
// };

// const calculateDayCount = () => {
//     if (form.check_in_date && form.check_out_date) {
//         const checkIn = new Date(form.check_in_date);
//         const checkOut = new Date(form.check_out_date);
//         const timeDiff = checkOut - checkIn;
//         const days = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));

//         if (days > 0) {
//             day_count.value = props.hotels.rooms.map((_, index) => days);
//             console.log('Day count:', day_count.value);
//         } else {
//             day_count.value = props.hotels.rooms.map((_, index) => 0);
//             console.log('Day count (zero):', day_count.value);
//         }
//     }
// };
</script> -->

<!-- <template>
  <div class="ml-8 overflow-hidden bg-white rounded-lg shadow-lg max-w-8xl mr-14">
    <table class="w-full border-collapse">
      <thead>
        <tr class="bg-[#FF9B50] text-white">
          <th class="px-6 py-4 font-medium text-left">Room Type</th>
          <th class="px-6 py-4 font-medium text-center">Number of guests</th>
          <th class="px-6 py-4 font-medium text-center">Price for per day</th>
          <th class="px-6 py-4 font-medium text-center">Select amount</th>
          <th class="px-6 py-4"></th>
        </tr>
      </thead>
      <tbody>
        <template v-for="room in props.hotels.rooms" :key="room.type"> -->
          <!-- <template v-for="(option, optIndex) in room.options" :key="`${roomIndex}-${optIndex}`"> -->
            <!-- <tr class="border-b border-gray-200">
              <td>
                <h3 class="text-[#FF9B50] font-medium mb-2">{{ room.type }}</h3>
              </td>
              
              <td class="px-6 py-4" :rowspan="room.options.length" v-if="optIndex === 0">
                <div>
                 
                  <ul class="space-y-1">
                    <li v-for="feature in rooms.features" :key="feature" 
                        class="flex items-center text-sm text-gray-600">
                      <span class="text-[#FF9B50] mr-2">✓</span>
                      {{ feature }}
                    </li>
                  </ul> -->
                  <!-- {{ rooms.features }} -->
                <!-- </div>
              </td>
              
              <td class="px-6 py-4 text-center">
                <div class="flex justify-center space-x-1">
                  <template v-for="n in max_adult" :key="n">
                    <span class="text-xl">👤</span>
                  </template>
                  <template v-if="max_children > 0">
                    <span class="mx-1">+</span>
                    <template v-for="n in max_children" :key="`child-${n}`">
                      <span class="text-xl">👶</span>
                    </template>
                  </template> -->
                  <!-- <h1>{{ room.max_adult }}</h1> -->
                <!-- </div>
              </td>
              
              <td class="px-6 py-4 text-center">
                LKR {{ room.price }}
              </td>
              <td class="px-6 py-4 text-center">
                <select 
                  v-model="room.count"
                  class="border rounded px-8 py-1 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#FF9B50] focus:border-transparent"
                >
                  <option v-for="n in 5" :key="n" :value="n-1">{{ n-1 }}</option>
                </select>
              </td>
              <td class="px-6 py-4" :rowspan="room.options.length" v-if="optIndex === 0">
                <button class="bg-[#FF9B50] text-white px-6 py-2 rounded-md hover:bg-[#FF8B40] transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-[#FF9B50] focus:ring-opacity-50 text-center">
                  I'll reserve
                </button>
              </td>
            </tr>
          </template> -->
        <!-- </template> -->
      <!-- </tbody>
    </table>
  </div>
</template> -->

<!-- Tst code  -->
<script setup>
import { useForm, router } from '@inertiajs/vue3';
import {ref, computed, watch,watchEffect} from 'vue';

const currentIndex = ref(0);

const adult_count = ref([]);
const child_count = ref([]);
const room_count = ref([]);
let day_count = ref([]);

let room_id = ref([]);
let price = ref([]);

const props = defineProps({
    hotels: Object,
});
console.log(props.hotels.rooms);

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

// interface RoomOption {
//   guests: number;
//   children: number;
//   price: number;
// }

// interface Room {
//   type: string;
//   features: string[];
//   options: RoomOption[];
//   count: number;
// }

// const rooms = ref<Room[]>([
//   {
//     type: 'Single Room',
//     features: ['1 single bed', 'Towels', 'Fan', 'Clothes rak', 'TV'],
//     options: [
//       { guests: 1, children: 0, price: 2500 },
//       { guests: 1, children: 1, price: 3000 },
//     ],
//     count: 0
//   },
//   {
//     type: 'Double Room',
//     features: ['1 double bed', 'Towels', 'Fan', 'Clothes rak', 'TV'],
//     options: [
//       { guests: 2, children: 0, price: 3500 },
//       { guests: 2, children: 2, price: 4500 },
//     ],
//     count: 0
//   },
//   {
//     type: 'Twin Room',
//     features: ['2 single beds', 'Towels', 'Fan', 'Clothes rak', 'TV'],
//     options: [
//       { guests: 2, children: 0, price: 3500 },
//       { guests: 2, children: 1, price: 3000 },
//     ],
//     count: 0
//   }
// ]);



</script>

<template>
  <div class="max-w-5xl mx-auto overflow-hidden bg-white rounded-lg shadow-lg">
    <div class="flex justify-end gap-4 p-4">
      
                                <div>
                                    <label class="block font-bold">Check-in Date</label>
                                    <input
                                        type="date"
                                        v-model="form.check_in_date"
                                        class="p-2 border rounded-md"
                                    />

                                </div>

                                <div>
                                    <label class="block font-bold">Check-out Date</label>
                                    <input
                                        type="date"
                                        v-model="form.check_out_date"
                                        class="p-2 border rounded-md"
                                        @change="calculateDayCount"
                                    />
                                </div>
                            </div>
                            
    <table class="w-full border-collapse">
      <thead>
        <tr class="bg-[#FF9B50] text-white">
          <th class="px-6 py-4 font-medium text-left">Room Type</th>
          <th class="px-6 py-4 font-medium text-center">Number of guests</th>
          <th class="px-6 py-4 font-medium text-center">Price for per day</th>
          <th class="px-6 py-4 font-medium text-center">Select Count</th>
          <th class="px-6 py-4"></th>
        </tr>
      </thead>
      
      <tbody>
        <template v-for="(room, roomIndex) in props.hotels.rooms" :key="room.type">
          <!-- <template v-for="(option, optIndex) in room.options" :key="`${roomIndex}-${optIndex}`"> -->
            <tr class="border-b border-gray-200">
              <td class="px-6 py-4" :rowspan="room.max_adult">
                <div>
                  <h3 class="text-[#FF9B50] font-medium mb-2">{{ room.type }}</h3>
                  <!-- <span class="text-[#FF9B50] mr-2">✓</span> -->
                   <p>{{ room.features }}</p>
                  <!-- <ul class="space-y-1">
                    <li v-for="feature in room.features" :key="feature" 
                        class="flex items-center text-sm text-gray-600">
                      <span class="text-[#FF9B50] mr-2">✓</span>
                      {{ room.features }}
                    </li>
                  </ul> -->
                </div>
              </td>
              
              <td class="px-6 py-4 text-center">
                <div class="flex justify-center space-x-1">
                  <template v-for="n in room.max_adult" :key="n">
                    <span class="text-xl">👤</span>
                  </template>
                  <template v-if="room.max_children> 0">
                    <span class="mx-1">+</span>
                    <template v-for="n in room.max_children" :key="`child-${n}`">
                      <span class="text-xl">👶</span>
                    </template>
                  </template>
                </div>
              </td>
              
              <td class="px-6 py-4 text-center">
                LKR {{ room.price_per_adult}}
              </td>
              
              <td class="px-6 py-4 text-center">
                <select 
                  v-model="room.count"
                  class="border rounded px-6 py-1 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#FF9B50] focus:border-transparent"
                >
                  <option v-for="n in 5" :key="n" :value="n-1">{{ n-1 }}</option>
                </select>
              </td>
              
              <!-- <td class="px-6 py-4" :colspan="room.length">
                <button class="bg-[#FF9B50] text-white px-6 py-2 rounded-md hover:bg-[#FF8B40] transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-[#FF9B50] focus:ring-opacity-50">
                  I'll reserve
                </button>
              </td> -->
            </tr>
           
          <!-- </template> -->
        </template>
        <tr>
            <!-- <button class="bg-[#FF9B50] text-white px-6 py-2 rounded-md hover:bg-[#FF8B40] transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-[#FF9B50] focus:ring-opacity-50">
                  I'll reserve
                </button> -->
          <td colspan="6" class="px-4 py-2 font-bold text-right">Total Price:</td>
          <td  class="px-4 py-2 font-bold text-right text-green-600">
              <i class="fas fa-dollar-sign"></i> {{totalPrice}}
          </td>
          <td>
              <button
                @click="submitFormResevation"
                class="px-4 py-2 text-white bg-[#eca554] rounded-md hover:bg-[#ED9B40]">
                <i class="fas fa-check"></i> Submit
              </button>
            </td>
           </tr>
      </tbody>
    </table>
  </div>
</template>

