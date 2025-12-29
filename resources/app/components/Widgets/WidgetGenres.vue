<script setup lang="ts">
import { formatBytes, formatDecimals, formatSeconds } from "@/formatters/numbers";
import { useWidgetStore } from "@/stores/widgetStore";
import axios from "axios";
import AppIcon from "Components/AppIcon/AppIcon.vue";
import AppWidget from "Components/Widget/AppWidget.vue";
import { push } from "notivue";
import { computed, onMounted, ref } from "vue";
const isLoading = ref(false);
const data = ref(null);
const hasError = ref(false);
const widgetStore = useWidgetStore();
const shuffle = computed(() => widgetStore.toggles.genre);
const fetchData = () => {
    isLoading.value = true;
    hasError.value = false;
    let url = "/api/widget/genre";
    if (shuffle.value) url += "?shuffle=1";
    axios
        .get(url)
        .then(response => {
            if (response.data?.length > 1) {
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
        icon="genre"
        :error="hasError"
        @refresh="fetchData()"
        :refresh-button="true"
        toggle-name="genre"
        ajax-url="/api/music/search/genres"
    >
        <template #title>
            <span v-if="shuffle">Zufällige Genres</span>
            <span v-else>Populärste Genres</span>
        </template>
        <template #body>
            <nav class="stats" v-if="data?.length" aria-label="Links zu Genres">
                <router-link
                    v-for="genre in data"
                    :key="genre.id"
                    class="stats__item stats__item--link"
                    :to="{ name: 'genre', params: { id: genre.encodedName } }"
                >
                    <span class="stats__col">
                        <span class="stats__item-meta">
                            <span class="stats__item-hdl">{{ genre.name }}</span>
                            <span class="highlight">
                                <app-icon name="file" />
                                {{ formatBytes(genre.size) }}
                            </span>
                        </span>
                        <span class="stats__item-row">
                            <span class="subitem">
                                <app-icon name="time" />
                                {{ formatSeconds(genre.duration) }}
                            </span>
                            <span class="subitem pull-right">
                                {{ formatDecimals(genre.numSongs) }}
                                Dateien
                            </span>
                        </span>
                    </span>
                </router-link>
            </nav>
        </template>
        <template #footer>
            <router-link class="btn primary" :to="{ name: 'genres' }">
                <app-icon name="genre" />
                Alle Genres
            </router-link>
        </template>
    </app-widget>
</template>
