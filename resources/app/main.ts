import "@/styles/app.scss";
// import "datatables.net-dt/css/dataTables.dataTables.min.css";
import { createNotivue } from "notivue";
import "notivue/animations.css"; // Only needed if using built-in animations
import "notivue/notification-progress.css";
import "notivue/notification.css"; // Only needed if using built-in notifications
import { createPinia } from "pinia";
import piniaPluginPersistedstate from "pinia-plugin-persistedstate";
import "tippy.js/dist/tippy.css";
import { createApp } from "vue";
import VueTippy from "vue-tippy";
import App from "./App.vue";
import { router } from "./router";

const notivue = createNotivue({
    position: "top-right",
    limit: 4,
    enqueue: true,
    avoidDuplicates: false,
    teleportTo: "body",
    notifications: {
        global: {
            duration: 5000
        }
    }
});

const app = createApp(App);
const pinia = createPinia();
pinia.use(piniaPluginPersistedstate);
app.use(pinia);
app.use(router);
app.use(notivue);
app.use(VueTippy);
app.mount("#app");
