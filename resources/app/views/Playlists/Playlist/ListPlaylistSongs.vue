<script setup lang="ts">
import { useAppStore } from "@/stores/appStore";
import { usePlaylistStore } from "@/stores/playlistStore";
import axios from "axios";
import { push } from "notivue";
import PlaylistSong from "Views/Playlists/Playlist/PlaylistSong.vue";
import { computed } from "vue";
import { VueDraggableNext as draggable } from "vue-draggable-next";
const pStore = usePlaylistStore();
const appStore = useAppStore();
const playlist = computed<Array<Playlist>>({
    get: () => pStore.detailedPlaylist,
    set: value => {
        pStore.detailedPlaylist = value;
    }
});
interface Playlist {
    id: string;
    sort: number;
    songs: Array<PlaylistSong>;
    name: string;
    duration: number;
    entries: number;
}
interface PlaylistSong {
    id: string;
    album: string;
    artist: string;
    createdAt: Date;
    duration: number;
    encodedPath: string;
    size: number;
    song: string;
    sort: number;
    updatedAt: Date;
    newSort: number;
}
const onListChange = () => {
    let count = 1;
    // identify changes and prepare for server
    const changes = playlist.value.songs
        .reverse() // highest sort value is the first item, so we need to reverse
        .map(item => {
            item.newSort = count;
            count++;
            return item;
        })
        .filter(item => item.newSort !== item.sort)
        .map(item => {
            return { id: item.id, sort: item.newSort };
        });
    appStore.loading = true;
    axios
        .post(`/api/playlists/${playlist.value.id}/sort`, { changes: changes })
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
            console.log("sort done");
            appStore.loading = false;
        });
};
</script>

<template>
    <div v-if="playlist.songs.length === 0">Noch keine Songs in dieser Playlist. Füge welche hinzu!</div>
    <div v-else class="widget">
        <draggable
            v-model="playlist.songs"
            group="playlist"
            @change="onListChange"
            class="playlists"
            handle=".playlist-song__drag-handle"
        >
            <playlist-song v-for="song in playlist.songs" :key="song.id" :id="song.id" />
        </draggable>
    </div>
</template>
