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
                    text: "4P's Vs Non 4P's",
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
                        data: dashboards.value.four4Ps_non4Ps,
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
                    text: "Total Respondents",
                },
                plotOptions: {
                    pie: {
                        innerSize: 100,
                        depth: 45,
                    },
                },
                series: [
                    {
                        name: "Total #",
                        colorByPoint: true,
                        data: dashboards.value.total_respondents_chart,
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
                    text: "Top 10 Most number of Respondents",
                },
                plotOptions: {
                    pie: {
                        innerSize: 100,
                        depth: 45,
                    },
                },
                series: [
                    {
                        name: "Top 10",
                        colorByPoint: true,
                        data: dashboards.value.top10barangay,
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
                    text: "Number of Respondents per barangay",
                },
                plotOptions: {
                    pie: {
                        innerSize: 100,
                        depth: 45,
                    },
                },
                series: [
                    {
                        name: "Barangay",
                        colorByPoint: true,
                        data: dashboards.value.respondents_per_barangay,
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

        };

        const onUpdate = () => {

        };

        const onDestroy = () => {

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
                                {{ dashboards.total_respondents }}
                            </span>
                        </div>
                        <div
                            class="mx-1 mt-1 py-5 px-5 bg-green-700 h-20 sm:rounded-lg text-white text-center"
                        >
                            <span class="py-2 text-xl">
                                4 P's:
                                {{
                                    dashboards.fourPs
                                }}</span
                            >
                        </div>

                        <div
                            class="mx-1 mt-1 py-5 px-5 bg-blue-700 h-20 sm:rounded-lg text-white text-center"
                        >
                            <span class="py-5 px-5 text-xl"
                                >    Non - 4 P's:
                                {{
                                    dashboards.nonfourPs
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
