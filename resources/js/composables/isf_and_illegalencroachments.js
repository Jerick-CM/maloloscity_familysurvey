import { ref, reactive } from "vue";
import axios from "axios";

export default function useISF_illegal_encroachment() {
    const isf = ref([]);
    const isfs = ref([]);
    const errors_isf = ref("");

    const getISF = async (id) => {
        let response = await axios.get("/request/isf/" + id);
        isf.value = response.data.data;
    };

    const getISFs = async () => {
        let response = await axios.get("/request/isf");
        isfs.value = response.data.data;
    };

    const storeISF = async (data) => {
        errors_isf.value = "";
        try {
            await axios.post("/request/isf", data);
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
            await axios.post(
                "/request/isf/update/" + id,
                isf.value
            );
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

    const loadFromServer = async (
        isfs,
        serverItemsLength,
        options,
        params
    ) => {
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

    return {
        errors_isf,
        isf,
        isfs,
        getISF,
        getISFs,
        destroyISF,
        updateISF,
        storeISF,
        getISF,
        getISFs,
        loadFromServer,
    };
}
