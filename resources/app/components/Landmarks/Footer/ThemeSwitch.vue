<script setup lang="ts">
import { computed, onMounted } from "vue";
import ThemeSwitchItem from "./ThemeSwitchItem.vue";
const colorScheme = document.querySelector("meta[name='color-scheme']");
if (!colorScheme) {
    throw new Error("Meta tag with name='color-scheme' not found");
}
const updateMeta = (val: string) => {
    colorScheme.setAttribute("content", val);
};
const theme = computed({
    get() {
        return localStorage.getItem("theme") || colorScheme.getAttribute("content") || "light dark";
    },
    set(val) {
        updateMeta(val);
        localStorage.setItem("theme", val);
    }
});
onMounted(() => {
    if (colorScheme.getAttribute("content") !== theme.value) updateMeta(theme.value);
});
const options = [
    { value: "dark", label: "Dunkel", icon: "dark" },
    { value: "light", label: "Hell", icon: "light" },
    { value: "light dark", label: "System", icon: "system" }
];
</script>

<template>
    <ul aria-label="Temp Theme Switch">
        <theme-switch-item
            v-for="option in options"
            :key="option.value"
            :name="option.value"
            :label="option.label"
            :icon="option.icon"
            :selected="theme === option.value"
            @radio="theme = option.value"
        />
    </ul>
</template>

<style lang="scss" scoped>
@use "sass:map";
@use "Abstracts/sizes" as s;
@use "Abstracts/mixins" as m;

ul {
    display: flex;

    padding: 0;
    margin: 0;

    list-style: none;

    @include m.mqset(
        "gap",
        #{map.get(s.$footer, "theme-gap", "base")},
        #{map.get(s.$footer, "theme-gap", "portrait")},
        #{map.get(s.$footer, "theme-gap", "landscape")}
    );
}
</style>
