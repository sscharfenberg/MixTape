<script setup lang="ts">
import { usePlaylistStore } from "@/stores/playlistStore";
import { useQueueStore } from "@/stores/queueStore";
import axios from "axios";
import AppButton from "Components/Form/Button/AppButton.vue";
import { shuffleQueue } from "Components/Player/useSongQueue";
import { push } from "notivue";
import { ref } from "vue";
const cleanupProcessing = ref(false);
const playlistStore = usePlaylistStore();
const queueStore = useQueueStore();
const props = defineProps({
    playlistId: {
        type: String,
        required: true
    }
});
const onCleanup = () => {
    cleanupProcessing.value = true;
    axios
        .post(`/api/playlists/${props.playlistId}/cleanup`)
        .then(response => {
            const res = response.data;
            console.log(res);
            const serverQueue = res.playlist.songs.map(song => song.encodedPath);
            playlistStore.detailedPlaylist = res.playlist;
            queueStore.sortedQueue = serverQueue;
            queueStore.shuffledQueue = shuffleQueue(serverQueue);
            queueStore.updateCurrentPath();
            queueStore.updateQueueIndex();
            playlistStore.setNowPlaying("");
            let message = "Es wurden keine toten Einträge gefunden.";
            if (res.removedEntries.length > 0) {
                message =
                    "Die folgenden Songs wurden entfernt:\n" +
                    response.data.removedEntries.map(entry => `👉 ${entry.artist} - ${entry.song}`).join("\n");
            }
            push.success({
                title: "Aufräumen erfolgreich.",
                message: message,
                duration: res.removedEntries.length > 0 ? Infinity : 5000
            });
        })
        .catch(error => {
            console.error(error);
            push.error({
                title: error.code,
                message: error.message
            });
        })
        .finally(() => {
            cleanupProcessing.value = false;
            console.log("cleanup xhr finished.");
        });
};
</script>

<template>
    <app-button
        icon="cleanup"
        text="Aufräumen"
        v-tippy="{
            content: 'entfernt Einträge bei denen der Song nicht mehr existiert (umbenannt in Samba share)'
        }"
        :loading="cleanupProcessing"
        @click="onCleanup"
    />
</template>
