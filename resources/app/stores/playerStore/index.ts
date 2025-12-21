/******************************************************************************
 * store for audio player
 *****************************************************************************/
import { defineStore } from "pinia";
import { PersistenceOptions } from "pinia-plugin-persistedstate";

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
    persist: <PersistenceOptions>{
        key: "player",
        storage: localStorage
    }
});

interface PlayerState {
    autoplay: boolean;
    shuffle: boolean;
}
