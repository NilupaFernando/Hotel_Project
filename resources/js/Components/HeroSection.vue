<script setup lang="ts">
import AOS from 'aos';
import 'aos/dist/aos.css';

AOS.init();

defineProps<{
  district: {
    name: string;
    about: string;
    image: string;
    mostly_popular: string;
    travel_season: string,
  }
}>();
</script>

<template>
  <div class="min-h-screen">
    <div class="relative h-[600px] bg-fixed bg-center bg-cover parallax-section"
         :style="{ backgroundImage: `url(${district.image})` }">
      <div class="absolute inset-0 bg-gradient-to-b from-black/50 via-black/30 to-orange-900/30"></div>
      <div class="absolute inset-0 flex items-center justify-center">
        <div class="px-4 text-center text-white" data-aos="fade-down" data-aos-duration="2000">
          <span class="inline-block px-6 py-2 mb-4 text-sm rounded-full bg-orange-500/20 backdrop-blur-sm">
            Welcome to
          </span>
          <h1 class="text-6xl md:text-7xl mb-4 font-['Black_Ops_One'] tracking-wider text-orange-50 drop-shadow-lg">
            {{ district.name }}
          </h1>
          <p class="max-w-2xl mx-auto text-xl leading-relaxed text-orange-50 drop-shadow">
            {{ district.about }}
          </p>
        </div>
      </div>
    </div>

    <div class="max-w-7xl mx-auto rounded-xl shadow-sm overflow-hidden">
      <div class="max-w-[100rem] mx-auto px-1 py-16">
        <div class="grid gap-40 md:grid-cols-2">
          <!-- Welcome Card -->
          <div class="p-8 transition-all duration-300 transform bg-white border-t-4 border-orange-500 shadow-xl rounded-xl hover:-translate-y-2"
               data-aos="fade-right" data-aos-duration="2000">
            <div class="flex items-center mb-6">
              <div class="w-6 h-8 mr-4 bg-orange-500"></div>
              <h2 class="text-2xl font-['Poppins'] font-bold text-gray-800">
                Discover {{ district.name }}
              </h2>
            </div>
            <p class="leading-relaxed text-gray-600">
              {{ district.name }} is a captivating destination known for its rich history, 
              cultural landmarks, and scenic beauty. Visitors can explore famous attractions, 
              enjoy local cuisine, and experience the unique charm.
            </p>
            <div class="p-4 mt-6 border-l-4 border-orange-500 rounded-lg bg-orange-50">
              <p class="italic text-orange-800">
                Discover and book the best hotels with ease. From luxurious resorts to 
                budget-friendly stays, find your perfect accommodation.
              </p>
            </div>
          </div>

          <!-- Season Card -->
          <div class="p-8 text-white transition-all duration-300 transform shadow-xl bg-gradient-to-br from-orange-200 to-orange-500 rounded-xl hover:-translate-y-2"
               data-aos="fade-left" data-aos-duration="2000">
            <div class="flex items-center mb-6">
              <div class="w-16 h-8 mr-4 bg-white"></div>
              <h2 class="text-2xl font-['Cantora_One']">Best Time to Visit</h2>
            </div>
            <div class="relative space-y-4">
              <div class="absolute top-0 right-0 w-20 h-20 rounded-full bg-white/10 blur-2xl"></div>
              <p class="leading-relaxed">
                Visit during the {{ district.travel_season }} months to experience its unique charm 
                and attractions.
              </p>
              <p class="leading-relaxed">
                Whether you're exploring cultural landmarks or enjoying scenic landscapes, 
                {{ district.name }} promises an unforgettable journey throughout 
                the {{ district.travel_season }} season.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.parallax-section {
  background-attachment: fixed;
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
}

@media (max-width: 768px) {
  .parallax-section {
    background-attachment: scroll;
  }
}
</style>