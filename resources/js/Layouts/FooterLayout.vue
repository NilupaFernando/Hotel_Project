<script setup lang="ts">
const currentYear = new Date().getFullYear();

const sections = [
    {
        title: 'Section',
        links: [
            { text: 'Home', url: '/' },
            { text: 'Features', url: '/features' },
            { text: 'Pricing', url: '/pricing' },
            { text: 'FAQs', url: '/faqs' },
            { text: 'About', url: '/about' },
        ]
    },
    {
        title: 'Section',
        links: [
            { text: 'Home', url: '/' },
            { text: 'Features', url: '/features' },
            { text: 'Pricing', url: '/pricing' },
            { text: 'FAQs', url: '/faqs' },
            { text: 'About', url: '/about' },
        ]
    },
    {
        title: 'Section',
        links: [
            { text: 'Home', url: '/' },
            { text: 'Features', url: '/features' },
            { text: 'Pricing', url: '/pricing' },
            { text: 'FAQs', url: '/faqs' },
            { text: 'About', url: '/about' },
        ]
    }
];
</script>

<template>
    <footer class="bg-gray-200 py-8 w-full">
        <div class="max-w-6xl mx-auto px-4 flex flex-col md:flex-row gap-8">
            <!-- Logo and Description -->
            <div class="flex-1">
                <img src="/imges/red_code_logo.png" alt="Logo" class="h-10 mb-4" />
                <p class="text-gray-700 text-sm leading-relaxed">
                    Monthly digest of what's new and exciting from us.
                </p>
            </div>

            <!-- Navigation Sections -->
            <div class="flex-1 grid grid-cols-1 sm:grid-cols-3 gap-8">
                <div v-for="(section, index) in sections" :key="index">
                    <h3 class="text-gray-900 text-lg font-semibold mb-3">{{ section.title }}</h3>
                    <ul class="space-y-2">
                        <li v-for="link in section.links" :key="link.text">
                            <a :href="link.url" class="text-gray-600 hover:text-blue-500 text-sm">{{ link.text }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <hr class="border-gray-400 my-6">

        <!-- Footer Bottom -->
        <div class="max-w-6xl mx-auto px-4 flex flex-col sm:flex-row justify-between items-center text-gray-700 text-sm">
            <p>&copy; {{ currentYear }} Company, Inc. All rights reserved.</p>
            <div class="flex space-x-6 mt-3 sm:mt-0">
                <a href="#" class="text-gray-600 hover:text-blue-500 text-xl">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="#" class="text-gray-600 hover:text-blue-500 text-xl">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="#" class="text-gray-600 hover:text-blue-500 text-xl">
                    <i class="fab fa-facebook"></i>
                </a>
            </div>
            <p class="mt-3 sm:mt-0">Powered by Red Code Solutions</p>
        </div>
    </footer>
</template>
