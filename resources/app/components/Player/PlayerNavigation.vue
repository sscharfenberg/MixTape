<script setup lang="ts">
import { router } from "@/router";
import AppButton from "Components/Button/AppButton.vue";
import AutoplaySwitch from "./AutoplaySwitch.vue";
const props = defineProps({
    nav: {
        type: Object,
        required: true
    }
});
const onNavigate = async direction => {
    await router.push({
        to: "song",
        params: {
            id: props.nav[direction].encodedPath
        }
    });
};
</script>

<template>
    <div class="song-buttons">
        <app-button
            icon="prev"
            :disabled="nav.prev?.encodedPath ? null : true"
            @click="onNavigate('prev')"
            :short="true"
            v-tippy="{ content: `${nav.prev?.track} ${nav.prev?.name}` }"
        />
        <app-button
            icon="next"
            :disabled="nav.next?.encodedPath ? null : true"
            @click="onNavigate('next')"
            :short="true"
            v-tippy="{ content: `${nav.next?.track} ${nav.next?.name}` }"
        />
        <autoplay-switch />
    </div>
</template>

<style scoped lang="scss">
.song-buttons {
    display: flex;
    align-items: center;

    gap: 1ch;
}
</style>
