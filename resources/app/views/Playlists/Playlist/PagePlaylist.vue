<script setup lang="ts">
import { useAppStore } from "@/stores/appStore";
import { usePlayerStore } from "@/stores/playerStore";
import { usePlaylistStore } from "@/stores/playlistStore";
import axios from "axios";
import ShowError from "Components/Error/ShowError.vue";
import LoadingSpinner from "Components/Loading/LoadingSpinner.vue";
import { shuffleQueue } from "Components/Player/useSongQueue";
import { push } from "notivue";
import ListPlaylistSongs from "Views/Playlists/Playlist/ListPlaylistSongs.vue";
import PlaylistMetaData from "Views/Playlists/Playlist/PlaylistMetaData.vue";
import PlaylistTitle from "Views/Playlists/Playlist/PlaylistTitle.vue";
import { onUnmounted, ref, watch } from "vue";
import { useRoute } from "vue-router";
const app = useAppStore();
const playlistStore = usePlaylistStore();
const playerStore = usePlayerStore();
const route = useRoute();
const hasError = ref(false);
const fetchData = () => {
    app.loading = true;
    axios
        .get(`/api/playlists/${route.params.id}`)
        .then(response => {
            if (response.status === 200) {
                playlistStore.detailedPlaylist = response.data;
                const serverQueue = response.data.songs.map(song => song.encodedPath);
                playerStore.sortedQueue = serverQueue;
                playerStore.shuffledQueue = shuffleQueue(serverQueue);
            }
        })
        .catch(error => {
            console.error(error);
            push.error({
                title: error.code,
                message: error.response.data.message || error.message
            });
        })
        .finally(() => {
            app.loading = false;
        });
};
watch(() => route.params.id, fetchData, { immediate: true });
onUnmounted(() => {
    playlistStore.detailedPlaylist = {};
});
</script>

<template>
    <section class="widget">
        <div class="loading-spinner__outer" v-if="app.loading">
            <loading-spinner :size="8" />
        </div>
        <show-error v-else-if="hasError && !app.loading" @refresh="fetchData()" />
        <div v-else class="playlist">
            <playlist-title
                :title="playlistStore.detailedPlaylist.name"
                :cover="playlistStore.detailedPlaylist.cover"
            />
            <playlist-meta-data :playlist="playlistStore.detailedPlaylist" />
            <list-playlist-songs :songs="playlistStore.detailedPlaylist.songs" />
        </div>
    </section>
</template>

<style lang="scss" scoped>
.playlist {
    display: flex;
    flex-direction: column;

    gap: 2ch;
}
</style>
