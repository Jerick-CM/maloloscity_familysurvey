<script>
import BreezeAuthenticatedLayout from "./../../Layouts/Composition.vue";
import { Head, Link, usePage } from "@inertiajs/inertia-vue3";
import { ref, computed, onMounted, reactive, watch } from "vue";
import Modal from "./../../Components/Modals/Modal.vue";
import { useToast } from "vue-toastification";
import Nav from "./../../Components/Nav/BreadCrumb.vue";
import useUsers from "./../../composables/user";
import useRoles from "./../../composables/roles";

export default {
    components: {
        BreezeAuthenticatedLayout,
        Head,
        Link,
        Modal,
        Nav,
    },
    setup(props) {


        /* init */
        const Auth_User = computed(() => usePage().props.value.auth.user);
        const toast = useToast();
        const form = reactive({});
        const cstm_error = reactive({ password: null });

        /* composables */
        const { errors_U, user, storeUser } = useUsers();
        const { errors_roles, roles, getRoles } = useRoles();

        /* lifecycle hooks */
        onMounted(async () => {
            form.user_id = Auth_User.value.id;
            await getRoles();
        });
        /* methods */
        const togglemodal = () => {
            if (errors_U.value) {
                toast.error("Error.", {
                    timeout: 1000,
                });
            } else {
                toast.success("Create User Successful.", {
                    timeout: 1000,
                });

                form.name = "";
                form.email = "";
                form.password = "";
                form.password_confirmation = "";
            }
        };
        const createUser = async () => {
            toast.info("Sending", {
                timeout: 1000,
            });

            form.user_id = Auth_User.value.id;
            await storeUser({ ...form }).then(() => {
                togglemodal();
            });
        };
        /* watch */
        watch(
            () => form.password_confirmation,
            (value) => {
                if (value != form.password) {
                    cstm_error.password = "Password does not match.";
                } else {
                    cstm_error.password = null;
                }
            }
        );
        /* returns */
        return {
            createUser,
            user,
            errors_U,
            form,
            cstm_error,
            roles,
            Auth_User,
        };
    },
};
</script>

<style src="@vueform/multiselect/themes/default.css"></style>

<template>
    <Head title="User Create" />
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
                                        >Create
                                    </span>
                                </div>
                            </li>
                        </ol>
                    </nav>

                    <form class="" @submit.prevent="createUser">
                        <div>
                            <div class="md:grid md:grid-cols-3 md:gap-6">
                                <!-- LEFT -->
                                <div class="md:col-span-1">
                                    <div class="px-4 sm:px-0">
                                        <h3
                                            class="text-lg font-medium leading-6 text-gray-900"
                                        >
                                            User Information
                                        </h3>
                                        <p class="mt-1 text-sm text-gray-600">
                                            Create a user either admin ar just a
                                            user.
                                        </p>
                                    </div>
                                </div>
                                <!-- RIGHT  -->
                                <div class="mt-5 md:mt-0 md:col-span-2">
                                    <div
                                        class="shadow sm:rounded-md sm:overflow-hidden"
                                    >
                                        <div
                                            class="px-4 py-5 bg-white space-y-6 sm:p-6"
                                        >
                                            <div class="grid grid-cols-3 gap-6">
                                                <div v-if="errors_U">
                                                    <div
                                                        v-for="(
                                                            v, k
                                                        ) in errors_U"
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
                                            </div>

                                            <div class="grid grid-cols-3 gap-6">
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
                                                            v-model="form.name"
                                                            placeholder="Name"
                                                        />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-3 gap-6">
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
                                                            class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300"
                                                            type="text"
                                                            v-model="form.email"
                                                            placeholder="Email"
                                                        />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-3 gap-6">
                                                <div
                                                    class="col-span-3 sm:col-span-2"
                                                >
                                                    <label
                                                        for="company-website"
                                                        class="block text-sm font-medium text-gray-700"
                                                    >
                                                        Roles : Administrative
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
                                                                form.superadmin
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
                                                    <div class="mt-1 flex"  v-if="
                                                            Auth_User.admin ==
                                                            1
                                                        ">
                                                        <input
                                                            v-model="form.admin"
                                                            
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
                                            <div class="grid grid-cols-3 gap-6">
                                                <div
                                                    class="col-span-3 sm:col-span-2"
                                                >
                                                    <label
                                                        for="company-website"
                                                        class="block text-sm font-medium text-gray-700"
                                                    >
                                                        Sub Role
                                                    </label>
                                                    <div
                                                        class="mt-1 flex rounded-md shadow-sm"
                                                    >
                                                        <select
                                                            v-model="form.role"
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

                                            <div class="grid grid-cols-3 gap-6">
                                                <div
                                                    class="col-span-3 sm:col-span-2"
                                                >
                                                    <label
                                                        for="company-website"
                                                        class="block text-sm font-medium text-gray-700"
                                                    >
                                                        Password
                                                    </label>
                                                    <div
                                                        class="mt-1 flex rounded-md shadow-sm"
                                                    >
                                                        <input
                                                            class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300"
                                                            type="password"
                                                            v-model="
                                                                form.password
                                                            "
                                                            placeholder="Password"
                                                        />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-3 gap-6">
                                                <div
                                                    class="col-span-3 sm:col-span-2"
                                                >
                                                    <label
                                                        for="company-website"
                                                        class="block text-sm font-medium text-gray-700"
                                                    >
                                                        Retype password
                                                    </label>
                                                    <div
                                                        class="mt-1 flex rounded-md shadow-sm"
                                                    >
                                                        <input
                                                            class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300"
                                                            type="password"
                                                            v-model="
                                                                form.password_confirmation
                                                            "
                                                            placeholder="Password"
                                                        />
                                                    </div>
                                                </div>
                                                <div
                                                    v-if="
                                                        cstm_error.password !=
                                                        null
                                                    "
                                                >
                                                    <div
                                                        class="bg-red-500 text-white rounded font-bold mb-4 shadow-lg py-2 px-4 pr-0"
                                                    >
                                                        <p class="text-sm">
                                                            {{
                                                                cstm_error.password
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
                                                        class="inline-flex justify-center py-2 px-10 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                    >
                                                        <Link
                                                            :href="
                                                                route('user')
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
                                                        Create
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
    </BreezeAuthenticatedLayout>
</template>
