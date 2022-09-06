<script>
import BreezeAuthenticatedLayout from "./../../Layouts/Authenticated.vue";
import { Head, Link, usePage } from "@inertiajs/inertia-vue3";
import BreezeNavLink from "./../../Components/NavLink.vue";
import useUsers from "./../../composables/user";
import { ref, onMounted, reactive, watch, computed } from "vue";
import { useToast } from "vue-toastification";
import Nav from "./../../Components/Nav/BreadCrumb.vue";

export default {
    components: {
        Head,
        Nav,
        BreezeAuthenticatedLayout,
        Link,
        BreezeNavLink,
    },
    setup() {

        const toast = useToast();
        const Auth_User = ref(usePage().props.value.auth.user);
        const permissions = usePage().props.value.auth.user.PermissionList;
        const {
            users,
            getUsers,
            getUsersById,
            exportUserData,
            destroyUser_with_logs,
            loadFromServer,
            errors_users_table,
        } = useUsers();
        const isLoading = ref(false);
        const headers = ref([
            // {
            //     text: "Id",
            //     value: "id",
            //     sortable: false,
            // },
            { text: "Name", value: "name", sortable: false },
            { text: "Email", value: "email", sortable: false },
            { text: "Role", value: "userrole", sortable: false },
            { text: "Date/Time", value: "createddate", sortable: false },
            { text: "Action", value: "action", sortable: false },
        ]);

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

        const form = reactive({});

        onMounted(async () => {
            await server_sided();
        });

        const server_sided = _.debounce(async () => {
            loading.value = true;
            await loadFromServer(
                users,
                serverItemsLength,
                serverOptions,
                searchParameter
            );
            loading.value = false;
        }, 500);

        const exportUser = async () => {
            await exportUserData();
        };

        const deleteUser = async (id) => {
            if (!window.confirm("Are you sure?")) {
                return;
            }
            toast.info("Send", {
                timeout: 2000,
            });
            await destroyUser_with_logs(id, Auth_User.value.id);

            // await getUsers();
            if (errors_users_table.value) {
                toast.error("Delete error.");
            } else {
                await server_sided();
                toast.success("Delete success.");
            }
        };
        watch(
            () => searchParameter.searchValue,
            (value) => {
                server_sided();
            }
        );
        watch(
            () => serverOptions.value,
            (currentValue, oldValue) => {
                server_sided();
            },
            { deep: true }
        );
        return {
            form,
            isLoading,
            headers,
            users,
            Auth_User,
            form,
            deleteUser,
            loading,
            exportUser,
            permissions,
            searchParameter,
            serverOptions,
            serverItemsLength,
            errors_users_table,
        };
    },
};
</script>
<template>
    <Head title="User" />
    <BreezeAuthenticatedLayout>
        <template #header> Users </template>
        <div class="py-2">
            <div class="w-full mx-auto sm:px-6 lg:px-8">
                <div>
                    <Nav second="User" third="none" secondroute="user" />
                </div>
                <div v-if="errors_users_table">
                    <div
                        v-for="(v, k) in errors_users_table"
                        :key="k"
                        class="bg-red-500 text-white rounded font-bold mb-4 shadow-lg py-2 px-4 pr-0"
                    >
                        <p v-for="error in v" :key="error" class="text-sm">
                            {{ error }}
                        </p>
                    </div>
                </div>
                <div class="flex flex-col 2xl:flex-row xl:flex-row lg:flex-row">
                    <div
                        class="py-2 w-full 2xl:w-2/4 xl:w-2/4 lg:w-2/4 flex flex-col 2xl:flex-row xl:flex-row lg:flex-row justify-items-start place-content-start"
                    >
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
                    <div
                        class="w-full 2xl:w-2/4 xl:w-2/4 lg:w-2/4 flex flex-col 2xl:flex-row xl:flex-row lg:flex-row justify-items-end place-content-end"
                    >
                        <div
                            class="px-1"
                            v-if="permissions.includes('Action Settings Roles')"
                        >
                            <Link :href="route('roles')">
                                <button
                                    class="my-2 py-2 px-4 w-full 2xl:w-fit xl:w-fit lg:w-fit bg-blue-300 hover:bg-blue-400 text-blue-800 font-bold rounded inline-flex items-center"
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
                                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                        />
                                    </svg>
                                    <span>Edit Roles</span>
                                </button>
                            </Link>
                        </div>
                        <div
                            class="px-1"
                            v-if="permissions.includes('Action Download User')"
                        >
                            <button
                                @click.prevent="exportUser"
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
                                <span>Download</span>
                            </button>
                        </div>

                        <div
                            class="px-1 w-full 2xl:w-fit xl:w-fit lg:w-fit"
                            v-if="permissions.includes('Action Create User')"
                        >
                            <Link :href="route('user-create')">
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
                                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"
                                        />
                                    </svg>
                                    <span>Create Users </span>
                                </button>
                            </Link>
                        </div>
                    </div>
                </div>

                <div>
                    <EasyDataTable
                        show-index
                        v-model:server-options="serverOptions"
                        :server-items-length="serverItemsLength"
                        :headers="headers"
                        :items="users"
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
                        <template #item-action="item">
                            <div class="operation-wrapper flex">
                                <div
                                    class="p-1"
                                    v-if="
                                        permissions.includes('Action Edit User')
                                    "
                                >
                                    <div>
                                        <Link
                                            :href="route('user-edit', item.id)"
                                        >
                                            <button
                                                class="text-xs bg-green-700 hover:bg-green-400 text-white font-bold py-2 px-4 rounded"
                                            >
                                                Edit
                                            </button>
                                        </Link>
                                    </div>
                                </div>

                                <div
                                    class="p-1"
                                    v-if="
                                        permissions.includes(
                                            'Action Delete User'
                                        )
                                    "
                                >
                                    <div v-if="item.id != 1">
                                        <button
                                            @click="deleteUser(item.id)"
                                            class="text-xs bg-red-700 hover:bg-red-400 text-white font-bold py-2 px-4 rounded"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </EasyDataTable>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
<style scoped src="./../../assets/css/table.css"></style>
