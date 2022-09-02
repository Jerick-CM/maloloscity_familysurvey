import { ref, reactive } from "vue";
import axios from "axios";

const serverItemsLength = ref(0);
const serverOptions = ref({
    page: 1,
    rowsPerPage: 10,
    sortBy: "",
    sortType: "desc",
    searchField: "",
    searchValue: "",
    filterField: "",
    filterValue: "",
});

export default function useLogs() {
    const logs = ref([]);
    const log = ref([]);
    const errors_logs = ref("");

    const getLogs = async () => {
        let response = await axios.get("/api/logs");
        logs.value = response.data.data;
    };

    const getLog = async (id) => {
        let response = await axios.get("/api/logs/" + id);
        log.value = response.data.data;
    };

    const storeLog = async (data) => {
        errors_logs.value = "";
        try {
            await axios.post("/api/logs", data);
        } catch (e) {
            if (e.response.status === 422) {
                errors_logs.value = e.response.data.errors_logs;
            }
        }
    };

    const updateLog = async (id) => {
        errors_logs.value = "";
        try {
            await axios.put("/api/logs/" + id, log.value).then(() => {});
        } catch (e) {
            if (e.response.status === 422) {
                errors_logs.value = e.response.data.errors_logs;
            }
        }
    };

    const destroyLog = async (id) => {
        await axios.delete("/api/logs/" + id);
    };

    const searchLogs = async (data) => {
        errors_logs.value = "";
        try {
            let response = await axios.post("/api/cstm/logs/search", data);
            logs.value = response.data.data;
        } catch (e) {
            if (e.response.status === 422) {
                errors_logs.value = e.response.data.errors;
            }
        }
    };

    const loadFromServer = async (logs, serverItemsLength, options, params) => {
        errors_logs.value = "";
        try {
            let response = await axios.post("/api/cstm/logs/fetch", {
                options: options.value,
                params: params,
            });

            logs.value = response.data.data;
            serverItemsLength.value = response.data.totalRecords;
        } catch (e) {
            if (e.response.status === 422) {
                errors_logs.value = e.response.data.errors;
            }
        }
    };

    return {
        logs,
        log,
        errors_logs,
        getLogs,
        getLog,
        storeLog,
        updateLog,
        destroyLog,
        searchLogs,
        loadFromServer,
        serverItemsLength,
        serverOptions,
    };
}
