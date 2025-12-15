// https://en.wikipedia.org/wiki/Fisher%E2%80%93Yates_shuffle
export const shuffleQueue = (queue: Array<string>): Array<string> => {
    const arr = queue.slice(0);
    let currentIndex = arr.length;
    // While there remain elements to shuffle...
    while (currentIndex != 0) {
        // Pick a remaining element...
        const randomIndex = Math.floor(Math.random() * currentIndex);
        currentIndex--;
        // And swap it with the current element.
        [arr[currentIndex], arr[randomIndex]] = [arr[randomIndex], arr[currentIndex]];
    }
    return arr;
};
