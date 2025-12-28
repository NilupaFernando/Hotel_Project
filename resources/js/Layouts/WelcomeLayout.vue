<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Head, Link } from '@inertiajs/vue3';
import FooterLayout from '@/Layouts/FooterLayout.vue';
import { usePage } from '@inertiajs/vue3';
import { SearchIcon, HeartIcon, ShoppingBagIcon, UserIcon, MenuIcon, XIcon } from 'lucide-vue-next';

const page = usePage();
const showingNavigationDropdown = ref(false);
const isScrolled = ref(false);
const searchActive = ref(false);
const searchQuery = ref('');

// Handle scroll event to change header appearance
onMounted(() => {
  window.addEventListener('scroll', handleScroll);
  return () => window.removeEventListener('scroll', handleScroll);
});

const handleScroll = () => {
  isScrolled.value = window.scrollY > 50;
};

// Close mobile menu when route changes
watch(() => route().current(), () => {
  showingNavigationDropdown.value = false;
});

const toggleSearch = () => {
  searchActive.value = !searchActive.value;
  if (searchActive.value) {
    setTimeout(() => {
      document.getElementById('search-input')?.focus();
    }, 100);
  }
};
</script>

<template>
  <div>
    <div class="min-h-screen bg-gray-50">
      <!-- Header -->
      <header
        :class="[
          'fixed w-full z-50 transition-all duration-500',
          'py-3 bg-gray-200 shadow-md border-b border-[#ED9B40]' 
        ]"
      >
        <div class="container px-4 mx-auto lg:px-8">
          <div class="flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center">
              <Link :href="route('index')" class="flex items-center group">
                <ApplicationLogo class="w-auto h-10 transition-all duration-300 group-hover:scale-110 group-hover:rotate-3" />
                <span class="ml-2 text-xl font-bold text-black font-['Cantora_One'] tracking-wide group-hover:text-primary transition-colors duration-300">RoomCatch</span>
              </Link>
            </div>

            <!-- Desktop Navigation -->
            <nav class="items-center hidden space-x-10 md:flex">
              <NavLink
                v-for="(item, index) in [
                  { name: 'Home', route: 'index' },
                  { name: 'Hotel', route: 'explore.hotels' },
                  { name: 'About', route: 'about' },
                  { name: 'Contact', route: 'contact' }
                ]"
                :key="index"
                :href="route(item.route)"
                :active="route().current(item.route)"
                class="relative py-2 overflow-hidden text-base font-medium text-orange-500 transition-all duration-300 nav-link hover:text-primary"
              >
                {{ item.name }}
              </NavLink>
            </nav>

            <!-- Right Side Actions -->
            <div class="flex items-center space-x-4">
             
              <!-- Auth Links -->
              <div v-if="page.props.canLogin" class="flex items-center space-x-3">
                <Link
                  v-if="$page.props.auth.user"
                  :href="route($page.props.auth.user.role === 'admin'
                    ? 'admin.dashboard'
                    : $page.props.auth.user.role === 'hotel_owner'
                      ? 'hotelOwner.dashboard'
                      : 'user.dashboard'
                  )"
                  class="flex items-center space-x-1 text-gray-700 transition-all duration-300 hover:text-primary hover:scale-105"
                >
                  <UserIcon class="w-5 h-5 transition-transform duration-300" />
                  <span class="hidden font-medium sm:inline">Dashboard</span>
                </Link>

                <template v-else>
                  <Link
                    :href="route('hotel-owner.register')"
                    class="hidden font-medium text-gray-600 transition-all duration-300 hover:text-primary sm:block hover:scale-105"
                  >
                    List your property
                  </Link>
                  <Link
                    :href="route('login')"
                    class="btn-secondary"
                  >
                    Log in
                  </Link>
                  <Link
                    v-if="page.props.canRegister"
                    :href="route('register')"
                    class="btn-primary"
                  >
                    Register
                  </Link>
                </template>
              </div>

              <!-- Mobile Menu Button -->
              <button
                @click="showingNavigationDropdown = !showingNavigationDropdown"
                class="p-2 transition-all duration-300 rounded-full hover:bg-gray-800 md:hidden hover:rotate-3 active:scale-95"
                aria-label="Menu"
              >
                <MenuIcon v-if="!showingNavigationDropdown" class="w-6 h-6 text-gray-600" />
                <XIcon v-else class="w-6 h-6 text-gray-600 animate-spin-once" />
              </button>
            </div>
          </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div
          v-show="showingNavigationDropdown"
          class="absolute left-0 z-50 w-full duration-500 border-t shadow-xl bg-gray-400 backdrop-blur-md md:hidden top-full animate-dropdown-open"
        >
          <div class="py-2 space-y-1">
            <ResponsiveNavLink
              v-for="(item, index) in [
                { name: 'Home', route: 'index' },
                { name: 'Hotels', route: 'explore.hotels' },
                { name: 'About', route: 'about' },
                { name: 'Contact', route: 'contact' }
              ]"
              :key="index"
              :href="route(item.route)"
              :active="route().current(item.route)"
              class="block px-4 py-3 text-base font-medium transition-all duration-300 hover:bg-primary/10 hover:pl-6"
            >
              {{ item.name }}
            </ResponsiveNavLink>

            <div v-if="!$page.props.auth.user" class="px-4 py-3 border-t">
              <Link
                :href="route('hotel-owner.register')"
                class="block py-2 font-medium text-gray-600 transition-all duration-300 hover:text-primary hover:pl-2"
              >
                List your property
              </Link>
            </div>
          </div>
        </div>
      </header>

      <!-- Page Heading -->
      <header
        class="bg-white shadow"
        v-if="$slots.header"
      >
        <div class="px-4 py-6 mx-auto lg:px-8 max-w-7xl sm:px-6">
          <slot name="header" />
        </div>
      </header>

      <!-- Page Content -->
      <main class="pt-0">
        <slot />
        <FooterLayout />
      </main>
    </div>
  </div>
</template>

<style scoped>
/* Base styles */
.container {
  @apply max-w-7xl mx-auto;
}

/* Button styles */
.btn-primary {
  @apply rounded-full px-5 py-2 text-white font-medium bg-orange-500 hover:bg-primary/90
         transition-all duration-300 shadow-md hover:shadow-lg focus:outline-none focus:ring-2
         focus:ring-primary/50 whitespace-nowrap transform hover:scale-105 hover:-translate-y-0.5;
}

.btn-secondary {
  @apply rounded-full px-5 py-2 text-primary font-medium bg-orange-200 hover:bg-primary/20
         transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-primary/50
         whitespace-nowrap transform hover:scale-105 hover:-translate-y-0.5;
}

/* Navigation link styles */
.nav-link {
  @apply relative text-black hover:text-primary transition-all duration-300 font-medium;
}

.nav-link::after {
  content: '';
  @apply absolute left-0 -bottom-0 w-full h-0.5 bg-primary scale-x-0 origin-center transition-transform duration-300 rounded-full;
}

.nav-link:hover::after {
  @apply scale-x-110;
  animation: bounce 0.5s ease-in-out;
}

.nav-link:hover {
  @apply transform scale-110 rotate-1;
}

/* Define theme colors */
:root {
  --color-primary: #ED9B40;
}

.text-primary {
  color: var(--color-primary);
}

.bg-primary {
  background-color: var(--color-primary);
}

.hover\:text-primary:hover {
  color: rgba(237, 155, 64, 0.9);
}

.focus\:ring-primary\/50:focus {
  --tw-ring-color: rgba(237, 155, 64, 0.5);
}

.bg-primary\/10 {
  background-color: rgba(237, 155, 64, 0.1);
}

.hover\:bg-primary\/20:hover {
  background-color: rgba(237, 155, 64, 0.2);
}

.hover\:bg-primary\/90:hover {
  background-color: rgba(237, 155, 64, 0.9);
}

/* Glassmorphism effect for header when scrolled */
header {
  transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
  box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
}

header.bg-white\/30 {
  background: rgba(255, 255, 255, 0.3);
  backdrop-filter: blur(15px);
  -webkit-backdrop-filter: blur(15px);
  box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
}

header.bg-white\/20 {
  background: rgba(255, 255, 255, 0.2);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
}

/* Smooth transitions for mobile menu */
.top-full {
  top: 100%;
}

/* Add subtle shadow to search overlay */
.shadow-xl {
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

/* Animation for mobile menu */
@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-slide-down {
  animation: slideDown 0.3s ease forwards;
}

/* Animation for close icon */
@keyframes spinOnce {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(90deg);
  }
}

.animate-spin-once {
  animation: spinOnce 0.3s ease forwards;
}

/* Pulse animation for active elements */
@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.8;
  }
}

.nav-link.active {
  @apply text-primary;
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
  color: #ED9B40; /* Ensure active links are displayed in #ED9B40 */
}

/* Improved hover transitions */
a, button {
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

/* Add subtle hover lift effect to all interactive elements */
a:hover:not(.nav-link), 
button:hover {
  transform: translateY(-2px);
}

/* Active state for buttons */
button:active,
a:active {
  transform: translateY(1px);
}

/* Bounce animation for hover effect */
@keyframes bounce {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-3px);
  }
}

/* Responsive styles for navigation */
@media (max-width: 768px) {
  .nav-link {
    @apply text-sm;
  }

  .container {
    @apply px-4;
  }

  .btn-primary, .btn-secondary {
    @apply px-4 py-2 text-sm;
  }
}

@media (max-width: 640px) {
  .nav-link {
    @apply text-xs;
  }

  .btn-primary, .btn-secondary {
    @apply px-3 py-1 text-xs;
  }
}
</style>