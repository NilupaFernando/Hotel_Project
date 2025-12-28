<script setup>
// Your existing imports
import { ref, computed, onMounted, onUnmounted } from 'vue';
import Swal from 'sweetalert2';
import { router, usePage } from '@inertiajs/vue3'
const props = defineProps({
  reservations: {
    type: Array,
    required: true,
  },
  reservationRooms: {
    type: Object,
    default: () => ({})
  },
  bookings:{
    type:Array
  }
});

console.log(props.reservations);


let timer = null;
const remainingTimes = ref({});


let refreshInterval = null;



onUnmounted(() => {
  if (timer) clearInterval(timer);
  if (refreshInterval) clearInterval(refreshInterval);
});


onMounted(() => {
  // reset details
  showDetails.value = {};

  // 🔄 Auto sync from backend (important)
  refreshInterval = setInterval(() => {
    console.log('Auto syncing user reservations...');
    router.reload({
      preserveScroll: true,
      preserveState: true,
    });
  }, 5000);
  // ⏳ Countdown logic
  const updateRemaining = () => {
    const now = new Date();
    const map = {};
    if (props.reservations && props.reservations.length) {
      props.reservations.forEach(reservation => {
        if (reservation.expire_at && reservation.status === 'pending') {
          // robust parsing for different timestamp formats
          // prefer ISO timestamp if provided by backend
          const raw = reservation.expire_at_iso || reservation.expire_at;
          const parseExpire = (s) => {
            if (!s) return null;
            // direct ISO or RFC strings should parse correctly
            let d = new Date(s);
            if (!isNaN(d.getTime())) return d;
            // try replacing space with T
            d = new Date(String(s).replace(' ', 'T'));
            if (!isNaN(d.getTime())) return d;
            // try treating as UTC
            d = new Date(String(s) + 'Z');
            if (!isNaN(d.getTime())) return d;
            return null;
          };

          const exp = parseExpire(raw);
          if (!exp) {
            map[reservation.id] = null;
          } else {
            const diff = Math.floor((exp.getTime() - now.getTime()) / 1000);
           if (!isFinite(diff) || diff <= 0) {
  map[reservation.id] = '00:00:00';


} else {
              const hrs = Math.floor(diff / 3600);
              const mins = Math.floor((diff % 3600) / 60);
              const secs = diff % 60;
              map[reservation.id] = String(hrs).padStart(2, '0') + ':' + String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
            }
          }
        } else {
          map[reservation.id] = null;
        }
      });
    }
    remainingTimes.value = map;
  };

  updateRemaining();
  timer = setInterval(updateRemaining, 1000);
});

const previousReservations = computed(() => {
  const now = new Date();
  return props.reservations.filter(request => {
    if (request.is_completed) return true;
    return request.status === 'confirmed' && new Date(request.check_out_date) <= now;
  });
});

const rejectedReservations = computed(() => {
  if (props.reservations) {
    return props.reservations.filter(reservation => reservation.status === 'cancelled');
  } else {
    return [];
  }
});


const pendingReservations = computed(() => {
  if (props.reservations) {
    return props.reservations.filter(reservation => reservation.status === 'pending');
  } else {
    return [];
  }
});

// State for toggling details visibility
const showDetails = ref({});

// Toggle function that affects only the clicked reservation
const toggleDetails = (id) => {
  showDetails.value[id] = !showDetails.value[id];
};




const upCommingReservation = () => {
  const currendate = new Date().toISOString().split('T')[0];
  return props.reservations
    ? props.reservations.filter(
        reservation =>
          reservation.status === 'confirmed' &&
          !reservation.is_completed &&
          reservation.check_out_date > currendate
      )
    : [];
};
const cancelReservation = (id) => {
  Swal.fire({
    title: "Cancel Reservation?",
    text: `Are you sure you want to cancel this reservation?`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Yes, Cancel",
    cancelButtonText: "No, Keep It",
    confirmButtonColor: "#ED9B40",
    iconColor: "#ED9B40",
    cancelButtonColor: "#3085d6",
  }).then((result) => {
    if (result.isConfirmed) {
      router.post(route('user.reservations.cancel', id), {
        onSuccess: () => {
          Swal.fire({
            title: "Cancelled!",
            text: `Your reservation has been canceled.`,
            icon: "success",
            confirmButtonText: "OK",
            confirmButtonColor: "#3085d6",
          });
        },
        onError: (errors) => {
          Swal.fire({
            title: "Error!",
            text: errors,
            icon: "error",
            confirmButtonText: "OK",
            confirmButtonColor: "#3085d6",
          });
        }
      });
    }
  });
};

// Add these to handle the payment modal
const showPaymentModal = ref(false);
const selectedReservation = ref(null);

const viewPayment = (reservationId) => {
    selectedReservation.value = reservationId;
    showPaymentModal.value = true;
};

const closePaymentModal = () => {
    showPaymentModal.value = false;
    selectedReservation.value = null;
};

const roomIndex = (id) => {
    router.get(route('view-hotel.show', { id: id }));
};

const seeAllHotels = () => {
  router.get(route('user.hotel.index'));
};
const hasChannelManager = ref(true);
const hasPreviouseReservations = ref(true);

// Add new ref for active tab
const activeTab = ref('pending');

// Add method to change active tab
const setActiveTab = (tab) => {
  activeTab.value = tab;
};

// Add these computed properties
const pendingCount = computed(() => pendingReservations.value.length);
const upcomingCount = computed(() => upCommingReservation().length);
const previousCount = computed(() => previousReservations.value.length);
const rejectedCount = computed(() => rejectedReservations.value.length);

</script>

<template>
  <div class="min-h-screen px-4 py-10 text-gray-900 bg-gradient-to-br from-orange-50 via-white to-orange-50 sm:px-6">
    <div class="max-w-6xl mx-auto">
      <!-- Dashboard Header -->
      <div class="mb-6 text-center">
        <h1 class="mt-10 mb-12 text-xl font-bold text-transparent md:text-3xl bg-clip-text bg-gradient-to-r from-gray-700 to-gray-700">
            Your Reservations Details
        </h1>
        <!-- Tab Buttons  -->
        <div class="flex flex-wrap justify-center gap-4 mb-10">
          <button
            @click="setActiveTab('pending')"
            :class="{
              'bg-orange-500 text-white': activeTab === 'pending',
              'bg-white text-orange-500 hover:bg-orange-50': activeTab !== 'pending'
            }"
            class="relative flex items-center gap-2 px-6 py-3 font-semibold transition-all duration-300 rounded-full shadow-sm hover:shadow-md"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
            </svg>
            Pending Reservations
            <span v-if="pendingCount > 0"
              class="absolute flex items-center justify-center w-5 h-5 text-xs text-white bg-red-500 rounded-full -top-2 -right-2">
              {{ pendingCount }}
            </span>
          </button>

          <button
            @click="setActiveTab('upcoming')"
            :class="{
              'bg-orange-500 text-white': activeTab === 'upcoming',
              'bg-white text-orange-500 hover:bg-orange-50': activeTab !== 'upcoming'
            }"
            class="relative flex items-center gap-2 px-6 py-3 font-semibold transition-all duration-300 rounded-full shadow-sm hover:shadow-md"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
            </svg>
            Upcoming Reservations
            <span v-if="upcomingCount > 0"
              class="absolute flex items-center justify-center w-5 h-5 text-xs text-white bg-red-500 rounded-full -top-2 -right-2">
              {{ upcomingCount }}
            </span>
          </button>

          <button
            @click="setActiveTab('previous')"
            :class="{
              'bg-orange-500 text-white': activeTab === 'previous',
              'bg-white text-orange-500 hover:bg-orange-50': activeTab !== 'previous'
            }"
            class="relative flex items-center gap-2 px-6 py-3 font-semibold transition-all duration-300 rounded-full shadow-sm hover:shadow-md"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.707-10.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L9.414 11H13a1 1 0 100-2H9.414l1.293-1.293z" clip-rule="evenodd" />
            </svg>
            Previous Reservations
            <span v-if="previousCount > 0"
              class="absolute flex items-center justify-center w-5 h-5 text-xs text-white bg-red-500 rounded-full -top-2 -right-2">
              {{ previousCount }}
            </span>
          </button>

          <button
            @click="setActiveTab('rejected')"
            :class="{
              'bg-orange-500 text-white': activeTab === 'rejected',
              'bg-white text-orange-500 hover:bg-orange-50': activeTab !== 'rejected'
            }"
            class="relative flex items-center gap-2 px-6 py-3 font-semibold transition-all duration-300 rounded-full shadow-sm hover:shadow-md"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
            Rejected Reservations
            <span v-if="rejectedCount > 0"
              class="absolute flex items-center justify-center w-5 h-5 text-xs text-white bg-red-500 rounded-full -top-2 -right-2">
              {{ rejectedCount }}
            </span>
          </button>
        </div>
      </div>

      <!-- Conditional Rendering of Sections -->
      <div class="mb-10 fade-in">
        <!-- Pending Reservations -->
        <div v-show="activeTab === 'pending'" class="transition-all duration-300">
          <div class="pending-reservations-section">
            <div>
          <div class="flex items-center mb-6">
            <div class="flex items-center justify-center w-10 h-10 mr-3 bg-orange-100 rounded-full">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-orange-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
              </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Pending Reservations</h2>
          </div>

          <div
            v-if="pendingReservations.length > 0"
            v-for="reservation in pendingReservations"
            :key="reservation.id"
            class="bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:translate-y-[-5px] border border-orange-100/50 backdrop-blur-sm mb-5"
          >
            <div
              @click="toggleDetails(reservation.id)"
              class="flex items-center justify-between p-6 cursor-pointer bg-gradient-to-r from-orange-500/5 via-orange-500/10 to-white"
            >
              <div class="flex items-center">
                <div class="flex items-center justify-center w-12 h-12 mr-4 bg-orange-100 rounded-full">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                  </svg>
                </div>
                <div>
                  <div @click="roomIndex(reservation.hotel.id)" class="text-xl font-bold text-orange-900" >{{ reservation.hotel.name }}</div>
                    <div class="text-sm text-gray-500">Please wait while your reservation is being approved. Stay tuned for updates.</div>
                    <div v-if="remainingTimes[reservation.id] !== undefined && remainingTimes[reservation.id] !== null" class="text-sm font-semibold text-orange-600 mt-1">Time left: {{ remainingTimes[reservation.id] }}</div>
                </div>
              </div>
              <div
                class="text-orange-500 transition-transform duration-300"
                :class="{ 'rotate-180': showDetails[reservation.id] }"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </div>
            </div>

            <div
              v-if="showDetails[reservation.id]"
              class="p-6 transition-all duration-500 ease-in-out bg-white"
              :class="{
                'opacity-100 max-h-[500px]': showDetails[reservation.id],
                'opacity-0 max-h-0 overflow-hidden': !showDetails[reservation.id],
              }"
            >
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div>
                  <div class="font-medium text-orange-600">Check-in</div>
                  <div class="font-semibold text-gray-800">{{ reservation.check_in_date }}</div>
                </div>
                <div>
                  <div class="font-medium text-orange-600">Check-out</div>
                  <div class="font-semibold text-gray-800">{{ reservation.check_out_date }}</div>
                </div>
                <div>
                  <div class="font-medium text-orange-600">Payable Amount</div>
                  <div class="font-semibold text-gray-800">Rs: {{ reservation.total_price }}</div>
                </div>
              </div>

              <!-- Room Details -->
              <div v-if="reservationRooms[reservation.id] && reservationRooms[reservation.id].length > 0" class="mt-6 pt-6 border-t border-orange-100">
                <div class="font-bold text-gray-800 mb-4">Room Details</div>
                <div class="grid grid-cols-1 gap-3">
                  <div v-for="room in reservationRooms[reservation.id]" :key="room.id" class="flex items-center justify-between p-4 bg-gradient-to-r from-orange-50 to-white rounded-lg border border-orange-100">
                    <div>
                      <div class="font-medium text-gray-700">
                        <span class="text-orange-600 font-semibold">{{ room.room?.type || 'Room Type' }}</span>
                      </div>
                      <div class="text-sm text-gray-500">Room Count: <span class="font-semibold text-gray-700">{{ room.room_count }} {{ room.room_count > 1 ? 'rooms' : 'room' }}</span></div>
                    </div>
                    <div class="text-right">
                      <div class="text-xs text-gray-500 mb-1">Guests:</div>
                      <div class="text-sm text-gray-700">
                        <span class="font-semibold">{{ room.adult_count }}</span> Adult<span v-if="room.adult_count !== 1">s</span>
                        <span v-if="room.child_count > 0">, <span class="font-semibold">{{ room.child_count }}</span> Child<span v-if="room.child_count !== 1">ren</span></span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="flex justify-end mt-4">
                <button
                @click="cancelReservation(reservation.id)"
                    class="flex items-center gap-2 px-6 py-2 font-semibold text-orange-600 transition-all bg-white border border-orange-200 rounded-full hover:bg-orange-50 hover:border-orange-300 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-orange-300 group"
                  >
                    <span>Cancel Booking</span>
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="w-4 h-4 transition-transform group-hover:translate-x-1"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                      />
                    </svg>
                  </button>
              </div> 
            </div>
          </div>

          <!-- Empty State - Pending Reservations -->
          <div
            v-if="pendingReservations.length === 0"
            class="p-10 mb-10 text-center transition-all duration-300 bg-white border border-orange-100 shadow-lg rounded-2xl hover:shadow-xl empty-state"
          >
            <div class="inline-block p-4 mb-4 rounded-full shadow-inner bg-orange-50">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-16 h-16 text-orange-300"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
            </div>
            <p class="mb-2 text-xl font-medium text-gray-700">No pending reservations</p>
            <p class="mb-6 text-gray-500">Your upcoming bookings will appear here</p>
            <button
              @click="seeAllHotels"
              class="px-6 py-3 text-sm font-medium text-white transition-all duration-300 transform rounded-full shadow-md bg-gradient-to-r from-orange-400 to-orange-500 hover:from-orange-500 hover:to-orange-600 hover:shadow-lg hover:-translate-y-1"
            >
              Browse Hotels
            </button>
          </div>

        </div>
          </div>
        </div>

        <!-- Upcoming Reservations -->
        <div v-show="activeTab === 'upcoming'" class="transition-all duration-300">
          <div class="upcoming-reservations-section">
            <div class="mb-16 slide-in-bottom">
        <div class="flex items-center mb-6">
          <div class="flex items-center justify-center w-10 h-10 mr-3 bg-orange-100 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-orange-500" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
            </svg>
          </div>
          <h2 class="text-2xl font-bold text-gray-800">Upcoming Reservations</h2>
        </div>

        <div v-if="upCommingReservation().length > 0" class="fade-in" style="animation-delay: 0.2s;">
          <div class="grid grid-cols-1 gap-6">
            <div v-for="reservation in upCommingReservation()" :key="reservation.id"
                 class="bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:translate-y-[-5px] border border-orange-100 mb-5">
              <div class="p-6">
                <div class="flex items-center mb-4">
                  <div class="flex items-center justify-center w-12 h-12 mr-4 bg-orange-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                  <div>
                      <div v-if="reservation.hotel" @click="roomIndex(reservation.hotel.id)" class="text-xl font-bold text-gray-900">
                          {{ reservation.hotel.name }}
                      </div>
                    <div class="text-sm text-gray-600">
                      <span class="px-3 py-1 text-sm rounded-full"
                            :class="{
                              'bg-yellow-100 text-yellow-800': reservation.status === 'pending',
                              'bg-green-100 text-green-800': reservation.payment_status === 'paid'
                            }">
                        {{ reservation.status === 'pending' ? 'Pending Confirmation' : (reservation.payment_status === 'paid' ? 'Confirmed' : 'Unknown') }}
                      </span>
                    </div>
                  </div>
                </div>

                <div class="grid gap-4 p-4 mb-4 md:grid-cols-3 bg-gradient-to-r from-orange-50 to-white rounded-xl">
                  <div>
                    <div class="font-medium text-orange-600">Check-in</div>
                    <div class="font-semibold text-gray-800">{{ reservation.check_in_date }}</div>
                  </div>
                  <div>
                    <div class="font-medium text-orange-600">Check-out</div>
                    <div class="font-semibold text-gray-800">{{ reservation.check_out_date }}</div>
                  </div>
                  <div>
                    <div class="font-medium text-orange-600">Total Amount</div>
                    <div class="font-semibold text-gray-800">Rs: {{ reservation.total_price }}</div>
                  </div>
                </div>

                <!-- Room Details for Upcoming -->
                <div v-if="reservationRooms[reservation.id] && reservationRooms[reservation.id].length > 0" class="mb-4 pt-4 border-t border-orange-100">
                  <div class="font-bold text-gray-800 mb-3">Room Details</div>
                  <div class="grid grid-cols-1 gap-2">
                    <div v-for="room in reservationRooms[reservation.id]" :key="room.id" class="flex items-center justify-between p-3 bg-gradient-to-r from-orange-50 to-white rounded-lg border border-orange-100">
                      <div>
                        <div class="font-medium text-gray-700">
                          <span class="text-orange-600 font-semibold">{{ room.room?.type || 'Room Type' }}</span>
                        </div>
                        <div class="text-sm text-gray-500">Room Count: <span class="font-semibold text-gray-700">{{ room.room_count }}</span></div>
                      </div>
                      <div class="text-right text-sm">
                        <div class="font-semibold text-gray-700">{{ room.adult_count }} Adult<span v-if="room.adult_count !== 1">s</span><span v-if="room.child_count > 0">, {{ room.child_count }} Child<span v-if="room.child_count !== 1">ren</span></span></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="flex justify-end mt-4">
                <button
                @click="cancelReservation(reservation.id)"
                    class="flex items-center gap-2 px-6 py-2 font-semibold text-orange-600 transition-all bg-white border border-orange-200 rounded-full hover:bg-orange-50 hover:border-orange-300 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-orange-300 group"
                  >
                    <span>Cancel Booking</span>
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="w-4 h-4 transition-transform group-hover:translate-x-1"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                      />
                    </svg>
                  </button>
              </div> 
              </div>
            </div>
          </div>
        </div>

        <!-- Empty State - Upcoming Reservations -->
        <div
          v-if="upCommingReservation().length === 0"
          class="p-10 text-center transition-all duration-300 bg-white border border-orange-100 shadow-lg rounded-2xl hover:shadow-xl empty-state"
        >
          <div class="inline-block p-4 mb-4 rounded-full shadow-inner bg-orange-50">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="w-16 h-16 text-orange-300"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
          </div>
          <p class="mb-2 text-xl font-medium text-gray-700">No upcoming reservations</p>
          <p class="mb-6 text-gray-500">Your confirmed bookings will appear here</p>
        </div>
      </div>
          </div>
        </div>

        <!-- Previous Reservations -->
        <div v-show="activeTab === 'previous'" class="transition-all duration-300">
          <div class="previous-reservations-section">
            <div class="fade-in" style="animation-delay: 0.3s;">
        <div class="flex items-center mb-6">
          <div class="flex items-center justify-center w-10 h-10 mr-3 bg-orange-100 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-orange-500" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd" />
            </svg>
          </div>
          <h2 class="text-2xl font-bold text-gray-800">Previous Reservations</h2>
        </div>

        <div class="overflow-x-auto">
          <table v-if="previousReservations.length > 0" class="w-full overflow-hidden bg-white border-separate shadow-lg rounded-2xl border-spacing-0">
            <thead>
              <tr class="text-white bg-gradient-to-r from-orange-500 to-orange-400">
                <th class="px-6 py-4 font-medium text-left rounded-tl-xl">Hotel Name</th>
                <th class="px-6 py-4 font-medium text-left">Check-In Date</th>
                <th class="px-6 py-4 font-medium text-left">Check-Out Date</th>
                <th class="px-6 py-4 font-medium text-left rounded-tr-xl">Earned Coins</th>
              </tr>
            </thead>
            <tbody>
              <template v-for="(reservation, index) in previousReservations" :key="index">
                <tr class="transition-colors duration-200 hover:bg-orange-50/70 cursor-pointer" @click="toggleDetails(`prev-${reservation.id}`)">
                  <td class="px-6 py-4 border-b border-orange-100">
                    {{ reservation.hotel ? reservation.hotel.name : (reservation.reservation?.hotel?.name || 'Unknown Hotel') }}
                  </td>
                  <td class="px-6 py-4 border-b border-orange-100">
                    {{ reservation.check_in_date || reservation.reservation?.check_in_date || 'N/A' }}
                  </td>
                  <td class="px-6 py-4 border-b border-orange-100">
                    {{ reservation.check_out_date || reservation.reservation?.check_out_date || 'N/A' }}
                  </td>
                  <td class="px-6 py-4 border-b border-orange-100">
                    <div class="flex items-center justify-between">
                      <span class="font-semibold text-orange-500">Rs:{{ reservation.total_price || reservation.reservation?.total_price || '0' }}</span>
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-orange-500 transition-transform" :class="{ 'rotate-180': showDetails[`prev-${reservation.id}`] }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                      </svg>
                    </div>
                  </td>
                </tr>
                <!-- Room Details Row for Previous -->
                <tr v-if="showDetails[`prev-${reservation.id}`] && reservationRooms[reservation.id]" class="bg-orange-50/50 border-b border-orange-100">
                  <td colspan="4" class="px-6 py-4">
                    <div v-if="reservationRooms[reservation.id].length > 0" class="ml-4">
                      <div class="font-bold text-gray-800 mb-3">Room Details</div>
                      <div class="grid grid-cols-1 gap-2 md:grid-cols-2">
                        <div v-for="room in reservationRooms[reservation.id]" :key="room.id" class="flex items-center justify-between p-3 bg-white rounded-lg border border-orange-100">
                          <div>
                            <div class="font-medium text-gray-700">
                              <span class="text-orange-600 font-semibold">{{ room.room?.type || 'Room Type' }}</span>
                            </div>
                            <div class="text-sm text-gray-500">Room Count: <span class="font-semibold text-gray-700">{{ room.room_count }}</span></div>
                          </div>
                          <div class="text-right text-sm">
                            <div class="font-semibold text-gray-700">{{ room.adult_count }} Adult<span v-if="room.adult_count !== 1">s</span><span v-if="room.child_count > 0">, {{ room.child_count }} Child<span v-if="room.child_count !== 1">ren</span></span></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
              </template>
            </tbody>
          </table>

          <!-- Empty State - Previous Reservations -->
          <div v-else class="p-10 text-center transition-all duration-300 bg-white border border-orange-100 shadow-lg rounded-2xl hover:shadow-xl empty-state">
            <div class="inline-block p-4 mb-4 rounded-full shadow-inner bg-orange-50">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-orange-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <p class="mb-2 text-xl font-medium text-gray-700">No previous reservations</p>
            <p class="mb-6 text-gray-500">Your past bookings will appear here</p>
          </div>
        </div>
      </div>
          </div>
        </div>

        <!-- Rejected Reservations -->
        <div v-show="activeTab === 'rejected'" class="transition-all duration-300">
          <div class="rejected-reservations-section">
            <div class="fade-in" style="animation-delay: 0.4s;">
        <div class="flex items-center mb-6">
          <div class="flex items-center justify-center w-10 h-10 mr-3 bg-red-100 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </div>
          <h2 class="text-2xl font-bold text-gray-800">Rejected Reservations</h2>
        </div>

        <div class="overflow-x-auto">
          <table v-if="rejectedReservations.length > 0" class="w-full overflow-hidden bg-white border-separate shadow-lg rounded-2xl border-spacing-0">
            <thead>
              <tr class="text-white bg-gradient-to-r from-red-500 to-red-400">
                <th class="px-6 py-4 font-medium text-left rounded-tl-xl">Hotel Name</th>
                <th class="px-6 py-4 font-medium text-left">Check-In Date</th>
                <th class="px-6 py-4 font-medium text-left">Check-Out Date</th>
                <th class="px-6 py-4 font-medium text-left">Total Price</th>
                <th class="px-6 py-4 font-medium text-left rounded-tr-xl">Rejection Reason</th>
              </tr>
            </thead>
            <tbody>
              <template v-for="(reservation, index) in rejectedReservations" :key="index">
                <tr class="transition-colors duration-200 hover:bg-red-50/70 cursor-pointer border-b border-red-100" @click="toggleDetails(`reject-${reservation.id}`)">
                  <td class="px-6 py-4">
                    {{ reservation.hotel ? reservation.hotel.name : 'Unknown Hotel' }}
                  </td>
                  <td class="px-6 py-4">
                    {{ reservation.check_in_date || 'N/A' }}
                  </td>
                  <td class="px-6 py-4">
                    {{ reservation.check_out_date || 'N/A' }}
                  </td>
                  <td class="px-6 py-4">
                    <span class="font-semibold text-red-500">Rs: {{ reservation.total_price || '0' }}</span>
                  </td>
                  <td class="px-6 py-4">
                    <div class="flex items-center justify-between">
                      <span class="px-3 py-1 text-sm text-white bg-red-500 rounded-full">
                        {{ reservation.rejection_reason || 'Rejected by hotel owner' }}
                      </span>
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-500 transition-transform" :class="{ 'rotate-180': showDetails[`reject-${reservation.id}`] }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                      </svg>
                    </div>
                  </td>
                </tr>
                <!-- Room Details Row for Rejected -->
                <tr v-if="showDetails[`reject-${reservation.id}`] && reservationRooms[reservation.id]" class="bg-red-50/50 border-b border-red-100">
                  <td colspan="5" class="px-6 py-4">
                    <div v-if="reservationRooms[reservation.id].length > 0" class="ml-4">
                      <div class="font-bold text-gray-800 mb-3">Room Details</div>
                      <div class="grid grid-cols-1 gap-2 md:grid-cols-2">
                        <div v-for="room in reservationRooms[reservation.id]" :key="room.id" class="flex items-center justify-between p-3 bg-white rounded-lg border border-red-100">
                          <div>
                            <div class="font-medium text-gray-700">
                              <span class="text-red-600 font-semibold">{{ room.room?.type || 'Room Type' }}</span>
                            </div>
                            <div class="text-sm text-gray-500">Room Count: <span class="font-semibold text-gray-700">{{ room.room_count }}</span></div>
                          </div>
                          <div class="text-right text-sm">
                            <div class="font-semibold text-gray-700">{{ room.adult_count }} Adult<span v-if="room.adult_count !== 1">s</span><span v-if="room.child_count > 0">, {{ room.child_count }} Child<span v-if="room.child_count !== 1">ren</span></span></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
              </template>
            </tbody>
          </table>

          <!-- Empty State - Rejected Reservations -->
          <div v-else class="p-10 text-center transition-all duration-300 bg-white border border-red-100 shadow-lg rounded-2xl hover:shadow-xl empty-state">
            <div class="inline-block p-4 mb-4 rounded-full shadow-inner bg-red-50">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-red-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2m4-4l2 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <p class="mb-2 text-xl font-medium text-gray-700">No rejected reservations</p>
            <p class="mb-6 text-gray-500">Your rejected bookings will appear here</p>
          </div>
        </div>
      </div>
          </div>
        </div>
      </div>
    </div>

    <PaymentModal
            v-if="selectedReservation"
            @close="selectedReservation = null"
            :paymentUrl="route('user.reservations.payment', { id: selectedReservation })"
        />

  </div>
</template>

<style scoped>
/* Custom animations */
@keyframes glow {
  0%, 100% { box-shadow: 0 0 5px rgba(255, 165, 0, 0.5); }
  50% { box-shadow: 0 0 20px rgba(255, 165, 0, 0.8); }
}

.border-3 {
  border-width: 3px;
}

.delay-100 {
  animation-delay: 0.1s;
}

.delay-200 {
  animation-delay: 0.2s;
}

.delay-300 {
  animation-delay: 0.3s;
}

/* Empty state styling */
.empty-state {
  background-color: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(251, 146, 60, 0.1);
  box-shadow: 0 10px 25px -5px rgba(251, 146, 60, 0.1);
  transition: all 0.3s ease;
}

.empty-state:hover {
  transform: translateY(-5px);
  box-shadow: 0 20px 25px -5px rgba(251, 146, 60, 0.2);
}

/* Card hover effect */
.card-hover {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card-hover:hover {
  transform: translateY(-5px);
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Float animation */
@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-10px); }
}

.float-animation {
  animation: float 3s ease-in-out infinite;
}

/* Shine effect */
@keyframes shine {
  0% { background-position: 200% center; }
  100% { background-position: -200% center; }
}

.shine-effect {
  position: relative;
  overflow: hidden;
}

.shine-effect::after {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 50%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
  animation: shine 2s linear infinite;
}

/* Slide in animation */
.slide-in-bottom {
  animation: slideInBottom 0.5s ease-out forwards;
}

@keyframes slideInBottom {
  from {
    transform: translateY(20px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

/* Fade in animation */
.fade-in {
  animation: fadeIn 0.5s ease-out forwards;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

/* Responsive adjustments */
@media (max-width: 640px) {
  .rounded-2xl {
    border-radius: 1rem;
  }

  .p-8 {
    padding: 1.5rem;
  }
}
</style>
