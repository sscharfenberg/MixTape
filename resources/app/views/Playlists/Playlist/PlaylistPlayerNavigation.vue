<script setup lang="ts">
import { usePlayerStore } from "@/stores/playerStore";
import { usePlaylistStore } from "@/stores/playlistStore";
import { useQueueStore } from "@/stores/queueStore";
import axios from "axios";
import AppButton from "Components/Form/Button/AppButton.vue";
import AutoplaySwitch from "Components/Player/AutoplaySwitch.vue";
import ShuffleSwitch from "Components/Player/ShuffleSwitch.vue";
import { shuffleQueue } from "Components/Player/useSongQueue";
import PopOver from "Components/Popover/PopOver.vue";
import { push } from "notivue";
import { computed, ref } from "vue";
import PlaylistExportM3U from "./PlaylistExportM3U.vue";
const sortProcessing = ref(false);
const queueStore = useQueueStore();
const playerStore = usePlayerStore();
const playlistStore = usePlaylistStore();
const songQueue = computed(() => {
    if (playerStore.shuffle) return queueStore.shuffledQueue;
    else return queueStore.sortedQueue;
});
const emit = defineEmits(["play"]);
const getSongPath = (value: number) => {
    return songQueue.value[value];
};
const onPlay = (value: number) => {
    emit("play", getSongPath(value));
};
const isPlaying = computed(() => {
    return playlistStore.detailedPlaylist.songs.filter(song => song.nowPlaying).length > 0;
});
const onStop = () => {
    queueStore.currentQueuePath = "";
    queueStore.currentQueueIndex = 0;
    playlistStore.setNowPlaying("");
};
const onNext = () => {
    const queueLength = queueStore.sortedQueue.length;
    queueStore.currentQueueIndex += 1;
    if (queueStore.currentQueueIndex >= queueLength) {
        queueStore.currentQueueIndex = 0;
    }
    onPlay(queueStore.currentQueueIndex);
};
const onPrev = () => {
    queueStore.currentQueueIndex -= 1;
    if (queueStore.currentQueueIndex < 0) {
        queueStore.currentQueueIndex = queueStore.sortedQueue.length - 1; // index starts @ 0
    }
    onPlay(queueStore.currentQueueIndex);
};
const onSort = () => {
    sortProcessing.value = true;
    axios
        .post(`/api/playlists/${playlistStore.detailedPlaylist.id}/autosort`)
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
const onClosePopover = () => {
    document.getElementById("exportPlaylist").hidePopover();
};
</script>

<template>
    <div class="player-navigation">
        <app-button icon="prev" @click="onPrev" aria-label="Voriger Song" />
        <app-button
            v-if="!isPlaying"
            icon="play"
            @click="onPlay(queueStore.currentQueueIndex)"
            aria-label="Abspielen"
        />
        <app-button v-if="isPlaying" icon="stop" @click="onStop" aria-label="Stoppen" />
        <app-button icon="next" @click="onNext" aria-label="Nächster Song" />
        <autoplay-switch />
        <shuffle-switch />
        <app-button icon="sort" text="Sort Playlist" @click="onSort" :loading="sortProcessing" />
        <pop-over icon="download" reference="exportPlaylist">
            <ul class="popover-list">
                <li>
                    <playlist-export-m3-u
                        :name="playlistStore.detailedPlaylist.name"
                        @closepopover="onClosePopover"
                        export-type="simple"
                    />
                </li>
                <li>
                    <playlist-export-m3-u
                        :name="playlistStore.detailedPlaylist.name"
                        @closepopover="onClosePopover"
                        export-type="extended"
                    />
                </li>
            </ul>
        </pop-over>
    </div>
</template>
