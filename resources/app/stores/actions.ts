export const actions = {
    /**
     * @function set bookmark of audiobook
     * @param AudiobookEncodedName
     * @param TrackEncodedPath
     * @param timestamp
     */
    setAudiobookBookmark(AudiobookEncodedName: string, TrackEncodedPath: string, timestamp: number) {
        console.log("set audiobookBookmark for ", TrackEncodedPath, timestamp);
        // do we already have a bookmark for this book?
        if (this.audiobooks.find(book => book.audiobookEncodedName === AudiobookEncodedName)) {
            console.log("update audiobookBookmark to", TrackEncodedPath, timestamp);
            this.audiobooks = this.audiobooks.map(book => {
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
        this.audiobooks = this.audiobooks.filter(book => book.audiobookEncodedName !== AudiobookEncodedName);
    }
};
