<script setup lang="ts">
import { usePlayerStore } from "@/stores/playerStore";
import AppIcon from "Components/AppIcon/AppIcon.vue";
import { shuffleQueue } from "Components/Player/useSongQueue";
import { computed } from "vue";
const playerStore = usePlayerStore();
const shuffle = computed({
    get: () => playerStore.shuffle,
    set: isShuffle => {
        playerStore.shuffle = isShuffle;
        if (isShuffle) {
            playerStore.shuffledQueue = shuffleQueue(playerStore.sortedQueue); // this might cause problems.
        }
    }
});
</script>

<template>
    <div class="toggle-switch" v-tippy="{ content: 'Toggle Shuffle' }">
        <input
            class="toggle-switch__input"
            type="checkbox"
            name="autoplay"
            id="shuffleSwitch"
            value="on"
            v-model="shuffle"
        />
        <label class="toggle-switch__label" for="shuffleSwitch">
            <span class="toggle-switch__knob">
                <app-icon name="shuffle" class="toggle-switch--on" />
                <app-icon name="shuffle_off" class="toggle-switch--off" />
            </span>
        </label>
    </div>
</template>
