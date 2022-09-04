<script>
import BreezeAuthenticatedLayout from "./../../Layouts/Form.vue";
import Breadcrumb from "./../../Components/BreadCrumb/navSurvey.vue";

import { Head, Link, usePage } from "@inertiajs/inertia-vue3";
import { ref, reactive, computed, onMounted, watch } from "vue";

import { useToast } from "vue-toastification";

export default {
    components: {
        BreezeAuthenticatedLayout,
        Head,
        Link,
        Breadcrumb,
    },
    setup(props, { attrs, slots, emit, expose }) {
        console.log("setup");
        const title = ref[""];
        return { title };
    },
};
</script>
<template>
    <Head title="Survey Form Entry" />
    <BreezeAuthenticatedLayout>
        <template #header>
            Department of Social Welfare and Development
            <p>FAMILY SURVEY ON RISKS AND VULNERABILITY</p>
        </template>
        <div class="w-full mx-auto sm:px-6 lg:px-8">
            <Breadcrumb />

            <div class="my-1 bg-white rounded p-5 sm:p1">
                <div>I.Pagkakakilanlan</div>
                <div>1. Pangalan</div>
                <div>2. Posisyon sa Pamilya</div>
                <div>3. BIlang ng Anak</div>
                <div>4. BIlang ng pamilya sa bahay</div>
                <div>5. 4P's Beneficiary</div>
                <div>Kung OO Kailang pa</div>
            </div>
            <div class="my-1 bg-white rounded p-5 sm:p1">
                <table
                    class="w-full border-collapse border border-slate-500 table-fixed"
                >
                    <thead>
                        <tr>
                            <td rowspan="3" width="70%">
                                II. MGA PANGANIB (RISK) AT VULNERABILITY NA
                                KINAHARAP NG PAMILYA
                            </td>
                            <td rowspan="3">Lagyan ng ng (x) kung wala</td>
                            <td colspan="2">Kung meron /0o, ilan?</td>
                        </tr>
                        <tr>
                            <td rowspan="2">Ngayong taon</td>
                            <td rowspan="2">Nakalipas na taon</td>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- table2 30 -->
                        <!-- INDIVIDUAL LIFE CYCLE RISKS  -->
                        <tr>
                            <td>INDIVIDUAL LIFE CYCLE RISKS</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>1. Pagbubuntis at panganganak</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>2. Hindi nakapagpre-natal check up</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>3. Pagkalagas (abortion)</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>4. Nakunan</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                5. Pagkawala ng anak na may kapansanan ng
                                isinilang
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>6. Hindi nakapagpost-natal check up</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>

                        <tr>
                            <td>6. Hindi nakapagpost-natal check up</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>

                        <tr>
                            <td>
                                7. Pagkakaroon ng anak na may kapansanan ng
                                isinilang
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>

                        <tr>
                            <td>8. Di nabakunahan ang mga anak o bata</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>

                        <tr>
                            <td>
                                9. Miyembro ng pamilya na may malubhang
                                kapansanan dahil sa sakit o aksidente
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>10. Namatay dahils sa sakuna o aksidente</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>

                        <tr>
                            <td>
                                11. Miyembro ng pamilya na may malubha o matagal
                                nang sakit?
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                12. Di nakatanggap ng serbisyong medikal sa
                                health center o ospital?
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>13. Di nakabili ng kinakailangang gamot"</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                14. Miyembro ng pamilya na pumanaw dahil sa
                                karamdaman
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                15. Di nai-enrol sa day care o pre-school ang
                                mga anak
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>16. Di nai-enrol sa elemtarya ang anak</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>

                        <tr>
                            <td>17. Di nai-enrol sa high school ang anak</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>

                        <tr>
                            <td>
                                18. Di nai-enrol o nahinto sa kolehiyo ang anak
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>

                        <tr>
                            <td>
                                19. Tumigil sa pag-aaral o nagdrop out ng
                                elementarya o high school ang anak
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>

                        <tr>
                            <td>20. Nalulong sa bawal na gamot o alak</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                21. Nabuntis na menor de edad? (below 18 years
                                old)
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>

                        <tr>
                            <td>
                                22. Naglayas o hindi umuwi ng bahay ng walang
                                paalam sa magulang
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>23. Miyembro na nangibang bansa</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                24. Naghahanapbuhay sa ibang munisipyo o siyudad
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>25. Hiwalay sa asawa</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>26. Namatayan ng asawa</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                27. Di sapat ang pagkain ng pamilya o naranasan
                                ang kawalan ng pagkain
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>

                        <tr>
                            <td>28. kakulangan ng malinis na inuming tubig</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>

                        <tr>
                            <td>
                                29. Bahay ay gawa sa light /salvaged material
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>

                        <tr>
                            <td>30. Kawalan ng malinis na CR o palikuran</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>

                        <!-- table 2 - 30  -->
                        <tr>
                            <td>ECONOMIC RISK</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>31.  Walang hanapbuhay</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>32.  Hanapbuhay na di akma sa tinapos na kurso</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>33.  Naranasang maaksidente habang nasa trabaho</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>34.  Di nakabayad ng utang</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>35.  Biktima ng scam</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>36.  Biktima ng illegal recruiter</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>37.  Lumipat ng tirahan sa ibang munisipyo</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>38.  Walang life insurance</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>39.  Walang health insurance</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>40.  Naghahanapbuhay pero walang SSS o GSIS</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>41.  Pagkalugi sa negosyo</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>42.  Pagsangla ng ari-arian</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>43.  Paghiram ng pera sa kamag-anak</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>44.  Kapamilya na nakakatanda na di nakkatanggap ng pension</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <!-- economic risk 14 rows -->
                        <tr>
                            <td>ENVIRONMENT AND DISASTER RISKS</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>45.  Biktima ng sunog</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>46.  Biktima ng bagyo o tagtuyot</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>47.  Biktima ng lindol o pagputok ng bulkan</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>48.  Nawalan ng miyembro ng pamilya sanhi ng kalamidad</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>49.  Demolisyon</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>50.  Tumira sa relocation o resettlement</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <!-- environmental risk 6 rows -->
                        <!-- social and governance risk 24 rows -->
                        <tr>
                            <td>SOCIAL AND GOVERNANCE RISKS</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>51.  Biktima ng pagnanakaw sa bahay</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>52.  Nakarans ng pagnanakaw o hold-up sa daan</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>53.  Nakaranas ng sekswal na karahasan</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>54.  May alam po ba kayong "gang" ng kabataan sa barangay</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>55.  Namatayan ng kapamilya dahil sa krimen</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>56 Nakakita ng aktuwal na krimen na naganap sa komunidad</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>57.  Karahasan kaugnay sa eleksyon</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>58.  Takot at panggugulo dahil sa pulitika </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>59.  Pag-aalsa laban sa pamahalaan</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>

                        <tr>
                            <td>60.  Pangugulo ng militar</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>61. Karahasan sa tahanan (pananakit ng asawa, anak etc.</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>62.  Pang-aabuso sa menor de edad</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>63.  Pagtatrabaho ng menor de edad</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>64.  Katiwalian sa pamahalaan (corruption, bribery, embezzlement etc)</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>65.  Di nakikilahok sa aktibidades ng komunidad</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>66.  Di bumoboto</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>67.  Di kasapi ng anumang organisasyon</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>68.  Pag-angkin ng lupa ng iba</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>69.  Tradisyon na nakakasama sa tao.</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>

                        <tr>
                            <td>70.  Kaguluhan o di pagkakaunawaan dahil sa relihiyon</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>71.  Nakaranas ng mabagal na judicial system</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>72.  Diskriminasyon dahil sa pinagmulan o kasarian (ethnicity, gender, etc)</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>73.  Alitan o tunggali dahil sa pagsasalungat ng tribu</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>74.  Ibang panganib na kinakaharap ng mag-anak</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
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
</style>
