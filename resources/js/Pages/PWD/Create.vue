<script>
import BreezeAuthenticatedLayout from "./../../Layouts/Form.vue";
import Breadcrumb from "./../../Components/BreadCrumb/navPWDCreate.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import { ref, reactive, computed, onMounted, watch } from "vue";
import usePWD from "./../../composables/pwd";
import { useToast } from "vue-toastification";
import Modal from "./../../Components/Modals/Modal_Create.vue";
import Multiselect from "@vueform/multiselect";

export default {
    data: () => ({
        risksHeaderClass: "bg-blue-100",
        inputClass:
            "bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5",
    }),
    components: {
        BreezeAuthenticatedLayout,
        Head,
        Link,
        Breadcrumb,
        Modal,
        Multiselect,
    },

    props: ["barangays", "municipalities"],

    setup(props, { attrs, slots, emit, expose }) {

        const toast = useToast();
        const brgys = computed(() => props.barangays);
        const filteredBrgys = ref([]);
        const submission_process = ref(false);
        const modal_show = ref(false);
        const data = ref(false);

        const multiselect_yearrenewal = ref(null);
        const multiselect_address = ref(null);
        const multiselect_disability = ref(null);
        const multiselect_causeofdisability = ref(null);
        const multiselect_remarks = ref(null);
        const multiselect_notes = ref(null);

        /* init */
        const form = reactive({
            province: 14,
            municipality: null,
            region: "III",
        });

        const { errors_pwd, storePWD } = usePWD();

        const submitPWD = async () => {
            toast.info("Sending create");
            submission_process.value = true;
            await storePWD({ ...form }).then(() => {
                if (errors_pwd.value) {
                    submission_process.value = false;
                    toast.error("Submit failed.");
                } else {
                    modal_show.value = true;
                    submission_process.value = false;
                    toast.success("Submit success.");
                    clearFields();
                }
            });
        };

        onMounted(() => {
            form.province = 14;
            form.municipality = 10;
            form.zipcode = 3000;
            form.lalawigan = "BULACAN";
        });

        const clearFields = () => {
            form.date = "";
            form.first_name = "";
            form.middle_name = "";
            form.last_name = "";
            form.barangay = "";
            form.gender = "";
            form.address = "";
            form.date_of_application = "";
            form.id_number = "";

            multiselect_address.value.clear();
            multiselect_disability.value.clear();
            multiselect_causeofdisability.value.clear();
            multiselect_remarks.value.clear();
            multiselect_notes.value.clear();
            multiselect_yearrenewal.value.clear();
        };

        const filterBrgys = async (munId) => {
            filteredBrgys.value = brgys.value.filter(
                (brgy) => brgy.parent === munId
            );
        };

        const toggleModal = () => {
            modal_show.value = !modal_show.value;
        };

        const fetchSelectfield = async (query, field) => {
            let data;
            await axios
                .post(route("pwd-multiselect"), {
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

        watch(
            () => form.municipality,
            (value) => {
                filterBrgys(value);
            }
        );

        return {
            filteredBrgys,
            form,
            errors_pwd,
            submission_process,
            data,
            modal_show,
            multiselect_address,
            multiselect_disability,
            multiselect_causeofdisability,
            multiselect_remarks,
            multiselect_notes,
            submitPWD,
            toggleModal,
            fetchSelectfield,
        };
    },
};
</script>
<template>
    <Head title="PWD" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <div>Persons with Disability (PWD) Create Page</div>
        </template>
        <div class="w-full mx-auto sm:px-6 lg:px-8">
            <Breadcrumb />
            <Modal :showmodal="modal_show" @toggle="toggleModal()" :info="data">
            </Modal>
            <div v-if="errors_pwd">
                <div
                    v-for="(v, k) in errors_pwd"
                    :key="k"
                    class="bg-red-500 text-white rounded font-bold mb-4 shadow-lg py-2 px-4 pr-0"
                >
                    <p v-for="error in v" :key="error" class="text-sm">
                        {{ error }}
                    </p>
                </div>
            </div>

            <form class="" @submit.prevent="submitPWD">
                <div class="flex flex-col 2xl:flex-row xl:flex-row lg:flex-row">
                    <div
                        class="w-full 2xl:w-2/4 xl:w-2/4 lg:w-2/4 flex flex-col 2xl:flex-row xl:flex-row lg:flex-row justify-items-end place-content-end"
                    ></div>

                    <div
                        class="w-full 2xl:w-2/4 xl:w-2/4 lg:w-2/4 flex flex-col 2xl:flex-row xl:flex-row lg:flex-row justify-items-end place-content-end"
                    >
                        <div class="">
                            <button
                                :disabled="submission_process"
                                type="submit"
                                :class="
                                    submission_process
                                        ? 'inline-flex justify-center py-2 px-10 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500'
                                        : 'inline-flex justify-center py-2 px-10 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500'
                                "
                            >
                                <svg
                                    v-if="submission_process"
                                    role="status"
                                    class="inline mr-3 w-4 h-4 text-white animate-spin stroke-1"
                                    viewBox="0 0 100 101"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                        fill="#E5E7EB"
                                    />
                                    <path
                                        d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                        fill="currentColor"
                                    />
                                </svg>
                                <span>Create</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="my-3 bg-white rounded p-5 sm:p1">
                    <div class="flex flex-wrap -mx-3">
                        <div class="w-full md:w-1/4 px-3 py-1">
                            <label
                                class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            >
                            </label>
                        </div>

                        <div class="w-full md:w-1/4 px-3 py-1">
                            <label
                                class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            >
                                New / Renewal Year
                            </label>

                            <Multiselect
                                mode="single"
                                v-model="form.year"
                                class="focus:ring-2 focus:ring-blue-500 focus:outline-none appearance-none w-full text-sm leading-6 text-slate-900 placeholder-slate-400 rounded-md py-2 ring-1 ring-slate-200 shadow-sm"
                                placeholder=""
                                :filter-results="false"
                                :min-chars="1"
                                :resolve-on-load="false"
                                :delay="0"
                                :searchable="true"
                                :create-option="true"
                                :options="[
                                    { value: '2022', label: '2022' },
                                    { value: '2023', label: '2023' },
                                    { value: '2024', label: '2024' },
                                    { value: '2025', label: '2025' },
                                    { value: '2026', label: '2026' },
                                    { value: '2027', label: '2027' },
                                    { value: '2028', label: '2028' },
                                    { value: '2029', label: '2029' },
                                    { value: '2030', label: '2030' },
                                    { value: '2031', label: '2031' },
                                    { value: '2032', label: '2032' },
                                    { value: '2033', label: '2033' },
                                    { value: '2034', label: '2034' },
                                    { value: '2035', label: '2035' },
                                    { value: '2036', label: '2036' },
                                    { value: '2037', label: '2037' },
                                    { value: '2038', label: '2038' },
                                    { value: '2039', label: '2039' },
                                    { value: '2040', label: '2040' },
                                    { value: '2041', label: '2041' },
                                    { value: '2042', label: '2042' },
                                    { value: '2043', label: '2043' },
                                    { value: '2044', label: '2044' },
                                    { value: '2045', label: '2045' },
                                    { value: '2046', label: '2046' },
                                    { value: '2047', label: '2047' },
                                    { value: '2048', label: '2048' },
                                    { value: '2049', label: '2049' },
                                    { value: '2050', label: '2050' },
                                    { value: '2051', label: '2051' },
                                    { value: '2052', label: '2052' },
                                    { value: '2053', label: '2053' },
                                    { value: '2054', label: '2054' },
                                    { value: '2055', label: '2055' },
                                    { value: '2056', label: '2056' },
                                    { value: '2057', label: '2057' },
                                    { value: '2058', label: '2058' },
                                    { value: '2059', label: '2059' },
                                    { value: '2060', label: '2060' },
                                    { value: '2061', label: '2061' },
                                    { value: '2062', label: '2062' },
                                    { value: '2063', label: '2063' },
                                    { value: '2064', label: '2064' },
                                    { value: '2065', label: '2065' },
                                    { value: '2066', label: '2066' },
                                    { value: '2067', label: '2067' },
                                    { value: '2068', label: '2068' },
                                    { value: '2069', label: '2069' },
                                    { value: '2070', label: '2070' },
                                    { value: '2071', label: '2071' },
                                    { value: '2072', label: '2072' },
                                    { value: '2073', label: '2073' },
                                    { value: '2074', label: '2074' },
                                    { value: '2075', label: '2075' },
                                    { value: '2076', label: '2076' },
                                    { value: '2077', label: '2077' },
                                    { value: '2078', label: '2078' },
                                    { value: '2079', label: '2079' },
                                    { value: '2080', label: '2080' },
                                    { value: '2081', label: '2081' },
                                    { value: '2082', label: '2082' },
                                    { value: '2083', label: '2083' },
                                    { value: '2084', label: '2084' },
                                    { value: '2085', label: '2085' },
                                    { value: '2086', label: '2086' },
                                    { value: '2087', label: '2087' },
                                    { value: '2088', label: '2088' },
                                    { value: '2089', label: '2089' },
                                    { value: '2090', label: '2090' },
                                    { value: '2091', label: '2091' },
                                    { value: '2092', label: '2092' },
                                    { value: '2093', label: '2093' },
                                    { value: '2094', label: '2094' },
                                    { value: '2095', label: '2095' },
                                    { value: '2096', label: '2096' },
                                    { value: '2097', label: '2097' },
                                    { value: '2098', label: '2098' },
                                    { value: '2099', label: '2099' },
                                    { value: '2100', label: '2100' },
                                ]"
                            />

                        </div>

                        <div class="w-full md:w-1/4 px-3 py-1">
                            <label
                                class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            >
                                ID Number
                            </label>
                            <input
                                :class="inputClass"
                                v-model="form.id_number"
                            />
                        </div>

                        <div class="w-full md:w-1/4 px-3 py-1">
                            <label
                                class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            >
                                Filing Date
                            </label>
                            <input
                                :class="inputClass"
                                v-model="form.date_of_application"
                                type="date"
                            />
                        </div>
                    </div>

                    <div class="py-2">
                        <div class="py-1 font-semibold">I. Pagkakakilanlan</div>

                        <div class="py-1 font-medium text-red-700">
                            1. Pangalan
                        </div>

                        <div class="flex flex-wrap -mx-3">
                            <div class="w-full md:w-1/4 px-3 py-1">
                                <label
                                    class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                >
                                    First Name
                                </label>
                                <input
                                    v-model="form.first_name"
                                    :class="inputClass"
                                    type="text"
                                    placeholder="First Name"
                                />
                            </div>
                            <div class="w-full md:w-1/4 px-3 py-1">
                                <label
                                    class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                >
                                    Middle Name or M.I.
                                </label>
                                <input
                                    v-model="form.middle_name"
                                    :class="inputClass"
                                    type="text"
                                    placeholder="Middle Name or Middle Initial"
                                />
                            </div>
                            <div class="w-full md:w-1/4 px-3 py-1">
                                <label
                                    class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                >
                                    Last Name
                                </label>
                                <input
                                    :class="inputClass"
                                    v-model="form.last_name"
                                    type="text"
                                    placeholder="Last Name"
                                />
                            </div>
                            <div class="w-full md:w-1/4 px-3 py-1">
                                <label
                                    class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                    for="grid-last-name"
                                >
                                    Name Suffix
                                </label>
                                <input
                                    :class="inputClass"
                                    type="text"
                                    v-model="form.name_suffix"
                                    placeholder="Jr., Sr. ,III"
                                />
                            </div>
                        </div>

                        <div class="flex flex-wrap -mx-3">
                            <div class="w-full md:w-1/4 px-3 py-1">
                                <label
                                    class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                >
                                    Birth of Date
                                </label>
                                <input
                                    :class="inputClass"
                                    v-model="form.date_of_birth"
                                    type="date"
                                    placeholder=""
                                />
                            </div>
                            <div class="w-full md:w-1/4 px-3 py-1"></div>
                            <div class="w-full md:w-1/4 px-3 py-1">
                                <label
                                    class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                >
                                    Gender
                                </label>
                                <Multiselect
                                    mode="single"
                                    v-model="form.gender"
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
                                                'gender'
                                            );
                                        }
                                    "
                                />
                            </div>
                            <div class="w-full md:w-1/4 px-3 py-1"></div>
                        </div>
                    </div>

                    <div class="py-1 font-medium text-red-700">
                        2. Address Location
                    </div>

                    <div class="flex flex-wrap -mx-3">
                        <div class="w-full md:w-2/4 px-3 py-1">
                            <label
                                class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            >
                                Complete Address
                            </label>

                            <Multiselect
                                ref="multiselect_address"
                                mode="single"
                                v-model="form.address"
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
                                            'address'
                                        );
                                    }
                                "
                            />
                        </div>
                        <div class="w-full md:w-1/4 px-3 py-1">
                            <label
                                class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            >
                                Barangay
                            </label>

                            <select
                                v-model="form.barangay"
                                id="barangays"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                            >
                                <option value="0" selected="" disabled>
                                    Select
                                </option>
                                <option
                                    v-for="barangay in filteredBrgys"
                                    :key="barangay.id"
                                    :value="barangay.value"
                                >
                                    {{ barangay.value }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- row 4  -->
                    <div class="py-1 font-medium text-red-700">
                        3. Nature of Disability
                    </div>
                    <div class="flex flex-wrap -mx-3">
                        <div class="w-full md:w-2/4 px-3 py-1">
                            <label
                                class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            >
                                Disability
                            </label>
                            <Multiselect
                                ref="multiselect_disability"
                                mode="single"
                                v-model="form.disability"
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
                                            'disability'
                                        );
                                    }
                                "
                            />
                        </div>

                        <div class="w-full md:w-2/4 px-3 py-1">
                            <label
                                class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            >
                                Cause of Disability
                            </label>
                            <Multiselect
                                ref="multiselect_causeofdisability"
                                mode="single"
                                v-model="form.cause_of_disability"
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
                                            'cause_of_disability'
                                        );
                                    }
                                "
                            />
                        </div>
                    </div>

                    <div class="py-1 font-medium text-red-700">4. Notes</div>
                    <div class="flex flex-wrap -mx-3">
                        <div class="w-full md:w-2/4 px-3 py-1">
                            <label
                                class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            >
                                Remarks
                            </label>

                            <Multiselect
                                ref="multiselect_remarks"
                                mode="single"
                                v-model="form.remarks"
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
                                            'remarks'
                                        );
                                    }
                                "
                            />
                        </div>
                        <div class="w-full md:w-2/4 px-3 py-1">
                            <label
                                class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            >
                                Notes
                            </label>

                            <Multiselect
                                ref="multiselect_notes"
                                mode="single"
                                v-model="form.notes"
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
                                            'notes'
                                        );
                                    }
                                "
                            />
                        </div>
                    </div>
                </div>
                <div class="flex flex-col 2xl:flex-row xl:flex-row lg:flex-row">
                    <div
                        class="w-full 2xl:w-2/4 xl:w-2/4 lg:w-2/4 flex flex-col 2xl:flex-row xl:flex-row lg:flex-row justify-items-end place-content-end"
                    ></div>
                    <div
                        class="w-full 2xl:w-2/4 xl:w-2/4 lg:w-2/4 flex flex-col 2xl:flex-row xl:flex-row lg:flex-row justify-items-end place-content-end"
                    >
                        <div class="">
                            <button
                                :disabled="submission_process"
                                type="submit"
                                :class="
                                    submission_process
                                        ? 'inline-flex justify-center py-2 px-10 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500'
                                        : 'inline-flex justify-center py-2 px-10 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500'
                                "
                            >
                                <svg
                                    v-if="submission_process"
                                    role="status"
                                    class="inline mr-3 w-4 h-4 text-white animate-spin stroke-1"
                                    viewBox="0 0 100 101"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                        fill="#E5E7EB"
                                    />
                                    <path
                                        d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                        fill="currentColor"
                                    />
                                </svg>
                                <span>Create</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            <div v-if="errors_pwd">
                <div
                    v-for="(v, k) in errors_pwd"
                    :key="k"
                    class="bg-red-500 text-white rounded font-bold mb-4 shadow-lg py-2 px-4 pr-0"
                >
                    <p v-for="error in v" :key="error" class="text-sm">
                        {{ error }}
                    </p>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
<style scoped>
table,
th,
td {
    border: 1px solid black;
}
input[type="radio"] + label span {
    transition: background 0.2s, transform 0.2s;
}

input[type="radio"] + label span:hover,
input[type="radio"] + label:hover span {
    transform: scale(1.2);
}

input[type="radio"]:checked + label span {
    background-color: #1f9d55;
    box-shadow: 0px 0px 0px 2px white inset;
}

input[type="radio"]:checked + label {
    color: #1f9d55;
}

tr:hover {
    background-color: coral;
    cursor: pointer;
}
td {
    padding: 2px 2px 2px 2px;
}
</style>
<style src="@vueform/multiselect/themes/default.css"></style>
