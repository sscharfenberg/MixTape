import PageDashboard from "../../views/Dashboard/PageDashboard.vue";
import { musicRoutes } from "./music";

export const routes = [
    {
        path: "/",
        component: PageDashboard,
        name: "dashboard",
        meta: { title: "Dashboard", icon: "dashboard" }
    },
    ...musicRoutes,
    {
        path: "/audiobooks",
        component: () => import("../../views/Audiobooks/PageAudiobooks.vue"),
        name: "audiobooks",
        meta: { title: "Audiobooks", icon: "audiobook" }
    },
    {
        path: "/playlists",
        component: () => import("../../views/Playlists/PagePlaylists.vue"),
        name: "playlists",
        meta: { title: "Playlists", icon: "playlist" }
    }
];
