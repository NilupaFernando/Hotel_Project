<script setup>
import { ref } from 'vue'
import ViewHotelOfferCard from './ViewHotelOfferCard.vue'

const checkInDate = ref(new Date())
const checkOutDate = ref(new Date())
const adult = ref(1)
const child = ref(1)
const isOpen = ref(false)

const toggleDropdown = () => {
  isOpen.value = !isOpen.value
}

const increase = (type) => {
  if (type === 'adult') adult.value++
  if (type === 'child') child.value++
}

const decrease = (type) => {
  if (type === 'adult' && adult.value > 1) adult.value--
  if (type === 'child' && child.value > 0) child.value--
}

const handleSearch = () => {
  console.log({
    checkIn: checkInDate.value,
    checkOut: checkOutDate.value,
    adult: adult.value,
    child: child.value
  })
}

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
</script>

<template>
  <div class="flex p-8 mx-auto gap-28">
    <div class="flex-1 p-8 rounded-lg shadow-md bg-[#E8EEF5] ml-8">
      <div class="grid grid-cols-2 gap-4 mb-8">
        <div class="flex flex-col gap-2">
          <label class="font-medium text-gray-700">Check-In Date</label>
          <!-- <DatePicker v-model="checkInDate" class="w-full" /> -->
          <input
                type="date"
                v-model="checkInDate"
                class="p-2 border rounded-md"
            />
        </div>

        <div class="flex flex-col gap-2">
          <label class="font-medium text-gray-700">Check-Out Date</label>
          <!-- <DatePicker v-model="checkOutDate" class="w-full" /> -->
          <input
                type="date"
                v-model="checkOutDate"
                class="p-2 border rounded-md"
                @change="calculateDayCount"
                />
        </div>
      </div>

      <div class="mb-8">
        <label class="block mb-2 font-medium text-gray-700">Adult / Children</label>
        <div class="relative">
          <button 
            @click="toggleDropdown" 
            class="flex items-center justify-between w-full px-4 py-3 text-left transition-colors bg-white border border-gray-300 rounded-md hover:border-gray-400"
          >
            {{ adult }} adult - {{ child }} child
            <span 
              class="transition-transform duration-200 transform"
              :class="{ 'rotate-180': isOpen }"
            >▼</span>
          </button>

          <div 
            v-if="isOpen" 
            class="absolute left-0 right-0 z-50 p-4 mt-2 bg-[#F2F2F2] border border-gray-300 rounded-md shadow-lg top-full"
          >
            <div class="space-y-4">
              <div class="flex items-center justify-between">
                <label class="font-medium text-gray-700">Adult</label>
                <div class="flex items-center gap-3">
                  <button 
                    @click="decrease('adult')"
                    class="flex items-center justify-center w-8 h-8 border border-gray-300 rounded hover:bg-gray-50"
                  >-</button>
                  <span class="w-8 text-center">{{ adult }}</span>
                  <button 
                    @click="increase('adult')"
                    class="flex items-center justify-center w-8 h-8 border border-gray-300 rounded hover:bg-gray-50"
                  >+</button>
                </div>
              </div>

              <div class="flex items-center justify-between">
                <label class="font-medium text-gray-700">Children</label>
                <div class="flex items-center gap-3">
                  <button 
                    @click="decrease('child')"
                    class="flex items-center justify-center w-8 h-8 border border-gray-300 rounded hover:bg-gray-50"
                  >-</button>
                  <span class="w-8 text-center">{{ child }}</span>
                  <button 
                    @click="increase('child')"
                    class="flex items-center justify-center w-8 h-8 border border-gray-300 rounded hover:bg-gray-50"
                  >+</button>
                </div>
              </div>
              <p class="text-sm text-gray-500">under 12 years old are considered as children</p>
            </div>
          </div>
        </div>
      </div>

      <button 
        @click="handleSearch"
        class="w-full py-4 font-medium text-white transition-colors bg-[#eca34f] rounded-md hover:bg-[#e99535] text-xl"
      >
        Search
      </button>
    </div>

    <div class="relative mr-14">
      <ViewHotelOfferCard/>
  </div>
  </div>
</template>