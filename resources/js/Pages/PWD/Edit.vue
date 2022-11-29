<script>
import BreezeAuthenticatedLayout from "./../../Layouts/Form.vue";
import Breadcrumb from "./../../Components/BreadCrumb/navISFView.vue";
import { Head, Link, usePage } from "@inertiajs/inertia-vue3";
import { ref, reactive, computed, onMounted, watch } from "vue";
import useISF from "./../../composables/isf_and_illegalencroachments";
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

        const multiselect_street = ref(null);
        const multiselect_balik_probinsya = ref(null);
        const multiselect_tenurial_status = ref(null);
        const multiselect_zone = ref(null);
        const multiselect_body_of_water_name = ref(null);
        const multiselect_body_of_water_type = ref(null);
        const multiselect_distance_to_waterway = ref(null);

        const searchParameter = reactive({
            searchField: "",
            searchValue: "",
            filterField: "",
            filterValue: "",
        });

        /* init */
        const form = reactive({
            province: 14,
            municipality: null,
            region: "III",
        });

        const {
            isf,
            getISF,
            destroyISF,
            errors_isf,
            loadFromServer,
            updateISF,
            street,
            balik_probinsya,
            tenurial_status,
            zone,
            body_of_water_name,
            body_of_water_type,
            distance_to_waterway,
        } = useISF();

        onMounted(async () => {
            form.province = 14;
            form.municipality = 10;
            form.lalawigan = "BULACAN";
            console.log(form.municipality);
            await getISF(route().params.id);
        });

        const filterBrgys = async (munId) => {
            filteredBrgys.value = brgys.value.filter(
                (brgy) => brgy.parent === munId
            );
        };

        const toggleModal = () => {
            modal_show.value = !modal_show.value;
        };

        watch(
            () => form.municipality,
            (value) => {
                filterBrgys(value);
            }
        );

        watch(
            () => street.value,
            (currentValue, oldValue) => {
                isf.value.street = currentValue.value;
            },
            { deep: true }
        );

        watch(
            () => balik_probinsya.value,
            (currentValue, oldValue) => {
                isf.value.balik_probinsya = currentValue.value;
            },
            { deep: true }
        );

        watch(
            () => tenurial_status.value,
            (currentValue, oldValue) => {
                isf.value.tenurial_status = currentValue.value;
            },
            { deep: true }
        );

        watch(
            () => zone.value,
            (currentValue, oldValue) => {
                isf.value.zone = currentValue.value;
            },
            { deep: true }
        );

        watch(
            () => body_of_water_name.value,
            (currentValue, oldValue) => {
                isf.value.body_of_water_name = currentValue.value;
            },
            { deep: true }
        );

        watch(
            () => body_of_water_type.value,
            (currentValue, oldValue) => {
                isf.value.body_of_water_type = currentValue.value;
            },
            { deep: true }
        );

        watch(
            () => distance_to_waterway.value,
            (currentValue, oldValue) => {
                isf.value.distance_to_waterway = currentValue.value;
            },
            { deep: true }
        );

        const editISF = async () => {
            toast.info("Sending update");
            await updateISF(route().params.id).then(() => {
                if (errors_isf.value) {
                    submission_process.value = false;
                    toast.error("Submit failed.");
                } else {
                    modal_show.value = true;
                    submission_process.value = false;
                    toast.success("Submit success.");
                }
            });
        };

        const fetchSelectfield = async (query, field) => {
            let data;
            await axios
                .post("/request/isf/getSelectfield", {
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
        return {
            filteredBrgys,
            form,
            errors_isf,
            submission_process,
            toggleModal,
            data,
            modal_show,
            isf,
            editISF,
            multiselect_street,
            multiselect_balik_probinsya,
            multiselect_tenurial_status,
            multiselect_zone,
            multiselect_body_of_water_name,
            multiselect_body_of_water_type,
            multiselect_distance_to_waterway,
            fetchSelectfield,
            street,
            balik_probinsya,
            tenurial_status,
            zone,
            body_of_water_name,
            body_of_water_type,
            distance_to_waterway,
        };
    },
};
</script>

<template>
    <Head title="Informal Settler Families (ISF) and Illegal Encroachments" />
    <BreezeAuthenticatedLayout>
        <template #header>
            Informal Settler Families (ISF) and Illegal Encroachments
        </template>

        <div class="w-full mx-auto sm:px-6 lg:px-8">
            <Breadcrumb />

            <Modal :showmodal="modal_show" @toggle="toggleModal()" :info="data">
            </Modal>

            <div v-if="errors_isf">
                <div
                    v-for="(v, k) in errors_isf"
                    :key="k"
                    class="bg-red-500 text-white rounded font-bold mb-4 shadow-lg py-2 px-4 pr-0"
                >
                    <p v-for="error in v" :key="error" class="text-sm">
                        {{ error }}
                    </p>
                </div>
            </div>
            <!-- row 1 -->
            <form class="" @submit.prevent="editISF">
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
                                :class="
                                    submission_process
                                        ? 'inline-flex justify-center py-2 px-10 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500'
                                        : 'inline-flex justify-center py-2 px-10 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500'
                                "
                                type="submit"
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
                                Update
                            </button>
                        </div>
                    </div>
                </div>
                <div class="my-3 bg-white rounded p-5 sm:p1">
                    <div class="flex flex-wrap -mx-3">
                        <div class="w-full md:w-1/4 px-3 py-1"></div>
                        <div class="w-full md:w-1/4 px-3 py-1"></div>
                        <div class="w-full md:w-1/4 px-3 py-1"></div>
                        <div class="w-full md:w-1/4 px-3 py-1">
                            <label
                                class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            >
                                Filing Date
                            </label>
                            <input
                                :class="inputClass"
                                v-model="isf.date"
                                type="date"
                                placeholder=""
                            />
                        </div>
                    </div>

                    <!-- row 2 name and info -->
                    <div class="py-1 font-medium text-red-700">
                        1. Personal Info
                    </div>
                    <div class="flex flex-wrap -mx-3">
                        <div class="w-full md:w-1/4 px-3 py-1">
                            <label
                                class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            >
                                Household Head
                            </label>
                            <input
                                v-model="isf.household_head"
                                :class="inputClass"
                                type="text"
                                placeholder=""
                            />
                        </div>
                        <div class="w-full md:w-1/4 px-3 py-1">
                            <label
                                class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            >
                                Birth date (YYYY/MM/DD)
                            </label>
                            <input
                                v-model="isf.birthdate"
                                :class="inputClass"
                                type="date"
                                placeholder=""
                            />
                        </div>
                        <div class="w-full md:w-1/4 px-3 py-1">
                            <label
                                class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            >
                                Spouse Name
                            </label>
                            <input
                                :class="inputClass"
                                v-model="isf.spouse_name"
                                type="text"
                                placeholder=""
                            />
                        </div>
                        <div class="w-full md:w-1/4 px-3 py-1">
                            <label
                                class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            >
                                Spouse Birthdate (YYYY/MM/DD)
                            </label>
                            <input
                                :class="inputClass"
                                v-model="isf.spouse_birthdate"
                                type="date"
                                placeholder="Distance to waterways"
                            />
                        </div>
                    </div>

                    <!-- row 3 family and info -->
                    <div class="py-1 font-medium text-red-700">
                        2. Address and Geographical Location
                    </div>
                    <div class="flex flex-wrap -mx-3">
                        <div class="w-full md:w-1/4 px-3 py-1">
                            <label
                                class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            >
                                Street
                            </label>

                            <Multiselect
                                :object="true"
                                ref="multiselect_street"
                                mode="single"
                                v-model="street"
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
                                            'street'
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
                                v-model="isf.barangay"
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
                        <div class="w-full md:w-1/4 px-3 py-1">
                            <label
                                class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            >
                                Latitude
                            </label>
                            <input
                                v-model="isf.latitude"
                                :class="inputClass"
                                type="text"
                                placeholder=""
                            />
                        </div>
                        <div class="w-full md:w-1/4 px-3 py-1">
                            <label
                                class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            >
                                Longitude
                            </label>
                            <input
                                v-model="isf.longitude"
                                :class="inputClass"
                                type="text"
                                placeholder=""
                            />
                        </div>
                    </div>

                    <!-- row 4  -->
                    <div class="py-1 font-medium text-red-700">3. Status</div>
                    <div class="flex flex-wrap -mx-3">
                        <div class="w-full md:w-1/4 px-3 py-1">
                            <label
                                class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            >
                                Balik Probinsya
                            </label>

                            <Multiselect
                                :object="true"
                                ref="multiselect_balik_probinsya"
                                mode="single"
                                v-model="balik_probinsya"
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
                                            'balik_probinsya'
                                        );
                                    }
                                "
                            />
                        </div>
                        <div class="w-full md:w-1/4 px-3 py-1">
                            <label
                                class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            >
                                Number of Family Members
                            </label>
                            <input
                                :class="inputClass"
                                v-model="isf.no_of_family_members"
                                type="number"
                                placeholder=""
                            />
                        </div>
                        <div class="w-full md:w-1/4 px-3 py-1">
                            <label
                                class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            >
                                Tenurial Status
                            </label>
                            <Multiselect
                                :object="true"
                                ref="multiselect_tenurial_status"
                                mode="single"
                                v-model="tenurial_status"
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
                                            'tenurial_status'
                                        );
                                    }
                                "
                            />
                        </div>
                        <div class="w-full md:w-1/4 px-3 py-1">
                            <label
                                class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            >
                                Zone
                            </label>

                            <Multiselect
                                :object="true"
                                ref="multiselect_zone"
                                mode="single"
                                v-model="zone"
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
                                            'zone'
                                        );
                                    }
                                "
                            />
                        </div>
                    </div>
                    <div class="py-1 font-medium text-red-700">
                        4. Waterways
                    </div>
                    <!-- row 1 water -->
                    <div class="flex flex-wrap -mx-3">
                        <div class="w-full md:w-1/4 px-3 py-1">
                            <label
                                class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            >
                                Body of Water Name
                            </label>
                            <Multiselect
                                :object="true"
                                ref="multiselect_body_of_water_name"
                                mode="single"
                                v-model="body_of_water_name"
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
                                            'body_of_water_name'
                                        );
                                    }
                                "
                            />
                        </div>
                        <div class="w-full md:w-1/4 px-3 py-1">
                            <label
                                class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            >
                                Body of Water Type
                            </label>
                            <Multiselect
                                :object="true"
                                ref="multiselect_body_of_water_type"
                                mode="single"
                                v-model="body_of_water_type"
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
                                            'body_of_water_type'
                                        );
                                    }
                                "
                            />
                        </div>
                        <div class="w-full md:w-1/4 px-3 py-1">
                            <label
                                class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            >
                                Distance to Waterways
                            </label>

                            <Multiselect
                                :object="true"
                                ref="multiselect_distance_to_waterway"
                                mode="single"
                                v-model="distance_to_waterway"
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
                                            'distance_to_waterway'
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
                                :class="
                                    submission_process
                                        ? 'inline-flex justify-center py-2 px-10 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500'
                                        : 'inline-flex justify-center py-2 px-10 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500'
                                "
                                type="submit"
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
                                Update
                            </button>
                        </div>
                    </div>
                </div>
            </form>
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

/* tr:nth-child(even) {
        background-color: rgb(243, 182, 182);
    }
    tr:nth-child(od) {
        background-color: rgb(242, 169, 169);
    } */

tr:hover {
    background-color: coral;
    cursor: pointer;
}
td {
    padding: 2px 2px 2px 2px;
}
</style>
<style src="@vueform/multiselect/themes/default.css"></style>
