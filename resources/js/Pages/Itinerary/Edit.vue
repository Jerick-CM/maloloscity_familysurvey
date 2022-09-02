<script>
import BreezeAuthenticatedLayout from "./../../Layouts/Composition.vue";
import { Head, Link, usePage } from "@inertiajs/inertia-vue3";
import { ref, reactive, computed, onMounted, watch } from "vue";
import Multiselect from "@vueform/multiselect";
import useItineraries from "./../../composables/itineraries";
import { useToast } from "vue-toastification";
import Modal from "./../../Components/Modals/Modal.vue";
import useUsers from "./../../composables/user";
import useBusinesses from "./../../composables/business";
import Modalinfo from "./../../Components/Modals/Modal_info.vue";

export default {
    components: {
        Multiselect,
        BreezeAuthenticatedLayout,
        Head,
        Link,
        Modal,
        Modalinfo,
    },
    setup(props, { attrs, slots, emit, expose }) {
        console.log("setup");
        const Auth_user = ref(usePage().props.value.auth.user);
        /* init */
        const businessSelection = ref(false);

        const toast = useToast();
        const permissions = usePage().props.value.auth.user.PermissionList;
        const { errors_U, users, getUsers } = useUsers();
        const {
            errors_I,
            itinerary,
            updateItinerary,
            getItinerary,
            selectedbusinesses,
            getItineraryPull,
            selectedbusinesses_ref,
            Add_Business,
            destroyItineraryBusiness,
        } = useItineraries();

        const { business, getBusiness, businesses, loadFromServer } =
            useBusinesses();

        const form = reactive({});

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

        let multiselect_clear = ref(false);
        let modal_show = ref(false);
        let modal_note = ref(false);
        let modal_status = ref(false);
        let modal_show_info = ref(false);
        const modal_status_info = ref(null);
        const headers_current = ref([
            { text: "Id", value: "id" },
            {
                text: "BIN",
                value: "business_identification_number",
                sortable: false,
            },
            { text: "Business Name", value: "business_name" },
            {
                text: "Line of Business",
                value: "line_of_business",
                sortable: false,
            },
            { text: "Capital", value: "capital", sortable: false },
            { text: "Address", value: "address", sortable: false },

            { text: "Barangay", value: "barangay", sortable: false },
            { text: "Action", value: "action", sortable: false },
        ]);

        const headers = ref([
            { text: "Id", value: "id" },
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

        const saveItinerary = async () => {
            toast.info("Sending", {
                timeout: 2000,
            });
            itinerary.value.user_id = Auth_user.value.id;

            await updateItinerary(route().params.id).then(() => {
                if (errors_I.value) {
                    toast.error("Error");
                } else {
                    toast.success("Success");
                }
            });
        };

        onMounted(async () => {
            await server_sided();
            await getItineraryPull(route().params.id);
            await getItinerary(route().params.id);
            await getUsers();
        });

        /* method */
        function togglemodal() {
            if (errors_I.value) {
                toast.error("Error", {
                    timeout: 5000,
                });
                modal_status.value = false;
                modal_note.value = "Error.";
            } else {
                toast.success("Success", {
                    timeout: 5000,
                });
                modal_status.value = true;
                modal_note.value = "Update successful.";
            }
        }

        const Add_Businesses = async () => {
            FilterSelection();
            toast.info("Add");

            let current_businesses = itinerary.value.businesses
                .split(",")
                .map(function (item) {
                    return parseInt(item, 10);
                });

            let data = [];
            selectedItems.value.forEach((item) => {
                data.push(item.id);
            });

            data = data.filter(function (el) {
                return current_businesses.indexOf(el) < 0;
            });

            form.newbusiness = data.toString();

            itinerary.value.businesses =
                itinerary.value.businesses + "," + form.newbusiness;

            form.itin_id = route().params.id;
            if (form.newbusiness == null) {
                toast.error("Failed.");
                return 0;
            }
            await Add_Business({ ...form }).then(() => {
                selectedItems.value = [];
                if (errors_I.value) {
                    toast.error("Failed.");
                } else {
                    toast.success("Success.");
                }
            });

            form.newbusiness = null;

            await getItinerary(route().params.id);
            await getItineraryPull(route().params.id);
            closeModalBusiness();
        };

        const FilterSelection = () => {
            businesses.value = businesses.value.filter(
                (ar) =>
                    !selectedbusinesses_ref.value.find((rm) => rm.id === ar.id)
            );
        };

        const deleteItineraryBusiness = async (id) => {
            if (!window.confirm("Are you sure?")) {
                return;
            }
            toast.info("Sending delete", {
                timeout: 2000,
            });
            form.business_itinerary_id = id;
            await destroyItineraryBusiness({ ...form }).then(() => {
                returns();
            });
            await getItinerary(route().params.id);
            await getItineraryPull(route().params.id); //use id of table
            FilterSelection();
        };

        const returns = () => {
            if (errors_I.value) {
                toast.error("Failed.");
            } else {
                toast.success("Success.");
            }
        };

        const showInfo = async (id) => {
            await getBusiness(id);
            togglemodal_info();
        };
        function togglemodal_info() {
            modal_show_info.value = !modal_show_info.value;
        }
        const handleSearch = () => {
            server_sided();
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
        const closeModalBusiness = () => {
            businessSelection.value = false;
        };
        const showToggleBusinesses = () => {
            toast.info("Show Businnesses");
            FilterSelection();
            toggleBusinesses();
        };
        /* watch */

        /* make selected comparison with current business and selected business start*/

        watch(
            () => selectedbusinesses_ref.value,
            (currentValue, oldValue) => {
                // businesses.value = businesses.value.filter(
                //     (ar) =>
                //         !selectedbusinesses_ref.value.find(
                //             (rm) => rm.id === ar.id
                //         )
                // );
            },
            { deep: true }
        );
        /* make selected comparison with current business and selected business end*/
        watch(
            () => searchParameter.searchValue,
            (value) => {
                server_sided();
            }
        );

        watch(
            () => selectedItems.value,
            (currentValue, oldValue) => {
                let current_businesses = itinerary.value.businesses
                    .split(",")
                    .map(function (item) {
                        return parseInt(item, 10);
                    });

                let data = [];
                currentValue.forEach((item) => {
                    data.push(item.id);
                });

                data = data.filter(function (el) {
                    return current_businesses.indexOf(el) < 0;
                });

                form.newbusiness = data.toString();
            },
            { deep: true }
        );

        return {
            loading,
            errors_I,
            form,
            users,
            Auth_user,

            modal_show,
            modal_note,
            modal_status,
            togglemodal,
            selectedbusinesses,
            multiselect_clear,
            itinerary,
            saveItinerary,
            selectedbusinesses_ref,
            headers_current,
            headers,
            selectedItems,
            businesses,
            business,
            Add_Businesses,
            deleteItineraryBusiness,
            showInfo,
            modal_show_info,
            modal_status_info,
            togglemodal_info,
            handleSearch,
            fetch_selectfield_composition,
            serverItemsLength,
            serverOptions,
            searchParameter,
            businessSelection,
            showToggleBusinesses,
            closeModalBusiness,
            permissions,
        };
    },
};
</script>
<template>
    <Head title="Itinerary Edit" />
    <BreezeAuthenticatedLayout>
        <template #header> Itinerary page </template>
        <div class="py-2">
            <div class="w-full mx-auto sm:px-6 lg:px-8">
                <Modal
                    :showmodal="modal_show"
                    @toggle-modal="togglemodal"
                    :statusmodal="modal_status"
                    :note="modal_note"
                >
                </Modal>
                <Modalinfo
                    :showmodal="modal_show_info"
                    @toggle-modal="togglemodal_info()"
                    :info="business"
                    :statusmodal="modal_status_info"
                >
                </Modalinfo>
                <div id="template" class="">
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
                                            >Edit</Link
                                        >
                                    </div>
                                </li>
                            </ol>
                        </nav>
                        <div class="bg-white rounded p-5 sm:p1">
                            <div v-if="!businessSelection">
                                <form class="" @submit.prevent="saveItinerary">
                                    <div class="grid grid-cols-1 divide-y">
                                        <div class="hidden">
                                            {{ itinerary.businesses }}
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
                                                            Edit Itinerary
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
                                                                        itinerary.name
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
                                                                class="px-1 py-1"
                                                            >
                                                                <Link
                                                                    class="inline-flex justify-center py-2 px-10 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                                    :href="
                                                                        route(
                                                                            'itinerary'
                                                                        )
                                                                    "
                                                                >
                                                                    Cancel
                                                                </Link>
                                                            </div>
                                                            <div
                                                                class="px-1 py-1"
                                                            >
                                                                <button
                                                                    type="submit"
                                                                    class="inline-flex justify-center py-2 px-10 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                                >
                                                                    Update
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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
                                                <div class="mt-1">
                                                    <textarea
                                                        v-model="
                                                            itinerary.notes
                                                        "
                                                        id="about"
                                                        name="about"
                                                        rows="3"
                                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"
                                                        placeholder="Description"
                                                    ></textarea>
                                                </div>
                                                <p
                                                    class="mt-2 text-sm text-gray-500"
                                                >
                                                    Brief Itinerary description
                                                    (Optional)
                                                </p>
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
                                                            itinerary.requestdate
                                                        "
                                                        class="focus:ring-2 focus:ring-blue-500 focus:outline-none appearance-none w-full text-sm leading-6 text-slate-900 placeholder-slate-400 rounded-md py-2 ring-1 ring-slate-200 shadow-sm"
                                                        id="grid-date"
                                                        type="date"
                                                    />
                                                </div>
                                                <div
                                                    class="col-span-6 sm:col-span-3"
                                                    v-if="
                                                        permissions.includes(
                                                            'Action Edit-AssignTo Itinerary'
                                                        )
                                                    "
                                                >
                                                    <label
                                                        for="last-name"
                                                        class="block text-sm font-medium text-gray-700"
                                                        >Assign To:</label
                                                    >
                                                    <select
                                                        class="focus:ring-2 focus:ring-blue-500 focus:outline-none appearance-none w-full text-sm leading-6 text-slate-900 placeholder-slate-400 rounded-md py-2 ring-1 ring-slate-200 shadow-sm"
                                                        v-model="
                                                            itinerary.assign_id
                                                        "
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

                                        <div class="p-1">
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

                                            <div class="py-2">
                                                <EasyDataTable
                                                    show-index
                                                    :headers="headers_current"
                                                    :items="
                                                        selectedbusinesses_ref
                                                    "
                                                    table-class-name="customize-table"
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
                                                                        class="py-1 px-1"
                                                                    >
                                                                        <button
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
                                                    <template
                                                        #item-action="item"
                                                    >
                                                        <div
                                                            class="flex flex-items"
                                                        >
                                                            <div class="px-1">
                                                                <a
                                                                    href="#"
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
                                                            <div class="px-1">
                                                                <a
                                                                    href="#"
                                                                    @click="
                                                                        deleteItineraryBusiness(
                                                                            item.iten_busi_id
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
                                    </div>
                                </form>
                            </div>

                            <div v-if="businessSelection">
                                <div class="p1">
                                    <div class="w-full bg-blue-700 py-2 px-2">
                                        <label for="" class="text-white"
                                            >Add Businesses</label
                                        >
                                    </div>
                                    <p class="text-red-800 py-2">
                                        Search Filter
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
                                                Filter column:
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
                                            <div class="group relative mt-2">
                                                <a
                                                    href="#"
                                                    @click.prevent="
                                                        handleSearch
                                                    "
                                                    class="py-4 px-7 text-xs bg-green-700 hover:bg-green-400 text-white font-bold py-2 px-4 rounded"
                                                >
                                                    Search
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="py-1">
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
                                                                    Add_Businesses
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
                                <div class="p1">
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
                                                    <!-- href="#" -->
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
