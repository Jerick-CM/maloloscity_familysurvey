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

        const fetchSelectfield = async (query, field) => {
            let data;
            await axios
                .post("/request/familysurvey/getSelectfield", {
                    searchValue: query,
                    field: field,
                    ISF,
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
    <Head title="ISF and Illegal Encroachments" />
    <BreezeAuthenticatedLayout>
        <template #header> ISF and Illegal Encroachments </template>

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

            <div class="my-3 bg-white rounded p-5 sm:p1">
                <div class="py-1 font-semibold">I. Pagkakakilanlan</div>

                <div class="py-1 font-medium text-red-700">1. Pangalan</div>

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
                            v-model="isf.body_of_water_type"
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
                            v-model="isf.distance_to_waterway"
                            type="text"
                            placeholder="Last Name"
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
