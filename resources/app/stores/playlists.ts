import { defineStore } from "pinia";

/**
 * define appStore
 */
export const usePlaylistStore = defineStore("playlistStore", {
    state: (): PlaylistState => {
        return {
            playlists: []
        };
    },
    actions: {},
    getters: {}
});

interface PlaylistState {
    playlists: Array<Playlist>;
}

interface Playlist {
    id: string;
    name: string;
    sort: number;
    entries: number;
    duration: number;
}
