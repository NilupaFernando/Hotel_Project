<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import FooterLayout from '@/Layouts/FooterLayout.vue';
import { route } from 'ziggy-js'; // Import the route function

import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import NavLinkPartTwo from '@/Components/NavLinkPartTwo.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);

</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-900 text-gray-200">
            <nav class="border-b border-gray-700 bg-gray-800">
                <!-- Primary Navigation Menu -->
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <div class="flex">
                             <!-- Logo -->
                    <div class="flex items-center">
                        <Link :href="route('admin.dashboard')" class="flex items-center group">
                            <ApplicationLogo
                                class="w-auto h-10 transition-transform duration-300 group-hover:scale-110" />
                            <span
                                class="ml-2 text-xl font-bold text-white font-['Cantora_One'] tracking-wide group-hover:text-[#ED9B40] transition-colors duration-300">
                                RoomCatch
                            </span>
                        </Link>
                    </div>
                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink
                                    :href="route('admin.dashboard')"
                                    :active="route().current('admin.dashboard')"
                                    class="text-white hover:text-orange-400"
                                >
                                    Home
                                </NavLink>
                            </div>

                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink
                                    :href="route('district.index')"
                                    :active="route().current('district.index')"
                                    class="text-white hover:text-orange-400"
                                >
                                    District Info
                                </NavLink>
                            </div>

                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink
                                    :href="route('hotel.index')"
                                    :active="route().current('hotel.index')"
                                    class="text-white hover:text-orange-400"
                                >
                                    Hotel
                                </NavLink>
                            </div>

                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink
                                    :href="route('admin.propertyRequest')"
                                    :active="route().current('admin.propertyRequest')"
                                    class="text-white hover:text-orange-400"
                                >
                                    Property Request
                                </NavLink>
                            </div>
                        </div>

                        <div class="hidden sm:ms-6 sm:flex sm:items-center">
                            <!-- Settings Dropdown -->
                            <div class="relative ms-3">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center rounded-md border border-transparent bg-gray-800 px-3 py-2 text-sm font-medium leading-4 text-gray-200 transition duration-150 ease-in-out hover:text-gray-400 focus:outline-none"
                                            >
                                                {{ $page.props.auth.user.name }}

                                                <svg
                                                    class="-me-0.5 ms-2 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink
                                            :href="route('profile.edit')"
                                            class="text-gray-200 hover:text-gray-400"
                                        >
                                            Profile
                                        </DropdownLink>
                                        <DropdownLink
                                            :href="route('logout')"
                                            method="post"
                                            as="button"
                                            class="text-gray-200 hover:text-gray-400"
                                        >
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                                class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-700 hover:text-gray-200 focus:bg-gray-700 focus:text-gray-200 focus:outline-none"
                            >
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex':
                                                !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex':
                                                showingNavigationDropdown,
                                        }"
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

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="sm:hidden bg-gray-800 text-gray-200"
                >
                    <div class="space-y-1 pb-3 pt-2">
                        <ResponsiveNavLink
                            :href="route('admin.dashboard')"
                            :active="route().current('admin.dashboard')"
                            class="text-gray-200 hover:text-gray-400"
                        >
                            Dashboard
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('district.index')"
                            :active="route().current('admin.dashboard')"
                            class="text-gray-200 hover:text-gray-400"
                        >
                            District Info
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('hotel.index')"
                            :active="route().current('admin.dashboard')"
                            class="text-gray-200 hover:text-gray-400"
                        >
                            Hotel
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('admin.propertyRequest')"
                            :active="route().current('admin.dashboard')"
                            class="text-gray-200 hover:text-gray-400"
                        >
                            Property Request
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="border-t border-gray-700 pb-1 pt-4">
                        <div class="px-4">
                            <div class="text-base font-medium text-gray-200">
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="text-sm font-medium text-gray-400">
                                {{ $page.props.auth.user.email }}
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink
                                :href="route('profile.edit')"
                                class="text-gray-200 hover:text-gray-400"
                            >
                                Profile
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="text-gray-200 hover:text-gray-400"
                            >
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header
                class="bg-gray-800 shadow text-gray-200"
                v-if="$slots.header"
            >
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
<style>

.add-scroll-bar {
    width: 80%;
    max-height: 75vh; 
    overflow-y: auto; 
}

.District-style-2025-new {
    background-color: #084d5e;
    transform: translateY(1%);
    box-shadow: 0px 1px 1px 0px rgba(201, 181, 71, 0.98);
}

.Hotel-style-2025-new {
    background-color: #084d5e;
    transform: translateY(1%);
    box-shadow: 0px 1px 1px 0px rgba(201, 181, 71, 0.98);
}
</style>
