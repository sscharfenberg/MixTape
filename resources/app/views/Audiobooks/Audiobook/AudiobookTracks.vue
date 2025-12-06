<script setup lang="ts">
import { usePlayerStore } from "@/stores/player";
import MonoSelect from "Components/Select/MonoSelect.vue";
import { computed, onMounted } from "vue";
const store = usePlayerStore();
const props = defineProps({
    tracks: {
        type: Array,
        required: true
    },
    bookEncodedName: {
        type: String,
        required: true
    }
});
const emit = defineEmits<{
    (e: "play", value: string): void;
}>();
const trackOptions = props.tracks
    .sort((a, b) => a.track - b.track)
    .map(track => {
        return { label: track.name, value: track.encodedPath };
    });
const onChange = (value: string) => {
    store.setAudiobookBookmark(props.bookEncodedName, value, 0);
    emit("play", value);
};
const currentTrack = computed(() => store.getAudiobookBookmark(props.bookEncodedName));
const currentChapter = computed(() => {
    const chapter = currentTrack.value;
    const path = chapter.trackEncodedPath;
    const timestamp = chapter.timestamp;
    const t = props.tracks.find(track => track.encodedPath === path);
    return t ? `${t.track} - ${t.name}` : null;
});
onMounted(() => {
    if (currentTrack.value.trackEncodedPath) {
        onChange(currentTrack.value.trackEncodedPath);
    }
});
</script>

<template>
    <ul class="bookmark">
        <li class="bookmark__current" v-if="currentTrack">Aktuelles Kapitel {{ currentChapter }}</li>
        <li class="bookmark__go">
            Gehe zu Kapitel
            <mono-select
                :options="trackOptions"
                :selected="currentTrack ? currentTrack.trackEncodedPath : null"
                placeholder="Bitte wählen"
                @change="onChange"
            />
        </li>
    </ul>
</template>

<style lang="scss" scoped>
@use "sass:map";
@use "Abstracts/colors" as c;
@use "Abstracts/sizes" as s;
@use "Abstracts/mixins" as m;

.bookmark {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(min(320px, 100%), 1fr));

    padding: 0;
    margin: 0;

    gap: 1ch;

    list-style: none;

    &__go,
    &__current {
        display: flex;
        align-items: center;

        height: 100%;

        gap: 1ch;

        background-color: map.get(c.$main, "row1-background");
        color: map.get(c.$main, "row1-surface");

        list-style: none;

        text-decoration: none;

        @include m.mqset(
            "padding",
            #{map.get(s.$main, "row-padding", "base")},
            #{map.get(s.$main, "row-padding", "portrait")},
            #{map.get(s.$main, "row-padding", "landscape")},
            #{map.get(s.$main, "row-padding", "desktop")}
        );
    }
}
</style>
