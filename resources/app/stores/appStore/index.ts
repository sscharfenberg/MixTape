import { defineStore } from "pinia";

/**
 * define appStore
 */
export const useAppStore = defineStore("app", {
    state: (): AppState => {
        return {
            loading: false,
            error: ""
        };
    },
    actions: {},
    getters: {}
});

interface AppState {
    loading: boolean;
    error: string;
}
