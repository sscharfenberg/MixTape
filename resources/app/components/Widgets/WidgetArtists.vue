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
const shuffle = computed(() => widgetStore.toggles.artist);
const fetchData = () => {
    isLoading.value = true;
    hasError.value = false;
    let url = "/api/widget/artist";
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
        toggle-name="artist"
        ajax-url="/api/music/search/artists"
    >
        <template #title>
            <span v-if="shuffle">Zufällige Künstler</span>
            <span v-else>Neueste Künstler</span>
        </template>
        <template #body>
            <nav class="stats" v-if="data?.length && !hasError" aria-label="Links zu Künstlern">
                <router-link
                    v-for="artist in data"
                    :key="artist.id"
                    class="stats__item stats__item--link"
                    :to="{ name: 'artist', params: { id: artist.encodedName } }"
                >
                    <span class="stats__col">
                        <span class="stats__item-meta">
                            <span class="highlight">
                                <app-icon name="artist" />
                                {{ artist.name }}
                            </span>
                            <span class="subitem" v-tippy="{ content: 'Alben des Künstlers' }">
                                <app-icon name="album" />
                                {{ artist.albums.length }}
                            </span>
                        </span>
                        <span class="stats__item-row">
                            <span class="subitem highlight">
                                <app-icon name="time" />
                                {{ formatSeconds(artist.songsDuration) }}
                            </span>
                            <span class="subitem pull-right">
                                {{ formatDecimals(artist.numSongs) }}
                                Songs
                            </span>
                        </span>
                    </span>
                </router-link>
            </nav>
        </template>
        <template #footer>
            <router-link class="btn primary" :to="{ name: 'artists' }">
                <app-icon name="artist" />
                Alle Künstler
            </router-link>
        </template>
    </app-widget>
</template>
