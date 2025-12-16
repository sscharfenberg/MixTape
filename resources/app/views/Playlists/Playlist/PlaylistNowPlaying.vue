<script setup lang="ts">
import { usePlayerStore } from "@/stores/playerStore";
import { usePlaylistStore } from "@/stores/playlistStore";
import { useQueueStore } from "@/stores/queueStore";
import AppIcon from "Components/AppIcon/AppIcon.vue";
import { computed } from "vue";
const queueStore = useQueueStore();
const playerStore = usePlayerStore();
const playlistStore = usePlaylistStore();
const currentSongPath = computed(() => {
    const idx = queueStore.currentQueueIndex;
    if (playerStore.shuffle) return queueStore.shuffledQueue[idx];
    return queueStore.sortedQueue[idx];
});
const currentSongData = computed(() => {
    return playlistStore.detailedPlaylist.songs.find(song => song.encodedPath === currentSongPath.value);
});
</script>

<template>
    <ul class="now-playing">
        <li>
            <app-icon name="music" />
            <strong>{{ currentSongData.song }}</strong>
        </li>
        <li>
            <app-icon name="artist" />
            {{ currentSongData.artist }}
        </li>
        <li>
            <app-icon name="album" />
            {{ currentSongData.album }}
        </li>
    </ul>
</template>

<style lang="scss" scoped>
@use "sass:map";
@use "Abstracts/colors" as c;
@use "Abstracts/sizes" as s;
@use "Abstracts/shadows" as sh;

.now-playing {
    display: flex;
    align-items: center;
    flex-wrap: wrap;

    padding: map.get(s.$main, "playlist-now-playing-padding");
    border: map.get(s.$main, "player-border") solid map.get(c.$main, "player-border") !important;
    margin: 0;
    gap: 1.5ch;

    background-color: map.get(c.$main, "player-background") !important;
    color: map.get(c.$main, "player-surface") !important;

    list-style: none;
    border-radius: map.get(s.$main, "player-radius") !important;

    box-shadow: map.get(sh.$main, "player");

    > li {
        display: flex;
        align-items: center;

        padding: map.get(s.$main, "playlist-now-playing-item-padding");
        gap: 0.5ch;
    }
}
</style>
