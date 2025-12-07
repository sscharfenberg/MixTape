/******************************************************************************
 * store for audio player
 *****************************************************************************/
import { defineStore } from "pinia";
import { actions } from "./actions";

/**
 * @function store definition
 */
export const usePlayerStore = defineStore("player", {
    state: (): PlayerState => {
        return {
            autoplay: false,
            shuffle: false,
            audiobooks: []
        };
    },
    actions,
    getters: {
        getAudiobookBookmark: state => {
            return (AudiobookEncodedName: string) =>
                state.audiobooks.find(book => book.audiobookEncodedName === AudiobookEncodedName);
        }
    },
    persist: {
        key: "player",
        storage: localStorage
    }
});

interface PlayerState {
    autoplay: boolean;
    shuffle: boolean;
    audiobooks: Array<Audiobook>;
}

interface Audiobook {
    audiobookEncodedName: string;
    trackEncodedPath: string;
    timestamp: number;
}
