<script setup lang="ts">
import ThemeSwitch from "./ThemeSwitch.vue";
</script>

<template>
    <footer>
        <theme-switch />
        <address>Handcrafted with ❤ by Sven Scharfenberg</address>
    </footer>
</template>

<style scoped lang="scss">
@use "sass:map";
@use "Abstracts/mixins" as m;
@use "Abstracts/sizes" as s;

footer {
    display: flex;
    justify-content: space-between;
    flex-direction: column;

    gap: 4px;

    @include m.mq("portrait") {
        align-items: center;
        flex-direction: row;
    }
}
</style>
