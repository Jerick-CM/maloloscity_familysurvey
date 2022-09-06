<script>
import { ref, defineEmits } from "vue";
export default {
    props: ["closeModal"],
    setup(props,context) {

        const toggleModal = () => {

            context.emit("toggle","hello");
            //     closeModal.value = false
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
        v-if="closeModal"
        id="defaultModal"
        tabindex="-1"
        class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-full md:h-full justify-center items-center flex bg-fade-black"
        aria-modal="true"
        role="dialog"
    >
        <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div
                    class="flex justify-between items-start p-4 rounded-t dark:border-gray-600 custom-red-gradient"
                >
                    <h3 class="text-xl font-bold text-white">
                        Data Privacy Act of 2012 (R.A 10173)
                    </h3>
                    <!-- @click.prevent="toggleWarning" -->
                    <button
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
                <!-- Modal body -->
                <div class="p-6 space-y-6">
                    <p
                        class="text-base leading-relaxed text-gray-500 dark:text-gray-400"
                    >
                        Alinsunod sa Data Privacy Act of 2012 (R.A 10173), ang
                        lahat ng impormasyong ilalahad at ilalagay mo rito ay
                        ituturing na lubos na kumpidensyal at may matikas na
                        seguridad, at tanging ang mga awtorisadong opisyales
                        lamang ng Pamahalaang Panlungsod ng Malolos ang maaaring
                        mangasiwa at magbukas ng mga datos na nasasaad dito.
                        Kung kaya naman, sa diwang ito, kinikilala rin ng
                        Pamahalaang Panlungsod ng Malolos ang kanilang mga
                        responsibilidad at obligasyon sa ilalim ng nabanggit na
                        batas.
                    </p>
                    <p
                        class="text-base leading-relaxed text-gray-500 dark:text-gray-400 flex"
                    >
                        <span class="mr-3"
                            ><svg
                                class="checkmark"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 40 40"
                            >
                                <path
                                    class="checkmark__check"
                                    fill="none"
                                    d="M14.1 27.2l7.1 7.2 16.7-16.8"
                                /></svg
                        ></span>
                        Binabasa at sinasang-ayunan ko ang mga tuntunin at
                        polisiyang nakasaad dito.
                    </p>
                    <p
                        class="text-base leading-relaxed text-gray-500 dark:text-gray-400 flex"
                    >
                        <span class="mr-3"
                            ><svg
                                class="checkmark"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 40 40"
                            >
                                <path
                                    class="checkmark__check"
                                    fill="none"
                                    d="M14.1 27.2l7.1 7.2 16.7-16.8"
                                /></svg
                        ></span>
                        Binibigyan ko ng aking lehitimong pahintulot ang
                        Pamahalaang Panlungsod ng Malolos na buksan at suriin
                        ang mga personal na impormasyong aking ilalakip sa
                        online form na ito.
                    </p>
                    <p
                        class="text-base leading-relaxed text-gray-500 dark:text-gray-400 flex"
                    >
                        <span class="mr-3"
                            ><svg
                                class="checkmark"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 40 40"
                            >
                                <path
                                    class="checkmark__check"
                                    fill="none"
                                    d="M14.1 27.2l7.1 7.2 16.7-16.8"
                                /></svg
                        ></span>
                        Binibigyan ko ng aking lehitimong pahintulot ang
                        Pamahalaang Panlungsod ng Malolos na buksan at suriin
                        ang mga personal na impormasyong aking ilalakip sa
                        online form na ito.
                    </p>

                    <p
                        class="text-base leading-relaxed text-gray-500 dark:text-gray-400 flex"
                    >
                        <span class="mr-3"
                            ><svg
                                class="checkmark"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 40 40"
                            >
                                <path
                                    class="checkmark__check"
                                    fill="none"
                                    d="M14.1 27.2l7.1 7.2 16.7-16.8"
                                /></svg
                        ></span>
                        Binibigyan ko ng aking lehitimong pahintulot ang
                        Pamahalaang Panlungsod ng Malolos na buksan at suriin
                        ang mga personal na impormasyong aking ilalakip sa
                        online form na ito.
                    </p>
                </div>

                <p v-if="disagree" class="text-red-500 text-sm px-6 pb-6">
                    Piliin ang sumang-ayon upang makapagpatuloy sa aplikasyon.
                </p>
                <!-- Modal footer -->
                <div
                    class="flex items-center p-6 space-x-2 rounded-b border-t border-gray-200 dark:border-gray-600"
                >
                    <button
                        @click.prevent="toggleModal"
                        type="button"
                        class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
                    >
                        Sumang-ayon
                    </button>
                    <!-- @click.prevent="toggleWarning" -->
                    <button
                        type="button"
                        class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600"
                    >
                        Tumanggi
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
