<script setup lang="ts">
import { useAppStore } from "@/stores/app";
import axios from "axios";
import ShowError from "Components/Error/ShowError.vue";
import LoadingSpinner from "Components/Loading/LoadingSpinner.vue";
import { push } from "notivue";
import ListPlaylistSongs from "Views/Playlists/Playlist/ListPlaylistSongs.vue";
import PlaylistMetaData from "Views/Playlists/Playlist/PlaylistMetaData.vue";
import PlaylistTitle from "Views/Playlists/Playlist/PlaylistTitle.vue";
import { ref, watch } from "vue";
import { useRoute } from "vue-router";
const app = useAppStore();
const route = useRoute();
const playlist = ref(null);
const hasError = ref(false);
const fetchData = () => {
    app.loading = true;
    axios
        .get(`/api/playlists/${route.params.id}`)
        .then(response => {
            if (response.status === 200) {
                playlist.value = response.data;
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
</script>

<template>
    <section class="widget">
        <div class="loading-spinner__outer" v-if="app.loading">
            <loading-spinner :size="8" />
        </div>
        <show-error v-else-if="hasError && !app.loading" @refresh="fetchData()" />
        <div v-else class="playlist">
            <playlist-title :title="playlist.name" :cover="playlist?.cover" />
            <playlist-meta-data :playlist="playlist" />
            <list-playlist-songs :songs="playlist.songs" />
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
