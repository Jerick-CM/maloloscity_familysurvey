import { ref, reactive } from "vue";
import axios from "axios";

export default function useUsers() {
    const users = ref([]);
    const user = ref([]);
    const errors_U = ref("");
    const errors_permissions = ref("");
    const errors_change_password = ref("");
    const errors_reset_password = ref("");
    const errors_users_table = ref("");

    const getUsers = async () => {
        let response = await axios.get("/api/users");
        users.value = response.data.data;
    };

    const getUsersById = async (id) => {
        let response = await axios.get("/api/cstm/users/" + id);

        users.value = response.data.data;
    };

    const getUser = async (id) => {
        let response = await axios.get("/api/users/" + id);
        user.value = response.data.data;
    };

    const storeUser = async (data) => {
        errors_U.value = "";
        try {
            await axios.post("/api/users", data);
        } catch (e) {
            if (e.response.status === 422) {
                errors_U.value = e.response.data.errors;
            }
        }
    };

    const updateUser = async (id) => {
        errors_U.value = "";
        try {
            await axios.put("/api/users/" + id, user.value).then(() => {});
        } catch (e) {
            if (e.response.status === 422) {
                errors_U.value = e.response.data.errors;
            }
        }
    };

    const updateUserPassword = async (id) => {
        errors_change_password.value = "";
        try {
            await axios
                .put("/api/cstm/users/changepassword/" + id, user.value)
                .then(() => {});
        } catch (e) {
            if (e.response.status === 422) {
                errors_change_password.value = e.response.data.errors;
            }
        }
    };

    const handleResetPassword = async (id) => {
        errors_reset_password.value = "";
        try {
            await axios
                .put("/api/cstm/users/resetpassword/" + id, user.value)
                .then(() => {});
        } catch (e) {
            if (e.response.status === 422) {
                errors_reset_password.value = e.response.data.errors;
            }
        }
    };

    const destroyUser = async (id) => {
        await axios.delete("/api/users/" + id);
    };

    const destroyUser_with_logs = async (id, user_id) => {
        errors_users_table.value = "";

        try {
            let response = await axios.delete(
                "/api/cstm/users/" + id + "/" + user_id
            );
        } catch (e) {
            if (e.response.status === 422) {
                errors_users_table.value = e.response.data.errors;
            }
        }
    };

    const searchUser = async (data) => {
        errors_U.value = "";
        try {
            let response = await axios.post("/api/users/search", data);
            users.value = response.data.data;
        } catch (e) {
            if (e.response.status === 422) {
                errors_U.value = e.response.data.errors;
            }
        }
    };

    const exportUserData = async () => {
        await axios
            .post("/api/cstm/user/export", {}, { responseType: "blob" })
            .then((response) => {
                var fileURL = window.URL.createObjectURL(
                    new Blob([response.data])
                );
                var fileLink = document.createElement("a");
                fileLink.href = fileURL;
                fileLink.setAttribute(
                    "download",
                    "users-" +
                        new Date().toJSON().slice(0, 10).replace(/-/g, "_") +
                        ".xls"
                );
                document.body.appendChild(fileLink);
                fileLink.click();
            });
    };
    const updateUserPermissions = async (data) => {
        errors_permissions.value = "";
        try {
            let response = await axios.post(
                "/users/updatePermissions",
                data
            );
            // users.value = response.data.data;
        } catch (e) {
            if (e.response.status === 422) {
                errors_permissions.value = e.response.data.errors;
            }
        }
    };

    const loadFromServer = async (
        users,
        serverItemsLength,
        options,
        params
    ) => {
        errors_users_table.value = "";
        try {
            let response = await axios.post("/api/cstm/users/fetch", {
                options: options.value,
                params: params,
            });

            users.value = response.data.data;
            serverItemsLength.value = response.data.totalRecords;
        } catch (e) {
            if (e.response.status === 422) {
                errors_users_table.value = e.response.data.errors;
            }
        }
    };
    return {
        users,
        user,
        errors_U,
        errors_permissions,
        getUsers,
        getUser,
        storeUser,
        updateUser,
        destroyUser,
        searchUser,
        updateUserPassword,
        handleResetPassword,
        errors_change_password,
        errors_reset_password,
        getUsersById,
        exportUserData,
        destroyUser_with_logs,
        updateUserPermissions,
        loadFromServer,
        errors_users_table
    };
}
