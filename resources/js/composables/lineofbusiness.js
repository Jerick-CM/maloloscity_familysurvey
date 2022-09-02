import { ref, reactive } from "vue";
import axios from "axios";

export default function useLineOfBusinesses() {
    const lineofbusinesses = ref([]);
    // const business = ref([]);
    const errors_lob = ref("");
    // const modal = reactive({ show: false, status: true, note: "Saved." });

    const getLineOfBusinesses = async () => {
        let response = await axios.get("/api/lineofbusiness");
        lineofbusinesses.value = response.data.data;
    };
    // const getBusiness = async (id) => {
    //     let response = await axios.get("/api/business/" + id);
    //     business.value = response.data.data;
    // };

    // const storeBusiness = async (data) => {
    //     errors_lob.value = "";
    //     try {
    //         await axios.post("/api/business", data);
    //     } catch (e) {
    //         if (e.response.status === 422) {
    //             errors_lob.value = e.response.data.errors;
    //         }
    //     }
    // };

    // const updateBusiness = async (id) => {
    //     errors_lob.value = "";
    //     try {
    //         await axios
    //             .put("/api/business/" + id, business.value)
    //             .then(() => {
    //                 console.log('done')
    //                 // console.log("test");

    //                 // modal.show.value = true;
    //             });
    //     } catch (e) {
    //         if (e.response.status === 422) {
    //             errors_lob.value = e.response.data.errors;
    //         }
    //     }
    // };

    // const destroyBusiness = async (id) => {
    //     await axios.delete("/api/business/" + id);
    // };

    return {
        lineofbusinesses,
        // business,
        errors_lob,
        getLineOfBusinesses,
        // getBusiness,
        // storeBusiness,
        // updateBusiness,
        // destroyBusiness,
    };
}
