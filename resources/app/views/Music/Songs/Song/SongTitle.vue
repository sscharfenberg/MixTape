<script setup lang="ts">
import PlayerAlbumNavigation from "./PlayerAlbumNavigation.vue";
defineProps({
    song: {
        type: Object,
        required: true
    }
});
</script>

<template>
    <header class="details-title">
        <div class="details-title__title">
            <h3>{{ song.name }}</h3>
            <player-album-navigation :nav="song.nav" />
        </div>
        <img v-if="song.cover && song.cover.length > 48" :src="song.cover" :alt="song.album.name" />
        <img v-else src="./missing-cover.jpg" alt="Cover fehlt!" v-tippy="{ content: 'Cover fehlt!' }" />
    </header>
</template>
