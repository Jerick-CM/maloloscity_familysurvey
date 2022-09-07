<script>
import BreezeAuthenticatedLayout from "./../../Layouts/Authenticated.vue";
import BreezeDropdown from "./../../Components/Dropdown/Dropdown.vue";
import { Head, Link, usePage } from "@inertiajs/inertia-vue3";
import { ref, onMounted, reactive, watch, computed } from "vue";
import Multiselect from "@vueform/multiselect";
import { useToast } from "vue-toastification";

import Breadcrumb from "./../../Components/BreadCrumb/navSurveyIndex.vue";
import useFamilySurveys from "./../../composables/familysurvey";
export default {
    components: {
        Breadcrumb,
        BreezeDropdown,
        Multiselect,
        BreezeAuthenticatedLayout,
        Head,
        Link,
    },
    props: ["hosting"],
    methods: {},
    data: () => ({
        collapseClass:
            "border-green-200 rounded-t rounded-t mb-0 px-4 py-3 border-0 bg-green-600",
        errorClass:
            "border-red-200 rounded-t rounded-t mb-0 px-4 py-3 border-0 bg-red-600",
        defaultClass:
            "border-slate-200 rounded-t rounded-t mb-0 px-4 py-3 border-0 bg-slate-600",
    }),

    setup(props) {
        const Auth_user = computed(() => usePage().props.value.auth.user);
        const permissions = usePage().props.value.auth.user.PermissionList;
        const hosting = computed(() => props.hosting);
        const toast = useToast();
        const form = reactive({});
        const {
            familysurveys,
            destroyFamilySurvey,
            errors_fs,
            loadFromServer,
        } = useFamilySurveys();

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
            { text: "Name", value: "full_name", sortable: true },
            {
                text: "Barangay",
                value: "barangay",
                sortable: false,
            },
            {
                text: "Family Position",
                value: "family_position",
                sortable: false,
            },
            {
                text: "Number of Children",
                value: "number_of_children",
                sortable: false,
            },
            {
                text: "Total Family in House",
                value: "number_of_people_in_household",
                sortable: false,
            },
            {
                text: "4P's Beneficiary",
                value: "fourps",
                sortable: false,
            },
            { text: "Date / Time", value: "createddate", sortable: false },
            { text: "Action", value: "action", sortable: false },
        ]);

        onMounted(async () => {
            await server_sided();
        });

        const server_sided = _.debounce(async () => {
            loading.value = true;
            await loadFromServer(
                familysurveys,
                serverItemsLength,
                serverOptions,
                searchParameter
            );
            loading.value = false;
        }, 500);

        const fetchSelectfield = async (query, field) => {
            let data;
            await axios
                .post("/request/familysurvey/getSelectfield", {
                    searchValue: query,
                    field: field,
                })
                .then((response) => {
                    data = response.data.data.map((item) => {
                        return {
                            value: eval("item." + field),
                            label: eval("item." + field),
                        };
                    });
                });

            return data;
        };

        const searchButton = () => {
            server_sided();
        };
        const generatePDF = () => {
            const url =
                hosting.value + "/report/pdf/" + searchParameter.filterValue;
            window.open(url);
        };

        const removeSurvey = async (id) => {
            if (!window.confirm("Are you sure?")) {
                return;
            }
            toast.info("Sending delete");
            await destroyFamilySurvey(id);
            await server_sided();
            await toast.success("Delete success.");
        };

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

        return {
            form,
            headers,
            Auth_user,
            loading,
            serverItemsLength,
            serverOptions,
            searchParameter,
            permissions,
            familysurveys,
            selectedItems,
            fetchSelectfield,
            searchButton,
            generatePDF,
            removeSurvey,
        };
    },
};
</script>
<template>
    <BreezeAuthenticatedLayout>
        <Head title="Family Survey" />
        <template #header> Family Survey </template>
        <div class="">
            <div class="pb-10 py-2 w-full mx-auto sm:px-6 lg:px-8">
                <Breadcrumb />
                <div class="flex flex-col 2xl:flex-row xl:flex-row lg:flex-row">
                    <div
                        class="py-2 w-full 2xl:w-2/4 xl:w-2/4 lg:w-2/4 flex flex-col 2xl:flex-row xl:flex-row lg:flex-row justify-items-start place-content-start"
                    ></div>
                    <div
                        class="w-full 2xl:w-2/4 xl:w-2/4 lg:w-2/4 flex flex-col 2xl:flex-row xl:flex-row lg:flex-row justify-items-end place-content-end"
                    >
                        <div
                            class="px-1"
                            v-if="
                                permissions.includes(
                                    'Action Download SurveyForm'
                                )
                            "
                        >
                            <button
                                @click.prevent="generatePDF()"
                                class="my-2 py-2 px-4 w-full 2xl:w-fit xl:w-fit lg:w-fit bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold rounded inline-flex items-center"
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

                                <span>Print Report</span>
                            </button>
                        </div>
                        <div class="px-1">
                            <Link :href="route('forms-create')">
                                <button
                                    class="my-2 py-2 px-4 w-full 2xl:w-fit xl:w-fit lg:w-fit bg-green-300 hover:bg-green-400 text-green-800 font-bold rounded inline-flex items-center"
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
                                    <span>Create Family Survey</span>
                                </button>
                            </Link>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-lg">
                    <div class="py-2 pb-8 md:grid md:grid-cols-3 md:gap-6">
                        <div class="col-span-1 sm:col-span-1">
                            <label
                                for="company-website"
                                class="block text-sm font-medium text-red-700"
                            >
                                Filter column:
                            </label>

                            <form class="group relative">
                                <select
                                    v-model="searchParameter.filterField"
                                    class="focus:ring-2 focus:ring-blue-500 focus:outline-none appearance-none w-full text-sm leading-6 text-slate-900 placeholder-slate-400 rounded-md py-2 ring-1 ring-slate-200 shadow-sm"
                                >
                                    <option value="" selected>None</option>
                                    <option value="barangay">Barangay</option>
                                </select>
                            </form>

                            <!-- </div> -->
                        </div>
                        <div class="col-span-1 sm:col-span-1">
                            <label
                                for="company-website"
                                class="block text-sm font-medium text-red-700"
                            >
                                Filter value:
                            </label>

                            <form class="group relative">
                                <Multiselect
                                    ref="multiselect_line_of_business"
                                    mode="single"
                                    v-model="searchParameter.filterValue"
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
                                            return await fetchSelectfield(
                                                query,
                                                'barangay'
                                            );
                                        }
                                    "
                                />
                            </form>
                        </div>
                    </div>
                    <!-- Search -->
                    <div class="py-2 pb-8 md:grid md:grid-cols-3 md:gap-6">
                        <div class="col-span-1 sm:col-span-1">
                            <label
                                for="company-website"
                                class="block text-sm font-medium text-red-700"
                            >
                                Search column:
                            </label>

                            <form class="group relative">
                                <select
                                    class="focus:ring-2 focus:ring-blue-500 focus:outline-none appearance-none w-full text-sm leading-6 text-slate-900 placeholder-slate-400 rounded-md py-2 ring-1 ring-slate-200 shadow-sm"
                                    v-model="searchParameter.searchField"
                                >
                                    <option value="" selected>ALL</option>
                                    <option value="full_name">Full Name</option>
                                </select>
                            </form>

                            <!-- </div> -->
                        </div>
                        <div class="col-span-1 sm:col-span-1">
                            <label
                                for="company-website"
                                class="block text-sm font-medium text-red-700"
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
                                    v-model="searchParameter.searchValue"
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
                            <div class="group relative">
                                <button
                                    type="button"
                                    @click.prevent="searchButton"
                                    class="bg-blue-300 hover:bg-blue-400 text-blue-800 font-bold py-2 px-4 rounded inline-flex items-center"
                                >
                                    <span>Search</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-6 py-2 hidden">
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
                    </div>
                    <div>
                        <EasyDataTable
                            show-index
                            v-model:server-options="serverOptions"
                            v-model:items-selected="selectedItems"
                            :server-items-length="serverItemsLength"
                            :headers="headers"
                            :items="familysurveys"
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
                                                            'Action Edit SurveyForm'
                                                        )
                                                    "
                                                    :href="
                                                        route(
                                                            'forms-edit',
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
                                                            'Action Delete SurveyForm'
                                                        )
                                                    "
                                                >
                                                    <button
                                                        @click="
                                                            removeSurvey(
                                                                item.id
                                                            )
                                                        "
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

                            <template #item-action="item">
                                <div class="operation-wrapper flex">
                                    <div
                                        class="p-1"
                                        v-if="
                                            permissions.includes(
                                                'Action Edit SurveyForm'
                                            )
                                        "
                                    >
                                        <Link
                                            :href="route('forms-edit', item.id)"
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
                                                'Action Delete SurveyForm'
                                            )
                                        "
                                    >
                                        <button
                                            @click="removeSurvey(item.id)"
                                            class="text-xs bg-red-700 hover:bg-red-400 text-white font-bold py-2 px-4 rounded"
                                        >
                                            Delete
                                        </button>
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
