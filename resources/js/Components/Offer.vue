<template>
<div class="card bg-white shadow-lg rounded-xl p-6 mt-8 relative border border-gray-200">
    <div class="label absolute top-0 left-0 bg-orange-500 text-white text-xs font-semibold px-4 py-2 rounded-tl-lg">Special Offer</div>
    <div class="text-center text-gray-800">
        <span class="text-2xl font-extrabold">Start booking now and unlock exclusive benefits.</span>
        <p class="subtitle mt-4 text-gray-600 text-lg">Book a hotel and earn reward coins per reservation. Collect 5 coins and enjoy a free hotel stay.</p>
        <button class="mt-6 px-6 py-3 bg-orange-500 text-white rounded-full hover:bg-orange-600 transition duration-300 transform hover:scale-105">See More Details</button>
    </div>
</div>
</template>

<style scoped>
.card {
    background-color: #ffffff;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    border-radius: 1rem;
    padding: 2rem;
    margin-top: 3rem;
    position: relative;
    overflow: hidden;
    border: 1px solid #e2e8f0;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
}

.label {
    position: absolute;
    top: 0;
    left: 0;
    background-color: #fc8b3a; /* Light orange */
    color: #ffffff;
    font-size: 0.875rem;
    font-weight: 600;
    padding: 0.5rem 1rem;
    border-radius: 0 0.5rem 0.5rem 0;
}

.text {
    text-align: center;
}

.text span {
    font-size: 1.75rem;
    font-weight: 800;
    color: #2d3748;
}

.subtitle {
    margin-top: 1rem;
    color: #4a5568;
    font-size: 1.1rem;
}

button {
    margin-top: 1.5rem;
    padding: 0.75rem 1.5rem;
    background-color: #f79855;
    color: #ffffff;
    border-radius: 50px;
    transition: background-color 0.3s, transform 0.3s ease;
    font-weight: 600;
}

button:hover {
    background-color: #ea580c;
    transform: scale(1.05);
}

</style>