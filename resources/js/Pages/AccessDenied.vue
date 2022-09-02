<script setup>
import { Head, Link } from "@inertiajs/inertia-vue3";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
    <Head title="Welcome" />
    <div class="h-screen md:flex">
        <div
            class="relative overflow-hidden md:flex md:w-1/2 p-5 md:p-0 text-center md:text-left bg-gradient-to-tr from-red-200 to-red-700 i justify-around items-center bg-image py-20"
        >
            <div class="px-10 py-5">
                <h1 class="text-white font-bold text-3xl font-serif">
                    City Government of Malolos
                </h1>
                <p class="text-white mt-1">
                    Business and Itinerary Application
                </p>
            </div>
        </div>
        <div
            class="flex md:w-1/2 justify-center md:py-10 lg:py-10 py-20 items-center bg-white"
        >
            <div class="justify-center">
                <Link
                    class="block w-full bg-red-700 hover:bg-red-800 my-3 py-2 rounded-2xl text-white font-semibold mb-2 text-center px-10"
                >
                    Access Denied
                </Link>
            </div>
        </div>
    </div>
</template>

<style scoped>
.bg-image {
    /*
    background: linear-gradient(
            0deg,
            rgba(51, 65, 85, 0.874),
            rgba(51, 65, 85, 0.534)
        ),
        url("assets/images/login.jpg"); */
    background: linear-gradient(
            0deg,
            rgba(158, 0, 0, 0.532),
            rgba(104, 0, 0, 0.701)
        ),
        url("../assets/images/login.jpg");
    background-size: cover;
    background-position-x: center;
    background-position-y: center;
}
.py-20 {
    padding-top: 5rem;
    padding-bottom: 5rem;
}
.bg-red-fade {
    background-color: rgba(147, 27, 27, 0.4);
}
.bg-logo {
    background-image: url("../assets/images/logo.png");
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
    width: 90px;
    height: 80px;
}
.bg-bottom {
    background-image: url("../assets/images/bg.png");
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}
.text-mustard {
    color: #e4c18e;
}
.bg-gray-100 {
    background-color: #f7fafc;
    background-color: rgba(247, 250, 252, var(--tw-bg-opacity));
}

.border-gray-200 {
    border-color: #edf2f7;
    border-color: rgba(237, 242, 247, var(--tw-border-opacity));
}

.text-gray-400 {
    color: #cbd5e0;
    color: rgba(203, 213, 224, var(--tw-text-opacity));
}

.text-gray-500 {
    color: #a0aec0;
    color: rgba(160, 174, 192, var(--tw-text-opacity));
}

.text-gray-600 {
    color: #718096;
    color: rgba(113, 128, 150, var(--tw-text-opacity));
}

.text-gray-700 {
    color: #4a5568;
    color: rgba(74, 85, 104, var(--tw-text-opacity));
}

.text-gray-900 {
    color: #1a202c;
    color: rgba(26, 32, 44, var(--tw-text-opacity));
}

@media (prefers-color-scheme: dark) {
    .dark\:bg-gray-800 {
        background-color: #2d3748;
        background-color: rgba(45, 55, 72, var(--tw-bg-opacity));
    }

    .dark\:bg-gray-900 {
        background-color: #1a202c;
        background-color: rgba(26, 32, 44, var(--tw-bg-opacity));
    }

    .dark\:border-gray-700 {
        border-color: #4a5568;
        border-color: rgba(74, 85, 104, var(--tw-border-opacity));
    }

    .dark\:text-white {
        color: #fff;
        color: rgba(255, 255, 255, var(--tw-text-opacity));
    }

    .dark\:text-gray-400 {
        color: #cbd5e0;
        color: rgba(203, 213, 224, var(--tw-text-opacity));
    }
}
</style>
