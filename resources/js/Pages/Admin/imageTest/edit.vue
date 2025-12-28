<template>
    <Head title="District" />

    <AdminAuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Edit District info
                </h2>
            </div>
        </template>

        <div class="max-w-full lg:max-w-6xl mx-auto px-4 sm:px-8 lg:px-20 Hotel-style-2025-new add-scroll-bar">
            <h2 class="flex justify-center text-2xl sm:text-3xl font-semibold leading-7 text-white mt-2 mb-6 font-serif">
                Add Hotel Information
            </h2>
            <form @submit.prevent="editHotelTest(props.testImages.id)" method="POST" class="space-y-6">
                <div class="bg-gray-800 p-4 rounded-lg mb-6">
                    <h3 class="text-xl text-white mb-4">Basic Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-white">Upload Main Thumbnail</label>
                            <input type="file" id="imageUpload1" accept="image/*" @change="handleImageChangetest" />
                        </div>

                        <!-- Existing Images from DB -->
                        <div class="flex flex-wrap gap-4">
                            <div v-for="(img, index) in form.images" :key="'existing-' + index" class="relative group">
                                <img :src="typeof img === 'string' ? `/storage/${img}` : img.preview"
                                     class="w-24 h-24 object-cover rounded-lg shadow-md" />

                                <input
                                    type="button"
                                    value="Remove"
                                    class="absolute top-0 right-0 bg-red-500 text-white px-2 rounded" @click="removeImage(index)"
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end mt-6 gap-x-6">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded shadow hover:bg-blue-600">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </AdminAuthenticatedLayout>
</template>

<script setup>
import { Inertia } from '@inertiajs/inertia';
import AdminAuthenticatedLayout from '@/Layouts/Admin/AdminAuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    testImages: {
        type: Object,
    },
});

const form = useForm({
    name: "kamala",
    images: props.testImages.images ? props.testImages.images.map(img => ({ url: img, preview: `/storage/${img}` })) : [],
});

let removedImages = ref([]);

const handleImageChangetest = (event) => {
    const file = event.target.files[0];
    if (file) {
        const newImage = {
            file,
            preview: URL.createObjectURL(file), // Preview for UI
        };
        form.images.push(newImage);
    }
};

// Remove image
const removeImage = (index) => {
    if (form.images[index].url) {
        removedImages.value.push(form.images[index].url); // Track removed DB images
    }
    form.images.splice(index, 1); // Remove from UI
};

// Submit form
const editHotelTest = (id) => {
    const formData = new FormData();

    // Append all images (only new ones)
    form.images.forEach((image) => {
        if (image.file) {
            formData.append('images[]', image.file);
        } else {
            formData.append('images[]', image.url); // Keep DB images
        }
    });

    Object.keys(form).forEach((key) => {
        if (key !== "images") {
            formData.append(key, form[key]);
        }
    });

    // Append removed images
    formData.append('removed_images', JSON.stringify(removedImages.value));

    // Send the request
    Inertia.post(route('imageTest.update', id), formData,form, {
        onSuccess: () => {
            alert("Hotel Updated Successfully");
            form.images = []; // Clear images after save
            removedImages.value = []; // Reset removed images
        },
        onError: (errors) => {
            console.log("Error updating hotel:", errors);
        },
    });
};
</script>

<!--<input-->
<!--    type="button"-->
<!--    value="Remove"-->
<!--    class="absolute top-0 right-0 bg-red-500 text-white px-2 rounded" @click="removeExistingImage(index)"-->
<!--&gt;-->

<!--<div class="grid grid-cols-1 md:grid-cols-2 gap-4">-->
<!--<div>-->
<!--    <label class="block text-sm font-medium text-white">Upload Main Thumbnail</label>-->
<!--    <input type="file" id="imageUpload1" accept="image/*"  @change="handleImageChange" />-->
<!--</div>-->
<!--<div>-->
<!--    <div>-->
<!--        <img-->
<!--            v-if="form.images[0] && !arrayImage[0] || arrayImage[0] === null"-->
<!--            :src="`/storage/${form.images[0]}`"-->
<!--            :alt="form.name"-->
<!--            class="w-full h-64 object-cover"-->
<!--            style="height: 80px; width: 150px; border-radius: 30px"-->
<!--        />-->
<!--        <img-->
<!--            v-if="arrayImage[0]"-->
<!--            :src="arrayImage[0]"-->
<!--            alt="Uploaded Image"-->
<!--            style="height: 80px; width: 150px; border-radius: 30px"-->
<!--        />-->
<!--    </div>-->

<!--</div>-->
<!--</div>-->



<!--let arrayImage = ref([]);-->
<!--let i = ref(0);-->

<!--const handleImageChange = (event) => {-->
<!--if (arrayImage.value.length <= 8) {-->
<!--const file = event.target.files[0];-->
<!--if (file) {-->
<!--form.images[i.value] = file;-->
<!--arrayImage.value[i.value] = URL.createObjectURL(file);-->
<!--i.value++;-->
<!--}-->
<!--console.log(arrayImage.value.length);-->
<!--}-->
<!--}-->

<!--const remove = () => {-->
<!--i.value = 0;-->
<!--arrayImage.value = []; // Clear previous images-->
<!--form.images = []; // Reset form images-->
<!--};-->


<!--const showImage = () => {-->
<!--for (let img of arrayImage.value) {-->
<!--console.log(img);-->
<!--}-->
<!--};-->

<!--const removeindexArray = (index) => {-->
<!--arrayImage.value.splice(index, 1);-->
<!--form.images.splice(index, 1);-->

<!--i.value = arrayImage.value.length;-->
<!--};-->

                                                                <!--remove all image-->
<!--const remove = () => {-->
<!--i.value = 1;-->
<!--arrayImage.value = []; // Clear previous images-->
<!--form.images = []; // Reset form images-->
<!--};-->



<!--let arrayImage = ref([]);-->
<!--let i = ref( form.images.length);-->

<!--const handleImageChange = (event) => {-->
<!--if (arrayImage.value.length < 8) {-->
<!--const file = event.target.files[0];-->
<!--if (file) {-->
<!--form.images[i.value] = file; // Store the image file in form.images-->
<!--arrayImage.value[i.value] = URL.createObjectURL(file); // Store the image URL in arrayImage-->
<!--i.value++;-->
<!--}-->
<!--console.log(arrayImage.value.length);-->
<!--} else {-->
<!--alert("You can upload a maximum of 8 images.");-->
<!--}-->
<!--};-->

<!--const showImage = () => {-->
<!--for (let img of arrayImage.value) {-->
<!--console.log(img);-->
<!--}-->
<!--};-->

<!--const removeindexArray = (index) => {-->
<!--// Remove from both form.images and arrayImage-->
<!--arrayImage.value.splice(index, 1);-->
<!--form.images.splice(index, 1);-->
<!--// Update the index pointer for the next image-->
<!--i.value = arrayImage.value.length;-->
<!--console.log("Image removed, current array length:", arrayImage.value.length);-->
<!--};-->
