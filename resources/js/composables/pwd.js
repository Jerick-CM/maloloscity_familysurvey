import { ref, reactive } from "vue";
import axios from "axios";

export default function usePWD() {
    const pwd = ref([]);
    const pwds = ref([]);
    const errors_pwd = ref("");

    const getISF = async (id) => {
        let response = await axios.get("/request/pwd/" + id);

        pwd.value = response.data.data;
    };

    const getISFs = async () => {
        let response = await axios.get("/request/pwd");
        pwds.value = response.data.data;
    };

    const storePWD = async (data) => {
        errors_pwd.value = "";
        try {
            // "/request/pwd/"
            await axios.post(route(''), data);
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

    const updateISF = async (id) => {
        errors_pwd.value = "";
        try {
            await axios.post("/request/pwd/update/" + id, pwd.value);
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

    const destroyISF = async (id) => {
        await axios.post("/request/pwd/delete/" + id);
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

    return {
        errors_pwd,
        pwd,
        pwds,
        getISF,
        getISFs,
        destroyISF,
        updateISF,
        storePWD,
        getISF,
        getISFs,
        loadFromServer,
    };
}
