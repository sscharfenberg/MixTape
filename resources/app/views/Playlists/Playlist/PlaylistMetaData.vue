<script setup lang="ts">
import { formatBytes, formatDecimals, formatSeconds } from "@/formatters/numbers";
import AppIcon from "Components/AppIcon/AppIcon.vue";
import { format } from "date-fns";

defineProps({
    playlist: {
        type: Object,
        required: true
    }
});
</script>

<template>
    <ul class="details-metadata">
        <li v-if="playlist.entries" v-tippy="{ content: 'Songs in der Playlist' }">
            <app-icon name="playlist_entry" />
            {{ formatDecimals(playlist.entries) }} Songs
        </li>
        <li v-if="playlist.duration" v-tippy="{ content: 'Laufzeit' }">
            <app-icon name="time" />
            {{ formatSeconds(playlist.duration) }}
        </li>
        <li v-if="playlist.size" v-tippy="{ content: 'Dateigröße' }">
            <app-icon name="file" />
            {{ formatBytes(playlist.size) }}
        </li>
        <li v-if="playlist.createdAt" v-tippy="{ content: 'Zeitpunkt der Erstellung' }">
            <app-icon name="datetime" />
            {{ format(playlist.createdAt, "dd.MM.yyyy HH:mm:ss") }}
        </li>
    </ul>
</template>
