import { push } from "notivue";

/**
 * TS interfaces
 */
interface FileObject {
    track: number;
    disc: number;
    discs: number;
    discTracks: number;
    name: string;
}
interface Toast {
    title: string;
    message: string;
}

/**
 * @function nowPlaying function for songs and tracks
 * @param file<FileObject>
 */
export const nowPlaying = (file: FileObject): void => {
    const toast: Toast = {
        title: "Wird gespielt:",
        message: ""
    };
    if (file.discs > 1) {
        toast.message += `Disc ${file.disc}/${file.discs} - `;
    }
    if (file.discTracks > 1) {
        const numDigits = file.discTracks.toString().length;
        const track = file.track.toString().padStart(numDigits, "0");
        toast.message += `Track ${track}/${file.discTracks} - `;
    }
    toast.message += file.name;
    push.info(toast);
};
