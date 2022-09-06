<script>
import { ref, defineEmits } from "vue";
export default {
    props: ["showmodal", "data"],
    setup(props, context) {

        const toggleModal = () => {
            context.emit("toggle");
        };
        return {
            toggleModal,
        };
    },
};
</script>
<template>
    <!-- Main modal -->
    <div
        v-if="showmodal"
        id="defaultModal"
        tabindex="-1"
        class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-full md:h-full justify-center items-center flex bg-fade-black"
        aria-modal="true"
        role="dialog"
    >
        <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div
                    class="flex justify-between items-start p-4 rounded-t dark:border-gray-600 custom-red-gradient bg-red-500"
                >
                    <h3 class="text-xl font-bold text-white bg-red-500">
                        Survey Form
                    </h3>

                    <button
                        @click.prevent="toggleModal"
                        type="button"
                        class="text-white bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="defaultModal"
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
                </div>

                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-2 py-2 px-2">
                        <div>
                            <label class="text-sm font-semibold text-red-900">
                                Survey form completed
                            </label>
                        </div>
                    </div>
                </div>
                <div
                    class="flex items-center p-6 space-x-2 rounded-b border-t border-gray-200 dark:border-gray-600"
                >
                    <button
                        @click.prevent="toggleModal"
                        type="button"
                        class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
.checkmark {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: block;
    stroke-width: 2;
    stroke: green;
    stroke-miterlimit: 10;
    stroke-dashoffset: 0;
    margin: 10% auto;
}
.bg-fade-black {
    background-color: rgba(0, 0, 0, 0.3);
}
</style>
