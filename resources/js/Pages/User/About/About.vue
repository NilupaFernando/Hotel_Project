<template>
    <AuthenticatedLayout>
        <div class="font-serif">
            <!-- Hero Section -->
            <div class="relative h-[400px] bg-cover bg-center" style="background-image: url('/img/hero.jpeg')">
                <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-center items-center">
                    <h1 class="text-white text-5xl font-bold mb-4 animate-fade-in-down">ABOUT US</h1>
                    <div class="text-white flex items-center space-x-2">
                        <a href="#" class="hover:text-gold-400 transition-colors duration-300">HOME</a>
                        <span>›</span>
                        <span>ABOUT US</span>
                    </div>
                </div>
            </div>

            <!-- About Luxury Hotel Section -->
            <div class="container mx-auto py-20 px-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                    <!-- Image Column -->
                    <div
                        class="relative order-2 md:order-1 transform transition-transform duration-700 hover:scale-[1.02]">
                        <img src="/img/room.jpeg" alt="Luxury Room"
                            class="w-full h-auto rounded-xl shadow-2xl object-cover" />
                        <!-- Award Badge -->
                        <div
                            class="absolute -right-5 md:-right-10 top-10 bg-black p-6 rounded-full text-center w-32 h-32 flex flex-col justify-center items-center transform transition-all duration-500 hover:rotate-12 hover:shadow-xl">
                            <div class="text-gold-500 mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto" viewBox="0 0 24 24"
                                    fill="currentColor">
                                    <path d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
                                    <path fillRule="evenodd"
                                        d="M12 3a9 9 0 100 18 9 9 0 000-18zM5 12a7 7 0 1114 0 7 7 0 01-14 0z"
                                        clipRule="evenodd" />
                                </svg>
                            </div>
                            <p class="text-white text-xs font-bold">LUXURY</p>
                            <p class="text-white text-xs font-bold">HOTELS</p>
                        </div>
                    </div>

                    <!-- Content Column -->
                    <div class="order-1 md:order-2">
                        <div class="space-y-6 max-w-lg">

                            <h2 class="text-3xl md:text-4xl font-bold leading-tight">
                                DISCOVER THE BEST <span class="text-gold-500">LUXURY HOTELS</span>
                            </h2>

                            <div class="space-y-4 text-gray-600">
                                <p class="leading-relaxed">
                                    Experience unparalleled elegance and world-class hospitality at the finest luxury
                                    hotels. Indulge in
                                    opulent accommodations, breathtaking views, and exceptional service designed to
                                    elevate your stay.
                                </p>
                                <p class="leading-relaxed">
                                    Whether you're seeking a serene retreat or an extravagant escape, our curated
                                    selection of luxury
                                    hotels offers the perfect blend of comfort, sophistication, and modern amenities.
                                </p>
                            </div>

                            <!-- Button -->
                            <div class="pt-4">
                                <button
                                    @click="seeAllHotels"
                                    class="bg-gold-500 hover:bg-gold-600 text-white px-8 py-3 rounded-lg transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg flex items-center group">
                                    <span>SEE ALL</span>
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 ml-2 transform transition-transform duration-300 group-hover:translate-x-1"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fillRule="evenodd"
                                            d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z"
                                            clipRule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hotel Amenities Section -->
            <div class="bg-black py-16">
                <div class="container mx-auto px-4">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                        <div class="text-center p-4 hover:scale-105 transition-transform duration-300">
                            <div class="text-gold-500 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"
                                    strokeLinejoin="round">
                                    <path d="M12 2v8M4 6l8 4 8-4M4 18l8-4 8 4M12 22v-8" />
                                </svg>
                            </div>
                            <p class="text-white text-sm">Air Condition</p>
                        </div>
                        <div class="text-center p-4 hover:scale-105 transition-transform duration-300">
                            <div class="text-gold-500 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"
                                    strokeLinejoin="round">
                                    <path
                                        d="M5 12.55a11 11 0 0 1 14.08 0M1.42 9a16 16 0 0 1 21.16 0M8.53 16.11a6 6 0 0 1 6.95 0" />
                                </svg>
                            </div>
                            <p class="text-white text-sm">Speed Internet</p>
                        </div>
                        <div class="text-center p-4 hover:scale-105 transition-transform duration-300">
                            <div class="text-gold-500 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"
                                    strokeLinejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                </svg>
                            </div>
                            <p class="text-white text-sm">Smart Lock</p>
                        </div>
                        <div class="text-center p-4 hover:scale-105 transition-transform duration-300">
                            <div class="text-gold-500 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="1" y="10" width="6" height="4" />
                                    <rect x="17" y="10" width="6" height="4" />
                                    <rect x="8" y="8" width="8" height="8" />
                                    <line x1="4" y1="12" x2="8" y2="12" />
                                    <line x1="16" y1="12" x2="20" y2="12" />
                                </svg>
                            </div>
                            <p class="text-white text-sm">Gym Center</p>
                        </div>
                        <div class="text-center p-4 hover:scale-105 transition-transform duration-300">
                            <div class="text-gold-500 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"
                                    strokeLinejoin="round">
                                    <path d="M18 8h1a4 4 0 0 1 0 8h-1" />
                                    <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z" />
                                    <line x1="6" y1="1" x2="6" y2="4" />
                                    <line x1="10" y1="1" x2="10" y2="4" />
                                    <line x1="14" y1="1" x2="14" y2="4" />
                                </svg>
                            </div>
                            <p class="text-white text-sm">Breakfast</p>
                        </div>
                        <div class="text-center p-4 hover:scale-105 transition-transform duration-300">
                            <div class="text-gold-500 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"
                                    strokeLinejoin="round">
                                    <path
                                        d="M22 9h-5.07M22 9l-3 6M22 9H8.93M8.93 9H2m6.93 0 3 6m0 0h6.14m-6.14 0-3-6m9.14 6-3-6" />
                                </svg>
                            </div>
                            <p class="text-white text-sm">Swimming Pool</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Services Section -->
            <div class="py-16 bg-cover bg-center bg-gray-100" style="background-image: url('/spa-background.jpg')">
                <div class="container mx-auto px-4">
                    <div class="text-center mb-12">
                        <p class="text-gold-500 mb-2">Our Services</p>
                        <h2 class="text-3xl md:text-4xl font-bold">EASY & RELIABLE <span class="text-gold-500">HOTEL
                                BOOKING</span>
                        </h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        <!-- Instant Booking -->
                        <div
                            class="bg-white p-8 text-center rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 animate-fade-in">
                            <div class="text-gold-500 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"
                                    strokeLinejoin="round">
                                    <path d="M3 10h18M9 21V3M15 21V3" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold mb-4">INSTANT HOTEL BOOKING</h3>
                            <p class="text-gray-600 mb-6">
                                Book your favorite hotel in just a few clicks! Our real-time availability and secure
                                payment system
                                ensure a hassle-free reservation experience.
                            </p>
                        </div>

                        <!-- Flexible Cancellations -->
                        <div class="bg-white p-8 text-center rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 animate-fade-in"
                            style="animation-delay: 0.2s">
                            <div class="text-gold-500 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"
                                    strokeLinejoin="round">
                                    <path d="M9 12l2 2 4-4M3 12h4M17 12h4" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold mb-4">FLEXIBLE CANCELLATIONS</h3>
                            <p class="text-gray-600 mb-6">
                                Travel plans change? No worries! Many of our hotels offer **free cancellation** or easy
                                modification
                                options for a stress-free booking.
                            </p>
                        </div>

                        <!-- Best Price Guarantee -->
                        <div class="bg-white p-8 text-center rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 animate-fade-in"
                            style="animation-delay: 0.4s">
                            <div class="text-gold-500 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"
                                    strokeLinejoin="round">
                                    <path d="M12 2L15 8H9l3-6zM9 8l3 6 3-6M3 22h18" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold mb-4">BEST PRICE GUARANTEE</h3>
                            <p class="text-gray-600 mb-6">
                                Get the best deals on hotel stays! We offer competitive pricing, exclusive discounts,
                                and price match
                                guarantees to ensure you save more.
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/User/AuthenticatedLayout.vue';

const seeAllHotels = () => {
    router.get(route('explore.hotels'));
};


</script>

<style>
@import 'tailwindcss/base';
@import 'tailwindcss/components';
@import 'tailwindcss/utilities';


/* Custom colors */
:root {
    --color-gold-400: #ff9f43;
    --color-gold-500: #ff8c29;
    --color-gold-600: #e67e22;
}

.text-gold-400 {
    color: var(--color-gold-400);
}

.text-gold-500 {
    color: var(--color-gold-500);
}

.text-gold-600 {
    color: var(--color-gold-600);
}

.bg-gold-400 {
    background-color: var(--color-gold-400);
}

.bg-gold-500 {
    background-color: var(--color-gold-500);
}

.bg-gold-600 {
    background-color: var(--color-gold-600);
}

/* Animations */
@keyframes fade-in-down {
    0% {
        opacity: 0;
        transform: translateY(-20px);
    }

    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fade-in-right {
    0% {
        opacity: 0;
        transform: translateX(-20px);
    }

    100% {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes fade-in {
    0% {
        opacity: 0;
    }

    100% {
        opacity: 1;
    }
}

.animate-fade-in-down {
    animation: fade-in-down 0.5s ease-out forwards;
}

.animate-fade-in-right {
    animation: fade-in-right 0.5s ease-out forwards;
}

.animate-fade-in {
    animation: fade-in 0.5s ease-out forwards;
}
</style>