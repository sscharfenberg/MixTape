<script setup lang="ts">
import { usePlayerStore } from "@/stores/playerStore";
import { usePlaylistStore } from "@/stores/playlistStore";
import { useQueueStore } from "@/stores/queueStore";
import AppButton from "Components/Form/Button/AppButton.vue";
import AutoplaySwitch from "Components/Player/AutoplaySwitch.vue";
import ShuffleSwitch from "Components/Player/ShuffleSwitch.vue";
import { computed } from "vue";
const queueStore = useQueueStore();
const playerStore = usePlayerStore();
const playlistStore = usePlaylistStore();
const songQueue = computed(() => {
    if (playerStore.shuffle) return queueStore.shuffledQueue;
    else return queueStore.sortedQueue;
});
const emit = defineEmits(["play"]);
const getSongPath = (value: number) => {
    // TODO: index out of bounds
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
        <app-button icon="prev" @click="onPrev" />
        <app-button v-if="!isPlaying" icon="play" @click="onPlay(queueStore.currentQueueIndex)" />
        <app-button v-if="isPlaying" icon="stop" @click="onStop" />
        <app-button icon="next" @click="onNext" />
        <autoplay-switch />
        <shuffle-switch />
    </div>
</template>
