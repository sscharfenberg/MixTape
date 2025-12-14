<script setup lang="ts">
import { usePlayerStore } from "@/stores/playerStore";
import AppIcon from "Components/AppIcon/AppIcon.vue";
import { computed } from "vue";
const store = usePlayerStore();
const autoPlay = computed({
    get: () => store.autoplay,
    set: newValue => {
        store.autoplay = newValue;
    }
});
</script>

<template>
    <div class="pit">
        <input type="checkbox" name="autoplay" id="autoPlay" value="on" v-model="autoPlay" />
        <label for="autoPlay">
            <span class="knob">
                <app-icon name="autoplay" />
                <app-icon name="pause" />
            </span>
        </label>
    </div>
</template>

<style scoped lang="scss">
@use "sass:map";
@use "Abstracts/colors" as c;
@use "Abstracts/sizes" as s;
@use "Abstracts/timings" as ti;

.pit {
    display: flex;

    width: 60px;
    height: 40px;

    border: map.get(s.$button, "border") solid map.get(c.$button, "default", "border");

    background-color: map.get(c.$button, "default", "background");
    color: map.get(c.$button, "default", "surface");
    border-radius: map.get(s.$main, "switch-pit");

    &:hover {
        background-color: map.get(c.$button, "default", "background-hover");
        color: map.get(c.$button, "default", "surface-hover");
    }
}

label {
    display: flex;
    position: relative;

    flex-grow: 1;

    cursor: pointer;
}

.knob {
    display: flex;
    position: absolute;
    top: 0;
    bottom: 0;
    left: 2px;
    align-items: center;

    transition:
        left map.get(ti.$timings, "fast") linear,
        background-color map.get(ti.$timings, "fast") linear,
        color map.get(ti.$timings, "fast") linear;

    .icon {
        box-sizing: content-box;
        padding: 2px;

        background: map.get(c.$main, "autoplay-knob-background");
        border-radius: 50%;
    }
}

input[type="checkbox"] {
    display: none;

    + label .icon.autoplay {
        display: none;
    }

    + label .icon.pause {
        display: block;
    }

    &:checked + label .icon.autoplay {
        display: block;
    }

    &:checked + label .icon.pause {
        display: none;
    }

    &:checked + label .knob {
        left: 26px;

        .icon {
            background-color: map.get(c.$button, "primary", "background");
            color: map.get(c.$button, "primary", "surface");
        }
    }
}
</style>
