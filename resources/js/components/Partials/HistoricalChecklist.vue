<script>
import { ref } from "vue";
export default {
    props: ["pull_loading", "historical_checklist"],
    setup(props, { attrs, slots, emit, expose }) {
        const div_infoholder = ref(false);
        const showInfo = async () => {
            div_infoholder.value = !div_infoholder.value;
        };
        return { showInfo, div_infoholder };
    },
};
</script>
<template>
    <div>
        <div
            @click="showInfo()"
            class="lg:hidden xl:hidden 2xl:hidden cursor-pointer w-full mr-3 md:mb-0 text-white bg-red-500 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium text-sm px-4 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
        >
            <div class="flex-none">Show historical checklist</div>
            <div class="grow justify-left content-start"></div>
            <div class="place-content-center">
                <svg
                    v-if="!div_infoholder"
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 5l7 7-7 7"
                    />
                </svg>
                <svg
                    v-if="div_infoholder"
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M19 9l-7 7-7-7"
                    />
                </svg>
            </div>
        </div>
        <!--  -->
        <div
            class="py-4 text-red-500 hidden xl:block 2xl:block xl:block"
            @click="showInfo()"
        >
            <div class="flex flex-items">
                <div>Business History</div>

                <div class="place-content-center">
                    <svg
                        v-if="!div_infoholder"
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 5l7 7-7 7"
                        />
                    </svg>
                    <svg
                        v-if="div_infoholder"
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M19 9l-7 7-7-7"
                        />
                    </svg>
                </div>
            </div>
        </div>
        <div v-if="div_infoholder">
            <div v-if="!pull_loading">
                <svg
                    aria-hidden="true"
                    class="mr-2 w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
                    viewBox="0 0 100 101"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                        fill="currentColor"
                    />
                    <path
                        d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                        fill="currentFill"
                    />
                </svg>
                <span class="">Loading...</span>
            </div>

            <div v-if="pull_loading">
                <div class="px-4 py-1" v-if="!historical_checklist.length">
                    <div class="px-4 py-4 backdrop-brightness-50 bg-white/80">
                        History Page
                    </div>
                    <div class="px-4 py-4">No business history</div>
                </div>
                <div class="back px-1 py-1" v-if="historical_checklist.length">
                    <div class="py-4 backdrop-brightness-50 bg-white/80">
                        <label for="" class="pl-3"> History Page</label>
                    </div>
                    <div
                        class="my-2 py-2 px-1 backdrop-brightness-50 bg-white/80"
                        v-for="(list, idx) in historical_checklist"
                        :key="idx"
                    >
                        <div class="grid grid-cols-1 divide-y">
                            <div class="px-2 py-2">
                                <div class="py-2">
                                    <label class="font-medium text-red-800">
                                        Date:
                                    </label>
                                    {{ list.createddate }}
                                </div>
                                <div class="py-2">
                                    <label for="">Check List and Remarks</label>
                                </div>
                            </div>
                        </div>
                        <div
                            class="flex items-center mb-4"
                            v-for="(checklist, chxidx) in list.checklistdata"
                            :key="chxidx"
                        >
                            <input
                                disabled
                                :checked="checklist.checkbox"
                                type="checkbox"
                                value=""
                                class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                            />
                            <label
                                for="default-checkbox"
                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                            >
                                {{ checklist.label }}
                            </label>
                        </div>
                        <div>
                            <label
                                for="about"
                                class="block text-sm font-medium text-red-800 py-2"
                            >
                                Remarks:
                            </label>

                            <div class="mt-1">
                                <textarea
                                    disabled
                                    name="about"
                                    rows="3"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"
                                    placeholder="Description"
                                    >{{ list.remarks }}</textarea
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
