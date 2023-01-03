import { ref, reactive } from "vue";
import axios from "axios";

export default function useSoloParent() {
    const soloparent = ref([]);
    const soloparents = ref([]);
    const errors_soloparent = ref("");
    const soloparent_renewals = ref([]);
    const soloparent_application_date = ref([]);
    const muxsel_barangay = ref(null);
    const muxsel_gender = ref(null);
    const muxsel_remarks = ref(null);
    const muxsel_notes = ref(null);
    const muxsel_address = ref(null);


    const getSoloParent = async (id) => {
        let response = await axios.get(route("soloparent-request-edit", id));
        soloparent.value = response.data.data;
        let data = [];
        response.data.soloparent.forEach((element) => {
            data.push({ value: element.year, label: element.year });
        });

        soloparent_application_date.value =
            response.data.date["date_of_application"];

        soloparent_renewals.value = data;

        muxsel_address.value = {
            value: response.data.data.address,
            label: response.data.data.address,
        };

        muxsel_barangay.value = {
            value: response.data.data.barangay,
            label: response.data.data.barangay,
        };

        muxsel_gender.value = {
            value: response.data.data.gender,
            label: response.data.data.gender,
        };

        muxsel_remarks.value = {
            value: response.data.data.remarks,
            label: response.data.data.remarks,
        };

        muxsel_notes.value = {
            value: response.data.data.notes,
            label: response.data.data.notes,
        };
    };

    const getSoloParents = async () => {
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

    const updateSoloParent = async (id) => {
        errors_soloparent.value = "";

        try {
            await axios.post(
                route("soloparent-request-update", id),
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
        await axios.post(route("soloparent-request-delete", id));
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

    const exportRequests = async (
        soloparents,
        serverItemsLength,
        serverOptions,
        searchParameter
    ) => {
        await axios
            .post(
                route("soloparent-export"),
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
        errors_soloparent,
        soloparent,
        soloparents,
        soloparent_renewals,
        soloparent_application_date,
        muxsel_barangay,
        muxsel_gender,
        muxsel_remarks,
        muxsel_notes,
        muxsel_address,
        getSoloParent,
        getSoloParents,
        destroySoloParent,
        store,
        loadFromServer,
        updateSoloParent,
        exportRequests,
    };
}
