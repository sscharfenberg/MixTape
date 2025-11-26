/******************************************************************************
 * store for audio player
 *****************************************************************************/
import { defineStore } from "pinia";

/**
 * @function store definition
 */
export const usePlayerStore = defineStore("player", {
    state: () => {
        return {
            autoplay: false
        };
    },
    actions: {},
    persist: {
        key: "ac-player",
        storage: localStorage
    }
});
