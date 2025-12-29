import { defineStore } from "pinia";
import { PersistenceOptions } from "pinia-plugin-persistedstate";

/**
 * define appStore
 */
export const useWidgetStore = defineStore("widgetStore", {
    state: (): WidgetState => {
        return {
            toggles: {
                song: true,
                album: false,
                artist: true,
                genre: false,
                audiobook: false
            }
        };
    },
    actions: {},
    getters: {},
    persist: <PersistenceOptions>{
        key: "widget",
        storage: localStorage
    }
});

interface WidgetState {
    toggles: WidgetToggle;
}

interface WidgetToggle {
    song: boolean;
    album: boolean;
    artist: boolean;
    genre: boolean;
    audiobook: boolean;
}
