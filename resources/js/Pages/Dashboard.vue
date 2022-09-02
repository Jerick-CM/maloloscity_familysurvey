<script>
import BreezeAuthenticatedLayout from "../Layouts/Authenticated.vue";
import { Head } from "@inertiajs/inertia-vue3";
import VueHighcharts from "vue3-highcharts";
import { ref, computed, onMounted } from "vue";
import useDashboard from "../composables/dashboard";
export default {
    components: {
        BreezeAuthenticatedLayout,
        Head,
        VueHighcharts,
    },
    setup(props) {
        const { errors_dash, dashboards, getdashboard_datas } = useDashboard();
        const data = ref([1, 2, 3]);

        const total_itineraries = ref(299);
        const chartData = computed(() => {
            return {
                series: [
                    {
                        name: "Test Series",
                        data: data.value,
                    },
                ],
            };
        });
        onMounted(async () => {
            await getdashboard_datas();
        });
        const chartOptions_pie = computed(() => {
            return {
                legend: { enabled: false },
                chart: {
                    type: "pie",
                    options3d: {
                        enabled: true,
                        alpha: 45,
                    },
                },
                title: {
                    text: "Itineary Completed Business Vs Pending",
                },
                plotOptions: {
                    pie: {
                        innerSize: 100,
                        depth: 45,
                    },
                },
                series: [
                    {
                        name: "Data",
                        colorByPoint: true,
                        data: dashboards.value.completedVsPending,
                    },
                ],
                colors: [
                    "#9346dd",
                    "#fe5288",
                    "#0183d6",
                    "#f4a62f",
                    "#1aadce",
                    "#492970",
                    "#f28f43",
                    "#77a1e5",
                    "#c42525",
                    "#a6c96a",
                ],
            };
        });

        const chartOptions_bar = computed(() => {
            return {
                legend: { enabled: false },
                chart: {
                    type: "bar",
                },
                title: {
                    text: "Total Businesses",
                },
                plotOptions: {
                    pie: {
                        innerSize: 100,
                        depth: 45,
                    },
                },
                series: [
                    {
                        name: "Total Businesses",
                        colorByPoint: true,
                        data: dashboards.value.total_business,
                    },
                ],
                colors: [
                    "#1aadce",
                    "#9346dd",
                    "#fe5288",
                    "#0183d6",
                    "#f4a62f",

                    "#492970",
                    "#f28f43",
                    "#77a1e5",
                    "#c42525",
                    "#a6c96a",
                ],
            };
        });

        const chartOptions_column = computed(() => {
            return {
                chart: {
                    type: "column",
                },

                title: {
                    text: "Top Line of Business",
                },
                plotOptions: {
                    pie: {
                        innerSize: 100,
                        depth: 45,
                    },
                },
                series: [
                    {
                        name: "Line of Business",
                        colorByPoint: true,
                        data: dashboards.value.line_of_business,
                    },
                ],
                colors: [
                    "#9346dd",
                    "#fe5288",
                    "#0183d6",
                    "#f4a62f",
                    "#1aadce",
                    "#492970",
                    "#f28f43",
                    "#77a1e5",
                    "#c42525",
                    "#a6c96a",
                ],
            };
        });

        const chartOptions_business_barangay = computed(() => {
            return {
                chart: {
                    type: "column",
                },

                title: {
                    text: "Number of Business per barangay",
                },
                plotOptions: {
                    pie: {
                        innerSize: 100,
                        depth: 45,
                    },
                },
                series: [
                    {
                        name: "Business",
                        colorByPoint: true,
                        data: dashboards.value.business_barangay,
                    },
                ],
                colors: [
                    "#1aadce",
                    "#9346dd",
                    "#fe5288",
                    "#0183d6",
                    "#f4a62f",
                    "#492970",
                    "#f28f43",
                    "#77a1e5",
                    "#c42525",
                    "#a6c96a",
                ],
            };
        });

        const chartTypeData = computed(() => {
            return {
                chart: {
                    type: "pie",
                    options3d: {
                        enabled: true,
                        alpha: 45,
                    },
                },
                title: {
                    text: "Application Type",
                },
                plotOptions: {
                    pie: {
                        innerSize: 100,
                        depth: 45,
                    },
                },
                series: [
                    {
                        name: "Applications",
                        data: typeData.value,
                    },
                ],
                colors: [
                    "#9346dd",
                    "#fe5288",
                    "#0183d6",
                    "#f4a62f",
                    "#1aadce",
                    "#492970",
                    "#f28f43",
                    "#77a1e5",
                    "#c42525",
                    "#a6c96a",
                ],
            };
        });

        const onRender = () => {
            console.log("Chart rendered");
        };

        const onUpdate = () => {
            console.log("Chart updated");
        };

        const onDestroy = () => {
            console.log("Chart destroyed");
        };
        return {
            dashboards,
            chartData,
            onRender,
            onUpdate,
            onDestroy,
            chartTypeData,
            chartOptions_pie,
            chartOptions_bar,
            chartOptions_column,
            total_itineraries,
            chartOptions_business_barangay,
        };
    },
};
</script>

<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        <template #header> Dashboard </template>

        <div class="py-12">
            <div class="max-w-full mx-auto sm:px-6 lg:px-8">
                <div
                    class="px-1 bg-white overflow-hidden shadow-sm sm:rounded-lg"
                >
                    <div
                        class="md:grid md:grid-cols-3 content-center gap-1 px-2 py-2"
                    >
                        <div
                            class="mx-1 mt-1 py-5 px-5 bg-red-500 h-20 sm:rounded-lg text-white text-center"
                        >
                            <span class="py-2 text-xl">
                                Total:
                                {{ dashboards.total_itinerary }}</span
                            >
                        </div>
                        <div
                            class="mx-1 mt-1 py-5 px-5 bg-green-700 h-20 sm:rounded-lg text-white text-center"
                        >
                            <span class="py-2 text-xl">
                                Pending:
                                {{
                                    dashboards.pending_itinerary_business
                                }}</span
                            >
                        </div>

                        <div
                            class="mx-1 mt-1 py-5 px-5 bg-blue-700 h-20 sm:rounded-lg text-white text-center"
                        >
                            <span class="py-5 px-5 text-xl"
                                >Completed:
                                {{
                                    dashboards.complete_itinerary_business
                                }}</span
                            >
                        </div>
                    </div>
                    <div class="md:grid md:grid-cols-3 py-5">
                        <div>
                            <vue-highcharts
                                type="chart"
                                :options="chartOptions_pie"
                                :redrawOnUpdate="true"
                                :oneToOneUpdate="false"
                                :animateOnUpdate="true"
                                @rendered="onRender"
                                @update="onUpdate"
                                @destroy="onDestroy"
                            />
                        </div>
                        <div class="">
                            <vue-highcharts
                                type="chart"
                                :options="chartOptions_bar"
                                :redrawOnUpdate="true"
                                :oneToOneUpdate="false"
                                :animateOnUpdate="true"
                                @rendered="onRender"
                                @update="onUpdate"
                                @destroy="onDestroy"
                            />
                        </div>

                        <div class="">
                            <vue-highcharts
                                type="chart"
                                :options="chartOptions_column"
                                :redrawOnUpdate="true"
                                :oneToOneUpdate="false"
                                :animateOnUpdate="true"
                                @rendered="onRender"
                                @update="onUpdate"
                                @destroy="onDestroy"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div>
                            <vue-highcharts
                                type="chart"
                                :options="chartOptions_business_barangay"
                                :redrawOnUpdate="true"
                                :oneToOneUpdate="false"
                                :animateOnUpdate="true"
                                @rendered="onRender"
                                @update="onUpdate"
                                @destroy="onDestroy"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
