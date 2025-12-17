<script setup lang="ts">
import { usePlayerStore } from "@/stores/playerStore";
import LoadingSpinner from "Components/Loading/LoadingSpinner.vue";
import AudioPlayer from "Components/Player/AudioPlayer.vue";
import PlaylistNowPlaying from "Views/Playlists/Playlist/PlaylistNowPlaying.vue";
const playerStore = usePlayerStore();
defineProps({
    currentTrackUrl: {
        type: String,
        default: ""
    },
    currentTrackName: {
        type: String,
        default: ""
    },
    loading: {
        type: Boolean,
        default: false
    }
});
const emit = defineEmits(["ended"]);
const onEnded = () => {
    emit("ended");
};
</script>

<template>
    <section class="playlist-player">
        <loading-spinner v-if="loading" text="Lade Song ..." />
        <audio-player
            v-if="!loading && currentTrackUrl && currentTrackName"
            :src="currentTrackUrl"
            :title="currentTrackName"
            :autoplay="playerStore.autoplay"
            @player-ended="onEnded"
        />
        <playlist-now-playing v-if="!loading && currentTrackUrl && currentTrackName" />
    </section>
</template>

<style lang="scss" scoped>
.playlist-player {
    display: flex;
    flex-direction: column;

    gap: 2ch;
}
</style>
