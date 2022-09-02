import "./bootstrap";
import '../css/app.css';


import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/inertia-vue3";
import { InertiaProgress } from "@inertiajs/progress";

import { ZiggyVue } from "../../vendor/tightenco/ziggy/dist/vue.m";

import Vue3EasyDataTable from "vue3-easy-data-table";
import "vue3-easy-data-table/dist/style.css";

import Toast, { POSITION } from "vue-toastification";
import "vue-toastification/dist/index.css";

const appName =
    window.document.getElementsByTagName("title")[0]?.innerText || "Malolos";

const options = {
    position: POSITION.BOTTOM_RIGHT,
    timeout: 2000,
};

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}`),
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(Toast, options)
            .component("EasyDataTable", Vue3EasyDataTable)
            .mount(el);
    },
});

InertiaProgress.init({ color: "#4B5563" });
