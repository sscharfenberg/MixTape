/******************************************************************************
 * store for player queues
 *****************************************************************************/
import { defineStore } from "pinia";

/**
 * define playerStore
 */
export const useQueueStore = defineStore("queueStore", {
    state: (): QueueState => {
        return {
            sortedQueue: [],
            shuffledQueue: [],
            currentQueueIndex: 0
        };
    },
    actions: {
        /**
         * @function reset store to defaults
         */
        reset() {
            this.sortedQueue = [];
            this.shuffledQueue = [];
            this.currentQueueIndex = 0;
        }
    },
    getters: {}
});

interface QueueState {
    sortedQueue: Array<string>;
    shuffledQueue: Array<string>;
    currentQueueIndex: 0;
}
