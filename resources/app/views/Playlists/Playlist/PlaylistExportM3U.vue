<script setup lang="ts">
import AppIcon from "Components/AppIcon/AppIcon.vue";
import AppButton from "Components/Form/Button/AppButton.vue";
import FormRow from "Components/Form/Row/FormRow.vue";
import MonoSelect from "Components/Form/Select/MonoSelect.vue";
import LoadingSpinner from "Components/Loading/LoadingSpinner.vue";
import ModalWindow from "Components/Modal/ModalWindow.vue";
import { computed, PropType, ref } from "vue";
import { useRoute } from "vue-router";
const showModal = ref(false);
const route = useRoute();
defineProps({
    name: {
        type: String,
        required: true
    },
    exportType: {
        type: String as PropType<"simple" | "extended">,
        required: true,
        validator: prop => ["simple", "extended"].includes(prop)
    }
});
const emit = defineEmits(["closepopover"]);
const onShowModal = () => {
    showModal.value = true;
    emit("closepopover");
};
const encodings = [
    { label: "UTF-8", value: "UTF-8" },
    { label: "Windows-1252", value: "Windows-1252" }
];
const encoding = ref(encodings[0].value);
const pathPrefix = ref("/Volumes/debshare/music/");
const isLoading = ref(false);
const onEncodingChange = (value: string) => {
    encoding.value = value;
};
const csrf = computed(() => document.querySelector("meta[name='csrf-token']").getAttribute("content"));
const onExport = () => {
    document.downloadPlaylist.submit();
    showModal.value = false;
};
</script>

<template>
    <button @click="onShowModal">
        <app-icon name="playlist" /> {{ exportType === "simple" ? "Einfache" : "Erweiterte" }} .m3u
    </button>
    <modal-window v-if="showModal" @close="showModal = false" :title="`Playlist ${name} exportieren`">
        <div class="loading-wrapper"><loading-spinner v-if="isLoading" :size="5" /></div>
        <p v-if="!isLoading">
            Export der Playlist in "{{ exportType === "simple" ? "Einfachem" : "Erweitertem" }}"
            <strong>.M3U</strong> Format.
        </p>
        <form
            v-if="!isLoading"
            name="downloadPlaylist"
            class="form-col"
            :action="`/api/playlists/${route.params.id}/export-m3u`"
            METHOD="POST"
        >
            <form-row label="Encoding" ref-id="encoding" :required="true">
                <template #input>
                    <mono-select :options="encodings" :selected="encoding" @change="onEncodingChange" />
                    <input type="hidden" name="encoding" v-model="encoding" />
                </template>
            </form-row>
            <form-row label="Pfad Präfix" ref-id="rootpath" :required="true">
                <template #input>
                    <input class="form-input" name="prefixPath" type="text" v-model="pathPrefix" />
                    (Wird vor den eigentlichen Dateipfad gestellt)
                </template>
            </form-row>
            <input type="hidden" name="type" :value="exportType" />
            <input type="hidden" name="_token" :value="csrf" />
        </form>
        <template #footer>
            <app-button icon="close" text="Abbrechen" @click="showModal = false" :disabled="isLoading" />
            <app-button icon="download" text="Exportieren" type="primary" @click="onExport" :disabled="isLoading" />
        </template>
    </modal-window>
</template>

<style lang="scss" scoped>
p {
    margin-top: 0;
}

.loading-wrapper {
    display: flex;
    justify-content: center;
}
</style>
