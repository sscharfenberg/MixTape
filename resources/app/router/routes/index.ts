import PageDashboard from "../../views/Dashboard/PageDashboard.vue";
import { audiobookRoutes } from "./audiobooks";
import { musicRoutes } from "./music";
import { playlistRoutes } from "./playlists";

export const routes = [
    {
        path: "/",
        component: PageDashboard,
        name: "dashboard",
        meta: { title: "Dashboard", icon: "dashboard" }
    },
    ...musicRoutes,
    ...audiobookRoutes,
    ...playlistRoutes
];
