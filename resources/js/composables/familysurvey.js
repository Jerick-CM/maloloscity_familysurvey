import { ref, reactive } from "vue";
import axios from "axios";

export default function useFamilySurveys() {
    const familysurvey = ref([]);
    const familysurveys = ref([]);
    const family_position = ref([]);
    const errors_fs = ref("");

    const individual_lifecycle_risks = ref("");
    const economic_risks = ref("");
    const environment_and_disaster_risks = ref("");
    const social_and_governance_risks = ref("");

    const getFamilySurvey = async (id) => {
        let response = await axios.get("/request/familysurvey/" + id);
        (familysurvey.value = response.data.data),
            (family_position.value = {
                value: response.data.data.family_position,
                label: response.data.data.family_position,
            });

        individual_lifecycle_risks.value =
            response.data.data.individual_lifecycle_risk;
        economic_risks.value = response.data.data.economic_risk;

        environment_and_disaster_risks.value =
            response.data.data.environmental_and_disaster_risk;

        social_and_governance_risks.value =
            response.data.data.social_and_governance_risk;
    };

    const getFamilySurveys = async () => {
        let response = await axios.get("/request/familysurveys");
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
            await axios.post("/request/familysurvey/update/" + id, familysurvey.value);
        } catch (e) {
            if (e.response.status === 422) {
                errors_fs.value = e.response.data.errors;
            }
            if (e.response.status === 500) {
                errors_fs.value = {
                    errors: { error: "server Error 500" },
                };
            }
        }
    };

    const destroyFamilySurvey = async (id) => {
        await axios.post("/request/familysurvey/delete/" + id);
    };

    const loadFromServer = async (
        familysurveys,
        serverItemsLength,
        options,
        params
    ) => {
        errors_fs.value = "";
        try {
            let response = await axios.post("/table/familysurvey/fetch", {
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
        family_position,

        economic_risks,
        environment_and_disaster_risks,
        individual_lifecycle_risks,
        social_and_governance_risks,
    };
}
