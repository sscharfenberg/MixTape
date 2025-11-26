<script setup lang="ts">
import { formatSeconds } from "@/formatters/numbers";
import axios from "axios";
import AppIcon from "Components/AppIcon/AppIcon.vue";
import AppWidget from "Components/Widget/AppWidget.vue";
import { format } from "date-fns";
import { push } from "notivue";
import { onMounted, ref } from "vue";
const isLoading = ref(false);
const data = ref({});
const hasError = ref(false);
const fetchData = () => {
    isLoading.value = true;
    hasError.value = false;
    axios
        .get("/api/stats/songs")
        .then(response => {
            if (response.data && response.data.longest) {
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
    <app-widget :loading="isLoading" icon="music" :error="hasError" @refresh="fetchData()" :refresh-button="true">
        <template #title> Songs </template>
        <template #body>
            <nav class="stats" v-if="data.longest">
                <router-link
                    class="stats__item stats__item--link"
                    :to="{ name: 'song', params: { id: data.longest.encodedPath } }"
                >
                    <span class="stats__item-meta">
                        <span class="stats__item-hdl">Längstes Lied:</span>
                        <span class="highlight">
                            <app-icon name="time" />
                            {{ formatSeconds(data.longest.duration) }}
                        </span>
                    </span>
                    <span class="stats__item-row">
                        <span class="subitem">
                            <app-icon name="artist" />
                            {{ data.longest.artist.name }}
                        </span>
                        <span class="subitem">
                            <app-icon name="music" />
                            {{ data.longest.name }}
                        </span>
                    </span>
                </router-link>
                <router-link
                    class="stats__item stats__item--link"
                    :to="{ name: 'song', params: { id: data.newest.encodedPath } }"
                >
                    <span class="stats__item-meta">
                        <span class="stats__item-hdl">Zuletzt geändert:</span>
                        <span class="highlight">
                            <app-icon name="datetime" />
                            {{ format(data.newest.modifiedAt, "dd.MM.yyyy HH:ii") }}
                        </span>
                    </span>
                    <span class="stats__item-row">
                        <span class="subitem">
                            <app-icon name="artist" />
                            {{ data.newest.artist.name }}
                        </span>
                        <span class="subitem">
                            <app-icon name="music" />
                            {{ data.newest.name }}
                        </span>
                    </span>
                </router-link>
                <router-link
                    class="stats__item stats__item--link"
                    :to="{ name: 'song', params: { id: data.oldest.encodedPath } }"
                >
                    <span class="stats__item-meta">
                        <span class="stats__item-hdl">Älteste Datei:</span>
                        <span class="highlight">
                            <app-icon name="datetime" />
                            {{ format(data.oldest.modifiedAt, "dd.MM.yyyy HH:ii") }}
                        </span>
                    </span>
                    <span class="stats__item-row">
                        <span class="subitem">
                            <app-icon name="artist" />
                            {{ data.oldest.artist.name }}
                        </span>
                        <span class="subitem">
                            <app-icon name="music" />
                            {{ data.oldest.name }}
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
