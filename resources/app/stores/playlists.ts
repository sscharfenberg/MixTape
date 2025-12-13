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
    actions: {
        /**
         * @function set the name of a playlist
         * @param playlistId
         * @param list
         */
        setPlaylistName(playlistId: string, list: Array<Playlist>) {
            this.playlists = this.playlists.map(pList => {
                if (pList.id === playlistId) {
                    pList.name = list.name;
                }
                return pList;
            });
        }
    },
    getters: {
        getPlaylist: state => {
            return (playlistId: string) => state.playlists.find(playlist => playlist.id === playlistId);
        }
    }
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
