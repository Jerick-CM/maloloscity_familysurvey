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
            <div class="relative my-6">
                <div
                    class="border-0 rounded-lg shadow-lg relative flex flex-col bg-white outline-none focus:outline-none"
                >
                    <div
                        :class="
                            statusmodal === true ? defaultClass : errorClass
                        "
                        class="flex items-start justify-between p-5 border-b border-solid"
                    >
                        <h3 class="text-xl font-semibold text-white">
                            <slot name="title"></slot>
                        </h3>
                        <button
                            class="p-1 ml-auto bg-transparent border-0 text-black opacity-5 float-right text-3xl leading-none font-semibold outline-none focus:outline-none"
                            v-on:click="toggleModal()"
                        >
                            <span
                                class="bg-transparent text-black opacity-5 h-6 w-6 text-2xl block outline-none focus:outline-none"
                            >
                                ×
                            </span>
                        </button>
                    </div>
                    <div class="relative px-10 py-6 flex-auto">
                        <div class="grid grid-cols-1 divide-y">
                            <div class="p-1">
                                <label class="text-red-500 text-red text-sm">
                                    Business Name :</label
                                >
                                {{ info.business_name }}
                            </div>
                            <div class="p-1">
                                <div class="grid grid-cols-6 gap-6">
                                    <div class="col-span-6 sm:col-span-3">
                                        <div class="flex">
                                            <label
                                                class="text-red-500 text-red text-sm"
                                            >
                                                Business Identification
                                                Number:</label
                                            ><br />
                                            {{
                                                info.business_identification_number
                                            }}
                                        </div>
                                    </div>
                                    <div class="col-span-6 sm:col-span-3">
                                        <div class="flex">
                                            <label
                                                class="text-red-500 text-red text-sm"
                                            >
                                                Address : </label
                                            ><br />
                                            {{ info.address }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="p-1">
                                <div class="grid grid-cols-6 gap-6">
                                    <div class="col-span-6 sm:col-span-3">
                                        <div class="flex">
                                            <label
                                                class="text-red-500 text-red text-sm"
                                            >
                                                Owner</label
                                            ><br />
                                            {{ info.owner }}
                                        </div>
                                    </div>
                                    <div class="col-span-6 sm:col-span-3">
                                        <div class="flex">
                                            <label
                                                class="text-red-500 text-red text-sm"
                                            >
                                                Line of Business:</label
                                            ><br />

                                            {{ info.line_of_business }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-1">
                                <div class="grid grid-cols-6 gap-6">
                                    <div
                                        class="col-span-6 sm:col-span-3 lg:col-span-2"
                                    >
                                        <label
                                            class="text-red-500 text-red text-sm"
                                        >
                                            Contact Number:
                                        </label>

                                        {{ info.contact_number }}
                                    </div>
                                    <div
                                        class="col-span-6 sm:col-span-3 lg:col-span-2"
                                    >
                                        <label
                                            class="text-red-500 text-red text-sm"
                                        >
                                            Capital:
                                        </label>
                                        {{ info.capital }}
                                    </div>
                                    <div
                                        class="col-span-6 sm:col-span-3 lg:col-span-2"
                                    >
                                        <label
                                            class="text-red-500 text-red text-sm"
                                        >
                                            Business Tax:
                                        </label>
                                        {{ info.business_tax }}
                                    </div>
                                </div>
                            </div>
                            <div class="p-1">
                                <div class="grid grid-cols-6 gap-6">
                                    <div
                                        class="col-span-6 sm:col-span-3 lg:col-span-2"
                                    >
                                        <label
                                            class="text-red-500 text-red text-sm"
                                        >
                                            code:</label
                                        >
                                        {{ info.code }}
                                    </div>
                                    <div
                                        class="col-span-6 sm:col-span-3 lg:col-span-2"
                                    >
                                        <label
                                            class="text-red-500 text-red text-sm"
                                        >
                                            Gross Receipts:</label
                                        >
                                        {{ info.gross_receipts }}
                                    </div>
                                    <div
                                        class="col-span-6 sm:col-span-3 lg:col-span-2"
                                    >
                                        <label
                                            class="text-red-500 text-red text-sm"
                                        >
                                            Surcharge:
                                        </label>
                                        {{ info.surcharge }}
                                    </div>
                                </div>
                            </div>
                            <div class="p-1">
                                <div class="grid grid-cols-6 gap-6">
                                    <div
                                        class="col-span-6 sm:col-span-3 lg:col-span-2"
                                    >
                                        <label
                                            class="text-red-500 text-red text-sm"
                                        >
                                            Total:
                                        </label>
                                        {{ info.total }}
                                    </div>
                                    <div
                                        class="col-span-6 sm:col-span-3 lg:col-span-2"
                                    >
                                        <label
                                            class="text-red-500 text-red text-sm"
                                        >
                                            Official Receipt Number:
                                        </label>

                                        {{ info.official_receipt_number }}
                                    </div>
                                    <div
                                        class="col-span-6 sm:col-span-3 lg:col-span-2"
                                    >
                                        <label
                                            class="text-red-500 text-red text-sm"
                                        >
                                            Official Receipt Date:
                                        </label>

                                        {{ info.official_receipt_date }}
                                    </div>
                                </div>
                            </div>

                            <div class="p-1">
                                <div class="grid grid-cols-6 gap-6">
                                    <div
                                        class="col-span-6 sm:col-span-3 lg:col-span-2"
                                    >
                                        <label
                                            class="text-red-500 text-red text-sm"
                                        >
                                            Terms:
                                        </label>
                                        {{ info.terms }}
                                    </div>
                                    <div
                                        class="col-span-6 sm:col-span-3 lg:col-span-2"
                                    >
                                        <label
                                            class="text-red-500 text-red text-sm"
                                        >
                                            Tax Year:
                                        </label>
                                        {{ info.tax_year }}
                                    </div>
                                    <div
                                        class="col-span-6 sm:col-span-3 lg:col-span-2"
                                    >
                                        <label
                                            class="text-red-500 text-red text-sm"
                                        >
                                            QTR Paid:
                                        </label>
                                        {{ info.qtr_paid }}
                                    </div>
                                </div>
                            </div>

                            <div class="p-1">
                                <div class="grid grid-cols-6 gap-6">
                                    <div
                                        class="col-span-6 sm:col-span-3 lg:col-span-2"
                                    >
                                        <label
                                            class="text-red-500 text-red text-sm"
                                        >
                                            Number of Employees:
                                        </label>
                                        {{ info.number_of_employees }}
                                    </div>
                                    <div
                                        class="col-span-6 sm:col-span-3 lg:col-span-2"
                                    >
                                        <label
                                            class="text-red-500 text-red text-sm"
                                        >
                                            Status:
                                        </label>
                                        {{ info.status }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="flex items-center justify-end px-6 py-2 border-t border-solid border-slate-200 rounded-b"
                    >
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

        <!-- <div
            v-if="showmodal"
            class="opacity-25 fixed inset-0 z-40 bg-black"
        ></div> -->
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
