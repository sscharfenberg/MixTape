<script setup lang="ts">
import { formatDecimals, formatSeconds } from "@/formatters/numbers";
import AppIcon from "Components/AppIcon/AppIcon.vue";
import AppWidget from "Components/Widget/AppWidget.vue";
import axios from "axios";
import { push } from "notivue";
import { onMounted, ref } from "vue";
const isLoading = ref(false);
const data = ref({});
const hasError = ref(false);
const fetchData = () => {
    isLoading.value = true;
    hasError.value = false;
    axios
        .get("/api/stats/albums")
        .then(response => {
            if (response.data && response.data.length > 0) {
                hasError.value = false;
                data.value = response.data;
            }
        })
        .catch(error => {
            console.error(error);
            push.error({
                title: error.code,
                message: error.message
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
    <app-widget :loading="isLoading" icon="album" :error="hasError" @refresh="fetchData()" :refresh-button="true">
        <template #title> Zufällige Alben </template>
        <template #body>
            <nav class="stats" v-if="data?.length">
                <router-link
                    v-for="album in data"
                    :key="album.id"
                    class="stats__item stats__item--link"
                    :to="{ name: 'album', params: { id: album.id } }"
                >
                    <span class="stats__item-meta">
                        <span class="stats__item-hdl">
                            {{ album.name }}
                        </span>
                    </span>
                    <span class="stats__item-row">
                        <app-icon name="artist" />
                        {{ album.artist.name }}
                    </span>
                    <span class="stats__item-row">
                        <span class="subitem">
                            <app-icon name="time" />
                            {{ formatSeconds(album.duration) }}
                        </span>
                        <span class="subitem pull-right">
                            {{ formatDecimals(album.numSongs) }}
                            Songs
                        </span>
                    </span>
                </router-link>
            </nav>
        </template>
        <template #footer>
            <router-link class="btn primary" :to="{ name: 'albums' }">
                <app-icon name="music" />
                Alle Alben
            </router-link>
        </template>
    </app-widget>
</template>
