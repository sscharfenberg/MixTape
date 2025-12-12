<script setup lang="ts">
import { useAppStore } from "@/stores/app";
import { usePlaylistStore } from "@/stores/playlists";
import ListPlaylistsItem from "Views/Playlists/ListPlaylistsItem.vue";
import axios from "axios";
import { push } from "notivue";
import { computed } from "vue";
import { VueDraggableNext as draggable } from "vue-draggable-next";
const app = useAppStore();
const pStore = usePlaylistStore();
const playlists = computed<Array<Playlist>>({
    get: () => pStore.playlists,
    set: value => {
        pStore.playlists = value;
    }
});
interface Playlist {
    id: string;
    sort: number;
    newSort: number;
    name: string;
    duration: number;
    entries: number;
}
const onListChange = () => {
    let count = 1;
    // identify changes and prepare for server
    const changes = playlists.value
        .map(item => {
            item.newSort = count;
            count++;
            return item;
        })
        .filter(item => item.newSort !== item.sort)
        .map(item => {
            return { id: item.id, sort: item.newSort };
        });
    // app.loading = true;
    axios
        .post(`/api/playlists/sort`, { changes: changes })
        .then(response => {
            if (response.status === 200) {
                playlists.value = response.data;
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
            console.log("done");
            // app.loading = false;
        });
};
</script>

<template>
    <div v-if="!app.loading && !app.error && playlists?.length === 0">Keine Playlists gefunden. Leg los!</div>
    <draggable
        v-else
        v-model="playlists"
        group="playlists"
        @change="onListChange"
        class="playlists"
        handle=".drag-handle"
    >
        <list-playlists-item v-for="list in playlists" :key="list.id" :p-id="list.id" />
    </draggable>
</template>
