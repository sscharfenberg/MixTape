<script setup lang="ts">
import { usePlaylistStore } from "@/stores/playlistStore";
import PopOver from "Components/Popover/PopOver.vue";
import PlaylistExportM3U from "./PlaylistExportM3U.vue";
const playlistStore = usePlaylistStore();
const onClosePopover = () => {
    document.getElementById("exportPlaylist").hidePopover();
};
</script>

<template>
    <pop-over
        icon="download"
        label="Export"
        aria-label="Export als Datei zur Verwendung mit anderen Playern"
        reference="exportPlaylist"
    >
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
</template>
