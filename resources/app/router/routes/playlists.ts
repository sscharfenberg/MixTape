export const playlistRoutes = [
    {
        path: "/playlists",
        children: [
            {
                path: "",
                component: () => import("../../views/Playlists/PagePlaylists.vue"),
                name: "playlists",
                meta: { title: "Playlists", icon: "playlists" }
            },
            {
                path: ":id",
                component: () => import("../../views/Playlists/Playlist/PagePlaylist.vue"),
                name: "playlist",
                meta: { title: "Playlist", icon: "playlist" }
            }
        ]
    }
];
