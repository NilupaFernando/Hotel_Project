<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  images: {
    type: Array,
    required: true,
    default: () => []
  }
})

console.log('images:', props.images);
const galleryImages = computed(() => {
  if (!props.images) return []
  return props.images.map((url, index) => ({
    id: index + 1,
    url: url.startsWith('/') || url.startsWith('http')
      ? url
      : `/storage/${url}`,  // ← FIXED LINE
    title: `Image ${index + 1}`,
    size: index === 0 ? 'large' : 'small'
  }))
})

const selectedImage = ref(null)

const openImage = (image) => {
  selectedImage.value = image
}

const closeImage = () => {
  selectedImage.value = null
}
</script>

<template>
  <div class="container px-4 py-8 mx-auto">
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
      <div v-for="image in galleryImages"
           :key="image.id"
           :class="[
             'relative overflow-hidden rounded-lg shadow-lg cursor-pointer transform transition-transform duration-300 hover:scale-105',
             image.size === 'large' ? 'col-span-2 row-span-2' : '',
             'aspect-square'
           ]"
           @click="openImage(image)">
        <img :src="image.url"
             :alt="image.title"
             class="object-cover w-full h-full"
             :style="{
               maxWidth: image.size === 'large' ? '450px' : '300px',
               maxHeight: image.size === 'large' ? '450px' : '300px'
             }"
             loading="lazy">
        <div class="absolute inset-0 flex items-center justify-center transition-opacity duration-300 bg-black opacity-0 bg-opacity-40 hover:opacity-100">
          <span class="text-xl font-semibold text-white">{{ image.title }}</span>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div v-if="selectedImage"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-90"
         @click="closeImage">
      <div class="relative w-full max-w-4xl p-4">
        <button
          @click="closeImage"
          class="absolute right-0 text-4xl text-white -top-10 hover:text-gray-300"
        >
          &times;
        </button>
        <img
          :src="selectedImage.url"
          :alt="selectedImage.title"
          class="w-full h-auto max-h-[80vh] object-contain rounded-lg"
        >
        <p class="mt-4 text-xl text-center text-white">{{ selectedImage.title }}</p>
      </div>
    </div>
  </div>
</template>
