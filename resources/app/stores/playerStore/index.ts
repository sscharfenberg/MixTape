/******************************************************************************
 * store for audio player
 *****************************************************************************/
import { defineStore } from "pinia";
// import { actions } from "./actions";

/**
 * define playerStore
 */
export const usePlayerStore = defineStore("playerStore", {
    state: (): PlayerState => {
        return {
            autoplay: false,
            shuffle: false,
            sortedQueue: [],
            shuffledQueue: []
        };
    },
    actions: {},
    getters: {},
    persist: {
        key: "player",
        storage: localStorage
    }
});

interface PlayerState {
    autoplay: boolean;
    shuffle: boolean;
    sortedQueue: Array<string>;
    shuffledQueue: Array<string>;
}
