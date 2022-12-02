import { ref, reactive } from "vue";
import axios from "axios";

export default function useSoloParent() {
    const soloparent = ref([]);
    const soloparents = ref([]);
    const errors_soloparent = ref("");
    const street = ref([]);

    const getISF = async (id) => {
        let response = await axios.get("/request/soloparent/" + id);

        soloparent.value = response.data.data;

        street.value = {
            value: response.data.data.street,
            label: response.data.data.street,
        };
    };

    const getISFs = async () => {
        let response = await axios.get("/request/soloparent");
        soloparents.value = response.data.data;
    };

    const store = async (data) => {
        errors_soloparent.value = "";
        try {
            await axios.post(route("soloparent-store"), data);
        } catch (e) {
            if (e.response.status === 422) {
                errors_soloparent.value = e.response.data.errors;
            }
            if (e.response.status === 500) {
                errors_soloparent.value = {
                    errors: { error: "server Error 500" },
                };
            }
        }
    };

    const update = async (id) => {
        errors_soloparent.value = "";

        try {
            await axios.post(
                "/request/soloparent/update/" + id,
                soloparent.value
            );
        } catch (e) {
            if (e.response.status === 422) {
                errors_soloparent.value = e.response.data.errors;
            }
            if (e.response.status === 500) {
                errors_soloparent.value = {
                    errors: { error: "server Error 500" },
                };
            }
        }
    };

    const destroySoloParent = async (id) => {
        await axios.post("/request/soloparent/delete/" + id);
    };

    const loadFromServer = async (
        soloparents,
        serverItemsLength,
        options,
        params
    ) => {
        errors_soloparent.value = "";

        try {
            let response = await axios.post(route("soloparent-fetch"), {
                options: options.value,
                params: params,
            });

            soloparents.value = response.data.data;
            serverItemsLength.value = response.data.totalRecords;
        } catch (e) {
            if (e.response.status === 422) {
                errors_soloparent.value = e.response.data.errors;
            }
        }
    };

    return {
        errors_soloparent,
        soloparent,
        soloparents,
        getISF,
        getISFs,
        destroySoloParent,
        update,
        store,
        getISF,
        getISFs,
        loadFromServer,
        street,
    };
}
