<script setup lang="ts">
import AppButton from "Components/Form/Button/AppButton.vue";
import AutoplaySwitch from "Components/Player/AutoplaySwitch.vue";
import ShuffleSwitch from "Components/Player/ShuffleSwitch.vue";
// import { computed } from "vue";
// const playerStore = usePlayerStore();
// const songQueue = computed(() => {
//     if (playerStore.shuffle) return playerStore.shuffledQueue;
//     else return playerStore.sortedQueue;
// });
</script>

<template>
    <div class="player-navigation">
        <app-button icon="prev" />
        <app-button icon="play" />
        <app-button icon="next" />
        <autoplay-switch />
        <shuffle-switch />
    </div>
</template>
