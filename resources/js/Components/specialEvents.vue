<script setup>
import { ref, onMounted } from 'vue';
import { ChevronLeftIcon, ChevronRightIcon } from 'lucide-vue-next';

const cards = ref([
  {
    name: "Kandy Esala Perahera",
    date: "July/August",
    location: "Kandy",
    image: "/img/Awrudu.jpg",
    color: "#FFA500"
  },
  {
    name: "Vesak Festival",
    date: "May",
    location: "Nationwide",
    image: "/img/vesak.jpg",
    color: "#4A90E2"
  },
  {
    name: "Sinhala & Tamil New Year",
    date: "13-14 April",
    location: "Nationwide",
    image: "/img/Awrudu.jpg",
    color: "#50C878"
  },
  {
    name: "Adam's Peak Pilgrimage",
    date: "December-May",
    location: "Sri Pada",
    image: "/img/ademsPeak.jpg",
    color: "#800080"
  },
  {
    name: "Hikkaduwa Beach Festival",
    date: "July",
    location: "Hikkaduwa",
    image: "/img/hikkaduwa.jpg",
    color: "#FF6347"
  }
]);

const activeIndex = ref(0);
const isAnimating = ref(false);

const nextCard = () => {
  if (isAnimating.value) return;
  isAnimating.value = true;
  activeIndex.value = (activeIndex.value + 1) % cards.value.length;
  setTimeout(() => {
    isAnimating.value = false;
  }, 500);
};

const prevCard = () => {
  if (isAnimating.value) return;
  isAnimating.value = true;
  activeIndex.value = (activeIndex.value - 1 + cards.value.length) % cards.value.length;
  setTimeout(() => {
    isAnimating.value = false;
  }, 500);
};

onMounted(() => {
  // Auto-rotate cards every 5 seconds
  const interval = setInterval(() => {
    nextCard();
  }, 5000);

  return () => clearInterval(interval);
});
</script>

<template>
  <section class="special-events-section">
    <div class="events-container">
      <!-- Decorative elements -->
      <div class="circle-1 decorative-circle"></div>
      <div class="circle-2 decorative-circle"></div>

      <!-- Content -->
      <div class="content-wrapper">
        <!-- Text content -->
        <div class="text-content" data-aos="fade-up" data-aos-duration="1000">
          <div class="section-tag">Discover</div>
          <h2 class="section-title">Special Events</h2>
          <p class="section-description">
            Sri Lanka's vibrant festivals reflect its rich culture. The Dalada Perahera in Kandy honors the sacred Tooth Relic with grand processions. The Adam's Peak pilgrimage draws devotees for a sacred climb. In April, the Sinhala and Tamil New Year unites families with traditions and festivities, while Vesak in May lights up the country with lanterns and religious celebrations.
          </p>
          <div class="navigation-controls">
            <button
              class="nav-button"
              @click="prevCard"
              :disabled="isAnimating"
              aria-label="Previous event"
            >
              <ChevronLeftIcon size="20" />
            </button>
            <div class="pagination-indicators">
              <span
                v-for="(_, index) in cards"
                :key="index"
                class="indicator"
                :class="{ active: activeIndex === index }"
                @click="activeIndex = index"
              ></span>
            </div>
            <button
              class="nav-button"
              @click="nextCard"
              :disabled="isAnimating"
              aria-label="Next event"
            >
              <ChevronRightIcon size="20" />
            </button>
          </div>
        </div>

        <!-- Cards carousel -->
        <div class="cards-showcase" data-aos="fade-left" data-aos-duration="1000">
          <div class="cards-container">
            <div
              v-for="(card, index) in cards"
              :key="index"
              class="event-card"
              :class="{
                'active': index === activeIndex,
                'next': (index === (activeIndex + 1) % cards.length),
                'prev': (index === (activeIndex - 1 + cards.length) % cards.length),
                'far': (index !== activeIndex &&
                        index !== (activeIndex + 1) % cards.length &&
                        index !== (activeIndex - 1 + cards.length) % cards.length)
              }"
              :style="{
                '--card-color': card.color,
                '--card-index': index
              }"
            >
              <div class="card-image-container">
                <img :src="card.image" :alt="card.name" class="card-image">
                <div class="card-overlay"></div>
              </div>
              <div class="card-content">
                <h3 class="card-title">{{ card.name }}</h3>
                <div class="card-details">
                  <span class="card-date">{{ card.date || 'Annual Event' }}</span>
                  <span class="card-location">{{ card.location || 'Sri Lanka' }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
/* Base section styling */
.special-events-section {
  @apply relative py-20 px-6 overflow-hidden bg-gradient-to-b from-white to-orange-50;
}

.events-container {
  @apply max-w-7xl mx-auto relative;
}

/* Decorative elements */
.decorative-circle {
  @apply absolute rounded-full opacity-20;
  background: linear-gradient(135deg, #ED9B40, #ff5a1f);
}

/* .circle-1 {
  @apply w-64 h-64 -top-20 -left-20;
} */

.circle-2 {
  @apply w-96 h-96 -bottom-40 -right-40;
}

.decorative-line {
  @apply absolute h-1 w-24 bg-orange-500 top-28 left-0;
}

/* Content layout */
.content-wrapper {
  @apply grid grid-cols-1 lg:grid-cols-2 gap-16 items-center relative z-10;
}

/* Text content styling */
.text-content {
  @apply max-w-md;
}

.section-tag {
  @apply text-gray-500 font-medium mb-2 tracking-wider uppercase text-sm;
  font-family: 'Poppins', sans-serif;
}

.section-title {
  @apply text-3xl font-bold mb-6 text-orange-500 relative;
  font-family: 'Black Ops One', serif;
}

.section-title::after {
  content: '';
  @apply absolute -bottom-3 left-0 h-1 w-16 bg-orange-500;
}

.section-description {
  @apply text-gray-600 leading-relaxed mb-10;
  font-family: 'Poppins', sans-serif;
}

/* Navigation controls */
.navigation-controls {
  @apply flex items-center space-x-4;
}

.nav-button {
  @apply flex items-center justify-center w-10 h-10 rounded-full bg-white text-orange-500
         shadow-md hover:bg-orange-500 hover:text-white transition-all duration-300
         focus:outline-none focus:ring-2 focus:ring-orange-300;
}

.nav-button:disabled {
  @apply opacity-50 cursor-not-allowed;
}

.pagination-indicators {
  @apply flex space-x-2;
}

.indicator {
  @apply block w-2 h-2 rounded-full bg-gray-300 cursor-pointer transition-all duration-300;
}

.indicator.active {
  @apply w-6 bg-orange-500;
}

/* Cards showcase */
.cards-showcase {
  @apply relative h-[350px] w-full;
}

.cards-container {
  @apply absolute inset-0;
  perspective: 1000px;
}

.event-card {
  @apply absolute w-full h-full rounded-2xl overflow-hidden shadow-xl transition-all duration-500;
  max-width: 70%;
  left: 15%;
  transform-style: preserve-3d;
  backface-visibility: hidden;
}

.event-card.active {
  @apply z-30;
  transform: translateX(0) scale(1) rotateY(0);
}

.event-card.next {
  @apply z-20 opacity-70;
  transform: translateX(40%) scale(0.9) rotateY(10deg);
}

.event-card.prev {
  @apply z-20 opacity-70;
  transform: translateX(-40%) scale(0.9) rotateY(-10deg);
}

.event-card.far {
  @apply z-10 opacity-0;
  transform: translateX(0) scale(0.8);
}

.card-image-container {
  @apply relative h-full w-full;
}

.card-image {
  @apply h-full w-full object-cover;
}

.card-overlay {
  @apply absolute inset-0 bg-gradient-to-t from-black/80 to-transparent;
}

.card-content {
  @apply absolute bottom-0 left-0 w-full p-6 text-white;
}

.card-title {
  @apply text-2xl font-bold mb-2;
  font-family: 'Poppins', sans-serif;
}

.card-details {
  @apply flex justify-between text-sm mb-4 opacity-80;
}

.card-button {
  @apply px-4 py-2 bg-orange-500 text-white rounded-lg text-sm font-medium
         hover:bg-orange-600 transition-colors duration-300;
}

/* Responsive adjustments */
@media (max-width: 1023px) {
  .content-wrapper {
    @apply flex flex-col-reverse;
  }

  .text-content {
    @apply mt-12 text-center mx-auto;
  }

  .section-title::after {
    @apply left-1/2 -translate-x-1/2;
  }

  .navigation-controls {
    @apply justify-center;
  }

  .cards-showcase {
    @apply h-[400px];
  }

  .event-card {
    max-width: 90%;
    left: 5%;
  }
}

@media (max-width: 639px) {
  .cards-showcase {
    @apply h-[350px];
  }

  .event-card.next,
  .event-card.prev {
    @apply opacity-0;
  }

  .event-card {
    max-width: 100%;
    left: 0;
  }
}
</style>
