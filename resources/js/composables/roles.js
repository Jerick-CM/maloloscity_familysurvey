import { ref, reactive } from "vue";
import axios from "axios";

export default function useRoles() {
    const roles = ref([]);
    const role = ref([]);
    const errors_roles = ref("");
    const roles_form = ref([]);

    const getRoles = async () => {
        let response = await axios.get("/cstm/roles");
        roles.value = response.data.data;
        roles.value.map((item, index) => {
            roles_form.value.push(item.name);
        });
    };

    const getRoles_edit = async () => {
        let response = await axios.get("/api/cstm/roles");
        roles.value = response.data.data;
        roles.value.map((item, index) => {
            roles_form.value.push(item.name);
        });
    };

    const getRoles_user_edit = async () => {
        let response = await axios.get("/api/cstm/roles/user_edit");
        roles.value = response.data.data;
        roles.value.map((item, index) => {
            roles_form.value.push(item.name);
        });
    };

    const getRole = async (id) => {
        let response = await axios.get("/api/roles/" + id);
        role.value = response.data.data;
    };

    const storeRole = async (data) => {
        errors_roles.value = "";
        try {
            await axios.post("/api/roles", data);
        } catch (e) {
            if (e.response.status === 422) {
                errors_roles.value = e.response.data.errors;
            }
        }
    };

    const updateRole = async (id) => {
        errors_roles.value = "";
        try {
            await axios.put("/api/roles/" + id, role.value);
        } catch (e) {
            if (e.response.status === 422) {
                errors_roles.value = e.response.data.errors;
            }
        }
    };

    const destroyRole = async (id) => {
        await axios.delete("/api/roles/" + id);
    };
    // cstm
    const updateRoleslist_data = async (data) => {
        errors_roles.value = "";
        try {
            await axios.post("/api/cstm/roles/update_all", data);
        } catch (e) {
            if (e.response.status === 422) {
                errors_roles.value = e.response.data.errors;
            }
        }
    };
    return {
        roles_form,
        roles,
        role,
        errors_roles,
        getRoles,
        getRoles_edit,
        getRole,
        storeRole,
        updateRole,
        destroyRole,
        updateRole,
        updateRoleslist_data,
        getRoles_user_edit,
    };
}
