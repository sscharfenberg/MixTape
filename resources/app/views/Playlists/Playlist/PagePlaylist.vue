<script setup lang="ts">
import { useAppStore } from "@/stores/appStore";
import { usePlaylistStore } from "@/stores/playlistStore";
import axios from "axios";
import ShowError from "Components/Error/ShowError.vue";
import LoadingSpinner from "Components/Loading/LoadingSpinner.vue";
import { push } from "notivue";
import ListPlaylistSongs from "Views/Playlists/Playlist/ListPlaylistSongs.vue";
import PlaylistMetaData from "Views/Playlists/Playlist/PlaylistMetaData.vue";
import PlaylistTitle from "Views/Playlists/Playlist/PlaylistTitle.vue";
import { onUnmounted, ref, watch } from "vue";
import { useRoute } from "vue-router";
const app = useAppStore();
const pStore = usePlaylistStore();
const route = useRoute();
const hasError = ref(false);
const fetchData = () => {
    app.loading = true;
    axios
        .get(`/api/playlists/${route.params.id}`)
        .then(response => {
            if (response.status === 200) {
                pStore.detailedPlaylist = response.data;
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
    pStore.detailedPlaylist = {};
});
</script>

<template>
    <section class="widget">
        <div class="loading-spinner__outer" v-if="app.loading">
            <loading-spinner :size="8" />
        </div>
        <show-error v-else-if="hasError && !app.loading" @refresh="fetchData()" />
        <div v-else class="playlist">
            <playlist-title :title="pStore.detailedPlaylist.name" :cover="pStore.detailedPlaylist.cover" />
            <playlist-meta-data :playlist="pStore.detailedPlaylist" />
            <list-playlist-songs :songs="pStore.detailedPlaylist.songs" />
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
