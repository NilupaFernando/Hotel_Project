<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
</script>

<template>
    <div class="w-full min-h-screen bg-gray-100 sm:pt-0">
        <div class="mb-6 flex justify-center">
            <Link :href="route(page.props.auth?.user?.role === 'user' ? 'user.dashboard' : 'index')">
                <ApplicationLogo class="h-20 w-20 fill-current text-gray-500" />
            </Link>
        </div>

        <div>
            <slot />
        </div>
    </div>
</template>
