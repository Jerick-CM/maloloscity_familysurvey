<script>
import BreezeApplicationLayout from "../Layouts/Landing.vue";
import { Head } from "@inertiajs/inertia-vue3";
export default {
    components: {
        BreezeApplicationLayout,
        Head,
    },
    props: ["item", "businesses"],
    setup() {
        //

        return {
            //
        };
    },
};
</script>
<template>
    <BreezeApplicationLayout>
        <Head title="Itinerary" />
        <div class="w-full px-10">
            <h1 class="py-1 text-center text-4xl font-black text-green-600">
                Itinerary #{{ item.id }}
            </h1>
            <div
                class="flex flex-rows cursor-pointer w-full mr-3 md:mb-0 text-white bg-red-500 hover:bg-ref-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium text-sm px-4 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
            ></div>
            <div class="flex flex-col table-info-data">
                <div class="pl-1 pt-1">
                    <span class="text-red-600"> Name:</span> {{ item.name }}
                </div>
                <div class="pl-1 pt-1">
                    <span class="text-red-600">Control Number: </span>
                    {{ item.control_number }}
                </div>
                <div class="pl-1 pt-1">
                    <span class="text-red-600">Request Date:</span>
                    {{ item.requestdate }}
                </div>
                <div class="pl-1 pt-1">
                    <span class="text-red-600">Completed Time:</span>
                    {{ item.completed_time }}
                </div>
                <div class="pl-1 pt-1">
                    <span class="text-red-600">Start Time: </span>

                    {{ item.start_at }}
                </div>
                <div class="pl-1 pt-1">
                    <span class="text-red-600"> End Time: </span>
                    {{ item.end_at }}
                </div>
            </div>
            <p class="text-center mt-5">
                <span class="font-semibold mr-2"></span>
            </p>
            <div
                class="flex flex-rows cursor-pointer w-full mr-3 md:mb-0 text-white table-info-header hover:bg-ref-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium text-sm px-4 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
            ></div>

            <div
                v-for="(data, key) in businesses"
                :key="key"
                class="flex flex-col table-info-data bg-transparent hover:bg-slate-300 hover:text-slate-800 hover:font-semibold border border-solid hover:blue-white uppercase text-sm px-6 py-3 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
            >
                <div>
                    <span class="text-red-600">B.I.N.:</span>
                    {{ data.bin }}
                </div>
                <div>
                    <span class="text-red-600">Business Name:</span>
                    {{ data.business_name }}
                </div>
                <div>
                    <span class="text-red-600">Address:</span>
                    {{ data.address }}
                </div>
                <div>
                    <span class="text-red-600">Line of Business: </span
                    >{{ data.line_of_business }}
                </div>
                <div>
                    <span class="text-red-600">Owner:</span> {{ data.owner }}
                </div>
                <div>
                    <span class="text-red-600">Contact Number:</span>
                    {{ data.contact_number }}
                </div>
            </div>
        </div>
    </BreezeApplicationLayout>
</template>
<style scoped>
.table-info-header {
    background-color: rgb(239 68 68);
    /* background-color: rgb(127, 29, 29); */
}

.table-info-data {
    background-color: #e2e1de;
}
.bg-bottom {
    background: linear-gradient(
            0deg,
            rgba(158, 0, 0, 0.532),
            rgba(239, 68, 68, 0.701)
        ),
        url("../assets/images/bg.png");

    /* background-image: url("../assets/images/bg.png"); */
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}
.text-mustard {
    color: #e4c18e;
}
</style>
