<script setup lang="ts">
import { useAppStore } from "@/stores/appStore";
import { usePlayerStore } from "@/stores/playerStore";
import { usePlaylistStore } from "@/stores/playlistStore";
import { useQueueStore } from "@/stores/queueStore";
import axios from "axios";
import ShowError from "Components/Error/ShowError.vue";
import LoadingSpinner from "Components/Loading/LoadingSpinner.vue";
import { shuffleQueue } from "Components/Player/useSongQueue";
import { push } from "notivue";
import ListPlaylistSongs from "Views/Playlists/Playlist/ListPlaylistSongs.vue";
import PlaylistMetaData from "Views/Playlists/Playlist/PlaylistMetaData.vue";
import PlaylistPlaySection from "Views/Playlists/Playlist/PlaylistPlaySection.vue";
import PlaylistTitle from "Views/Playlists/Playlist/PlaylistTitle.vue";
import { onBeforeMount, ref, watch } from "vue";
import { useRoute } from "vue-router";
const app = useAppStore();
const playerStore = usePlayerStore();
const playlistStore = usePlaylistStore();
const queueStore = useQueueStore();
const route = useRoute();
const hasError = ref(false);
const currentTrackUrl = ref("");
const currentTrackName = ref("");
const loadingSong = ref(false);
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
                // start playing if autoplay is active
                if (playerStore.autoplay) onPlay(queueStore.getCurrentSongPath);
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
            console.log("fetchData xhr finished.");
        });
};
const onPlay = value => {
    loadingSong.value = true;
    console.log("got play emitted", value);
    axios
        .get(`/api/playlists/play/${value}`)
        .then(response => {
            currentTrackUrl.value = response.data.path;
            currentTrackName.value = response.data.name;
            queueStore.updateCurrentPath();
            queueStore.updateQueueIndex();
            playlistStore.setNowPlaying(value);
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
            loadingSong.value = false;
            console.log("play xhr finished.");
        });
};
const onEnded = () => {
    console.log("ended");
};
watch(() => route.params.id, fetchData, { immediate: true });
onBeforeMount(() => {
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
            <playlist-play-section
                v-if="playlistStore.detailedPlaylist.songs.filter(s => s.nowPlaying).length > 0"
                :current-track-url="currentTrackUrl"
                :current-track-name="currentTrackName"
                :loading="loadingSong"
                @ended="onEnded"
            />
            <playlist-meta-data :playlist="playlistStore.detailedPlaylist" />
            <list-playlist-songs :songs="playlistStore.detailedPlaylist.songs" @play="onPlay" />
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
