/******************************************************************************
 * store for genre page
 *****************************************************************************/
import { defineStore } from "pinia";
import { PersistenceOptions } from "pinia-plugin-persistedstate";

/**
 * define store
 */
export const useGenreStore = defineStore("genreStore", {
    state: (): QueueState => {
        return {
            genres: []
        };
    },
    actions: {
        /**
         * @function update the tabIndex of a genre
         */
        setCurrentTabIndex(genreName: string, tabIndex: number): void {
            this.artists = this.genres.map((genre: Genre) => {
                if (genre.encodedName === genreName) {
                    genre.tabIndex = tabIndex;
                }
                return genre;
            });
        }
    },
    getters: {
        /**
         * @function get the tabIndex of a specific genre
         */
        getCurrentTabIndex(state) {
            return (genreName: string) => {
                const genre = state.genres.find((genre: Genre) => genre.encodedName === genreName);
                if (genre) {
                    return genre.tabIndex || 0;
                } else {
                    this.$patch(state => {
                        state.genres.push({
                            encodedName: genreName,
                            tabIndex: 0
                        });
                    });
                    console.log("added genre", state.genres);
                    return 0;
                }
            };
        }
    },
    persist: <PersistenceOptions>{
        key: "genres",
        storage: localStorage
    }
});

interface QueueState {
    genres: Array<Genre>;
}

interface Genre {
    encodedName: string;
    tabIndex: number;
}
