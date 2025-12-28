<template>
<AuthenticatedLayout> 
     <div class="min-h-screen font-sans text-gray-800 bg-gradient-to-b from-amber-50 to-white">
      <!-- Hero Section -->
      <div class="relative py-28 bg-cover bg-center bg-no-repeat bg-blend-overlay bg-gray-900/70"
        style="background-image: url('https://images.unsplash.com/photo-1544148103-0773bf10d330?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80')">
        <div class="container mx-auto text-center">
          <h1 class="mb-4 text-5xl font-bold text-white animate-fade-in-down">CONTACT US</h1>
          <div class="flex items-center justify-center space-x-2 text-white">
            <a href="#" class="transition-colors duration-300 hover:text-amber-400">HOME</a>
            <span>›</span>
            <span>CONTACT US</span>
          </div>
        </div>
      </div>

      <!-- Contact Form and Info Section -->
      <div class="container px-4 py-20 mx-auto">
        <div class="grid grid-cols-1 gap-12 lg:grid-cols-2">
          <!-- Contact Form -->
          <div class="overflow-hidden bg-white border-none rounded-2xl shadow-xl animate-slideInLeft">
            <div class="p-8 sm:p-10">
              <div class="mb-8">
                <h3 class="mb-2 font-medium text-amber-600">Contact Us</h3>
                <h2 class="text-4xl font-bold text-gray-800">Get In Touch</h2>
              </div>
              <form @submit.prevent="contactForm">
                <div class="mb-5">
                  <label class="block mb-2 font-medium text-gray-700">Name</label>
                  <input
                    type="text"
                    placeholder="Your Name..."
                    class="w-full p-2 text-gray-800 transition-all duration-300 border border-gray-200 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                    v-model="form.name"
                    @input="validateName"
                  />
                  <p v-if="nameError" class="mt-1 text-sm text-red-600">{{ nameError }}</p>
                </div>
                <div class="mb-5">
                  <label class="block mb-2 font-medium text-gray-700">Email</label>
                  <input
                    type="email"
                    placeholder="example@youmail.com"
                    class="w-full p-2 text-gray-800 transition-all duration-300 border border-gray-200 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                    v-model="form.email"
                    @input="validateEmail"
                  />
                  <p v-if="emailError" class="mt-1 text-sm text-red-600">{{ emailError }}</p>
                </div>
                <div class="mb-5">
                  <label class="block mb-2 font-medium text-gray-700">Subject</label>
                  <input type="text" placeholder="Title..."
                    class="w-full p-2 text-gray-800 transition-all duration-300 border border-gray-200 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                    v-model="form.subject"
                    @input="subjectError = ''"
                  >
                  <p v-if="subjectError" class="mt-1 text-sm text-red-600">{{ subjectError }}</p>
                </div>
                <div class="mb-6">
                  <label class="block mb-2 font-medium text-gray-700">Message</label>
                  <textarea placeholder="Type here..." rows="5"
                    class="w-full p-4 text-gray-800 transition-all duration-300 border border-gray-200 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent resize-none"
                    v-model="form.message"
                    @input="messageError = ''"
                  ></textarea>
                  <p v-if="messageError" class="mt-1 text-sm text-red-600">{{ messageError }}</p>
                </div>
                <button type="submit"
                  class="w-full py-3 text-lg font-medium text-white transition-all duration-300 transform rounded-lg shadow-md bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 hover:shadow-lg hover:-translate-y-1">
                  Send Now
                </button>
              </form>
            </div>
          </div>

          <!-- Contact Info -->
          <div class="animate-slideInRight">
            <div class="mb-10 mt-20">
              <p class="mb-10 text-lg leading-relaxed text-gray-600">Have questions or need assistance? Feel free to
                reach out to us! We're here to help with any inquiries, bookings, or support you may need.</p>

              <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                <!-- Phone Number -->
                <div class="flex items-start group">
                  <div
                    class="p-4 mr-5 transition-all duration-300 shadow-sm bg-amber-50 rounded-xl group-hover:bg-amber-500 group-hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg"
                      class="text-amber-500 transition-colors duration-300 h-7 w-7 group-hover:text-white" fill="none"
                      viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                  </div>
                  <div>
                    <h3 class="mb-2 text-lg font-bold text-gray-800">Phone Number</h3>
                    <p class="text-gray-600">+94 77 404 2407</p>
                  </div>
                </div>

                <!-- Email Address -->
                <div class="flex items-start group">
                  <div
                    class="p-4 mr-5 transition-all duration-300 shadow-sm bg-amber-50 rounded-xl group-hover:bg-amber-500 group-hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg"
                      class="text-amber-500 transition-colors duration-300 h-7 w-7 group-hover:text-white" fill="none"
                      viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                  </div>
                  <div>
                    <h3 class="mb-2 text-lg font-bold text-gray-800">Email Address</h3>
                    <p class="text-gray-600">Example@email.com</p>
                  </div>
                </div>

                <!-- Whatsapp -->
                <div class="flex items-start group">
                  <div
                    class="p-4 mr-5 transition-all duration-300 shadow-sm bg-amber-50 rounded-xl group-hover:bg-amber-500 group-hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg"
                      class="text-amber-500 transition-colors duration-300 h-7 w-7 group-hover:text-white" fill="none"
                      viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                  </div>
                  <div>
                    <h3 class="mb-2 text-lg font-bold text-gray-800">Whatsapp</h3>
                    <p class="text-gray-600">+94 77 404 2407</p>
                  </div>
                </div>

                <!-- Our Office -->
                <div class="flex items-start group">
                  <div
                    class="p-4 mr-5 transition-all duration-300 shadow-sm bg-amber-50 rounded-xl group-hover:bg-amber-500 group-hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg"
                      class="text-amber-500 transition-colors duration-300 h-7 w-7 group-hover:text-white" fill="none"
                      viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                  </div>
                  <div>
                    <h3 class="mb-2 text-lg font-bold text-gray-800">Our Office</h3>
                    <p class="text-gray-600">No.06/B , Poramba, Ambalangoda</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Map -->
            <div class="w-full overflow-hidden bg-gray-100 shadow-lg h-72 rounded-2xl">
              <img src="/imges/contact.jpeg" alt="Office location map" class="object-cover w-full h-full">
            </div>
          </div>
        </div>
      </div>
    </div>
    </AuthenticatedLayout>
</template>

<script>
import { useForm } from '@inertiajs/inertia-vue3';
import { defineComponent, ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import AuthenticatedLayout from '@/Layouts/User/AuthenticatedLayout.vue';

export default defineComponent({
  components: {
    AuthenticatedLayout,
  },
  setup() {
    const form = useForm({
      name: '',
      email: '',
      subject: '',
      message: ''
    });


    // Real-time name validation
    const nameError = ref('');
    const namePattern = /^[A-Za-z\s]+$/;
    const validateName = () => {
      if (!form.name) {
        nameError.value = 'Name is required.';
      } else if (!namePattern.test(form.name)) {
        nameError.value = 'Name can only contain letters and spaces.';
      } else {
        nameError.value = '';
      }
    };

    // Real-time email validation
    const emailError = ref('');
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const validateEmail = () => {
      if (!form.email) {
        emailError.value = 'Email is required.';
      } else if (!emailPattern.test(form.email)) {
        emailError.value = 'Please enter a valid email address.';
      } else {
        emailError.value = '';
      }
    };

    // Subject and message validation
    const subjectError = ref('');
    const messageError = ref('');

    const contactForm = () => {
      validateName();
      validateEmail();
      subjectError.value = form.subject ? '' : 'Subject is required.';
      messageError.value = form.message ? '' : 'Message is required.';
      if (nameError.value || emailError.value || subjectError.value || messageError.value) {
        return;
      }
      form.post(route('contact.send'), {
        onSuccess: () => {
          alert("Message sent successfully");
          form.reset();
        },
        onError: (errors) => {
          console.log("Form submission errors:", errors);
        },
      });
    };

    return {
      form,
      contactForm,
      validateName,
      nameError,
      validateEmail,
      emailError,
      subjectError,
      messageError
    };
  },
});
</script>

<style>
@keyframes fade-in-down {
  0% {
    opacity: 0;
    transform: translateY(-20px);
  }

  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }

  to {
    opacity: 1;
  }
}

@keyframes slideInLeft {
  from {
    transform: translateX(-30px);
    opacity: 0;
  }

  to {
    transform: translateX(0);
    opacity: 1;
  }
}

@keyframes slideInRight {
  from {
    transform: translateX(30px);
    opacity: 0;
  }

  to {
    transform: translateX(0);
    opacity: 1;
  }
}

.animate-fade-in-down {
  animation: fade-in-down 0.5s ease-out forwards;
}

.animate-fadeIn {
  animation: fadeIn 1.2s ease-out;
}

.animate-slideInLeft {
  animation: slideInLeft 1s ease-out;
}

.animate-slideInRight {
  animation: slideInRight 1s ease-out;
}

/* Add smooth transitions for all interactive elements */
a,
button,
input,
textarea {
  transition: all 0.3s ease;
}

/* Add focus styles for better accessibility */
input:focus,
textarea:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.2);
}

/* Add hover effects for interactive elements */
.group:hover .group-hover\:bg-amber-500 {
  background-color: rgb(245, 158, 11);
}

.group:hover .group-hover\:text-white {
  color: white;
}
</style>