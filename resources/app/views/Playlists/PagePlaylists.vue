<script setup lang="ts">
import ShowError from "Components/Error/ShowError.vue";
import LoadingSpinner from "Components/Loading/LoadingSpinner.vue";
import ListPlaylists from "Views/Playlists/ListPlaylists.vue";
import NewPlaylist from "Views/Playlists/NewPlaylist.vue";
import axios from "axios";
import { push } from "notivue";
import { onMounted, ref } from "vue";
const isLoading = ref(false);
const hasError = ref(false);
const playlists = ref([]);
const fetchData = () => {
    isLoading.value = true;
    hasError.value = false;
    axios
        .get(`/api/playlists`)
        .then(response => {
            if (response.status === 200) {
                playlists.value = response.data;
            }
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
onMounted(() => {
    fetchData();
});
</script>

<template>
    <new-playlist @new="fetchData" />
    <div class="widget playlists">
        <div class="loading-spinner__outer" v-if="isLoading">
            <loading-spinner :size="8" />
        </div>
        <show-error v-else-if="hasError && !isLoading" @refresh="fetchData()" />
        <list-playlists v-if="!isLoading && playlists.length > 0" :playlists="playlists" />
    </div>
</template>

<style lang="scss" scoped>
.widget.playlists {
    margin-top: 1lh;
}
</style>
