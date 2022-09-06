<script>
import BreezeAuthenticatedLayout from "./../../Layouts/Authenticated.vue";
import { Head, Link, usePage } from "@inertiajs/inertia-vue3";
import { ref, onMounted, reactive, watch, computed } from "vue";
import useLogs from "./../../composables/logs";
import { useToast } from "vue-toastification";
import BreezeDropdown from "./../../Components/Dropdown/Dropdown.vue";

export default {
    components: {
        BreezeDropdown,
        BreezeAuthenticatedLayout,
        Head,
        Link,
    },
    setup(props) {

        const Auth_user = computed(() => usePage().props.value.auth.user);

        const toast = useToast();
        const form = reactive({});
        /* Datatable */
        const loading = ref(true);
        // const selectedItems = ref([]);
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
        const { logs, loadFromServer } = useLogs();
        const headers = ref([
            { text: "Id", value: "id" },
            { text: "User Email", value: "useremail" },
            { text: "User Name", value: "username" },
            { text: "Type", value: "type_desc", sortable: true },
            { text: "Description", value: "description" },
            { text: "Date/Time ", value: "createddate", sortable: true },
        ]);

        const server_sided = _.debounce(async () => {
            loading.value = true;
            await loadFromServer(
                logs,
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

        onMounted(async () => {
            toast.info("Loading Logs");
            server_sided();
        });

        watch(
            () => serverOptions.value,
            (currentValue, oldValue) => {
                server_sided();
            },
            { deep: true }
        );

        return {
            form,
            headers,
            Auth_user,
            logs,
            serverItemsLength,
            serverOptions,
            loading,
            searchParameter,
        };
    },
};
</script>
<template>
    <BreezeAuthenticatedLayout>
        <Head title="Logs" />
        <template #header> Logs page </template>
        <div class="">
            <!-- <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"> -->
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
                                        >Logs</Link
                                    >
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>

                <div class="flex place-content-end mb-4"></div>

                <!--  -->
                <div class="bg-white p-8 rounded-lg">
                    <div class="grid grid-cols-3 gap-6 py-2">
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
                    </div>
                    <div>
                        <EasyDataTable
                            show-index
                            v-model:server-options="serverOptions"
                            :server-items-length="serverItemsLength"
                            :headers="headers"
                            :items="logs"
                            table-class-name="customize-table"
                            :loading="loading"
                            :rows-items="[10, 25, 50, 100]"
                        >
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
