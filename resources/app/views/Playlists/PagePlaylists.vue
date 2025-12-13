<script setup lang="ts">
import { useAppStore } from "@/stores/app";
import { usePlaylistStore } from "@/stores/playlists";
import ShowError from "Components/Error/ShowError.vue";
import LoadingSpinner from "Components/Loading/LoadingSpinner.vue";
import ListPlaylists from "Views/Playlists/ListPlaylists.vue";
import NewPlaylist from "Views/Playlists/NewPlaylist.vue";
import axios from "axios";
import { push } from "notivue";
import { computed, onMounted } from "vue";
const app = useAppStore();
const pStore = usePlaylistStore();
const playlists = computed({
    get: () => pStore.playlists,
    set: value => {
        pStore.playlists = value;
    }
});
const fetchData = () => {
    app.loading = true;
    app.error = "";
    axios
        .get(`/api/playlists`)
        .then(response => {
            if (response.status === 200) {
                console.log(response.data);
                playlists.value = response.data;
            }
        })
        .catch(error => {
            console.error(error);
            push.error({
                title: error.code,
                message: error.response.data.message || error.message
            });
            app.error = error.response.data.message || error.message;
        })
        .finally(() => {
            app.loading = false;
        });
};
onMounted(() => {
    fetchData();
});
const onNew = val => (playlists.value = val);
</script>

<template>
    <new-playlist @new="onNew" />
    <div class="widget playlists">
        <div class="loading-spinner__outer" v-if="app.loading">
            <loading-spinner :size="8" />
        </div>
        <show-error v-else-if="app.error && !app.loading" @refresh="fetchData()" />
        <list-playlists v-if="!app.loading" />
    </div>
</template>

<style lang="scss" scoped>
.widget.playlists {
    min-height: 1lh;
    margin-top: 1lh;
}
</style>
