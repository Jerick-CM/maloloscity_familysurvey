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
            <!-- <header class="bg-blue-900 shadow second-nav" v-if="$slots.header">
                <div class="w-full mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <h2 class="font-semibold text-xl text-white leading-tight">
                        <slot name="header" />
                    </h2>
                </div>
            </header> -->

            <div id="holder">
                <header class="border-b-4 border-blue-500">
                    <div class="p-5 bg-bottom second-nav">
                        <div class="flex flex-items">
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('dashboard')">
                                    <BreezeApplicationLogo
                                        class="block h-9 w-auto"
                                    />
                                </Link>
                            </div>
                            <div class="flex flex-col pl-3">
                                <div>
                                    <p class="text-white">
                                        Republic of the Philippines
                                    </p>
                                </div>
                                <div>
                                    <p class="text-white">
                                        Province of Bulacan
                                    </p>
                                </div>
                                <div>
                                    <p class="text-white">City of Malolos</p>
                                </div>
                            </div>
                        </div>
                        <div class="text-white">
                            <slot class="text-white" name="header" />
                        </div>
                    </div>
                </header>
                <div id="body">
                    <main>
                        <slot />
                    </main>
                    <div style="min-height: 460px"></div>
                </div>
                <Footer />
            </div>
        </div>
    </div>
</template>
<style scoped>
.second-nav {
    background: linear-gradient(
            0deg,
            rgba(248, 113, 113, 0.532),
            rgba(239, 68, 68, 0.701)
        ),
        url("../assets/images/bg.png");
    opacity: 0.9;
}
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

footer {
    height: 100px;
    width: 100%;
    position: absolute;
    left: 0;
    bottom: 0;
}
</style>
