<script>
import BreezeAuthenticatedLayout from "./../../Layouts/Authenticated.vue";
import { Head, Link, usePage } from "@inertiajs/inertia-vue3";
import { ref, onMounted, reactive, watch, computed } from "vue";
import Multiselect from "@vueform/multiselect";
import useItineraries from "./../../composables/itineraries";
import { useToast } from "vue-toastification";
import BreezeDropdown from "./../../Components/Dropdown/Dropdown.vue";

export default {
    components: {
        BreezeDropdown,
        Multiselect,
        BreezeAuthenticatedLayout,
        Head,
        Link,
    },
    methods: {},
    data: () => ({
        collapseClass:
            "border-green-200 rounded-t rounded-t mb-0 px-4 py-3 border-0 bg-green-600",
        errorClass:
            "border-red-200 rounded-t rounded-t mb-0 px-4 py-3 border-0 bg-red-600",
        defaultClass:
            "border-slate-200 rounded-t rounded-t mb-0 px-4 py-3 border-0 bg-slate-600",
    }),
    props: ["hosting"],
    setup(props) {
        console.log("setup");
        const Auth_user = computed(() => usePage().props.value.auth.user);
        const hosting = computed(() => props.hosting);
        const permissions = usePage().props.value.auth.user.PermissionList;
        const toast = useToast();
        const form = reactive({});

        /* Datatable */
        const loading = ref(true);
        const selectedItems = ref([]);
        const serverItemsLength = ref(0);
        const serverOptions = ref({
            page: 1,
            rowsPerPage: 10,
        });
        const searchParameter = reactive({
            searchField: "name",
            searchValue: "",
            filterField: "",
            filterValue: "",
        });

        /* Datatable */

        const headers = ref([
            { text: "Id", value: "id" },
            { text: "Control Number", value: "control_number" },
            { text: "Itinerary name", value: "name", sortable: true },
            { text: "Description", value: "notes", sortable: true },
            { text: "View", value: "qr_hash", sortable: true },
            {
                text: "Request Date",
                value: "requestdateformatted",
                sortable: true,
            },
            { text: "Time Completed", value: "completed_time", sortable: true },
            { text: "Date / Time", value: "createddate", sortable: true },
            { text: "Action", value: "action", sortable: true },
        ]);

        const {
            itineraries,
            getItineraries,
            exportItineraryData,
            exportItineraryBusinessData,
            exportItinerarySelected,
            destroyItinerarySelected,
            destroyItinerary_logs,
            loadFromServer,
        } = useItineraries();

        onMounted(async () => {
            await server_sided();
        });

        const server_sided = _.debounce(async () => {
            loading.value = true;
            await loadFromServer(
                Auth_user.value.id,
                itineraries,
                serverItemsLength,
                serverOptions,
                searchParameter
            );
            loading.value = false;
        }, 500);

        watch(
            () => searchParameter.searchValue,
            (value) => {
                server_sided();
            }
        );

        watch(
            () => serverOptions.value,
            (value) => {
                server_sided();
            }
        );
        function viewpdf(id) {
            const url = hosting.value + "/view-pdf/" + id;
            window.open(url);
        }

        function view_itinerary(hash) {
            const url = hosting.value + "/trace/" + hash;
            window.open(url);
        }

        const deleteItinerary = async (id) => {
            if (!window.confirm("Are you sure?")) {
                return;
            }
            toast.info("Sending delete");
            await destroyItinerary_logs(id, Auth_user.value.id);
            await getItineraries();
            await toast.success("Delete success.");
        };

        const exportItineraryBusiness = async () => {
            await exportItineraryBusinessData();
        };
        const exportItinerary = async () => {
            await exportItineraryData();
        };

        const exportSelected = async (data) => {
            await exportItinerarySelected(data);
        };

        const deleteSelected = async (data) => {
            if (!window.confirm("Are you sure?")) {
                return;
            }
            toast.info("Sending delete");
            await destroyItinerarySelected(data, Auth_user.value.id);
            await getItineraries();
            await toast.success("Delete success.");
        };

        return {
            form,
            headers,
            itineraries,
            Auth_user,
            getItineraries,
            viewpdf,
            view_itinerary,
            deleteItinerary,
            loading,
            exportItineraryBusiness,
            exportItinerary,
            selectedItems,
            exportSelected,
            deleteSelected,
            serverItemsLength,
            serverOptions,
            searchParameter,
            permissions,
        };
    },
};
</script>
<template>
    <BreezeAuthenticatedLayout>
        <Head title="Itinerary" />
        <template #header> Itinerary page </template>
        <div class="">
            <div class="pb-10 py-2 w-full mx-auto sm:px-6 lg:px-8">
                <div>
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
                                        href="#"
                                        class="ml-1 text-sm font-medium text-gray-700 hover:text-gray-900 md:ml-2 dark:text-gray-400 dark:hover:text-white"
                                        >Itinerary</Link
                                    >
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>
                <div class="flex flex-col 2xl:flex-row xl:flex-row lg:flex-row">
                    <div
                        class="py-2 w-full 2xl:w-2/4 xl:w-2/4 lg:w-2/4 flex flex-col 2xl:flex-row xl:flex-row lg:flex-row justify-items-start place-content-start"
                    ></div>
                    <div
                        class="w-full 2xl:w-2/4 xl:w-2/4 lg:w-2/4 flex flex-col 2xl:flex-row xl:flex-row lg:flex-row justify-items-end place-content-end"
                    >
                        <div class="px-1" v-if="selectedItems.length > 0">
                            <div
                                v-if="
                                    permissions.includes(
                                        'Action Download Itinerary'
                                    )
                                "
                            >
                                <button
                                    @click.prevent="
                                        exportSelected(selectedItems)
                                    "
                                    class="my-2 py-2 px-4 w-full 2xl:w-fit xl:w-fit lg:w-fit bg-green-300 hover:bg-green-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center"
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

                                    <span>Download Selected</span>
                                </button>
                            </div>
                        </div>

                        <div
                            class="px-1"
                            v-if="
                                permissions.includes(
                                    'Action Download Itinerary'
                                )
                            "
                        >
                            <button
                                @click.prevent="exportItineraryBusiness"
                                class="my-2 py-2 px-4 w-full 2xl:w-fit xl:w-fit lg:w-fit bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center"
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

                                <span>Download All with Business List</span>
                            </button>
                        </div>

                        <div
                            class="px-1"
                            v-if="
                                permissions.includes(
                                    'Action Download Itinerary'
                                )
                            "
                        >
                            <button
                                @click.prevent="exportItinerary"
                                class="my-2 py-2 px-4 w-full 2xl:w-fit xl:w-fit lg:w-fit bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center"
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

                                <span>Download All</span>
                            </button>
                        </div>

                        <div
                            class="px-1"
                            v-if="
                                permissions.includes(
                                    'Action Settings Checklist'
                                )
                            "
                        >
                            <Link :href="route('checklist')">
                                <button
                                    class="my-2 py-2 px-4 w-full 2xl:w-fit xl:w-fit lg:w-fit bg-blue-300 hover:bg-blue-400 text-blue-800 font-bold py-2 px-4 rounded inline-flex items-center"
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
                                            d="M5 13l4 4L19 7"
                                        />
                                    </svg>
                                    <span>Checklist</span>
                                </button>
                            </Link>
                        </div>

                        <div
                            class="px-1"
                            v-if="
                                permissions.includes('Action Create Itinerary')
                            "
                        >
                            <Link :href="route('itinerary-create')">
                                <button
                                    class="my-2 py-2 px-4 w-full 2xl:w-fit xl:w-fit lg:w-fit bg-green-300 hover:bg-green-400 text-green-800 font-bold py-2 px-4 rounded inline-flex items-center"
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
                                    <span>Create Itinerary</span>
                                </button>
                            </Link>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-lg">
                    <div class="grid grid-cols-3 gap-6 py-2">
                        <div class="col-span-1 sm:col-span-1">
                            <label
                                for="company-website"
                                class="block text-sm font-medium text-red-700"
                            >
                                Search value:
                            </label>

                            <form class="group relative">
                                <svg
                                    width="20"
                                    height="20"
                                    fill="currentColor"
                                    class="absolute left-3 top-1/2 -mt-2.5 text-slate-400 pointer-events-none group-focus-within:text-blue-500"
                                    aria-hidden="true"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    />
                                </svg>
                                <input
                                    v-model="searchParameter.searchValue"
                                    class="focus:ring-2 focus:ring-blue-500 focus:outline-none appearance-none w-full text-sm leading-6 text-slate-900 placeholder-slate-400 rounded-md py-2 pl-10 ring-1 ring-slate-200 shadow-sm"
                                    type="text"
                                    aria-label="Search"
                                    placeholder="Search..."
                                />
                            </form>
                        </div>

                        <div class="col-span-1 sm:col-span-1 invisible">
                            <label
                                for="company-website"
                                class="block text-sm font-medium text-gray-700"
                            >
                                search field:
                            </label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <select
                                    class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300"
                                    v-model="searchParameter.searchField"
                                >
                                    <option value="name">Name</option>
                                </select>
                            </div>
                        </div>

                        <div
                            v-if="
                                permissions.includes('Action Delete Itinerary')
                            "
                        >
                            <div
                                class="px-1 float-right"
                                v-if="selectedItems.length > 0"
                            >
                                <button
                                    @click.prevent="
                                        deleteSelected(selectedItems)
                                    "
                                    class="bg-red-500 hover:bg-red-600 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center text-white"
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
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                        />
                                    </svg>
                                    <span>Delete Selected</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div>
                        <EasyDataTable
                            show-index
                            v-model:server-options="serverOptions"
                            v-model:items-selected="selectedItems"
                            :server-items-length="serverItemsLength"
                            :headers="headers"
                            :items="itineraries"
                            table-class-name="customize-table"
                            :loading="loading"
                            :rows-items="[10, 25, 50, 100]"
                        >
                            <!--  -->
                            <template #expand="item">
                                <div class="">
                                    <div class="md:grid md:grid-rows">
                                        <div class="flex flex-items">
                                            <!-- Edit -->
                                            <div class="py-1 px-1">
                                                <Link
                                                    v-if="
                                                        permissions.includes(
                                                            'Action Edit Itinerary'
                                                        )
                                                    "
                                                    :href="
                                                        route(
                                                            'itinerary-edit',
                                                            item.id
                                                        )
                                                    "
                                                >
                                                    <button
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
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                                            />
                                                        </svg>
                                                        <span class="text-lg"
                                                            >Edit</span
                                                        >
                                                    </button>
                                                </Link>
                                            </div>
                                            <!-- Delete -->
                                            <div class="py-1 px-1">
                                                <Link
                                                    v-if="
                                                        permissions.includes(
                                                            'Action Delete Itinerary'
                                                        )
                                                    "
                                                    :href="
                                                        route(
                                                            'itinerary-edit',
                                                            item.id
                                                        )
                                                    "
                                                >
                                                    <button
                                                        class="h-15 w-24 text-xs bg-red-700 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
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
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                            />
                                                        </svg>

                                                        <span class="text-lg"
                                                            >Delete</span
                                                        >
                                                    </button>
                                                </Link>
                                            </div>
                                            <!-- Preview -->
                                            <div class="py-1 px-1">
                                                <Link
                                                    v-if="
                                                        permissions.includes(
                                                            'Action Edit Itinerary'
                                                        )
                                                    "
                                                    :href="
                                                        route(
                                                            'itinerary-edit',
                                                            item.id
                                                        )
                                                    "
                                                >
                                                    <button
                                                        class="h-15 w-24 text-xs bg-blue-700 hover:bg-blue-400 text-white font-bold py-2 px-4 rounded"
                                                    >
                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            fill="none"
                                                            viewBox="0 0 24 24"
                                                            stroke-width="1.5"
                                                            stroke="currentColor"
                                                            class="w-6 h-6"
                                                        >
                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"
                                                            />
                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                                            />
                                                        </svg>

                                                        <span class="text-lg"
                                                            >Preview</span
                                                        >
                                                    </button>
                                                </Link>
                                            </div>
                                            <!-- Pull -->
                                            <div class="py-1 px-1">
                                                <Link
                                                    v-if="
                                                        permissions.includes(
                                                            'Action Edit Itinerary'
                                                        )
                                                    "
                                                    :href="
                                                        route(
                                                            'itinerary-edit',
                                                            item.id
                                                        )
                                                    "
                                                >
                                                    <button
                                                        class="h-15 w-24 text-xs bg-green-700 hover:bg-green-400 text-white font-bold py-2 px-4 rounded"
                                                    >
                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            fill="none"
                                                            viewBox="0 0 24 24"
                                                            stroke-width="1.5"
                                                            stroke="currentColor"
                                                            class="w-6 h-6"
                                                        >
                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                d="M10.125 2.25h-4.5c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125v-9M10.125 2.25h.375a9 9 0 019 9v.375M10.125 2.25A3.375 3.375 0 0113.5 5.625v1.5c0 .621.504 1.125 1.125 1.125h1.5a3.375 3.375 0 013.375 3.375M9 15l2.25 2.25L15 12"
                                                            />
                                                        </svg>

                                                        <span class="text-lg"
                                                            >Check</span
                                                        >
                                                    </button>
                                                </Link>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <template #loading>
                                <div role="status">
                                    <svg
                                        aria-hidden="true"
                                        class="mr-2 w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
                                        viewBox="0 0 100 101"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                            fill="currentColor"
                                        />
                                        <path
                                            d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                            fill="currentFill"
                                        />
                                    </svg>

                                    <span>Loading...</span>
                                </div>
                            </template>
                            <template #item-qr_hash="item">
                                <div class="operation-wrapper flex">
                                    <div class="p-1">
                                        <button
                                            @click="
                                                view_itinerary(item.qr_hash)
                                            "
                                            class="text-xs bg-blue-700 hover:bg-blue-400 text-white font-bold py-2 px-4 rounded"
                                        >
                                            View
                                        </button>
                                    </div>
                                </div>
                            </template>
                            <template #item-action="item">
                                <div class="operation-wrapper flex">
                                    <BreezeDropdown
                                        align="right"
                                        width="48"
                                        class="hidden"
                                    >
                                        <template #trigger>
                                            <span
                                                class="inline-flex rounded-md"
                                            >
                                                <button
                                                    type="button"
                                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                                >
                                                    Select
                                                    <svg
                                                        class="ml-2 -mr-0.5 h-4 w-4"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 20 20"
                                                        fill="currentColor"
                                                    >
                                                        <path
                                                            fill-rule="evenodd"
                                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                            clip-rule="evenodd"
                                                        />
                                                    </svg>
                                                </button>
                                            </span>
                                        </template>
                                        <template #content>
                                            <Link
                                                v-if="
                                                    permissions.includes(
                                                        'Action Edit Itinerary'
                                                    )
                                                "
                                                class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                                :href="
                                                    route(
                                                        'itinerary-edit',
                                                        item.id
                                                    )
                                                "
                                            >
                                                <button type="button">
                                                    <span
                                                        class="inline-flex rounded-md"
                                                    >
                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            class="stroke-green-700 w-4 h-4 mr-2"
                                                            fill="none"
                                                            viewBox="0 0 24 24"
                                                            stroke="currentColor"
                                                            stroke-width="2"
                                                        >
                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"
                                                            />
                                                        </svg>
                                                        <span>Edit</span>
                                                    </span>
                                                </button>
                                            </Link>

                                            <button
                                                v-if="
                                                    permissions.includes(
                                                        'Action Delete Itinerary'
                                                    )
                                                "
                                                class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                                @click="
                                                    deleteItinerary(item.id)
                                                "
                                            >
                                                <span
                                                    class="inline-flex rounded-md"
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        class="stroke-red-700 w-4 h-4 mr-2"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                        />
                                                    </svg>
                                                    <span>Delete</span>
                                                </span>
                                            </button>

                                            <button
                                                @click="viewpdf(item.id)"
                                                class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                            >
                                                <span
                                                    class="inline-flex rounded-md"
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        class="stroke-blue-700 w-4 h-4 mr-2"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                                        />
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                                        />
                                                    </svg>
                                                    <span>Preview</span>
                                                </span>
                                            </button>

                                            <Link
                                                :href="
                                                    route(
                                                        'itinerary-pull',
                                                        item.id
                                                    )
                                                "
                                            >
                                                <button
                                                    class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                                >
                                                    <span
                                                        class="inline-flex rounded-md"
                                                    >
                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            class="stroke-green-700 w-4 h-4 mr-2"
                                                            fill="none"
                                                            viewBox="0 0 24 24"
                                                            stroke="currentColor"
                                                            stroke-width="2"
                                                        >
                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"
                                                            />
                                                        </svg>
                                                        <span>Check</span>
                                                    </span>
                                                </button>
                                            </Link>
                                        </template>
                                    </BreezeDropdown>
                                    <div class="p-1">
                                        <Link
                                            :href="
                                                route('itinerary-edit', item.id)
                                            "
                                        >
                                            <button
                                                type="button"
                                                class="text-xs bg-green-700 hover:bg-green-400 text-white font-bold py-2 px-4 rounded"
                                            >
                                                Edit
                                            </button>
                                        </Link>
                                    </div>
                                    <div
                                        class="p-1"
                                        v-if="
                                            permissions.includes(
                                                'Action Delete Itinerary'
                                            )
                                        "
                                    >
                                        <button
                                            @click="deleteItinerary(item.id)"
                                            class="text-xs bg-red-700 hover:bg-red-400 text-white font-bold py-2 px-4 rounded"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                    <div class="p-1">
                                        <button
                                            @click="viewpdf(item.id)"
                                            class="text-xs bg-blue-700 hover:bg-blue-400 text-white font-bold py-2 px-4 rounded"
                                        >
                                            Preview
                                        </button>
                                    </div>
                                    <div class="p-1">
                                        <Link
                                            :href="
                                                route('itinerary-pull', item.id)
                                            "
                                        >
                                            <button
                                                class="text-xs bg-green-700 hover:bg-green-400 text-white font-bold py-2 px-4 rounded"
                                            >
                                                Check
                                            </button>
                                        </Link>
                                    </div>
                                </div>
                            </template>
                        </EasyDataTable>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<style src="@vueform/multiselect/themes/default.css"></style>
<style scoped src="./../../assets/css/table.css"></style>

<style scoped>
.vue3-easy-data-table__main .fixed-header {
    padding-bottom: 200px;
    margin-bottom: 200px;
}
</style>
