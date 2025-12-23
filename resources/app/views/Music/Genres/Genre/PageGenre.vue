<script setup lang="ts">
import { useGenreStore } from "@/stores/genreStore";
import axios from "axios";
import ShowError from "Components/Error/ShowError.vue";
import LoadingSpinner from "Components/Loading/LoadingSpinner.vue";
import TabbedNavigation from "Components/TabbedNavigation/TabbedNavigation.vue";
import { push } from "notivue";
import GenreAlbumsTable from "Views/Music/Genres/Genre/GenreAlbumsTable.vue";
import GenreArtistsTable from "Views/Music/Genres/Genre/GenreArtistsTable.vue";
import GenreMetaData from "Views/Music/Genres/Genre/GenreMetaData.vue";
import GenreSongsTable from "Views/Music/Genres/Genre/GenreSongsTable.vue";
import GenreTitle from "Views/Music/Genres/Genre/GenreTitle.vue";
import { ref, watch } from "vue";
import { useRoute } from "vue-router";
const genreStore = useGenreStore();
const route = useRoute();
const isLoading = ref(false);
const data = ref(null);
const hasError = ref(false);
const currentTabIndex = ref(0);
const fetchData = () => {
    data.value = null;
    isLoading.value = true;
    hasError.value = false;
    axios
        .get(`/api/music/genres/${route.params.id}`)
        .then(response => {
            data.value = response.data;
            currentTabIndex.value = genreStore.getCurrentTabIndex(data.value.genre.encodedName);
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
const onTabChange = (val: number) => {
    currentTabIndex.value = val;
    genreStore.setCurrentTabIndex(data.value.genre.encodedName, val);
};
</script>

<template>
    <section class="widget">
        <div class="loading-spinner__outer" v-if="isLoading">
            <loading-spinner :size="8" />
        </div>
        <show-error v-else-if="hasError && !isLoading" @refresh="fetchData()" />
        <div v-else class="genre">
            <genre-title :title="data.genre.name" />
            <genre-meta-data :genre="data.genre" />
            <tabbed-navigation
                name="tabbed-navigation-genre"
                :tabs="[
                    {
                        idx: 0,
                        label: `Künstler (${data.artists.length})`,
                        icon: 'artist',
                        checked: currentTabIndex === 0
                    },
                    { idx: 1, label: `Alben (${data.albums.length})`, icon: 'album', checked: currentTabIndex === 1 },
                    { idx: 2, label: `Songs (${data.songs.length})`, icon: 'music', checked: currentTabIndex === 2 }
                ]"
                @tabchange="onTabChange"
            >
                <genre-artists-table v-show="currentTabIndex === 0" :artists="data.artists" />
                <genre-albums-table v-show="currentTabIndex === 1" :albums="data.albums" />
                <genre-songs-table v-show="currentTabIndex === 2" :songs="data.songs" />
            </tabbed-navigation>
        </div>
    </section>
</template>

<style lang="scss" scoped>
section {
    min-height: 120px;
}

.genre {
    display: flex;
    flex-direction: column;

    gap: 2ch;
}
</style>
