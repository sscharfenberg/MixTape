<script setup lang="ts">
import { useAudiobookStore } from "@/stores/audiobookStore";
import MonoSelect from "Components/Form/Select/MonoSelect.vue";
import { computed } from "vue";
const store = useAudiobookStore();
const props = defineProps({
    tracks: {
        type: Array<Track>,
        required: true
    },
    bookEncodedName: {
        type: String,
        required: true
    }
});
interface Track {
    disc: number;
    discs: number;
    track: number;
    name: string;
    encodedPath: string;
}
const emit = defineEmits<{
    (e: "play", value: string): void;
}>();
const trackOptions = props.tracks.map((t: Track) => {
    let discs = "";
    let track = "";
    if (t.discs > 1) {
        discs = "Disc " + t.disc + "/" + t.discs + " - ";
    }
    if (props.tracks.length > 1) {
        track = "Track " + t.track + " - ";
    }
    return { label: discs + track + t.name, value: t.encodedPath };
});
const onChange = (value: string) => {
    if (value) {
        store.setAudiobookBookmark(props.bookEncodedName, value, 0);
        emit("play", value);
    } else {
        store.clearAudiobookBookmark(props.bookEncodedName);
    }
};
const currentTrack = computed(() => store.getAudiobookBookmark(props.bookEncodedName));
</script>

<template>
    <div class="bookmark">
        Kapitel
        <mono-select
            :options="trackOptions"
            :selected="currentTrack ? currentTrack.trackEncodedPath : null"
            placeholder="Bitte wählen"
            @change="onChange"
        />
    </div>
</template>

<style lang="scss" scoped>
@use "sass:map";
@use "Abstracts/colors" as c;
@use "Abstracts/sizes" as s;
@use "Abstracts/mixins" as m;

.bookmark {
    display: flex;
    align-items: center;

    flex-grow: 1;

    height: 100%;
    gap: 1ch;

    background-color: map.get(c.$main, "row2-background");
    color: map.get(c.$main, "row2-surface");

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
</style>
