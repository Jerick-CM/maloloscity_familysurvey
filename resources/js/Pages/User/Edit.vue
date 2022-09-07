<script>
import BreezeAuthenticatedLayout from "./../../Layouts/Authenticated.vue";
import { Head, Link, usePage } from "@inertiajs/inertia-vue3";
import { ref, computed, onMounted, reactive, watch } from "vue";
import Modal from "../../Components/Modals/Modal.vue";
import Nav from "./../../Components/Nav/BreadCrumb.vue";

/* composables */
import useUsers from "./../../composables/user";
import useRoles from "./../../composables/roles";
/* composables */

import { useToast } from "vue-toastification";
export default {
    components: {
        BreezeAuthenticatedLayout,
        Head,
        Link,
        Modal,
        Nav,
    },
    props: ["allPermissions", "user"],
    setup(props) {

        /* init */
        const toast = useToast();
        const Auth_User = computed(() => usePage().props.value.auth.user);
        const permissions = usePage().props.value.auth.user.PermissionList;
        const item = computed(() => props.user);
        const cstm_error = reactive({
            changepassword: null,
            resetpassword: null,
        });
        const selectedPermissions = ref(item.value.PermissionList);
        const tempPermissions = ref([]);
        const payload = reactive({
            id: null,
            name: null,
            permissions: [],
        });

        /* composables */

        const { errors_roles, roles, getRoles, getRoles_user_edit } =
            useRoles();

        const {
            errors_U,
            errors_change_password,
            errors_reset_password,
            errors_permissions,
            updateUserPassword,
            user,
            getUser,
            updateUser,
            handleResetPassword,
            updateUserPermissions,
        } = useUsers();

        /* lifecycle hooks */

        onMounted(async () => {
            await getUser(route().params.id);
            await getRoles_user_edit();
        });

        /* methods */

        const saveUser = async () => {
            toast.info("Send", {
                timeout: 2000,
            });

            user.value.user_id = Auth_User.value.id;

            await updateUser(route().params.id).then(() => {
                if (errors_U.value) {
                    toast.error("Failed");
                } else {
                    toast.success("Success");
                }
            });
        };

        const changePassword = async () => {
            toast.info("Send", {
                timeout: 2000,
            });

            user.value.user_id = Auth_User.value.id;
            await updateUserPassword(route().params.id).then(() => {
                if (errors_change_password.value) {
                    toast.error("Failed");
                } else {
                    toast.success("Success");
                }
            });
        };

        const resetPassword = async () => {
            toast.info("Send", {
                timeout: 2000,
            });

            user.value.user_id = Auth_User.value.id;
            await handleResetPassword(route().params.id).then(() => {
                if (errors_reset_password.value) {
                    toast.error("Failed");
                } else {
                    toast.success("Success");
                }
            });
        };

        const modifyPermissions = (event, permission) => {
            if (event.target.checked) {
                selectedPermissions.value.push(permission);
            } else {
                tempPermissions.value = selectedPermissions.value.filter(
                    (item) => {
                        return item != permission;
                    }
                );
                selectedPermissions.value = tempPermissions.value;
                tempPermissions.value = [];
            }
        };

        const updatePermissions = async () => {
            toast.info("Update", {
                timeout: 2000,
            });
            payload.user_id = route().params.id;
            payload.permissions = selectedPermissions.value;
            await updateUserPermissions(payload).then(() => {
                if (errors_permissions.value) {
                    toast.error("Failed");
                } else {
                    toast.success("Success");
                }
            });
        };

        /* watch */
        watch(
            () => user.value,
            (currentValue, oldValue) => {
                if (
                    currentValue.retype_new_password !=
                    currentValue.new_password
                ) {
                    cstm_error.changepassword = "Password does not match.";
                } else {
                    cstm_error.changepassword = null;
                }
            },
            { deep: true }
        );

        /* returns */
        return {
            saveUser,
            user,
            errors_U,
            changePassword,
            resetPassword,
            cstm_error,
            errors_change_password,
            errors_reset_password,
            Auth_User,
            roles,
            item,
            modifyPermissions,
            updatePermissions,
            permissions,
        };
    },
};
</script>

<style src="@vueform/multiselect/themes/default.css"></style>

<template>
    <Head title="User Edit" />
    <BreezeAuthenticatedLayout>
        <template #header> Edit an existing User </template>
        <div class="py-2">
            <div class="w-full mx-auto sm:px-6 lg:px-8">
                <div id="template" class="">
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
                                    <Link
                                        :href="route('user')"
                                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                                    >
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

                                        User
                                    </Link>
                                </div>
                            </li>
                            <li aria-current="page">
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
                                    <span
                                        class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400"
                                        >Edit
                                    </span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                    <div class="py-1">
                        <form class="" @submit.prevent="saveUser">
                            <div>
                                <div class="md:grid md:grid-cols-3 md:gap-6">
                                    <!-- LEFT -->
                                    <div class="md:col-span-1">
                                        <div class="px-4 sm:px-0">
                                            <h3
                                                class="text-lg font-medium leading-6 text-gray-900"
                                            >
                                                Edit Personal Info
                                            </h3>
                                            <p
                                                class="mt-1 text-sm text-gray-600"
                                            >
                                                This information will be
                                                displayed publicly so be careful
                                                what you share.
                                            </p>
                                        </div>
                                    </div>
                                    <!-- RIGHT  -->
                                    <div class="mt-5 md:mt-0 md:col-span-2">
                                        <div v-if="errors_U">
                                            <div
                                                v-for="(v, k) in errors_U"
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
                                        <div
                                            class="shadow sm:rounded-md sm:overflow-hidden"
                                        >
                                            <div
                                                class="px-4 py-5 bg-white space-y-6 sm:p-6"
                                            >
                                                <div
                                                    class="grid grid-cols-3 gap-6"
                                                >
                                                    <div
                                                        class="col-span-3 sm:col-span-2"
                                                    >
                                                        <label
                                                            for="company-website"
                                                            class="block text-sm font-medium text-gray-700"
                                                        >
                                                            Name
                                                        </label>
                                                        <div
                                                            class="mt-1 flex rounded-md shadow-sm"
                                                        >
                                                            <input
                                                                class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300"
                                                                type="text"
                                                                v-model="
                                                                    user.name
                                                                "
                                                                placeholder="Name"
                                                            />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div
                                                    class="grid grid-cols-3 gap-6"
                                                >
                                                    <div
                                                        class="col-span-3 sm:col-span-2"
                                                    >
                                                        <label
                                                            for="company-website"
                                                            class="block text-sm font-medium text-gray-700"
                                                        >
                                                            Email
                                                        </label>
                                                        <div
                                                            class="mt-1 flex rounded-md shadow-sm"
                                                        >
                                                            <input
                                                                class="mt-1 block w-full px-3 py-2 bg-gray-200 border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400 focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500"
                                                                type="text"
                                                                readonly
                                                                disable
                                                                v-model="
                                                                    user.email
                                                                "
                                                                placeholder="Email"
                                                            />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div
                                                    class="grid grid-cols-3 gap-6"
                                                    v-if="
                                                        Auth_User.superadmin ==
                                                            1 ||
                                                        Auth_User.admin == 1
                                                    "
                                                >
                                                    <div
                                                        class="col-span-3 sm:col-span-2"
                                                    >
                                                        <label
                                                            for="company-website"
                                                            class="block text-sm font-medium text-gray-700"
                                                        >
                                                            Roles :
                                                            Administrative
                                                        </label>
                                                        <div
                                                            class="mt-1 flex"
                                                            v-if="
                                                                Auth_User.superadmin ==
                                                                1
                                                            "
                                                        >
                                                            <input
                                                                v-model="
                                                                    user.superadmin
                                                                "
                                                                :checked="
                                                                    user.superadmin
                                                                "
                                                                
                                                                type="checkbox"
                                                                class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                                            />
                                                            <label
                                                                for="default-checkbox"
                                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                                                            >
                                                                Super Admin
                                                            </label>
                                                        </div>
                                                        <div
                                                            class="mt-1 flex"
                                                            v-if="
                                                                Auth_User.admin ==
                                                                1
                                                            "
                                                        >
                                                            <input
                                                                v-model="
                                                                    user.admin
                                                                "
                                                                :checked="
                                                                    user.admin
                                                                "
                                                                
                                                                type="checkbox"
                                                                class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                                            />
                                                            <label
                                                                for="default-checkbox"
                                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                                                            >
                                                                Admin
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div
                                                    class="grid grid-cols-3 gap-6"
                                                    v-if="
                                                        Auth_User.fieldpersonnel !=
                                                        1
                                                    "
                                                >
                                                    <div
                                                        class="col-span-3 sm:col-span-2"
                                                    >
                                                        <label
                                                            for="company-website"
                                                            class="block text-sm font-medium text-gray-700"
                                                        >
                                                            Roles : Itinerary
                                                            and Business
                                                        </label>
                                                        <div
                                                            class="mt-1 flex rounded-md shadow-sm"
                                                        >
                                                            <select
                                                                v-model="
                                                                    user.role
                                                                "
                                                                id="management"
                                                                class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300"
                                                            >
                                                                <option
                                                                    v-for="(
                                                                        option,
                                                                        index
                                                                    ) in roles"
                                                                    :key="index"
                                                                    :value="
                                                                        option.id
                                                                    "
                                                                >
                                                                    {{
                                                                        option.name
                                                                    }}
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="md:grid md:grid-cols-3 md:gap-6">
                                    <!-- LEFT -->
                                    <div class="md:col-span-1">
                                        <div class="px-4 sm:px-0">
                                            <h3
                                                class="text-lg font-medium leading-6 text-gray-900"
                                            ></h3>
                                            <p
                                                class="mt-1 text-sm text-gray-600"
                                            ></p>
                                        </div>
                                    </div>
                                    <!-- RIGHT  -->
                                    <div class="mt-5 md:mt-0 md:col-span-2">
                                        <div
                                            class="sm:rounded-md sm:overflow-hidden"
                                        >
                                            <div
                                                class="px-4 py-5 space-y-1 sm:pb-6"
                                            >
                                                <div class="flex justify-end">
                                                    <div class="px-1 py-1">
                                                        <button
                                                            type="button"
                                                            class="inline-flex justify-center py-2 px-10 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                        >
                                                            <Link
                                                                :href="
                                                                    route(
                                                                        'user'
                                                                    )
                                                                "
                                                            >
                                                                Cancel
                                                            </Link>
                                                        </button>
                                                    </div>
                                                    <div class="px-1 py-1">
                                                        <button
                                                            type="submit"
                                                            class="inline-flex justify-center py-2 px-10 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                        >
                                                            Save
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div
                        class="py-1"
                        v-if="permissions.includes('Action Edit Permission')"
                    >
                        <form class="" @submit.prevent="updatePermissions">
                            <div>
                                <div class="md:grid md:grid-cols-3 md:gap-6">
                                    <!-- LEFT -->
                                    <div class="md:col-span-1">
                                        <div class="px-4 sm:px-0">
                                            <h3
                                                class="text-lg font-medium leading-6 text-gray-900"
                                            >
                                                Edit Permissions
                                            </h3>
                                            <p
                                                class="mt-1 text-sm text-gray-600"
                                            >
                                                Edit actions and page
                                                permissions
                                            </p>
                                        </div>
                                    </div>
                                    <!-- RIGHT  -->
                                    <div class="mt-5 md:mt-0 md:col-span-2">
                                        <div
                                            class="shadow sm:rounded-md sm:overflow-hidden px-4 py-5 bg-white space-y-6 sm:p-6 md:grid md:grid-cols-2"
                                        >
                                            <!-- Dashboard -->
                                            <div class="w-full px-2 py-1">
                                                <label
                                                    class="text-gray-700 font-bold"
                                                    >Dashboard</label
                                                >
                                                <template
                                                    v-for="(
                                                        permission, index
                                                    ) in allPermissions"
                                                    :key="index"
                                                >
                                                    <div
                                                        v-if="
                                                            permission.indexOf(
                                                                'Dashboard'
                                                            ) > -1
                                                        "
                                                        class="flex items-center my-2"
                                                    >
                                                        <input
                                                            
                                                            type="checkbox"
                                                            @change="
                                                                modifyPermissions(
                                                                    $event,
                                                                    permission
                                                                )
                                                            "
                                                            :value="permission"
                                                            class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                                            :checked="
                                                                item.PermissionList.includes(
                                                                    permission
                                                                )
                                                            "
                                                        />
                                                        <label
                                                            for="default-checkbox"
                                                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                                                            >{{
                                                                permission
                                                            }}</label
                                                        >
                                                    </div>
                                                </template>
                                            </div>
                                            <!-- User -->
                                            <div class="w-full px-2 py-1">
                                                <label
                                                    class="text-gray-700 font-bold"
                                                    >User</label
                                                >
                                                <template
                                                    v-for="(
                                                        permission, index
                                                    ) in allPermissions"
                                                    :key="index"
                                                >
                                                    <div
                                                        v-if="
                                                            permission.indexOf(
                                                                'User'
                                                            ) > -1
                                                        "
                                                        class="flex items-center my-2"
                                                    >
                                                        <input
                                                            
                                                            type="checkbox"
                                                            @change="
                                                                modifyPermissions(
                                                                    $event,
                                                                    permission
                                                                )
                                                            "
                                                            :value="permission"
                                                            class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                                            :checked="
                                                                item.PermissionList.includes(
                                                                    permission
                                                                )
                                                            "
                                                        />
                                                        <label
                                                            for="default-checkbox"
                                                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                                                            >{{
                                                                permission
                                                            }}</label
                                                        >
                                                    </div>
                                                </template>
                                            </div>
                                            <!-- Survey -->

                                            <div class="w-full px-2 py-1">
                                                <label
                                                    class="text-gray-700 font-bold"
                                                    >Survey Form</label
                                                >
                                                <template
                                                    v-for="(
                                                        permission, index
                                                    ) in allPermissions"
                                                    :key="index"
                                                >
                                                    <div
                                                        v-if="
                                                            permission.indexOf(
                                                                'Survey'
                                                            ) > -1
                                                        "
                                                        class="flex items-center my-2"
                                                    >
                                                        <input
                                                            
                                                            type="checkbox"
                                                            @change="
                                                                modifyPermissions(
                                                                    $event,
                                                                    permission
                                                                )
                                                            "
                                                            :value="permission"
                                                            class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                                            :checked="
                                                                item.PermissionList.includes(
                                                                    permission
                                                                )
                                                            "
                                                        />
                                                        <label
                                                            for="default-checkbox"
                                                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                                                            >{{
                                                                permission
                                                            }}</label
                                                        >
                                                    </div>
                                                </template>
                                            </div>

                                            <!-- Logs -->

                                            <div class="w-full px-2 py-1">
                                                <label
                                                    class="text-gray-700 font-bold"
                                                    >Logs</label
                                                >
                                                <template
                                                    v-for="(
                                                        permission, index
                                                    ) in allPermissions"
                                                    :key="index"
                                                >
                                                    <div
                                                        v-if="
                                                            permission.indexOf(
                                                                'Logs'
                                                            ) > -1
                                                        "
                                                        class="flex items-center my-2"
                                                    >
                                                        <input
                                                            
                                                            type="checkbox"
                                                            @change="
                                                                modifyPermissions(
                                                                    $event,
                                                                    permission
                                                                )
                                                            "
                                                            :value="permission"
                                                            class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                                            :checked="
                                                                item.PermissionList.includes(
                                                                    permission
                                                                )
                                                            "
                                                        />
                                                        <label
                                                            for="default-checkbox"
                                                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                                                            >{{
                                                                permission
                                                            }}</label
                                                        >
                                                    </div>
                                                </template>
                                            </div>
                                            <!-- Settings -->
                                            <div class="w-full px-2 py-1">
                                                <label
                                                    class="text-gray-700 font-bold"
                                                    >Settings</label
                                                >
                                                <template
                                                    v-for="(
                                                        permission, index
                                                    ) in allPermissions"
                                                    :key="index"
                                                >
                                                    <div
                                                        v-if="
                                                            permission.indexOf(
                                                                'Settings'
                                                            ) > -1
                                                        "
                                                        class="flex items-center my-2"
                                                    >
                                                        <input
                                                            
                                                            type="checkbox"
                                                            @change="
                                                                modifyPermissions(
                                                                    $event,
                                                                    permission
                                                                )
                                                            "
                                                            :value="permission"
                                                            class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                                            :checked="
                                                                item.PermissionList.includes(
                                                                    permission
                                                                )
                                                            "
                                                        />
                                                        <label
                                                            for="default-checkbox"
                                                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                                                            >{{
                                                                permission
                                                            }}</label
                                                        >
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="md:grid md:grid-cols-3 md:gap-6">
                                    <!-- LEFT -->
                                    <div class="md:col-span-1">
                                        <div class="px-4 sm:px-0">
                                            <h3
                                                class="text-lg font-medium leading-6 text-gray-900"
                                            ></h3>
                                            <p
                                                class="mt-1 text-sm text-gray-600"
                                            ></p>
                                        </div>
                                    </div>
                                    <!-- RIGHT  -->
                                    <div class="mt-5 md:mt-0 md:col-span-2">
                                        <div
                                            class="sm:rounded-md sm:overflow-hidden"
                                        >
                                            <div
                                                class="px-4 py-5 space-y-1 sm:pb-6"
                                            >
                                                <div class="flex justify-end">
                                                    <div class="px-1 py-1">
                                                        <button
                                                            type="button"
                                                            class="inline-flex justify-center py-2 px-10 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                        >
                                                            <Link
                                                                :href="
                                                                    route(
                                                                        'user'
                                                                    )
                                                                "
                                                            >
                                                                Cancel
                                                            </Link>
                                                        </button>
                                                    </div>
                                                    <div class="px-1 py-1">
                                                        <button
                                                            type="submit"
                                                            class="inline-flex justify-center py-2 px-10 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                        >
                                                            Save
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="py-1">
                        <form class="" @submit.prevent="changePassword">
                            <div>
                                <div class="md:grid md:grid-cols-3 md:gap-6">
                                    <!-- LEFT -->
                                    <div class="md:col-span-1">
                                        <div class="px-4 sm:px-0">
                                            <h3
                                                class="text-lg font-medium leading-6 text-gray-900"
                                            >
                                                Change Password
                                            </h3>
                                            <p
                                                class="mt-1 text-sm text-gray-600"
                                            ></p>
                                        </div>
                                    </div>
                                    <!-- RIGHT  -->
                                    <div class="mt-5 md:mt-0 md:col-span-2">
                                        <div
                                            class="shadow sm:rounded-md sm:overflow-hidden"
                                        >
                                            <div v-if="errors_change_password">
                                                <div
                                                    v-for="(
                                                        v, k
                                                    ) in errors_change_password"
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
                                            <div
                                                class="px-4 py-5 bg-white space-y-6 sm:p-6"
                                            >
                                                <div
                                                    class="grid grid-cols-3 gap-6"
                                                >
                                                    <div
                                                        class="col-span-3 sm:col-span-2"
                                                    >
                                                        <label
                                                            for="company-website"
                                                            class="block text-sm font-medium text-gray-700"
                                                        >
                                                            Current Password :
                                                        </label>
                                                        <div
                                                            class="mt-1 flex rounded-md shadow-sm"
                                                        >
                                                            <input
                                                                class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300"
                                                                type="password"
                                                                v-model="
                                                                    user.password
                                                                "
                                                                placeholder="Name"
                                                            />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div
                                                    class="grid grid-cols-3 gap-6"
                                                >
                                                    <div
                                                        class="col-span-3 sm:col-span-2"
                                                    >
                                                        <label
                                                            for="company-website"
                                                            class="block text-sm font-medium text-gray-700"
                                                        >
                                                            New Password
                                                        </label>
                                                        <div
                                                            class="mt-1 flex rounded-md shadow-sm"
                                                        >
                                                            <input
                                                                class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300"
                                                                type="password"
                                                                v-model="
                                                                    user.new_password
                                                                "
                                                                placeholder="New Password"
                                                            />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div
                                                    class="grid grid-cols-3 gap-6"
                                                >
                                                    <div
                                                        class="col-span-3 sm:col-span-2"
                                                    >
                                                        <label
                                                            for="company-website"
                                                            class="block text-sm font-medium text-gray-700"
                                                        >
                                                            Retype New Password
                                                        </label>
                                                        <div
                                                            class="mt-1 flex rounded-md shadow-sm"
                                                        >
                                                            <input
                                                                class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300"
                                                                type="password"
                                                                v-model="
                                                                    user.retype_new_password
                                                                "
                                                                placeholder="New Password"
                                                            />
                                                        </div>
                                                    </div>
                                                    <div
                                                        v-if="
                                                            cstm_error.changepassword !=
                                                            null
                                                        "
                                                    >
                                                        <div
                                                            class="bg-red-500 text-white rounded font-bold mb-4 shadow-lg py-2 px-4 pr-0"
                                                        >
                                                            <p class="text-sm">
                                                                {{
                                                                    cstm_error.changepassword
                                                                }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="md:grid md:grid-cols-3 md:gap-6">
                                    <!-- LEFT -->
                                    <div class="md:col-span-1">
                                        <div class="px-4 sm:px-0">
                                            <h3
                                                class="text-lg font-medium leading-6 text-gray-900"
                                            ></h3>
                                            <p
                                                class="mt-1 text-sm text-gray-600"
                                            ></p>
                                        </div>
                                    </div>
                                    <!-- RIGHT  -->
                                    <div class="mt-5 md:mt-0 md:col-span-2">
                                        <div
                                            class="sm:rounded-md sm:overflow-hidden"
                                        >
                                            <div
                                                class="px-4 py-5 space-y-1 sm:pb-6"
                                            >
                                                <div class="flex justify-end">
                                                    <div class="px-1 py-1">
                                                        <button
                                                            type="button"
                                                            class="inline-flex justify-center py-2 px-10 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                        >
                                                            <Link
                                                                :href="
                                                                    route(
                                                                        'user'
                                                                    )
                                                                "
                                                            >
                                                                Cancel
                                                            </Link>
                                                        </button>
                                                    </div>
                                                    <div class="px-1 py-1">
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
                            </div>
                        </form>
                    </div>

                    <div class="py-1" v-if="Auth_User.admin">
                        <form class="" @submit.prevent="resetPassword">
                            <div>
                                <div class="md:grid md:grid-cols-3 md:gap-6">
                                    <!-- LEFT -->
                                    <div class="md:col-span-1">
                                        <div class="px-4 sm:px-0">
                                            <h3
                                                class="text-lg font-medium leading-6 text-gray-900"
                                            >
                                                Reset Password
                                            </h3>
                                            <p
                                                class="mt-1 text-sm text-gray-600"
                                            ></p>
                                        </div>
                                    </div>
                                    <!-- RIGHT  -->
                                    <div class="mt-5 md:mt-0 md:col-span-2">
                                        <div v-if="errors_reset_password">
                                            <div
                                                v-for="(
                                                    v, k
                                                ) in errors_reset_password"
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
                                        <div
                                            class="shadow sm:rounded-md sm:overflow-hidden"
                                        >
                                            <div
                                                class="px-4 py-5 bg-white space-y-6 sm:p-6"
                                            >
                                                <div
                                                    class="grid grid-cols-3 gap-6"
                                                >
                                                    <div
                                                        class="col-span-3 sm:col-span-2"
                                                    >
                                                        <label
                                                            for="company-website"
                                                            class="block text-sm font-medium text-gray-700"
                                                        >
                                                            New Password
                                                        </label>
                                                        <div
                                                            class="mt-1 flex rounded-md shadow-sm"
                                                        >
                                                            <input
                                                                class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300"
                                                                type="password"
                                                                v-model="
                                                                    user.reset_password
                                                                "
                                                                placeholder="Reset Password"
                                                            />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div
                                                    class="grid grid-cols-3 gap-6"
                                                >
                                                    <div
                                                        class="col-span-3 sm:col-span-2"
                                                    >
                                                        <label
                                                            for="company-website"
                                                            class="block text-sm font-medium text-gray-700"
                                                        >
                                                            Retype Reset
                                                            Password
                                                        </label>
                                                        <div
                                                            class="mt-1 flex rounded-md shadow-sm"
                                                        >
                                                            <input
                                                                class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300"
                                                                type="password"
                                                                v-model="
                                                                    user.retype_reset_password
                                                                "
                                                                placeholder="Reset Password"
                                                            />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="md:grid md:grid-cols-3 md:gap-6">
                                    <!-- LEFT -->
                                    <div class="md:col-span-1">
                                        <div class="px-4 sm:px-0">
                                            <h3
                                                class="text-lg font-medium leading-6 text-gray-900"
                                            ></h3>
                                            <p
                                                class="mt-1 text-sm text-gray-600"
                                            ></p>
                                        </div>
                                    </div>
                                    <!-- RIGHT  -->
                                    <div class="mt-5 md:mt-0 md:col-span-2">
                                        <div
                                            class="sm:rounded-md sm:overflow-hidden"
                                        >
                                            <div
                                                class="px-4 py-5 space-y-1 sm:pb-6"
                                            >
                                                <div class="flex justify-end">
                                                    <div class="px-1 py-1">
                                                        <button
                                                            type="button"
                                                            class="inline-flex justify-center py-2 px-10 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                        >
                                                            <Link
                                                                :href="
                                                                    route(
                                                                        'user'
                                                                    )
                                                                "
                                                            >
                                                                Cancel
                                                            </Link>
                                                        </button>
                                                    </div>
                                                    <div class="px-1 py-1">
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
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
