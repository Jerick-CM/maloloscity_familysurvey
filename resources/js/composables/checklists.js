import { ref, reactive } from "vue";
import axios from "axios";

export default function useChecklist() {
    const checklists = ref([]);
    const checklist = ref([]);
    const errors_checklist = ref("");
    const checklist_form = ref([]);

    const getChecklists = async () => {
        let response = await axios.get("/cstm/checklists");
        checklists.value = response.data.data;
        checklists.value.map((item, index) => {
            checklist_form.value.push(item.name);
        });
    };

    const getchecklist = async (id) => {
        let response = await axios.get("/api/checklists/" + id);
        checklist.value = response.data.data;
    };

    const storechecklist = async (data) => {
        errors_checklist.value = "";
        try {
            await axios.post("/api/checklists", data);
        } catch (e) {
            if (e.response.status === 422) {
                errors_checklist.value = e.response.data.errors;
            }
        }
    };

    const updatechecklist = async (id) => {
        errors_checklist.value = "";
        try {
            await axios.put("/api/checklists/" + id, checklist.value);
        } catch (e) {
            if (e.response.status === 422) {
                errors_checklist.value = e.response.data.errors;
            }
        }
    };

    const destroychecklist = async (id) => {
        await axios.delete("/api/checklists/" + id);
    };

    /* custom */

    const updateChecklist_data = async (data) => {
        errors_checklist.value = "";
        try {
            await axios.post("/cstm/checklists/all", data);
        } catch (e) {
            if (e.response.status === 422) {
                errors_checklist.value = e.response.data.errors;
            }
        }
    };

    return {
        checklist_form,
        checklists,
        checklist,
        errors_checklist,
        getChecklists,
        getchecklist,
        storechecklist,
        updatechecklist,
        destroychecklist,
        updateChecklist_data,
    };
}
