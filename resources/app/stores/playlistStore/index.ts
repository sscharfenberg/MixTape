import { defineStore } from "pinia";

/**
 * define appStore
 */
export const usePlaylistStore = defineStore("playlistStore", {
    state: (): PlaylistState => {
        return {
            playlists: [],
            detailedPlaylist: null
        };
    },
    actions: {
        /**
         * @function set the name of a playlist
         * @param playlistId
         * @param list
         */
        setPlaylistName(playlistId: string, list: Playlist) {
            this.playlists = this.playlists.map(pList => {
                if (pList.id === playlistId) {
                    pList.name = list.name;
                }
                return pList;
            });
        },

        /**
         * set the "nowPlaying" flag in playlists to a specific song
         * @param songEncodedPath
         */
        setNowPlaying(songEncodedPath: string) {
            this.detailedPlaylist.songs = this.detailedPlaylist.songs.map(s => {
                s.nowPlaying = s.encodedPath === songEncodedPath;
                return s;
            });
        }
    },
    getters: {
        getPlaylist: state => {
            return (playlistId: string) => state.playlists.find(playlist => playlist.id === playlistId);
        },

        getSong: state => {
            return (songId: string) => state.detailedPlaylist.songs.find(song => song.id === songId);
        }
    }
});

interface PlaylistState {
    playlists: Array<Playlist>;
    detailedPlaylist: Playlist | null;
}

interface Playlist {
    id: string;
    name: string;
    sort: number;
    entries: number;
    duration: number;
    songs: Array<Song>;
    cover: string;
    createdAt: Date;
}

interface Song {
    id: string;
    album: string;
    artist: string;
    song: string;
    duration: number;
    size: number;
    sort: number;
    createdAt: Date;
    updatedAt: Date;
    encodedPath: string;
    nowPlaying: boolean;
}
