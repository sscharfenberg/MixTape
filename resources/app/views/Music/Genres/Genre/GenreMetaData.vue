<script setup lang="ts">
import { formatBytes, formatDecimals, formatSeconds } from "@/formatters/numbers";
import AppIcon from "Components/AppIcon/AppIcon.vue";
defineProps({
    genre: {
        type: Object,
        required: true
    }
});
</script>

<template>
    <ul class="details-metadata">
        <li v-if="genre.duration" v-tippy="{ content: 'Gesamte Laufzeit' }">
            <app-icon name="time" />
            {{ formatSeconds(genre.duration) }}
        </li>
        <li v-if="genre.numArtists" v-tippy="{ content: 'Anzahl Artists' }">
            <app-icon name="artist" />
            {{ formatDecimals(genre.numArtists) }}
        </li>
        <li v-if="genre.numSongs" v-tippy="{ content: 'Anzahl Songs' }">
            <app-icon name="music" />
            {{ formatDecimals(genre.numSongs) }}
        </li>
        <li v-if="genre.size" v-tippy="{ content: 'Dateigröße gesamt' }">
            <app-icon name="file" />
            {{ formatBytes(genre.size) }}
        </li>
    </ul>
</template>
