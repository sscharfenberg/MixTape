<script setup lang="ts">
import { formatSeconds } from "@/formatters/numbers";
import { usePlayerStore } from "@/stores/playerStore";
import { usePlaylistStore } from "@/stores/playlistStore";
import { useQueueStore } from "@/stores/queueStore";
import axios from "axios";
import AppIcon from "Components/AppIcon/AppIcon.vue";
import AppButton from "Components/Form/Button/AppButton.vue";
import LoadingSpinner from "Components/Loading/LoadingSpinner.vue";
import ModalWindow from "Components/Modal/ModalWindow.vue";
import { push } from "notivue";
import { computed, ref } from "vue";
import { useRoute } from "vue-router";
const showModal = ref(false);
const loading = ref(false);
const route = useRoute();
const playerStore = usePlayerStore();
const queueStore = useQueueStore();
const props = defineProps({
    id: {
        type: String,
        required: true
    }
});
const playlistStore = usePlaylistStore();
const s = computed(() => playlistStore.getSong(props.id));
const emit = defineEmits(["play"]);
const onDelete = () => {
    console.log("delete " + props.id);
    loading.value = true;
    showModal.value = false;
    axios
        .post(`/api/playlists/${route.params.id}/delete/${props.id}`)
        .then(response => {
            playlistStore.detailedPlaylist = response.data;
        })
        .catch(error => {
            console.error(error);
            push.error({
                title: error.code,
                message: error.response.data.message || error.message
            });
        })
        .finally(() => {
            loading.value = false;
        });
};
const onPlay = () => {
    queueStore.currentQueueIndex = playerStore.shuffle
        ? queueStore.shuffledQueue.indexOf(s.value.encodedPath)
        : queueStore.sortedQueue.indexOf(s.value.encodedPath);
    queueStore.currentQueuePath = s.value.encodedPath;
    emit("play", s.value.encodedPath);
};
</script>

<template>
    <div class="playlist-song" :class="{ active: s.nowPlaying }">
        <div class="playlist-song__drag-handle"><app-icon name="drag" /></div>
        <div class="playlist-song__data">
            <div class="playlist-song__name">
                <app-icon name="playlist_entry" />
                {{ s.song }}
            </div>
            <div class="playlist-song__duration">
                <app-icon name="time" />
                {{ formatSeconds(s.duration) }}
            </div>
            <div class="playlist-song__artist">
                <app-icon name="artist" />
                {{ s.artist }}
            </div>
            <div class="playlist-song__album">
                <app-icon name="album" />
                {{ s.album }}
            </div>
        </div>
        <div class="playlist-song__actions">
            <app-button
                icon="play"
                :short="true"
                type="primary"
                v-tippy="{ content: `Playlist ab dem Song ${s.song} abspielen` }"
                @click="onPlay"
                :aria-label="`Playlist ab dem Song ${s.song} abspielen`"
            />
            <app-button
                icon="delete"
                :short="true"
                @click="showModal = true"
                aria-label="Löschen"
                v-tippy="{ content: 'Löschen' }"
            />
            <modal-window v-if="showModal" @close="showModal = false" :title="`Song von der Playlist entfernen?`">
                Sind Sie sicher das Sie den Song
                <strong>"{{ s.song }}"</strong>
                von der Playlist entfernen möchten?
                <template #footer>
                    <app-button icon="close" text="Abbrechen" @click="showModal = false" />
                    <app-button icon="delete" text="Löschen" type="primary" @click="onDelete" />
                </template>
            </modal-window>
            <loading-spinner v-if="loading" :size="2" />
        </div>
    </div>
</template>
