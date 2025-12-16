<script setup lang="ts">
import { useAppStore } from "@/stores/appStore";
import { usePlayerStore } from "@/stores/playerStore";
import { usePlaylistStore } from "@/stores/playlistStore";
import { useQueueStore } from "@/stores/queueStore";
import axios from "axios";
import ShowError from "Components/Error/ShowError.vue";
import LoadingSpinner from "Components/Loading/LoadingSpinner.vue";
import AudioPlayer from "Components/Player/AudioPlayer.vue";
import { shuffleQueue } from "Components/Player/useSongQueue";
import { push } from "notivue";
import ListPlaylistSongs from "Views/Playlists/Playlist/ListPlaylistSongs.vue";
import PlaylistMetaData from "Views/Playlists/Playlist/PlaylistMetaData.vue";
import PlaylistNowPlaying from "Views/Playlists/Playlist/PlaylistNowPlaying.vue";
import PlaylistTitle from "Views/Playlists/Playlist/PlaylistTitle.vue";
import { onUnmounted, ref, watch } from "vue";
import { useRoute } from "vue-router";
const app = useAppStore();
const playerStore = usePlayerStore();
const playlistStore = usePlaylistStore();
const queueStore = useQueueStore();
const route = useRoute();
const hasError = ref(false);
const currentTrackUrl = ref("");
const currentTrackName = ref("");
const fetchData = () => {
    app.loading = true;
    hasError.value = false;
    axios
        .get(`/api/playlists/${route.params.id}`)
        .then(response => {
            if (response.status === 200) {
                playlistStore.detailedPlaylist = response.data;
                const serverQueue = response.data.songs.map(song => song.encodedPath);
                queueStore.sortedQueue = serverQueue;
                queueStore.shuffledQueue = shuffleQueue(serverQueue);
            }
        })
        .catch(error => {
            console.error(error);
            push.error({
                title: error.code,
                message: error.response.data.message || error.message
            });
            hasError.value = true;
        })
        .finally(() => {
            app.loading = false;
        });
};
const onPlay = value => {
    axios
        .get(`/api/playlists/play/${value}`)
        .then(response => {
            currentTrackUrl.value = response.data.path;
            currentTrackName.value = response.data.name;
        })
        .catch(error => {
            console.error(error);
            push.error({
                title: error.code,
                message: error.response.data.message || error.message
            });
            hasError.value = true;
        })
        .finally(() => {
            console.log("xhr finished.");
        });
};
const onEnded = () => {
    console.log("ended");
};
watch(() => route.params.id, fetchData, { immediate: true });
onUnmounted(() => {
    playlistStore.detailedPlaylist = {};
    queueStore.reset();
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
                @play="onPlay"
            />
            <audio-player
                v-if="currentTrackUrl && currentTrackName"
                :src="currentTrackUrl"
                :title="currentTrackName"
                :autoplay="playerStore.autoplay"
                @player-ended="onEnded"
            />
            <playlist-now-playing v-if="currentTrackUrl && currentTrackName" />
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
