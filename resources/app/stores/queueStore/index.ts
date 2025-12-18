/******************************************************************************
 * store for player queues
 *****************************************************************************/
import { defineStore } from "pinia";
import { usePlayerStore } from "../playerStore";
import { usePlaylistStore } from "../playlistStore";
/**
 * define playerStore
 */
export const useQueueStore = defineStore("queueStore", {
    state: (): QueueState => {
        return {
            sortedQueue: [],
            shuffledQueue: [],
            currentQueueIndex: 0,
            currentQueuePath: ""
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
        },

        /**
         * @function set currentQueuePath to the currently playing path
         */
        updateCurrentPath() {
            const playerStore = usePlayerStore();
            this.currentQueuePath = playerStore.shuffle
                ? this.shuffledQueue[this.currentQueueIndex]
                : this.sortedQueue[this.currentQueueIndex];
        },

        /**
         * @function update currentQueueIndex to the new index (sorted/shuffledQueue will have changed)
         */
        updateQueueIndex() {
            const playerStore = usePlayerStore();
            const playlistStore = usePlaylistStore();
            this.currentQueueIndex = playerStore.shuffle
                ? this.shuffledQueue.indexOf(this.currentQueuePath)
                : this.sortedQueue.indexOf(this.currentQueuePath);
            playlistStore.setNowPlaying(this.currentQueuePath);
        }
    },
    getters: {
        /**
         * @function get the current song path.
         */
        getCurrentSongPath(state): string {
            const playerStore = usePlayerStore();
            if (playerStore.shuffle) return state.shuffledQueue[state.currentQueueIndex];
            return state.sortedQueue[state.currentQueueIndex];
        }
    }
});

interface QueueState {
    sortedQueue: Array<string>;
    shuffledQueue: Array<string>;
    currentQueueIndex: number;
    currentQueuePath: string;
}
