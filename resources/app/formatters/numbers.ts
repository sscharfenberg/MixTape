import { intervalToDuration } from "date-fns";

/**
 * @function format a decimal number into human-readable format
 * @param {number} num
 * @return {string}
 */
export function formatDecimals(num: number): string {
    return num.toLocaleString("de-DE", { style: "decimal" });
}

/**
 * Format bytes as human-readable text.
 *
 * @param bytes Number of bytes.
 * @param si True to use metric (SI) units, aka powers of 1000. False to use
 *           binary (IEC), aka powers of 1024.
 * @param dp Number of decimal places to display.
 *
 * @return {string}
 */
export function formatBytes(bytes: number, si = false, dp = 1): string {
    const thresh = si ? 1000 : 1024;

    if (Math.abs(bytes) < thresh) {
        return bytes + " B";
    }

    const units = si
        ? ["kB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"]
        : ["KiB", "MiB", "GiB", "TiB", "PiB", "EiB", "ZiB", "YiB"];
    let u = -1;
    const r = 10 ** dp;

    do {
        bytes /= thresh;
        ++u;
    } while (Math.round(Math.abs(bytes) * r) / r >= thresh && u < units.length - 1);

    return bytes.toFixed(dp) + " " + units[u];
}

/**
 * @function format a duration in seconds to human readable "6d 2h 55m 14s" format
 * @param {number} seconds
 * @return string
 */
export function formatSeconds(seconds: number): string {
    const d = intervalToDuration({ start: 0, end: seconds * 1000 });
    const dateArr = [];
    if (d.months && d.months > 0) dateArr.push(`${d.months}M`);
    if (d.days && d.days > 0) dateArr.push(`${d.days}d`);
    if (d.hours && d.hours > 0) dateArr.push(`${d.hours}h`);
    if (d.minutes && d.minutes > 0) dateArr.push(`${d.minutes}m`);
    if (d.seconds && d.seconds > 0) dateArr.push(`${d.seconds}s`);
    return dateArr.join(" ");
}
