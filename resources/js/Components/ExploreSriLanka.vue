<script setup>
import { ref, computed } from 'vue';
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/24/solid';

const props = defineProps({
    title: String,
    showViewAll: Boolean,
    categories: Array,
    user: Object,
    admin: Boolean,
});

const isAdmin = computed(() => !!props.user && props.user.role === 'admin');

const currentIndex = ref(0);
const itemsPerPage = ref(4);
let navigationTimeout = null;

const totalPages = computed(() => Math.ceil(props.categories.length / itemsPerPage.value));

const visibleDestinations = computed(() => {
    const start = currentIndex.value * itemsPerPage.value;
    return props.categories.slice(start, start + itemsPerPage.value);
});

const canGoNext = computed(() => currentIndex.value < totalPages.value - 1);
const canGoPrev = computed(() => currentIndex.value > 0);

const nextPage = () => {
    if (canGoNext.value) {
        currentIndex.value++;
    }
};

const prevPage = () => {
    if (canGoPrev.value) {
        currentIndex.value--;
    }
};

const viewDistrict = (district) => {
    // Debounce navigation
    if (navigationTimeout) return;
    navigationTimeout = setTimeout(() => { navigationTimeout = null; }, 500);

    if (!isAdmin.value) {
        if (typeof window !== 'undefined' && window.$inertia) {
            window.$inertia.visit(route('district.showDistrictHotels', { id: district.id }), { preserveScroll: true });
        } else {
            window.location.href = route('district.showDistrictHotels', { id: district.id });
        }
    }
}
</script>

<template>
    <div class="px-4 mx-auto my-5 max-w-7xl sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-3xl font-bold text-orange-500" style="font-family: 'Calistoga', serif;" data-aos="fade-right">
                    {{ title }}
                </h2>
                <p class="mt-2 text-gray-600" data-aos="fade-right" data-aos-delay="100">
                    Discover the unique charm of each district
                </p>
            </div>
        </div>

        <div class="relative">

            <div :class="admin? 'h-96':'h-54'"
                 class="flex gap-4 mb-10 transition-all duration-300 ease-in-out" style="will-change: transform;">
                <div v-for="district in visibleDestinations"
                     :key="district.id"
                     @click="!isAdmin && viewDistrict(district)"
                     class="relative flex-1 overflow-hidden rounded-lg shadow-md cursor-pointer group" style="will-change: transform;">
                    <img :src="`/img/districts/${district.name.toLowerCase()}.jpg`"
                         :alt="district.name" loading="lazy"
                         class="object-cover w-full h-56 transition-transform duration-300 group-hover:scale-110" style="will-change: transform;">
                    <div class="absolute inset-0 flex flex-col justify-end p-4 bg-gradient-to-t from-black/60 to-transparent">
                        <h3 class="text-lg font-semibold text-white">{{ district.name }}</h3>
                        <p class="text-sm text-white/80">Best Season: {{ district.travel_season }}</p>

                        <div v-if="user && isAdmin" class="flex mt-2 space-x-4">
                            <a :href="route('district.edit', { id: district.id })"  class="font-bold text-blue-500">Edit</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Arrows -->
            <button @click="prevPage"
                    :class="['absolute left-0 top-1/2 transform -translate-y-1/2 -translate-x-4',
                       'bg-white p-2 rounded-full shadow-lg hover:bg-gray-100 transition-colors',
                       { 'opacity-50 cursor-not-allowed': !canGoPrev }]"
                    :disabled="!canGoPrev">
                <ChevronLeftIcon class="w-6 h-6 text-gray-600" />
            </button>

            <button @click="nextPage"
                    :class="['absolute right-0 top-1/2 transform -translate-y-1/2 translate-x-4',
                       'bg-white p-2 rounded-full shadow-lg hover:bg-gray-100 transition-colors',
                       { 'opacity-50 cursor-not-allowed': !canGoNext }]"
                    :disabled="!canGoNext">
                <ChevronRightIcon class="w-6 h-6 text-gray-600" />
            </button>
        </div>
    </div>
</template>
