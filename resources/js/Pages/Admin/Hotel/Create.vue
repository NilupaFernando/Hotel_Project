<template>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-3xl md:text-4xl font-extrabold text-orange-500 tracking-tight">
                    List Your Property
                </h1>
                <p class="mt-3 text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                    Showcase your property to millions of travelers with a detailed and professional listing
                </p>
            </div>

            <form @submit.prevent="submit" class="space-y-8">
                <!-- Account Information Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center mb-6">
                        <div class="p-2 bg-orange-100 dark:bg-orange-900 rounded-lg mr-3">
                            <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Account Details</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Full Name -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Full Name</label>
                            <input v-model="form.nameAccount" type="text" required
                                   @input="validateFullName()"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all" />
                            <p class="text-red-500 text-sm" v-if="form.errors.nameAccount">{{ form.errors.nameAccount }}</p>
                        </div>

                        <!-- Email Address -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Address</label>
                            <input v-model="form.emailAccount" type="email" required
                                   class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all" />
                            <p class="text-red-500 text-sm" v-if="form.errors.emailAccount">{{ form.errors.emailAccount }}</p>
                        </div>

                        <!-- Password -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                            <input v-model="form.password" type="password" required
                                   class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all" />
                            <p class="text-red-500 text-sm" v-if="form.errors.password">{{ form.errors.password }}</p>
                        </div>

                        <!-- Confirm Password -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm Password</label>
                            <input v-model="form.password_confirmation" type="password" required
                                   class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all" />
                            <p class="text-red-500 text-sm" v-if="form.errors.password_confirmation">{{ form.errors.password_confirmation }}</p>
                        </div>

                        <!-- Registration Number -->
                        <div class="space-y-2 md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Registration Number</label>
                            <input v-model="form.registration_number" type="text" required
                                   @input="validateRegistrationNumber()"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all" />
                            <p class="text-red-500 text-sm" v-if="form.errors.registration_number">{{ form.errors.registration_number }}</p>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Business Registration Image</label>
                            <input type="file" @change="handleImageUploadAndValidate" accept="image/*"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 file:bg-gray-50 dark:file:bg-gray-900 file:border-0 file:rounded-lg file:px-4 file:py-2 file:text-orange-700 hover:file:bg-orange-100 dark:hover:file:bg-orange-800 transition-all" />
                            <p class="text-red-500 text-sm" v-if="form.errors.imageUpload">{{ form.errors.imageUpload }}</p>
                        </div>
                        <div v-if="imagePreview" class="flex items-center justify-center">
                            <img :src="imagePreview" alt="Preview" class="h-24 w-24 object-cover rounded-lg shadow-md" />
                        </div>
                    </div>
                </div>

                <!-- Property Information Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center mb-6">
                        <div class="p-2 bg-orange-100 dark:bg-orange-900 rounded-lg mr-3">
                            <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h-14"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Property Information</h2>
                    </div>

                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Property Name -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Property Name</label>
                                <input v-model="form.name" type="text" required
                                       @input="validatePropertyName()"
                                       class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all" />
                                <p class="text-red-500 text-sm" v-if="form.errors.name">{{ form.errors.name }}</p>
                            </div>

                            <!-- Accommodation Type -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Accommodation Type</label>
                                <select v-model="form.accommodation_type" required
                                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                                    <option value="">Select Accommodation Type</option>
                                    <option v-for="(category, index) in categories" :key="index" :value="category">
                                        {{ category }}
                                    </option>
                                </select>
                                <p class="text-red-500 text-sm" v-if="form.errors.accommodation_type">{{ form.errors.accommodation_type }}</p>
                            </div>
                        </div>

                        <!-- Conditional Fields for Villa -->
                        <div v-if="['Bunglow', 'Pavilion', 'Cabana'].includes(form.accommodation_type)" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Max Adults</label>
                                <input v-model="form.max_adults" type="number" min="1" required
                                       class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm" />
                            </div>
                            <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Max Children</label>
                                <input v-model="form.max_children" type="number" min="0" required
                                       class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm" />
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                            <textarea v-model="form.description" rows="4"
                                      class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all"></textarea>
                        </div>

                        <div class="space-y-4 mb-10">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">State Category</label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <label v-for="state in stateCategories" :key="state" class="flex items-center space-x-2 cursor-pointer">
                                    <input type="checkbox" v-model="form.category" :value="state"
                                           class="w-5 h-5 text-orange-600 bg-gray-600 dark:text-orange-400 border-gray-300 dark:border-gray-600 rounded focus:ring-orange-500" />
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ state }}</span>
                                </label>
                            </div>
                            <p class="text-red-500 text-sm" v-if="form.errors.category">{{ form.errors.category }}</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Star Rating -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Star Rating</label>
                                <input v-model="form.star" type="number" step="0.1" min="0" max="5"
                                       class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all" />
                                <p class="text-red-500 text-sm" v-if="form.errors.star">{{ form.errors.star }}</p>
                            </div>

                            <!-- Price per Night -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price per Night ($)</label>
                                <input v-model="form.price_per_night" type="number" step="0.01" min="0" required
                                       class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all" />
                                <p class="text-red-500 text-sm" v-if="form.errors.price_per_night">{{ form.errors.price_per_night }}</p>
                            </div>

                            <!-- Cover Photo -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cover Photo</label>
                                <input type="file" @change="handleThumbImage" accept="image/*"
                                       class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 file:bg-gray-50 dark:file:bg-gray-900 file:border-0 file:rounded-lg file:px-4 file:py-2 file:text-orange-700 hover:file:bg-orange-100 dark:hover:file:bg-orange-800 transition-all" />
                                <p class="text-red-500 text-sm" v-if="form.errors.thumbnail_image">{{ form.errors.thumbnail_image }}</p>
                            </div>
                        </div>

                        <div v-if="imagePreviewThumb" class="flex justify-center">
                            <img :src="imagePreviewThumb" alt="Thumbnail Preview" class="h-32 w-32 object-cover rounded-lg shadow-md" />
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Additional Photos (Max 8)</label>
                            <input type="file" @change="handleImageChangetest" accept="image/*"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 file:bg-gray-50 dark:file:bg-gray-900 file:border-0 file:rounded-lg file:px-4 file:py-2 file:text-orange-700 hover:file:bg-orange-100 dark:hover:file:bg-orange-800 transition-all" />
                            <p class="text-red-500 text-sm" v-if="form.errors.images">{{ form.errors.images }}</p>
                            <div class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-4">
                                <div v-for="(img, index) in form.images" :key="index" class="relative group">
                                    <img :src="typeof img === 'string' ? `/storage/${img}` : img.preview"
                                         class="w-full h-24 object-cover rounded-lg shadow-md" />
                                    <button type="button" @click="removeImage(index)"
                                            class="absolute top-2 right-2 bg-red-500 text-white p-1.5 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                                        ×
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Location & Contact Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center mb-6">
                        <div class="p-2 bg-orange-100 dark:bg-orange-900 rounded-lg mr-3">
                            <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Location & Contact</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- District -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">District</label>
                            <select v-model="form.district_id" required
                                    class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                                <option value="">Select a District</option>
                                <option v-for="district in filterValueDistrict" :key="district.id" :value="district.id">
                                    {{ district.name }}
                                </option>
                            </select>
                            <p class="text-red-500 text-sm" v-if="form.errors.district_id">{{ form.errors.district_id }}</p>
                        </div>

                        <!-- Province -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Province</label>
                            <input v-model="form.province_name" type="text" placeholder="Enter province"
                                   @input="validateProvinceName()"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all" />
                            <p class="text-red-500 text-sm" v-if="form.errors.province_name">{{ form.errors.province_name }}</p>
                        </div>

                        <!-- Latitude -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Latitude</label>
                            <input v-model="form.latitude" type="text"
                                   @input="validateLatitude()"
                                   placeholder="6.9271"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all" />
                            <p class="text-red-500 text-sm" v-if="form.errors.latitude">{{ form.errors.latitude }}</p>
                        </div>

                        <!-- Longitude -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Longitude</label>
                            <input v-model="form.longitude" type="text"
                                   @input="validateLongitude()"
                                   placeholder="79.8612"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all" />
                            <p class="text-red-500 text-sm" v-if="form.errors.longitude">{{ form.errors.longitude }}</p>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
                            <input v-model="form.location" type="text"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all" />
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contact Number</label>
                            <input v-model="form.contact_number" type="text" required
                                   @input="validateContactNumber()"
                                   placeholder="0712345678"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all" />
                            <p class="text-red-500 text-sm" v-if="form.errors.contact_number">{{ form.errors.contact_number }}</p>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                            <input v-model="form.email" type="email" required
                                   @input="validateContactEmail()"
                                   placeholder="example@email.com"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all" />
                            <p class="text-red-500 text-sm" v-if="form.errors.email">{{ form.errors.email }}</p>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Website</label>
                            <input v-model="form.website" type="url"
                                   @input="validateWebsite()"
                                   placeholder="https://example.com"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all" />
                            <p class="text-red-500 text-sm" v-if="form.errors.website">{{ form.errors.website }}</p>
                        </div>
                    </div>
                </div>

                <!-- Amenities & Details Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center mb-6">
                        <div class="p-2 bg-orange-100 dark:bg-orange-900 rounded-lg mr-3">
                            <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Amenities & Details</h2>
                    </div>

                    <div class="space-y-6">
                        <div class="space-y-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amenities</label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <label v-for="amenity in amenities" :key="amenity" class="flex items-center space-x-2 cursor-pointer">
                                    <input type="checkbox" v-model="form.amenities" :value="amenity"
                                           class="w-5 h-5 text-orange-600  bg-gray-600 dark:text-orange-400 border-gray-300 dark:border-gray-600 rounded focus:ring-orange-500" />
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ amenity }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Check-in Time</label>
                                <input v-model="form.check_in_time" type="time"
                                       @input="validateCheckInOutTime()"
                                       class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all" />
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Check-out Time</label>
                                <input v-model="form.check_out_time" type="time"
                                       @input="validateCheckInOutTime()"
                                       class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all" />
                            </div>
                        </div>
                        <p class="text-red-500 text-sm" v-if="form.errors.check_in_out_time">{{ form.errors.check_in_out_time }}</p>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end pt-4">
                    <button type="submit"
                            @click="saveHotel"
                            class="px-8 py-3 bg-orange-600 text-white font-semibold rounded-lg hover:bg-orange-700 focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="form.processing">
                        {{ form.processing ? 'Saving...' : 'Add Property' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
// Validate Property Name (no numbers or special characters)
const validatePropertyName = () => {
    form.errors = form.errors || {};
    const name = form.name ? form.name.trim() : '';
    if (!name) {
        form.errors.name = 'Property Name is required.';
        return false;
    }
    if (/[^a-zA-Z\s]/.test(name)) {
        form.errors.name = 'Property Name cannot contain numbers or special characters.';
        return false;
    }
    delete form.errors.name;
    return true;
};

// Validate Website (must be a valid URL or empty)
const validateWebsite = () => {
    form.errors = form.errors || {};
    const website = form.website ? form.website.trim() : '';
    if (!website) {
        delete form.errors.website;
        return true;
    }
    // Accepts http(s) URLs only
    const urlRegex = /^(https?:\/\/)([\w-]+\.)+[\w-]{2,}(\/\S*)?$/i;
    if (!urlRegex.test(website)) {
        form.errors.website = 'Enter a valid website URL (must start with http:// or https://).';
        return false;
    }
    delete form.errors.website;
    return true;
};

//Validate Check-in and Check-out Time (not same, check-out after check-in)
const validateCheckInOutTime = () => {
    form.errors = form.errors || {};
    const checkIn = form.check_in_time ? form.check_in_time.trim() : '';
    const checkOut = form.check_out_time ? form.check_out_time.trim() : '';
    if (!checkIn || !checkOut) {
        delete form.errors.check_in_out_time;
        return true;
    }
    if (checkIn === checkOut) {
        form.errors.check_in_out_time = 'Check-in and check-out times cannot be the same.';
        return false;
    }
    // Compare times (HH:mm)
    const [inH, inM] = checkIn.split(':').map(Number);
    const [outH, outM] = checkOut.split(':').map(Number);
    const inMinutes = inH * 60 + inM;
    const outMinutes = outH * 60 + outM;
    if (outMinutes <= inMinutes) {
        form.errors.check_in_out_time = 'Check-out time must be after check-in time.';
        return false;
    }
    delete form.errors.check_in_out_time;
    return true;
};

// Validate Full Name in real-time and before submit
const validateFullName = () => {
    form.errors = form.errors || {};
    const name = form.nameAccount ? form.nameAccount.trim() : '';
    if (!name) {
        form.errors.nameAccount = 'Full Name is required.';
        return false;
    }
    if (name.length < 3) {
        form.errors.nameAccount = 'Full Name must be at least 3 characters long.';
        return false;
    }
    if (/\d/.test(name)) {
        form.errors.nameAccount = 'Full Name cannot contain numbers.';
        return false;
    }
    delete form.errors.nameAccount;
    return true;
};

// Validate Registration Number in real-time and before submit
const validateRegistrationNumber = () => {
    form.errors = form.errors || {};
    const reg = form.registration_number ? form.registration_number.trim() : '';
    if (!reg) {
        form.errors.registration_number = 'Registration Number is required.';
        return false;
    }
    if (reg.length < 5) {
        form.errors.registration_number = 'Registration Number must be at least 5 characters long.';
        return false;
    }
    delete form.errors.registration_number;
    return true;
};

// Validate Business Registration Image in real-time and before submit
const validateImageUpload = () => {
    form.errors = form.errors || {};
    if (!form.image) {
        form.errors.imageUpload = 'Business Registration Image is required.';
        return false;
    }
    delete form.errors.imageUpload;
    return true;
};

// Validate Contact Number (10-15 digits, can start with +, no letters)
const validateContactNumber = () => {
    form.errors = form.errors || {};
    const number = form.contact_number ? form.contact_number.trim() : '';
    if (!number) {
        form.errors.contact_number = 'Contact number is required.';
        return false;
    }
    // Accepts numbers like 0712345678, +94712345678, 94712345678
    const phoneRegex = /^(\+?\d{10,15})$/;
    if (!phoneRegex.test(number)) {
        form.errors.contact_number = 'Enter a valid contact number (10-15 digits, numbers only).';
        return false;
    }
    delete form.errors.contact_number;
    return true;
};

// Validate Contact Email (standard email format)
const validateContactEmail = () => {
    form.errors = form.errors || {};
    const email = form.email ? form.email.trim() : '';
    if (!email) {
        form.errors.email = 'Email is required.';
        return false;
    }
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        form.errors.email = 'Enter a valid email address.';
        return false;
    }
    delete form.errors.email;
    return true;
};

const imagePreview = ref(null);

const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.image = file;
        // Optional: show preview
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    } else {
        form.image = null;
        imagePreview.value = null;
    }
};

const handleImageUploadAndValidate = (event) => {
    handleImageUpload(event);
    validateImageUpload();
};

// Validate Province Name (no numbers or special characters)
const validateProvinceName = () => {
    form.errors = form.errors || {};
    const province = form.province_name ? form.province_name.trim() : '';
    if (!province) {
        form.errors.province_name = 'Province is required.';
        return false;
    }
    if (/[^a-zA-Z\s]/.test(province)) {
        form.errors.province_name = 'Province cannot contain numbers or special characters.';
        return false;
    }
    delete form.errors.province_name;
    return true;
};

// Validate Latitude (decimal degrees, -90 to 90)
const validateLatitude = () => {
    form.errors = form.errors || {};
    const lat = form.latitude ? form.latitude.trim() : '';
    if (!lat) {
        form.errors.latitude = 'Latitude is required.';
        return false;
    }
    // Regex for valid latitude: -90 to 90 with optional decimals
    const latRegex = /^-?([1-8]?\d(\.\d+)?|90(\.0+)?)$/;
    if (!latRegex.test(lat)) {
        form.errors.latitude = 'Latitude must be a valid number between -90 and 90.';
        return false;
    }
    delete form.errors.latitude;
    return true;
};

// Validate Longitude (decimal degrees, -180 to 180)
const validateLongitude = () => {
    form.errors = form.errors || {};
    const lng = form.longitude ? form.longitude.trim() : '';
    if (!lng) {
        form.errors.longitude = 'Longitude is required.';
        return false;
    }
    // Regex for valid longitude: -180 to 180 with optional decimals
    const lngRegex = /^-?(1[0-7]\d(\.\d+)?|0?\d{1,2}(\.\d+)?|180(\.0+)?)$/;
    if (!lngRegex.test(lng)) {
        form.errors.longitude = 'Longitude must be a valid number between -180 and 180.';
        return false;
    }
    delete form.errors.longitude;
    return true;
};
import AdminAuthenticatedLayout from '@/Layouts/Admin/AdminAuthenticatedLayout.vue';
import {Head, router, useForm} from '@inertiajs/vue3';
import { Inertia } from "@inertiajs/inertia";
import {computed, ref, watch} from "vue";
import Swal from 'sweetalert2';


const allowMultipleSelectionHotel = ref(true);
const allowMultipleSelectionAmenities = ref(true);
const MultipleCatogoryCreatPart = ref(true);
const imagePreviewThumb = ref(null); // Holds the preview URL

const props = defineProps({
    districts: {
        type: Array,
    },
});
const amenities = [
    "Free Wi-Fi","Air Conditioning","Swimming Pool","Gym","Restaurant","Spa","Room Service","Free Parking","Bar","Concierge","Laundry Service",
    "24-Hour Front Desk","Elevator","Pet-Friendly","Shuttle Service","Mini Bar","Jacuzzi","Sauna",
    "Housekeeping","Smoke-Free Rooms","Non-Smoking Rooms","Child Care","Complimentary Breakfast"];
const categories = [
    "Hotel", "Resort","Guesthouse","Villa","Bunglow","Cabana","Pavilion","Cottage",
    "Hostel","Apartment","Homestay","Motel","Chalet","Business Hotel","Extended Stay Hotel"
];
const roomTypes = [
    "Single Room","Double Room","Twin Room","Triple Room","Quad Room","Suite","Junior Suite","Family Room", "Studio",
    "Penthouse","Deluxe Room","Superior Room","Accessible Room","Bunk Room","Connecting Rooms",
];
const stateCategories = [
    "Beach", "Forest", "Historical Sites", "Mountain", "Lakes",
    "Tourist Activities", "Wildlife",
];

const form = useForm({
    nameAccount: '',
    emailAccount: '',
    password: '',
    password_confirmation: '',
    registration_number: '',
    image: '',

    name: "",
    description: "",
    location: "",
    province_name: "",
    district_id: "",
    accommodation_type: "",
    latitude: "",
    longitude: "",
    star: "",
    price_per_night: "",
    amenities: [],
    room_types: [],
    check_in_time: "14:00",
    check_out_time: "11:00",
    contact_number: "",
    email: "",
    website: "",
    images: [],
    category: [],
    thumbnail_image: null,

    photo: [],
    removed_images: []
});

const filterValueDistrict = computed(() => {
    console.log("Filtering for province:", form.province_name);
    return props.districts.filter(district => {
        if (form.name && form.name === district.name) {
            return true;  }
        return true;
    });
});

watch(() => form.district_id, (newDistrictId) => {
    if (!newDistrictId) return;
    const selectedDistrict = props.districts.find(d => d.id === newDistrictId);
    if (selectedDistrict && selectedDistrict.province?.province_name) {
        form.province_name = selectedDistrict.province.province_name;
        console.log("Updated province_name:", form.province_name);
    }
});

const handleThumbImage = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.thumbnail_image = file;

        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreviewThumb.value = e.target.result; // Set the preview URL
        };
        reader.readAsDataURL(file);
    }
};


let arrayImage = ref([])
let removedImages = ref([]);

const handleImageChangetest = (event) => {
    if (form.images.length < 8) {
        const file = event.target.files[0];
        if (file) {
            const newImage = {
                file,
                preview: URL.createObjectURL(file),
            };
            form.images.push(newImage);
        }
    } else {
        alert("only you can upload 8 images");
    }
};

const removeImage = (index) => {
    if (form.images[index].url) {
        removedImages.value.push(form.images[index].url);
    }
    form.images.splice(index, 1);
};

const validateForm = () => {
    form.errors = {}; // Clear previous errors

    // Validate Full Name
        if (!validateFullName()) return false;

    // Validate Email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!form.emailAccount) {
        form.errors.emailAccount = "Email Address is required.";
    } else if (!emailRegex.test(form.emailAccount)) {
        form.errors.emailAccount = "Invalid email format.";
    }

    // Validate Password
    if (!form.password) {
        form.errors.password = "Password is required.";
    } else if (form.password.length < 8) {
        form.errors.password = "Password must be at least 8 characters long.";
    } else if (!/[A-Z]/.test(form.password)) {
        form.errors.password = "Password must contain at least one uppercase letter.";
    } else if (!/[a-z]/.test(form.password)) {
        form.errors.password = "Password must contain at least one lowercase letter.";
    } else if (!/[0-9]/.test(form.password)) {
        form.errors.password = "Password must contain at least one number.";
    } else if (!/[!@#$%^&*]/.test(form.password)) {
        form.errors.password = "Password must contain at least one special character.";
    }

    // Validate Password Confirmation
    if (!form.password_confirmation) {
        form.errors.password_confirmation = "Confirm Password is required.";
    } else if (form.password !== form.password_confirmation) {
        form.errors.password_confirmation = "Passwords do not match.";
    }

    // Validate Registration Number
    if (!validateRegistrationNumber()) return false;

    // Validate Business Registration Image
    if (!validateImageUpload()) return false;

    // Validate Property Name
    if (!validatePropertyName()) return false;

    // Validate Accommodation Type
    if (!form.accommodation_type) form.errors.accommodation_type = "Accommodation Type is required.";

    // Validate District
    if (!form.district_id) form.errors.district_id = "District is required.";

    // Validate Province
    if (!validateProvinceName()) return false;


    // Validate Amenities
    if (!form.amenities.length) form.errors.amenities = "At least one amenity must be selected.";

    // Validate State Category
    if (!form.category || !form.category.length) {
        form.errors.category = "At least one state category must be selected.";
    }

    // Validate Additional Photos
    if (!form.images || form.images.length === 0) {
        form.errors.images = "At least one additional photo is required.";
    }


    // Validate Contact Number
    if (!validateContactNumber()) return false;

    // Validate Contact Email
    if (!validateContactEmail()) return false;



    // Validate Check-in and Check-out Time
    if (!validateCheckInOutTime()) return false;

    // Validate Website
    if (!validateWebsite()) return false;

    // Validate Latitude and Longitude
    if (!validateLatitude()) return false;
    if (!validateLongitude()) return false;

    // Validate Price per Night
    if (!form.price_per_night || form.price_per_night <= 0) {
        form.errors.price_per_night = "Price per night must be greater than 0.";
    }

    // Validate Star Rating
    if (!form.star || form.star < 0 || form.star > 5) {
        form.errors.star = "Star rating must be between 0 and 5.";
    }

    // Validate Images
    if (!form.thumbnail_image) {
        form.errors.thumbnail_image = "Cover photo is required.";
    }
    if (form.images.length > 8) {
        form.errors.images = "You can upload a maximum of 8 images.";
    }

    return Object.keys(form.errors).length === 0; // Return true if no errors
};

const saveHotel = () => {
    if (!validateForm()) {
            console.log("Validation errors:", form.errors); 
        return;
    }

    form.photo = [];
    form.images.forEach((image) => {
        if (image.file) {
            form.photo.push(image.file);
        } else {
            form.photo.push(image.url); // Keep DB images
        }
    });

    form.removed_images = JSON.stringify(removedImages.value || []);

    form.post(route('hotel.store'), {
        onSuccess: () => {
            Swal.fire({
                title: "Registration Successful!",
                text: "Property has been successfully registered.",
                icon: "success",
                confirmButtonText: "OK",
                confirmButtonColor: "#ED9B40",
                iconColor: "#ED9B40"
            });
        },
        onError: (errors) => {
            Swal.fire({
                title: "Registration Failed",
                text: "Please check the form for errors and try again.",
                icon: "error",
                confirmButtonText: "Retry",
                confirmButtonColor: "#d33",
            });
            console.log("district Save errors:", errors);
        },
    });
};

</script>
