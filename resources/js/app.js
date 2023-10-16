import "./bootstrap";
import { createApp } from "vue/dist/vue.esm-bundler";
import equipment from "@/pages/equipment.vue";
const app = createApp({});

app.component("equipment", equipment);
app.mount("#app");
