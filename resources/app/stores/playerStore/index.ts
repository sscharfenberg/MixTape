/******************************************************************************
 * store for audio player
 *****************************************************************************/
import { defineStore } from "pinia";

/**
 * define playerStore
 */
export const usePlayerStore = defineStore("playerStore", {
    state: (): PlayerState => {
        return {
            autoplay: false,
            shuffle: false
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
}
