<template>
    <div>
        <!-- Modal -->
        <div
            v-if="showmodal"
            class="overflow-x-hidden overflow-y-auto fixed px-5 inset-0 z-50 outline-none focus:outline-none justify-center items-center flex"
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
                        <p class="my-4 text-slate-500 text-md leading-relaxed">
                            {{ note }}
                        </p>
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
        <div
            v-if="showmodal"
            class="opacity-25 fixed inset-0 z-40 bg-black"
        ></div>
        <!-- Modal -->
    </div>
</template>

<script>
export default {
    props: ["showmodal", "statusmodal", "note"],
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
