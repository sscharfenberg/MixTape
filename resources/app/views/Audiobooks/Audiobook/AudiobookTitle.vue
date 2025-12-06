<script setup lang="ts">
import PlayerAudiobookNavigation from "Components/Player/PlayerAudiobookNavigation.vue";
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
</script>

<template>
    <header class="details-title">
        <div class="details-title__title">
            <h3>{{ title }}</h3>
            <player-audiobook-navigation
                :nav="{}"
                :tracks="tracks"
                :book-encoded-name="bookEncodedName"
                @play="$emit('play', value)"
            />
        </div>
        <img v-if="cover && cover.length > 48" :src="cover" :alt="title" />
        <img v-else src="./missing-cover.jpg" alt="Cover fehlt!" v-tippy="{ content: 'Cover fehlt!' }" />
    </header>
</template>
