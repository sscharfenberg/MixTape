<script setup lang="ts">
defineProps({
    songs: {
        type: Array,
        required: true
    }
});
</script>

<template>
    <div v-if="songs.length === 0">Noch keine Songs in dieser Playlist. Füge welche hinzu!</div>
    <div v-else>
        {{ songs }}
    </div>
</template>
