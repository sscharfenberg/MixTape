<script setup lang="ts">
import PlayerAudiobookNavigation from "./PlayerAudiobookNavigation.vue";
const emit = defineEmits(["play"]);
defineProps({
    title: {
        type: String,
        required: true
    },
    tracks: {
        type: Array,
        required: true
    },
    cover: String,
    bookEncodedName: {
        type: String,
        required: true
    }
});
const onPlay = (value: string) => {
    emit("play", value);
};
</script>

<template>
    <header class="details-title">
        <div class="details-title__title">
            <h3>{{ title }}</h3>
            <player-audiobook-navigation :tracks="tracks" :book-encoded-name="bookEncodedName" @play="onPlay" />
        </div>
        <div v-if="cover && cover.length > 48" class="cover">
            <img :src="cover" :alt="title" />
        </div>
        <img v-else src="./missing-cover.jpg" alt="Cover fehlt!" v-tippy="{ content: 'Cover fehlt!' }" />
    </header>
</template>
