<script>
import BreezeAuthenticatedLayout from "./../../Layouts/Form.vue";
import { Head, Link, usePage } from "@inertiajs/inertia-vue3";
import { ref, reactive, computed, onMounted, watch } from "vue";
import useSoloParent from "./../../composables/soloparent";
import { useToast } from "vue-toastification";
import Modal from "./../../Components/Modals/Modal_Create.vue";
import Multiselect from "@vueform/multiselect";
import Breadcrumb from "./../../Components/BreadCrumb/navSoloParentEdit.vue";

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
        /* init */
        const form = reactive({
            province: 14,
            municipality: null,
            region: "III",
        });

        const year_group = reactive([
            { value: "2022", label: "2022" },
            { value: "2023", label: "2023" },
            { value: "2050", label: "2050" },
        ]);

        const year_selection = ref([
            { value: "2022", label: "2022" },
            { value: "2023", label: "2023" },
            { value: "2024", label: "2024" },
            { value: "2025", label: "2025" },
        ]);

        const {
            soloparent_application_date,
            soloparent,
            errors_soloparent,
            soloparent_renewals,
            muxsel_barangay,
            muxsel_gender,
            muxsel_remarks,
            muxsel_notes,
            getSoloParent,
            updateSoloParent,
        } = useSoloParent();

        onMounted(async () => {
            form.province = 14;
            form.municipality = 10;
            form.lalawigan = "BULACAN";
            await getSoloParent(route().params.id);
            form.application_date = soloparent_application_date.value;
        });

        const filterBrgys = async (munId) => {
            filteredBrgys.value = brgys.value.filter(
                (brgy) => brgy.parent === munId
            );
        };

        const toggleModal = () => {
            modal_show.value = !modal_show.value;
        };

        const editSoloParent = async () => {
            toast.info("Sending update");

            await updateSoloParent(route().params.id).then(() => {
                if (errors_soloparent.value) {
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

        /* watch events */
        watch(
            () => form.municipality,
            (value) => {
                filterBrgys(value);
            }
        );

        watch(
            () => muxsel_notes.value,
            (currentValue, oldValue) => {
                soloparent.value.notes = currentValue.value;
            },
            { deep: true }
        );

        watch(
            () => muxsel_remarks.value,
            (currentValue, oldValue) => {
                soloparent.value.remarks = currentValue.value;
            },
            { deep: true }
        );

        watch(
            () => muxsel_gender.value,
            (currentValue, oldValue) => {
                soloparent.value.muxsel_gender = currentValue.value;
            },
            { deep: true }
        );

        watch(
            () => year_group,
            (value) => {
                console.log(value);
            }
        );

        watch(
            () => soloparent_renewals.value,
            (value) => {
                let data = [];
                value.forEach((element) => {
                    data.push(element.value);
                });
                soloparent.value.year_renewal = data;
            },
            { deep: true }
        );

        watch(
            () => soloparent_application_date.value,
            (currentValue, oldValue) => {
                soloparent.value.application_date = currentValue.value;
            },
            { deep: true }
        );

        return {
            filteredBrgys,
            form,
            errors_soloparent,
            submission_process,
            data,
            modal_show,
            soloparent,
            year_group,
            year_selection,
            soloparent_renewals,
            muxsel_barangay,
            muxsel_gender,
            muxsel_remarks,
            muxsel_notes,
            soloparent_application_date,
            toggleModal,
            editSoloParent,
            fetchSelectfield,
        };
    },
};
</script>

<template>
    <Head title="Edit Solo Parents" />
    <BreezeAuthenticatedLayout>
        <template #header> Solo Parent Edit Form </template>

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

            <form class="" @submit.prevent="editSoloParent">
                <div class="flex flex-col 2xl:flex-row xl:flex-row lg:flex-row">
                    <div
                        class="w-full 2xl:w-2/4 xl:w-2/4 lg:w-2/4 flex flex-col 2xl:flex-row xl:flex-row lg:flex-row justify-items-end place-content-end"
                    ></div>

                    <div
                        class="w-full 2xl:w-2/4 xl:w-2/4 lg:w-2/4 flex flex-col 2xl:flex-row xl:flex-row lg:flex-row justify-items-end place-content-end"
                    ></div>
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
                                v-model="soloparent_renewals"
                                mode="tags"
                                :object="true"
                                :close-on-select="false"
                                :searchable="true"
                                :create-option="true"
                                :options="year_selection"
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
                                v-model="soloparent.id_number"
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
                                v-model="soloparent_application_date"
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
                                    v-model="soloparent.first_name"
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
                                    v-model="soloparent.middle_name"
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
                                    v-model="soloparent.last_name"
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
                                    v-model="soloparent.name_suffix"
                                    placeholder="Jr., Sr. ,III"
                                />
                            </div>
                        </div>

                        <div class="flex flex-wrap -mx-3">
                            <div class="w-full md:w-1/4 px-3 py-1">
                                <label
                                    class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                >
                                    Full Name
                                </label>
                                <input
                                    :class="inputClass"
                                    v-model="soloparent.full_name"
                                    type="text"
                                    placeholder="Full Name"
                                />
                            </div>
                            <div class="w-full md:w-1/4 px-3 py-1">
                                <label
                                    class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                >
                                    Birth of Date
                                </label>
                                <input
                                    :class="inputClass"
                                    v-model="soloparent.date_of_birth"
                                    type="date"
                                />
                            </div>

                            <div class="w-full md:w-1/4 px-3 py-1">
                                <label
                                    class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                >
                                    Gender
                                </label>

                                <Multiselect
                                    :object="true"
                                    mode="single"
                                    v-model="muxsel_gender"
                                    class="focus:ring-2 focus:ring-blue-500 focus:outline-none appearance-none w-full text-sm leading-6 text-slate-900 placeholder-slate-400 rounded-md py-2 ring-1 ring-slate-200 shadow-sm"
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
                                :object="true"
                                ref="multiselect_address"
                                mode="single"
                                v-model="muxsel_complete_address"
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

                            <Multiselect
                                :object="true"
                                mode="single"
                                v-model="muxsel_barangay"
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
                                            'barangay'
                                        );
                                    }
                                "
                            />
                        </div>
                    </div>

                    <div class="py-1 font-medium text-red-700">2. Children</div>

                    <div class="flex flex-wrap -mx-3">
                        <div class="w-full md:w-1/4 px-3 py-1">
                            <label
                                class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            >
                                Number of Son
                            </label>
                            <input
                                :class="inputClass"
                                v-model="soloparent.sons"
                                type="number"
                            />
                        </div>
                        <div class="w-full md:w-1/4 px-3 py-1">
                            <label
                                class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            >
                                Number of Daugther
                            </label>
                            <input
                                :class="inputClass"
                                v-model="soloparent.daughters"
                                type="number"
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
                                :object="true"
                                mode="single"
                                v-model="muxsel_remarks"
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
                                :object="true"
                                mode="single"
                                v-model="muxsel_notes"
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
                        <div class=""></div>
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

tr:hover {
    background-color: coral;
    cursor: pointer;
}
td {
    padding: 2px 2px 2px 2px;
}
</style>
<style src="@vueform/multiselect/themes/default.css"></style>
