<template>
    <Head title="Add Hotel" />

    <AdminAuthenticatedLayout>
        <div class="max-w-full max-h-[500px] mx-auto px-4 sm:px-8 lg:px-20 Hotel-style-2025-new add-scroll-bar">

        <h2 class="flex justify-center text-2xl sm:text-3xl font-semibold leading-7 text-white mt-2 mb-6 font-serif">
                Add Hotel Information
            </h2>


            <!-- Form -->
            <form class="space-y-6">
                <div class="bg-gray-800 p-4 rounded-lg mb-6">
                    <h3 class="text-xl text-white mb-4">Basic Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-white">Name</label>
                            <input v-model="form.name" type="text" class="w-full p-2 border rounded-lg text-black" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-white">Description</label>
                            <input v-model="form.description" type="text" class="w-full p-2 border rounded-lg text-black" />
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-6 gap-x-6">
                    <button type="button" @click="saveTest" class="bg-green-600 text-white px-4 py-2 rounded shadow hover:bg-blue-600">
                        Save
                    </button>
                    <button type="button" @click="updateTest" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-green-600">
                        Update
                    </button>
                </div>


            </form>

            <!-- Table Displaying Saved Hotels -->
            <div v-if="testImage.length" class="mt-10 bg-gray-900 p-6 rounded-lg">
                <div class="bg-gray-800 p-4 rounded-lg mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-xl text-white mb-4">Saved Hotels</h3>
                        </div>
                        <div>
                            <div class="mb-4 flex justify-end">
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Search hotels..."
                                    class="w-full sm:w-1/3 p-2 border rounded-lg text-black"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <table class="w-full text-white border-collapse border border-gray-700">
                    <thead>
                    <tr class="bg-gray-800">
                        <th class="border border-gray-700 px-4 py-2">ID</th>
                        <th class="border border-gray-700 px-4 py-2">Name</th>
                        <th class="border border-gray-700 px-4 py-2">Description</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="test in filteredHotels" :key="test.id"  @click="selectTest(test)" class="bg-gray-700">
                        <td class="border border-gray-600 px-4 py-2">{{ test.id }}</td>
                        <td class="border border-gray-600 px-4 py-2">{{ test.name }}</td>
                        <td class="border border-gray-600 px-4 py-2">{{ test.description || 'N/A' }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminAuthenticatedLayout>
</template>


<script setup>
import AdminAuthenticatedLayout from '@/Layouts/Admin/AdminAuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed } from "vue";

const searchQuery = ref("");

const form = useForm({
    name: "",
    description: "",
});

const props = defineProps({
    testImage: {
        type: Array,
        default: () => [],
    },
});

let testImageId;

const selectTest = (test) => {
    testImageId = test.id;
    form.name = test.name;
    form.description = test.description;
};

const saveTest = () => {
    form.post(route('imageTest.store'), {
        onSuccess: () => {
            testImageId = null;
            alert("Hotel saved successfully!");
            form.reset();
        },
        onError: (errors) => {
            console.log("Save errors:", errors);
        },
    });
};

const updateTest = () => {
    if (testImageId == null) {
        alert("click table what do you need to update")
    } else {
        form.post(route('imageTest.update', testImageId),  {
            onSuccess: () => {
                alert("Hotel Updated Successfully");
            },
            onError: (errors) => {
                if (errors.available_rooms) {
                    form.errors.available_rooms = errors.available_rooms;
                }
                if (errors.type) {
                    form.errors.type = errors.type;
                }
                console.log("Error updating hotel:", errors);
            },
        });
    }
};

const filteredHotels = computed(() => {
    return props.testImage.filter(
        hotel => hotel.name.toLowerCase().includes(searchQuery.value.toLowerCase()) || (
        hotel.description && hotel.description.toLowerCase().includes(searchQuery.value.toLowerCase()))
    );
});

</script>

