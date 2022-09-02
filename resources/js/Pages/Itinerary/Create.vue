<script>
import BreezeAuthenticatedLayout from "./../../Layouts/Composition.vue";
import { Head, Link, usePage } from "@inertiajs/inertia-vue3";
import { ref, reactive, computed, onMounted, watch } from "vue";
import Multiselect from "@vueform/multiselect";
import useItineraries from "./../../composables/itineraries";
import useBusinesses from "./../../composables/business";
import useUsers from "./../../composables/user";
import { useToast } from "vue-toastification";
import Modal from "./../../Components/Modals/Modal.vue";
import Modalinfo from "./../../Components/Modals/Modal_info.vue";

import Modal_info from "./../../Components/Modals/Modal_v2.vue";

export default {
    components: {
        Modal_info,
        Modalinfo,
        Multiselect,
        BreezeAuthenticatedLayout,
        Head,
        Link,
        Modal,
    },
    props: ["hosting"],
    setup(props, { attrs, slots, emit, expose }) {
        console.log("setup");

        const toast = useToast();
        const hosting = computed(() => props.hosting);
        const Auth_User = computed(() => usePage().props.value.auth.user);
        const closeModal = ref(true);

        const businessSelection = ref(false);
        const form = reactive({});

        const headers = ref([
            {
                text: "BIN",
                value: "business_identification_number",
                sortable: false,
            },
            { text: "Business Name", value: "business_name", sortable: false },
            { text: "Owner", value: "owner", sortable: false },
            { text: "Address", value: "address", sortable: false },
            {
                text: "Line of Business",
                value: "line_of_business",
                sortable: false,
            },
            { text: "Capital", value: "capital", sortable: false },
            { text: "Barangay", value: "barangay", sortable: false },
            { text: "Action", value: "action", sortable: false },
        ]);

        let modal_show = ref(false);
        let modal_note = ref(false);
        let modal_status = ref(false);
        let modal_show_info = ref(false);
        const modal_status_info = ref(null);
        const itinerary_business = ref([]);
        const print = ref([false]);
        /* Datatable */
        const loading = ref(true);
        const selectedItems = ref([]);
        const serverItemsLength = ref(0);
        const serverOptions = ref({
            page: 1,
            rowsPerPage: 10,
            sortBy: "age",
            sortType: "desc",
        });
        const searchParameter = reactive({
            searchField: "",
            searchValue: "",
            filterField: "",
            filterValue: "",
        });
        /* Datatable */
        const { errors_I, storeItinerary, itinerary_id } = useItineraries();

        const { errors, businesses, business, getBusiness, loadFromServer } =
            useBusinesses();

        const { errors_U, users, getUsers } = useUsers();

        onMounted(async () => {
            form.user_id = Auth_User.value.id;
            await getUsers();
            server_sided();
            // loading.value = true;
            // await loadFromServer(
            //     businesses,
            //     serverItemsLength,
            //     serverOptions,
            //     searchParameter
            // );
            // loading.value = false;
        });

        const togglemodal = () => {
            if (errors_I.value) {
                toast.error("Create failed.");
            } else {
                form.name = "";
                form.requestdate = "";
                form.assign_id = "";
                form.notes = "";
                toast.success("Create success.");
            }
        };

        const createItinerary = async () => {
            selectedItems.value = [];
            itinerary_business.value = [];
            form.user_id = Auth_User.value.id;

            await toast.success("Sending", {
                timeout: 1000,
            });

            await storeItinerary({ ...form }).then(() => {
                togglemodal();
            });
        };

        const server_sided = _.debounce(async () => {
            loading.value = true;
            await loadFromServer(
                businesses,
                serverItemsLength,
                serverOptions,
                searchParameter
            );
            FilterSelection();
            loading.value = false;
        }, 500);

        /* method */

        function togglemodal_info() {
            console.log(modal_show_info);
            modal_show_info.value = !modal_show_info.value;
        }

        const handleSearch = () => {
            server_sided();
        };

        const fetch_selectfield_composition = async (query) => {
            let data;
            await axios
                .post("/api/cstm/business/get_selectfield", {
                    searchValue: query,
                    field: searchParameter.filterField,
                })
                .then((response) => {
                    data = response.data.data.map((item) => {
                        return {
                            value: eval("item." + searchParameter.filterField),
                            label: eval("item." + searchParameter.filterField),
                        };
                    });
                });

            return data;
        };

        const toggleBusinesses = () => {
            businessSelection.value = !businessSelection.value;
        };

        const toggleModal_dttble = (data) => {
            closeModal.value = !closeModal.value;
        };

        const showInfo = async (id) => {
            await getBusiness(id);
            togglemodal_info();
        };
        const removeBusiness = async (item) => {
            toast.info("Remove");
            itinerary_business.value.splice(item.key, 1);
            await server_sided();
            FilterSelection();
        };

        const showToggleBusinesses = () => {
            toast.info("Show Businnesses");
            toggleBusinesses();
        };

        const closeModalBusiness = () => {
            businessSelection.value = false;
        };
        const addBusinesses = () => {
            toast.info("Add");

            itinerary_business.value = itinerary_business.value.concat(
                selectedItems.value
            );

            itinerary_business.value = [
                ...new Map(
                    itinerary_business.value.map((item) => [item["id"], item])
                ).values(),
            ];

            let data = [];
            itinerary_business.value.forEach((item) => {
                data.push(item.id);
            });
            form.businesses = data.toString();
            selectedItems.value = [];
            toggleBusinesses();
            FilterSelection();
        };
        const PrintItinerary = () => {
            const url = hosting.value + "/view-pdf/" + itinerary_id.value;
            window.open(url);
        };

        const FilterSelection = () => {
            businesses.value = businesses.value.filter(
                (ar) => !itinerary_business.value.find((rm) => rm.id === ar.id)
            );
        };
        /* watch */

        watch(
            () => serverOptions.value,
            (currentValue, oldValue) => {
                console.log(currentValue);
                server_sided();
            },
            { deep: true }
        );
        watch(
            () => searchParameter.searchValue,
            (value) => {
                server_sided();
            }
        );
        watch(
            () => selectedItems.value,
            (currentValue) => {},
            { deep: true }
        );
        watch(
            () => itinerary_id.value,
            (currentValue) => {
                print.value = true;
            },
            { deep: true }
        );

        /* make selected comparison with current business and selected business start*/
        watch(
            () => itinerary_business.value,
            (currentValue, oldValue) => {
                console.log("filter");
            },
            { deep: true }
        );

        watch(
            () => businesses.value,
            (currentValue, oldValue) => {
                // FilterSelection();
            },
            { deep: true }
        );
        /* make selected comparison with current business and selected business end*/
        return {
            errors_I,
            loading,
            itinerary_id,
            print,
            PrintItinerary,
            modal_show,
            modal_note,
            modal_status,
            form,
            createItinerary,
            users,
            Auth_User,
            headers,
            businesses,
            business,
            selectedItems,
            handleSearch,
            fetch_selectfield_composition,
            showInfo,
            modal_show_info,
            modal_status_info,
            togglemodal_info,
            itinerary_business,
            addBusinesses,
            removeBusiness,
            closeModal,
            toggleModal_dttble,
            businessSelection,
            toggleBusinesses,
            showToggleBusinesses,
            closeModalBusiness,
            serverOptions,
            serverItemsLength,
            searchParameter,
            togglemodal,
        };
    },
};
</script>

<template>
    <Head title="Itinerary Create" />
    <BreezeAuthenticatedLayout>
        <template #header> Itinerary page </template>
        <div class="py-2">
            <div class="w-full mx-auto sm:px-6 lg:px-8">
                <div id="template" class="">
                    <div>
                        <!-- nav start -->
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
                                                class="ml-1 text-sm font-medium text-gray-700 hover:text-gray-900 md:ml-2 dark:text-gray-400 dark:hover:text-white"
                                                >Create
                                            </Link>
                                        </div>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <!-- nav end -->
                        <div class="bg-white rounded p-5 sm:p1">
                            <Modal
                                :showmodal="modal_show"
                                @toggle-modal="togglemodal"
                                :statusmodal="modal_status"
                                :note="modal_note"
                            >
                            </Modal>

                            <Modal_info
                                :showmodal="modal_show_info"
                                @toggle="togglemodal_info()"
                                :info="business"
                            >
                            </Modal_info>

                            <div v-if="!businessSelection">
                                <div v-if="errors_I">
                                    <div
                                        v-for="(v, k) in errors_I"
                                        :key="k"
                                        class="bg-red-500 text-white rounded font-bold mb-4 shadow-lg py-2 px-4 pr-0"
                                    >
                                        <p
                                            v-for="error in v"
                                            :key="error"
                                            class="text-sm"
                                        >
                                            {{ error }}
                                        </p>
                                    </div>
                                </div>
                                <form
                                    @submit.prevent="createItinerary"
                                    class="z-10"
                                >
                                    <div class="grid grid-cols-1 divide-y">
                                        <div
                                            class="w-full bg-red-500 py-2 px-2"
                                        >
                                            <label for="" class="text-white"
                                                >Itinerary
                                            </label>
                                        </div>
                                        <div class="p1">
                                            <div
                                                class="grid grid-cols-6 gap-6 py-6"
                                            >
                                                <div
                                                    class="col-span-6 sm:col-span-3"
                                                >
                                                    <div>
                                                        <h3
                                                            class="text-red-800 text-lg font-medium leading-6 text-gray-900"
                                                        >
                                                            Create Itinerary
                                                        </h3>
                                                        <p
                                                            class="mt-1 text-sm text-gray-600"
                                                        >
                                                            Add single/multiple
                                                            businesses, date and
                                                            itinerary name
                                                        </p>
                                                    </div>
                                                    <div>
                                                        <div
                                                            class="grid grid-cols-2 gap-1"
                                                        >
                                                            <div
                                                                class="col-span-6 sm:col-span-1"
                                                            >
                                                                <label
                                                                    for="first-name"
                                                                    class="block text-sm font-medium text-gray-700"
                                                                    >Name</label
                                                                >
                                                                <input
                                                                    type="text"
                                                                    v-model="
                                                                        form.name
                                                                    "
                                                                    autocomplete="given-name"
                                                                    class="focus:ring-2 focus:ring-blue-500 focus:outline-none appearance-none w-full text-sm leading-6 text-slate-900 placeholder-slate-400 rounded-md py-2 ring-1 ring-slate-200 shadow-sm"
                                                                />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div
                                                    class="col-span-6 sm:col-span-3"
                                                >
                                                    <div class="">
                                                        <div
                                                            class="flex justify-end"
                                                        >
                                                            <div
                                                                v-if="
                                                                    print ==
                                                                    true
                                                                "
                                                                class="px-1 py-1"
                                                            >
                                                                <button
                                                                    @click="
                                                                        PrintItinerary
                                                                    "
                                                                    type="button"
                                                                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center"
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

                                                                    <span
                                                                        >Print</span
                                                                    >
                                                                </button>
                                                            </div>
                                                            <div
                                                                class="px-1 py-1"
                                                            >
                                                                <Link
                                                                    :href="
                                                                        route(
                                                                            'itinerary'
                                                                        )
                                                                    "
                                                                >
                                                                    <button
                                                                        type="submit"
                                                                        class="bg-red-300 hover:bg-red-400 text-red-800 font-bold py-2 px-4 rounded inline-flex items-center"
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
                                                                                d="M6 18L18 6M6 6l12 12"
                                                                            />
                                                                        </svg>
                                                                        <span
                                                                            >Cancel</span
                                                                        >
                                                                    </button>
                                                                </Link>
                                                            </div>
                                                            <div
                                                                class="px-1 py-1"
                                                            >
                                                                <button
                                                                    type="submit"
                                                                    class="bg-green-300 hover:bg-green-400 text-green-800 font-bold py-2 px-4 rounded inline-flex items-center"
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
                                                                        >Create
                                                                    </span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="p1">
                                            <p class="py-2 text-red-800">
                                                Date and User Assignment
                                            </p>
                                            <div class="grid grid-cols-6 gap-6">
                                                <div
                                                    class="col-span-6 sm:col-span-3"
                                                >
                                                    <label
                                                        for="last-name"
                                                        class="block text-sm font-medium text-gray-700"
                                                        >Date</label
                                                    >
                                                    <input
                                                        v-model="
                                                            form.requestdate
                                                        "
                                                        class="focus:ring-2 focus:ring-blue-500 focus:outline-none appearance-none w-full text-sm leading-6 text-slate-900 placeholder-slate-400 rounded-md py-2 ring-1 ring-slate-200 shadow-sm"
                                                        id="grid-date"
                                                        type="date"
                                                    />
                                                </div>
                                                <div
                                                    class="col-span-6 sm:col-span-3"
                                                >
                                                    <label
                                                        for="last-name"
                                                        class="block text-sm font-medium text-gray-700"
                                                        >Assign To:</label
                                                    >
                                                    <select
                                                        class="focus:ring-2 focus:ring-blue-500 focus:outline-none appearance-none w-full text-sm leading-6 text-slate-900 placeholder-slate-400 rounded-md py-2 ring-1 ring-slate-200 shadow-sm"
                                                        v-model="form.assign_id"
                                                        name=""
                                                        id=""
                                                    >
                                                        <option
                                                            v-for="(
                                                                option, index
                                                            ) in users"
                                                            :key="index"
                                                            :value="option.id"
                                                        >
                                                            {{ option.name }}
                                                        </option>
                                                    </select>
                                                </div>
                                                <div
                                                    class="col-span-6 sm:col-span-3"
                                                ></div>
                                            </div>
                                        </div>

                                        <div>
                                            <div>
                                                <label
                                                    for="about"
                                                    class="block text-sm font-medium text-red-800 py-2"
                                                >
                                                    Description:
                                                </label>
                                                <p
                                                    class="mt-2 text-sm text-gray-500"
                                                >
                                                    Brief Itinerary description
                                                    (Optional)
                                                </p>
                                                <div class="mt-1">
                                                    <textarea
                                                        v-model="form.notes"
                                                        id="about"
                                                        name="about"
                                                        rows="3"
                                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"
                                                        placeholder="Description"
                                                    ></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="py-2">
                                            <div
                                                class="flex flex-col 2xl:flex-row xl:flex-row lg:flex-row"
                                            >
                                                <div
                                                    class="w-full 2xl:w-2/4 xl:w-2/4 lg:w-2/4 flex flex-col 2xl:flex-row xl:flex-row lg:flex-row justify-items-start place-content-start"
                                                >
                                                    <p
                                                        class="text-red-800 py-2"
                                                    >
                                                        Current selected
                                                        businesses
                                                    </p>
                                                </div>
                                                <div
                                                    class="w-full 2xl:w-2/4 xl:w-2/4 lg:w-2/4 flex flex-col 2xl:flex-row xl:flex-row lg:flex-row justify-items-end place-content-end"
                                                >
                                                    <button
                                                        type="button"
                                                        @click="
                                                            showToggleBusinesses
                                                        "
                                                        class="bg-blue-300 hover:bg-blue-400 text-blue-800 font-bold py-2 px-4 rounded inline-flex items-center"
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
                                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                                            />
                                                        </svg>

                                                        <span>
                                                            Show
                                                            Businesses</span
                                                        >
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="p1">
                                            <EasyDataTable
                                                show-index
                                                :headers="headers"
                                                :items="itinerary_business"
                                                table-class-name="customize-table z-10"
                                            >
                                                <template #expand="item">
                                                    <div class="">
                                                        <div
                                                            class="md:grid md:grid-rows"
                                                        >
                                                            <div
                                                                class="flex flex-items"
                                                            >
                                                                <!-- Info -->
                                                                <div
                                                                    class="py-1 px-1"
                                                                >
                                                                    <button
                                                                        type="button"
                                                                        @click="
                                                                            showInfo(
                                                                                item.id
                                                                            )
                                                                        "
                                                                        class="text-xs bg-yellow-700 hover:bg-yellow-400 text-white font-bold py-2 px-4 rounded"
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
                                                                        <span
                                                                            class="text-lg"
                                                                            >Info</span
                                                                        >
                                                                    </button>
                                                                </div>
                                                                <!-- Delete -->
                                                                <div
                                                                    class="p-1"
                                                                >
                                                                    <button
                                                                        type="button"
                                                                        @click="
                                                                            removeBusiness(
                                                                                item
                                                                            )
                                                                        "
                                                                        class="text-xs bg-red-700 hover:bg-red-400 text-white font-bold py-2 px-4 rounded"
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
                                                                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
                                                                            />
                                                                        </svg>

                                                                        <span
                                                                            class="text-lg"
                                                                            >Remove</span
                                                                        >
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                                <template #item-action="item">
                                                    <div
                                                        class="operation-wrapper flex"
                                                    >
                                                        <div class="p-1">
                                                            <a
                                                                @click="
                                                                    showInfo(
                                                                        item.id
                                                                    )
                                                                "
                                                                class="text-xs bg-yellow-700 hover:bg-yellow-400 text-white font-bold py-2 px-4 rounded"
                                                            >
                                                                Info
                                                            </a>
                                                        </div>
                                                        <div class="p-1">
                                                            <a
                                                                @click="
                                                                    removeBusiness(
                                                                        item
                                                                    )
                                                                "
                                                                class="text-xs bg-red-700 hover:bg-red-400 text-white font-bold py-2 px-4 rounded"
                                                            >
                                                                Remove
                                                            </a>
                                                        </div>
                                                    </div>
                                                </template>
                                            </EasyDataTable>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- 2. datatatable -->
                            <!-- drop-shadow-2xl relative inset-0 z-50 -->
                            <div class="p1" v-if="businessSelection">
                                <div class="p1">
                                    <div class="w-full bg-blue-700 py-2 px-2">
                                        <label for="" class="text-white"
                                            >Add Businesses</label
                                        >
                                    </div>

                                    <p class="text-blue-800 py-2">
                                        Search , Select and Add business to
                                        Itinerary
                                    </p>

                                    <div
                                        class="grid grid-cols-3 gap-6 py-2 pb-8"
                                    >
                                        <div class="col-span-1 sm:col-span-1">
                                            <label
                                                for="company-website"
                                                class="block text-sm font-medium"
                                            >
                                                Filter column:
                                            </label>

                                            <div class="group relative">
                                                <select
                                                    class="focus:ring-2 focus:ring-blue-500 focus:outline-none appearance-none w-full text-sm leading-6 text-slate-900 placeholder-slate-400 rounded-md py-2 ring-1 ring-slate-200 shadow-sm"
                                                    v-model="
                                                        searchParameter.filterField
                                                    "
                                                >
                                                    <option value="" selected>
                                                        None
                                                    </option>
                                                    <option
                                                        value="business_name"
                                                    >
                                                        Business Name
                                                    </option>
                                                    <option
                                                        value="business_identification_number"
                                                    >
                                                        BIN
                                                    </option>
                                                    <option value="capital">
                                                        Capital
                                                    </option>

                                                    <option
                                                        value="line_of_business"
                                                    >
                                                        Line of Business
                                                    </option>
                                                    <option value="owner">
                                                        Owner
                                                    </option>
                                                    <option value="address">
                                                        Address
                                                    </option>
                                                    <option value="barangay">
                                                        Barangay
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-span-1 sm:col-span-1">
                                            <label
                                                for="company-website"
                                                class="block text-sm font-medium"
                                            >
                                                Filter value:
                                            </label>

                                            <div class="group relative">
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

                                                <Multiselect
                                                    ref="multiselect_line_of_business"
                                                    mode="single"
                                                    v-model="
                                                        searchParameter.filterValue
                                                    "
                                                    class="focus:ring-2 focus:ring-blue-500 focus:outline-none appearance-none w-full text-sm leading-6 text-slate-900 placeholder-slate-400 rounded-md py-2 ring-1 ring-slate-200 shadow-sm"
                                                    placeholder=""
                                                    :filter-results="false"
                                                    :min-chars="1"
                                                    :resolve-on-load="false"
                                                    :delay="0"
                                                    :searchable="true"
                                                    :create-option="true"
                                                    :options="
                                                        async function (query) {
                                                            return await fetch_selectfield_composition(
                                                                query
                                                            );
                                                        }
                                                    "
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="grid grid-cols-3 gap-6 py-2 pb-8"
                                    >
                                        <div class="col-span-1 sm:col-span-1">
                                            <label
                                                for="company-website"
                                                class="block text-sm font-medium"
                                            >
                                                Search column:
                                            </label>

                                            <div class="group relative">
                                                <select
                                                    class="focus:ring-2 focus:ring-blue-500 focus:outline-none appearance-none w-full text-sm leading-6 text-slate-900 placeholder-slate-400 rounded-md py-2 ring-1 ring-slate-200 shadow-sm"
                                                    v-model="
                                                        searchParameter.searchField
                                                    "
                                                >
                                                    <option value="" selected>
                                                        ALL
                                                    </option>
                                                    <option
                                                        value="business_name"
                                                    >
                                                        Business Name
                                                    </option>
                                                    <option
                                                        value="business_identification_number"
                                                    >
                                                        BIN
                                                    </option>
                                                    <option value="capital">
                                                        Capital
                                                    </option>

                                                    <option
                                                        value="line_of_business"
                                                    >
                                                        Line of Business
                                                    </option>
                                                    <option value="owner">
                                                        Owner
                                                    </option>
                                                    <option value="address">
                                                        Address
                                                    </option>
                                                    <option value="barangay">
                                                        Barangay
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-span-1 sm:col-span-1">
                                            <label
                                                for="company-website"
                                                class="block text-sm font-medium"
                                            >
                                                Search value:
                                            </label>

                                            <div class="group relative">
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
                                                    v-model="
                                                        searchParameter.searchValue
                                                    "
                                                    class="focus:ring-2 focus:ring-blue-500 focus:outline-none appearance-none w-full text-sm leading-6 text-slate-900 placeholder-slate-400 rounded-md py-2 pl-10 ring-1 ring-slate-200 shadow-sm"
                                                    type="text"
                                                    aria-label="Search"
                                                    placeholder="Search..."
                                                />
                                            </div>
                                        </div>
                                        <div class="col-span-1 sm:col-span-1">
                                            <label
                                                for="company-website"
                                                class="block text-sm font-medium text-red-700"
                                                >&nbsp;
                                            </label>

                                            <button
                                                type="button"
                                                @click.prevent="handleSearch"
                                                class="bg-green-300 hover:bg-green-400 text-green-800 font-bold py-2 px-4 rounded inline-flex items-center"
                                            >
                                                <span>Search</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="">
                                    <div class="grid grid-cols-6 gap-6 py-6">
                                        <div class="col-span-6 sm:col-span-3">
                                            <p class="text-red-800 py-2">
                                                Add Additional Business
                                            </p>
                                        </div>
                                        <div class="col-span-6 sm:col-span-3">
                                            <div class="">
                                                <div class="flex justify-end">
                                                    <div
                                                        class="flex flex-items"
                                                    >
                                                        <div class="px-1">
                                                            <button
                                                                @click="
                                                                    addBusinesses
                                                                "
                                                                type="button"
                                                                class="bg-green-300 hover:bg-green-400 text-green-800 font-bold py-2 px-4 rounded inline-flex items-center"
                                                            >
                                                                <svg
                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-5 w-6"
                                                                    fill="none"
                                                                    viewBox="0 0 24 24"
                                                                    stroke="currentColor"
                                                                    stroke-width="2"
                                                                >
                                                                    <path
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                                                    />
                                                                </svg>

                                                                <span
                                                                    >Add
                                                                    Business</span
                                                                >
                                                            </button>
                                                        </div>
                                                        <div class="px-1">
                                                            <button
                                                                @click="
                                                                    closeModalBusiness
                                                                "
                                                                type="button"
                                                                class="bg-red-300 hover:bg-red-400 text-red-800 font-bold py-2 px-4 rounded inline-flex items-center"
                                                            >
                                                                <svg
                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-5 w-6"
                                                                    fill="none"
                                                                    viewBox="0 0 24 24"
                                                                    stroke="currentColor"
                                                                    stroke-width="2"
                                                                >
                                                                    <path
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        d="M6 18L18 6M6 6l12 12"
                                                                    />
                                                                </svg>

                                                                <span
                                                                    >Close</span
                                                                >
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="py-1">
                                    <EasyDataTable
                                        v-model:server-options="serverOptions"
                                        v-model:items-selected="selectedItems"
                                        :server-items-length="serverItemsLength"
                                        :rows-items="[10, 25, 50, 100]"
                                        show-index
                                        :headers="headers"
                                        :items="businesses"
                                        table-class-name="customize-table-add"
                                        :loading="loading"
                                    >
                                        <template #expand="item">
                                            <div class="">
                                                <div
                                                    class="md:grid md:grid-rows"
                                                >
                                                    <div
                                                        class="flex flex-items"
                                                    >
                                                        <!-- Edit -->
                                                        <div class="py-1 px-1">
                                                            <button
                                                                @click="
                                                                    showInfo(
                                                                        item.id
                                                                    )
                                                                "
                                                                class="text-xs bg-yellow-700 hover:bg-yellow-400 text-white font-bold py-2 px-4 rounded"
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
                                                                <span
                                                                    class="text-lg"
                                                                    >Info</span
                                                                >
                                                            </button>
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
                                                <!-- class="sr-only" -->
                                                <span>Loading...</span>
                                            </div>
                                        </template>
                                        <template #item-action="item">
                                            <div class="operation-wrapper flex">
                                                <div class="p-1">
                                                    <a
                                                        @click="
                                                            showInfo(item.id)
                                                        "
                                                        class="text-xs bg-yellow-700 hover:bg-yellow-400 text-white font-bold py-2 px-4 rounded"
                                                    >
                                                        Info
                                                    </a>
                                                </div>
                                            </div>
                                        </template>
                                    </EasyDataTable>
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
<style scoped src="./../../assets/css/table_blue.css"></style>
