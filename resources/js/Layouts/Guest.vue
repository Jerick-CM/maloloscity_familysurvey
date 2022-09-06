<script>
import { Link } from "@inertiajs/inertia-vue3";
import BreezeApplicationLogo from "../Components/ApplicationLogoLanding.vue";
export default {
    components: {
        BreezeApplicationLogo,
        Link,
    },
};
</script>

<template>
    <div
        class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-red-500 bg-page"
    >
        <div class="flex flex-items items-center">
            <div class="">
                <BreezeApplicationLogo class="block h-20 w-auto" />
            </div>
            <div class="ml-2">
                <Link href="/login">
                    <h1 class="text-3xl text-white font-bold">
                        Family Survey on <br />
                        Risks and Vulnerabilities
                    </h1>
                </Link>
            </div>
        </div>

        <div class="w-full sm:max-w-md px-2">
            <div
                class="w-full sm:max-w-md bg-white shadow-md overflow-hidden rounded-lg sm:rounded-lg mt-6 px-6 py-4"
            >
                <slot />
            </div>
        </div>
    </div>
</template>

<style scoped>
.bg-page {
    background-image: url("../assets/images/familysurvey.png");
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
}
</style>
