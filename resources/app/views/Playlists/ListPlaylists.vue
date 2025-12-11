<script setup lang="ts">
import { formatDecimals, formatSeconds } from "@/formatters/numbers";
import { useAppStore } from "@/stores/app";
import { usePlaylistStore } from "@/stores/playlists";
import AppIcon from "Components/AppIcon/AppIcon.vue";
import axios from "axios";
import { push } from "notivue";
import { computed } from "vue";
import { VueDraggableNext as draggable } from "vue-draggable-next";
const app = useAppStore();
const pStore = usePlaylistStore();
const playlists = computed({
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
    app.loading = true;
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
            app.loading = false;
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
        <div class="playlist" v-for="list in playlists" :key="list.id">
            <div class="drag-handle"><app-icon name="drag" /></div>
            <router-link :to="{ name: 'playlist', params: { id: list.id } }">
                <span class="title">
                    {{ list.name }}
                </span>
                <span class="meta">
                    <span v-if="list.duration" v-tippy="{ content: 'Laufzeit: ' + formatSeconds(list.duration) }">
                        <app-icon name="time" />
                        {{ formatSeconds(list.duration) }}
                    </span>
                    <span class="entries" v-tippy="{ content: formatDecimals(list.entries) + ' Einträge' }">
                        <app-icon name="playlist_entry" />
                        {{ formatDecimals(list.entries) }}
                    </span>
                </span>
            </router-link>
        </div>
    </draggable>
</template>

<style scoped lang="scss">
@use "sass:map";
@use "Abstracts/colors" as c;
@use "Abstracts/sizes" as s;

.playlists {
    display: flex;
    flex-direction: column;

    gap: 0.5lh;
}

.playlist {
    display: flex;
    align-items: center;

    background-color: map.get(c.$main, "playlist-background-row1");
    color: map.get(c.$main, "playlist-surface-row1");

    text-decoration: none;

    &:first-of-type {
        border-top-left-radius: map.get(s.$main, "playlist-radius");
        border-top-right-radius: map.get(s.$main, "playlist-radius");
    }

    &:last-of-type {
        border-bottom-right-radius: map.get(s.$main, "playlist-radius");
        border-bottom-left-radius: map.get(s.$main, "playlist-radius");
    }

    &:hover {
        background-color: map.get(c.$main, "playlist-background-row1-hover");
    }

    .drag-handle {
        padding: map.get(s.$main, "playlist-padding");

        cursor: move;
    }

    > a {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-grow: 1;

        padding: map.get(s.$main, "playlist-padding");
        padding-left: 0;

        color: map.get(c.$main, "playlist-surface-row1");

        text-decoration: none;
    }

    &:nth-of-type(even) {
        background-color: map.get(c.$main, "playlist-background-row2");
        color: map.get(c.$main, "playlist-surface-row2");

        > a {
            color: map.get(c.$main, "playlist-surface-row2");
        }

        &:hover {
            background-color: map.get(c.$main, "playlist-background-row2-hover");
        }
    }

    > .title,
    > .meta {
        display: flex;
        align-items: center;

        gap: 1ch;
    }
}
</style>
