import { ref, reactive } from "vue";
import axios from "axios";

export default function useItineraries() {
    const itineraries = ref([]);
    const itinerary = ref([]);
    const selectedbusinesses = reactive({});
    const selectedbusinesses1 = ref(null);
    const selectedbusinesses_reA = reactive({});
    const selectedbusinesses_ref = ref([]);
    const checklists = ref(null);
    const checklists_values = ref(null);
    const assigned_id = ref(null);
    const errors_I = ref("");
    const strategicbusiness = ref([]);
    const business = ref([]);
    const historical_checklist = ref([]);
    const itinerary_id = ref([false]);

    const checklistdata = ref([]);

    const loadFromServer = async (
        userId,
        itineraries,
        serverItemsLength,
        options,
        params
    ) => {
        errors_I.value = "";
        try {
            let response = await axios.post(
                "/cstm/itineraries/fetch/" + userId,
                {
                    options: options.value,
                    params: params,
                }
            );

            itineraries.value = response.data.data;
            serverItemsLength.value = response.data.totalRecords;
        } catch (e) {
            if (e.response.status === 422) {
                errors_I.value = e.response.data.errors;
            }
        }
    };

    const getItineraries = async () => {
        let response = await axios.get("/api/itineraries");
        itineraries.value = response.data.data;
    };

    const getItinerary = async (id) => {
        let response = await axios.get("/api/itineraries/" + id);
        itinerary.value = response.data.data;
        selectedbusinesses.data = response.data.data.businessnames;
        selectedbusinesses1.value = response.data.data.businessnames;
        selectedbusinesses_reA.data = response.data.data.businessnames;
        assigned_id.value = response.data.data.assign_id;
    };

    const storeItinerary = async (data) => {
        errors_I.value = "";
        try {
            let response = await axios.post("/cstm/itineraries", data, {
                withCredentials: true,
            });
            itinerary_id.value = response.data.id;
        } catch (e) {
            if (e.response.status === 422) {
                errors_I.value = e.response.data.errors;
            }
        }
    };

    const updateItinerary = async (id) => {
        errors_I.value = "";
        try {
            await axios.put("/api/itineraries/" + id, itinerary.value);
        } catch (e) {
            if (e.response.status === 422) {
                errors_I.value = e.response.data.errors;
            }
        }
    };

    const destroyItinerary = async (id) => {
        await axios.delete("/api/itineraries/" + id);
    };

    const destroyItinerary_logs = async (id, user_id) => {
        await axios.delete("/api/cstm/itinerary/" + id + "/" + user_id, {
            withCredentials: true,
        });
    };

    const getBusiness = async (id) => {
        let response = await axios.get("/api/business/" + id);
        business.value = response.data.data;
    };

    /* Custom */

    const getHistoricalBusinessData = async (
        bussiness_id,
        itinerary_business_id
    ) => {
        let response = await axios.get(
            "/api/cstm/itineraries/gethistoricalbusiness/" +
                itinerary_business_id +
                "/" +
                bussiness_id
        );
        historical_checklist.value = response.data.data;
    };

    const getChecklistsBusiness = async (buss_id, itin_id) => {
        let response = await axios.get(
            "/api/cstm/itineraries/checkvalues/" + buss_id + "/" + itin_id
        );
        checklists_values.value = response.data.data;
    };

    const handleStartTime = async (data) => {
        errors_I.value = "";
        try {
            await axios.post("/api/cstm/itineraries/start", data);
        } catch (e) {
            if (e.response.status === 422) {
                errors_I.value = e.response.data.errors;
            }
        }
    };

    const handleFinishTime = async (data) => {
        errors_I.value = "";
        try {
            await axios.post("/api/cstm/itineraries/finish", data);
        } catch (e) {
            if (e.response.status === 422) {
                errors_I.value = e.response.data.errors;
            }
        }
    };

    const getItineraryPull = async (id) => {
        let response = await axios.get("/api/cstm/itineraries/pull/" + id);
        selectedbusinesses_ref.value = response.data.data;
    };

    const getChecklists = async () => {
        let response = await axios.get(
            "/api/cstm/itineraries/checklists/checklist"
        );
        checklists.value = response.data.data;
    };

    const getItinerariesByUser = async (userId) => {
        let response = await axios.get("/api/cstm/itineraries/user/" + userId);
        itineraries.value = response.data.data;
    };

    const complete_ItineraryBusiness = async (data) => {
        errors_I.value = "";
        try {
            await axios.post("/api/cstm/itineraries/completebusiness", data);
        } catch (e) {
            if (e.response.status === 422) {
                errors_I.value = e.response.data.errors;
            }
        }
    };

    const searchItineraries = async (data) => {
        errors_I.value = "";
        try {
            let response = await axios.post(
                "/api/cstm/itineraries/search",
                data
            );
            itineraries.value = response.data.data;
        } catch (e) {
            if (e.response.status === 422) {
                errors_I.value = e.response.data.errors;
            }
        }
    };

    const getStrategicBusinesses = async (location) => {
        let response = await axios.get(
            "/api/cstm/business/location/" + location
        );
        strategicbusiness.value = response.data.data;
    };

    const Add_Business = async (data) => {
        errors_I.value = "";
        try {
            await axios.post("/cstm/itineraries/add_business", data);
        } catch (e) {
            if (e.response.status === 422) {
                errors_I.value = e.response.data.errors;
            }
        }
    };

    const destroyItineraryBusiness = async (data) => {
        errors_I.value = "";
        try {
            await axios.post("/api/cstm/itineraries/itin_busi", data);
        } catch (e) {
            if (e.response.status === 422) {
                errors_I.value = e.response.data.errors;
            }
        }
    };

    const exportItineraryData = async () => {
        await axios
            .post("/api/cstm/itinerary/export", {}, { responseType: "blob" })
            .then((response) => {
                var fileURL = window.URL.createObjectURL(
                    new Blob([response.data])
                );
                var fileLink = document.createElement("a");
                fileLink.href = fileURL;
                fileLink.setAttribute(
                    "download",
                    "itinerary-" +
                        new Date().toJSON().slice(0, 10).replace(/-/g, "_") +
                        ".xls"
                );
                document.body.appendChild(fileLink);
                fileLink.click();
            });
    };

    const exportItineraryBusinessData = async () => {
        await axios
            .post("/api/cstm/ib/export", {}, { responseType: "blob" })
            .then((response) => {
                var fileURL = window.URL.createObjectURL(
                    new Blob([response.data])
                );
                var fileLink = document.createElement("a");
                fileLink.href = fileURL;
                fileLink.setAttribute(
                    "download",
                    "itinerary-business-" +
                        new Date().toJSON().slice(0, 10).replace(/-/g, "_") +
                        ".xls"
                );
                document.body.appendChild(fileLink);
                fileLink.click();
            });
    };

    const exportItineraryPullData = async (items) => {
        await axios
            .post(
                "/api/cstm/itinerary/export_selected",
                { items: items },
                { responseType: "blob" }
            )
            .then((response) => {
                var fileURL = window.URL.createObjectURL(
                    new Blob([response.data])
                );
                var fileLink = document.createElement("a");
                fileLink.href = fileURL;
                fileLink.setAttribute(
                    "download",
                    "itinerary-business-checklist" +
                        new Date().toJSON().slice(0, 10).replace(/-/g, "_") +
                        ".xls"
                );
                document.body.appendChild(fileLink);
                fileLink.click();

                toast.success("Exported Successfully!", {
                    timeout: 2000,
                });
            });
    };
    const validateStartChecklistsBusiness = async (buss_id, itin_id) => {
        let response = await axios.get(
            "/api/cstm/itineraries/validate_start/" + buss_id + "/" + itin_id
        );
    };

    const validateStartBusiness = async (
        bussiness_id,
        itinerary_business_id
    ) => {
        let response = await axios.get(
            "/api/cstm/itineraries/business_start/" +
                itinerary_business_id +
                "/" +
                bussiness_id
        );
        // historical_checklist.value = response.data.data;
    };

    const getChecklistData = async (itinerary_business_id) => {
        let response = await axios.get(
            "/api/cstm/getchecklistdata/" + itinerary_business_id
        );

        checklistdata.value = response.data.data;
    };
    const complete_ItineraryBusiness_new = async (data) => {
        errors_I.value = "";
        try {
            await axios.post(
                "/api/cstm/itineraries/completebusiness_checklist",
                data
            );
        } catch (e) {
            if (e.response.status === 422) {
                errors_I.value = e.response.data.errors;
            }
        }
    };

    const getBussinessHistory = async (bussiness_id, itinerary_business_id) => {
        let response = await axios.get(
            "/api/cstm/itineraries/getbusinesshistory/" +
                itinerary_business_id +
                "/" +
                bussiness_id
        );
        historical_checklist.value = response.data.data;
    };

    const exportItinerarySelected = async (items) => {
        await axios
            .post(
                "/api/cstm/itinerary/export_selected_with_business",
                { items: items },
                { responseType: "blob" }
            )
            .then((response) => {
                var fileURL = window.URL.createObjectURL(
                    new Blob([response.data])
                );
                var fileLink = document.createElement("a");
                fileLink.href = fileURL;
                fileLink.setAttribute(
                    "download",
                    "itinerary-business-list" +
                        new Date().toJSON().slice(0, 10).replace(/-/g, "_") +
                        ".xls"
                );
                document.body.appendChild(fileLink);
                fileLink.click();

                toast.success("Exported Successfully!", {
                    timeout: 2000,
                });
            });
    };

    const destroyItinerarySelected = async (items, user_id) => {
        errors_I.value = "";
        try {
            await axios.post("/api/cstm/itineraries/delete_selected", {
                items: items,
                user_id: user_id,
            });
        } catch (e) {
            if (e.response.status === 422) {
                errors_I.value = e.response.data.errors;
            }
        }
    };
    return {
        itineraries,
        itinerary,
        errors_I,
        selectedbusinesses,
        selectedbusinesses1,

        getItineraries,
        getItinerary,
        storeItinerary,
        updateItinerary,
        destroyItinerary,
        searchItineraries,
        getStrategicBusinesses,
        getItinerariesByUser,
        getChecklists,
        strategicbusiness,
        selectedbusinesses_ref,
        selectedbusinesses_reA,
        checklists,
        complete_ItineraryBusiness,
        getItineraryPull,
        getBusiness,
        business,
        handleStartTime,
        handleFinishTime,
        getChecklistsBusiness,
        checklists_values,
        Add_Business,
        destroyItineraryBusiness,
        historical_checklist,
        getHistoricalBusinessData,
        itinerary_id,
        exportItineraryData,
        exportItineraryBusinessData,
        exportItineraryPullData,
        assigned_id,
        validateStartChecklistsBusiness,
        validateStartBusiness,
        getChecklistData,
        checklistdata,
        complete_ItineraryBusiness_new,
        getBussinessHistory,
        exportItinerarySelected,
        destroyItinerarySelected,
        destroyItinerary_logs,
        loadFromServer,
    };
}
