<script>
import BreezeAuthenticatedLayout from "./../../Layouts/Form.vue";
import Breadcrumb from "./../../Components/BreadCrumb/navSurveyCreate.vue";
import { Head, Link, usePage } from "@inertiajs/inertia-vue3";
import { ref, reactive, computed, onMounted, watch } from "vue";
import useFamilySurveys from "./../../composables/familysurvey";
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
        const checklist_form = ref([]);
        const checklist_4PS = ref([]);
        const submission_process = ref(false);
        const modal_show = ref(false);
        const data = ref(false);

        const multiselect_familyposition = ref(null);

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

        const { storeFamilySurvey, errors_fs } = useFamilySurveys();
        const submitSurvey = async () => {
            toast.info("Sending create");
            submission_process.value = true;
            form.four_ps_beneficiary = checklist_4PS.value;
            form.checklist = checklist_form.value;
            await storeFamilySurvey({ ...form }).then(() => {
                if (errors_fs.value) {
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

            for (let i = 0; i <= 73; i++) {
                checklist_form.value[i] = 1;
            }
        });
        const clearFields = () => {
            form.first_name = "";
            form.middle_name = "";
            form.last_name = "";
            form.name_suffix = "";
            form.family_position = "";
            form.number_of_children = "";
            form.number_of_people_in_household = "";
            form.four_ps_beneficiary_id = "";
            form.four_ps_beneficiary_date = "";
            form.barangay = "";
            form.sitio = "";
            form.purok = "";
            multiselect_familyposition.value.clear();
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

        watch(
            () => form.municipality,
            (value) => {
                filterBrgys(value);
            }
        );

        return {
            checklist_4PS,
            checklist_form,
            filteredBrgys,
            form,
            submitSurvey,
            errors_fs,
            submission_process,
            toggleModal,
            data,
            modal_show,
            multiselect_familyposition,
            fetchSelectfield,
        };
    },
};
</script>
<template>
    <Head title="Survey Form Entry" />
    <BreezeAuthenticatedLayout>
        <template #header>
            <div>
                <p class="text-center">
                    Department of Social Welfare and Development
                </p>
                <p class="text-center">
                    FAMILY SURVEY ON RISKS AND VULNERABILITY
                </p>
            </div>
        </template>
        <div class="w-full mx-auto sm:px-6 lg:px-8">
            <Breadcrumb />
            <Modal :showmodal="modal_show" @toggle="toggleModal()" :info="data">
            </Modal>
            <div v-if="errors_fs">
                <div
                    v-for="(v, k) in errors_fs"
                    :key="k"
                    class="bg-red-500 text-white rounded font-bold mb-4 shadow-lg py-2 px-4 pr-0"
                >
                    <p v-for="error in v" :key="error" class="text-sm">
                        {{ error }}
                    </p>
                </div>
            </div>
            <form class="" @submit.prevent="submitSurvey">
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
                                <span>Submit</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="my-3 bg-white rounded p-5 sm:p1">
                    <!-- row 1 -->
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
                    </div>
                    <!-- row 2 -->
                    <div class="py-2">
                        <div class="flex flex-wrap -mx-3">
                            <div class="w-full md:w-1/4 px-3 py-1">
                                <div class="py-1 font-medium text-red-700">
                                    2. Posisyon sa Pamilya
                                </div>
                                <label
                                    class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                    for="grid-first-name"
                                >
                                    Position in the Family
                                </label>

                                <Multiselect
                                    ref="multiselect_familyposition"
                                    mode="single"
                                    v-model="form.family_position"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    placeholder="Position in the Family"
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
                                                'family_position'
                                            );
                                        }
                                    "
                                />
                            </div>

                            <div class="w-full md:w-1/4 px-3 py-1">
                                <div class="py-1 font-medium text-red-700">
                                    3. Bilang ng Anak
                                </div>
                                <label
                                    class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                    for="grid-first-name"
                                >
                                    Number of children
                                </label>
                                <input
                                    v-model="form.number_of_children"
                                    :class="inputClass"
                                    type="number"
                                    placeholder="Total number of children"
                                />
                            </div>
                            <div class="w-full md:w-1/4 px-3 py-1">
                                <div class="py-1 font-medium text-red-700">
                                    4. Bilang ng pamilya sa bahay
                                </div>
                                <label
                                    class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                    for="grid-first-name"
                                >
                                    Number of Families in the household
                                </label>
                                <input
                                    v-model="form.number_of_people_in_household"
                                    :class="inputClass"
                                    type="number"
                                    placeholder="Total families in the household"
                                />
                            </div>
                        </div>
                    </div>
                    <!-- row 3 -->
                    <div class="py-2">
                        <div class="flex flex-wrap -mx-3">
                            <div class="w-full md:w-1/4 px-3 py-1">
                                <div class="py-1 font-medium text-red-700">
                                    5. 4P's Beneficiary ba?
                                </div>
                            </div>

                            <div class="w-full md:w-1/4 px-3 py-1">
                                <div
                                    class="pl-4 rounded border border-gray-200 h-10 flex items-center"
                                >
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_4PS"
                                    />
                                    <label
                                        for="bordered-checkbox-1"
                                        class="py-4 ml-2 w-full text-sm font-medium text-gray-900"
                                        >Oo</label
                                    >
                                </div>
                            </div>
                            <div class="w-full md:w-1/4 px-3 py-1">
                                <div class="flex flex-items">
                                    <label
                                        class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                        for="grid-first-name"
                                    >
                                        HH ID#
                                    </label>
                                    <input
                                        v-model="form.four_ps_beneficiary_id"
                                        :class="inputClass"
                                        type="text"
                                        placeholder="HH ID#"
                                    />
                                </div>
                            </div>
                            <div class="w-full md:w-1/4 px-3 py-1">
                                <div
                                    class="pl-4 rounded border border-gray-200 h-10 flex items-center"
                                >
                                    <input
                                        type="radio"
                                        value="0"
                                        v-model="checklist_4PS"
                                    />
                                    <label
                                        for="bordered-checkbox-1"
                                        class="py-4 ml-2 w-full text-sm font-medium text-gray-900"
                                        >Hindi</label
                                    >
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="py-1 font-semibold">Kung Oo, Kailan pa?</div>
                    <div class="flex flex-wrap -mx-3">
                        <div class="w-full md:w-2/4 px-3 mb-6 md:mb-0">
                            <input
                                v-model="form.four_ps_beneficiary_date"
                                :class="inputClass"
                                placeholder=""
                                type="date"
                            />
                        </div>
                    </div>
                </div>
                <!-- Address -->
                <div class="my-3 bg-white rounded p-5 sm:p1">
                    <!-- row 1 -->
                    <div class="py-2">
                        <div class="py-1 font-semibold">Address:</div>

                        <div class="flex flex-wrap -mx-3">
                            <div class="w-full md:w-1/4 px-3 py-1">
                                <label
                                    for="grid-first-name"
                                    class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                >
                                    Rehiyon
                                </label>
                                <input
                                    v-model="form.region"
                                    :class="inputClass"
                                    type="text"
                                    placeholder="III"
                                />
                            </div>
                            <div class="w-full md:w-1/4 px-3 py-1">
                                <label
                                    class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                >
                                    Lalawigan
                                </label>
                                <input
                                    v-model="form.lalawigan"
                                    :class="inputClass"
                                    type="text"
                                    placeholder="Bulacan"
                                />
                            </div>
                            <div class="w-full md:w-1/4 px-3 py-1">
                                <label
                                    class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                >
                                    Bayan/Lunsod
                                </label>

                                <select
                                    v-model="form.municipality"
                                    id="municipality"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                >
                                    <option value="0" disabled>Select</option>
                                    <option
                                        v-for="municipality in municipalities"
                                        :key="municipality.id"
                                        :value="municipality.id"
                                    >
                                        {{ municipality.value }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="flex flex-wrap -mx-3">
                            <div class="w-full md:w-1/4 px-3 py-1">
                                <label
                                    for="grid-first-name"
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
                            <div class="w-full md:w-1/4 px-3 py-1">
                                <label
                                    class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                >
                                    Sitio
                                </label>

                                <input
                                    v-model="form.sitio"
                                    :class="inputClass"
                                    type="text"
                                    placeholder="Sitio"
                                />
                            </div>
                            <div class="w-full md:w-1/4 px-3 py-1">
                                <label
                                    class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                >
                                    Purok
                                </label>
                                <input
                                    v-model="form.purok"
                                    :class="inputClass"
                                    type="text"
                                    placeholder="Purok"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- table divider -->
                <div class="my-3 bg-white rounded p-5 sm:p1">
                    <table
                        class="w-full border-collapse border border-slate-500 table-fixed md:table-fixed"
                    >
                        <thead>
                            <tr>
                                <td rowspan="3" width="70%">
                                    II. MGA PANGANIB (RISK) AT VULNERABILITY NA
                                    KINAHARAP NG PAMILYA
                                </td>
                                <td rowspan="3">Lagyan ng ng (x) kung wala</td>
                                <td colspan="2">Kung meron /0o, ilan?</td>
                            </tr>
                            <tr>
                                <td rowspan="2">Ngayong taon</td>
                                <td rowspan="2">2-5 taon na nakalipas</td>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- table2 30 -->
                            <!-- INDIVIDUAL LIFE CYCLE RISKS  -->
                            <tr :class="risksHeaderClass">
                                <td>INDIVIDUAL LIFE CYCLE RISKS</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>1. Pagbubuntis at panganganak</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[0]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[0]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[0]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>2. Hindi nakapagpre-natal check up</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[1]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[1]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[1]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>3. Pagkalagas (abortion)</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[2]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[2]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[2]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>4. Nakunan</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[3]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[3]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[3]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    5. Pagkawala ng anak na may kapansanan ng
                                    isinilang
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[4]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[4]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[4]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>6. Hindi nakapagpost-natal check up</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[5]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[5]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[5]"
                                    />
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    7. Pagkakaroon ng anak na may kapansanan ng
                                    isinilang
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[6]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[6]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[6]"
                                    />
                                </td>
                            </tr>

                            <tr>
                                <td>8. Di nabakunahan ang mga anak o bata</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[7]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[7]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[7]"
                                    />
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    9. Miyembro ng pamilya na may malubhang
                                    kapansanan dahil sa sakit o aksidente
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[8]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[8]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[8]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    10. Namatay dahils sa sakuna o aksidente
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[9]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[9]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[9]"
                                    />
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    11. Miyembro ng pamilya na may malubha o
                                    matagal nang sakit?
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[10]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[10]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[10]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    12. Di nakatanggap ng serbisyong medikal sa
                                    health center o ospital?
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[11]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[11]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[11]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    13. Di nakabili ng kinakailangang gamot"
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[12]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[12]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[12]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    14. Miyembro ng pamilya na pumanaw dahil sa
                                    karamdaman
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[13]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[13]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[13]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    15. Di nai-enrol sa day care o pre-school
                                    ang mga anak
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[14]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[14]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[14]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>16. Di nai-enrol sa elemtarya ang anak</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[15]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[15]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[15]"
                                    />
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    17. Di nai-enrol sa high school ang anak
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[16]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[16]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[16]"
                                    />
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    18. Di nai-enrol o nahinto sa kolehiyo ang
                                    anak
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[17]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[17]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[17]"
                                    />
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    19. Tumigil sa pag-aaral o nagdrop out ng
                                    elementarya o high school ang anak
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[18]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[18]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[18]"
                                    />
                                </td>
                            </tr>

                            <tr>
                                <td>20. Nalulong sa bawal na gamot o alak</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[19]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[19]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[19]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    21. Nabuntis na menor de edad? (below 18
                                    years old)
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[20]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[20]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[20]"
                                    />
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    22. Naglayas o hindi umuwi ng bahay ng
                                    walang paalam sa magulang
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[21]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[21]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[21]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>23. Miyembro na nangibang bansa</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[22]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[22]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[22]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    24. Naghahanapbuhay sa ibang munisipyo o
                                    siyudad
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[23]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[23]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[23]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>25. Hiwalay sa asawa</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[24]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[24]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[24]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>26. Namatayan ng asawa</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[25]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[25]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[25]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    27. Di sapat ang pagkain ng pamilya o
                                    naranasan ang kawalan ng pagkain
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[26]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[26]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[26]"
                                    />
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    28. kakulangan ng malinis na inuming tubig
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[27]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[27]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[27]"
                                    />
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    29. Bahay ay gawa sa light /salvaged
                                    material
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[28]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[28]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[28]"
                                    />
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    30. Kawalan ng malinis na CR o palikuran
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[29]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[29]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[29]"
                                    />
                                </td>
                            </tr>

                            <!-- table 2 - 30  -->
                            <tr :class="risksHeaderClass">
                                <td>ECONOMIC RISK</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>31. Walang hanapbuhay</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[30]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[30]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[30]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    32. Hanapbuhay na di akma sa tinapos na
                                    kurso
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[31]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[31]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[31]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    33. Naranasang maaksidente habang nasa
                                    trabaho
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[32]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[32]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[32]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>34. Di nakabayad ng utang</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[33]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[33]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[33]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>35. Biktima ng scam</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[34]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[34]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[34]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>36. Biktima ng illegal recruiter</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[35]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[35]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[35]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    37. Lumipat ng tirahan sa ibang munisipyo
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[36]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[36]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[36]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>38. Walang life insurance</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[37]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[37]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[37]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>39. Walang health insurance</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[38]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[38]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[38]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    40. Naghahanapbuhay pero walang SSS o GSIS
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[39]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[39]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[39]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>41. Pagkalugi sa negosyo</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[40]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[40]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[40]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>42. Pagsangla ng ari-arian</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[41]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[41]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[41]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>43. Paghiram ng pera sa kamag-anak</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[42]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[42]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[42]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    44. Kapamilya na nakakatanda na di
                                    nakkatanggap ng pension
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[43]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[43]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[43]"
                                    />
                                </td>
                            </tr>
                            <!-- economic risk 14 rows -->
                            <tr :class="risksHeaderClass">
                                <td>ENVIRONMENT AND DISASTER RISKS</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>45. Biktima ng sunog</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[44]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[44]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[44]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>46. Biktima ng bagyo o tagtuyot</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[45]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[45]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[45]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    47. Biktima ng lindol o pagputok ng bulkan
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[46]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[46]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[46]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    48. Nawalan ng miyembro ng pamilya sanhi ng
                                    kalamidad
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[47]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[47]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[47]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>49. Demolisyon</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[48]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[48]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[48]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>50. Tumira sa relocation o resettlement</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[49]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[49]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[49]"
                                    />
                                </td>
                            </tr>
                            <!-- environmental risk 6 rows -->
                            <!-- social and governance risk 24 rows -->
                            <tr :class="risksHeaderClass">
                                <td>SOCIAL AND GOVERNANCE RISKS</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>51. Biktima ng pagnanakaw sa bahay</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[50]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[50]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[50]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    52. Nakaranas ng pagnanakaw o hold-up sa
                                    daan
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[51]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[51]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[51]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>53. Nakaranas ng sekswal na karahasan</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[52]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[52]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[52]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    54. May alam po ba kayong "gang" ng kabataan
                                    sa barangay
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[53]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[53]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[53]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    55. Namatayan ng kapamilya dahil sa krimen
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[54]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[54]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[54]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    56 Nakakita ng aktuwal na krimen na naganap
                                    sa komunidad
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[55]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[55]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[55]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>57. Karahasan kaugnay sa eleksyon</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[56]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[56]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[56]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    58. Takot at panggugulo dahil sa pulitika
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[57]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[57]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[57]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>59. Pag-aalsa laban sa pamahalaan</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[58]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[58]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[58]"
                                    />
                                </td>
                            </tr>

                            <tr>
                                <td>60. Pangugulo ng militar</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[59]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[59]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[59]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    61. Karahasan sa tahanan (pananakit ng
                                    asawa, anak etc.
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[60]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[60]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[60]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>62. Pang-aabuso sa menor de edad</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[61]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[61]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[61]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>63. Pagtatrabaho ng menor de edad</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[62]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[62]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[62]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    64. Katiwalian sa pamahalaan (corruption,
                                    bribery, embezzlement etc)
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[63]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[63]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[63]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    65. Di nakikilahok sa aktibidades ng
                                    komunidad
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[64]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[64]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[64]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>66. Di bumoboto</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[65]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[65]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[65]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>67. Di kasapi ng anumang organisasyon</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[66]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[66]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[66]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>68. Pag-angkin ng lupa ng iba</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[67]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[67]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[67]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>69. Tradisyon na nakakasama sa tao.</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[68]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[68]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[68]"
                                    />
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    70. Kaguluhan o di pagkakaunawaan dahil sa
                                    relihiyon
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[69]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[69]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[69]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    71. Nakaranas ng mabagal na judicial system
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[70]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[70]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[70]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    72. Diskriminasyon dahil sa pinagmulan o
                                    kasarian (ethnicity, gender, etc)
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[71]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[71]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[71]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    73. Alitan o tunggali dahil sa pagsasalungat
                                    ng tribu
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[72]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[72]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[72]"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    74. Ibang panganib na kinakaharap ng
                                    mag-anak
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="checklist_form[73]"
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="checklist_form[73]"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="checklist_form[73]"
                                    />
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            <div v-if="errors_fs">
                <div
                    v-for="(v, k) in errors_fs"
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
/*
table {
    border: 1px solid #ccc;
    border-collapse: collapse;
    margin: 0;
    padding: 0;
    width: 100%;
    table-layout: fixed;
}

table caption {
    font-size: 1.5em;
    margin: 0.5em 0 0.75em;
}

table tr {
    background-color: #f8f8f8;
    border: 1px solid #ddd;
    padding: 0.35em;
}

table th,
table td {
    padding: 0.625em;
    text-align: center;
}

table th {
    font-size: 0.85em;
    letter-spacing: 0.1em;
    text-transform: uppercase;
}

@media screen and (max-width: 600px) {
    table {
        border: 0;
    }

    table caption {
        font-size: 1.3em;
    }

    table thead {
        border: none;
        clip: rect(0 0 0 0);
        height: 1px;
        margin: -1px;
        overflow: hidden;
        padding: 0;
        position: absolute;
        width: 1px;
    }

    table tr {
        border-bottom: 3px solid #ddd;
        display: block;
        margin-bottom: 0.625em;
    }

    table td {
        border-bottom: 1px solid #ddd;
        display: block;
        font-size: 0.8em;
        text-align: right;
    }

    table td::before {

        content: attr(data-label);
        float: left;
        font-weight: bold;
        text-transform: uppercase;
    }

    table td:last-child {
        border-bottom: 0;
    }
} */

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
