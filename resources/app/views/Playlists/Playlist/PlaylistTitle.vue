<script setup lang="ts">
import PlaylistPlayerNavigation from "./PlaylistPlayerNavigation.vue";

defineProps({
    title: {
        type: String,
        required: true
    },
    cover: String
});
const emit = defineEmits(["play"]);
const onPlay = (value: string) => {
    emit("play", value);
};
</script>

<template>
    <header class="details-title">
        <div class="details-title__title">
            <h3>{{ title }}</h3>
            <playlist-player-navigation @play="onPlay" />
        </div>
        <div v-if="cover && cover.length > 48" class="cover">
            <img :src="cover" :alt="title" />
        </div>
        <img v-else src="./missing-cover.jpg" alt="Cover fehlt!" v-tippy="{ content: 'Cover fehlt!' }" class="cover" />
    </header>
</template>
