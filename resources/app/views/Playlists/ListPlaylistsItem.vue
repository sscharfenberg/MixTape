<script setup lang="ts">
import { formatDecimals, formatSeconds } from "@/formatters/numbers";
import { usePlaylistStore } from "@/stores/playlistStore";
import axios from "axios";
import AppIcon from "Components/AppIcon/AppIcon.vue";
import AppButton from "Components/Form/Button/AppButton.vue";
import LoadingSpinner from "Components/Loading/LoadingSpinner.vue";
import ModalWindow from "Components/Modal/ModalWindow.vue";
import { push } from "notivue";
import { computed, nextTick, ref, useTemplateRef } from "vue";
const playlistStore = usePlaylistStore();
const props = defineProps({
    pId: {
        type: String,
        required: true
    }
});
const list = computed({
    get: () => playlistStore.getPlaylist(props.pId),
    set: value => {
        playlistStore.setPlaylistName(props.pId, value);
    }
});
const name = ref(list.value.name);
const nameRef = useTemplateRef("nameRef");
const editing = ref(false);
const loading = ref(false);
const showModal = ref(false);
const onEdit = async val => {
    editing.value = val;
    if (val) {
        await nextTick();
        nameRef.value.focus();
    }
};
const onSave = () => {
    loading.value = true;
    axios
        .post("/api/playlists/edit", { id: props.pId, name: name.value })
        .then(response => {
            editing.value = false;
            list.value = response.data;
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
            loading.value = false;
        });
};
const onDeleteDialogue = () => {
    showModal.value = true;
};
const onDelete = () => {
    loading.value = true;
    showModal.value = false;
    axios
        .post("/api/playlists/delete", { id: list.value.id })
        .then(response => {
            editing.value = false;
            playlistStore.playlists = response.data;
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
</script>

<template>
    <div class="playlist">
        <div class="drag-handle"><app-icon name="drag" /></div>
        <router-link v-if="!editing" :to="{ name: 'playlist', params: { id: list.id } }">
            <span class="title"> {{ list.name }}</span>
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
        <form v-if="editing" @submit.prevent="onSave">
            <input
                class="form-input"
                type="text"
                v-model="name"
                ref="nameRef"
                :readonly="loading ? 'true' : null"
                aria-label="Name der Playlist"
            />
            <app-button v-if="!loading" icon="save" :short="true" @click.prevent="onSave" aria-label="Speichern" />
            <app-button
                v-if="!loading"
                icon="edit_off"
                :short="true"
                @click.prevent="onEdit(false)"
                aria-label="Editieren beenden"
            />
        </form>
        <app-button
            v-if="!editing && !loading"
            icon="edit"
            :short="true"
            @click="onEdit(true)"
            aria-label="Editieren"
        />
        <modal-window v-if="showModal" @close="showModal = false" :title="`Playlist ${list.name} löschen?`">
            Sind Sie sicher das Sie die Playlist
            <strong>{{ list.name }}</strong>
            löschen möchten?
            <template #footer>
                <app-button icon="close" text="Abbrechen" @click="showModal = false" />
                <app-button icon="delete" text="Löschen" type="primary" @click="onDelete" />
            </template>
        </modal-window>
        <app-button
            v-if="!editing && !loading"
            icon="delete"
            :short="true"
            @click="onDeleteDialogue"
            class="delete-btn"
            aria-label="Löschen"
        />
        <loading-spinner v-if="loading" :size="2" />
    </div>
</template>

<style lang="scss" scoped>
.form-input {
    max-width: 100%;
}

form {
    display: flex;
    align-items: center;
    flex-grow: 1;

    gap: 1ch;
}

.delete-btn {
    margin-left: 1ch;
}
</style>
