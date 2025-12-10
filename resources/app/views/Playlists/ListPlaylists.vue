<script setup lang="ts">
defineProps({
    playlists: {
        type: Array,
        default: []
    }
});
</script>

<template>
    <nav class="playlists">
        <router-link
            v-for="playlist in playlists"
            :key="playlist.id"
            :to="{ name: 'playlist', params: { id: playlist.id } }"
        >
            {{ playlist }}
        </router-link>
    </nav>
</template>

<style scoped lang="scss">
.playlists {
    display: flex;
    flex-direction: column;

    gap: 1lh;
}

a {
    display: block;
}
</style>
