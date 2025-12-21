<script setup lang="ts">
import { usePlayerStore } from "@/stores/playerStore";
import { usePlaylistStore } from "@/stores/playlistStore";
import { useQueueStore } from "@/stores/queueStore";
import AppButton from "Components/Form/Button/AppButton.vue";
import AutoplaySwitch from "Components/Player/AutoplaySwitch.vue";
import ShuffleSwitch from "Components/Player/ShuffleSwitch.vue";
import { computed } from "vue";
import PlaylistPlayerNavigationCleanup from "./PlaylistPlayerNavigationCleanup.vue";
import PlaylistPlayerNavigationExport from "./PlaylistPlayerNavigationExport.vue";
import PlaylistPlayerNavigationSort from "./PlaylistPlayerNavigationSort.vue";
const queueStore = useQueueStore();
const playerStore = usePlayerStore();
const playlistStore = usePlaylistStore();
defineProps({
    playlistId: {
        type: String,
        required: true
    }
});
const songQueue = computed(() => {
    if (playerStore.shuffle) return queueStore.shuffledQueue;
    else return queueStore.sortedQueue;
});
const emit = defineEmits(["play"]);
const getSongPath = (value: number) => {
    return songQueue.value[value];
};
const onPlay = (value: number) => {
    emit("play", getSongPath(value));
};
const isPlaying = computed(() => {
    return playlistStore.detailedPlaylist.songs.filter(song => song.nowPlaying).length > 0;
});
const onStop = () => {
    queueStore.currentQueuePath = "";
    queueStore.currentQueueIndex = 0;
    playlistStore.setNowPlaying("");
};
const onNext = () => {
    const queueLength = queueStore.sortedQueue.length;
    queueStore.currentQueueIndex += 1;
    if (queueStore.currentQueueIndex >= queueLength) {
        queueStore.currentQueueIndex = 0;
    }
    onPlay(queueStore.currentQueueIndex);
};
const onPrev = () => {
    queueStore.currentQueueIndex -= 1;
    if (queueStore.currentQueueIndex < 0) {
        queueStore.currentQueueIndex = queueStore.sortedQueue.length - 1; // index starts @ 0
    }
    onPlay(queueStore.currentQueueIndex);
};
</script>

<template>
    <div class="player-navigation">
        <app-button icon="prev" @click="onPrev" aria-label="Voriger Song" />
        <app-button
            v-if="!isPlaying"
            icon="play"
            @click="onPlay(queueStore.currentQueueIndex)"
            aria-label="Abspielen"
        />
        <app-button v-if="isPlaying" icon="stop" @click="onStop" aria-label="Stoppen" />
        <app-button icon="next" @click="onNext" aria-label="Nächster Song" />
        <autoplay-switch />
        <shuffle-switch />
        <playlist-player-navigation-sort :playlist-id="playlistId" />
        <playlist-player-navigation-export />
        <playlist-player-navigation-cleanup :playlist-id="playlistId" />
    </div>
</template>
