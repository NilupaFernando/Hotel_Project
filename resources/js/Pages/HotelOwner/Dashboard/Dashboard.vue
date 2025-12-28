<script setup>
import HotelOwnerAuthenticatedLayout from '@/Layouts/HotelOwner/HotelOwnerAuthenticatedLayout.vue';
import { computed, ref, onMounted, onUnmounted } from 'vue';
import { Link, router, usePage, useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

// const route = usePage().route;

const tabs = [
  { id: 'urgent', name: 'Urgent', description: 'Confirm within 5 hour' },
  { id: 'standard', name: 'Standard', description: 'Confirm within 48 hours' },
  { id: 'flexible', name: 'Flexible', description: 'Confirm within 72 hours' },
];

const props = defineProps({
  reservations: Array,
  reservationRooms: Object,
  hotel: Array,
  bookings: Array,
  bankDetails: Boolean,
});

console.log(props.bankDetails);

const approvedReservationsCount = computed(() => {
  return props.reservations.filter(reservation => reservation.status === 'confirmed').length;
});
const rejectedReservationsCount = computed(() => {
  return props.reservations.filter(reservation => reservation.status === 'cancelled').length;
});
const pendingReservationsCount = computed(() => {
  return props.reservations.filter(reservation => reservation.status === 'pending').length;
});

const activeTab = ref('urgent');
const currentView = ref('pending');

// Timer for updating remaining times
let timer = null;
const remainingTimes = ref({});
const expiredReservationIds = ref(new Set());

// 🔄 Auto refresh reservations from backend
let refreshInterval = null;

onMounted(() => {
  refreshInterval = setInterval(() => {
    console.log('Auto syncing reservations with backend...');
    router.reload({
      preserveScroll: true,
      preserveState: true,
    });
  }, 5000); // every 5 seconds
});

onUnmounted(() => {
  if (refreshInterval) clearInterval(refreshInterval);
});

const filteredReservations = computed(() => {
  const pendingReservations = props.reservations.filter(request => {
    return request.status === 'pending' && request.reservation_type === activeTab.value;
  });

  return pendingReservations.map(reservation => {
    const rooms = props.reservationRooms[reservation.id] || [];
    return {
      ...reservation,
      rooms: rooms
    };
  });
});

console.log('filteredReservations', filteredReservations.value);

const previousReservations = computed(() => {
  const now = new Date();
  return props.reservations.filter(request => {
    if (request.is_completed) return true;
    return request.status === 'confirmed' && new Date(request.check_out_date) <= now;
  });
});

const upCommingReservation = computed(() => {
  const today = new Date();
  today.setHours(0, 0, 0, 0); 
  return props.reservations.filter(reservation => {
    if (reservation.is_completed) return false;
    const checkOutDate = new Date(reservation.check_out_date);
    checkOutDate.setHours(0, 0, 0, 0);
    return reservation.status === 'confirmed' && checkOutDate >= today ;
  });
});


// Methods for handling actions
const confirmReservation = (request) => {
  Swal.fire({
    title: "Confirm Reservation?",
    text: `Are you sure you want to confirm the reservation for ${request.user.name}?`,
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "Yes, Confirm",
    cancelButtonText: "Cancel",
    confirmButtonColor: "#f97316",
    cancelButtonColor: "#d33",
  }).then((result) => {
    if (result.isConfirmed) {
      router.post(route('hotelOwner.reservations.update', { id: request.id }), { status: 'confirmed' })
        .then(() => {
          Swal.fire({
            title: "Confirmed!",
            text: `Reservation for ${request.user.name} has been confirmed.`,
            icon: "success",
            confirmButtonText: "OK",
            confirmButtonColor: "#f97316",
          });
        });
    }
  });
};

const rejectReservation = (request) => {
  Swal.fire({
    title: "Reject Reservation?",
    text: `Are you sure you want to reject the reservation?`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Yes, Reject",
    cancelButtonText: "Cancel",
    confirmButtonColor: "#ED9B40",
    iconColor: "#ED9B40",
    cancelButtonColor: "#f97316",
  }).then((result) => {
    if (result.isConfirmed) {
      router.post(route('hotelOwner.reservations.update', { id: request.id }), { status: 'cancelled' })
        .then(() => {
          Swal.fire({
            title: "Rejected!",
            text: `Reservation has been rejected.`,
            icon: "error",
            confirmButtonText: "OK",
            confirmButtonColor: "#d33",
          });
        });
    }
  });
};

const completeReservation = (request) => {
  Swal.fire({
    title: "Mark Completed?",
    text: `Mark reservation for ${request.user.name} as completed?`,
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "Yes, Complete",
    cancelButtonText: "Cancel",
    confirmButtonColor: "#10B981",
    cancelButtonColor: "#d33",
  }).then((result) => {
    if (result.isConfirmed) {
      router.post(route('hotelOwner.reservations.update', { id: request.id }), { is_completed: true })
        .then(() => {
          Swal.fire({
            title: "Completed",
            text: `Reservation for ${request.user.name} moved to Previous reservations.`,
            icon: "success",
            confirmButtonText: "OK",
            confirmButtonColor: "#10B981",
          });
        });
    }
  });
};

const groupRoomsByType = (rooms) => {
  return rooms.reduce((acc, room) => {
    const existingRoom = acc.find(r => r.room.type === room.room.type);
    if (existingRoom) {
      existingRoom.room_count += room.room_count;
      existingRoom.adult_count = (existingRoom.adult_count || 0) + (room.adult_count || 0);
      existingRoom.child_count = (existingRoom.child_count || 0) + (room.child_count || 0);
    } else {
      acc.push({ ...room });
    }
    return acc;
  }, []);
};

const urgentCount = computed(() => {
  return props.reservations.filter(request => 
    request.status === 'pending' && 
    request.reservation_type === 'urgent'
  ).length;
});

const standardCount = computed(() => {
  return props.reservations.filter(request => 
    request.status === 'pending' && 
    request.reservation_type === 'standard'
  ).length;
});

const flexibleCount = computed(() => {
  return props.reservations.filter(request => 
    request.status === 'pending' && 
    request.reservation_type === 'flexible'
  ).length;
});

const showBankDetailModal = ref(false);

const form = useForm({
  hotel_id: props.hotel[0]?.id || '',
  hotelowner_id: usePage().props.auth.user.id,
  bank_name: '',
  acc_nomber: '',
  branch: '',
  acc_holder_name: '',
});

function submit() {
  form.post('/bank-detail', {
    onSuccess: () => {
      showBankDetailModal.value = false;
      Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: 'Bank details added successfully.',
        confirmButtonColor: '#f97316'
      });
    }
  });
}

// Show modal if bankDetails is false (no details exist)
if (props.bankDetails === false) {
  showBankDetailModal.value = true;
}

const bankNames = [
  'Bank of Ceylon',
  'People\'s Bank',
  'Commercial Bank','National Savings Bank','HDFC Bank',
  'Sampath Bank','SDB Bank','RDB Bank',
  'Hatton National Bank',
  'Seylan Bank',
  'Nations Trust Bank',
  'DFCC Bank','HSBC Bank',
  'Union Bank',
  'Pan Asia Bank',
  'Amana Bank PLC','Cargills Bank Limited',
];

// start countdown updater for owner dashboard
onMounted(() => {
  const updateRemaining = () => {
    const now = new Date();
    const map = {};
    if (props.reservations && props.reservations.length) {
      props.reservations.forEach(reservation => {
        if (reservation.expire_at && reservation.status === 'pending') {
          const raw = reservation.expire_at_iso || reservation.expire_at;
          const parseExpire = (s) => {
            if (!s) return null;
            let d = new Date(s);
            if (!isNaN(d.getTime())) return d;
            d = new Date(String(s).replace(' ', 'T'));
            if (!isNaN(d.getTime())) return d;
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

onUnmounted(() => {
  if (timer) clearInterval(timer);
});

// Format currency for display (LKR)
const formatCurrency = (value) => {
  if (value === null || value === undefined || value === '') return 'Rs. 0.00';
  const num = Number(value);
  if (isNaN(num)) return value;
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'LKR',
    minimumFractionDigits: 2
  }).format(num);
};
</script>

<template>
  <HotelOwnerAuthenticatedLayout>
    <div class="min-h-screen bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-12">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
          <!-- Reservation Count Card -->
          <div class="bg-white rounded-2xl shadow-md p-6 border-l-4 border-orange-500 hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300 ease-in-out">
            <div class="flex items-center">
              <div class="p-3 bg-orange-100 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3M16 7V3M4 11h16M4 19h16M4 11v8a2 2 0 002 2h12a2 2 0 002-2v-8M4 7h16" />
                </svg>
              </div>
              <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-700">Reservation count</h3>
                <p class="text-3xl font-bold text-orange-600">{{ props.reservations.length }}</p>
              </div>
            </div>
          </div>

          <!-- Approved Reservations Card -->
          <div class="bg-white rounded-2xl shadow-md p-6 border-l-4 border-green-500 hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300 ease-in-out">
            <div class="flex items-center">
              <div class="p-3 bg-green-100 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-700">Approved reservations</h3>
                <p class="text-3xl font-bold text-green-600">{{ approvedReservationsCount }}</p>
              </div>
            </div>
          </div>

          <!-- Rejected Reservations Card -->
          <div class="bg-white rounded-2xl shadow-md p-6 border-l-4 border-red-500 hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300 ease-in-out">
            <div class="flex items-center">
              <div class="p-3 bg-red-100 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </div>
              <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-700">Rejected reservations</h3>
                <p class="text-3xl font-bold text-red-600">{{rejectedReservationsCount}}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Reservation Requests Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
          <!-- Urgent Requests Card -->
          <div class="bg-white rounded-2xl shadow-md p-6 border-l-4 border-red-600 hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300 ease-in-out">
            <div class="flex items-center">
              <div class="p-3 bg-red-100 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-700">Urgent Requests</h3>
                <p class="text-3xl font-bold text-red-600">{{ urgentCount }}</p>
                <span class="text-xs text-gray-500">Confirm within 5 hours</span>
              </div>
            </div>
          </div>

          <!-- Standard Requests Card -->
          <div class="bg-white rounded-2xl shadow-md p-6 border-l-4 border-blue-500 hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300 ease-in-out">
            <div class="flex items-center">
              <div class="p-3 bg-blue-100 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-700">Standard Requests</h3>
                <p class="text-3xl font-bold text-blue-600">{{ standardCount }}</p>
                <span class="text-xs text-gray-500">Confirm within 48 hours</span>
              </div>
            </div>
          </div>

          <!-- Flexible Requests Card -->
          <div class="bg-white rounded-2xl shadow-md p-6 border-l-4 border-green-600 hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300 ease-in-out">
            <div class="flex items-center">
              <div class="p-3 bg-green-100 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-700">Flexible Requests</h3>
                <p class="text-3xl font-bold text-green-600">{{ flexibleCount }}</p>
                <span class="text-xs text-gray-500">Confirm within 72 hours</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Consolidated Alert Notifications -->
        <div v-if="pendingReservationsCount || upCommingReservation.length || previousReservations.length" 
            class="bg-orange-50 border-l-4 border-orange-500 p-5 rounded-2xl mb-8 animate-fadeIn shadow-md">
          <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="space-y-1">
              <h2 class="text-sm sm:text-base font-medium text-gray-700">Reservation Summary:</h2>
              <ul class="text-sm text-gray-600 space-y-1">
                <li v-if="pendingReservationsCount" class="flex flex-col space-y-2">
                  <div class="ml-4 space-y-1 text-sm">
                    <div v-if="urgentCount" class="flex items-center">
                      <span class="w-2 h2 bg-red-500 rounded-full mr-2"></span>
                      <span>{{ urgentCount }} urgent (5 hour response)</span>
                    </div>
                    <div v-if="standardCount" class="flex items-center">
                      <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                      <span>{{ standardCount }} standard (48 hour response)</span>
                    </div>
                    <div v-if="flexibleCount" class="flex items-center">
                      <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                      <span>{{ flexibleCount }} flexible (72 hour response)</span>
                    </div>
                  </div>
                </li>
                <li v-if="upCommingReservation.length" class="flex items-center">
                  <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                  <span>{{ upCommingReservation.length }} upcoming reservations</span>
                </li>
              </ul>
            </div>
          </div>
        </div>

        

        <!-- Header with title and actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4">
          <div class="flex flex-wrap gap-2">
            <button 
              @click="currentView = 'pending'"
              :class="[
                'px-4 py-2 rounded-lg font-medium transition-all duration-300',
                currentView === 'pending' 
                  ? 'bg-orange-500 text-white shadow-md' 
                  : 'bg-orange-100 text-gray-700 hover:bg-orange-200'
              ]"
            >
              Reservation Requests
            </button>
            <button 
              @click="currentView = 'upcoming'"
              :class="[
                'px-4 py-2 rounded-lg font-medium transition-all duration-300',
                currentView === 'upcoming' 
                  ? 'bg-orange-500 text-white shadow-md' 
                  : 'bg-orange-100 text-gray-700 hover:bg-orange-200'
              ]"
            >
              Upcoming Reservations
            </button>
            <button 
              @click="currentView = 'previous'"
              :class="[
                'px-4 py-2 rounded-lg font-medium transition-all duration-300',
                currentView === 'previous' 
                  ? 'bg-orange-500 text-white shadow-md' 
                  : 'bg-orange-100 text-gray-700 hover:bg-orange-200'
              ]"
            >
              Previous Reservations
            </button>
          </div>
          <div class="flex flex-wrap gap-3">
            <Link :href="route('hotelOwner.hotel.show', { id: props.hotel[0].id })" class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2.5 px-5 rounded-lg shadow-md transition-all duration-300 transform hover:scale-105 hover:shadow-lg flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
              View Hotel
            </Link>
            <Link :href="route('hotelOwner.hotel.edit', { id: props.hotel[0].id })" class="bg-teal-500 hover:bg-teal-600 text-white font-semibold py-2.5 px-5 rounded-lg shadow-md transition-all duration-300 transform hover:scale-105 hover:shadow-lg flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
              Update Hotel
            </Link>
          </div>
        </div>

        <!-- Pending Reservations Section -->
        <div v-if="currentView === 'pending'" class="space-y-6">
          <div class="flex items-center mb-4">
            <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center mr-3 shadow-inner">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-600" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
              </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 relative">
             Reservation Requests
            </h2>
          </div>
          
          <!-- Request Type Tabs -->
          <div class="bg-white rounded-2xl shadow-md overflow-hidden">
            <div class="flex flex-wrap">
              <button
                v-for="tab in tabs"
                :key="tab.id"
                @click="activeTab = tab.id"
                :class="[
                  'flex-1 min-w-[150px] py-3 px-4 text-sm font-medium transition-all duration-300 relative',
                  activeTab === tab.id
                    ? 'bg-orange-500 text-white shadow-md'
                    : 'hover:bg-orange-50 text-gray-600'
                ]"
              >
                <div class="flex flex-col items-center">
                  <span class="text-base font-semibold">{{ tab.name }}</span>
                  <span class="block text-xs mt-1 opacity-80">{{ tab.description }}</span>
                  <span v-if="activeTab === tab.id" class="absolute bottom-0 left-0 w-full h-1 bg-white"></span>
                </div>
              </button>
            </div>
          </div>

          <!-- Pending Requests -->
          <div class="bg-white rounded-2xl shadow-md overflow-hidden mb-12">
            <div class="divide-y divide-gray-100 space-y-2">
              <div v-for="request in filteredReservations" :key="request.id"
                  class="group hover:bg-orange-50 transition-all duration-300 p-6 animate-fadeIn first:border-t-0">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                  <div class="flex-1">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-4">
                      <h3 class="text-xl font-bold text-gray-800 group-hover:text-orange-600 transition-colors">
                        {{ request.user.name }}
                      </h3>
                      
                      <div class="flex items-center ml-auto gap-4">
                        <span class="flex items-center bg-gray-50 px-4 py-3 rounded-lg">
                            <div class="flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span v-for="room in groupRoomsByType(request.rooms)" class="text-gray-600">{{ room.day_count }} days</span>
                          </div>
                          <div class="mx-3 h-5 w-px bg-gray-300"></div>
                          <div class="flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="font-medium text-gray-900">{{ formatCurrency(request.total_price) }}</span>
                          </div>
                        </span>

                        <span v-if="remainingTimes[request.id] !== undefined && remainingTimes[request.id] !== null" class="flex items-center bg-gray-50 px-3 py-2 rounded-lg text-sm font-semibold text-orange-600">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                          </svg>
                          Time left: {{ remainingTimes[request.id] }}
                        </span>

                        <div class="flex space-x-2">
                          <button @click="confirmReservation(request)" class="bg-white text-sm border-2 border-green-500 text-green-500 hover:bg-green-500 hover:text-white font-semibold py-2 px-4 rounded-lg shadow-sm transition-all duration-300 transform hover:scale-105 hover:shadow-md flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                          </button>
                          <button @click="rejectReservation(request)" class="bg-white text-sm border-2 border-red-500 text-red-500 hover:bg-red-500 hover:text-white font-semibold py-2 px-4 rounded-lg shadow-sm transition-all duration-300 transform hover:scale-105 hover:shadow-md flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                          </button>
                        </div>
                      </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-sm text-gray-600">
                      <span class="flex items-center bg-gray-50 p-3 rounded-lg group-hover:bg-white group-hover:shadow-sm transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 01-2-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="font-medium">Guest:</span> {{ request.user.name }}
                      </span>
                      <span class="flex items-center bg-gray-50 p-3 rounded-lg group-hover:bg-white group-hover:shadow-sm transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="font-medium">Check-in:</span> {{ request.check_in_date }}
                      </span>
                      <span class="flex items-center bg-gray-50 p-3 rounded-lg group-hover:bg-white group-hover:shadow-sm transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="font-medium">Check-out:</span> {{ request.check_out_date }}
                      </span>
                      <span class="flex flex-col bg-gray-50 p-3 rounded-lg group-hover:bg-white group-hover:shadow-sm transition-all duration-300">
                        <div class="flex items-center mb-2">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10V20M21 10V20M3 16h18M3 10h18M7 10V6a2 2 0 012-2h6a2 2 0 012 2v4"/>
                          </svg>
                          <span class="font-medium text-gray-700">Rooms</span>
                        </div>
                        <div class="space-y-1 pl-7">
                          <div v-for="room in groupRoomsByType(request.rooms)" :key="room.room.type" class="flex items-center">
                            <span class="text-gray-600">{{ room.room_count }}x {{ room.room.type }}</span>
                          </div>
                        </div>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!--Requests Empty State -->
            <div v-if="filteredReservations.length === 0" class="p-12 text-center">
              <div class="mx-auto w-24 h-24 bg-orange-50 rounded-full flex items-center justify-center mb-6 animate-pulse">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <h3 class="text-xl font-semibold text-gray-800 mb-3">No {{activeTab}} Requests</h3>
              <p class="text-gray-600 max-w-md mx-auto">There are currently no pending reservation requests to review in this category.</p>
            </div>
          </div>
        </div>

       <!-- Upcoming Reservations Section -->
        <div v-if="currentView === 'upcoming'" class="space-y-6">
          <div class="flex items-center mb-4">
            <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center mr-3 shadow-inner">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-600" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
              </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 relative">
              Upcoming Reservations
            </h2>
          </div>
          
          <!-- Upcoming Reservations Section -->
          <div v-if="upCommingReservation.length > 0" class="mb-16 fade-in" style="animation-delay: 0.2s;">           
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-1 gap-6">
              <div v-for="reservation in upCommingReservation" :key="reservation.id" 
                  class="bg-white rounded-2xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-xl hover:translate-y-[-5px] border border-amber-100/50">
                <div class="p-6">
                  <div class="flex items-center mb-4">
                    <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center mr-4 shadow-inner">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                    </div>
                    <div>
                      <div class="text-xl font-bold text-gray-900">{{ reservation.user.name }}</div>
                    </div>
                  </div>
                  
                  <div class="grid md:grid-cols-3 gap-4 p-4 bg-amber-50 rounded-xl mb-4 shadow-inner">
                    <div>
                      <div class="text-amber-600 font-medium">Check-in</div>
                      <div class="font-semibold text-gray-800">{{ reservation.check_in_date }}</div>
                    </div>
                    <div>
                      <div class="text-amber-600 font-medium">Check-out</div>
                      <div class="font-semibold text-gray-800">{{ reservation.check_out_date }}</div>
                    </div>
                    <div>
                      <div class="text-amber-600 font-medium">Rooms</div>
                      <div class="space-y-1">
                        <div v-for="room in groupRoomsByType(props.reservationRooms[reservation.id] || [])" :key="room.room.type" class="font-semibold text-gray-800">
                          {{ room.room_count }} {{  room.room.type }}
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="flex justify-end items-center space-x-3">
                    <div class="flex items-center mr-4">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v8" />
                      </svg>
                      <span class="font-medium text-gray-900">{{ formatCurrency(reservation.total_price) }}</span>
                    </div>
                    <div>
                      <span v-if="reservation.status === 'pending'" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                        Pending
                      </span>
                      <span v-if="reservation.payment_status === 'paid'" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        Paid
                      </span>
                    </div>
                    <div>
                      <button
                        v-if="reservation.status === 'confirmed' && !reservation.is_completed"
                        @click.prevent="completeReservation(reservation)"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg font-semibold shadow-sm transition"
                      >
                        Complete
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div v-else class="p-12 text-center bg-white rounded-2xl shadow-md mb-12">
            <div class="mx-auto w-24 h-24 bg-amber-50 rounded-full flex items-center justify-center mb-6 animate-pulse shadow-inner">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-3">No Upcoming Reservations</h3>
            <p class="text-gray-600 max-w-md mx-auto">There are currently no upcoming reservations to review.</p>
          </div>
        </div>
      
        <!-- Previous Reservations Section -->
        <div v-if="currentView === 'previous'" class="space-y-6">
          <div class="flex items-center mb-4">
            <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center mr-3 shadow-inner">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-600" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
              </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 relative">
              Previous Reservations
            </h2>
          </div>

          <div class="bg-white rounded-2xl shadow-md overflow-hidden">
            <div v-if="previousReservations.length !== 0" class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-orange-50">
                  <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-orange-700 uppercase tracking-wider">Customer Name</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-orange-700 uppercase tracking-wider">Check in Date</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-orange-700 uppercase tracking-wider">Check out Date</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-orange-700 uppercase tracking-wider">Rooms</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-orange-700 uppercase tracking-wider">Budget</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="prev in previousReservations" :key="prev.id" class="hover:bg-orange-50 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ prev.user.name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ prev.check_in_date }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ prev.check_out_date }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <div class="space-y-1">
                          <div v-for="room in groupRoomsByType(props.reservationRooms[prev.id] || [])" :key="room.room.type" class="text-sm text-gray-700">
                            {{ room.room_count }} x {{ room.room.type }} <span class="text-gray-500">({{ room.adult_count || 0 }} adults, {{ room.child_count || 0 }} children)</span>
                          </div>
                        </div>
                      </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rs.{{ prev.total_price }}</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!--Table Empty State -->
            <div v-else class="p-12 text-center">
              <div class="mx-auto w-24 h-24 bg-orange-50 rounded-full flex items-center justify-center mb-6 animate-pulse shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <h3 class="text-xl font-semibold text-gray-800 mb-3">No Previous Reservations</h3>
              <p class="text-gray-600 max-w-md mx-auto">There are currently no previous reservations to review.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </HotelOwnerAuthenticatedLayout>
</template>

<style>
/* Add animations and transitions */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

.animate-fadeIn {
  animation: fadeIn 0.5s ease-out forwards;
}

@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.7; }
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

.fade-in {
  opacity: 0;
  animation: fadeIn 0.8s ease-out forwards;
}

/* Responsive improvements */
@media (max-width: 640px) {
  .grid-cols-1 {
    row-gap: 1rem;
  }
}
</style>