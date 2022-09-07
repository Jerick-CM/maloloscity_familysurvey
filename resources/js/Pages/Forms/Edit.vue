<script>
import BreezeAuthenticatedLayout from "./../../Layouts/Form.vue";
import Breadcrumb from "./../../Components/BreadCrumb/navSurveyEdit.vue";
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

        const {
            family_position,
            familysurvey,
            getFamilySurvey,

            errors_fs,
            updateFamilySurvey,
            economic_risks,
            environment_and_disaster_risks,
            individual_lifecycle_risks,
            social_and_governance_risks,
        } = useFamilySurveys();

        const editFamilySurvey = async () => {
            toast.info("Sending update");
            submission_process.value = true;

            familysurvey.value.individual_lifecycle_risk =
                individual_lifecycle_risks.value;
            familysurvey.value.economic_risk = economic_risks.value;
            familysurvey.value.environmental_and_disaster_risk =
                environment_and_disaster_risks.value;
            familysurvey.value.social_and_governance_risk =
                social_and_governance_risks.value;

            submission_process.value = false;
            await updateFamilySurvey(route().params.id).then(() => {
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

        onMounted(async () => {
            form.province = 14;
            form.municipality = 10;
            form.zipcode = 3000;
            form.lalawigan = "BULACAN";

            for (let i = 0; i <= 73; i++) {
                checklist_form.value[i] = 1;
            }

            await getFamilySurvey(route().params.id);
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

        watch(
            () => family_position.value,
            (currentValue, oldValue) => {
                familysurvey.value.family_position = currentValue.value;
            },
            { deep: true }
        );

        return {
            checklist_4PS,
            checklist_form,
            filteredBrgys,
            form,
            editFamilySurvey,
            errors_fs,
            submission_process,
            toggleModal,
            data,
            modal_show,
            multiselect_familyposition,
            fetchSelectfield,
            familysurvey,
            family_position,

            individual_lifecycle_risks,
            economic_risks,
            environment_and_disaster_risks,
            social_and_governance_risks,
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

            <form class="" @submit.prevent="editFamilySurvey">
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
                                <span>Update</span>
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
                                    v-model="familysurvey.first_name"
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
                                    v-model="familysurvey.middle_name"
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
                                    v-model="familysurvey.last_name"
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
                                    v-model="familysurvey.name_suffix"
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
                                    :object="true"
                                    ref="multiselect_code"
                                    mode="single"
                                    v-model="family_position"
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
                                    v-model="familysurvey.number_of_children"
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
                                    v-model="
                                        familysurvey.number_of_people_in_household
                                    "
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
                                        v-model="
                                            familysurvey.four_ps_beneficiary
                                        "
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
                                        v-model="
                                            familysurvey.four_ps_beneficiary_id
                                        "
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
                                        v-model="
                                            familysurvey.four_ps_beneficiary
                                        "
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
                                v-model="familysurvey.four_ps_beneficiary_date"
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
                                    v-model="familysurvey.barangay"
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
                                    v-model="familysurvey.sitio"
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
                                    v-model="familysurvey.purok"
                                    :class="inputClass"
                                    type="text"
                                    placeholder="Purok"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <!-- {{ economic_risks }} -->

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
                                        v-model="
                                            individual_lifecycle_risks.pregnancy_and_birth
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            individual_lifecycle_risks.pregnancy_and_birth
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            individual_lifecycle_risks.pregnancy_and_birth
                                        "
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>2. Hindi nakapagpre-natal check up</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="
                                            individual_lifecycle_risks.no_prenatal_checkup
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            individual_lifecycle_risks.no_prenatal_checkup
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            individual_lifecycle_risks.no_prenatal_checkup
                                        "
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>3. Pagkalagas (abortion)</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="
                                            individual_lifecycle_risks.abortion
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            individual_lifecycle_risks.abortion
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            individual_lifecycle_risks.abortion
                                        "
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>4. Nakunan</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="
                                            individual_lifecycle_risks.miscarriage
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            individual_lifecycle_risks.miscarriage
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            individual_lifecycle_risks.miscarriage
                                        "
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
                                        v-model="
                                            individual_lifecycle_risks.death_ofa_child
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            individual_lifecycle_risks.death_ofa_child
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            individual_lifecycle_risks.death_ofa_child
                                        "
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>6. Hindi nakapagpost-natal check up</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="
                                            individual_lifecycle_risks.no_postnatal_checkup
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            individual_lifecycle_risks.no_postnatal_checkup
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            individual_lifecycle_risks.no_postnatal_checkup
                                        "
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
                                        v-model="
                                            individual_lifecycle_risks.kid_with_disability
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            individual_lifecycle_risks.kid_with_disability
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            individual_lifecycle_risks.kid_with_disability
                                        "
                                    />
                                </td>
                            </tr>

                            <tr>
                                <td>8. Di nabakunahan ang mga anak o bata</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="
                                            individual_lifecycle_risks.no_vaccine_in_childhood
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            individual_lifecycle_risks.no_vaccine_in_childhood
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            individual_lifecycle_risks.no_vaccine_in_childhood
                                        "
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
                                        v-model="
                                            individual_lifecycle_risks.familymember_disability_dueto_accident_sickness
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            individual_lifecycle_risks.familymember_disability_dueto_accident_sickness
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            individual_lifecycle_risks.familymember_disability_dueto_accident_sickness
                                        "
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
                                        v-model="
                                            individual_lifecycle_risks.death_dueto_accident_disaster
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            individual_lifecycle_risks.death_dueto_accident_disaster
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            individual_lifecycle_risks.death_dueto_accident_disaster
                                        "
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
                                        v-model="
                                            individual_lifecycle_risks.familymember_severe_or_longtime_sickness
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            individual_lifecycle_risks.familymember_severe_or_longtime_sickness
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            individual_lifecycle_risks.familymember_severe_or_longtime_sickness
                                        "
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
                                        v-model="
                                            individual_lifecycle_risks.no_awarded_medicalservice_healthcenter_or_hospital
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            individual_lifecycle_risks.no_awarded_medicalservice_healthcenter_or_hospital
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            individual_lifecycle_risks.no_awarded_medicalservice_healthcenter_or_hospital
                                        "
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
                                        v-model="
                                            individual_lifecycle_risks.not_able_to_buy_medicine
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            individual_lifecycle_risks.not_able_to_buy_medicine
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            individual_lifecycle_risks.not_able_to_buy_medicine
                                        "
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
                                        v-model="
                                            individual_lifecycle_risks.familymember_death_dueto_sickness
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            individual_lifecycle_risks.familymember_death_dueto_sickness
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            individual_lifecycle_risks.familymember_death_dueto_sickness
                                        "
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
                                        v-model="
                                            individual_lifecycle_risks.not_ableto_enrol_in_daycare_preschool
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            individual_lifecycle_risks.not_ableto_enrol_in_daycare_preschool
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            individual_lifecycle_risks.not_ableto_enrol_in_daycare_preschool
                                        "
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>16. Di nai-enrol sa elemtarya ang anak</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="
                                            individual_lifecycle_risks.not_ableto_enrol_in_elementary
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            individual_lifecycle_risks.not_ableto_enrol_in_elementary
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            individual_lifecycle_risks.not_ableto_enrol_in_elementary
                                        "
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
                                        v-model="
                                            individual_lifecycle_risks.not_ableto_enrol_in_highschool
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            individual_lifecycle_risks.not_ableto_enrol_in_highschool
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            individual_lifecycle_risks.not_ableto_enrol_in_highschool
                                        "
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
                                        v-model="
                                            individual_lifecycle_risks.not_ableto_enrol_in_college
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            individual_lifecycle_risks.not_ableto_enrol_in_college
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            individual_lifecycle_risks.not_ableto_enrol_in_college
                                        "
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
                                        v-model="
                                            individual_lifecycle_risks.stop_in_school_or_dropout_in_elementary_or_highschool
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            individual_lifecycle_risks.stop_in_school_or_dropout_in_elementary_or_highschool
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            individual_lifecycle_risks.stop_in_school_or_dropout_in_elementary_or_highschool
                                        "
                                    />
                                </td>
                            </tr>

                            <tr>
                                <td>20. Nalulong sa bawal na gamot o alak</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="
                                            individual_lifecycle_risks.drug_addiction
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            individual_lifecycle_risks.drug_addiction
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            individual_lifecycle_risks.drug_addiction
                                        "
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
                                        v-model="
                                            individual_lifecycle_risks.teen_pregnancy_below_18yearsold
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            individual_lifecycle_risks.teen_pregnancy_below_18yearsold
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            individual_lifecycle_risks.teen_pregnancy_below_18yearsold
                                        "
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
                                        v-model="
                                            individual_lifecycle_risks.ranaway_from_home
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            individual_lifecycle_risks.ranaway_from_home
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            individual_lifecycle_risks.ranaway_from_home
                                        "
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>23. Miyembro na nangibang bansa</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="
                                            individual_lifecycle_risks.familymember_in_abroad
                                        "
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            individual_lifecycle_risks.familymember_in_abroad
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            individual_lifecycle_risks.familymember_in_abroad
                                        "
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
                                        v-model="
                                            individual_lifecycle_risks.working_in_municipality_or_city
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            individual_lifecycle_risks.working_in_municipality_or_city
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            individual_lifecycle_risks.working_in_municipality_or_city
                                        "
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>25. Hiwalay sa asawa</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="
                                            individual_lifecycle_risks.separated_with_husbandwife
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            individual_lifecycle_risks.separated_with_husbandwife
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            individual_lifecycle_risks.separated_with_husbandwife
                                        "
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>26. Namatayan ng asawa</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="
                                            individual_lifecycle_risks.death_of_husbandwife
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            individual_lifecycle_risks.death_of_husbandwife
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            individual_lifecycle_risks.death_of_husbandwife
                                        "
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
                                        v-model="
                                            individual_lifecycle_risks.insufficient_food_security
                                        "
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            individual_lifecycle_risks.insufficient_food_security
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            individual_lifecycle_risks.insufficient_food_security
                                        "
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
                                        v-model="
                                            individual_lifecycle_risks.insufficient_clean_drinkable_water
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            individual_lifecycle_risks.insufficient_clean_drinkable_water
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            individual_lifecycle_risks.insufficient_clean_drinkable_water
                                        "
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
                                        v-model="
                                            individual_lifecycle_risks.home_made_of_lightsalvagable_material
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            individual_lifecycle_risks.home_made_of_lightsalvagable_material
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            individual_lifecycle_risks.home_made_of_lightsalvagable_material
                                        "
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
                                        v-model="
                                            individual_lifecycle_risks.scarcity_of_cleanbathroom
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            individual_lifecycle_risks.scarcity_of_cleanbathroom
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            individual_lifecycle_risks.scarcity_of_cleanbathroom
                                        "
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
                                        v-model="economic_risks.no_work"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="economic_risks.no_work"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="economic_risks.no_work"
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
                                        v-model="
                                            economic_risks.work_notalign_to_profession
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            economic_risks.work_notalign_to_profession
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            economic_risks.work_notalign_to_profession
                                        "
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
                                        v-model="
                                            economic_risks.experienced_workaccident
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            economic_risks.experienced_workaccident
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            economic_risks.experienced_workaccident
                                        "
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>34. Di nakabayad ng utang</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="economic_risks.unpaid_loans"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="economic_risks.unpaid_loans"
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="economic_risks.unpaid_loans"
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>35. Biktima ng scam</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="
                                            economic_risks.victim_of_scamming
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            economic_risks.victim_of_scamming
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            economic_risks.victim_of_scamming
                                        "
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>36. Biktima ng illegal recruiter</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="
                                            economic_risks.victim_of_illegalrecruitment
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            economic_risks.victim_of_illegalrecruitment
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            economic_risks.victim_of_illegalrecruitment
                                        "
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
                                        v-model="
                                            economic_risks.change_of_cityaddress
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            economic_risks.change_of_cityaddress
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            economic_risks.change_of_cityaddress
                                        "
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>38. Walang life insurance</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="
                                            economic_risks.no_lifeinsurance
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            economic_risks.no_lifeinsurance
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            economic_risks.no_lifeinsurance
                                        "
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>39. Walang health insurance</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="
                                            economic_risks.no_healthinsurance
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            economic_risks.no_healthinsurance
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            economic_risks.no_healthinsurance
                                        "
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
                                        v-model="
                                            economic_risks.working_without_sss_or_gsis
                                        "
                                        checked
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            economic_risks.working_without_sss_or_gsis
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            economic_risks.working_without_sss_or_gsis
                                        "
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>41. Pagkalugi sa negosyo</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="
                                            economic_risks.bankrupt_in_business
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            economic_risks.bankrupt_in_business
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            economic_risks.bankrupt_in_business
                                        "
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>42. Pagsangla ng ari-arian</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="
                                            economic_risks.mortgage_of_posessions
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            economic_risks.mortgage_of_posessions
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            economic_risks.mortgage_of_posessions
                                        "
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>43. Paghiram ng pera sa kamag-anak</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="
                                            economic_risks.loaned_money_from_relatives
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            economic_risks.loaned_money_from_relatives
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            economic_risks.loaned_money_from_relatives
                                        "
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
                                        v-model="
                                            economic_risks.family_elder_without_pension
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            economic_risks.family_elder_without_pension
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            economic_risks.family_elder_without_pension
                                        "
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
                                        v-model="
                                            environment_and_disaster_risks.victim_of_housefire
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            environment_and_disaster_risks.victim_of_housefire
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            environment_and_disaster_risks.victim_of_housefire
                                        "
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>46. Biktima ng bagyo o tagtuyot</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="
                                            environment_and_disaster_risks.victim_of_typoon_drought
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            environment_and_disaster_risks.victim_of_typoon_drought
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            environment_and_disaster_risks.victim_of_typoon_drought
                                        "
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
                                        v-model="
                                            environment_and_disaster_risks.victim_of_earthquake_volcanic_eruption
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            environment_and_disaster_risks.victim_of_earthquake_volcanic_eruption
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            environment_and_disaster_risks.victim_of_earthquake_volcanic_eruption
                                        "
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
                                        v-model="
                                            environment_and_disaster_risks.death_of_family_via_calamity
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            environment_and_disaster_risks.death_of_family_via_calamity
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            environment_and_disaster_risks.death_of_family_via_calamity
                                        "
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>49. Demolisyon</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="
                                            environment_and_disaster_risks.house_demolition
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            environment_and_disaster_risks.house_demolition
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            environment_and_disaster_risks.house_demolition
                                        "
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>50. Tumira sa relocation o resettlement</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="
                                            environment_and_disaster_risks.resided_in_relocation_resettlement
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            environment_and_disaster_risks.resided_in_relocation_resettlement
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            environment_and_disaster_risks.resided_in_relocation_resettlement
                                        "
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
                                        v-model="
                                            social_and_governance_risks.victim_of_burglary
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            social_and_governance_risks.victim_of_burglary
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            social_and_governance_risks.victim_of_burglary
                                        "
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
                                        v-model="
                                            social_and_governance_risks.experienced_buglary_holdup
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            social_and_governance_risks.experienced_buglary_holdup
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            social_and_governance_risks.experienced_buglary_holdup
                                        "
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>53. Nakaranas ng sekswal na karahasan</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="
                                            social_and_governance_risks.experienced_sexual_assult
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            social_and_governance_risks.experienced_sexual_assult
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            social_and_governance_risks.experienced_sexual_assult
                                        "
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
                                        v-model="
                                            social_and_governance_risks.qna_knowledge_with_youth_gangs
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            social_and_governance_risks.qna_knowledge_with_youth_gangs
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            social_and_governance_risks.qna_knowledge_with_youth_gangs
                                        "
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
                                        v-model="
                                            social_and_governance_risks.death_of_family_due_to_crime
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            social_and_governance_risks.death_of_family_due_to_crime
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            social_and_governance_risks.death_of_family_due_to_crime
                                        "
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
                                        v-model="
                                            social_and_governance_risks.witnessed_actual_crime_in_comminity
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            social_and_governance_risks.witnessed_actual_crime_in_comminity
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            social_and_governance_risks.witnessed_actual_crime_in_comminity
                                        "
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>57. Karahasan kaugnay sa eleksyon</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="
                                            social_and_governance_risks.election_violence
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            social_and_governance_risks.election_violence
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            social_and_governance_risks.election_violence
                                        "
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
                                        v-model="
                                            social_and_governance_risks.fear_of_disturbance_dueto_election
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            social_and_governance_risks.fear_of_disturbance_dueto_election
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            social_and_governance_risks.fear_of_disturbance_dueto_election
                                        "
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>59. Pag-aalsa laban sa pamahalaan</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="
                                            social_and_governance_risks.rebellion_against_government
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            social_and_governance_risks.rebellion_against_government
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            social_and_governance_risks.rebellion_against_government
                                        "
                                    />
                                </td>
                            </tr>

                            <tr>
                                <td>60. Pangugulo ng militar</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="
                                            social_and_governance_risks.rebellion_of_military
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            social_and_governance_risks.rebellion_of_military
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            social_and_governance_risks.rebellion_of_military
                                        "
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
                                        v-model="
                                            social_and_governance_risks.violence_at_home
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            social_and_governance_risks.violence_at_home
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            social_and_governance_risks.violence_at_home
                                        "
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>62. Pang-aabuso sa menor de edad</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="
                                            social_and_governance_risks.violence_to_minor
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            social_and_governance_risks.violence_to_minor
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            social_and_governance_risks.violence_to_minor
                                        "
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>63. Pagtatrabaho ng menor de edad</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="
                                            social_and_governance_risks.child_labor
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            social_and_governance_risks.child_labor
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            social_and_governance_risks.child_labor
                                        "
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
                                        v-model="
                                            social_and_governance_risks.corruption_in_government
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            social_and_governance_risks.corruption_in_government
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            social_and_governance_risks.corruption_in_government
                                        "
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
                                        v-model="
                                            social_and_governance_risks.nonparticipant_in_comunity_activities
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            social_and_governance_risks.nonparticipant_in_comunity_activities
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            social_and_governance_risks.nonparticipant_in_comunity_activities
                                        "
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>66. Di bumoboto</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="
                                            social_and_governance_risks.nonvoter
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            social_and_governance_risks.nonvoter
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            social_and_governance_risks.nonvoter
                                        "
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>67. Di kasapi ng anumang organisasyon</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="
                                            social_and_governance_risks.non_membership_inany_organization
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            social_and_governance_risks.non_membership_inany_organization
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            social_and_governance_risks.non_membership_inany_organization
                                        "
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>68. Pag-angkin ng lupa ng iba</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="
                                            social_and_governance_risks.claim_land_of_others
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            social_and_governance_risks.claim_land_of_others
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            social_and_governance_risks.claim_land_of_others
                                        "
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>69. Tradisyon na nakakasama sa tao.</td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="1"
                                        v-model="
                                            social_and_governance_risks.harmful_tradition_to_people
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            social_and_governance_risks.harmful_tradition_to_people
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            social_and_governance_risks.harmful_tradition_to_people
                                        "
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
                                        v-model="
                                            social_and_governance_risks.religious_misunderstanding_dispute
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            social_and_governance_risks.religious_misunderstanding_dispute
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            social_and_governance_risks.religious_misunderstanding_dispute
                                        "
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
                                        v-model="
                                            social_and_governance_risks.experienced_slow_justicesystem
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            social_and_governance_risks.experienced_slow_justicesystem
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            social_and_governance_risks.experienced_slow_justicesystem
                                        "
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
                                        v-model="
                                            social_and_governance_risks.discrimination_in_ethnicity_gender
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            social_and_governance_risks.discrimination_in_ethnicity_gender
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            social_and_governance_risks.discrimination_in_ethnicity_gender
                                        "
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
                                        v-model="
                                            social_and_governance_risks.tribal_dispute
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            social_and_governance_risks.tribal_dispute
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            social_and_governance_risks.tribal_dispute
                                        "
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
                                        v-model="
                                            social_and_governance_risks.other_harms_to_family
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="2"
                                        v-model="
                                            social_and_governance_risks.other_harms_to_family
                                        "
                                    />
                                </td>
                                <td class="text-center">
                                    <input
                                        type="radio"
                                        value="3"
                                        v-model="
                                            social_and_governance_risks.other_harms_to_family
                                        "
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
                                Update
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
