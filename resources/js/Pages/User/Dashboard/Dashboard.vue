<script setup>
import AuthenticatedLayout from '@/Layouts/User/AuthenticatedLayout.vue';
import Carousel from '@/Components/carousel.vue';
import SpecialEvents from '@/Components/specialEvents.vue';
import TrendingHotels from '@/Components/TrendingHotels.vue';
import StateCategory from '@/Components/StateCategory.vue';
import ExploreSriLanka from '@/Components/ExploreSriLanka.vue';
import Offer from '@/Components/Offer.vue';
import FooterLayout from '@/Layouts/FooterLayout.vue';

const props = defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
    districts: {
        type: Array,
        required: true,
    },
    topHotels: {
        type: Array,
        default: () =>[],
    },
});

console.log(props.topHotels);

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
    title: 'Mountains',
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
    title: 'Lakes',
    image: 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4'
  },
  {
    id: 7,
    title: 'Tourist Activities',
    image: 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800'
  }
];
</script>

//user dashboard
<template>
    <AuthenticatedLayout>
        <!-- Hero Carousel -->
        <Carousel 
        :user="user"
        />

        <!-- Special Events -->
        <!-- <SpecialEvents :cards="specialEventCards" /> -->

        <!-- Trending Hotels -->
        <TrendingHotels :topHotels="topHotels" />

        <!-- Offer -->
        <div class="offer-wrapper">
            <Offer />
        </div>

        <!-- State Categories -->
        <StateCategory
        title="Browse by State Category"
        :categories="stateCategories"
        :showViewAll="true"
        />

        <!-- Explore Sri Lanka -->
        <ExploreSriLanka
        title="Explore Sri Lanka"
        :categories="districts"
        :showViewAll="true"
        />
    </AuthenticatedLayout>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=ADLaM+Display&family=Advent+Pro:ital,wght@0,100..900;1,100..900&family=Aleo:ital,wght@0,100..900;1,100..900&family=Baskervville:ital@0;1&family=Black+Ops+One&family=Calistoga&family=Cantora+One&family=Concert+One&display=swap');

footer {
    margin-left: 0;
    margin-right: 0;
    padding-left: 0;
    padding-right: 0;
}
.offer-wrapper {
    max-width: 1200px; /* Adjust the width as needed */
    margin: 0 auto; /* Center the wrapper */
    padding: 0 15px; /* Optional padding */
}
</style>
