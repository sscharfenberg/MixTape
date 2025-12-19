<script setup lang="ts">
import AppIcon from "Components/AppIcon/AppIcon.vue";
import AutoplaySwitch from "Components/Player/AutoplaySwitch.vue";
import { nowPlaying } from "Components/Player/useNowPlaying";
defineProps({
    nav: {
        type: Object,
        required: true
    }
});
const onNavigation = song => {
    nowPlaying(song);
};
</script>

<template>
    <div class="player-navigation">
        <router-link
            :disabled="nav.prev?.encodedPath ? null : true"
            :to="{ name: 'song', params: { id: nav.prev?.encodedPath } }"
            v-tippy="{ content: `${nav.prev?.track} ${nav.prev?.name}` }"
            class="btn default"
            @click.prevent="onNavigation(nav.prev)"
            aria-label="Voriger Song"
        >
            <app-icon name="prev" />
        </router-link>
        <router-link
            :disabled="nav.next?.encodedPath ? null : true"
            :to="{ name: 'song', params: { id: nav.next?.encodedPath } }"
            v-tippy="{ content: `${nav.next?.track} ${nav.next?.name}` }"
            class="btn default"
            @click.prevent="onNavigation(nav.next)"
            aria-label="Nächster Song"
        >
            <app-icon name="next" />
        </router-link>
        <autoplay-switch />
    </div>
</template>
