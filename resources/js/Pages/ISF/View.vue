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

        const { isf, getISF, destroyISF, errors_isf, loadFromServer } =
            useISF();

        onMounted(async () => {
            form.province = 14;
            form.municipality = 10;
            form.lalawigan = "BULACAN";
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

        return {
            filteredBrgys,
            form,
            errors_isf,
            submission_process,
            toggleModal,
            data,
            modal_show,
            isf,
        };
    },
};
</script>

<template>
    <Head title="Informal Settler Families (ISF) and Illegal Encroachments" />
    <BreezeAuthenticatedLayout>
        <template #header>
            Informal Settler Families (ISF) and Illegal Encroachments</template
        >

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
                            type="text"
                            placeholder=""
                        />
                    </div>
                </div>

                <!-- row 2 name and info -->
                <div class="py-1 font-medium text-red-700">
                    1. Personal Information
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
                            type="text"
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
                            type="text"
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
                        <input
                            v-model="isf.street"
                            :class="inputClass"
                            type="text"
                            placeholder="Street"
                        />
                    </div>
                    <div class="w-full md:w-1/4 px-3 py-1">
                        <label
                            class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                        >
                            Barangay
                        </label>
                        <input
                            :class="inputClass"
                            v-model="isf.barangay"
                            type="text"
                            placeholder="Barangay"
                        />
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
                        <input
                            v-model="isf.balik_probinsya"
                            :class="inputClass"
                            type="text"
                            placeholder=""
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
                            type="text"
                            placeholder=""
                        />
                    </div>
                    <div class="w-full md:w-1/4 px-3 py-1">
                        <label
                            class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                        >
                            Tenurial Status
                        </label>
                        <input
                            :class="inputClass"
                            v-model="isf.tenurial_status"
                            type="text"
                            placeholder=""
                        />
                    </div>
                    <div class="w-full md:w-1/4 px-3 py-1">
                        <label
                            class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                        >
                            Zone
                        </label>
                        <input
                            :class="inputClass"
                            v-model="isf.zone"
                            type="text"
                            placeholder=""
                        />
                    </div>
                </div>
                <div class="py-1 font-medium text-red-700">
                    4. Water ways and filing date
                </div>

                <!-- row 1 water -->
                <div class="flex flex-wrap -mx-3">
                    <div class="w-full md:w-1/4 px-3 py-1">
                        <label
                            class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                        >
                            Body of Water Name
                        </label>
                        <input
                            v-model="isf.body_of_water_name"
                            :class="inputClass"
                            type="text"
                            placeholder=""
                        />
                    </div>
                    <div class="w-full md:w-1/4 px-3 py-1">
                        <label
                            class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                        >
                            Body of Water Type
                        </label>
                        <input
                            v-model="isf.body_of_water_type"
                            :class="inputClass"
                            type="text"
                            placeholder=""
                        />
                    </div>
                    <div class="w-full md:w-1/4 px-3 py-1">
                        <label
                            class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                        >
                            Distance to Waterways
                        </label>
                        <input
                            :class="inputClass"
                            v-model="isf.distance_to_waterway"
                            type="text"
                            placeholder=""
                        />
                    </div>
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
