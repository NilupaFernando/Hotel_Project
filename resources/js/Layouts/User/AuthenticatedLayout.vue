<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import FooterLayout from '@/Layouts/FooterLayout.vue';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-50">
            <!-- Navigation Bar -->
            <header
                :class="[
                    'fixed w-full z-50 transition-all duration-500',
                    'py-3 bg-gray-200 shadow-md border-b border-[#ED9B40]'
                ]"
            >
                <nav class="container flex items-center justify-between px-4 mx-auto lg:px-8">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <Link :href="route('user.dashboard')" class="flex items-center group">
                            <ApplicationLogo
                                class="w-auto h-10 transition-transform duration-300 group-hover:scale-110" />
                            <span
                                class="ml-2 text-xl font-bold text-gray-800 font-['Cantora_One'] tracking-wide group-hover:text-[#ED9B40] transition-colors duration-300">
                                RoomCatch
                            </span>
                        </Link>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden space-x-8 md:flex">
                        <NavLink v-for="(item, index) in [
                            { name: 'Home', route: 'user.dashboard' },
                            { name: 'Hotel', route: 'user.hotel.index' },
                            { name: 'About', route: 'user.about' },
                            { name: 'Contact', route: 'user.contact' }
                        ]" :key="index" :href="route(item.route)" :active="route().current(item.route)"
                            class="text-base font-medium text-gray-700 transition-all duration-300 hover:text-[#ED9B40] [&.active]:text-[#ED9B40]">
                            {{ item.name }}
                        </NavLink>
                    </div>

                    <!-- List Your Property and User Dropdown -->
                    <div class="hidden space-x-4 md:flex">
                        <NavLink :href="route('user-register-hotel-owner')"
                            :active="route().current('user-register-hotel-owner')"
                            class="text-base font-medium text-gray-700 transition-all duration-300 hover:text-[#ED9B40] [&.active]:text-[#ED9B40]">
                            List your property
                        </NavLink>
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button type="button"
                                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-[#ED9B40] border border-[#ED9B40] rounded-md hover:bg-orange-400 focus:outline-none transition-colors">
                                    {{ $page.props.auth.user.name }}
                                    <svg class="w-4 h-4 ml-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </template>
                            <template #content>
                                <DropdownLink :href="route('profile.edit')">Profile</DropdownLink>
                                <DropdownLink :href="route('favorites.index')">Favourite Hotels</DropdownLink>
                                <DropdownLink :href="route('user.reservations.index')">Reservations</DropdownLink>
                                <DropdownLink :href="route('logout')" method="post" as="button">Log Out</DropdownLink>
                            </template>
                        </Dropdown>
                    </div>

                    <!-- Hamburger Menu -->
                    <div class="flex items-center md:hidden">
                        <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                            class="inline-flex items-center justify-center p-2 text-gray-400 transition duration-150 ease-in-out rounded-md hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none">
                            <svg class="w-6 h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </nav>

                <!-- Responsive Navigation Menu -->
                <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                    class="md:hidden bg-gray-400 border-b border-[#ED9B40]">
                    <div class="space-y-1 py-2">
                        <ResponsiveNavLink v-for="(item, index) in [
                            { name: 'Home', route: 'user.dashboard' },
                            { name: 'Hotel', route: 'user.hotel.index' },
                            { name: 'About', route: 'user.about' },
                            { name: 'Contact', route: 'user.contact' }
                        ]" :key="index" :href="route(item.route)" :active="route().current(item.route)"
                            class="text-lg font-bold text-white transition-all duration-300 hover:text-[#ED9B40] hover:bg-white/10 [&.active]:text-[#ED9B40] [&.active]:bg-white/10 px-6 py-3 rounded-md block tracking-wide">
                            {{ item.name }}
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('user-register-hotel-owner')"
                            :active="route().current('user-register-hotel-owner')"
                            class="text-lg font-bold text-white transition-all duration-300 hover:text-[#ED9B40] hover:bg-white/10 [&.active]:text-[#ED9B40] [&.active]:bg-white/10 px-6 py-3 rounded-md block tracking-wide">
                            List your property
                        </ResponsiveNavLink>
                    </div>
                    <div class="pt-4 border-t border-[#ED9B40] bg-gray-400">
                        <div class="px-6 pb-2">
                            <div class="text-base font-bold text-white">{{ $page.props.auth.user.name }}</div>
                            <div class="text-sm font-medium text-gray-300">{{ $page.props.auth.user.email }}</div>
                        </div>
                        <div class="mt-3 space-y-1 pb-2">
                            <ResponsiveNavLink :href="route('profile.edit')"
                                class="text-lg font-bold text-white transition-all duration-300 hover:text-[#ED9B40] hover:bg-white/10 [&.active]:text-[#ED9B40] [&.active]:bg-white/10 px-6 py-3 rounded-md block tracking-wide">
                                Profile
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button"
                                class="text-lg font-bold text-white transition-all duration-300 hover:text-[#ED9B40] hover:bg-white/10 [&.active]:text-[#ED9B40] [&.active]:bg-white/10 px-6 py-3 rounded-md block tracking-wide">
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
                <FooterLayout></FooterLayout>
            </main>
        </div>
    </div>
</template>
