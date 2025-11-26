<script setup lang="ts">
import AppIcon from "Components/AppIcon/AppIcon.vue";

const props = defineProps({
    name: {
        type: String,
        required: true
    },
    label: {
        type: String,
        required: true
    },
    icon: {
        type: String,
        required: true
    },
    selected: {
        type: Boolean,
        default: false
    }
});
</script>

<template>
    <li>
        <input
            :id="'theme' + name"
            name="theme"
            type="radio"
            :value="name"
            @change="$emit('radio', props.name)"
            :checked="selected"
        />
        <label :for="'theme' + name">
            {{ label }}
            <app-icon :name="icon" />
        </label>
    </li>
</template>

<style scoped lang="scss">
@use "sass:map";
@use "Abstracts/colors" as c;
@use "Abstracts/sizes" as s;

label {
    display: flex;
    align-items: center;

    padding: map.get(s.$footer, "themeswitch-padding");
    border: map.get(s.$footer, "themeswitch-border") solid map.get(c.$footer, "themeswitch-border");
    gap: 4px;

    background-color: map.get(c.$footer, "themeswitch-background");
    color: map.get(c.$footer, "themeswitch-surface");
    border-radius: map.get(s.$footer, "themeswitch-radius");

    line-height: 1;

    cursor: pointer;
}

input {
    display: none;
}

input:checked + label {
    background-color: map.get(c.$footer, "themeswitch-background-selected");
    color: map.get(c.$footer, "themeswitch-surface-selected");
    border-color: map.get(c.$footer, "themeswitch-surface-selected");
}
</style>
