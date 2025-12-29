<script setup lang="ts">
import { formatDecimals, formatSeconds } from "@/formatters/numbers";
import { useWidgetStore } from "@/stores/widgetStore";
import AppIcon from "Components/AppIcon/AppIcon.vue";
import AppWidget from "Components/Widget/AppWidget.vue";
import axios from "axios";
import { push } from "notivue";
import { computed, onMounted, ref } from "vue";
const isLoading = ref(false);
const data = ref({});
const hasError = ref(false);
const widgetStore = useWidgetStore();
const shuffle = computed(() => widgetStore.toggles.album);
const fetchData = () => {
    isLoading.value = true;
    hasError.value = false;
    let url = "/api/widget/album";
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
        icon="album"
        :error="hasError"
        @refresh="fetchData()"
        :refresh-button="true"
        toggle-name="album"
        ajax-url="/api/music/search/albums"
    >
        <template #title>
            <span v-if="shuffle">Zufällige Alben</span>
            <span v-else>Neueste Alben</span>
        </template>
        <template #body>
            <nav class="stats" v-if="data?.length" aria-label="Links zu Alben">
                <router-link
                    v-for="album in data"
                    :key="album.id"
                    class="stats__item stats__item--link"
                    :to="{ name: 'album', params: { id: album.id } }"
                >
                    <img v-if="album.thumbnail" class="thumbnail" :src="album.thumbnail" alt="Thumbnail" />
                    <span class="stats__col">
                        <span class="stats__item-meta">
                            <span class="stats__item-hdl">
                                <app-icon name="album" />
                                {{ album.name }}
                            </span>
                            <span class="subitem">
                                <app-icon name="time" />
                                {{ formatSeconds(album.duration) }}
                            </span>
                        </span>
                        <span class="stats__item-row">
                            <span class="highlight">
                                <app-icon name="artist" />
                                {{ album.artist.name }}
                            </span>
                            <span class="subitem pull-right">
                                {{ formatDecimals(album.numSongs) }}
                                Songs
                            </span>
                        </span>
                    </span>
                </router-link>
            </nav>
        </template>
        <template #footer>
            <router-link class="btn primary" :to="{ name: 'albums' }">
                <app-icon name="album" />
                Alle Alben
            </router-link>
        </template>
    </app-widget>
</template>
