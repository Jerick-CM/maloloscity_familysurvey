import { ref, reactive } from "vue";
import axios from "axios";

export default function useDashboard() {
    const dashboards = ref([]);
    const dashboard = ref([]);
    const errors_dash = ref([]);

    const getdashboard_datas = async () => {
        let response = await axios.get("/request/familysurvey/dashboard/get");
        dashboards.value = response.data.data;
    };

    // const getdashboard_data = async (id) => {
    //     let response = await axios.get("/api/dashboard/" + id);
    // };

    /* custom api */

    return {
        dashboards,
        dashboard,
        errors_dash,
        // getdashboard_data,
        getdashboard_datas,
    };
}
