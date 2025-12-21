<script setup lang="ts">
import { usePlaylistStore } from "@/stores/playlistStore";
import { useQueueStore } from "@/stores/queueStore";
import axios from "axios";
import AppButton from "Components/Form/Button/AppButton.vue";
import { shuffleQueue } from "Components/Player/useSongQueue";
import { push } from "notivue";
import { ref } from "vue";
const props = defineProps({
    playlistId: {
        type: String,
        required: true
    }
});
const queueStore = useQueueStore();
const playlistStore = usePlaylistStore();
const sortProcessing = ref(false);
const onSort = () => {
    sortProcessing.value = true;
    axios
        .post(`/api/playlists/${props.playlistId}/autosort`)
        .then(response => {
            const serverQueue = response.data.songs.map(song => song.encodedPath);
            playlistStore.detailedPlaylist = response.data;
            queueStore.sortedQueue = serverQueue;
            queueStore.shuffledQueue = shuffleQueue(serverQueue);
            queueStore.updateCurrentPath();
            queueStore.updateQueueIndex();
            playlistStore.setNowPlaying("");
        })
        .catch(error => {
            console.error(error);
            push.error({
                title: error.code,
                message: error.response.data.message || error.message
            });
        })
        .finally(() => {
            sortProcessing.value = false;
            console.log("sort xhr finished.");
        });
};
</script>

<template>
    <app-button
        icon="sort"
        text="Sort Playlist"
        @click="onSort"
        :loading="sortProcessing"
        v-tippy="{ content: 'Sortiert Einträge nach Pfad' }"
    />
</template>
