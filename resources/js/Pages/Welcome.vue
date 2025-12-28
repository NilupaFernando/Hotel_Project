<script setup>
import { Head, Link } from '@inertiajs/vue3';
import WelcomeLayout from '@/Layouts/WelcomeLayout.vue';
import Carousel from '@/Components/carousel.vue';
import SpecialEvents from '@/Components/specialEvents.vue';
import TrendingHotels from '@/Components/TrendingHotels.vue';
import StateCategory from '@/Components/StateCategory.vue';
import ExploreSriLanka from '@/Components/ExploreSriLanka.vue';
import Offer from '@/Components/Offer.vue';

const props = defineProps({
    districts:{
      type: Array,
      required: true,
    },
    user: {
        type: Object,
        required: true,
    },
    topHotels: {
        type: Array,
        default: () =>[],
    },
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}

const specialEventCards = [
  { index: 1, color: '142, 252, 204', image: '/img/ademsPeak.jpg', name: 'Adems Peak' },
  { index: 3, color: '215, 252, 142', image: '/img/DaladhaPerahara.jpg', name: 'Daladha Perahara' },
  { index: 5, color: '252, 208, 142', image: '/img/vesak.jpg', name: 'Vesak' },
  { index: 7, color: '252, 142, 239', image: '/img/Awrudu.jpg', name: 'Awrudu' },
  { index: 9, color: '142, 202, 252', image: '/path-to-image-5.jpg', name: 'Event 5' },
];

const stateCategories = [
  {
    id: 1,
    title: 'Beach',
    image: 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e'
  },
  {
    id: 2,
    title: 'Mountain',
    image: 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b'
  },
  {
    id: 3,
    title: 'Historical Sites',
    image: 'https://images.unsplash.com/photo-1548013146-72479768bada'
  },
  {
    id: 4,
    title: 'Forests',
    image: 'https://images.unsplash.com/photo-1511497584788-876760111969'
  },
  {
    id: 5,
    title: 'Wildlife',
    image: 'https://images.unsplash.com/photo-1564760055775-d63b17a55c44'
  },
  {
    id: 6,
    title: 'Tourist Activities',
    image: 'https://images.unsplash.com/photo-1533669955142-6a73332af4db'
  },
  {
    id: 7,
    title: 'Lakes',
    image: 'https://images.unsplash.com/photo-1533669955142-6a73332af4db'
  }
];

const navigationLinks = [
    { name: 'Home', route: 'user.dashboard' },
    { name: 'Hotels', route: 'hotel.index' },
    { name: 'About', route: 'about' },
    { name: 'Contact', route: 'contact' }
];

</script>
<style>
@import url('https://fonts.googleapis.com/css2?family=ADLaM+Display&family=Advent+Pro:ital,wght@0,100..900;1,100..900&family=Aleo:ital,wght@0,100..900;1,100..900&family=Baskervville:ital@0;1&family=Black+Ops+One&family=Calistoga&family=Cantora+One&family=Concert+One&display=swap');
</style>

<template>
    <Head title="Welcome"/>

    <WelcomeLayout>
    <!-- Hero Carousel -->
    <Carousel               
       :user="user"
    />
    <!-- <SpecialEvents :cards="specialEventCards" /> -->
     
    <div class="w-full bg-gray-50 text-black/50 dark:text-black/50">
        <div class="relative flex min-h-screen flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-black/90 dark:selection:bg-[#FF2D20] dark:selection:text-white/90">
            <div class="relative w-full max-w-2xl lg:max-w-7xl">
                <main>

                   <!-- Trending Hotels -->
                   <TrendingHotels :topHotels="topHotels" />

                    <!-- Offer -->
                   <Offer/>

                  <!-- State Categories -->
                  <StateCategory
                    title="Browse by State Category"
                    :categories="stateCategories"
                    :showViewAll="true"
                   />

                  <!-- Explore sri lanka -->
                  <ExploreSriLanka
                    title="Explore Sri Lanka"
                    :categories="districts"
                    :showViewAll="true"
                    :user="user"
                    />
                </main>
            </div>
        </div>
    </div>
    </WelcomeLayout>
</template>
