<script setup lang="ts">
import { formatSeconds } from "@/formatters/numbers";
import { useWidgetStore } from "@/stores/widgetStore";
import axios from "axios";
import AppIcon from "Components/AppIcon/AppIcon.vue";
import AppWidget from "Components/Widget/AppWidget.vue";
import { push } from "notivue";
import { computed, onMounted, ref } from "vue";
const isLoading = ref(false);
const data = ref({});
const hasError = ref(false);
const widgetStore = useWidgetStore();
const shuffle = computed(() => widgetStore.toggles.song);
const fetchData = () => {
    isLoading.value = true;
    hasError.value = false;
    let url = "/api/widget/song";
    if (shuffle.value) url += "?shuffle=1";
    axios
        .get(url)
        .then(response => {
            if (response.data?.length > 0) {
                hasError.value = false;
                data.value = response.data;
            }
        })
        .catch(error => {
            console.error(error);
            push.error({
                title: error.code,
                message: error.response?.data?.message || error.message
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
    <app-widget
        :loading="isLoading"
        icon="music"
        :error="hasError"
        @refresh="fetchData()"
        :refresh-button="true"
        toggle-name="song"
        ajax-url="/api/music/search/songs"
    >
        <template #title>
            <span v-if="shuffle">Zufällige Songs</span>
            <span v-else>Neueste Songs</span>
        </template>
        <template #body>
            <nav class="stats" v-if="data?.length" aria-label="Links zu Songs">
                <router-link
                    class="stats__item stats__item--link"
                    v-for="song in data"
                    :key="song.id"
                    :to="{ name: 'song', params: { id: song.encodedPath } }"
                >
                    <img v-if="song.thumbnail" class="thumbnail" :src="song.thumbnail" alt="Thumbnail" />
                    <span class="stats__col">
                        <span class="stats__item-meta">
                            <span>
                                <app-icon name="music" />
                                {{ song.name }}
                            </span>
                        </span>
                        <span class="stats__item-row">
                            <span class="subitem highlight">
                                <app-icon name="artist" />
                                {{ song.artist.name }}
                            </span>
                            <span class="subitem pull-right">
                                <app-icon name="time" />
                                {{ formatSeconds(song.duration) }}
                            </span>
                        </span>
                    </span>
                </router-link>
            </nav>
        </template>
        <template #footer>
            <router-link class="btn primary" :to="{ name: 'songs' }">
                <app-icon name="music" />
                Alle Songs
            </router-link>
        </template>
    </app-widget>
</template>
