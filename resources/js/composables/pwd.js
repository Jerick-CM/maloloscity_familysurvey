import { ref } from "vue";
import axios from "axios";

export default function usePWD() {

    const pwd = ref([]);
    const pwd_renewals = ref([]);
    const pwds = ref([]);
    const pwd_application_date = ref([]);
    const errors_pwd = ref("");

    const muxsel_complete_address = ref(null);
    const muxsel_gender = ref(null);
    const muxsel_disability = ref(null);
    const muxsel_cause_of_disability = ref(null);
    const muxsel_remarks = ref(null);
    const muxsel_notes = ref(null);
    const muxsel_barangay = ref(null);

    const getPWD = async (id) => {
        
        let response = await axios.get(route("pwd-request-edit", id));
        pwd.value = response.data.data;
        let data = [];

        response.data.pwd.forEach((element) => {
            data.push({ value: element.year, label: element.year });
        });

        pwd_application_date.value = response.data.date["date_of_application"];
        pwd_renewals.value = data;

        muxsel_complete_address.value = {
            value: response.data.data.address,
            label: response.data.data.address,
        };

        muxsel_gender.value = {
            value: response.data.data.gender,
            label: response.data.data.gender,
        };

        muxsel_disability.value = {
            value: response.data.data.disability,
            label: response.data.data.disability,
        };

        muxsel_cause_of_disability.value = {
            value: response.data.data.cause_of_disability,
            label: response.data.data.cause_of_disability,
        };

        muxsel_remarks.value = {
            value: response.data.data.remarks,
            label: response.data.data.remarks,
        };

        muxsel_notes.value = {
            value: response.data.data.notes,
            label: response.data.data.notes,
        };

        muxsel_barangay.value = {
            value: response.data.data.barangay,
            label: response.data.data.barangay,
        };

    };

    const getISFs = async () => {
        let response = await axios.get("/request/pwd");
        pwds.value = response.data.data;
    };

    const storePWD = async (data) => {
        errors_pwd.value = "";
        try {
            await axios.post(route("pwd-store"), data);
        } catch (e) {
            if (e.response.status === 422) {
                errors_pwd.value = e.response.data.errors;
            }
            if (e.response.status === 500) {
                errors_pwd.value = {
                    errors: { error: "server Error 500" },
                };
            }
        }
    };

    const updatePWD = async (id) => {
        errors_pwd.value = "";

        try {
            await axios.post(route("pwd-request-update", id), pwd.value);
        } catch (e) {
            if (e.response.status === 422) {
                errors_pwd.value = e.response.data.errors;
            }
            if (e.response.status === 500) {
                errors_pwd.value = {
                    errors: { error: "server Error 500" },
                };
            }
        }
    };

    const destroyPWD = async (id) => {
        await axios.post(route("pwd-request-delete", id));
    };

    const loadFromServer = async (pwds, serverItemsLength, options, params) => {
        errors_pwd.value = "";
        try {
            let response = await axios.post(route("pwd-fetch"), {
                options: options.value,
                params: params,
            });

            pwds.value = response.data.data;
            serverItemsLength.value = response.data.totalRecords;
        } catch (e) {
            if (e.response.status === 422) {
                errors_pwd.value = e.response.data.errors;
            }
        }
    };

    const exportRequests = async (
        pwds,
        serverItemsLength,
        serverOptions,
        searchParameter
    ) => {
        await axios
            .post(
                route("pwd-export"),
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
                    "pwd-data" +
                        new Date().toJSON().slice(0, 10).replace(/-/g, "_") +
                        ".xls"
                );
                document.body.appendChild(fileLink);
                fileLink.click();
            });
    };

    return {
        errors_pwd,
        pwd,
        pwds,
        pwd_renewals,
        pwd_application_date,
        muxsel_complete_address,
        muxsel_gender,
        muxsel_disability,
        muxsel_cause_of_disability,
        muxsel_remarks,
        muxsel_notes,
        muxsel_barangay,
        getPWD,
        getISFs,
        destroyPWD,
        updatePWD,
        storePWD,
        getPWD,
        getISFs,
        loadFromServer,
        exportRequests,
    };
}
