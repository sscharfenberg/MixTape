/******************************************************************************
 * store for artist page
 *****************************************************************************/
import { defineStore } from "pinia";
import { PersistenceOptions } from "pinia-plugin-persistedstate";

/**
 * define store
 */
export const useArtistStore = defineStore("artistStore", {
    state: (): QueueState => {
        return {
            artists: []
        };
    },
    actions: {
        /**
         * @function update the tabIndex of an artist
         */
        setCurrentTabIndex(artistId: string, tabIndex: number): void {
            this.artists = this.artists.map((artist: Artist) => {
                if (artist.id === artistId) {
                    artist.tabIndex = tabIndex;
                }
                return artist;
            });
        }
    },
    getters: {
        /**
         * @function get the tabIndex of a specific artist
         */
        getCurrentTabIndex(state) {
            return (artistId: string) => {
                const artist = state.artists.find((artist: Artist) => artist.id === artistId);
                if (artist) {
                    return artist.tabIndex;
                } else {
                    state.artists.push({
                        id: artistId,
                        currentTabIndex: 0
                    });
                    console.log("added artist", state.artists);
                    return 0;
                }
            };
        }
    },
    persist: <PersistenceOptions>{
        key: "artists",
        storage: localStorage
    }
});

interface QueueState {
    artists: Array<Artist>;
}

interface Artist {
    id: string;
    tabIndex: number;
}
