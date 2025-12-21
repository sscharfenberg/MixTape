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
        setCurrentTabIndex(artistName: string, tabIndex: number): void {
            this.artists = this.artists.map((artist: Artist) => {
                if (artist.encodedName === artistName) {
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
            return (artistName: string) => {
                const artist = state.artists.find((artist: Artist) => artist.encodedName === artistName);
                if (artist) {
                    return artist.tabIndex || 0;
                } else {
                    this.$patch(state => {
                        state.artists.push({
                            encodedName: artistName,
                            tabIndex: 0
                        });
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
    encodedName: string;
    tabIndex: number;
}
