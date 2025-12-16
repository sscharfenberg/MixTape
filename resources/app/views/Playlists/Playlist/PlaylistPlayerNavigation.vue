<script setup lang="ts">
import { usePlayerStore } from "@/stores/playerStore";
import { useQueueStore } from "@/stores/queueStore";
import AppButton from "Components/Form/Button/AppButton.vue";
import AutoplaySwitch from "Components/Player/AutoplaySwitch.vue";
import ShuffleSwitch from "Components/Player/ShuffleSwitch.vue";
import { computed } from "vue";
const queueStore = useQueueStore();
const playerStore = usePlayerStore();
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
</script>

<template>
    <div class="player-navigation">
        <app-button icon="prev" />
        <app-button icon="play" @click="onPlay(queueStore.currentQueueIndex)" />
        <app-button icon="next" />
        <autoplay-switch />
        <shuffle-switch />
    </div>
</template>
