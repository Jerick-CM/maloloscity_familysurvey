import { ref, reactive } from "vue";
import axios from "axios";

export default function useFamilySurveys() {
    const familysurvey = ref([]);
    const familysurveys = ref([]);
    const errors_fs = ref("");

    const getFamilySurvey = async () => {
        let response = await axios.get("/request/familysurvey");
        familysurvey.value = response.data.data;
    };

    const getFamilySurveys = async (id) => {
        let response = await axios.get("/request/familysurveys" + id);
        familysurveys.value = response.data.data;
    };

    const storeFamilySurvey = async (data) => {
        errors_fs.value = "";
        try {
            await axios.post("/request/familysurvey", data);
        } catch (e) {
            if (e.response.status === 422) {
                errors_fs.value = e.response.data.errors;
            }
            if (e.response.status === 500) {
                errors_fs.value = {
                    // message: "error 500",
                    errors: { error: "server Error 500" },
                };
            }
        }
    };

    const updateFamilySurvey = async (id) => {
        errors_fs.value = "";
        try {
            await axios
                .put("/request/familysurvey/" + id, familysurvey.value)
                .then(() => {});
        } catch (e) {
            if (e.response.status === 422) {
                errors_fs.value = e.response.data.errors;
            }
        }
    };

    const destroyFamilySurvey = async (id) => {
        await axios.delete("/request/familysurvey/" + id);
    };

    const loadFromServer = async (
        familysurveys,
        serverItemsLength,
        options,
        params
    ) => {
        errors_fs.value = "";
        try {
            let response = await axios.post("/request/familysurvey/fetch/", {
                options: options.value,
                params: params,
            });

            familysurveys.value = response.data.data;
            serverItemsLength.value = response.data.totalRecords;
        } catch (e) {
            if (e.response.status === 422) {
                errors_fs.value = e.response.data.errors;
            }
        }
    };

    return {
        errors_fs,
        familysurvey,
        familysurveys,
        getFamilySurvey,
        destroyFamilySurvey,
        updateFamilySurvey,
        storeFamilySurvey,
        getFamilySurveys,
        getFamilySurvey,
        loadFromServer,
    };
}
