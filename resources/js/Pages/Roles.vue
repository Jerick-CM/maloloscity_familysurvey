<script>
import BreezeAuthenticatedLayout from "../Layouts/Authenticated.vue";
import { Head, Link, usePage } from "@inertiajs/inertia-vue3";
import useRoles from "../composables/roles";
import { ref, computed, onMounted, reactive, watch } from "vue";
import Modal from "../Components/Modals/Modal.vue";
import { useToast } from "vue-toastification";
import Multiselect from "@vueform/multiselect";

export default {
    components: {
        BreezeAuthenticatedLayout,
        Head,
        Link,
        Modal,
        Multiselect,
    },
    data: () => ({
        readonlyClass:
            "focus:ring-gray-500 focus:border-gray-500 flex-1 block rounded-none rounded-r-md sm:text-sm border-gray-300 bg-gray-200",
        defaultClass:
            "focus:ring-indigo-500 focus:border-indigo-500 flex-1 block rounded-none rounded-r-md sm:text-sm border-gray-300",
    }),
    methods: {},
    setup(props) {


        const Auth_user = computed(() => usePage().props.value.auth.user);
        const toast = useToast();
        const form = reactive({});

        const {
            errors_roles,
            roles,
            roles_form,
            getRoles_edit,
            updateRoleslist_data,
        } = useRoles();

        const updateRoles = async () => {
            toast.info("Send.");

            form.roles = roles_form.value;
            await updateRoleslist_data({ ...form }).then(() => {
                if (errors_roles.value) {
                    toast.error("Error.");
                } else {
                    toast.success("Success.");
                }
            });
        };

        onMounted(async () => {
            await getRoles_edit();
        });

        const addRole = () => {
            roles.value.push("");
            roles_form.value.push("");
        };

        const removeRole = (index) => {
            roles.value.splice(index, 1);
            roles_form.value.splice(index, 1);
        };

        watch(
            () => roles.value,
            (value) => {}
        );
        return {
            roles_form,
            errors_roles,
            roles,
            form,
            updateRoles,
            addRole,
            removeRole,
        };
    },
};
</script>

<style src="@vueform/multiselect/themes/default.css"></style>

<template>
    <Head title="Business" />
    <BreezeAuthenticatedLayout>
        <template #header> Edit Roles </template>
        <div class="py-2">
            <div class="w-full mx-auto sm:px-6 lg:px-8">
                <Modal
                    :showmodal="modal_show"
                    @toggle-modal="togglemodal"
                    :statusmodal="modal_status"
                    :note="modal_note"
                >
                </Modal>

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
                                        :href="route('itinerary')"
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
                                        >Roles
                                    </span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                    <form class="" @submit.prevent="updateRoles">
                        <div>
                            <div class="md:grid md:grid-cols-3 md:gap-6">
                                <!-- LEFT -->
                                <div class="md:col-span-1">
                                    <div class="px-4 sm:px-0">
                                        <h3
                                            class="text-lg font-medium leading-6 text-gray-900"
                                        >
                                            Roles
                                        </h3>
                                        <p class="mt-1 text-sm text-gray-600">
                                            Edit roles
                                        </p>
                                    </div>
                                </div>
                                <!-- RIGHT  -->
                                <div class="mt-5 md:mt-0 md:col-span-2">
                                    <div v-if="errors_roles">
                                        <div
                                            v-for="(v, k) in errors_roles"
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
                                                class="grid grid-cols-1 divide-y"
                                            >
                                                <div
                                                    class="requested-amount-header flex flex-wrap my-3"
                                                >
                                                    <label
                                                        class="block uppercase tracking-wide text-gray-700 font-bold mb-2"
                                                        for="grid-password"
                                                    >
                                                        Add Role
                                                    </label>
                                                    <a
                                                        class="ml-3 block hover:text-green-600 tracking-wide text-green-500 text-xs font-bold mb-2"
                                                        href="#"
                                                        @click.prevent="addRole"
                                                        ><small
                                                            >Add List</small
                                                        ></a
                                                    >
                                                </div>

                                                <div
                                                    class="p-1"
                                                    v-for="(
                                                        req, index
                                                    ) in roles"
                                                    :key="index"
                                                >
                                                    <div
                                                        class="mt-1 flex rounded-md shadow-sm"
                                                    >
                                                        <div class="py-4">
                                                            <label
                                                                for="company-website"
                                                                class="block text-sm font-medium text-gray-700"
                                                            >
                                                                Roles :
                                                            </label>
                                                        </div>

                                                        <input
                                                            v-model="
                                                                roles_form[
                                                                    index
                                                                ]
                                                            "
                                                            :readonly="
                                                                index <= 1
                                                            "
                                                            :disabled="
                                                                index <= 1
                                                            "
                                                            :class="
                                                                index <= 1
                                                                    ? readonlyClass
                                                                    : defaultClass
                                                            "
                                                            type="text"
                                                            placeholder="Role"
                                                        />
                                                        <div
                                                            class="w-full md:w-1/4 md:pl-2 py-2"
                                                        ></div>
                                                        <div
                                                            class="w-full md:w-1/4 md:pl-2 py-2 hidden"
                                                        >
                                                            <a
                                                                v-if="
                                                                    index >= 3
                                                                "
                                                                class="h-full rounded text-center py-3 block hover:bg-red-500 tracking-wide bg-red-400 text-white text-xs font-bold my-auto"
                                                                href="#"
                                                                @click.prevent="
                                                                    removeRole(
                                                                        index
                                                                    )
                                                                "
                                                                >Remove</a
                                                            >
                                                        </div>
                                                    </div>
                                                </div>

                                                <div
                                                    class="w-full md:w-1/4 md:pl-2 py-2"
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
                        </div>

                        <div class="hidden sm:block" aria-hidden="true">
                            <div class="py-5">
                                <div class="border-t border-gray-200"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
