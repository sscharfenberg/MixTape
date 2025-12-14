<script setup lang="ts">
import { usePlayerStore } from "@/stores/playerStore";
import axios from "axios";
import ShowError from "Components/Error/ShowError.vue";
import LoadingSpinner from "Components/Loading/LoadingSpinner.vue";
import AudioPlayer from "Components/Player/AudioPlayer.vue";
import { nowPlaying } from "Components/Player/useNowPlaying";
import { push } from "notivue";
import { ref, watch } from "vue";
import { useRoute } from "vue-router";
import AudiobookMetaData from "./AudiobookMetaData.vue";
import AudiobookTitle from "./AudiobookTitle.vue";
import { getNavigation } from "./useNavigation";
const isLoading = ref(false);
const data = ref(null);
const hasError = ref(false);
const route = useRoute();
const store = usePlayerStore();
const trackUrl = ref("");
const currentName = ref("");
const fetchData = () => {
    data.value = null;
    isLoading.value = true;
    hasError.value = false;
    axios
        .get(`/api/audiobooks/${route.params.id}`)
        .then(response => {
            data.value = response.data;
        })
        .catch(error => {
            console.error(error);
            push.error({
                title: error.code,
                message: error.response.data.message || error.message
            });
            hasError.value = true;
        })
        .finally(() => {
            isLoading.value = false;
        });
};
watch(() => route.params.id, fetchData, { immediate: true });
const onPlay = async (value: string) => {
    store.setAudiobookBookmark(route.params.id, value, 0);
    axios
        .get(`/api/audiobooks/play/${value}`)
        .then(response => {
            trackUrl.value = response.data.path;
            currentName.value = response.data.name;
        })
        .catch(error => {
            console.error(error);
            push.error({
                title: error.code,
                message: error.response.data.message || error.message
            });
            hasError.value = true;
        })
        .finally(() => {
            console.log("xhr finished.");
        });
};
const onEnded = async () => {
    const nav = getNavigation(data.value.tracks, store.getAudiobookBookmark(data.value.encodedName)?.trackEncodedPath);
    if (nav.next?.encodedPath) {
        await onPlay(nav.next.encodedPath);
        store.setAudiobookBookmark(route.params.id, nav.next.encodedPath, 0);
        nowPlaying(nav.next);
    }
};
</script>

<template>
    <section class="widget">
        <div class="loading-spinner__outer" v-if="isLoading">
            <loading-spinner :size="8" />
        </div>
        <show-error v-else-if="hasError && !isLoading" @refresh="fetchData()" />
        <div v-if="!hasError && !isLoading && data?.tracks?.length" class="audiobook">
            <audiobook-title
                :title="data.name"
                :cover="data.cover"
                :tracks="data.tracks"
                :book-encoded-name="route.params.id"
                @play="onPlay"
            />
            <audio-player
                v-if="trackUrl && currentName"
                :src="trackUrl"
                :title="currentName"
                :autoplay="store.autoplay"
                @player-ended="onEnded"
            />
            <audiobook-meta-data :book="data" />
        </div>
    </section>
</template>

<style lang="scss" scoped>
.audiobook {
    display: flex;
    flex-direction: column;

    gap: 1ch;
}

.details-metadata {
    margin: 0;
}
</style>
