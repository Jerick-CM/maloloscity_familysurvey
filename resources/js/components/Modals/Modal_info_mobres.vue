<template>
    <div>
        <!-- Modal asdas -->
        <!--  -->
        <!-- class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0
        left-0 z-50 w-full md:inset-0 h-modal md:h-full" -->
        <!-- overflow-y-auto -->
        <div
            v-if="showmodal"
            class="overflow-x-hidden overflow-y-scroll fixed px-5 inset-0 z-50 outline-none focus:outline-none justify-center items-center flex"
        >
            <div
                class="relative flex min-h-screen flex-col justify-center overflow-hidden py-6 sm:py-12"
            >
                <div class="absolute"></div>
                <div
                    class="relative bg-white px-6 pt-10 pb-8 shadow-xl ring-1 ring-gray-900/5 sm:mx-auto sm:max-w-lg sm:rounded-lg sm:px-10"
                >
                    <div class="mx-auto max-w-md">
                        <div class="divide-y">
                            <div>
                                <label class="text-sm text-red-500">
                                    Business Name :
                                </label>
                                <span> {{ info.business_name }}</span>
                            </div>
                            <div>
                                <label class="text-sm text-red-500">
                                    Business Identification Number:
                                </label>
                                <span>
                                    {{ info.business_identification_number }}
                                </span>
                            </div>
                            <div>
                                <label class="text-sm text-red-500">
                                    Address :
                                </label>
                                <span> {{ info.address }}</span>
                            </div>
                            <div>
                                <label class="text-sm text-red-500">
                                    Owner:
                                </label>
                                <span> {{ info.owner }}</span>
                            </div>

                            <div>
                                <label class="text-sm text-red-500">
                                    Line of Business:
                                </label>
                                <span> {{ info.line_of_business }}</span>
                            </div>
                            <div>
                                <label class="text-sm text-red-500">
                                    Contact Number:
                                </label>
                                <span> {{ info.contact_number }}</span>
                            </div>
                            <div>
                                <label class="text-sm text-red-500">
                                    Capital:
                                </label>
                                <span> {{ info.capital }}</span>
                            </div>
                            <div>
                                <label class="text-sm text-red-500">
                                    Business Tax:
                                </label>
                                <span> {{ info.business_tax }}</span>
                            </div>
                            <div>
                                <label class="text-sm text-red-500">
                                    Code :
                                </label>
                                <span> {{ info.code }}</span>
                            </div>
                            <div>
                                <label class="text-sm text-red-500">
                                    Gross Receipts :
                                </label>
                                <span> {{ info.gross_receipts }}</span>
                            </div>
                            <div>
                                <label class="text-sm text-red-500">
                                    Surcharge :
                                </label>
                                <span> {{ info.surcharge }}</span>
                            </div>
                            <div>
                                <label class="text-sm text-red-500">
                                    Total:
                                </label>
                                <span> {{ info.total }}</span>
                            </div>
                            <div>
                                <label class="text-sm text-red-500">
                                    O.R No.:
                                </label>
                                <span> {{ info.official_receipt_number }}</span>
                            </div>
                            <div>
                                <label class="text-sm text-red-500">
                                    O.R. Date :
                                </label>
                                <span> {{ info.official_receipt_date }}</span>
                            </div>

                            <div class="pt-2 text-base font-semibold leading-7">
                                <button
                                    class="background-transparent font-bold uppercase px-6 py-2 text-xs outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
                                    type="button"
                                    v-on:click="toggleModal()"
                                >
                                    <slot name="btn-ok">OK</slot>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            v-if="showmodal"
            class="opacity-25 fixed inset-0 z-40 bg-black"
        ></div>
        <!-- Modal -->
    </div>
</template>

<script>
export default {
    props: ["showmodal", "statusmodal", "info"],
    data: () => ({
        successClass:
            "border-green-200 rounded-t rounded-t mb-0 px-4 py-3 border-0 bg-green-600",
        errorClass:
            "border-red-200 rounded-t rounded-t mb-0 px-4 py-3 border-0 bg-red-600",
        defaultClass:
            "border-slate-200 rounded-t rounded-t mb-0 px-4 py-3 border-0 bg-slate-600",
    }),
    methods: {
        toggleModal() {
            this.$emit("toggleModal");
        },
    },
};
</script>
