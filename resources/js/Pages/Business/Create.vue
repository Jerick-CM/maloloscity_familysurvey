<script>
import BreezeAuthenticatedLayout from "./../../Layouts/Composition.vue";
import { Head, Link, usePage } from "@inertiajs/inertia-vue3";
import Modal from "./../../Components/Modals/Modal.vue";
import { ref, reactive, computed, onMounted, watch } from "vue";
// composables
import useBusinesses from "./../../composables/business";
import Multiselect from "@vueform/multiselect";
import { useToast } from "vue-toastification";

export default {
    components: {
        Multiselect,
        BreezeAuthenticatedLayout,
        Head,
        Link,
        Modal,
    },
    methods: {
        async fetch_selectfield(query, field) {
            let data;
            await axios
                .post("/api/cstm/business/get_selectfield", {
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
        },
    },
    props: ["barangays", "municipalities"],
    setup(props) {
        console.log("setup");
        const toast = useToast();
        const brgys = computed(() => props.barangays);
        const Auth_User = computed(() => usePage().props.value.auth.user);
        const filteredBrgys = ref([]);

        /* init */
        const form = reactive({
            province: 14,
            municipality: null,
        });

        let modal_show = ref(false);
        let modal_note = ref(false);
        let modal_status = ref(false);

        const multiselect_line_of_business = ref(null);
        const multiselect_strategic_location = ref(null);
        const multiselect_qtr_paid = ref(null);
        const multiselect_terms = ref(null);
        const multiselect_code = ref(null);
        const multiselect_status = ref(null);

        /* init end */

        const { errors, business, storeBusiness } = useBusinesses();

        const createBusiness = async () => {
            toast.info("Sending create");
            form.user_id = Auth_User.value.id;
            await storeBusiness({ ...form }).then(() => {
                if (errors.value) {
                    toast.error("Create failed.");
                    modal_status.value = false;
                    modal_note.value = "Error.";
                } else {
                    toast.success("Create success.");

                    modal_status.value = true;
                    modal_note.value = "Create successful.";
                    form.business_identification_number = "";
                    form.owner = "";
                    form.business_name = "";
                    form.description = "";
                    form.number_of_employees = "";
                    form.first_name = "";
                    form.middle_name = "";
                    form.last_name = "";
                    form.contact_number = "";
                    form.address = "";
                    form.contact_number = "";
                    form.barangay = "";

                    form.official_receipt_number = "";
                    form.official_receipt_date = "";
                    form.gross_receipts = "";
                    form.business_tax = "";
                    form.tax_year = "";
                    form.capital = "";
                    form.fees = "";
                    form.surcharge = "";
                    form.total = "";

                    multiselect_line_of_business.value.clear();
                    multiselect_strategic_location.value.clear();
                    multiselect_qtr_paid.value.clear();
                    multiselect_terms.value.clear();
                    multiselect_code.value.clear();
                    multiselect_status.value.clear();
                }
            });
        };
        const filterBrgys = async (munId) => {
            filteredBrgys.value = brgys.value.filter(
                (brgy) => brgy.parent === munId
            );
        };

        /* method */
        function togglemodal() {
            modal_show.value = !modal_show.value;
        }

        watch(
            () => form.municipality,
            (value) => {
                filterBrgys(value);
            }
        );
        /* method end */

        /* event change */
        form.municipality = 10;
        form.zipcode = 3000;
        /* event change end*/
        /* return */
        return {
            business,
            form,
            errors,
            createBusiness,
            togglemodal,
            modal_show,
            modal_note,
            modal_status,

            filteredBrgys,

            multiselect_line_of_business,
            multiselect_strategic_location,
            multiselect_qtr_paid,
            multiselect_terms,
            multiselect_code,
            multiselect_status,
        };
        /* return end  */
    },
};
</script>
<template>
    <Head title="Business Create" />
    <BreezeAuthenticatedLayout>
        <Modal
            :showmodal="show_modal"
            @toggle-modal="togglemodal()"
            :statusmodal="status_modal"
            :note="note"
        >
        </Modal>
        <template #header> Register a Business </template>
        <div class="py-2">
            <!-- <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"> -->
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
                                        :href="route('business')"
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

                                        Business
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
                                        >Register</span
                                    >
                                </div>
                            </li>
                        </ol>
                    </nav>

                    <div class="py-5 invisible">
                        <Link :href="route('business')">
                            <div class="flex flex-items">
                                <div class="mb-1">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                                <div>Return to Business</div>
                            </div>
                        </Link>
                    </div>

                    <form class="" @submit.prevent="createBusiness">
                        <div>
                            <div class="md:grid md:grid-cols-3 md:gap-6">
                                <!-- LEFT -->
                                <div class="md:col-span-1">
                                    <div class="px-4 sm:px-0">
                                        <h3
                                            class="text-lg font-medium leading-6 text-gray-900"
                                        >
                                            Business Information
                                        </h3>
                                        <p class="mt-1 text-sm text-gray-600">
                                            This information will be displayed
                                            publicly so be careful what you
                                            share.
                                        </p>
                                    </div>
                                </div>
                                <!-- RIGHT  -->
                                <div class="mt-5 md:mt-0 md:col-span-2">
                                    <div v-if="errors">
                                        <div
                                            v-for="(v, k) in errors"
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
                                            <!-- B.I.N. -->
                                            <div class="grid grid-cols-3 gap-6">
                                                <div
                                                    class="col-span-3 sm:col-span-2"
                                                >
                                                    <label
                                                        for="company-website"
                                                        class="block text-sm font-medium text-gray-700"
                                                    >
                                                        Business Identification
                                                        Number (B.I.N.)
                                                    </label>
                                                    <div
                                                        class="mt-1 flex rounded-md shadow-sm"
                                                    >
                                                        <input
                                                            class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300"
                                                            type="text"
                                                            v-model="
                                                                form.business_identification_number
                                                            "
                                                            placeholder="Business Identification Number"
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- business name -->
                                            <div class="grid grid-cols-3 gap-6">
                                                <div
                                                    class="col-span-3 sm:col-span-2"
                                                >
                                                    <label
                                                        for="company-website"
                                                        class="block text-sm font-medium text-gray-700"
                                                    >
                                                        Business Name
                                                    </label>
                                                    <div
                                                        class="mt-1 flex rounded-md shadow-sm"
                                                    >
                                                        <input
                                                            class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300"
                                                            type="text"
                                                            v-model="
                                                                form.business_name
                                                            "
                                                            placeholder="Business Name"
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- line of business -->
                                            <div class="grid grid-cols-3 gap-6">
                                                <div
                                                    class="col-span-3 sm:col-span-2"
                                                >
                                                    <div
                                                        class="col-span-6 sm:col-span-3"
                                                    >
                                                        <label
                                                            for="line_of_business"
                                                            class="block text-sm font-medium text-gray-700"
                                                        >
                                                            Line of Business
                                                        </label>
                                                        <Multiselect
                                                            ref="multiselect_line_of_business"
                                                            mode="single"
                                                            v-model="
                                                                form.line_of_business
                                                            "
                                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                                            placeholder="Select a Line of Business"
                                                            :filter-results="
                                                                false
                                                            "
                                                            :min-chars="1"
                                                            :resolve-on-load="
                                                                false
                                                            "
                                                            :delay="0"
                                                            :searchable="true"
                                                            :create-option="
                                                                true
                                                            "
                                                            :options="
                                                                async function (
                                                                    query
                                                                ) {
                                                                    return await fetch_selectfield(
                                                                        query,
                                                                        'line_of_business'
                                                                    );
                                                                }
                                                            "
                                                        />
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- business owner -->
                                            <div class="grid grid-cols-3 gap-6">
                                                <div
                                                    class="col-span-3 sm:col-span-2"
                                                >
                                                    <label
                                                        for="company-website"
                                                        class="block text-sm font-medium text-gray-700"
                                                    >
                                                        Business Owner
                                                    </label>
                                                    <div
                                                        class="mt-1 flex rounded-md shadow-sm"
                                                    >
                                                        <input
                                                            class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300"
                                                            type="text"
                                                            v-model="form.owner"
                                                            placeholder="Business Owner"
                                                        />
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- about -->
                                            <div>
                                                <label
                                                    for="about"
                                                    class="block text-sm font-medium text-gray-700"
                                                >
                                                    About
                                                </label>
                                                <div class="mt-1">
                                                    <textarea
                                                        v-model="
                                                            form.description
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
                                                    Brief Company description
                                                    (Optional)
                                                </p>
                                            </div>
                                            <!-- number of employees-->
                                            <div class="grid grid-cols-3 gap-6">
                                                <div
                                                    class="col-span-3 sm:col-span-2"
                                                >
                                                    <label
                                                        for="company-website"
                                                        class="block text-sm font-medium text-gray-700"
                                                    >
                                                        Number of employees
                                                    </label>
                                                    <div
                                                        class="mt-1 flex rounded-md shadow-sm"
                                                    >
                                                        <input
                                                            class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300"
                                                            type="number"
                                                            v-model="
                                                                form.number_of_employees
                                                            "
                                                            placeholder="Number of Employees"
                                                        />
                                                    </div>
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

                        <div class="mt-10 sm:mt-0">
                            <div class="md:grid md:grid-cols-3 md:gap-6">
                                <div class="md:col-span-1">
                                    <div class="px-4 sm:px-0">
                                        <h3
                                            class="text-lg font-medium leading-6 text-gray-900"
                                        >
                                            Personal Information
                                        </h3>
                                        <p class="mt-1 text-sm text-gray-600">
                                            Permanent address and contact
                                            information.
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-5 md:mt-0 md:col-span-2">
                                    <!-- shadow overflow-hidden -->
                                    <div class="shadow sm:rounded-md">
                                        <div class="px-4 py-5 bg-white sm:p-6">
                                            <div class="grid grid-cols-6 gap-6">
                                                <div
                                                    class="col-span-6 sm:col-span-3 lg:col-span-2"
                                                >
                                                    <label
                                                        for="first-name"
                                                        class="block text-sm font-medium text-gray-700"
                                                        >First name</label
                                                    >
                                                    <input
                                                        type="text"
                                                        name="first-name"
                                                        id="first-name"
                                                        v-model="
                                                            form.first_name
                                                        "
                                                        autocomplete="given-name"
                                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                                    />
                                                </div>
                                                <div
                                                    class="col-span-6 sm:col-span-3 lg:col-span-2"
                                                >
                                                    <label
                                                        for="middle-name"
                                                        class="block text-sm font-medium text-gray-700"
                                                        >Middle name</label
                                                    >
                                                    <input
                                                        type="text"
                                                        name="middle-name"
                                                        id="middlename"
                                                        v-model="
                                                            form.middle_name
                                                        "
                                                        autocomplete="given-name"
                                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                                    />
                                                </div>
                                                <div
                                                    class="col-span-6 sm:col-span-3 lg:col-span-2"
                                                >
                                                    <label
                                                        for="last-name"
                                                        class="block text-sm font-medium text-gray-700"
                                                        >Last name</label
                                                    >
                                                    <input
                                                        v-model="form.last_name"
                                                        type="text"
                                                        name="last-name"
                                                        id="last-name"
                                                        autocomplete="family-name"
                                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                                    />
                                                </div>

                                                <div
                                                    class="col-span-6 sm:col-span-3"
                                                >
                                                    <label
                                                        for="country"
                                                        class="block text-sm font-medium text-gray-700"
                                                        >Country</label
                                                    >
                                                    <select
                                                        id="country"
                                                        name="country"
                                                        autocomplete="country-name"
                                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                    >
                                                        <option>
                                                            Philippines
                                                        </option>
                                                    </select>
                                                </div>
                                                <div
                                                    class="col-span-6 sm:col-span-3"
                                                >
                                                    <label
                                                        for="country"
                                                        class="block text-sm font-medium text-gray-700"
                                                        >Contact Number</label
                                                    >
                                                    <input
                                                        type="text"
                                                        name="street-address"
                                                        id="street-address"
                                                        autocomplete="street-address"
                                                        v-model="
                                                            form.contact_number
                                                        "
                                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                                    />
                                                </div>
                                                <div class="col-span-6">
                                                    <label
                                                        for="street-address"
                                                        class="block text-sm font-medium text-gray-700"
                                                        >Street address</label
                                                    >
                                                    <input
                                                        type="text"
                                                        name="street-address"
                                                        id="street-address"
                                                        autocomplete="street-address"
                                                        v-model="form.address"
                                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                                    />
                                                </div>
                                                <div
                                                    class="col-span-6 sm:col-span-3 lg:col-span-2"
                                                >
                                                    <label
                                                        for="region"
                                                        class="block text-sm font-medium text-gray-700"
                                                        >State / Province</label
                                                    >

                                                    <select
                                                        v-model="form.province"
                                                        id="province"
                                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                                    >
                                                        <option
                                                            value="14"
                                                            selected
                                                        >
                                                            BULACAN
                                                        </option>
                                                    </select>
                                                </div>
                                                <div
                                                    class="col-span-6 sm:col-span-6 lg:col-span-2"
                                                >
                                                    <label
                                                        for="city"
                                                        class="block text-sm font-medium text-gray-700"
                                                        >City</label
                                                    >
                                                    <select
                                                        v-model="
                                                            form.municipality
                                                        "
                                                        id="municipality"
                                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                                    >
                                                        <option
                                                            value="0"
                                                            disabled
                                                        >
                                                            Select
                                                        </option>
                                                        <option
                                                            v-for="municipality in municipalities"
                                                            :key="
                                                                municipality.id
                                                            "
                                                            :value="
                                                                municipality.id
                                                            "
                                                        >
                                                            {{
                                                                municipality.value
                                                            }}
                                                        </option>
                                                    </select>
                                                </div>
                                                <div
                                                    class="col-span-6 sm:col-span-6 lg:col-span-2"
                                                >
                                                    <label
                                                        class="block text-sm font-medium text-gray-700"
                                                        >Barangay</label
                                                    >

                                                    <select
                                                        v-model="form.barangay"
                                                        id="barangays"
                                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                                    >
                                                        <!-- mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md -->
                                                        <option
                                                            value="0"
                                                            selected=""
                                                            disabled
                                                        >
                                                            Select
                                                        </option>
                                                        <option
                                                            v-for="barangay in filteredBrgys"
                                                            :key="barangay.id"
                                                            :value="
                                                                barangay.value
                                                            "
                                                        >
                                                            {{ barangay.value }}
                                                        </option>
                                                    </select>
                                                </div>
                                                <div
                                                    class="col-span-6 sm:col-span-3 lg:col-span-2"
                                                >
                                                    <label
                                                        for="postal-code"
                                                        class="block text-sm font-medium text-gray-700"
                                                        >ZIP / Postal
                                                        code</label
                                                    >
                                                    <input
                                                        v-model="form.zipcode"
                                                        type="text"
                                                        name="postal-code"
                                                        id="postal-code"
                                                        autocomplete="postal-code"
                                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                                    />
                                                </div>
                                                <div
                                                    class="col-span-6 sm:col-span-3 lg:col-span-2"
                                                >
                                                    <label
                                                        for="postal-code"
                                                        class="block text-sm font-medium text-gray-700"
                                                        >Strategic
                                                        Location</label
                                                    >

                                                    <Multiselect
                                                        ref="multiselect_strategic_location"
                                                        mode="single"
                                                        v-model="
                                                            form.strategic_location
                                                        "
                                                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                                        placeholder="Strategic Location"
                                                        :filter-results="false"
                                                        :min-chars="1"
                                                        :resolve-on-load="false"
                                                        :delay="0"
                                                        :searchable="true"
                                                        :create-option="true"
                                                        :options="
                                                            async function (
                                                                query
                                                            ) {
                                                                return await fetch_selectfield(
                                                                    query,
                                                                    'strategic_location'
                                                                );
                                                            }
                                                        "
                                                    />
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

                        <div class="mt-10 sm:mt-0">
                            <div class="md:grid md:grid-cols-3 md:gap-6">
                                <div class="md:col-span-1">
                                    <div class="px-4 sm:px-0">
                                        <h3
                                            class="text-lg font-medium leading-6 text-gray-900"
                                        >
                                            Amounts / Denominations / Statuses
                                        </h3>
                                        <p class="mt-1 text-sm text-gray-600">
                                            Decide which communications you'd
                                            like to receive and how.
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-5 md:mt-0 md:col-span-2">
                                    <!-- shadow overflow-hidden -->
                                    <div class="shadow sm:rounded-md">
                                        <div
                                            class="px-4 py-5 bg-white space-y-6 sm:p-6"
                                        >
                                            <!-- Receipts -->
                                            <fieldset>
                                                <legend class="sr-only">
                                                    Reciepts
                                                </legend>

                                                <legend class="sr-only">
                                                    Reciepts
                                                </legend>
                                                <div
                                                    class="my-2 text-base font-medium text-gray-900"
                                                    aria-hidden="true"
                                                >
                                                    Reciepts
                                                </div>

                                                <div
                                                    class="grid grid-cols-6 gap-6"
                                                >
                                                    <div
                                                        class="col-span-6 sm:col-span-3 lg:col-span-2"
                                                    >
                                                        <label
                                                            for="region"
                                                            class="block text-sm font-medium text-gray-700"
                                                            >Official Receipt
                                                            Number (O.R.
                                                            NO.)</label
                                                        >
                                                        <input
                                                            v-model="
                                                                form.official_receipt_number
                                                            "
                                                            type="text"
                                                            name="receipt_number"
                                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                                        />
                                                    </div>
                                                    <div
                                                        class="col-span-6 sm:col-span-6 lg:col-span-2"
                                                    >
                                                        <label
                                                            class="block text-sm font-medium text-gray-700"
                                                            >Official Receipt
                                                            Date (O.R.
                                                            Date.)</label
                                                        >
                                                        <input
                                                            type="text"
                                                            v-model="
                                                                form.official_receipt_date
                                                            "
                                                            autocomplete="address-level2"
                                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                                        />
                                                    </div>
                                                    <div
                                                        class="col-span-6 sm:col-span-6 lg:col-span-2"
                                                    >
                                                        <label
                                                            class="block text-sm font-medium text-gray-700"
                                                            >Gross
                                                            Receipts</label
                                                        >
                                                        <input
                                                            v-model="
                                                                form.gross_receipts
                                                            "
                                                            placeholder=""
                                                            type="text"
                                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                                        />
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <!-- Tax -->
                                            <fieldset>
                                                <legend class="sr-only">
                                                    Tax
                                                </legend>

                                                <legend class="sr-only">
                                                    Tax
                                                </legend>
                                                <div
                                                    class="my-2 text-base font-medium text-gray-900"
                                                    aria-hidden="true"
                                                >
                                                    Tax
                                                </div>

                                                <div
                                                    class="grid grid-cols-6 gap-6"
                                                >
                                                    <div
                                                        class="col-span-6 sm:col-span-3 lg:col-span-2"
                                                    >
                                                        <label
                                                            for="region"
                                                            class="block text-sm font-medium text-gray-700"
                                                            >Business Tax</label
                                                        >
                                                        <input
                                                            v-model="
                                                                form.business_tax
                                                            "
                                                            type="text"
                                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                                        />
                                                    </div>
                                                    <div
                                                        class="col-span-6 sm:col-span-6 lg:col-span-2"
                                                    >
                                                        <label
                                                            class="block text-sm font-medium text-gray-700"
                                                            >Tax Year</label
                                                        >
                                                        <input
                                                            type="text"
                                                            v-model="
                                                                form.tax_year
                                                            "
                                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                                        />
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <!-- Amounts -->
                                            <fieldset>
                                                <legend class="sr-only">
                                                    Amounts
                                                </legend>

                                                <legend class="sr-only">
                                                    Amounts
                                                </legend>
                                                <div
                                                    class="my-2 text-base font-medium text-gray-900"
                                                    aria-hidden="true"
                                                >
                                                    Amounts
                                                </div>

                                                <div
                                                    class="my-4 grid grid-cols-6 gap-6"
                                                >
                                                    <div
                                                        class="col-span-6 sm:col-span-3 lg:col-span-2"
                                                    >
                                                        <label
                                                            for="region"
                                                            class="block text-sm font-medium text-gray-700"
                                                            >Capital
                                                            Amount</label
                                                        >

                                                        <input
                                                            v-model="
                                                                form.capital
                                                            "
                                                            placeholder="Capital"
                                                            type="text"
                                                            name="receipt_number"
                                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                                        />
                                                    </div>
                                                    <div
                                                        class="col-span-6 sm:col-span-6 lg:col-span-2"
                                                    >
                                                        <label
                                                            class="block text-sm font-medium text-gray-700"
                                                            >Fees</label
                                                        >
                                                        <input
                                                            type="text"
                                                            v-model="form.fees"
                                                            autocomplete="address-level2"
                                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                                        />
                                                    </div>
                                                    <div
                                                        class="col-span-6 sm:col-span-6 lg:col-span-2"
                                                    >
                                                        <label
                                                            class="block text-sm font-medium text-gray-700"
                                                            >Surcharge</label
                                                        >
                                                        <input
                                                            v-model="
                                                                form.surcharge
                                                            "
                                                            placeholder=""
                                                            type="text"
                                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                                        />
                                                    </div>
                                                </div>
                                                <div
                                                    class="my-4 grid grid-cols-6 gap-6"
                                                >
                                                    <div
                                                        class="col-span-6 sm:col-span-3 lg:col-span-2"
                                                    >
                                                        <label
                                                            for="region"
                                                            class="block text-sm font-medium text-gray-700"
                                                            >Total</label
                                                        >

                                                        <input
                                                            v-model="form.total"
                                                            placeholder="Total"
                                                            type="text"
                                                            name="receipt_number"
                                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                                        />
                                                    </div>
                                                    <div
                                                        class="col-span-6 sm:col-span-6 lg:col-span-2"
                                                    >
                                                        <label
                                                            class="block text-sm font-medium text-gray-700"
                                                            >QTR Paid</label
                                                        >
                                                        <Multiselect
                                                            ref="multiselect_qtr_paid"
                                                            mode="single"
                                                            v-model="
                                                                form.qtr_paid
                                                            "
                                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                                            placeholder="select or add qtr paid"
                                                            :filter-results="
                                                                false
                                                            "
                                                            :min-chars="1"
                                                            :resolve-on-load="
                                                                false
                                                            "
                                                            :delay="0"
                                                            :searchable="true"
                                                            :create-option="
                                                                true
                                                            "
                                                            :options="
                                                                async function (
                                                                    query
                                                                ) {
                                                                    return await fetch_selectfield(
                                                                        query,
                                                                        'qtr_paid'
                                                                    );
                                                                }
                                                            "
                                                        />
                                                    </div>
                                                    <div
                                                        class="col-span-6 sm:col-span-6 lg:col-span-2"
                                                    >
                                                        <label
                                                            class="block text-sm font-medium text-gray-700"
                                                            >Terms</label
                                                        >
                                                        <Multiselect
                                                            ref="multiselect_terms"
                                                            mode="single"
                                                            v-model="form.terms"
                                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                                            placeholder="select or add Terms"
                                                            :filter-results="
                                                                false
                                                            "
                                                            :min-chars="1"
                                                            :resolve-on-load="
                                                                false
                                                            "
                                                            :delay="0"
                                                            :searchable="true"
                                                            :create-option="
                                                                true
                                                            "
                                                            :options="
                                                                async function (
                                                                    query
                                                                ) {
                                                                    return await fetch_selectfield(
                                                                        query,
                                                                        'terms'
                                                                    );
                                                                }
                                                            "
                                                        />
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <!-- Status -->
                                            <fieldset>
                                                <legend class="sr-only">
                                                    Numbers
                                                </legend>

                                                <legend class="sr-only">
                                                    Numbers
                                                </legend>
                                                <div
                                                    class="my-2 text-base font-medium text-gray-900"
                                                    aria-hidden="true"
                                                >
                                                    Numbers
                                                </div>

                                                <div
                                                    class="grid grid-cols-6 gap-6"
                                                >
                                                    <div
                                                        class="col-span-6 sm:col-span-3 lg:col-span-2"
                                                    >
                                                        <label
                                                            for="region"
                                                            class="block text-sm font-medium text-gray-700"
                                                            >Code</label
                                                        >
                                                        <Multiselect
                                                            ref="multiselect_code"
                                                            mode="single"
                                                            v-model="form.code"
                                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                                            placeholder="Select or add Code"
                                                            :filter-results="
                                                                false
                                                            "
                                                            :min-chars="1"
                                                            :resolve-on-load="
                                                                false
                                                            "
                                                            :delay="0"
                                                            :searchable="true"
                                                            :create-option="
                                                                true
                                                            "
                                                            :options="
                                                                async function (
                                                                    query
                                                                ) {
                                                                    return await fetch_selectfield(
                                                                        query,
                                                                        'code'
                                                                    );
                                                                }
                                                            "
                                                        />
                                                    </div>
                                                    <div
                                                        class="col-span-6 sm:col-span-6 lg:col-span-2"
                                                    >
                                                        <label
                                                            class="block text-sm font-medium text-gray-700"
                                                            >Permit
                                                            Number</label
                                                        >
                                                        <input
                                                            type="text"
                                                            v-model="
                                                                form.permit_number
                                                            "
                                                            autocomplete="address-level2"
                                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                                        />
                                                    </div>
                                                    <div
                                                        class="col-span-6 sm:col-span-6 lg:col-span-2"
                                                    >
                                                        <label
                                                            class="block text-sm font-medium text-gray-700"
                                                            >Status</label
                                                        >
                                                        <!-- {{ form.status }} -->
                                                        <Multiselect
                                                            ref="multiselect_status"
                                                            mode="single"
                                                            v-model="
                                                                form.status
                                                            "
                                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                                            placeholder="Select or Add Status"
                                                            :filter-results="
                                                                false
                                                            "
                                                            :min-chars="1"
                                                            :resolve-on-load="
                                                                false
                                                            "
                                                            :delay="0"
                                                            :searchable="true"
                                                            :create-option="
                                                                true
                                                            "
                                                            :options="
                                                                async function (
                                                                    query
                                                                ) {
                                                                    return await fetch_selectfield(
                                                                        query,
                                                                        'status'
                                                                    );
                                                                }
                                                            "
                                                        />
                                                    </div>
                                                </div>
                                            </fieldset>
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
                                                                route(
                                                                    'business'
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

                <!-- new template  -->
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<style src="@vueform/multiselect/themes/default.css"></style>
