import { ref, reactive } from "vue";
import axios from "axios";

export default function useBusinesses() {
    const businesses = ref([]);
    const business = ref([]);
    const lineofbusinesses = ref([]);
    const errors = ref("");

    const line_of_business = ref("");
    const strategic_location = ref("");
    const qtr_paid = ref("");
    const terms = ref("");
    const code = ref("");
    const status = ref("");

    const getBusinesses = async () => {
        let response = await axios.get("/api/business");
        businesses.value = response.data.data;
    };

    const getBusiness = async (id) => {
        let response = await axios.get("/api/business/" + id);
        business.value = response.data.data;
        line_of_business.value = {
            value: response.data.data.line_of_business,
            label: response.data.data.line_of_business,
        };
        strategic_location.value = {
            value: response.data.data.strategic_location,
            label: response.data.data.strategic_location,
        };
        qtr_paid.value = {
            value: response.data.data.qtr_paid,
            label: response.data.data.qtr_paid,
        };

        terms.value = {
            value: response.data.data.terms,
            label: response.data.data.terms,
        };

        code.value = {
            value: response.data.data.code,
            label: response.data.data.code,
        };

        status.value = {
            value: response.data.data.status,
            label: response.data.data.status,
        };
    };

    const storeBusiness = async (data) => {
        errors.value = "";
        try {
            await axios.post("/api/business", data);
        } catch (e) {
            if (e.response.status === 422) {
                errors.value = e.response.data.errors;
            }
        }
    };

    const updateBusiness = async (id) => {
        errors.value = "";
        try {
            await axios
                .put("/api/business/" + id, business.value)
                .then(() => {});
        } catch (e) {
            if (e.response.status === 422) {
                errors.value = e.response.data.errors;
            }
        }
    };

    const destroyBusiness = async (id) => {
        await axios.delete("/api/business/" + id);
    };

    /* custom api */

    const searchBusiness = async (data) => {
        errors.value = "";
        try {
            let response = await axios.post("/api/cstm/business/search", data);
            businesses.value = response.data.data;
        } catch (e) {
            if (e.response.status === 422) {
                errors.value = e.response.data.errors;
            }
        }
    };
    const getLineofbusinesses = async () => {
        let response = await axios.get("/api/cstm/lineofbusinesses");
        lineofbusinesses.value = response.data.data;
    };

    const getBusinessesItinerary = async () => {
        let response = await axios.get("/api/cstm/business/itinerary");
        businesses.value = response.data.data;
    };

    const exportBusinessData = async () => {
        await axios
            .post("/api/cstm/business/export", {}, { responseType: "blob" })
            .then((response) => {
                var fileURL = window.URL.createObjectURL(
                    new Blob([response.data])
                );
                var fileLink = document.createElement("a");
                fileLink.href = fileURL;
                fileLink.setAttribute(
                    "download",
                    "business-" +
                        new Date().toJSON().slice(0, 10).replace(/-/g, "_") +
                        ".xls"
                );
                document.body.appendChild(fileLink);
                fileLink.click();
            });
    };

    const exportSelectedBusiness = async (items) => {
        await axios
            .post(
                "/api/cstm/business/export_selected_business",
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
                    "business-list" +
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

    const destroyBusiness_with_logs = async (id, user_id) => {
        await axios.delete("/api/cstm/business/" + id + "/" + user_id);
    };

    const loadFromServer = async (
        businesses,
        serverItemsLength,
        options,
        params
    ) => {
        errors.value = "";
        try {
            let response = await axios.post("/api/cstm/business/fetch", {
                options: options.value,
                params: params,
            });

            businesses.value = response.data.data;
            serverItemsLength.value = response.data.totalRecords;
        } catch (e) {
            if (e.response.status === 422) {
                errors.value = e.response.data.errors;
            }
        }
    };

    return {
        businesses,
        business,
        errors,
        lineofbusinesses,
        line_of_business,
        strategic_location,
        qtr_paid,
        terms,
        code,
        status,

        getBusinesses,
        getBusiness,
        storeBusiness,
        updateBusiness,
        destroyBusiness,
        searchBusiness,
        getBusinessesItinerary,
        getLineofbusinesses,
        exportBusinessData,
        exportSelectedBusiness,
        destroyBusiness_with_logs,
        loadFromServer,
    };
}
