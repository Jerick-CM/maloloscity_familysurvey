<script setup>
import { ref } from "vue";
import BreezeApplicationLogo from "../Components/ApplicationLogo.vue";
import BreezeDropdown from "../Components/Dropdown.vue";
import BreezeDropdownLink from "../Components/DropdownLink.vue";
import BreezeNavLink from "../Components/NavLink.vue";
import BreezeResponsiveNavLink from "../Components/ResponsiveNavLink.vue";
import { Link } from "@inertiajs/inertia-vue3";
import Footer from "../Components/Footer/footer.vue";
const showingNavigationDropdown = ref(false);
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <!-- Page Heading -->
            <header class="bg-red-500 shadow-inner" v-if="$slots.header">
                <div class="w-full mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <!-- <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8"> -->
                    <h2 class="font-semibold text-xl text-white leading-tight">
                        <slot name="header" />
                    </h2>
                </div>
            </header>

            <!-- Page Content -->
            <div id="holder">
                <div id="body">
                    <main>
                        <slot />
                        <div style="min-height: 460px"></div>
                    </main>
                </div>
                <Footer />

            </div>
        </div>
    </div>
</template>
<style scoped>

.text-mustard {
    color: #e4c18e;
}

#holder {
    min-height: 100%;
    position: relative;
}

#body {
    padding-bottom: 100px; /* height of footer */
}


</style>
