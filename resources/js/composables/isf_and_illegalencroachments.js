import { ref, reactive } from "vue";
import axios from "axios";

export default function useISF_illegal_encroachment() {
    const isf = ref([]);
    const isfs = ref([]);
    const errors_isf = ref("");
    const street = ref([]);
    const balik_probinsya = ref(null);
    const tenurial_status = ref(null);
    const zone = ref(null);
    const body_of_water_name = ref(null);
    const body_of_water_type = ref(null);
    const distance_to_waterway = ref(null);

    const getISF = async (id) => {
        let response = await axios.get("/request/isf/" + id);

        isf.value = response.data.data;

        street.value = {
            value: response.data.data.street,
            label: response.data.data.street,
        };

        balik_probinsya.value = {
            value: response.data.data.balik_probinsya,
            label: response.data.data.balik_probinsya,
        };

        tenurial_status.value = {
            value: response.data.data.tenurial_status,
            label: response.data.data.tenurial_status,
        };

        zone.value = {
            value: response.data.data.zone,
            label: response.data.data.zone,
        };

        body_of_water_name.value = {
            value: response.data.data.body_of_water_name,
            label: response.data.data.body_of_water_name,
        };

        body_of_water_type.value = {
            value: response.data.data.body_of_water_type,
            label: response.data.data.body_of_water_type,
        };

        distance_to_waterway.value = {
            value: response.data.data.distance_to_waterway,
            label: response.data.data.distance_to_waterway,
        };
    };

    const getISFs = async () => {
        let response = await axios.get("/request/isf");
        isfs.value = response.data.data;
    };

    const storeISF = async (data) => {
        errors_isf.value = "";
        try {
            await axios.post("/request/isf/", data);
        } catch (e) {
            if (e.response.status === 422) {
                errors_isf.value = e.response.data.errors;
            }
            if (e.response.status === 500) {
                errors_isf.value = {
                    errors: { error: "server Error 500" },
                };
            }
        }
    };

    const updateISF = async (id) => {
        errors_isf.value = "";
        try {
            await axios.post("/request/isf/update/" + id, isf.value);
        } catch (e) {
            if (e.response.status === 422) {
                errors_isf.value = e.response.data.errors;
            }
            if (e.response.status === 500) {
                errors_isf.value = {
                    errors: { error: "server Error 500" },
                };
            }
        }
    };

    const destroyISF = async (id) => {
        await axios.post("/request/isf/delete/" + id);
    };

    const loadFromServer = async (isfs, serverItemsLength, options, params) => {
        errors_isf.value = "";
        try {
            let response = await axios.post("/request/isf/fetch", {
                options: options.value,
                params: params,
            });

            isfs.value = response.data.data;
            serverItemsLength.value = response.data.totalRecords;
        } catch (e) {
            if (e.response.status === 422) {
                errors_isf.value = e.response.data.errors;
            }
        }
    };


    const exportRequests = async (
        isfs,
        serverItemsLength,
        serverOptions,
        searchParameter
    ) => {
        await axios
            .post(
                route("isf-export"),
                {
                    options: serverOptions.value,
                    params: searchParameter,
                },
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
                    "SoloParents_data-" +
                        new Date().toJSON().slice(0, 10).replace(/-/g, "_") +
                        ".xls"
                );
                document.body.appendChild(fileLink);
                fileLink.click();
            });
    };


    return {
        errors_isf,
        isf,
        isfs,
        street,
        balik_probinsya,
        tenurial_status,
        zone,
        body_of_water_name,
        body_of_water_type,
        distance_to_waterway,
        getISF,
        getISFs,
        destroyISF,
        updateISF,
        storeISF,
        getISF,
        getISFs,
        loadFromServer,
        exportRequests,
    };
}
