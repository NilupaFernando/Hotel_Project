<template>
    <Head title="Dashboard" />
    <AdminAuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <!-- District Header -->
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Hotel Information
                </h2>
            </div>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 px-4">
                        <!-- Hotel List -->
                        <template v-if="testImages.data.length > 0">
                            <div v-for="testImage in testImages.data" :key="testImage.id" class="relative group">

                                    <a  class="block bg-white shadow-md rounded-lg overflow-hidden relative">
                                        <!-- Image -->
                                        <div  class="cursor-pointer">
                                            <img
                                                :src="testImage.images ? `/storage/${JSON.parse(testImage.images)[0]}` : '/default-image.jpg'"
                                                class="w-full h-64 object-cover"
                                            />
                                        </div>

                                    </a>
                                    <div class="p-4">
                                        <div class="flex space-x-4 mt-2">
                                            <a :href="route('imageTest.edit', { id: testImage.id })" class="text-blue-500 font-bold">Edit</a>
                                        </div>
                                    </div>
                            </div>
                        </template>

                        <!-- No Hotels Available -->
                        <template v-else>
                            <p>No hotels available.</p>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </AdminAuthenticatedLayout>
</template>

<script setup>
import AdminAuthenticatedLayout from '@/Layouts/Admin/AdminAuthenticatedLayout.vue';
import { Head,useForm,router} from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    testImages: {
        type: Array,
    },
});

console.log(props.testImages);

</script>
