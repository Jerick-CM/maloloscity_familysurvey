<script>
import BreezeAuthenticatedLayout from "./../../Layouts/Composition.vue";
import { Head, Link, usePage } from "@inertiajs/inertia-vue3";
import { ref, reactive, computed, onMounted, watch } from "vue";
import Multiselect from "@vueform/multiselect";
import useItineraries from "./../../composables/itineraries";
import { useToast } from "vue-toastification";
import Modal from "./../../Components/Modals/Modal.vue";
import Partials_BusinessInfo from "./../../Components/Partials/BusinessInfo.vue";
import Partials_HistoricalChecklist from "./../../Components/Partials/HistoricalChecklist.vue";

import SkeletonLoader from "./../../Components/Skeleton/LoaderChecklist.vue";

import Modal_info from "./../../Components/Modals/Modal_v2.vue";
import Modal_remarks from "./../../Components/Modals/remarks.vue";
import LoaderCheck from "./../../Components/Skeleton/LoaderCheck.vue";

export default {
    components: {
        Modal_remarks,
        Modal_info,
        Multiselect,
        BreezeAuthenticatedLayout,
        Head,
        Link,
        Modal,
        Partials_BusinessInfo,
        Partials_HistoricalChecklist,
        SkeletonLoader,
        LoaderCheck,
    },
    data: () => ({ title: "Itinerary Page" }),
    props: ["hosting"],
    setup(props, { attrs, slots, emit, expose }) {
        console.log("setup");
        /* Init */
        const hosting = computed(() => props.hosting);
        const toast = useToast();
        const Auth_user = computed(() => usePage().props.value.auth.user);
        const permissions = usePage().props.value.auth.user.PermissionList;

        const form = reactive({
            id: null,
            it_id: null,
        });

        let page_title = ref("Itinerary Pull");
        let selectedItems = ref([]);
        let modal_show = ref(false);
        let modal_note = ref(false);
        let modal_show_info = ref(false);
        let modal_note_info = ref(false);
        let modal_status_info = ref(false);
        let div_infoholder = ref(false);
        const checklist_form = ref([]);

        const modal_show_remarks = ref(false);
        const remarks = ref(false);
        const panel = ref(false);
        const allowpull = ref(false);
        const const_itinerary_business_id = ref(false);
        const pull_loading = ref(false);
        const headers = ref([
            { text: "Business Name", value: "business_name" },
            {
                text: "Line of Business",
                value: "line_of_business",
                sortable: false,
            },
            { text: "Capital", value: "capital", sortable: false },
            { text: "Address", value: "address", sortable: false },

            { text: "Time Start", value: "start_at", sortable: false },
            { text: "Time End", value: "end_at", sortable: false },
            { text: "Time Complete", value: "completed_time", sortable: false },
            { text: "Date/Time", value: "created_date", sortable: false },
            { text: "Assigned to", value: "username", sortable: false },
            { text: "Status", value: "i_stat", sortable: false },
            { text: "Action", value: "action", sortable: false },
        ]);

        const {
            errors_I,
            itinerary,
            selectedbusinesses_ref,
            checklists,
            business,
            checklists_values,
            historical_checklist,
            getItinerary,
            getChecklists,
            complete_ItineraryBusiness,
            getBusiness,
            getItineraryPull,
            handleStartTime,
            handleFinishTime,
            getChecklistsBusiness,
            exportItineraryPullData,
            assigned_id,
            validateStartChecklistsBusiness,
            validateStartBusiness,
            /*revision*/
            getChecklistData,
            checklistdata,
            complete_ItineraryBusiness_new,
            getBussinessHistory,
        } = useItineraries();

        const CompleteItinerary = async () => {
            toast.info("Send");
            form.it_id = parseInt(route().params.id);
            form.id = business.value.id;
            await complete_ItineraryBusiness({ ...form }).then(() => {
                returns();
            });
            await getItineraryPull(route().params.id);
        };

        onMounted(async () => {
            await getItineraryPull(route().params.id);
            await getChecklists();
            await getItinerary(parseInt(route().params.id));
        });

        /* method */
        function togglemodal_info() {
            modal_show_business.value = !modal_show_business.value;
            modal_status_business.value = true;
            modal_note_business.value = "Error.";
        }

        // function togglemodal() {
        //     modal_show.value = !modal_show.value;
        //     if (errors.value) {
        //         toast.error("Error", {
        //             timeout: 5000,
        //         });
        //         modal_status.value = false;
        //         modal_note.value = "Error.";
        //     } else {
        //         toast.success("Success", {
        //             timeout: 5000,
        //         });

        //         modal_note.value = "Update successful.";
        //     }
        // }

        const PullChecklist = async (id, itinerary_business_id) => {
            pull_loading.value = false;
            panel.value = true;

            await getBusiness(id);
            await getChecklistsBusiness(id, parseInt(route().params.id));

            /*get checklist data*/

            await getChecklistData(itinerary_business_id);
            await getBussinessHistory(id, itinerary_business_id);

            const_itinerary_business_id.value = itinerary_business_id;

            if (permissions.includes("Action Pull Itinerary")) {
                await validateStartChecklistsBusiness(
                    id,
                    parseInt(route().params.id)
                );
            }

            if (permissions.includes("Action Pull Itinerary")) {
                await validateStartBusiness(
                    id,
                    parseInt(const_itinerary_business_id.value)
                );
            }

            pull_loading.value = true;
        };

        const PullBusinessID = () => {
            form.id = business.value.id;
        };

        const handleStart = async () => {
            toast.info("Send");
            form.id = parseInt(route().params.id);
            await handleStartTime({ ...form }).then(() => {
                returns();
            });
            await getItinerary(parseInt(route().params.id));
        };

        const handleFinish = async () => {
            toast.info("Send");
            form.id = parseInt(route().params.id);
            await handleFinishTime({ ...form }).then(() => {
                returns();
            });

            await getItinerary(parseInt(route().params.id));
        };

        const showInfo = async (id) => {
            await getBusiness(id);
            dropIt();
        };

        const showPanel = async () => {
            panel.value = false;
            await getItinerary(
                parseInt(route().params.id)
            ); /* get business pivot info*/
            await getItineraryPull(route().params.id); /* reload main table*/
        };

        const returns = () => {
            if (errors_I.value) {
                toast.error("Failed.");
            } else {
                toast.success("Success.");
            }
        };

        const dropIt = () => {
            div_infoholder.value = !div_infoholder.value;
        };

        function togglemodal_info() {
            modal_show_info.value = !modal_show_info.value;
        }

        const showInfo_modal = async (id) => {
            await getBusiness(id);
            togglemodal_info();
        };

        const showInfo_Remarks = (data) => {
            remarks.value = data;
            togglemodal_remarks();
        };

        function togglemodal_remarks() {
            modal_show_remarks.value = !modal_show_remarks.value;
        }

        const exportData = async (data) => {
            await exportItineraryPullData(data);
        };

        const CompleteItinerary_new = async () => {
            toast.info("Send");
            form.it_id = parseInt(route().params.id);
            form.id = business.value.id;
            form.checklist = checklist_form.value;
            form.itinerary_business_id = const_itinerary_business_id.value;
            await complete_ItineraryBusiness_new({ ...form }).then(() => {
                returns();
            });

            await getChecklistData(form.itinerary_business_id);
        };

        function viewpdf() {
            const url = hosting.value + "/view-checklist/" + route().params.id;
            window.open(url);
        }

        watch(
            () => checklistdata.value,
            (currentValue, oldValue) => {
                checklist_form.value = [];
                checklist_form.value.push(null);
                currentValue.forEach((element) => {
                    console.log(element.checkbox);
                    checklist_form.value.push(
                        element.checkbox == 1 ? true : false
                    );
                });
            },
            { deep: true }
        );
        watch(
            () => business.value,
            (currentValue, oldValue) => {},
            { deep: true }
        );

        watch(
            () => assigned_id.value,
            (currentValue, oldValue) => {
                if (currentValue == Auth_user.value.id) {
                    allowpull.value = true;
                } else {
                    allowpull.value = false;
                }
            },
            { deep: true }
        );

        watch(
            () => checklists_values.value,
            (currentValue, oldValue) => {
                form.remarks = currentValue.remarks;
            },
            { deep: true }
        );
        return {
            selectedItems,
            historical_checklist,
            headers,
            checklists,
            form,
            Auth_user,
            errors_I,
            selectedbusinesses_ref,
            business,
            checklists_values,
            modal_show_info,
            modal_note_info,
            modal_status_info,
            business,
            itinerary,
            div_infoholder,
            panel,
            modal_show_remarks,
            remarks,
            allowpull,
            togglemodal_info,
            CompleteItinerary,
            PullBusinessID,
            showInfo,
            dropIt,
            showPanel,
            handleStart,
            handleFinish,
            PullChecklist,
            showInfo_modal,
            showInfo_Remarks,
            togglemodal_remarks,
            exportData,
            checklistdata,
            checklist_form,
            CompleteItinerary_new,
            pull_loading,
            viewpdf,
            permissions,
        };
    },
};
</script>

<template>
    <Head title="Itinerary Pull" />
    <BreezeAuthenticatedLayout>
        <template #header> Itinerary page </template>
        <div class="py-2">
            <div class="w-full mx-auto sm:px-6 lg:px-8">
                <div id="template" class="">
                    <Modal_info
                        :showmodal="modal_show_info"
                        @toggle="togglemodal_info()"
                        :info="business"
                    >
                    </Modal_info>
                    <Modal_remarks
                        :showmodal="modal_show_remarks"
                        @toggle="togglemodal_remarks()"
                        :remarks="remarks"
                    >
                    </Modal_remarks>
                    <div>
                        <div for="BreadCrumb">
                            <nav class="py-5 flex" aria-label="Breadcrumb">
                                <ol
                                    class="inline-flex items-center space-x-1 md:space-x-3"
                                >
                                    <li class="inline-flex items-center">
                                        <Link
                                            :href="route('dashboard')"
                                            class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                                        >
                                            <svg
                                                class="w-4 h-4 mr-2"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg"
                                            >
                                                <path
                                                    d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"
                                                ></path>
                                            </svg>
                                            Dashboard
                                        </Link>
                                    </li>
                                    <li>
                                        <div class="flex items-center">
                                            <svg
                                                class="w-6 h-6 text-gray-400"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                    clip-rule="evenodd"
                                                ></path>
                                            </svg>
                                            <Link
                                                :href="route('itinerary')"
                                                class="ml-1 text-sm font-medium text-gray-700 hover:text-gray-900 md:ml-2 dark:text-gray-400 dark:hover:text-white"
                                                >Itinerary</Link
                                            >
                                        </div>
                                    </li>
                                    <li>
                                        <div class="flex items-center">
                                            <svg
                                                class="w-6 h-6 text-gray-400"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                    clip-rule="evenodd"
                                                ></path>
                                            </svg>
                                            <Link
                                                href="#"
                                                class="ml-1 text-sm font-medium text-gray-700 hover:text-gray-900 md:ml-2 dark:text-gray-400 dark:hover:text-white"
                                                >Pull</Link
                                            >
                                        </div>
                                    </li>
                                </ol>
                            </nav>
                        </div>

                        <div class="bg-white rounded p-5 sm:p1">
                            <div class="py-2">Itinerary</div>
                            <div id="itinerary_panel" v-if="!panel">
                                <!-- Top Panel -->
                                <div
                                    class="flex flex-col 2xl:flex-row xl:flex-row lg:flex-row"
                                >
                                    <div
                                        class="w-full 2xl:w-2/4 xl:w-2/4 lg:w-2/4 flex flex-col 2xl:flex-row xl:flex-row lg:flex-row justify-items-start place-content-start"
                                    >
                                        <div
                                            class="mt-1 px-1 w-full 2xl:w-fit xl:w-fit lg:w-fit flex flex-col 2xl:flex-row xl:flex-row lg:flex-row"
                                        >
                                            <div>
                                                <button
                                                    v-if="
                                                        itinerary.start_at ==
                                                        null
                                                    "
                                                    @click="handleStart()"
                                                    class="text-xs bg-blue-700 hover:bg-blue-400 text-white font-bold py-2 px-4 rounded"
                                                >
                                                    Start Time
                                                </button>
                                            </div>
                                            <div>
                                                <label
                                                    class="text-red-500"
                                                    for=""
                                                    v-if="
                                                        itinerary.start_at !=
                                                        null
                                                    "
                                                    >Start Time:
                                                    <span class="text-black">{{
                                                        itinerary.start_at
                                                    }}</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div
                                            class="mt-1 px-1 w-full 2xl:w-fit xl:w-fit lg:w-fit flex flex-col 2xl:flex-row xl:flex-row lg:flex-row"
                                        >
                                            <div>
                                                <label
                                                    class="text-red-500"
                                                    for=""
                                                    v-if="
                                                        itinerary.end_at != ''
                                                    "
                                                    >End Time:
                                                    <span class="text-black">{{
                                                        itinerary.end_at
                                                    }}</span></label
                                                >
                                            </div>
                                            <div class="px-2 hidden">
                                                <button
                                                    @click="handleFinish()"
                                                    class="text-xs bg-blue-700 hover:bg-blue-400 text-white font-bold py-2 px-4 rounded"
                                                >
                                                    Finish Time
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="w-full 2xl:w-2/4 xl:w-2/4 lg:w-2/4 flex flex-col 2xl:flex-row xl:flex-row lg:flex-row justify-items-end place-content-end"
                                    >
                                        <div
                                            class="px-1 w-full 2xl:w-fit xl:w-fit lg:w-fit mt-4 2xl:mt-1 xl:mt-1 lg:mt-1"
                                        >
                                            <button
                                                @click.prevent="viewpdf()"
                                                class="content-center w-full 2xl:w-fit xl:w-fit lg:w-fit bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="w-4 h-4 mr-2"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"
                                                    />
                                                </svg>

                                                <span>Print Checkist</span>
                                            </button>
                                        </div>
                                        <div
                                            class="px-1 2xl:w-fit xl:w-fit lg:w-fit mt-4 2xl:mt-1 xl:mt-1 lg:mt-1"
                                            v-if="
                                                permissions.includes(
                                                    'Action Download Itinerary'
                                                )
                                            "
                                        >
                                            <button
                                                @click.prevent="
                                                    exportData(selectedItems)
                                                "
                                                class="content-center w-full 2xl:w-fit xl:w-fit lg:w-fit bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center"
                                            >
                                                <svg
                                                    class="fill-current w-4 h-4 mr-2"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                >
                                                    <path
                                                        d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z"
                                                    />
                                                </svg>

                                                <span>Download</span>
                                            </button>
                                        </div>

                                        <div
                                            class="px-1 2xl:w-fit xl:w-fit lg:w-fit mt-4 2xl:mt-1 xl:mt-1 lg:mt-1"
                                        >
                                            <Link
                                                :href="route('business-create')"
                                            >
                                                <button
                                                    class="content-center w-full 2xl:w-fit xl:w-fit lg:w-fit bg-green-300 hover:bg-green-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center"
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        class="fill-current w-4 h-4 mr-2"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z"
                                                        />
                                                    </svg>
                                                    <span
                                                        >Add a new Business
                                                    </span>
                                                </button>
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                                <!-- Top End -->

                                <!-- datatable -->
                                <div class="py-2">
                                    <EasyDataTable
                                        v-model:items-selected="selectedItems"
                                        :headers="headers"
                                        :items="selectedbusinesses_ref"
                                        table-class-name="customize-table"
                                    >
                                        <!-- Expand -->
                                        <template #expand="item">
                                            <div class="">
                                                <div
                                                    class="md:grid md:grid-rows"
                                                >
                                                    <div
                                                        class="flex flex-items"
                                                    >
                                                        <div class="p-1 py-5">
                                                            <button
                                                                @click="
                                                                    showInfo_Remarks(
                                                                        item.remarks
                                                                    )
                                                                "
                                                                class="h-15 w-24 text-xs bg-green-700 hover:bg-green-400 text-white font-bold py-2 px-4 rounded"
                                                            >
                                                                <svg
                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-6 w-6"
                                                                    fill="none"
                                                                    viewBox="0 0 24 24"
                                                                    stroke="currentColor"
                                                                    stroke-width="2"
                                                                >
                                                                    <path
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"
                                                                    />
                                                                </svg>
                                                                <span
                                                                    class="text-lg"
                                                                    >Remarks</span
                                                                >
                                                            </button>
                                                        </div>
                                                        <div class="p-1 py-5">
                                                            <button
                                                                @click="
                                                                    showInfo_modal(
                                                                        item.id
                                                                    )
                                                                "
                                                                class="h-15 w-24 text-xs bg-yellow-700 hover:bg-yellow-400 text-white font-bold py-2 px-4 rounded"
                                                            >
                                                                <svg
                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-6 w-6"
                                                                    fill="none"
                                                                    viewBox="0 0 24 24"
                                                                    stroke="currentColor"
                                                                    stroke-width="2"
                                                                >
                                                                    <path
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                                                    />
                                                                </svg>
                                                                <span
                                                                    class="text-lg"
                                                                    >Info</span
                                                                >
                                                            </button>
                                                        </div>
                                                        <div class="p-1 py-5">
                                                            <button
                                                                @click="
                                                                    PullChecklist(
                                                                        item.id,
                                                                        item.iten_busi_id
                                                                    )
                                                                "
                                                                class="h-15 w-24 text-xs bg-blue-700 hover:bg-blue-400 text-white font-bold py-2 px-4 rounded"
                                                            >
                                                                <svg
                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-6 w-6"
                                                                    fill="none"
                                                                    viewBox="0 0 24 24"
                                                                    stroke="currentColor"
                                                                    stroke-width="2"
                                                                >
                                                                    <path
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"
                                                                    />
                                                                </svg>
                                                                <span
                                                                    class="text-lg"
                                                                    >Pull</span
                                                                >
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div>
                                                    Businesse Name :
                                                    {{ item.business_name }}
                                                </div>
                                                <div>
                                                    Address :
                                                    {{ item.address }}
                                                </div>
                                                <div>
                                                    Start Time :
                                                    {{ item.start_at }}
                                                </div>
                                                <div>
                                                    End Time :
                                                    {{ item.end_at }}
                                                </div>
                                                <div>
                                                    Completion Time :
                                                    {{ item.completed_time }}
                                                </div>
                                                <div>
                                                    Status :
                                                    {{
                                                        item.i_stat == 1
                                                            ? "Done"
                                                            : "Pending"
                                                    }}
                                                </div>
                                            </div>
                                        </template>
                                        <template #item-i_stat="item">
                                            {{
                                                item.i_stat == 1
                                                    ? "Done"
                                                    : "Pending"
                                            }}
                                        </template>
                                        <template #item-action="item">
                                            <div class="operation-wrapper flex">
                                                <div class="p-1">
                                                    <button
                                                        @click="
                                                            showInfo_Remarks(
                                                                item.remarks
                                                            )
                                                        "
                                                        class="text-xs bg-green-700 hover:bg-green-400 text-white font-bold py-2 px-4 rounded"
                                                    >
                                                        Remarks
                                                    </button>
                                                </div>
                                                <div class="p-1">
                                                    <button
                                                        @click="
                                                            showInfo_modal(
                                                                item.id
                                                            )
                                                        "
                                                        class="text-xs bg-yellow-700 hover:bg-yellow-400 text-white font-bold py-2 px-4 rounded"
                                                    >
                                                        Info
                                                    </button>
                                                </div>
                                                <div class="p-1">
                                                    <button
                                                        @click="
                                                            PullChecklist(
                                                                item.id,
                                                                item.iten_busi_id
                                                            )
                                                        "
                                                        class="text-xs bg-blue-700 hover:bg-blue-400 text-white font-bold py-2 px-4 rounded"
                                                    >
                                                        Pull
                                                    </button>
                                                </div>
                                            </div>
                                        </template>
                                    </EasyDataTable>
                                </div>
                            </div>

                            <!-- business panel -->
                            <div id="business_panel" v-if="panel">
                                <div class="py-4">
                                    <a
                                        href="#"
                                        @click.prevent="showPanel"
                                        class="flex flex-items"
                                    >
                                        <div>
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-6 w-6 stroke-green-800"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                                stroke-width="2"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z"
                                                />
                                            </svg>
                                        </div>
                                        <div>Back to Itinerary List</div>
                                    </a>
                                </div>
                                <!-- toggle business information container -->
                                <div
                                    @click="showInfo(business.id)"
                                    class="cursor-pointer w-full mr-3 md:mb-0 text-white bg-red-500 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium text-sm px-4 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                >
                                    <div class="flex-none">Info</div>
                                    <div
                                        class="grow justify-left content-start"
                                    ></div>
                                    <div class="place-content-center">
                                        <svg
                                            v-if="!div_infoholder"
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-6 w-6"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M9 5l7 7-7 7"
                                            />
                                        </svg>
                                        <svg
                                            v-if="div_infoholder"
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-6 w-6"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M19 9l-7 7-7-7"
                                            />
                                        </svg>
                                    </div>
                                </div>
                                <!-- toggle business information container end -->
                                <!-- business information container -->
                                <div
                                    v-if="div_infoholder"
                                    class="overflow-x-auto w-full table-info-data border-x-rose-600 border-1 border-b-rose-600"
                                >
                                    <Partials_BusinessInfo
                                        :business="business"
                                    />
                                </div>
                                <!-- business information container end -->

                                <div
                                    class="grid md:grid md:grid-cols-1 2xl:grid-cols-2 xl:grid-cols-2 lg:grid-cols-2"
                                >
                                    <!-- checklist complete-->

                                    <div class="py-1 px-1">
                                        <!-- <div
                                            class="py-4 text-red-500 hidden xl:block 2xl:block xl:block "
                                            @click="showInfo()"
                                        >
                                            <div class="flex flex-items hidden">
                                                <div>Business Checklist</div>

                                                <div
                                                    class="place-content-center"
                                                >
                                                    <svg
                                                        v-if="!div_infoholder"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        class="h-6 w-6"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            d="M9 5l7 7-7 7"
                                                        />
                                                    </svg>
                                                    <svg
                                                        v-if="div_infoholder"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        class="h-6 w-6"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            d="M19 9l-7 7-7-7"
                                                        />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div> -->
                                        <div>
                                            <div class="py-4 text-red-500">
                                                Business Checklist
                                            </div>
                                            <div v-if="!pull_loading">
                                                <LoaderCheck />
                                            </div>
                                            <div v-if="pull_loading">
                                                <form
                                                    @submit.prevent="
                                                        CompleteItinerary_new
                                                    "
                                                >
                                                    <div class="hidden">
                                                        test 1
                                                        {{ checklistdata }}
                                                        <br />
                                                        test 2
                                                        {{ checklist_form }}
                                                    </div>

                                                    <div class="py-4">
                                                        <div
                                                            class="grid grid-cols-1"
                                                        >
                                                            <div
                                                                class="p-1"
                                                                v-for="(
                                                                    list, idx
                                                                ) in checklistdata"
                                                                :key="idx"
                                                            >
                                                                <div
                                                                    class="flex items-center mb-4"
                                                                >
                                                                    <input
                                                                        v-model="
                                                                            checklist_form[
                                                                                idx +
                                                                                    1
                                                                            ]
                                                                        "
                                                                        :checked="
                                                                            list.checkbox
                                                                        "
                                                                        id="default-checkbox"
                                                                        type="checkbox"
                                                                        class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                                                    />
                                                                    <label
                                                                        for="default-checkbox"
                                                                        class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                                                                    >
                                                                        {{
                                                                            list.label
                                                                        }}
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <label
                                                                for="about"
                                                                class="block text-sm font-medium text-red-800 py-2"
                                                            >
                                                                Remarks:
                                                            </label>
                                                            <div class="mt-1">
                                                                <textarea
                                                                    v-model="
                                                                        form.remarks
                                                                    "
                                                                    id="about"
                                                                    name="about"
                                                                    rows="3"
                                                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"
                                                                    placeholder="Description"
                                                                ></textarea>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="py-4"
                                                            v-if="
                                                                permissions.includes(
                                                                    'Action Pull Itinerary'
                                                                )
                                                            "
                                                        >
                                                            <button
                                                                type="submit"
                                                                class="inline-flex justify-center py-2 px-10 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                            >
                                                                Complete
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- checklist history-->
                                    <div id="businesshistory">
                                        <Partials_HistoricalChecklist
                                            :pull_loading="pull_loading"
                                            :historical_checklist="
                                                historical_checklist
                                            "
                                        />

                                        <!-- {{ historical_checklist }} -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<style src="@vueform/multiselect/themes/default.css"></style>
<style scoped src="./../../assets/css/table.css"></style>
<style scoped>
.table-info-header {
    background-color: rgb(127, 29, 29);
}

.table-info-data {
    background-color: #e2e1de;
}

.back {
    background-image: url("../../assets/images/brown-crumpled-paper-texture-background.jpg");

    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    opacity: 0.9;
}

/* */

.div-table {
    display: table;
    width: auto;
    background-color: #eee;
    /* border: 1px solid #666666; */
    border-spacing: 5px; /* cellspacing:poor IE support for  this */
}
.div-table-row {
    display: table-row;
    width: auto;
    clear: both;
}
.div-table-col {
    float: left; /* fix for  buggy browsers */
    display: table-column;
    width: 200px;
    background-color: #ccc;
}
</style>
