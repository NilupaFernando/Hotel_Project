<template>
    <Head title="District" />
    <AdminAuthenticatedLayout>
        <!-- <template #header>
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"> -->
                <!-- District Header -->
                <!-- <h2 class="text-3xl font-bold text-orange-500">
                    District Information
                </h2> -->

                <!-- Add New District Section -->
                <!-- <div class="text-xl font-semibold leading-tight text-gray-800">
                    <button
                        @click="addNew"
                        class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400"
                    >
                        Add New
                    </button>
                </div>
            </div>
        </template> -->

        <div class="bg-gray-900 min-h-screen p-4 md:p-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 ">
            <div class="py-5">
                <ExploreSriLanka
                title="District Informations"
                    :categories="districts"
                    :showViewAll="true"
                    :user="user"
                    :admin="true"
                    class="w-full"
                />
            </div>
        </div>
        </div>


    </AdminAuthenticatedLayout>
</template>

<script setup>
import AdminAuthenticatedLayout from '@/Layouts/Admin/AdminAuthenticatedLayout.vue';
import {Head, Link, router} from '@inertiajs/vue3';
import ExploreSriLanka from '@/Components/ExploreSriLanka.vue';

const props = defineProps({
    districts: Array,
    user: Object,
});

const addNew = () => {
    router.get(route('district.create'));
};
</script>

