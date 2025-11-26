export const musicRoutes = [
    {
        path: "/music",
        children: [
            {
                path: "",
                component: () => import("../../views/Music/PageMusic.vue"),
                name: "music",
                meta: { title: "Musik", icon: "music" }
            },
            {
                path: "songs",
                children: [
                    {
                        path: "",
                        component: () => import("../../views/Music/Songs/PageSongs.vue"),
                        name: "songs",
                        meta: { title: "Alle Songs", icon: "music" }
                    },
                    {
                        path: ":id",
                        component: () => import("../../views/Music/Songs/Song/PageSong.vue"),
                        name: "song",
                        meta: { title: "Song", icon: "guitar" }
                    }
                ]
            },
            {
                path: "genres",
                children: [
                    {
                        path: "",
                        component: () => import("../../views/Music/Genres/PageGenres.vue"),
                        name: "genres",
                        meta: { title: "Alle Genres", icon: "genre" }
                    },
                    {
                        path: ":id",
                        component: () => import("../../views/Music/Genres/Genre/PageGenre.vue"),
                        name: "genre",
                        meta: { title: "Genre", icon: "guitar" }
                    }
                ]
            },
            {
                path: "albums",
                children: [
                    {
                        path: "",
                        component: () => import("../../views/Music/Albums/PageAlbums.vue"),
                        name: "albums",
                        meta: { title: "Alle Alben", icon: "album" }
                    },
                    {
                        path: ":id",
                        component: () => import("../../views/Music/Albums/Album/PageAlbum.vue"),
                        name: "album",
                        meta: { title: "Album", icon: "guitar" }
                    }
                ]
            },
            {
                path: "artists",
                children: [
                    {
                        path: "",
                        component: () => import("../../views/Music/Artists/PageArtists.vue"),
                        name: "artists",
                        meta: { title: "Alle Künstler", icon: "artist" }
                    },
                    {
                        path: ":id",
                        component: () => import("../../views/Music/Artists/Artist/PageArtist.vue"),
                        name: "artist",
                        meta: { title: "Künstler", icon: "guitar" }
                    }
                ]
            }
        ]
    }
];
