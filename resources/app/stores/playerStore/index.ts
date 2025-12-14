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
            audiobooks: []
        };
    },
    actions: {
        /**
         * @function set bookmark of audiobook
         * @param AudiobookEncodedName
         * @param TrackEncodedPath
         * @param timestamp
         */
        setAudiobookBookmark(AudiobookEncodedName: string, TrackEncodedPath: string, timestamp: number) {
            console.log("set audiobookBookmark for ", TrackEncodedPath, timestamp);
            // do we already have a bookmark for this book?
            if (this.audiobooks.find((book: Audiobook) => book.audiobookEncodedName === AudiobookEncodedName)) {
                console.log("update audiobookBookmark to", TrackEncodedPath, timestamp);
                this.audiobooks = this.audiobooks.map((book: Audiobook) => {
                    if (book.audiobookEncodedName === AudiobookEncodedName) {
                        book.trackEncodedPath = TrackEncodedPath;
                        book.timestamp = timestamp;
                    }
                    return book;
                });
            } else {
                console.log("set audiobookBookmark to", TrackEncodedPath, timestamp);
                this.audiobooks.push({
                    audiobookEncodedName: AudiobookEncodedName,
                    trackEncodedPath: TrackEncodedPath,
                    timestamp: 0
                });
            }
        },

        /**
         * @function clear and audiobook bookmark
         * @param AudiobookEncodedName
         */
        clearAudiobookBookmark(AudiobookEncodedName: string) {
            this.audiobooks = this.audiobooks.filter(
                (book: Audiobook) => book.audiobookEncodedName !== AudiobookEncodedName
            );
        }
    },
    getters: {
        getAudiobookBookmark: (state: PlayerState) => {
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
