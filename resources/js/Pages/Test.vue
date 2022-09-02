<script>
import BreezeAuthenticatedLayout from "../Layouts/Authenticated.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import useChecklist from "../composables/checklists";
import { ref, computed, onMounted, reactive, watch } from "vue";
import Modal from "../Components/Modals/Modal.vue";
import { useToast } from "vue-toastification";
import Multiselect from "@vueform/multiselect";
import Terms from "../Components/Terms.vue";

export default {
    components: {
        Terms,
        BreezeAuthenticatedLayout,
        Head,
        Link,
        Modal,
        Multiselect,
    },
    data() {
        return {};
    },
    methods: {},
    setup(props) {
        console.log("setup");
        const toast = useToast();
        let modal_show = ref(false);
        let modal_note = ref(false);
        let modal_status = ref(false);

        const form = reactive({
            check0: "",
            check1: "",
            check2: "",
            check3: "",
            check4: "",
            check5: "",
            check6: "",
            check7: "",
            check8: "",
            check9: "",
        });

        const {
            errors_checklist,
            checklists,
            getChecklists,
            updateChecklists,
            updatechecksinglelist,
        } = useChecklist();

        const updateCheck = async (id) => {
            form.id = id;
            toast.info("Send.");
            await updatechecksinglelist({ ...form }).then(() => {
                if (errors_checklist.value) {
                    toast.error("Error.");
                } else {
                    toast.success("Success.");
                }
            });
        };

        onMounted(getChecklists);

        // method
        function togglemodal() {
            modal_show.value = !modal_show.value;
            if (errors.value) {
                modal_status.value = false;
                modal_note.value = "Error.";
            } else {
                modal_status.value = true;
                modal_note.value = "Update successful.";
            }
        }
        watch(
            () => checklists.value,
            (value) => {
                console.log(value[0]["name"]);
                form.check0 = value[0]["name"];
                form.check1 = value[1]["name"];
                form.check2 = value[2]["name"];
                form.check3 = value[3]["name"];
                form.check4 = value[4]["name"];

                form.check5 = value[5]["name"];
                form.check6 = value[6]["name"];
                form.check7 = value[7]["name"];
                form.check8 = value[8]["name"];
                form.check9 = value[9]["name"];
            }
        );
        return {
            errors_checklist,
            checklists,
            form,
            updateCheck,
        };
    },
};
</script>

<style src="@vueform/multiselect/themes/default.css"></style>

<template>
    <Head title="Business" />
    <BreezeAuthenticatedLayout>
        <template #header> Edit an existing Business </template>
        <div class="py-2">
            <Terms />
            <button
                class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                type="button"
                data-modal-toggle="popup-modal"
            >
                Toggle modal
            </button>
            <!-- hidden -->
            <div
                id="popup-modal"
                tabindex="-1"
                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 md:inset-0 h-modal md:h-full"
            >
                <div class="relative p-4 w-full max-w-md h-full md:h-auto">
                    <div
                        class="relative bg-white rounded-lg shadow dark:bg-gray-700"
                    >
                        <button
                            type="button"
                            class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white"
                            data-modal-toggle="popup-modal"
                        >
                            <svg
                                aria-hidden="true"
                                class="w-5 h-5"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"
                                ></path>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                        <div class="p-6 text-center">
                            <svg
                                aria-hidden="true"
                                class="mx-auto mb-4 w-14 h-14 text-gray-400 dark:text-gray-200"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                ></path>
                            </svg>
                            <h3
                                class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400"
                            >
                                Are you sure you want to delete this product?
                            </h3>
                            <button
                                data-modal-toggle="popup-modal"
                                type="button"
                                class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2"
                            >
                                Yes, I'm sure
                            </button>
                            <button
                                data-modal-toggle="popup-modal"
                                type="button"
                                class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600"
                            >
                                No, cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
