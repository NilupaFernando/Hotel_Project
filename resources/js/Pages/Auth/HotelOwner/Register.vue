<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from "vue";
import Swal from 'sweetalert2';

const props = defineProps({
    districts: Array,
    users: Object
});

const imagePreviewThumb = ref(null);
const imagePreview = ref(null);

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
    removed_images: [],
    errors: {},

    max_adults: "",
    max_children:""

});

const amenities = [
    "Free Wi-Fi","Air Conditioning","Swimming Pool","Gym","Restaurant","Spa","Room Service","Free Parking","Bar","Concierge","Laundry Service",
    "24-Hour Front Desk","Elevator","Pet-Friendly","Shuttle Service","Mini Bar","Jacuzzi","Sauna",
    "Housekeeping","Smoke-Free Rooms","Non-Smoking Rooms","Child Care","Complimentary Breakfast"];
const categories = [
    "Hotel", "Resort","Business Hotel","Extended Stay Hotel","Guesthouse","Villa","Bunglow","Cabana","Pavilion","Cottage",
    "Hostel","Apartment","Homestay","Motel","Chalet",
];
const roomTypes = [
    "Single Room","Double Room","Twin Room","Triple Room","Quad Room","Suite","Junior Suite","Family Room", "Studio",
    "Penthouse","Deluxe Room","Superior Room","Accessible Room","Bunk Room","Connecting Rooms",
];
const stateCategories = [
    "Beach", "Forest", "Historical Sites", "Mountain", "Lakes",
    "Tourist Activities", "Wildlife",
];

const filterValueDistrict = computed(() => {
    if (!props.districts) return [];

    console.log("Filtering for province:", form.province_name);
    return props.districts.filter(district => {
        if (form.name && form.name === district.name) {
            return true;
        }
        return true;
    });
});

watch(() => form.district_id, (newDistrictId) => {
    if (!newDistrictId || !props.districts) return;
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
            imagePreviewThumb.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.image = file;

        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

let arrayImage = ref([]);
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

const submit = () => {
    // Check-in and Check-out time validation before submit
    if (
        activeSection.value === 'amenities' &&
        form.check_in_time &&
        form.check_out_time &&
        form.check_in_time === form.check_out_time
    ) {
        form.errors.check_in_time = "Check-in and Check-out time cannot be the same.";
        form.errors.check_out_time = "Check-in and Check-out time cannot be the same.";
        Swal.fire({
            title: "Invalid Check-in/Check-out Time",
            text: "Check-in and Check-out time cannot be the same.",
            icon: "error",
            confirmButtonText: "OK",
            confirmButtonColor: "#ED9B40"
        });
        setTimeout(() => {
            const el = document.querySelector('input[type="time"][v-model="form.check_in_time"]') ||
                       document.querySelector('input[type="time"][v-model="form.check_out_time"]');
            if (el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 50);
        return;
    }

    // Amenities validation before submit
    if (
        activeSection.value === 'amenities' &&
        (!form.amenities || form.amenities.length === 0)
    ) {
        form.errors.amenities = "At least one amenity must be selected.";
        Swal.fire({
            title: "Amenities Required",
            text: "Please select at least one amenity before submitting.",
            icon: "error",
            confirmButtonText: "OK",
            confirmButtonColor: "#ED9B40"
        });
        setTimeout(() => {
            const el = document.querySelector('input[type="checkbox"][v-model="form.amenities"]');
            if (el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 50);
        return;
    } else {
        form.errors.amenities = "";
    }

    form.photo = [];
    form.images.forEach((image) => {
        if (image.file) {
            form.photo.push(image.file);
        } else {
            form.photo.push(image.url);
        }
    });

    form.post(route('hotel-owner.register'), {
        onSuccess: () => {
            Swal.fire({
                title: "Registration Successful!",
                text: "Your request has been successfully sent. Please wait until you receive a request confirmation email.",
                icon: "success",
                confirmButtonText: "OK",
                confirmButtonColor: "#ED9B40",
                iconColor: "#ED9B40"
            }).then(() => {
                // Redirect after user clicks OK
                if(props.users.id === null) {
                    window.location.href = route('index');
                } else {
                    window.location.href = route('user.dashboard');
                }
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

// Active section tracking for animations
const activeSection = ref('account');
// Real-time validation for Star Rating
watch(() => form.star, (newVal) => {
    if (newVal && !/^\d*$/.test(newVal)) {
        form.errors.star = "Star rating can only be a number between 0 and 5.";
    } else if (newVal && (parseInt(newVal) < 0 || parseInt(newVal) > 5)) {
        form.errors.star = "Star rating must be between 0 and 5.";
    } else {
        form.errors.star = "";
    }
});

// Real-time validation for Full Name
watch(() => form.nameAccount, (newVal) => {
    if (!newVal) {
        form.errors.nameAccount = "Full Name is required.";
    } else if (newVal.trim().length < 3) {
        form.errors.nameAccount = "Full Name must be at least 3 characters long.";
    } else if (!/^[A-Za-z\s]+$/.test(newVal)) {
        form.errors.nameAccount = "Full Name can only contain letters and spaces.";
    } else {
        form.errors.nameAccount = "";
    }
});

// Real-time validation for Registration Number
watch(() => form.registration_number, (newVal) => {
    if (!newVal) {
        form.errors.registration_number = "Registration Number is required.";
    } else if (newVal.trim().length < 5) {
        form.errors.registration_number = "Registration Number must be at least 6 characters long.";
    } else if (!/^[A-Za-z0-9]+$/.test(newVal)) {
        form.errors.registration_number = "Registration Number can only contain letters and numbers.";
    } else {
        form.errors.registration_number = "";
    }
});

// Real-time validation for Latitude
watch(() => form.latitude, (newVal) => {
    if (newVal && !/^\d*\.?\d*$/.test(newVal)) {
        form.errors.latitude = "Latitude can only contain numbers and a dot.";
    } else {
        form.errors.latitude = "";
    }
});

// Real-time validation for Longitude
watch(() => form.longitude, (newVal) => {
    if (newVal && !/^\d*\.?\d*$/.test(newVal)) {
        form.errors.longitude = "Longitude can only contain numbers and a dot.";
    } else {
        form.errors.longitude = "";
    }
});

// Real-time validation for Check-in and Check-out Time
watch([() => form.check_in_time, () => form.check_out_time], ([checkIn, checkOut]) => {
    if (checkIn && checkOut && checkIn === checkOut) {
        form.errors.check_in_time = "Check-in and Check-out time cannot be the same.";
        form.errors.check_out_time = "Check-in and Check-out time cannot be the same.";
    } else {
        form.errors.check_in_time = "";
        form.errors.check_out_time = "";
    }
});

const validateSection = () => {
    form.errors = {}; // Clear previous errors

    if (activeSection.value === 'account') {
        // Validate Full Name
        if (!form.nameAccount) {
            form.errors.nameAccount = "Full Name is required.";
        } else if (form.nameAccount.trim().length < 3) {
            form.errors.nameAccount = "Full Name must be at least 3 characters long.";
        } else if (!/^[A-Za-z\s]+$/.test(form.nameAccount)) {
            form.errors.nameAccount = "Full Name can only contain letters and spaces.";
        }

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

        // Real-time validation for Contact Number
        watch(() => form.contact_number, (newVal) => {
            // Accepts +94 XXXXXXXXXX or 0XXXXXXXXX or international format
            const phoneRegex = /^(\+?\d{1,3}[- ]?)?\d{9,12}$/;
            if (!newVal) {
                form.errors.contact_number = "Contact Number is required.";
            } else if (!phoneRegex.test(newVal.replace(/\s+/g, ""))) {
                form.errors.contact_number = "Invalid contact number format.";
            } else {
                form.errors.contact_number = "";
            }
        });
        // Validate Registration Number
        if (!form.registration_number) {
            form.errors.registration_number = "Registration Number is required.";
        } else if (form.registration_number.trim().length < 6) {
            form.errors.registration_number = "Registration Number must be at least 6 characters long.";
        } else if (!/^[A-Za-z0-9]+$/.test(form.registration_number)) {
            form.errors.registration_number = "Registration Number can only contain letters and numbers.";
        }

        // Validate Business Registration Image
        if (!form.image) {
            form.errors.image = "Business Registration Image is required.";
        }
    }

    // Property section validations
    else if (activeSection.value === 'property') {
        if (!form.name) form.errors.name = "Property Name is required.";
        if (!form.accommodation_type) form.errors.accommodation_type = "Accommodation Type is required.";
        // State Category required
        if (!form.category || form.category.length === 0) {
            form.errors.category = "State Category is required.";
        }
        // Price per night required
        if (!form.price_per_night) {
            form.errors.price_per_night = "Price per Night is required.";
        }
        // Cover photo required
        if (!form.thumbnail_image) {
            form.errors.thumbnail_image = "Cover Photo is required.";
        }
        // Additional photos required
        if (!form.images || form.images.length === 0) {
            form.errors.images = "At least one additional photo is required.";
        }
        // Validate Star Rating
        if (form.star && (!/^\d+$/.test(form.star) || parseInt(form.star) < 0 || parseInt(form.star) > 5)) {
            form.errors.star = "Star rating must be a number between 0 and 5.";
        }
    } else if (activeSection.value === 'location') {
        if (!form.district_id) form.errors.district_id = "District is required.";
        if (!form.province_name) form.errors.province_name = "Province is required.";
        if (!form.latitude) {
            form.errors.latitude = "Latitude is required.";
        } else if (!/^\d*\.?\d*$/.test(form.latitude)) {
            form.errors.latitude = "Latitude can only contain numbers and a dot.";
        }
        if (!form.longitude) {
            form.errors.longitude = "Longitude is required.";
        } else if (!/^\d*\.?\d*$/.test(form.longitude)) {
            form.errors.longitude = "Longitude can only contain numbers and a dot.";
        }
        // Email validation for contact section
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!form.email) {
            form.errors.email = "Email is required.";
        } else if (!emailRegex.test(form.email)) {
            form.errors.email = "Invalid email format.";
        }
    } else if (activeSection.value === 'amenities') {
        if (!form.amenities.length) form.errors.amenities = "At least one amenity must be selected.";
        // Check-in and Check-out time should not be the same
        if (form.check_in_time && form.check_out_time && form.check_in_time === form.check_out_time) {
            form.errors.check_in_time = "Check-in and Check-out time cannot be the same.";
            form.errors.check_out_time = "Check-in and Check-out time cannot be the same.";
        }
    }

    return Object.keys(form.errors).length === 0; // Return true if no errors
};

const nextSection = () => {
    if (validateSection()) {
        activeSection.value = activeSection.value === 'account' ? 'property' :
            activeSection.value === 'property' ? 'location' :
                activeSection.value === 'location' ? 'amenities' : 'amenities';
    }
};
</script>

<template>
    <div class="min-h-screen bg-gradient-to-b from-orange-50 to-white py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">
            <!-- Header with animation -->
            <div class="text-center mb-16">

                <h1 class="text-3xl md:text-4xl font-sans font-bold text-transparent bg-clip-text bg-gradient-to-r from-orange-400 via-yellow-400 to-orange-600 tracking-wide">
                    List Your Property
                </h1>
                <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto animate-fade-in">
                    Showcase your property to travelers with a detailed and professional listing
                </p>
            </div>

            <!-- Navigation tabs -->
            <div class="flex flex-wrap justify-center mb-8 gap-2">
                <button @click="() => { if (activeSection !== 'account' && validateSection()) activeSection = 'account'; }" :class="[
          'px-4 py-2 rounded-full text-sm font-medium transition-all duration-300',
          activeSection === 'account'
            ? 'bg-orange-500 text-white shadow-md transform scale-105'
            : 'bg-white text-gray-600 hover:bg-orange-100'
        ]">
                    Account Details
                </button>
                <button @click="() => { if (activeSection !== 'property' && validateSection()) activeSection = 'property'; }" :class="[
          'px-4 py-2 rounded-full text-sm font-medium transition-all duration-300',
          activeSection === 'property'
            ? 'bg-orange-500 text-white shadow-md transform scale-105'
            : 'bg-white text-gray-600 hover:bg-orange-100'
        ]">
                    Property Info
                </button>
                <button @click="() => { if (activeSection !== 'location' && validateSection()) activeSection = 'location'; }" :class="[
          'px-4 py-2 rounded-full text-sm font-medium transition-all duration-300',
          activeSection === 'location'
            ? 'bg-orange-500 text-white shadow-md transform scale-105'
            : 'bg-white text-gray-600 hover:bg-orange-100'
        ]">
                    Location & Contact
                </button>
                <button @click="() => { if (activeSection !== 'amenities' && validateSection()) activeSection = 'amenities'; }" :class="[
          'px-4 py-2 rounded-full text-sm font-medium transition-all duration-300',
          activeSection === 'amenities'
            ? 'bg-orange-500 text-white shadow-md transform scale-105'
            : 'bg-white text-gray-600 hover:bg-orange-100'
        ]">
                    Amenities & Details
                </button>
            </div>

            <form @submit.prevent="submit" class="space-y-8">
                <!-- Account Information Card -->
          <div v-show="activeSection === 'account'"
     class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100 transition-all duration-500 transform">
    <div class="flex items-center mb-6">
        <div class="p-3 bg-gradient-to-br from-orange-400 to-orange-600 rounded-lg mr-4 text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900">Account Details</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
            <label class="block text-sm font-medium text-gray-700">Full Name</label>
            <input v-model="form.nameAccount" type="text" required
                   class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm" 
                   @input="() => {
                        // Only allow letters and spaces, min 3 letters
                        if (!form.nameAccount) {
                            form.errors.nameAccount = 'Full Name is required.';
                        } else if (form.nameAccount.trim().length < 3) {
                            form.errors.nameAccount = 'Full Name must be at least 3 letters.';
                        } else if (!/^[A-Za-z\s]+$/.test(form.nameAccount)) {
                            form.errors.nameAccount = 'Full Name can only contain letters and spaces.';
                        } else {
                            form.errors.nameAccount = '';
                        }
                   }"
            />
            <p class="text-red-500 text-sm" v-if="form.errors.nameAccount">{{ form.errors.nameAccount }}</p>
        </div>
        <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
            <label class="block text-sm font-medium text-gray-700">Email Address</label>
            <input v-model="form.emailAccount" type="email" required
                   class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm" />
            <p class="text-red-500 text-sm" v-if="form.errors.emailAccount">{{ form.errors.emailAccount }}</p>
        </div>
        <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
            <label class="block text-sm font-medium text-gray-700">Password</label>
            <input v-model="form.password" type="password" required
                   class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm" />
            <p class="text-red-500 text-sm" v-if="form.errors.password">{{ form.errors.password }}</p>
        </div>
        <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
            <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
            <input v-model="form.password_confirmation" type="password" required
                   class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm" />
            <p class="text-red-500 text-sm" v-if="form.errors.password_confirmation">{{ form.errors.password_confirmation }}</p>
        </div>
        <div class="space-y-2 md:col-span-2 transform transition-all duration-300 hover:translate-y-[-4px]">
            <label class="block text-sm font-medium text-gray-700">Registration Number</label>
            <input v-model="form.registration_number" type="text" required
                   class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm" />
            <p class="text-red-500 text-sm" v-if="form.errors.registration_number">{{ form.errors.registration_number }}</p>
        </div>
        <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
            <label class="block text-sm font-medium text-gray-700">Business Registration Image</label>
            <div class="relative">
                <input type="file" @change="handleImageUpload" accept="image/*"
                   
                       class="w-full px-4 py-3 rounded-lg border border-gray-200 file:bg-orange-50 file:border-0 file:rounded-lg file:px-4 file:py-2 file:text-orange-700 hover:file:bg-orange-100 transition-all shadow-sm" />
                <div v-if="!imagePreview"
                     class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-0 hover:opacity-100 transition-opacity">
                    <span class="bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded">Click to upload</span>
                </div>
            </div>
            <p class="text-red-500 text-sm" v-if="form.errors.image">{{ form.errors.image }}</p>
        </div>
        <div v-if="imagePreview" class="flex items-center justify-center">
            <div class="relative group">
                <img :src="imagePreview" alt="Preview"
                     class="h-24 w-24 object-cover rounded-lg shadow-md transition-all duration-300 group-hover:shadow-lg" />
                <div
                    class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 rounded-lg flex items-center justify-center">
                    <button type="button" @click="imagePreview = null"
                            class="opacity-0 group-hover:opacity-100 transition-opacity bg-red-500 text-white p-1 rounded-full">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


                <!-- Property Information Card -->
                <div v-show="activeSection === 'property'"
                     class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100 transition-all duration-500 transform">
                    <div class="flex items-center mb-6">
                        <div class="p-3 bg-gradient-to-br from-orange-400 to-orange-600 rounded-lg mr-4 text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h-14" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Property Information</h2>
                    </div>

                    <div class="space-y-6">
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
                                    <label class="block text-sm font-medium text-gray-700">Property Name</label>
                     <input v-model="form.name" type="text" required
                         placeholder="Ocean View Resort"
                         class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm" />
                                    <p class="text-red-500 text-sm" v-if="form.errors.name">{{ form.errors.name }}</p>
                                </div>
                                <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
                                    <label class="block text-sm font-medium text-gray-700">Accommodation Type</label>
                                    <select v-model="form.accommodation_type"
                                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm appearance-none bg-white">
                                        <option value="" disabled>Select accommodation type</option>
                                        <option v-for="(category, index) in categories" :key="index" :value="category">
                                            {{ category }}
                                        </option>
                                    </select>
                                    <p class="text-red-500 text-sm" v-if="form.errors.accommodation_type">{{ form.errors.accommodation_type }}</p>
                                </div> 
                            </div>

                            <!-- Conditional Fields for Villa -->
                            <div v-if="['Apartment','Villa','Homestay','Chalet','Business Hotel','Homestay','Extended Stay Hotel'].includes(form.accommodation_type)" class="grid grid-cols-1 md:grid-cols-2 gap-6">                <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
                                <label class="block text-sm font-medium text-gray-700">Max Adults</label>
                    <input v-model="form.max_adults" type="number" min="1" required
                        placeholder="1"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm" />
                            </div>
                                <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
                                    <label class="block text-sm font-medium text-gray-700">Max Children</label>
                     <input v-model="form.max_children" type="number" min="0" required
                         placeholder="1"
                         class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm" />
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea v-model="form.description" rows="4"
                                      placeholder="A luxury beachfront property with modern amenities and stunning views."
                                      class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm"></textarea>
                        </div>

                        <div class="space-y-4 mb-10">
                            <label class="block text-sm font-medium text-gray-700">State Category</label>
                            <p class="text-red-500 text-sm" v-if="form.errors.category">{{ form.errors.category }}</p>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <label v-for="state in stateCategories" :key="state"
                                       class="flex items-center space-x-2 cursor-pointer transform transition-all duration-200 hover:translate-y-[-2px]">
                                    <input type="checkbox" v-model="form.category" :value="state"
                                           class="w-5 h-5 text-orange-600 border-gray-300 rounded focus:ring-orange-500" />
                                    <span class="text-sm text-gray-700">{{ state }}</span>
                                    
                                </label>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
                                <label class="block text-sm font-medium text-gray-700">Star Rating</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    </div>
                     <input v-model="form.star" type="number" step="1" min="0" max="5"
                         placeholder="4"
                         class="w-full pl-10 px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm" />
                                </div>
                            </div>
                            <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
                                <label class="block text-sm font-medium text-gray-700">Price per Night ($)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500">$</span>
                                    </div>
                     <input v-model="form.price_per_night" type="number" step="0.01" min="0" required
                         placeholder="1200.00"
                         class="w-full pl-8 px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm" />
                                </div>
                                <p class="text-red-500 text-sm" v-if="form.errors.price_per_night">{{ form.errors.price_per_night }}</p>

                            </div>
                            <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
                                <label class="block text-sm font-medium text-gray-700">Cover Photo</label>
                                <input type="file" @change="handleThumbImage" accept="image/*"
                                       class="w-full px-4 py-3 rounded-lg border border-gray-200 file:bg-orange-50 file:border-0 file:rounded-lg file:px-4 file:py-2 file:text-orange-700 hover:file:bg-orange-100 transition-all shadow-sm" />
                                <p class="text-red-500 text-sm" v-if="form.errors.thumbnail_image">{{ form.errors.thumbnail_image }}</p>
                            </div>
                        </div>

                        <div v-if="imagePreviewThumb" class="flex justify-center">
                            <div class="relative group">
                                <img :src="imagePreviewThumb" alt="Thumbnail Preview"
                                     class="h-32 w-32 object-cover rounded-lg shadow-md transition-all duration-300 group-hover:shadow-lg" />
                                <div
                                    class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 rounded-lg flex items-center justify-center">
                                    <button type="button" @click="imagePreviewThumb = null"
                                            class="opacity-0 group-hover:opacity-100 transition-opacity bg-red-500 text-white p-1 rounded-full">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Additional Photos (Max 8)</label>
                            <div class="flex items-center justify-center w-full">
                                <label
                                    class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-all duration-300">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-10 h-10 mb-3 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                        <p class="mb-2 text-sm text-gray-500">
                                            <span class="font-semibold">Click to upload</span> or drag and drop
                                        </p>
                                        <p class="text-xs text-gray-500">PNG, JPG or JPEG (MAX. 2MB)</p>
                                    </div>
                                    <input type="file" @change="handleImageChangetest" accept="image/*" class="hidden" />
                                </label>
                                <!-- Additional Photos error message -->
<p class="text-red-500 text-sm" v-if="form.errors.images">{{ form.errors.images }}</p>
                            </div>
                            <div class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-4">
                                <div v-for="(img, index) in form.images" :key="index" class="relative group">
                                    <img :src="typeof img === 'string' ? `/storage/${img}` : img.preview"
                                         class="w-full h-24 object-cover rounded-lg shadow-md transition-all duration-300 group-hover:shadow-lg" />
                                    <div
                                        class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 rounded-lg flex items-center justify-center">
                                        <button type="button" @click="removeImage(index)"
                                                class="opacity-0 group-hover:opacity-100 transition-opacity bg-red-500 text-white p-1.5 rounded-full">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Location & Contact Card -->
                <div v-show="activeSection === 'location'"
                     class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100 transition-all duration-500 transform">
                    <div class="flex items-center mb-6">
                        <div class="p-3 bg-gradient-to-br from-orange-400 to-orange-600 rounded-lg mr-4 text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Location & Contact</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
                            <label class="block text-sm font-medium text-gray-700">District</label>
                            <select v-model="form.district_id" required
                                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm appearance-none bg-white">
                                <option value="" disabled>Select a District</option>
                                <option v-for="district in filterValueDistrict" :key="district.id" :value="district.id">
                                    {{ district.name }}
                                </option>
                            </select>
                            <p class="text-red-500 text-sm" v-if="form.errors.district_id">{{ form.errors.district_id }}</p>
                        </div>
                        <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
                            <label class="block text-sm font-medium text-gray-700">Province</label>
                            <input v-model="form.province_name" type="text" placeholder="Western Province"
        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm"
        readonly />
                            <p class="text-red-500 text-sm" v-if="form.errors.province_name">{{ form.errors.province_name }}</p>
                        </div>
                        <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
                            <label class="block text-sm font-medium text-gray-700">Latitude</label>
                            <div class="relative">
                                
                    <input v-model="form.latitude" type="text"
                        placeholder="65.2365"
                        class="w-full pl-10 px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm" />
                            </div>
                            <p class="text-red-500 text-sm" v-if="form.errors.latitude">{{ form.errors.latitude }}</p>
                        </div>
                        <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
                            <label class="block text-sm font-medium text-gray-700">Longitude</label>
                            <div class="relative">
                    <input v-model="form.longitude" type="text"
                        placeholder="45.2365"
                        class="w-full pl-10 px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm" />
                            </div>
                            <p class="text-red-500 text-sm" v-if="form.errors.longitude">{{ form.errors.longitude }}</p>
                        </div>
                        <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
                            <label class="block text-sm font-medium text-gray-700">Address</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                </div>
                    <input v-model="form.location" type="text"
                        placeholder="123 Beach Road, Colombo"
                        class="w-full pl-10 px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm" />
                            </div>
                        </div>
                        <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
                            <label class="block text-sm font-medium text-gray-700">Contact Number</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                    <input v-model="form.contact_number" type="text" required
                        placeholder="+94 77 123 4567"
                        class="w-full pl-10 px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm" />
                            </div>
                            <p class="text-red-500 text-sm" v-if="form.errors.contact_number">{{ form.errors.contact_number }}</p>
                        </div>
                        <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                    <input v-model="form.email" type="email" required
                        placeholder="info@oceanview.com"
                        class="w-full pl-10 px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm" />
                                <p class="text-red-500 text-sm" v-if="form.errors.email">{{ form.errors.email }}</p>
                            </div>
                        </div>
                        <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
                            <label class="block text-sm font-medium text-gray-700">Website</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9-3-9m-9 9a9 9 0 019-9" />
                                    </svg>
                                </div>
                    <input v-model="form.website" type="url"
    name="website"
    placeholder="https://oceanview.com (optional)"
    class="w-full pl-10 px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Amenities & Details Card -->
                <div v-show="activeSection === 'amenities'"
                     class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100 transition-all duration-500 transform">
                    <div class="flex items-center mb-6">
                        <div class="p-3 bg-gradient-to-br from-orange-400 to-orange-600 rounded-lg mr-4 text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Amenities & Details</h2>
                    </div>

                    <div class="space-y-6">
                        <div class="space-y-4">
                            <label class="block text-sm font-medium text-gray-700">Amenities</label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <label v-for="amenity in amenities" :key="amenity"
                                       class="flex items-center space-x-2 cursor-pointer transform transition-all duration-200 hover:translate-y-[-2px]">
                                    <input type="checkbox" v-model="form.amenities" :value="amenity"
                                           class="w-5 h-5 text-orange-600 border-gray-300 rounded focus:ring-orange-500" />
                                    <span class="text-sm text-gray-700">{{ amenity }}</span>
                                </label>
                            </div>
                            <p class="text-red-500 text-sm" v-if="form.errors.amenities">{{ form.errors.amenities }}</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
                                <label class="block text-sm font-medium text-gray-700">Check-in Time</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <input v-model="form.check_in_time" type="time"
                                           class="w-full pl-10 px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm" />
                                </div>
                            </div>
                            <div class="space-y-2 transform transition-all duration-300 hover:translate-y-[-4px]">
                                <label class="block text-sm font-medium text-gray-700">Check-out Time</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <input v-model="form.check_out_time" type="time"
                                           class="w-full pl-10 px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation buttons -->
                <div class="flex justify-between pt-4">
                    <button type="button" @click="activeSection = activeSection === 'account' ? 'account' :
            activeSection === 'property' ? 'account' :
              activeSection === 'location' ? 'property' : 'location'"
                            class="px-6 py-3 bg-white text-orange-600 font-semibold rounded-lg border border-orange-200 hover:bg-orange-50 focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all shadow-sm"
                            v-if="activeSection !== 'account'">
                        Previous
                    </button>
                    <div v-else></div>

                    <button type="button" @click="nextSection"
                            class="px-6 py-3 bg-orange-600 text-white font-semibold rounded-lg hover:bg-orange-700 focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all shadow-sm"
                            v-if="activeSection !== 'amenities'">
                        Next
                    </button>

                    <button type="submit"
                            class="px-8 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold rounded-lg hover:from-orange-600 hover:to-orange-700 focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="form.processing" v-if="activeSection === 'amenities'">
            <span v-if="form.processing" class="flex items-center">
              <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                   viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
              </svg>
              Saving...
            </span>
                        <span v-else>Add Property</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<style>
/* Add these styles to your CSS */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}

.fade-enter,
.fade-leave-to {
    opacity: 0;
}

/* Hover animations */
input:focus,
select:focus,
textarea:focus {
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

/* Gradient text animation */
@keyframes gradient {
    0% {
        background-position: 0% 50%;
    }

    50% {
        background-position:100% 50%;
    }

    100% {
        background-position: 0% 50%;
    }
}

.bg-gradient-to-r {
    background-size: 200% auto;
    animation: gradient 5s ease infinite;
}
</style>